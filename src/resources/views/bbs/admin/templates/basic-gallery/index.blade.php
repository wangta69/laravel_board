@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <h2 class='title'>
        {{ $cfg->name }}
    </h2>

    <div class="basic-gallery index">
        <div class='gallery'>

        @foreach ($articles as $index => $article)
        <div class="col-lg-3 col-md-4 col-xs-6 mb-5 " style="text-align: center;">
          <a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">
              <img src="{{get_thumb($article->image, 205) }}" >
          </a>
          <div class="mx-auto d-block">
          <div class="input-group" style="justify-content: center;">
              <span class="input-group-text"><a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></span>
              <span class="input-group-text">{{ $article->writer }}</span>
            </div>
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
