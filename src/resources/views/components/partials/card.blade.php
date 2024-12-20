<div class="card me-1 mt-1" style="width: 18rem;">
    <div class="card-header d-flex justify-content-between"><div>{{$table->name}}</div> <a href="{{ route('bbs.index', [$table->table_name]) }}">[더보기]</a></div>
    <div class="card-body">
      <table class="table" style="table-layout: fixed;">
          @forelse ($items as $v)
          <tr>
              <td style="text-overflow:ellipsis; overflow:hidden; white-space:nowrap;"><a href="{{ route('bbs.show', [$table->table_name, $v->id]) }}">{{$v->title}}</a></td>
          </tr>
          @empty
          
          <tr>
              <td>내용이 없습니다.</td>
          </tr>
          @endforelse
      </table>
    </div>
</div>