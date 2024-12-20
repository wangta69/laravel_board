var BBS = {
  _tbl_name:'',
  _article_id:0,
  _comment_id:0,
  csrf_token: $("meta[name=csrf-token]" ).attr("content"),
  get tbl_name(){
    return this._tbl_name;
  },
  set tbl_name(str){
    this._tbl_name = str;
  },
  set article_id(num){
    this._article_id = num;
  },
  get article_id(){
    return this._article_id;
  },
  set comment_id(num){
    this._comment_id = num;
  },
  get comment_id(){
    return this._comment_id;
  },

  createFileElement: function() {
    var ele = `<li class="mt-1">` + 
    `<input type="file" name="uploads[]" class="form-control">`;
    ele = ele + `<li>`;
    return ele;
  },
};
/*
var csrf_token = $("meta[name=csrf-token]" ).attr("content");
// delete comment
$(".comment-delete").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  if(confirm("삭제하시겠습니까?")){
    var data = {_token:csrf_token};
    $.ajax({
      url: '/bbs/'+BBS.tbl_name+'/'+BBS.article_id+'/comment/'+BBS.comment_id,
      type: 'DELETE',
      data: data,
      success: function(rep) {
        if(rep.result == false)
          alert(rep.message);
        else
          $this.remove();
      }
    });
  }else{
    return;
  }
});

// load comment form
$(".comment-update-form").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  //기존 열린창은 모두 닫기
  $(".comment-list .comment-article > ul > li.update").hide();

  $("li.view").show();
  //현재 창 열기
  $("li.view", $this).hide();
  $("li.update", $this).show();
//     console.log($("li.view .comment-content", $this).html());
  $("li.update .comment-update-content", $this).val($("li.view .comment-content", $this).html());
});
//comment update
$(".comment-update").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");

  var data = {_token:csrf_token, content:$("textarea[name=content]", $this).val()};

  $.ajax({
    url: '/bbs/'+BBS.tbl_name+'/'+BBS.article_id+'/comment/'+BBS.comment_id,
    type: 'PUT',
    data: data,
    success: function(rep) {
      if(rep.result == false)
        alert(rep.message);
      else{
        $("li.view", $this).show();
        $("li.update", $this).hide();
        $("li.view .comment-content", $this).html( $("li.update .comment-update-content", $this).val());
      }
    }
  });
});

//comment update cancel
$(".comment-update-cancel").on('click', function(){
  var $this = $(this).parents('article');
  $("li.view", $this).show();
  $("li.update", $this).hide();
});

//create comment reply form
$(".comment-reply-form").on('click', function(){
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  $(".re_comment").hide();

  $(".re_comment", $this).html($("#re_comment").html()).show();

});

//comment reply cancel
$( ".re_comment" ).on( "click", ".comment-reply-cancel", function() {
  $(this).parents(".re_comment").empty().hide();
});

//comment reply create
//답변의 답변일 경우 기존 답변의 comment id까지 구한다.
$( ".re_comment" ).on( "click", ".comment-reply-create", function() {
  var $this = $(this).parents('article');
  BBS.comment_id = $this.attr("user-attr-comment-id");
  var url= '/bbs/'+BBS.tbl_name+'/'+BBS.article_id+'/comment/'+BBS.comment_id;
  $.post(url, {_token:csrf_token, content:$("textarea[name=content]", $this).val()}, function(resp){
    if(resp.result == true){
      location.reload();
    }else{
      alert(resp.message);
    }
  });
});

*/
