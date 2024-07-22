@extends($cfg->extends)
@section ($cfg->section)
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
    </div>
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
