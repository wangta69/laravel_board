@extends($cfg->extends)
@section ($cfg->section)
<div class='basic-table show'>
    <h1 class='title'>
        {{ $article->table->name }}
    </h1>

    <article class="bbs-view">
        <header class="title">{{ $article->title }}</header>
        <section class="info">
                    작성자
               {{ $article->user->name }}

            <span class="created-at">{{ date('Y-m-d', strtotime($article->created_at)) }}</span>
            <span class="hit">조회 {{ number_format($article->hit) }} 회</span>

        </section>
        <section class="link">
            <ul>
            @foreach ($article->files as $file)
                    <!-- 파일 다운로드 경로 등을 넣으세요.. -->
                    <li>{{ link_to_route('bbs.file.download', $file->file_name, $file->id) }}</li>
                @endforeach
             </ul>
        </section>
        <section class="body">
             {!! nl2br($article->content) !!}
        </section>
        <section class="act">

        <form method="post" 
            action="{{ route('bbs.destroy', [$cfg->table_name, $article->id]) }}">
            @csrf
        @method('DELETE')

            @if ($article->isOwner(Auth::user()) || $isAdmin)
            <a href="{{ route('bbs.edit', [$cfg->table_name, $article->id]) }}" role='button' class='btn btn-primary btn-sm'>수정</a>
                <button type="submit" class="btn btn-danger btn-sm">삭제</button>
            @endif
            <a href="{{ route('bbs.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>목록</a>
            </form>
        </section>
    </article>
</div>
@if ($cfg->enable_comment == 1)
@include ('bbs.templates.'.$cfg->skin.'.comment')
@endif

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
<script>
    BBS.tbl_name    = "{{$cfg->table_name}}";
    BBS.article_id  = {{$article->id}};
</script>
@stop
