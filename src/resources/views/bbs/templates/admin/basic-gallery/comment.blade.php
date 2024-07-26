@if ($isAdmin) @endif <!--  qna에서는 오직 관리자만 답글을 달 수 있게 처리 -->
<div class="bbs comment mt-5 mb-5">
  <!-- comment 작성 form -->
  <form method="post" 
    action="{{ route('bbs.admin.tbl.comment.store', [$cfg->table_name, $article->id, 0]) }}" 
    enctype='multipart/form-data'>
    @csrf
    <input type="hidden" name="text_type" value="br">
    <section class="comment-post">
      <div class="container-fluid">
        <div style="float:right;">
          <button type="submit" class="btn btn-success" style="width:70px; height: 70px;">@lang('bbs::messages.bbs.button.store')</button>
        </div>
        <div style="margin-right: 80px;">
          <textarea name="content" maxlength="10000" required="" class="form-control input-sm"
              style="height:70px;" placeholder="@lang('bbs::messages.bbs.title.comment-placeholder')"></textarea>
        </div>
      </div>
    </section>
  </form>

  <section class="mt-3">
    <h3>@lang('bbs::messages.bbs.title.comments')</h3>
    @foreach ($article->comments()->orderBy('order_num')->orderBy('reply_depth')->get() as $comment)
    <article class="comment-article depth-{{ strlen($comment->reply_depth) }}" user-attr-comment-id="{{ $comment->id }}">
      <ul>
        <li class="comment-show">
          <div class="row">
            @if($comment->content)
            <div class="col-10">
              <div class="writer">
                <span class="fw-bold">{{ $comment->writer }}</span>
                <span class="comment-time-info">
                  <time datetime="{{ $comment->created_at }}">{{ date("Y-m-d H:i", strtotime($comment->created_at))}}</time>
                </span>
              </div>
              <div class='comment-content'>{!! nl2br($comment->content) !!}</div>
            </div><!-- col-8 -->

            <div class="col-2 d-flex justify-content-end">
              @if ($comment->isOwner(Auth::user()))
              <button type="button" class="btn btn-primary btn-sm me-1 btn-comment-modify" style="align-self: center">@lang('bbs::messages.bbs.button.modify')</button>
              <button type="button" class="btn btn-danger btn-sm me-1 btn-comment-delete" style="align-self: center">@lang('bbs::messages.bbs.button.delete')</button>
              @else
              <button type="button" class="btn btn-default btn-sm btn-comment-reply" style="align-self: center">@lang('bbs::messages.bbs.button.reple-create')</button>
              @endif
             
            </div>
            @else
            <div>
              <div style="background-color: #ccc; padding: 10px;">@lang('bbs::messages.message.deleted-content')</div>
            </div><!-- col-8 -->
            @endif
          </div> <!-- .comment-item" -->
          <div class="re_comment">

          </div><!-- 답변 -->
        </li>
        <li class="comment-update" style="display: none;">
          <form method="post" 
              action="{{ route('bbs.admin.tbl.comment.update', [$cfg->table_name, $article->id, $comment->id]) }}" 
              enctype='multipart/form-data'>
              @csrf
              @method('PUT')
            <input type="hidden" name="text_type" value="br">

            <section class="comment-input">
              <div class="container-fluid">
                <div style="float:right;">
                  <button type="button" class="btn btn-primary btn-comment-update"
                      style="width:70px; height: 70px;">@lang('bbs::messages.bbs.button.update')</button>
                  <button type="button" class="btn btn-default btn-comment-update-cancel"
                      style="width:70px; height: 70px;">@lang('bbs::messages.bbs.button.cancel')</button>
                </div>
                <div style="margin-right: 150px;">
                  <textarea name="content" maxlength="10000" required=""
                      class="form-control input-sm comment-update-content"
                      style="height:70px;"></textarea>
                </div>
              </div>
            </section>
          </form>
        </li>
      </ul>
    </article>
    @endforeach
  </section> <!-- .comment-list -->
</div><!-- .bbs comment -->
<div id="re_comment" style="display: none;">
  <!--  " -->
  <section class="comment-input">
    <div class="row">
      
      <div class="col-10">
        <textarea name="content" maxlength="10000" required="" class="form-control input-sm"
            style="height:70px;"></textarea>
      </div>
      <div class="col-2">
        <button type="button" class="btn btn-success comment-reply-store"
            style="width:70px; height: 70px;">@lang('bbs::messages.bbs.button.store')</button>
        <button type="button" class="btn btn-default comment-reply-cancel"
            style="width:70px; height: 70px;">@lang('bbs::messages.bbs.button.cancel')</button>
      </div>
    </div>
  </section>
</div>

@section ('scripts')
@parent
<script>
  // 댓글 수정 버튼 클릭시
  $(".btn-comment-modify").on('click', function(){
    var $this = $(this).parents('article');
    BBS.comment_id = $this.attr("user-attr-comment-id");
    // 기존 열린창은 모두 닫고 감쳐진 원 택스트들은 보여줌
    $(".comment-article .update").hide();
    $("li.comment-show").show();

    //현재 창 열기
    $("li.comment-show", $this).hide();
    $("li.comment-update", $this).show();
    $("li.comment-update .comment-update-content", $this).val($("li.comment-show .comment-content", $this).html());
  });

//comment update

$(".btn-comment-update").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  var param = $(this).attr('user-attr-file-id');


  BBS.ajaxroute('put', 
    {'name': 'bbs.comment.update', 'params[0]': BBS.tbl_name, 'params[1]': BBS.article_id, 'params[2]': BBS.comment_id}, 
    {content:$("textarea[name=content]", $this).val()}, 
    function(resp) {
      if(resp.error) {
        alert(resp.error);
      } else {
        $("li.comment-show", $this).show();
        $("li.comment-update", $this).hide();
        $("li.comment-show .comment-content", $this).html( $("li.comment-update .comment-update-content", $this).val());
      }
  })
});


$(".btn-comment-update-cancel").on('click', function(){
  var $this = $(this).parents('article');
  $("li.comment-show", $this).show();
  $("li.comment-update", $this).hide();
});

$(".btn-comment-delete").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  var param = $(this).attr('user-attr-file-id');
  if(confirm('@lang('bbs::messages.message.confirm-delete')')) {
    BBS.ajaxroute('delete', 
      {'name': 'bbs.comment.destroy', 'params[0]': BBS.tbl_name, 'params[1]': BBS.article_id, 'params[2]': BBS.comment_id}, {}, 
      function(resp) {
        if(resp.error) {
          alert(resp.error);
        } else {
          $this.remove();
        }
    })
  }
});

//create comment reply form
$(".btn-comment-reply").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  $(".re_comment").hide();

  $(".re_comment", $this).html($("#re_comment").html()).show();

});

//comment reply cancel
$( ".re_comment" ).on( "click", ".comment-reply-cancel", function() {
  $(this).parents(".re_comment").empty().hide();
});

$( ".re_comment" ).on( "click", ".comment-reply-store", function() {
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  var param = $(this).attr('user-attr-file-id');

  BBS.ajaxroute('post', 
    {'name': 'bbs.comment.destroy', 'params[0]': BBS.tbl_name, 'params[1]': BBS.article_id, 'params[2]': BBS.comment_id}, 
    {content:$("textarea[name=content]", $this).val()}, 
    function(resp) {
      if(resp.error) {
        alert(resp.error);
      } else {
        location.reload();
      }
  })
});
</script>
@stop