@extends($cfg->extends)
@section ($cfg->section)
<div class="container">
  <div class="bbs comment">
  <h1 class='title'>Story Comment</h1>

    <div class='card'>
      <!--
      <div class="card-header">
        <form method='get' action="{{url()->current()}}">
            <div class="right-search">
                <select name="f" id="">
                    <option value="title" @if (request()->get('f') == 'title') selected @endif>Title</option>
                    <option value="content" @if (request()->get('f') == 'content') selected @endif>Contents</option>
                </select>
                <input type="text" name="s" placeholder="Keyword Search" value="{{request()->get('s')}}" />
                <input type="submit" />
            </div>
        </form>
      </div> <!-- .card-header -->
      <div class="card-body">
        <table class="table">
          <colgroup>
            <col width='50' />
            <col width='*' />
            <col width='120' />
            <col width='*' />
            <col width='150' />
          </colgroup>
          <thead>
            <tr>
              <th class='text-center'>#</th>
              <th class='text-center'>Article Title</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.writer')</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.content')</th>
              <th class='text-center'>@lang('bbs::messages.bbs.title.created_at')</th>
            </tr>
          </thead>
          <tbody>

            @forelse ($comments as $index => $comment)
            <tr>
              <td class='text-center'>
                  {{ number_format($comments->total() - $comments->perPage() * ($comments->currentPage() - 1) - $index) }}
              </td>
              <td><a href="/{{$comment->path}}">{{$comment->title}}</a></td>
              <td class='text-center'>{{ $comment->writer }}</td>
              <td>{{ $comment->content }}</td>
              <td class='text-center'>{{ date('Y-m-d H:i', strtotime($comment->created_at)) }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5">@lang('bbs::messages.bbs.title.no-data')</td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <div class='navigation'>
          {!! $comments->render() !!}
        </div>
      </div><!-- .card-body -->

    </div> <!-- .card -->
  </div><!--. .bbs.comment -->
</div><!-- .container -->
@stop

@section ('styles')
@parent
@stop

@section ('scripts')
@parent
@stop
