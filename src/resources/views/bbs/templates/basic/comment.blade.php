{!! Form::open([
    'route' => ['bbs.comment.store', $cfg->table_name, $article->id, 'urlParams='.$urlParams->enc],
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
    
     @foreach ($article->comments()->orderBy('order_num')->get() as $comment)
        <!-- 파일 다운로드 경로 등을 넣으세요.. -->
        
   
    <article class="comment-article" user-attr-comment-id="{{ $comment->id }}">
        <header>
            <h1>{{ $comment->user_name }}의 댓글</h1>
            <b>{{ $comment->user_name }}</b>
            <span class="comment-time-info"><time datetime="{{ $comment->created_at }}">{{ date("Y-m-d H:i", strtotime($comment->created_at))}}</time></span>
        </header>
        <!-- 댓글 출력 -->
        <div class='comment-content'>{{ $comment->content }}</div>
<!--
        <input type="hidden" value="" id="secret_comment_51185">
        <div id="save_comment_51185" class="save_comment" style="display:none">
            <textarea>캐서린 샤프라... 다 봤겠네?</textarea>
            <a href="" class="btn_modify" onclick="alert('수정'); return false;">수정</a>
        </div>
-->        

<!--
        <footer>
            <ul class="comment-action">
                <li class="btn-comment-reply"><a href="#">답변</a></li>
            </ul>
        </footer>
    -->    
        <div class="re_comment" style="display:none;"></div><!-- 답변 -->
    </article>
     @endforeach
</section>