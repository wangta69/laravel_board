@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic-table show'>
        <h1 class='title'>
            {{ $article->table->name }}
        </h1>

        <table class="table">
            <colgroup>
                <col width='120' />
                <col width='*' />
                <col width='120' />
                <col width='*' />
            </colgroup>
            <thead>
                <tr>
                    <th>제목</th>
                    <td colspan='3'>{{ $article->title }}</td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td>{{ $article->user->name }} ({{ date('Y-m-d H:i', strtotime($article->created_at)) }})</td>
                    <th>조회수</th>
                    <td>{{ number_format($article->hit) }}</td>
                </tr>
                <tr>
                    <th>첨부파일</th>
                    <td colspan='3'>
                        <ul class="link">
                            @foreach ($article->files as $file)
                            <!-- 파일 다운로드 경로 등을 넣으세요.. -->
                            <li>{{ link_to_route('bbs.download', $file->file_name, $file->id) }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </thead>
        </table>

        <div class='body'>
            <div class="content">
            {!! nl2br($article->content) !!}
            </div>
        </div>

        <div class='btn-area text-right'>
            <form method="post" 
                action="{{ route('bbs.admin.tbl.destroy', [$cfg->table_name, $article->id]) }}">
                @csrf
                @method('DELETE')

            @if ($article->isOwner(Auth::user()) || $isAdmin)
            <a href="{{ route('bbs.admin.tbl.edit', [$cfg->table_name, $article->id]) }}" role="button" class='btn btn-primary btn-sm'>수정</a>
            <button type="submit" class="btn btn-danger btn-sm">삭제</button>
            @endif
            <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>목록</a>
            </form>
        </div>
    </div>
    @if ($cfg->enable_comment == 1)
    @include ('bbs.admin.templates.'.$cfg->skin.'.comment')
    @endif
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style') 
    @include ('bbs.admin.templates.'.$cfg->skin.'.css.style')
</style>
@stop
@section ('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js"></script>
<script>
    BBS.tbl_name = "{{$cfg->table_name}}";
    BBS.article_id = {{$article-> id}};
</script>
@stop