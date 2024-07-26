@extends($cfg->extends)
@section ($cfg->section)
<div class="container">
  <div class="bbs show">
    <h1 class='title'>{{ $cfg->name }}</h1>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.title')</div>
          <div class="col-10">{{ $article->title }}</div>
        </div> 
        <div class="row mt-2">
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.writer')</div>
          <div class="col-4">{{ $article->writer }} ({{ date('Y-m-d H:i', strtotime($article->created_at)) }})</div>
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.views')</div>
          <div class="col-4">{{ number_format($article->hit) }}</div>
        </div> 
        <div class="row mt-2">
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.attached')</div>
          <div class="col-10">
            <ul class="link">
              @foreach ($article->files as $file)
              <li>{{ link_to_route('bbs.file.download', $file->file_name, $file->id) }}</li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="card-text mt-5">
            {!! nl2br($article->content) !!}
        </div>
      </div><!-- .card-body -->
      <div class="card-footer">
        @if ($article->isOwner(Auth::user()) || $isAdmin)
        <a href="{{ route('bbs.admin.tbl.edit', [$cfg->table_name, $article->id]) }}" class='btn btn-primary btn-sm'>@lang('bbs::messages.bbs.button.modify')</a>
        <button type="button" class="btn btn-danger btn-sm btn-delete">@lang('bbs::messages.bbs.button.delete')</button>
        @endif
        <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>@lang('bbs::messages.bbs.button.list')</a>
      </div><!-- .card-footer -->
    </div><!-- .card -->
  </div> <!-- .bbs show -->
  @if ($cfg->enable_comment == 1)
  @include ('bbs.templates.admin.'.$cfg->skin_admin.'.comment')
  @endif
</div><!-- .container -->
@stop

@section ('styles')
@parent
@include ('bbs.templates.admin.'.$cfg->skin_admin.'.style')
@stop

@section ('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js"></script>
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
