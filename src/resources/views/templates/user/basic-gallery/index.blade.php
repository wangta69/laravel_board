@section('meta_tags')
<x-pondol-meta::meta :meta="$meta"/>
@endsection
@section('title', $meta->title)

<x-bbs::front :cfg="$cfg">
<div class="container">
  <div class="bbs index">
    <h1 class='title'>{{ $cfg->name }}</h1>

    <div class="card">
      <div class="card-header">
      <form method='get' action="{{url()->current()}}">
          <div class="col-5 float-end">
            <div  class=" input-group">
              <select name="f" id="" class="form-select" >
                <option value="title" @if (request()->get('f') == 'title') selected @endif>Title</option>
                <option value="content" @if (request()->get('f') == 'content') selected @endif>Contents</option>
              </select>
              <input type="text" name="s" placeholder="Keyword Search" value="{{request()->get('s')}}"  class="form-control"/>
              <button type="submit" class="btn btn-primary">@lang('bbs::messages.bbs.button.search')</button>
            </div>
          </div>
        </form>
      </div>

    <div class="card-body">
        <div class='img-gallery'>

          @foreach ($articles as $index => $article)
          <div class="gallery">
            <a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">
              <img src="{{bbs_get_thumb($article->image, 205) }}" alt="{{$article->title}}">
            </a>
            <div class="desc"> 
              <a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a>
            </div>
            <div class="desc">{{ $article->writer }}</div>
          </div>

          @endforeach
        </div><!-- .gallery -->
        <div class='navigation'>
        {!! $articles->links('pagination::bootstrap-4') !!}
        </div>
      </div><!-- .card-body -->
      <div class="card-footer">
        @if ($cfg->hasPermission('write'))
        <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>@lang('bbs::messages.bbs.button.write')</a>
        @endif
      </div>
    </div><!-- .card -->
  </div><!-- .bbs index -->
</div><!-- .container -->

@section ('styles')
@parent
@include ('bbs.templates.user.'.$cfg->skin.'.style')
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
@stop
</x-bbs::front>