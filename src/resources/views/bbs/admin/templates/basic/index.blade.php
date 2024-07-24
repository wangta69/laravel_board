@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic index'>
        <h2 class='title'>
            {{ $cfg->name }}
        </h2>
        <form method='get' action="{{url()->current()}}">
            <div class="col-3 float-end">
                <div  class=" input-group">
                    <select name="f" id="" class="form-select" >
                        <option value="title" @if (request()->get('f') == 'title') selected @endif>Title</option>
                        <option value="content" @if (request()->get('f') == 'content') selected @endif>Contents</option>
                    </select>
                    <input type="text" name="s" placeholder="Keyword Search" value="{{request()->get('s')}}"  class="form-control"/>
                    <button type="submit" class="btn btn-primary">@lang('bbs::messages.bbs.button.search')</button>
                </div>
            </div>
        </form>
        <table class="table mt-5">
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
                    <th class='text-center'>@lang('bbs::messages.bbs.title.title')</th>
                    <th class='text-center'>@lang('bbs::messages.bbs.title.writer')</th>
                    <th class='text-center'>@lang('bbs::messages.bbs.title.created_at')</th>
                    <th class='text-center'>@lang('bbs::messages.bbs.title.views')</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($articles as $index => $article)
                <tr>
                    <td class='text-center'>
                        {{ number_format($articles->total() - $articles->perPage() * ($articles->currentPage() - 1) - $index) }}
                    </td>
                    <td>
                        <a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a>
                    </td>
                    <td class='text-center'>{{ $article->writer }}</td>
                    <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
                    <td class='text-center'>{{ number_format($article->hit) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                    @lang('bbs::messages.bbs.title.no-data')
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
            <a href="{{ route('bbs.admin.tbl.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>@lang('bbs::messages.bbs.button.write')</a>
            @endif
        </div>
    </div>
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style')
    @include ('bbs::templates.'.$cfg->skin.'.css.style')
</style>
@stop
@section ('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js"></script>
@stop
