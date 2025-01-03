@section('meta_tags')
<x-pondol-meta::meta :meta="$meta"/>
@endsection
@section('title', $meta->title)

<x-bbs::front :cfg="$cfg">
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
              <td><a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></td>
              <td class='text-center'>{{$article->writer}}</td>
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
              <td><a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a></td>
              <td class='text-center'>{{$article->writer}}</td>
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

        <div class='navigation'>
        {!! $articles->links('pagination::bootstrap-4') !!}
        </div>

        <div class='btn-area text-right'>

            @if ($cfg->hasPermission('write'))
            <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>글쓰기</a>
            @endif
        </div>
    </div>
</div>

@section ('styles')
@parent
@include ('bbs.templates.user.'.$cfg->skin.'.style')
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
@stop
</x-bbs::front>
