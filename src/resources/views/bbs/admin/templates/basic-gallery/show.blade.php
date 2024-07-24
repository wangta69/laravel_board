@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic-notice show'>
        <h1>
            {{ $article->table->name }}
        </h1>

        <table class="table">
            <colgroup>
                <col width='120' />
                <col width='*' />
                <col width='120' />
                <col width='*' />
            </colgroup>
            <thead>
                <tr>
                    <th>@lang('bbs::messages.bbs.title.title')</th>
                    <td colspan='3'>{{ $article->title }}</td>
                </tr>
                <tr>
                    <th>@lang('bbs::messages.bbs.title.writer')</th>
                    <td>{{ $article->writer }} ({{ date('Y-m-d H:i', strtotime($article->created_at)) }})</td>
                    <th>@lang('bbs::messages.bbs.title.views')</th>
                    <td>{{ number_format($article->hit) }}</td>
                </tr>
                <tr>
                    <th>@lang('bbs::messages.bbs.title.attached')</th>
                    <td colspan='3'>
                        <ul class="link">
                            @foreach ($article->files as $file)
                            <!-- 파일 다운로드 경로 등을 넣으세요.. -->
                            <li>{{ link_to_route('bbs.file.download', $file->file_name, $file->id) }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </thead>
        </table>

        <div class='body'>
            <div class="content">
            {!! nl2br($article->content) !!}
            </div>
        </div>

        <div class='btn-area text-right'>
            @if ($article->isOwner(Auth::user()) || $isAdmin)
            <a href="{{ route('bbs.admin.tbl.edit', [$cfg->table_name, $article->id]) }}" class='btn btn-primary btn-sm'>@lang('bbs::messages.bbs.button.modify')</a>
            <button type="button" class="btn btn-danger btn-sm btn-delete">@lang('bbs::messages.bbs.button.delete')</button>
            @endif
            <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>@lang('bbs::messages.bbs.button.list')</a>

        </div>
    </div>
    @if ($cfg->enable_comment == 1)
    @include ('bbs.admin.templates.'.$cfg->skin.'.comment')
    @endif
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style') 
    @include ('bbs.admin.templates.'.$cfg->skin.'.css.style')
</style>
@stop

@section ('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js?v=1"></script>
<script>
  BBS.tbl_name = "{{$cfg->table_name}}";
  BBS.article_id = {{$article-> id}};
  $(function(){
    $('.btn-delete').on('click', function(){
      if(confirm('@lang('bbs::messages.message.confirm-delete')')) {
        BBS.ajaxroute('delete', {
          'name': 'bbs.admin.tbl.destroy', 
          'params[0]': '{{$cfg->table_name}}', 
          'params[1]': {{$article->id}}, 
        }, {}, function(resp) {
          if(resp.error) {
            alert(resp.error)
          } else {
              var url = "{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}"
              location.href= url;
          }
      })
      }
    })
  })
</script>
@stop
