@extends($cfg->extends)
@section ($cfg->section)
<div class="container">
  <div class="bbs admin">
    @if (isset($table->id))
    <h2 class='title'>@lang('bbs::messages.admin.bbs.edit')</h2>
    <form method="POST" action="{{route('bbs.admin.update', [$table->id]) }}" class="form-horizontal">
      @method('PUT')
      @else
    <h2 class='title'>@lang('bbs::messages.admin.bbs.create')</h2>
    <form method="POST" action="{{route('bbs.admin.store') }}" class="form-horizontal">
    @endif
      @csrf
      <div class="card">
          <div class="card-body">
          <div class='form-group row mt-1'>
            <label for='name' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.title')</label>
            <div class='col-sm-10'>
              <input type="text" name="name" class="form-control" id="name" placeholder="bbs title like 'free bbs for child'" value="{{isset($table) &&  $table->name ? $table->name : old('name')}}">
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='table_name' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.name')</label>
            <div class='col-sm-10'>
              <input type="text" name="table_name" id="table_name" class="form-control" placeholder="bbs name like 'free'" value="{{isset($table) && $table->table_name ? $table->table_name : old('table_name')}}">

            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.skin-user')</label>
            <div class='col-sm-10'>
              <select name="skin" class="form-select" id="skin">
                @foreach($skins as $k=>$v)
                <option value="{{$k}}" @if(isset($table) && $table->skin == $k) selected @endif>{{$v}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.skin-admin')</label>
            <div class='col-sm-10'>
              <select name="skin_admin" class="form-select" id="skin_admin">
                @foreach($skins_admin as $k=>$v)
                <option value="{{$k}}" @if(isset($table) && $table->skins_admin == $k) selected @endif>{{$v}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.blade-extends')</label>
            <div class='col-sm-10'>
              <input type="text" name="extends" id="extends" class="form-control" placeholder="extends" value="{{isset($table) && $table->extends ? $table->extends : old('extends')}}">
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.blade-section')</label>
            <div class='col-sm-10'>
              <input type="text" name="section" id="section" class="form-control" placeholder="extesectionnds" value="{{isset($table) && $table->section ? $table->section : old('section')}}">
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='editor' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.editor')</label>
            <div class='col-sm-10'>
              <select name="editor" class="form-select" id="editor">
                @foreach($editors as $k=>$v)
                <option value="{{$k}}" @if(isset($table) && $table->editor == $k) selected @endif>{{$v}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='roles-list' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.auth-list')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <input class="form-check-input" type="radio" name="auth_list" value="none">
                <label class="form-check-label"> @lang('bbs::messages.admin.bbs.a-none')</label>
              </div>
              <div class="col-auto">
                <input class="form-check-input" type="radio" name="auth_list" value="login" checked>
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-login')</label>
              </div>
              <div class="col-auto">
                <input class="form-check-input" type="radio" name="auth_list" value="role">
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-role')</label>
              </div>
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
            <label for='roles-read' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.auth-read')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <input class="form-check-input" type="radio" name="auth_read" value="none">
                <label class="form-check-label"> @lang('bbs::messages.admin.bbs.a-none')</label>
              </div>

              <div class="col-auto">
                <input class="form-check-input" type="radio" name="auth_read" value="login" checked>
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-login')</label>
              </div>

              <div class="col-auto">
                <input class="form-check-input" type="radio" name="auth_read" value="role">
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-role')</label>
              </div>

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
            <label for='roles-write' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.auth-write')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                  <input class="form-check-input" type="radio" name="auth_write" value="none">
                  <label class="form-check-label"> @lang('bbs::messages.admin.bbs.a-none')</label>
              </div>
              <div class="col-auto">
                  <input class="form-check-input" type="radio" name="auth_write" value="login" checked>
                  <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-login')</label>
              </div>
              <div class="col-auto">
                  <input class="form-check-input" type="radio" name="auth_write" value="role">
                  <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-role')</label>
              </div>
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
            <label for='roles-write' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.option')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <input class="form-check-input" type="checkbox" name="enable_reply" value="1" @if(isset($table) && $table->enable_reply == 1) checked @endif>
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.enable-reply')</label>
              </div>
              <div class="col-auto">
                <input class="form-check-input" type="checkbox" name="enable_comment" value="1" @if(isset($table) && $table->enable_comment == 1) checked @endif>
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.enable-comment')</label>
              </div>
              <div class="col-auto">
                <input class="form-check-input" type="checkbox" name="enable_qna" value="1" @if(isset($table) && $table->enable_qna == 1) checked @endif>
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.enable-qna')</label>
              </div>
              <div class="col-auto">
                <input class="form-check-input" type="checkbox" name="enable_password" value="1" @if(isset($table) && $table->enable_password == 1) checked @endif>
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.enable-password')</label>
              </div><!-- 비회원 운영시 패스워드로 처리 -->
            </div>
          </div>

          @if (isset($table->id))
          <div class='form-group row mt-1'>
            <label for='lists' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.category')</label>
            <div class='col-sm-10'>
              <div class='row'>
                <div class='col-6 input-group'>
                  <input class="form-control" placeholder="@lang('bbs::messages.admin.bbs.category-name')" name="category" type="text">
                  <button type="button" class="btn btn-info input-group-append" id="add-category">@lang('bbs::messages.button.create')</button>
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
                        <button type="button" class="btn btn-danger btn-sm delete-category">@lang('bbs::messages.button.delete')</button>
                      </td>
                    </tr>
                    @empty
                    <tr class="blank-category-list">
                      <td>@lang('bbs::messages.admin.bbs.category-message')</td>
                      <td></td>
                      <td></td>
                    </tr>
                    @endforelse
                  </table>
                </div>
              </div>
            </div>
          </div>
          @endif
          <div class='form-group row mt-1'>
            <label for='lists' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.lists-per-page')</label>
            <div class='col-sm-10'>
              <input type="text" name="lists" id="lists" class="form-control" value="{{isset($table) && $table->lists ? $table->lists : old('lists')}}">
            </div>
          </div>
        </div> <!-- .card-body -->
        <div class="card-footer">
          <div class='form-group'>

            @if (!$errors->isEmpty())
            <div class="alert alert-danger" role="alert">
                {!! $errors->first() !!}
            </div>
            @endif

            <div class='col-sm-12 text-right'>
              @if (isset($table->id))
              <button type="submit" class="btn btn-primary btn-sm">Update</button>
              @else
              <button type="submit" class="btn btn-primary btn-sm">Create</button>
              @endif

              <a href="{{ url()->previous()}}" class="btn btn-secondary btn-sm">List</a>
            </div>
          </div>
        </div><!-- .card-footer-->
      </div><!-- .card -->
    </form>
  </div>
</div><!-- .container -->
@stop

@section ('styles')
@parent
<style>
@include ('bbs::admin.css.style');
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
