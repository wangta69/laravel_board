@foreach($items as $v)
  <li class="{{ request()->is('bbs/admin/tbl/'.$v->table_name.'*') ? 'current-page' : '' }}">
    <a href="{{ route('bbs.admin.tbl.index', [$v->table_name]) }}">{{$v->name}}</a>
  </li>
@endforeach