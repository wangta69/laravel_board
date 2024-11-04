@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
  <div class='basic index'>
    <h2 class='title'>
      Story Comment
    </h2>
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
                <th class='text-center'>Article Title</th>
                <th class='text-center'>작성자</th>
                <th class='text-center'>내용</th>
                <th class='text-center'>작성일</th>
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
                <td class='text-center'>{{ $comment->content }}</td>
                <td class='text-center'>{{ date('Y-m-d H:i', strtotime($comment->created_at)) }}</td>
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
        {!! $comments->render() !!}
    </div>

  </div>
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style')
{{--    @include ('bbs::templates.'.$cfg->skin_admin.'.css.style') --}}
</style>
@stop
@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
@stop
