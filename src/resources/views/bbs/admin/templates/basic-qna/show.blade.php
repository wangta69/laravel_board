@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class="basic-qna show">
        <h1>
            {{ $cfg->name }}
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
                {!! Form::open([
                'route' => ['bbs.admin.tbl.destroy', $cfg->table_name, $article->id],
                'method' => 'delete',
                ]) !!}
                @if ($article->isOwner(Auth::user()) || $isAdmin)
                {!! Html::link(route('bbs.admin.tbl.edit', [$cfg->table_name, $article->id]), '수정', [
                'role' => 'button',
                'class' => 'btn btn-primary btn-sm',
                ]) !!}
                {!! Form::submit('삭제', [
                'class' => 'btn btn-danger btn-sm',
                ]) !!}
                @endif
                {!! Html::link(route('bbs.admin.tbl.index', [$cfg->table_name]), '목록', [
                'class' => 'btn btn-secondary btn-sm',
                ]) !!}
                {!! Form::close() !!}
        </div>
    </div>
    @if ($cfg->enable_comment == 1)
    @include ('bbs.admin.templates.'.$cfg->skin_admin.'.comment')
    @endif
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style') 
    @include ('bbs.admin.templates.'.$cfg->skin_admin.'.css.style')
</style>
@stop
@section ('scripts')
@parent
{{ Html::script('assets/pondol/bbs/bbs.js') }}
<script>
    BBS.tbl_name = "{{$cfg->table_name}}";
    BBS.article_id = {{$article-> id}};
</script>
@stop
