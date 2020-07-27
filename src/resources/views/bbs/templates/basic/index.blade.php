@extends($urlParams->dec['blade_extends'])
@section ('bbs-content')
<div class='basic-table index'>
    <h1 class='title'>
        {{ $cfg->name }}
    </h1>
    <form method='get' action="{{url()->current()}}">
        <input type="hidden" name="urlParams" value="{{request()->get('urlParams')}}">
        <div class="right-search">
            <select name="f" id="">
                <option value="title" @if (request()->get('f') == 'title') selected @endif>Title</option>
                <option value="content" @if (request()->get('f') == 'content') selected @endif>Contents</option>
            </select>
            <input type="text" name="s" placeholder="Keyword Search" value="{{request()->get('s')}}" />
            <input  type="submit" />
        </div>
    </form>
    <table>
    <colgroup>
        <col width='50' />
        <col width='' />
        <col width='120' />
        <col width='120' />
        <col width='80' />
    </colgroup>
    <thead>
        <tr>
            <th class='text-center'>#</th>
            <th class='text-center'>제목</th>
            <th class='text-center'>작성자</th>
            <th class='text-center'>작성일</th>
            <th class='text-center'>조회수</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($articles as $index => $article)
            <tr>
                <td class='text-center'>{{ number_format($articles->total() - $articles->perPage() * ($articles->currentPage() - 1) - $index) }}</td>
                <td>{!! Html::link(route('bbs.show', [$cfg->table_name, $article->id, 'urlParams='.$urlParams->enc]), $article->title) !!}</td>
                <td class='text-center'>{{ $article->user_name }}</td>
                <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
                <td class='text-center'>{{ number_format($article->hit) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
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
            {!! Html::link(route('bbs.create', [$cfg->table_name, 'urlParams='.$urlParams->enc]), '글쓰기', [
                'role' => 'button',
                'class' => 'btn btn-sm btn-default',
            ]) !!}
        @endif
    </div>
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs::templates.'.$cfg->skin.'.css.style')
</style>
@stop
@section ('scripts')
@parent
{{ Html::script('assets/pondol_bbs/js/bbs.js') }}
@stop
