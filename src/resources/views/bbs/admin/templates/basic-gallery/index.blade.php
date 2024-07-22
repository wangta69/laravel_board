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
                    <span class="title"><a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></span>
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
                <a href="{{ route('bbs.admin.tbl.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>글쓰기</a>
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
<script src="/assets/pondol/bbs/bbs.js"></script>
@stop
