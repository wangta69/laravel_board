var BBS = {
  _tbl_name: "",
  _article_id: 0,
  _comment_id: 0,
  csrf_token: "",

  // 초기화 함수: jQuery가 로드된 후 호출해야 합니다.
  init: function () {
    this.csrf_token = $("meta[name=csrf-token]").attr("content");
    this.bindEvents(); // 이벤트 바인딩 실행
  },
  get tbl_name() {
    return this._tbl_name;
  },
  set tbl_name(str) {
    this._tbl_name = str;
  },
  set article_id(num) {
    this._article_id = num;
  },
  get article_id() {
    return this._article_id;
  },
  set comment_id(num) {
    this._comment_id = num;
  },
  get comment_id() {
    return this._comment_id;
  },

  createFileElement: function () {
    var ele =
      `<li class="mt-1">` +
      `<input type="file" name="uploads[]" class="form-control">`;
    ele = ele + `<li>`;
    return ele;
  },
  // 이벤트 바인딩 (주석 처리되었던 로직들을 활성화)
  bindEvents: function () {
    var self = this;

    // 1. 댓글 수정 폼 열기
    $(document).on("click", ".comment-update-form", function () {
      var $article = $(this).parents("article");
      self.comment_id = $article.attr("user-attr-comment-id");

      $(".comment-list li.update").hide(); // 다른 열린 폼 닫기
      $(".comment-list li.view").show();

      $article.find("li.view").hide();
      $article.find("li.update").show();

      var currentContent = $article
        .find("li.view .comment-content")
        .html()
        .replace(/<br\s*\/?>/gm, "\n")
        .trim();
      $article.find("li.update .comment-update-content").val(currentContent);
    });

    // 2. 실제 댓글 수정 처리 (AJAX)
    $(document).on("click", ".comment-update", function () {
      var $article = $(this).parents("article");
      var content = $article.find("textarea[name=content]").val();

      $.ajax({
        url:
          "/bbs/" +
          self.tbl_name +
          "/" +
          self.article_id +
          "/comment/" +
          self.comment_id,
        type: "PUT",
        data: { _token: self.csrf_token, content: content },
        success: function (rep) {
          if (rep.result == false) {
            alert(rep.message);
          } else {
            $article
              .find("li.view .comment-content")
              .html(content.replace(/\n/g, "<br>"));
            $article.find("li.view").show();
            $article.find("li.update").hide();
          }
        },
      });
    });

    // 3. 수정 취소
    $(document).on("click", ".comment-update-cancel", function () {
      var $article = $(this).parents("article");
      $article.find("li.view").show();
      $article.find("li.update").hide();
    });

    // 4. 댓글 삭제
    $(document).on("click", ".comment-delete", function () {
      var $article = $(this).parents("article");
      var commentId = $article.attr("user-attr-comment-id");
      if (confirm("정말 삭제하시겠습니까?")) {
        $.ajax({
          url:
            "/bbs/" +
            self.tbl_name +
            "/" +
            self.article_id +
            "/comment/" +
            commentId,
          type: "DELETE",
          data: { _token: self.csrf_token },
          success: function (rep) {
            if (rep.result) $article.remove();
            else alert(rep.message);
          },
        });
      }
    });
  },
};
