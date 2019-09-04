@extends($urlParams->dec['blade_extends'])
@section ('bbs-content')
<?php use Pondol\Bbs\BbsController;?>
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
                {!! Html::link(route('bbs.create', [$cfg->table_name, 'urlParams='.$urlParams->enc]), '글쓰기', [
                    'role' => 'button',
                    'class' => 'btn btn-sm btn-default',
                ]) !!}
            @endif
        </div>
    </div>
    <div class='index'>

        @foreach ($articles as $index => $article)
        <div class="gallery">
          <a href="{{ route('bbs.show', [$cfg->table_name, $article->id, 'urlParams='.$urlParams->enc]) }}">
              <img src="{{ BbsController::get_thumb($article->image, 205)  }}" width="300" height="200">
          </a>
          <div class="desc">
              <span class="title">{!! Html::link(route('bbs.show', [$cfg->table_name, $article->id, 'urlParams='.$urlParams->enc]), $article->title) !!}</span>
              <span class="info">{{ $article->user_name }}</span>

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
                {!! Html::link(route('bbs.create', [$cfg->table_name, 'urlParams='.$urlParams->enc]), '글쓰기', [
                    'role' => 'button',
                    'class' => 'btn btn-sm btn-default',
                ]) !!}
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
{{ Html::script('assets/pondol_bbs/js/bbs.js') }}
@stop
