@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    @if (isset($table->id))
    <h2>게시판 수정</h2>
    {!! Form::open([
    'route' => ['bbs.admin.update', $table->id],
    'class' => 'form-horizontal',
    'method' => 'put'
    ]) !!}
    @else
    <h2>게시판 생성</h2>
    {!! Form::open([
    'route' => ['bbs.admin.store'],
    'class' => 'form-horizontal',
    'method' => 'post'
    ]) !!}
    @endif


    <div class='form-group row'>
        <label for='name' class='col-sm-2 control-label'>게시판 이름</label>
        <div class='col-sm-10'>
            {!! Form::text('name', isset($table) ? $table->name : old('name'), [
            'class' => 'form-control',
            'id' => 'name',
            'placeholder' => '게시판 이름',
            ]) !!}
        </div>
    </div>
    <div class='form-group row'>
        <label for='table_name' class='col-sm-2 control-label'>DB 테이블</label>
        <div class='col-sm-10'>
            {!! Form::text('table_name', isset($table) ? $table->table_name : old('table_name'), [
            'class' => 'form-control',
            'id' => 'table_name',
            'placeholder' => 'DB 테이블',
            ]) !!}


        </div>
    </div>
    <div class='form-group row'>
        <label for='skin' class='col-sm-2 control-label'>게시판 스킨(회원용)</label>
        <div class='col-sm-10'>
            {!!
            Form::select('skin', $skins, isset($table) ? $table->skin : null, ['class' => 'form-control', 'id' =>
            'skin'])
            !!}
        </div>
    </div>
    <div class='form-group row'>
        <label for='skin' class='col-sm-2 control-label'>게시판 스킨(관리자용)</label>
        <div class='col-sm-10'>
            {!!
            Form::select('skin_admin', $skins_admin, isset($table) ? $table->skin_admin : null, ['class' =>
            'form-control', 'id' => 'skin_admin'])
            !!}
        </div>
    </div>
    <div class='form-group row'>
        <label for='skin' class='col-sm-2 control-label'>Blade Extends</label>
        <div class='col-sm-10'>
            {!! Form::text('extends', isset($table) ? $table->extends : old('extends'), [
            'class' => 'form-control',
            'id' => 'extends',
            'placeholder' => 'extends',
            ]) !!}
        </div>
    </div>
    <div class='form-group row'>
        <label for='skin' class='col-sm-2 control-label'>Blade Section</label>
        <div class='col-sm-10'>
            {!! Form::text('section', isset($table) ? $table->section : old('section'), [
            'class' => 'form-control',
            'id' => 'section',
            'placeholder' => 'section',
            ]) !!}
        </div>
    </div>
    <div class='form-group row'>
        <label for='editor' class='col-sm-2 control-label'>editor</label>
        <div class='col-sm-10'>
            {!!
            Form::select('editor', $editors, isset($table) ? $table->editor : null, ['class' => 'form-control', 'id' =>
            'editor'])
            !!}
        </div>
    </div>

    <div class='form-group row'>
        <label for='roles-list' class='col-sm-2 control-label'>리스트접근권한</label>
        <div class='col-sm-10'>
            {{ Form::radio('auth_list', 'none')}}<label>비회원</label>
            {{ Form::radio('auth_list', 'login', true)}} <label>일반회원 </label>
            {{ Form::radio('auth_list', 'role')}} <label>특정회원</label>
            <select id="roles-list" name="roles-list[]" class="form-control" multiple="multiple"
                style="width: 100%; display:none;" autocomplete="off">
                @foreach($roles as $role)
                <option @if(isset($table) && $table->roles_list->find($role->id)) selected="selected" @endif
                    value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='form-group row'>
        <label for='roles-read' class='col-sm-2 control-label'>읽기접근권한</label>
        <div class='col-sm-10'>
            {{ Form::radio('auth_read', 'none')}}<label>비회원</label> {{ Form::radio('auth_read', 'login', true)}}
            <label>일반회원 </label> {{ Form::radio('auth_read', 'role')}} <label>특정회원</label>
            <select id="roles-read" name="roles-read[]" class="form-control" multiple="multiple"
                style="width: 100%; display:none;" autocomplete="off">
                @foreach($roles as $role)
                <option @if(isset($table) && $table->roles_read->find($role->id)) selected="selected" @endif
                    value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='form-group row'>
        <label for='roles-write' class='col-sm-2 control-label'>쓰기접근권한</label>
        <div class='col-sm-10'>
            {{ Form::radio('auth_write', 'none')}}<label>비회원</label>
            {{ Form::radio('auth_write', 'login', true)}} <label>일반회원 </label>
            {{ Form::radio('auth_write', 'role')}} <label>특정회원</label>
            <select id="roles-write" name="roles-write[]" class="form-control" multiple="multiple"
                style="width: 100%; display:none;" autocomplete="off">
                @foreach($roles as $role)
                <option @if(isset($table) && $table->roles_write->find($role->id)) selected="selected" @endif
                    value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='form-group row'>
        <label for='roles-write' class='col-sm-2 control-label'>옵션</label>
        <div class='col-sm-10'>
            {{ Form::checkbox('enable_reply', '1', isset($table) ? $table->enable_reply == 1 ? true : false : false )}}<label>댓글활성</label>
            {{ Form::checkbox('enable_comment', '1', isset($table) ? $table->enable_comment == 1 ? true : false : false)}}<label>코멘트
                활성 </label>
            {{ Form::checkbox('enable_qna', '1', isset($table) ? $table->enable_qna == 1 ? true : false : false)}}<label>1:1
                활성 </label>
            {{ Form::checkbox('enable_password', '1', isset($table) ? $table->enable_password == 1 ? true : false : false)}}<label>패스워드
                활성 </label> <!-- 비회원 운영시 패스워드로 처리 -->

        </div>
    </div>
    @if (isset($table->id))
    <div class='form-group row'>
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
                                <button type="button" class="btn btn-danger btn-sm delete-category">삭제</button>
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
    <div class='form-group row'>
        <label for='lists' class='col-sm-2 control-label'>페이지당 게시물 수</label>
        <div class='col-sm-10'>
            {!! Form::text('lists', isset($table) ? $table->lists : old('lists'), [
            'class' => 'form-control',
            'id' => 'lists',
            'placeholder' => '',
            ]) !!}
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
            {!! Form::submit('Update', [
            'class' => 'btn btn-primary btn-sm',
            ]) !!}
            @else
            {!! Form::submit('Create', [
            'class' => 'btn btn-primary btn-sm',
            ]) !!}
            @endif

            <a href="{{ url()->previous()}}" class="btn btn-secondary btn-sm">List</a>
        </div>
    </div>
    {!! Form::close() !!}
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
    var auth_list = '{{ isset($table->auth_list) ? $table->auth_list : '
    login '}}';
    var auth_read = '{{ isset($table->auth_read) ? $table->auth_read : '
    login '}}';
    var auth_write = '{{ isset($table->auth_write) ? $table->auth_write : '
    login '}}';

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
