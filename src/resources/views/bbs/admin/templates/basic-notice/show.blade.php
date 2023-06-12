@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic-notice show'>
        <h1 class='title'>
            {{ $article->table->name }}
        </h1>

        <table class="table">
            <colgroup>
                <col width='120' />
                <col width='*' />
                <col width='120' />
                <col width='*' />
            </colgroup>
            <thead>
                <tr>
                    <th>제목</th>
                    <td colspan='3'>{{ $article->title }}</td>
                </tr>
                <tr>
                    <th>작성일</th>
                    <td>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
                    <th>조회수</th>
                    <td>{{ number_format($article->hit) }}</td>
                </tr>
                <tr>
                    <th>첨부파일</th>
                    <td colspan='3'>
                        @foreach ($article->files as $file)
                        <!-- 파일 다운로드 경로 등을 넣으세요.. -->

                        {{ link_to_route('bbs.admin.tbl.download', $file->file_name, $file->id) }}

                        @endforeach
                    </td>
                </tr>
            </thead>
        </table>

        <div class='content mb-5'>
            {!! nl2br($article->content) !!}
        </div>

        <div class='btn-area text-right'>
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
        </div>
    </div>
    @if ($cfg->enable_comment == 1)
    @include ('bbs::templates.basic.comment')
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
