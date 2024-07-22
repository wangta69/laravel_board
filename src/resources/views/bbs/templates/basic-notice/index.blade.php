@extends($cfg->extends)
@section ($cfg->section)
<div class='basic-table index'>
    <h1 class='title'>
        {{ $cfg->name }}
    </h1>
    <table>
    <colgroup>
        <col width='50' />
        <col width='' />
        <col width='120' />
        <col width='80' />
    </colgroup>
    <thead>
        <tr>
            <th class='text-center'>#</th>
            <th class='text-center'>제목</th>
            <th class='text-center'>작성일</th>
            <th class='text-center'>조회수</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($articles as $index => $article)
       {{-- @foreach($articles as $article) --}}
            <tr>
                <td class='text-center'>{{ number_format($articles->total() - $articles->perPage() * ($articles->currentPage() - 1) - $index) }}</td>
                <td><a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></td>
                <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
                <td class='text-center'>{{ number_format($article->hit) }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="4">
                        No contents
                    </td>
                </tr>
            @endforelse
    </tbody>
    </table>

    <div class='navigation'>
        {!! $articles->render() !!}
    </div>

    <div class='btn-area text-right'>

        @if ($cfg->hasPermission('write'))
            <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class="btn btn-sm btn-default">글쓰기</a>
        @endif
    </div>
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.templates.'.$cfg->skin.'.css.style')
</style>
@stop
