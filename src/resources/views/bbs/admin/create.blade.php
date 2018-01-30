@extends($urlParams->dec['blade_extends'])
@section ('content')

@if (isset($cfg->id))
    <h2>게시판 수정</h2>
    {!! Form::open([
        'route' => ['bbs.admin.store', $cfg->id, 'urlParams='.$urlParams->enc],
        'class' => 'form-horizontal',
        'method' => 'put'
    ]) !!}
@else
    <h2>게시판 생성</h2>
    {!! Form::open([
        'route' => ['bbs.admin.create', 'urlParams='.$urlParams->enc],
        'class' => 'form-horizontal',
        'method' => 'post'
    ]) !!}
@endif
@if (!$errors->isEmpty())
    <div class="alert alert-danger" role="alert">
        {!! $errors->first() !!}
    </div>
@endif

<div class='form-group'>
	<label for='name' class='col-sm-2 control-label'>게시판 이름</label>
	<div class='col-sm-10'>
		{!! Form::text('name', isset($cfg) ? $cfg->name : old('name'), [
			'class' => 'form-control',
			'id' => 'name',
			'placeholder' => '게시판 이름',
		]) !!}
	</div>
</div>
<div class='form-group'>
	<label for='table_name' class='col-sm-2 control-label'>DB 테이블</label>
	<div class='col-sm-10'>
		{!! Form::text('table_name', isset($cfg) ? $cfg->table_name : old('table_name'), [
			'class' => 'form-control',
			'id' => 'table_name',
			'placeholder' => 'DB 테이블',
		]) !!}
		
		
	</div>
</div>
<div class='form-group'>
	<label for='skin' class='col-sm-2 control-label'>게시판 스킨</label>
	<div class='col-sm-10'>
		{!! 
        Form::select('skin', $skins, isset($cfg) ? $cfg->skin : null, ['class' => 'form-control', 'id' => 'skin'])
        !!}
	</div>
</div>
<div class='form-group'>
    <label for='editor' class='col-sm-2 control-label'>editor</label>
    <div class='col-sm-10'>
        {!! 
        Form::select('editor', $editors, isset($cfg) ? $cfg->editor : null, ['class' => 'form-control', 'id' => 'editor'])
        !!}
    </div>
</div>
<div class='form-group'>
    <label for='roles-read' class='col-sm-2 control-label'>읽기권한</label>
	<div class='col-sm-10'>
	    {{ Form::radio('auth_read', 'none')}}<label>비회원</label>    {{ Form::radio('auth_read', 'login', true)}} <label>일반회원 </label>  {{ Form::radio('auth_read', 'role')}} <label>특정회원</label> 
	    <select id="roles-read" name="roles-read[]" class="form-control" multiple="multiple" style="width: 100%; display:none;" autocomplete="off">
            @foreach($roles as $role)
                <option @if(isset($cfg) && $cfg->roles_read->find($role->id)) selected="selected" @endif value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
	</div>
</div>
<div class='form-group'>
     <label for='roles-write' class='col-sm-2 control-label'>쓰기권한</label>
    <div class='col-sm-10'>
        {{ Form::radio('auth_write', 'none')}}<label>비회원</label>    {{ Form::radio('auth_write', 'login', true)}} <label>일반회원 </label>  {{ Form::radio('auth_write', 'role')}} <label>특정회원</label> 
        <select id="roles-write" name="roles-write[]" class="form-control" multiple="multiple" style="width: 100%; display:none;" autocomplete="off">
            @foreach($roles as $role)
                <option @if(isset($cfg) && $cfg->roles_write->find($role->id)) selected="selected" @endif value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class='form-group'>
     <label for='roles-write' class='col-sm-2 control-label'>옵션</label>
    <div class='col-sm-10'>
        {{ Form::checkbox('enable_reply', '1', isset($cfg) ? $cfg->enable_reply == 1 ? true : false : false )}}<label>댓글활성</label>    {{ Form::checkbox('enable_comment', '1', isset($cfg) ? $cfg->enable_comment == 1 ? true : false : false)}} <label>코멘트 활성 </label> 
        
    </div>
</div>
<div class='form-group'>
    <div class='col-sm-12 text-right'>
        {!! Form::submit('Create', [
            'class' => 'btn btn-primary btn-sm',
        ]) !!}
        {!! Html::link(route('bbs.admin.index', ['urlParams='.$urlParams->enc]), 'List', [
            'class' => 'btn btn-default btn-sm',
        ]) !!}
    </div>
</div>
{!! Form::close() !!}
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style')
</style>
@stop

@section ('scripts')
@parent
<script>

var auth_read = '{{ isset($cfg->auth_read) ? $cfg->auth_read : 'login'}}';
var auth_write = '{{ isset($cfg->auth_write) ? $cfg->auth_write : 'login'}}';

$(function(){
    $("input[name=auth_read]").val([auth_read]);
    $("input[name=auth_write]").val([auth_write]);
    
    if(auth_read == 'role')
        $("#roles-read").show();
    
    if(auth_write == 'role')
        $("#roles-write").show();
        
    $("input[name='auth_read']").change(function(){
        $("#roles-read").hide();
        if($("input[name='auth_read']:checked").val() == 'role')
            $("#roles-read").show();
    });
    
    $("input[name='auth_write']").change(function(){
        $("#roles-write").hide();
        if($("input[name='auth_write']:checked").val() == 'role')
            $("#roles-write").show();
    });
    
});
</script>
@stop
