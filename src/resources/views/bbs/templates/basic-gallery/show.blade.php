@extends($cfg->extends)

@section('title'){!! $article->title !!} @stop
@section ('bbs-content')
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
                <li>{{ link_to_route('bbs.download', $file->file_name, $file->id) }}</li>
            @endforeach
            </ul>
        </section>
        <section class="body">
             @foreach ($article->files as $file)
                <!-- 파일 다운로드 경로 등을 넣으세요.. -->
                <div><img src="{{ Storage::url($file->path_to_file ) }}" alt="{{ $file->file_name }}"></div>
            @endforeach

             {!! nl2br($article->content) !!}
        </section>
        <section class="act">
            {!! Form::open([
                'route' => ['bbs.destroy', $cfg->table_name, $article->id],
                'method' => 'delete',
            ]) !!}
            @if ($article->isOwner(Auth::user()) || $isAdmin)
                {!! Html::link(route('bbs.edit', [$cfg->table_name, $article->id]), '수정', [
                    'role' => 'button',
                    'class' => 'btn btn-primary btn-sm',
                ]) !!}
                {!! Form::submit('삭제', [
                    'class' => 'btn btn-danger btn-sm',
                ]) !!}
            @endif
            {!! Html::link(route('bbs.index', [$cfg->table_name]), '목록', [
                'class' => 'btn btn-default btn-sm',
            ]) !!}
        {!! Form::close() !!}
        </section>
    </article>
</div>
    @if ($cfg->enable_comment == 1)
        @include ('bbs::templates.basic.comment', ['cfg'=>$cfg, 'article'=>$article])
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
{{ Html::script('assets/pondol_bbs/js/bbs.js') }}
<script>
    BBS.tbl_name    = "{{$cfg->table_name}}";
    BBS.article_id  = {{$article->id}};
</script>
@stop
