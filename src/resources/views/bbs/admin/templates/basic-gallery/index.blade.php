@extends($cfg->extends)
@section ($cfg->section)
<?php use Wangta69\Bbs\BbsController;?>
<div class="bbs-admin">
    <h2 class='title'>
        {{ $cfg->name }}
    </h2>

    <div class="basic-gallery index">
        <div class='gallery'>

            @foreach ($articles as $index => $article)
            <div>
                <a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}" class="image-link">
                    <img src="{{ BbsController::get_thumb($article->image, 205, 205)  }}" width="300" height="200">
                </a>
                <div class="desc">
                    <span class="title">{!! Html::link(route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]),
                        $article->title) !!}</span>
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
                {!! Html::link(route('bbs.admin.tbl.create', [$cfg->table_name]), '글쓰기', [
                'role' => 'button',
                'class' => 'btn btn-sm btn-primary',
                ]) !!}
                @endif
            </div>
        </div>
    </div>
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
{{ Html::script('assets/pondol_bbs/js/bbs.js') }}
@stop
