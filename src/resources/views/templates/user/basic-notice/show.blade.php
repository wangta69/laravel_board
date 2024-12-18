@section('meta_tags')
<x-pondol-meta::meta :meta="$meta"/>
@endsection
@section('title', $meta->title)

<x-bbs::front :cfg="$cfg">
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
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.created_at')</div>
          <div class="col-4">{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</div>
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.views')</div>
          <div class="col-4">{{ number_format($article->hit) }}</div>
        </div> 
        <div class="row mt-2">
          <div class="col-2 fw-bold">@lang('bbs::messages.bbs.title.attached')</div>
          <div class="col-10">
            <ul class="link">
              @foreach ($article->files as $file)
              <li><a href="{{ route('bbs.file.download', [ $file->id]) }}">{{$file->file_name}}</a></li>
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
        <a href="{{ route('bbs.edit', [$cfg->table_name, $article->id]) }}" class='btn btn-primary btn-sm'>@lang('bbs::messages.bbs.button.modify')</a>
        <button type="button" class="btn btn-danger btn-sm btn-delete">@lang('bbs::messages.bbs.button.delete')</button>
        @endif
        <a href="{{ route('bbs.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>@lang('bbs::messages.bbs.button.list')</a>
      </div><!-- .card-footer -->
    </div><!-- .card -->
  </div> <!-- .bbs show -->
  @if ($cfg->enable_comment == 1)
  @include ('bbs::templates.user.basic.comment')
  @endif
</div><!-- .container -->

@section ('styles')
@parent
@include ('bbs.templates.user.'.$cfg->skin.'.style')
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
<script>
BBS.tbl_name = "{{$cfg->table_name}}";
BBS.article_id = {{$article-> id}};
$(function(){
  $('.btn-delete').on('click', function(){
    if(confirm('@lang('bbs::messages.message.confirm-delete')')) {
      ROUTE.ajaxroute('delete', {
        route: 'bbs.destroy', 
        segments: ['{{$cfg->table_name}}',  {{$article->id}}]
      }, function(resp) {
        if(resp.error) {
          alert(resp.error)
        } else {
          var url = "{{ route('bbs.index', [$cfg->table_name]) }}"
          location.href= url;
        }
      })
    }
  })
})
</script>
@stop
</x-bbs::front>