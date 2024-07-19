@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class="card">
        <div class="card-header">
            <h2>게시판 리스트</h2>
        </div>
        <div class="card-body">
            
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
                            {!! Html::link(route('bbs.admin.show', [ $board->id]), 'edit', array('class' => 'btn btn-secondary
                            btn-sm')) !!}
                            {!! Html::link(route('bbs.admin.tbl.index', [$board->table_name]), 'view', array('class' => 'btn
                            btn-secondary btn-sm')) !!}
                            {{ Form::button('Delete', array('class' => 'btn btn-danger btn-sm btn-delete')) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer ">
            <div class='navigation'>
                {!! $list->render() !!}
            </div>
            <div class='btn-area text-right'>
                {!! Html::link(route('bbs.admin.create', []), 'create', [
                'role' => 'button',
                'class' => 'btn btn-primary btn-sm',
                ]) !!}
            </div>
        </div>
    </div>


    <div class="card mt-5">
        <div class="card-header">
            <h2>관리자 환경 설정</h2>
        </div>
    {!! Form::open([
            'route' => ['bbs.admin.config.update'],
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>관리자용 Blade Extends
                    <th>
                    <td>{!! Form::text('extends', old('extends') ? old('extends') : $cfg->extends, [
                        'class' => 'form-control',
                        'placeholder' => 'Admin 용 blade extends',
                        ]) !!}</td>
                </tr>
                        <tr>
                    <th>관리자용 contents section
                    <th>
                    <td>{!! Form::text('section', old('section') ? old('section') : $cfg->section, [
                        'class' => 'form-control',
                        'placeholder' => 'Admin 용 blade section',
                        ]) !!}</td>
                </tr>
            </table>
        </div>
    <div class="card-footer ">
        {!! Form::submit('Update', [
        'class' => 'btn btn-primary btn-sm',
        ]) !!}
    </div>
    {!! Form::close() !!}
</div>
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
    $(function () {
        $(".btn-delete").on('click', function () {
            $this = $(this).parents(".data-row");
            var board_id = $this.attr("user-attr-board-id");

            if (confirm('삭제하시겠습니까?')) {
                $.ajax({
                    url: '/bbs/admin/' + board_id + '/delete',
                    type: 'POST',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'DELETE',
                    },
                    success: function (result) {
                        // Do something with the result
                        console.log('result>', result);
                        $this.remove();
                    }
                });
            }
        })
    })

</script>
@endsection
