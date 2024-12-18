<x-dynamic-component 
  :component="config('pondol-bbs.component.admin.layout')" 
  :path="['환경설정']"> 
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

              <x-pondol::text name="name" class="form-control" id="name" placeholder="bbs title like 'free bbs for child'" 
              value="{{isset($table) &&  $table->name ? $table->name : old('name')}}" />
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='table_name' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.name')</label>
            <div class='col-sm-10'>
            <x-pondol::text name="table_name" id="table_name" class="form-control" placeholder="bbs name like 'free'"
             value="{{isset($table) && $table->table_name ? $table->table_name : old('table_name')}}" />

            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.skin-user')</label>
            <div class='col-sm-10'>
              <x-pondol::select name="skin" class="form-select" id="skin" 
                :options="$skins" 
                value="{{ old('skin', $table->skin)}}" />
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'></div>
            <div class='col-sm-10'>
              {{resource_path('views/bbs/templates/user')}}
            </div>
          </div>


          <div class='form-group row mt-1'>
            <label for='skin_admin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.skin-admin')</label>
            <div class='col-sm-10'>
            <x-pondol::select name="skin_admin" class="form-select" id="skin_admin" 
                :options="$skins_admin" 
                value="{{ old('skin_admin', $table->skin_admin )}}" />
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-2'></div>
            <div class='col-sm-10'>
              {{resource_path('views/bbs/templates/admin')}}
            </div>
          </div>
          <div class='form-group row mt-1'>
            <label for='editor' class='col-sm-2 control-label'>Blade</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input act-switch-component" name="blade" value="component" 
                curval="{{ old('blade', isset($table) &&  $table->blade ? $table->blade :'component')}}"/>
                <label class="form-check-label">Component</label>

              </div>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input act-switch-component" name="blade" value="extends" 
                curval="{{ old('blade', isset($table) &&  $table->blade ? $table->blade :'component')}}" />
                <label class="form-check-label">Extends</label>
              </div>
            </div>
          </div>
          <div class='form-group row mt-1 blade-div'>
            <label for='skin' class='col-sm-2 control-label'>Component</label>
            <div class='col-sm-10'>
            <x-pondol::text name="component" id="component" class="form-control" placeholder="component" 
            value="{{isset($table) && $table->component ? $table->component : old('component')}}" />
            remove x- (x-myapp => myapp )
            </div>
          </div>
          <div class="blade-div" style="display: none;">
            <div class='form-group row mt-1'>
              <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.blade-extends')</label>
              <div class='col-sm-10'>
              <x-pondol::text name="extends" id="extends" class="form-control" placeholder="extends" 
              value="{{isset($table) && $table->extends ? $table->extends : old('extends')}}" />
              </div>
            </div>

            <div class='form-group row mt-1'>
              <label for='skin' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.blade-section')</label>
              <div class='col-sm-10'>
              <x-pondol::text name="section" id="section" class="form-control" placeholder="extesectionnds" 
              value="{{isset($table) && $table->section ? $table->section : old('section')}}" />
              </div>
            </div>
          </div>
          <div class='form-group row mt-1'>
            <label for='editor' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.editor')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="editor" value="0" 
                curval="{{ old('editor', isset($table) &&  $table->editor ? $table->editor :'0')}}"/>
                <label class="form-check-label">Disable</label>

              </div>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="editor" value="1" 
                curval="{{ old('editor', isset($table) &&  $table->editor ? $table->editor :'0')}}" />
                <label class="form-check-label">Enable</label>
              </div>
            </div>
          </div>
          <div class='form-group row mt-1'>
            <label for='roles-list' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.auth-list')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_list" value="none"
                curval="{{ old('auth_list', isset($table) &&  $table->auth_list ? $table->auth_list :'login')}}" />
                <label class="form-check-label"> @lang('bbs::messages.admin.bbs.a-none')</label>
              </div>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_list" value="login" 
                curval="{{ old('auth_list', isset($table) &&  $table->auth_list ? $table->auth_list :'login')}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-login')</label>
              </div>
              <div class="col-auto">
              <x-pondol::radio class="form-check-input" name="auth_list" value="role"
              curval="{{ old('auth_list', isset($table) &&  $table->auth_list ? $table->auth_list :'login')}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-role')</label>
              </div>

              <x-pondol::select id="roles-list" name="roles-list[]" class="form-select" multiple
                style="width: 100%; display:none;" autocomplete="off"
                :options="$roles" 
                option-label="name" 
                option-value="id" 
                :value="$table->roles_list" />
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='roles-read' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.auth-read')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_read" value="none"
                  curval="{{ old('auth_read', $table->auth_read ? $table->auth_read :'login')}}" />
                <label class="form-check-label"> @lang('bbs::messages.admin.bbs.a-none')</label>
              </div>

              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_read" value="login" 
                curval="{{ old('auth_read', isset($table) &&  $table->auth_read ? $table->auth_read :'login')}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-login')</label>
              </div>

              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_read" value="role"
                curval="{{ old('auth_read', isset($table) &&  $table->auth_read ? $table->auth_read :'login')}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-role')</label>
              </div>

              <x-pondol::select id="roles-read" name="roles-read[]" class="form-select" multiple="multiple"
                style="width: 100%; display:none;" autocomplete="off"
                :options="$roles" 
                option-label="name" 
                option-value="id" 
                :value="$table->roles_read"
                />
            </div>
          </div>

          <div class='form-group row mt-1'>
            <label for='roles-write' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.auth-write')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_write" value="none"
                curval="{{ old('auth_write', isset($table) &&  $table->auth_write ? $table->auth_write :'login')}}" />
                <label class="form-check-label"> @lang('bbs::messages.admin.bbs.a-none')</label>
              </div>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_write" value="login" 
                curval="{{ old('auth_write', isset($table) &&  $table->auth_write ? $table->auth_write :'login')}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-login')</label>
              </div>
              <div class="col-auto">
                <x-pondol::radio class="form-check-input" name="auth_write" value="role"
                curval="{{ old('auth_write', isset($table) &&  $table->auth_write ? $table->auth_write :'login')}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.a-role')</label>
              </div>
         
              <x-pondol::select id="roles-write" name="roles-write[]" class="form-select" multiple="multiple"
                style="width: 100%; display:none;" autocomplete="off"
                :options="$roles" 
                option-label="name" 
                option-value="id" 
                :value="$table->roles_write"
                />
            </div>
          </div>
    
          <div class='form-group row mt-1'>
            <label for='roles-write' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.option')</label>
            <div class='col-sm-10 row'>
              <div class="col-auto">
                <x-pondol::checkbox class="form-check-input" name="enable_comment" value="1" 
                  curval="{{ old('enable_comment', $table->enable_comment)}}" />
                <label class="form-check-label">@lang('bbs::messages.admin.bbs.enable-comment')</label>
              </div>
            </div>
          </div>

          @if (isset($table->id))
          <div class='form-group row mt-1'>
            <label for='lists' class='col-sm-2 control-label'>@lang('bbs::messages.admin.bbs.category')</label>
            <div class='col-sm-10'>
              <div class='row'>
                <div class='col-6 input-group'>
                    <x-pondol::text class="form-control" 
                    :placeholder="trans('bbs::messages.admin.bbs.category-name')" name="category" />
                  <button type="button" class="btn btn-info input-group-append" id="act-add-category">@lang('bbs::messages.button.create')</button>
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
                        <button type="button" class="btn btn-danger btn-sm act-delete-category">@lang('bbs::messages.button.delete')</button>
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
            <x-pondol::text name="lists" id="lists" class="form-control" 
              value="{{isset($table) && $table->lists ? $table->lists : old('lists')}}" />
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

              <a href="{{ route('bbs.admin.index') }}" class="btn btn-secondary btn-sm">List</a>
            </div>
          </div>
        </div><!-- .card-footer-->
      </div><!-- .card -->
    </form>
  </div>
</div><!-- .container -->

@section ('styles')
@parent
<style>
@include ('bbs::admin.css.style');
</style>
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script>
var table_id = '{{$table->id}}';


/**
  * @param Number id : catetory ID
  * @param String order : up | down
  */
function setCategoryOrder(id, order) {
  ROUTE.ajaxroute('PUT', {
    route: 'bbs.admin.category.update', 
    segments: [id, order]
  }, function(resp) {
    if(resp.error === false) {
    }
  })
}

function switchComponent() {
  var val = $('input:radio[name="blade"]:checked').val();
  $blade = $(".blade-div");
  $blade.hide();
  switch(val) {
    case 'extends': $blade.eq(1).show();break;
    case 'component': $blade.eq(0).show();break;
  }
}

function init() {
  if ($('input[name="auth_list"]:checked').val() == 'role') {
    $("#roles-list").show();
  }

  if ($('input[name="auth_read"]:checked').val() == 'role') {
    $("#roles-read").show();
  }

  if ($('input[name="auth_write"]:checked').val() == 'role') {
    $("#roles-write").show();
  }

  switchComponent();

}

$(function () {

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
  
  $("#act-add-category").click(function () {
    var category = $("input[name='category']").val();
    var url = '/bbs/admin/category/add/' + table_id

    ROUTE.ajaxroute('POST', {
      route: 'bbs.admin.category.store', 
      segments: [table_id],
      data: {category: category}
    }, function(resp) {
      if(resp.error === false) {
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
            '<button type="button" class="btn btn-danger btn-sm act-delete-category">삭제</button>';
          addLine = addLine + '</td>';
          addLine = addLine + '</tr>';
          $("#category-list").append(addLine);

          $(".blank-category-list").remove();
        } else {
            //    alert(res.error);
        }
    })
  })

  // 카테고리 삭제
  $(".act-delete-category").on('click', function () {
    var $tr = $(this).parents('tr');
    var categoryId = $tr.attr('user-attr-id');

    if (confirm('카테고리를 삭제하시겠습니까?')) {
      var url = '/bbs/admin/category/delete/' + categoryId;

      ROUTE.ajaxroute('DELETE', {
        route: 'bbs.admin.category.destroy', 
        segments: [categoryId]
      }, function(resp) {
        $tr.remove();
        if(resp.error === false) {
        }
      })
    }
  })

  $(".up").on('click', function (e) {
    var $tr = $(this).parents('tr');
    var categoryId = $tr.attr('user-attr-id');

    $tr.prev().before($tr);

    setCategoryOrder(categoryId, 'up');
  });

  $(".down").on('click', function (e) {
    var $tr = $(this).parents('tr');
    var categoryId = $tr.attr('user-attr-id');
    setCategoryOrder(categoryId, 'down');
    $tr.next().after($tr);
  });

  $(".act-switch-component").on('click', function(e){
    switchComponent();
  })
  // 카테고리 관련 끝

  init();
});
</script>
@stop

</x-dynamic-component>