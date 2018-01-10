@extends($urlParams->dec['blade_extends'])
@section ('content')
<h2>게시판 생성/수정</h2>
@if (isset($cfg))
    {!! Form::open([
        'route' => ['bbs.admin.store', $cfg->id, 'urlParams='.$urlParams->enc],
        'class' => 'form-horizontal',
        'method' => 'put'
    ]) !!}
@else
    {!! Form::open([
        'route' => ['bbs.admin.store', 'urlParams='.$urlParams->enc],
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
        Form::select('skin', $skins, isset($cfg) ? $cfg->skin : null, ['class' => 'form-control'])
        !!}
	</div>
</div>
<div class='form-group'>
    <label for='skin' class='col-sm-2 control-label'>읽기권한</label>
	<div class='col-sm-10'>
	    <select id="roles" name="roles-read[]" class="form-control" multiple="multiple" style="width: 100%" autocomplete="off">
	        <option value="" @if(isset($cfg) && $cfg->roles_count('read') == 0) selected="selected" @endif>All</option>
            @foreach($roles as $role)
                <option @if(isset($cfg) && $cfg->roles_read->find($role->id)) selected="selected" @endif value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
	</div>
</div>
<div class='form-group'>
     <label for='skin' class='col-sm-2 control-label'>쓰기권한</label>
    <div class='col-sm-10'>
        
        <select id="roles" name="roles-write[]" class="form-control" multiple="multiple" style="width: 100%" autocomplete="off">
            <option value="" @if(isset($cfg) && $cfg->roles_count('write') == 0) selected="selected" @endif>All</option>
            @foreach($roles as $role)
                <option @if(isset($cfg) && $cfg->roles_write->find($role->id)) selected="selected" @endif value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class='form-group'>
    <div class='col-sm-12 text-right'>
        {!! Form::submit('Create', [
            'class' => 'btn btn-primary btn-sm',
        ]) !!}
        {!! Html::link(route('bbs.admin', ['urlParams='.$urlParams->enc]), 'List', [
            'class' => 'btn btn-default btn-sm',
        ]) !!}
    </div>
</div>
{!! Form::close() !!}
@stop