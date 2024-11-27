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
                <option value="" @if (request()->get('f') == '') selected @endif>@lang('bbs::messages.bbs.title.title_content')</option>
                <option value="writer" @if (request()->get('f') == 'writer') selected @endif>@lang('bbs::messages.bbs.title.writer')</option>
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
            @foreach($top_articles as $index => $article)
            <tr>
              <td class='text-center'></td>
              <td>
                <a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a>
              </td>
              <td class='text-center'>{{ $article->writer }}</td>
              <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
              <td class='text-center'>{{ number_format($article->hit) }}</td>
            </tr>
            @endforeach
            @forelse ($articles as $index => $article)
            <tr>
              <td class='text-center'>
                {{ number_format($articles->total() - $articles->perPage() * ($articles->currentPage() - 1) - $index) }}
              </td>
              <td>
                <a href="{{ route('bbs.show', [$cfg->table_name, $article->id]) }}">{{$article->title}}</a>
              </td>
              <td class='text-center'>{{ $article->writer }}</td>
              <td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
              <td class='text-center'>{{ number_format($article->hit) }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5">@lang('bbs::messages.bbs.title.no-data')</td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <div class='navigation'>
          {!! $articles->render() !!}
        </div>
      </div><!-- .card-body -->
      <div class="card-footer">
        @if ($cfg->hasPermission('write'))
        <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>@lang('bbs::messages.bbs.button.write')</a>
        @endif
      </div>
    </div><!-- .card -->
  </div><!-- .bbs index -->
</div><!-- .container -->

@stop

@section ('styles')
@parent
@include ('bbs.templates.user.'.$cfg->skin.'.style')
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
@stop
