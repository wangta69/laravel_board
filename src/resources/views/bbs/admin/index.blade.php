@extends(($blade_extends ? $blade_extends : 'bbs.layouts.default' ))
@section ('content')
<h2>게시판 리스트</h2>
<table class='table table-striped'>
<colgroup>
	<col width='50' />
	<col width='' />
	<col width='120' />
	<col width='120' />
	<col width='100' />
	<col width='180' />
</colgroup>
<thead>
	<tr>
		<th class='text-center'>#</th>
		<th class='text-center'>name</th>
		<th class='text-center'>category</th>
		<th class='text-center'>skin</th>
		<th class='text-center'>created</th>
		<th class='text-center'></th>
	</tr>
</thead>
<tbody>
	@foreach ($list as $index => $board)
		<tr>
			<td class='text-center'>
				{{ number_format($list->total() - $list->perPage() * ($list->currentPage() - 1) - $index) }}
			</td>
			<td class='text-center'>
				{{ $board->name }}
			</td>
			<td class='text-center'>
				{{ $board->table_name }}
			</td>
			<td class='text-center'>
				{{ $board->skin }}
			</td>
			<td class='text-center'>
				{{ date('Y-m-d', strtotime($board->created_at)) }}
			</td>
			<td class='text-center'>
			    {!! Html::link(route('bbs.admin.show', [ $board->id]), 'edit', array('class' => 'btn btn-default btn-xs')) !!}
			    {!! Html::link(route('bbs.index', $board->table_name), 'view', array('class' => 'btn btn-default btn-xs')) !!}

				{!! Form::open([
					'method' => 'delete',
					'route' => ['bbs.admin.destroy', $board->id],
					'style'=>'display:inline'
				]) !!}
				
					{!! Form::submit('delete', [
						'class' => 'btn btn-danger btn-xs',
					]) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach
</tbody>
</table>

<div class='navigation'>
	{!! $list->render() !!}
</div>

<div class='btn-area text-right'>
	{!! Html::link(route('bbs.admin.create'), 'create', [
		'role' => 'button',
		'class' => 'btn btn-primary btn-sm',
	]) !!}
</div>
@stop

@section ('script')

@stop
