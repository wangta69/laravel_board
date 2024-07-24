@extends($cfg->extends)
@section ($cfg->section)
<!-- use Wangta69\Bbs\BbsController; -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
     <li class="breadcrumb-item active" aria-current="page">{{ $cfg->name }}</li>
  </ol>
</nav>
<h3>{{ $cfg->name }} </h3>

<div class="bbs">
    <div class="bbs-bottom-nav">
        <nav aria-label="Page navigation">
            {!! $articles->render() !!}
        </nav>

        <div class='btn-area text-right'>
            @if ($cfg->hasPermission('write'))
            <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class="btn btn-sm btn-default">글쓰기</a>
            @endif
        </div>
    </div>
    <div class='index'>

        @foreach ($articles as $index => $article)
        <div class="gallery">
          <a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">
              <img src="{{get_thumb($article->image, 205)  }}" width="300" height="200">
          </a>
          <div class="desc">
              <span class="title"><a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></span>
              <span class="info">{{ $article->writer }}</span>

          </div>
        </div>
        @endforeach
    </div>

    <div class="bbs-bottom-nav">
        <nav aria-label="Page navigation">
            {!! $articles->render() !!}
        </nav>

        <div class='btn-area text-right'>
            @if ($cfg->hasPermission('write'))
            <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class="btn btn-sm btn-default">글쓰기</a>
            @endif
        </div>
    </div>
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.templates.'.$cfg->skin.'.css.style')
</style>
@stop
@section ('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js"></script>
@stop
