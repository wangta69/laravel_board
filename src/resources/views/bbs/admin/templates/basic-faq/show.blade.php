@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic-table show'>
        <h1 class='title'>
            {{ $article->table->name }}
        </h1>

        <table>
            <colgroup>
                <col width='120' />
                <col width='*' />
            </colgroup>
            <thead>
                <tr>
                    <th>제목</th>
                    <td colspan='3'>{{ $article->title }}</td>
                </tr>
            </thead>
        </table>

        <div class='content'>
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
    @if ($article->isOwner(Auth::user()) || $isAdmin)
    @include ('bbs.admin.templates.'.$cfg->skin.'.comment')
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
