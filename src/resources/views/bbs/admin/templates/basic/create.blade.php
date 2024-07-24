@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic create'>
        <h1 class='title'>
            {{ $cfg->name }}
        </h1>
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
        <input type="hidden" name="parent_id" value="{{isset($article) ? $article->id : ''}}">

        
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
        <tr>
          <th> @lang('bbs::messages.bbs.title.title')</th>
          <td>
            <input type="text" name="title" value="{{  isset($article) ? $article->title : old('title') }}" class='form-control input-sm' id='title'>
          </td>
        </tr>
        <tr>
          <th> @lang('bbs::messages.bbs.title.content')</th>
          <td>
            @if($cfg->editor == 'smartEditor')
              @include ('editor::smart-editor.editor', ['name'=>'content', 'id'=>'content-id', 'value'=>isset($article) ? $article->content : old('content'), 'attr'=>['class'=>'form-control input-sm']])
            @else
              <textarea name="content" class="form-control">{{  isset($article) ? $article->content : old('content') }}</textarea>
            @endif

          </td>
        </tr>
        <tr class='file-control'>
          <th> @lang('bbs::messages.bbs.title.attached') <i class="fa fa-plus" id="add-file"></i></th>
          <td>
         

            <ul class='list-inline' id="file-box">
            @forelse ($article->files as $file)
            <!-- 파일 다운로드 경로 등을 넣으세요.. -->
            <li class="mt-1"><input type="file" name="uploads[]" class="form-control"> ({{$file->file_name}} <span class="delete-file" user-attr-file-id="{{$file->id}}">X</span>)</li>
            @empty
              <li class="mt-1">
                <input type="file" name="uploads[]" class="form-control">
              </li>
              @endforelse
            </ul>
          </td>
        </tr>
    </table>

    <div class='form-group col-sm-12 text-right'>

    <button type="submit" class="btn btn-primary btn-sm"> @lang('bbs::messages.bbs.button.store')</button>
    <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>@lang('bbs::messages.bbs.button.list')</a>
    </div>
  </div>
  </form>
</div>
@stop

@section ('styles')
@parent
<style>
  @include ('bbs.admin.css.style')
  @include ('bbs.templates.'.$cfg->skin_admin.'.css.style')
</style>
@stop

@section ('scripts')
@parent
<script src="/assets/pondol/bbs/bbs.js"></script>

<script>
  $(function(){
    // 첨부파일 추가
    $("#add-file").click('on', function() {
      $('#file-box').append(BBS.createFileElement());
    })

    // 첨부파일 삭제
    $(".delete-file").click('on', function() {
      var param = $(this).attr('user-attr-file-id');
      var $delElem = $(this).parents('li');
      BBS.ajaxroute('delete', {'name': 'bbs.file.delete', 'params[0]': param}, {}, function(resp) {
        $delElem.remove();
      })
    })
  })
</script>
@stop
