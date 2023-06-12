@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
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
                    <li>{{ link_to_route('bbs.admin.tbl.download', $file->file_name, $file->id) }}</li>
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
                'route' => ['bbs.admin.tbl.destroy', $cfg->table_name, $article->id],
                'method' => 'delete',
                ]) !!}
                @if ($article->isOwner(Auth::user()) || $isAdmin)
                {!! Html::link(route('bbs.admin.tbl.edit', [$cfg->table_name, $article->id]), '수정', [
                'role' => 'button',
                'class' => 'btn btn-primary btn-sm',
                ]) !!}
                {!! Form::submit('삭제', [
                'class' => 'btn btn-danger btn-sm',
                ]) !!}
                @endif
                {!! Html::link(route('bbs.admin.tbl.index', [$cfg->table_name]), '목록', [
                'class' => 'btn btn-default btn-sm',
                ]) !!}
                {!! Form::close() !!}
            </section>
        </article>
    </div>
    @if ($cfg->enable_comment == 1)
    @include ('bbs::templates.basic.comment', ['cfg'=>$cfg, 'article'=>$article])
    @endif
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
<script>
    BBS.tbl_name = "{{$cfg->table_name}}";
    BBS.article_id = {
        {
            $article - > id
        }
    };

</script>
@stop