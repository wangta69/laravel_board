{!! Form::open([
    'route' => ['bbs.admin.tbl.comment.store', $cfg->table_name, $article->id, 0],
    'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data',
]) !!}
    {{ Form::hidden('text_type', 'br') }}

    <section class="comment-input">
        <div class="container-fluid">
            <div style="float:right;">
                <button type="submit" class="btn btn-success" style="width:70px; height: 70px;">저장</button>
            </div>
            <div style="margin-right: 80px;">
            <textarea name="content" maxlength="10000" required="" class="form-control input-sm" style="height:70px;" ></textarea>
            </div>

        </div>
    </section>
{!! Form::close() !!}
<section class="comment-list">
    <h3>댓글목록</h3>

     @foreach ($article->comments()->orderBy('order_num')->orderBy('reply_depth')->get() as $comment)
        <!-- 파일 다운로드 경로 등을 넣으세요.. -->
        <article class="comment-article" user-attr-comment-id="{{ $comment->id }}">
            <ul>
                <li class="view depth-{{ strlen($comment->reply_depth) }}">
                   <header>
                        <h3>{{ $comment->user_name }}의 댓글</h3>
                        <div class="info">
                            <b>{{ $comment->user_name }}</b>
                            <span class="comment-time-info"><time datetime="{{ $comment->created_at }}">{{ date("Y-m-d H:i", strtotime($comment->created_at))}}</time></span>
                        </div>
                    </header>
                    <!-- 댓글 출력 -->
                    <div class='comment-content'>{{ $comment->content }}</div>
                    <div class='footer'>

                        @if ($comment->isOwner(Auth::user()))
                            {!! Form::button('수정', [
                                'class' => 'btn btn-primary btn-sm comment-update-form',
                            ]) !!}
                            {!! Form::button('삭제', [
                                'class' => 'btn btn-danger btn-sm comment-delete-admin',
                            ]) !!}
                        @else
                            {!! Form::button('답변', [
                                'class' => 'btn btn-default btn-sm comment-reply-form-admin',
                            ]) !!}
                        @endif
                    </div>
                    <div class="re_comment">

                    </div><!-- 답변 -->
                </li>
                <li class="update">
                   <!--
                   {!! Form::open([
                    'route' => ['bbs.comment.update', $cfg->table_name, $article->id, $comment->id],
                    'method' => 'put',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                    ]) !!}
                    {{ Form::hidden('text_type', 'br') }}
                    {!! Form::close() !!}
                   -->
                    <section class="comment-input">
                        <div class="container-fluid">
                            <div style="float:right;">
                                <button type="button" class="btn btn-primary comment-update-admin" style="width:70px; height: 70px;">수정</button>
                                <button type="button" class="btn btn-default comment-update-cancel" style="width:70px; height: 70px;">취소</button>
                            </div>
                            <div style="margin-right: 150px;">
                                <textarea name="content" maxlength="10000" required="" class="form-control input-sm comment-update-content" style="height:70px;" ></textarea>
                            </div>
                        </div>
                    </section>
                </li>
            </ul>
        </article>
     @endforeach
</section>

<div id="re_comment">
        <section class="comment-input">
            <div class="container-fluid">
                <div style="float:right;">
                    <button type="button" class="btn btn-success comment-reply-create" style="width:70px; height: 70px;">저장</button>
                    <button type="button" class="btn btn-default comment-reply-cancel" style="width:70px; height: 70px;">취소</button>
                </div>
                <div style="margin-right: 150px;">
                <textarea name="content" maxlength="10000" required="" class="form-control input-sm" style="height:70px;" ></textarea>
                </div>

            </div>
        </section>
</div>
