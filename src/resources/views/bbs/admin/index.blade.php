@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
<!-- <div class="card">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
  </div>
  <div class="card-footer">
  </div>
</div> -->

  <div class="card">
    <div class="card-header">
    @lang('bbs::messages.admin.title')
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
                    <a href="{{route('bbs.admin.show', [ $board->id]) }}" class="btn btn-secondary btn-sm">Edit</a>
                    <a href="{{route('bbs.admin.tbl.index', [$board->table_name]) }}" class="btn btn-secondary btn-sm">View</a>
                    <button type="button" class="btn btn-danger btn-sm btn-delete">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class='navigation'>
      {!! $list->render() !!}
    </div>
    </div>
    <div class="card-footer">
      <a href="{{route('bbs.admin.create') }}" class="btn btn-primary" role="button">Create</a>
    </div>
  </div>


  
  <div class="card mt-5">
  <div class="card-header">
  @lang('bbs::messages.admin.layout')
   </div>
    <form method="POST" action="{{route('bbs.admin.config.update') }}" class="form-horizontal">
    @csrf
    @method('PUT')
    <div class="card-body">
      <table class="table">
        <tr>
          <th>@lang('bbs::messages.admin.layout-extends')<th>
          <td>
            <input type="text" name="extends" class="form-control"placeholder="Admin 용 blade extends" value="{{old('extends') ? old('extends') : $cfg->extends}}">
          </td>
        </tr>
        <tr>
          <th>@lang('bbs::messages.admin.layout-section')<th>
          <td>
            <input type="text" name="section" class="form-control"placeholder="Admin 용 blade section" value="{{old('section') ? old('section') : $cfg->section}}">
          </td>
        </tr>
    </table>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
    </form>
  </div>


  
</div>
@stop
@section ('styles')
@parent
<style>
  @include ('bbs::admin.css.style')
</style>
@stop
@section ('scripts')
@parent
<script>
  $(function () {
    $(".btn-delete").on('click', function () {
      $this = $(this).parents(".data-row");
      var board_id = $this.attr("user-attr-board-id");

      if (confirm('are you sure?')) {
        $.ajax({
          url: '/bbs/admin/' + board_id + '/delete',
          type: 'POST',
          data: {
            '_token': $('meta[name=csrf-token]').attr("content"),
            '_method': 'DELETE',
          },
          success: function (result) {
            // Do something with the result
            $this.remove();
          }
        });
      }
    })
  })

</script>
@endsection
