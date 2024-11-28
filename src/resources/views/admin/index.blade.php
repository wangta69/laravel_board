@section('title', 'BBS  관리')
<x-dynamic-component 
  :component="config('pondol-bbs.component.admin.layout')" 
  :path="['게시판', 'BBS  관리']"> 
<div class="container">
  <div class="bbs admin">
  <h2 class='title'>@lang('bbs::messages.admin.configure')</h2>
    <div class="card">
      <div class="card-header">@lang('bbs::messages.admin.title')</div>
      <div class="card-body">
        <table class='table table-striped'>
          <colgroup>
              <col width='50' />
              <col width='' />
              <col width='120' />
              <col width='120' />
              <col width='120' />
              <col width='350' />
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
                      <a href="{{route('bbs.admin.tbl.index', [$board->table_name]) }}" class="btn btn-primary btn-sm">View[Admin]</a>
                      <a href="{{route('bbs.index', [$board->table_name]) }}" class="btn btn-primary btn-sm">View[Front]</a>
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
  </div><!-- .bbs.admin -->
</div><!-- .container -->


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

      ROUTE.ajaxroute('DELETE', {
        route: 'bbs.admin.destroy', 
        segments: [board_id], 
        data: params
      }, function(resp) {
        $this.remove();
        if(resp.error === false) {
          window.location.reload();
        }
      })
    })
  })

</script>
@endsection
</x-dynamic-component>