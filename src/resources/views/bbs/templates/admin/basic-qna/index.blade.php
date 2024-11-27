@extends($cfg->extends)
@section ($cfg->section)
<div class="container">
  <div class="bbs index">
    <h1 class='title'>{{ $cfg->name }}</h1>

    <div class="card">
      <div class="card-header">
      <form method='get' action="{{url()->current()}}">
          <div class="col-5 float-end">
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
      </div>
      <div class="card-body">
        <table class="table">
          <colgroup>
            <col width='50' />
            <col width='' />
            <col width='120' />
            <col width='120' />
            <col width='80' />
            <col width='80' />
          </colgroup>
          <thead>
            <tr>
              <th class='text-center'>#</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.title')</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.writer')</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.created_at')</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.views')</th>
              <th class='text-center'></th>
            </tr>
          </thead>
          <tbody>
            @foreach($top_articles as $index => $article)
            <tr>
              <td class='text-center'></td>
              <td><a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></td>
              <td><a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->writer}}</a></td>
              <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
              <td class='text-center'>{{ number_format($article->hit) }}</td>
              <td class='text-center'></td>
            </tr>
            @endforeach
            @forelse($articles as $index => $article)
            <tr>
              <td class='text-center'>
                {{ number_format($articles->total() - $articles->perPage() * ($articles->currentPage() - 1) - $index) }}
              </td>
              <td><a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></td>
              <td><a href="{{ route('bbs.admin.tbl.show', [$cfg->table_name, $article->id]) }}">{{$article->writer}}</a></td>
              <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
              <td class='text-center'>{{ number_format($article->hit) }}</td>
              <td class='text-center'>@if($article->comment_cnt) @lang('bbs::messages.bbs.title.status.answerd') @else @lang('bbs::messages.bbs.title.status.ready') @endif</td>
            </tr>
            @empty
            <tr>
              <td colspan="6">
                @lang('bbs::messages.bbs.title.no-data')
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div><!-- .card-body -->
      <div class='navigation'>
        {{-- $articles->render() --}}
        {{ $articles->links("pagination::bootstrap-4") }}
      </div>
      <div class="card-footer">
        @if ($cfg->hasPermission('write'))
        <a href="{{ route('bbs.admin.tbl.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>@lang('bbs::messages.bbs.button.write')</a>
        @endif
      </div>
    </div><!-- .card -->
  </div><!-- .bbs index -->
</div><!-- .container -->
@stop

@section ('styles')
@parent
@include ('bbs.templates.admin.'.$cfg->skin_admin.'.style')
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
@stop
