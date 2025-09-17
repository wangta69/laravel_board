<x-dynamic-component 
  :component="config('pondol-bbs.component.admin.layout')" 
  :path="['게시판', $cfg->name, 'Write']"> 
<div class="container">
  <div class="bbs create">
    <h1 class='title'>
        {{ $cfg->name }}
    </h1>
    <div class="card">
      @if (isset($article->id))
      <form method="post" 
          action="{{ route('bbs.admin.tbl.update', [$cfg->table_name, $article->id]) }}" 
          enctype='multipart/form-data'>
          @method('PUT')

      @else
      <form method="post" 
          action="{{ route('bbs.admin.tbl.store', [$cfg->table_name]) }}" 
          enctype='multipart/form-data'>
      @endif
      @csrf
      <input type="hidden" name="text_type" value="{{$cfg->editor == 'none' ? 'br' : 'html'}}">
      <input type="hidden" name="parent_id" value="{{ $article->id ?? ''}}">

      <div class="card-body">
      @if (!$errors->isEmpty())
        <div class="alert alert-danger" role="alert">
            {!! $errors->first() !!}
        </div>
        @endif
        <table class="table">
          <colgroup>
            <col width='120' />
            <col width='' />
          </colgroup>
          @if(count($cfg->category))
            <tr>
              <th> @lang('bbs::messages.bbs.title.category')</th>
              <td>
              <select name="category" class="form-select">
              @foreach($cfg->category as  $v)
                <option value="{{$v->id}}">{{$v->name}}</option>
              @endforeach
              </select>
              </td>
            </tr>
            @endif
            <tr>
              <th> @lang('bbs::messages.bbs.title.title')</th>
              <td>
                <input type="text" name="title" value="{{ old('title', $article->title ?? null) }}" class='form-control input-sm' id='title'>
              </td>
            </tr>
            <tr>
              <th> @lang('bbs::messages.bbs.title.content')</th>
              <td>
                @if($cfg->editor)
                  @include ('editor::default', ['name'=>'content', 'id'=>'content-id', 'value'=>old('content', $article->content ?? null), 'attr'=>['class'=>'form-control input-sm']])
                @else
                  <textarea name="content" class="form-control">{{ old('content', $article->content ?? null) }}</textarea>
                @endif

              </td>
            </tr>
        </table>
      </div><!-- .card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-primary btn-sm"> @lang('bbs::messages.bbs.button.store')</button>
        <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>@lang('bbs::messages.bbs.button.list')</a>
      </div><!-- .card-footer -->
      </form>
    </div><!-- .card -->
  </div>
</div>


@section ('styles')
@parent
@include ('bbs.templates.admin.'.$cfg->skin_admin.'.style')
@stop

@section ('scripts')
@parent
@stop
</x-dynamic-component>