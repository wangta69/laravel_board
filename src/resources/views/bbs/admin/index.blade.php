@extends($urlParams->dec['blade_extends'])
@section ('content')
<h2>게시판 리스트</h2>
<table class='table table-striped'>
<colgroup>
	<col width='50' />
	<col width='' />
	<col width='120' />
	<col width='120' />
	<col width='120' />
	<col width='200' />
</colgroup>
<thead>
	<tr>
		<th class='text-center'>#</th>
		<th class='text-center'>name</th>
		<th class='text-center'>table name</th>
		<th class='text-center'>skin</th>
		<th class='text-center'>created</th>
		<th class='text-center'></th>
	</tr>
</thead>
<tbody>
	@foreach ($list as $index => $board)
		<tr class="data-row" user-attr-board-id="{{$board->id}}">
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
			    {!! Html::link(route('bbs.admin.show', [ $board->id, 'urlParams='.$urlParams->enc]), 'edit', array('class' => 'btn btn-secondary btn-sm')) !!}
			    {!! Html::link(route('bbs.index', [$board->table_name, 'urlParams='.$urlParams->enc]), 'view', array('class' => 'btn btn-secondary btn-sm')) !!}
				{{ Form::button('Delete', array('class' => 'btn btn-danger btn-sm btn-delete')) }}

			                   <!--
			   				{!! Form::open([
			   					'method' => 'delete',
			   					'route' => ['bbs.admin.destroy', $board->id],
			   					'style'=>'display:inline'
			   				]) !!}

			   					{!! Form::submit('delete', [
			   						'class' => 'btn btn-danger btn-xs',
			   					]) !!}
			   				{!! Form::close() !!}
			   				-->
			</td>
		</tr>
	@endforeach
</tbody>
</table>

<div class='navigation'>
	{!! $list->render() !!}
</div>

<div class='btn-area text-right'>
	{!! Html::link(route('bbs.admin.create', ['urlParams='.$urlParams->enc]), 'create', [
		'role' => 'button',
		'class' => 'btn btn-primary btn-sm',
	]) !!}
</div>
@stop
@section ('scripts')
@parent
<script>
    $(function(){
       $(".btn-delete").click(function(){
           $this = $(this).parents(".data-row");
           var board_id = $this.attr("user-attr-board-id");

           if(confirm('삭제하시겠습니까?')){
               $.ajax({
                    url: '/bbs/admin/'+board_id+'/delete',
                    type: 'POST',
                    data:{
                    '_token': $('meta[name=csrf-token]').attr("content"),
                    '_method': 'DELETE',
                     },
                    success: function(result) {
                        // Do something with the result
                        $this.remove();
                    }
                });
           }
       })
    })
</script>
@endsection
