@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
  @if (isset($table->id))
  <h2>게시판 수정</h2>
  <form method="post" href="{{ route('bbs.admin.update', [$table->id]) }}">
    @method('PUT')
  @else
  <h2>게시판 생성</h2>
  <form method="post" href="{{ route('bbs.admin.store') }}">
  @endif
  @csrf


  <div class='form-group row'>
    <label for='name' class='col-sm-2 control-label'>게시판 Title</label>
    <div class='col-sm-10'>
      <input type="text" name='name' value="{{ isset($table) ? $table->name : old('name')}}"
      class='form-control'
      id='name'
      placeholder='게시판 Title(자유게시판)'
      >
    </div>
  </div>
  <div class='form-group row mt-1'>
    <label for='table_name' class='col-sm-2 control-label'>게시판 Name</label>
    <div class='col-sm-10'>
      <input type="text" name='table_name' value="{{ isset($table) ? $table->table_name : old('table_name')}}"
      class='form-control'
      id='table_name'
      placeholder='게시판 Name(free)'
      >
    </div>
  </div>
  <div class='form-group row mt-1'>
    <label for='skin' class='col-sm-2 control-label'>게시판 스킨(회원용)</label>
    <div class='col-sm-10'>
      <select name="skin" class='form-control' id='skin'>
        @foreach($skins as $v)
        <option value="{{$v}}" @if(isset($table) && $table->skin == $v) selected @endif>{{$v}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class='form-group row mt-1'>
    <label for='skin' class='col-sm-2 control-label'>게시판 스킨(관리자용)</label>
    <div class='col-sm-10'>
      <select name="skin_admin" class='form-control' id='skin_admin'>
        @foreach($skins_admin as $v)
        <option value="{{$v}}" @if(isset($table) && $table->skin_admin == $v) selected @endif>{{$v}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class='form-group row mt-1'>
    <label for='skin' class='col-sm-2 control-label'>Blade Extends</label>
    <div class='col-sm-10'>
      <input type="text" name="extends" value="{{ isset($table) ? $table->extends : old('extends')}}"
        class='form-control'
        id='extends'
        placeholder='extends'
        >

    </div>
  </div>
  <div class='form-group row mt-1'>
      <label for='skin' class='col-sm-2 control-label'>Blade Section</label>
      <div class='col-sm-10'>
          <input type="text" name="section" value="{{ isset($table) ? $table->section : old('section')}}"
          class='form-control'
          id='section'
          placeholder='section'
          >
      </div>
  </div>
  <div class='form-group row mt-1'>
      <label for='editor' class='col-sm-2 control-label'>editor</label>
      <div class='col-sm-10'>
          <select name="editor" class='form-control' id='editor'>
              @foreach($editors as $v)
              <option value="{{$v}}" @if(isset($table) && $table->editor == $v) selected @endif>{{$v}}</option>
              @endforeach
          </select>
      </div>
  </div>

  <div class='form-group row mt-1'>
    <label for='roles-list' class='col-sm-2 control-label'>리스트접근권한</label>
    <div class='col-sm-10'>
      <input class="form-check-input" name="auth_list" type="radio" value="none"><label class="form-check-label">비회원</label>
      <input class="form-check-input" name="auth_list" type="radio" value="login" checked><label class="form-check-label">일반회원 </label>
      <input class="form-check-input" name="auth_list" type="radio" value="role"><label class="form-check-label">특정회원</label>

      <select id="roles-list" name="roles-list[]" class="form-select" multiple="multiple"
        style="width: 100%; display:none;" autocomplete="off">
        @foreach($roles as $role)
        <option @if(isset($table) && $table->roles_list->find($role->id)) selected="selected" @endif
            value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class='form-group row mt-1'>
      <label for='roles-read' class='col-sm-2 control-label'>읽기접근권한</label>
      <div class='col-sm-10'>

      <input class="form-check-input" name="auth_read" type="radio" value="none"><label class="form-check-label">비회원</label>
      <input class="form-check-input" name="auth_read" type="radio" value="login" checked><label class="form-check-label">일반회원 </label>
      <input class="form-check-input" name="auth_read" type="radio" value="role"><label class="form-check-label">특정회원</label>

      <select id="roles-read" name="roles-read[]" class="form-select" multiple="multiple"
        style="width: 100%; display:none;" autocomplete="off">
        @foreach($roles as $role)
        <option @if(isset($table) && $table->roles_read->find($role->id)) selected="selected" @endif
            value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class='form-group row mt-1'>
    <label for='roles-write' class='col-sm-2 control-label'>쓰기접근권한</label>
    <div class='col-sm-10'>
      
      <input class="form-check-input" name="auth_write" type="radio" value="none"><label class="form-check-label">비회원</label>
      <input class="form-check-input" name="auth_write" type="radio" value="login" checked><label class="form-check-label">일반회원 </label>
      <input class="form-check-input" name="auth_write" type="radio" value="role"><label class="form-check-label">특정회원</label>
      
      <select id="roles-write" name="roles-write[]" class="form-select" multiple="multiple"
        style="width: 100%; display:none;" autocomplete="off">
        @foreach($roles as $role)
        <option @if(isset($table) && $table->roles_write->find($role->id)) selected="selected" @endif
            value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class='form-group row mt-1'>
    <label for='roles-write' class='col-sm-2 control-label'>옵션</label>
    <div class='col-sm-10'>
      <input class="form-check-input" name="enable_reply" type="checkbox" value="1" 
        {{ isset($table) ? $table->enable_reply == 1 ? true : false : false }} ><label class="form-check-label">댓글 활성</label>

     <input class="form-check-input" name="enable_comment" type="checkbox" value="1"
      {{ isset($table) ? $table->enable_comment == 1 ? true : false : false }}><label class="form-check-label">코멘트 활성</label>

      <input class="form-check-input" name="enable_qna" type="checkbox" value="1"
        {{ isset($table) && $table->enable_qna == 1 ? 'checked' : '' }}><label class="form-check-label">1:1</label>

      <input class="form-check-input" name="enable_password" type="checkbox" value="1"
        {{ isset($table) && $table->enable_password == 1 ? 'checked' : '' }} ><label class="form-check-label">패스워드 활성</label>

    </div>
  </div>
  @if (isset($table->id))
  <div class='form-group row mt-1'>
      <label for='lists' class='col-sm-2 control-label'>카테고리</label>
      <div class='col-sm-10'>
          <div class='row'>
              <div class='col-6 input-group'>
                  <input class="form-control" placeholder="카테고리명" name="category" type="text">
                  <button type="button" class="btn btn-info btn-sm input-group-append" id="add-category"
                      style="height: 33px;">생성</button>
              </div>
              <div class='col-6'>
                  <table class='table table-borderless table-striped' id="category-list">

                      @forelse($table->category as $category)
                      <tr user-attr-id="{{ $category->id }}">
                          <td>{{$category->name}}</td>
                          <td>
                              <div class="order-btn">
                                  <span class="up">▲</span>
                                  <span class="down">▼</span>
                              </div>
                          </td>
                          <td>
                              <button type="button" class="btn btn-danger delete-category">삭제</button>
                          </td>
                      </tr>
                      @empty
                      <tr class="blank-category-list">
                          <td>카테고리를 등록해 주세요</td>
                          <td>
                          </td>
                          <td>
                          </td>
                      </tr>
                      @endforelse
                  </table>
              </div>
          </div>
      </div>
  </div>
  @endif
  <div class='form-group row mt-1'>
    <label for='lists' class='col-sm-2 control-label'>페이지당 게시물 수</label>
    <div class='col-sm-10'>
      <input type="text" name='lists' value="{{ isset($table) ? $table->lists : old('lists')}}"
        class='form-control'
        id='lists'
        placeholder=''
        >
    </div>
  </div>
  <div class='form-group'>

      @if (!$errors->isEmpty())
      <div class="alert alert-danger" role="alert">
        {!! $errors->first() !!}
      </div>
      @endif

      <div class='col-sm-12 text-right'>
        @if (isset($table->id))
        <input class="btn btn-primary" type="submit" value="Update">
        @else
        <input class="btn btn-primary" type="submit" value="Create">
        @endif

        <a href="{{ url()->previous()}}" class="btn btn-secondary btn-sm">List</a>
      </div>
  </div>
  </form>
</div>
@stop

@section ('styles')
@parent
<style>
  @include ('bbs.admin.css.style');

</style>
@stop

@section ('scripts')
@parent
<script>
  var table_id = '{{$table->id}}';
  var auth_list = '{{ isset($table->auth_list) ? $table->auth_list : 'login'}}';
  var auth_read = '{{ isset($table->auth_read) ? $table->auth_read : 'login'}}';
  var auth_write = '{{ isset($table->auth_write) ? $table->auth_write : 'login'}}';

  /**
   * @param Number id : catetory ID
   * @param String order : up | down
   */
  function setCategoryOrder(id, order) {
    var url = '/bbs/admin/category/update/' + id + '/' + order;
    $.ajax({
      url: url,
      method: 'PUT',
      //        data: {category},
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (res) {
        if (res.error === false) {
          // $( '.btn-active' ).removeClass( 'active' );
          // $('.btn-active').eq(i).addClass('active');
          //    window.location.reload();
        } else {
          //    alert(res.error);
        }
        // window.location.reload();
      }
    });
  }

  $(function () {
    $("input[name=auth_list]").val([auth_list]);
    $("input[name=auth_read]").val([auth_read]);
    $("input[name=auth_write]").val([auth_write]);

    if (auth_list == 'role') {
      $("#roles-list").show();
    }

    if (auth_read == 'role') {
      $("#roles-read").show();
    }

    if (auth_write == 'role') {
      $("#roles-write").show();
    }

    $("input[name='auth_list']").change(function () {
      $("#roles-list").hide();
      if ($("input[name='auth_list']:checked").val() == 'role')
        $("#roles-list").show();
    });

    $("input[name='auth_read']").change(function () {
      $("#roles-read").hide();
      if ($("input[name='auth_read']:checked").val() == 'role')
        $("#roles-read").show();
    });

    $("input[name='auth_write']").change(function () {
      $("#roles-write").hide();
      if ($("input[name='auth_write']:checked").val() == 'role')
        $("#roles-write").show();
    });

    // 카테고리 관련 시작
    // 카테고리 등록
    $("#add-category").click(function () {
      var category = $("input[name='category']").val();
      var url = '/bbs/admin/category/add/' + table_id
      $.ajax({
        url: url,
        method: 'POST',
        data: {
          category
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
          if (res.error === false) {
            var addLine = '<tr user-attr-id="' + category + '">';
            addLine = addLine + '<td>' + category + '</td>';
            addLine = addLine + '<td>';
            addLine = addLine + '<div class="order-btn">';
            addLine = addLine + '<span class="up">▲</span>';
            addLine = addLine + '<span class="down">▼</span>';
            addLine = addLine + '</div>';
            addLine = addLine + '</td>';
            addLine = addLine + '<td>';
            addLine = addLine +
              '<button type="button" class="btn btn-danger btn-sm delete-category">삭제</button>';
            addLine = addLine + '</td>';
            addLine = addLine + '</tr>';
            $("#category-list").append(addLine);

            $(".blank-category-list").remove();
          } else {
            //    alert(res.error);
          }
          // window.location.reload();
        }
      });
    });

    // 카테고리 삭제
    $(".delete-category").click(function () {
      var $tr = $(this).parents('tr');
      var categoryId = $tr.attr('user-attr-id');

      if (confirm('카테고리를 삭제하시겠습니까?')) {
        var url = '/bbs/admin/category/delete/' + categoryId;
        $.ajax({
          url: url,
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (res) {
            $tr.remove();
            if (res.error === false) {} else {
                //    alert(res.error);
            }
            // window.location.reload();
          }
        });
      }
    })

    $(".up").click(function (e) {
      var $tr = $(this).parents('tr');
      var categoryId = $tr.attr('user-attr-id');

      $tr.prev().before($tr);

      setCategoryOrder(categoryId, 'up');
    });

    $(".down").click(function (e) {
      var $tr = $(this).parents('tr');
      var categoryId = $tr.attr('user-attr-id');
      setCategoryOrder(categoryId, 'down');
      $tr.next().after($tr);

    });
    // 카테고리 관련 끝
  });

</script>
@stop
