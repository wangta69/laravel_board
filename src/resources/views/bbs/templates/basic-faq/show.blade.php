@extends($urlParams->dec['blade_extends'])
@section ('bbs-content')
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
            'route' => ['bbs.destroy', $cfg->table_name, $article->id, 'urlParams='.$urlParams->enc],
            'method' => 'delete',
        ]) !!}
            @if ($article->isOwner(Auth::user()) || $isAdmin)
                {!! Html::link(route('bbs.edit', [$cfg->table_name, $article->id, 'urlParams='.$urlParams->enc]), '수정', [
                    'role' => 'button',
                    'class' => 'btn btn-primary btn-sm',
                ]) !!}
                {!! Form::submit('삭제', [
                    'class' => 'btn btn-danger btn-sm',
                ]) !!}
            @endif
            {!! Html::link(route('bbs.index', [$cfg->table_name, 'urlParams='.$urlParams->enc]), '목록', [
                'class' => 'btn btn-default btn-sm',
            ]) !!}
        {!! Form::close() !!}
    </div>
</div>
    @if ($article->isOwner(Auth::user()) || $isAdmin)
        @include ('bbs.templates.'.$cfg->skin.'.comment')
    @endif
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.templates.'.$cfg->skin.'.css.style')
</style>
@stop
