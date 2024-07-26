<div class="comment-wrap">
  @foreach ($comments as $v)
  <hr>
  <article class="comment-article depth-{{strlen($v->reply_depth)}}" user-attr-comment-id="{{ $v->id }}">
    <blockquote class="comment">{{ $v->content }}</blockquote>


    <div class="re-comment">
      <span class='add-comment'><i class="fa-regular fa-pen-to-square"></i></span>
      <!-- | Show 2 more commnets -->
    </div>
  </article>

  @endforeach
  <div class="bbs-comments">
    <form method="post" 
    action="{{ route('bbs.item.comment.store', [$item, $item_id, 0]) }}" 
    class='form-horizontal' 
    enctype='multipart/form-data'>
    @csrf
    <input type="hidden" name="parent_id" value="0">
    
    <section class="comment-input">
    @error('error')
      <p class=error>{{ $message }}</p>
    @enderror
      <div class="container-fluid">
        <div style="float:right;">
          <button type="submit" class="btn btn-success" style="width:70px; height: 70px;">저장</button>
        </div>
        <div style="margin-right: 80px;">
          <textarea name="content" maxlength="10000" required="" class="form-control input-sm"
                style="height:120px;" placeholder="궁금한 점을 남겨주세요"></textarea>
        </div>

      </div>
    </section>
    </form>
  </div>

  <div id="re-comment" style="display: none; margin: 20px;">
    <!--  " -->
    <section class="comment-input">
      <div class="container-fluid">
        <div style="float:right;">
          <button type="button" class="btn btn-success comment-reply-create"
              style="width:70px; height: 70px;">저장</button>
          <!-- <button type="button" class="btn btn-default comment-reply-cancel"
              style="width:70px; height: 70px;">취소</button> -->
        </div>
        <div style="margin-right: 80px;">
          <textarea name="content" required="" class="form-control input-sm"
              style="height:70px;"></textarea>
        </div>

      </div>
    </section>
  </div>
</div>
@section('styles')
@parent
<style>
.comment-wrap hr:first-child {
  display: none;
}
.comment {
  background-color: #fbfbfb;
  padding: 10px;
}

.re-comment {
  text-align: right;
  padding: 5px;
}

.comment-article {
  /* padding-top: 3rem; */
}
.comment-article blockquote {
    margin: 0;
    color: #57606a;
    border-left: .25em solid #d0d7de;
}
.comment-article.depth-2 {
  padding-left: 20px;
}
.comment-article.depth-3 {
  padding-left: 40px;
}

.comment-article.depth-4 {
  padding-left: 60px;
}
</style>
@endsection
@section('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js"></script>
<script>
var item = "{{$item}}"; 
var item_id = {{$item_id}};
$(function(){
  $(".add-comment").on('click', function(){
    $recomment = $( "#re-comment" ).clone().removeAttr( "id" ).show();
    $(this).parents('.re-comment').html($recomment);
  })

  $( ".re-comment" ).on( "click", ".comment-reply-create", function() {
    // var index = $(".comment-reply-create").index(this);

    var routeInfo = {'name': 'bbs.item.comment.store', 'params[0]': item, 'params[1]': item_id};

    
    var params = {};
    params.content = $(this).parents('.comment-input').find($('textarea[name=content]')).eq(0).val();
    var comment_id = $(this).parents(".comment-article").attr("user-attr-comment-id");
    console.log(params);
    var routeInfo = {'name': 'bbs.item.comment.store', 'params[0]': item, 'params[1]': item_id, 'params[2]': comment_id};
    BBS.ajaxroute('post', routeInfo, params, function(resp) {
      console.log(resp);
      if(resp.error === false) {
        window.location.reload();
      }
    })
  });
})
</script>
@endsection
