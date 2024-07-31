@extends($cfg->extends)
@section ($cfg->section)
<div class="container">
  <div class="bbs create">
    <h1 class='title'>
        {{ $cfg->name }}
    </h1>
    <div class="card">
      @if (isset($article->id))
      <form method="post" 
          action="{{ route('bbs.update', [$cfg->table_name, $article->id]) }}" 
          enctype='multipart/form-data'>
          @method('PUT')

      @else
      <form method="post" 
          action="{{ route('bbs.store', [$cfg->table_name]) }}" 
          enctype='multipart/form-data'>
      @endif
      @csrf
      <input type="hidden" name="text_type" value="{{$cfg->editor == 'none' ? 'br' : 'html'}}">
      <input type="hidden" name="parent_id" value="{{isset($article) ? $article->id : ''}}">

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
            <tr>
              <th> @lang('bbs::messages.bbs.title.title')</th>
              <td>
                <input type="text" name="title" value="{{  isset($article) ? $article->title : old('title') }}" class='form-control input-sm' id='title'>
              </td>
            </tr>
            <tr>
              <th> @lang('bbs::messages.bbs.title.writer')</th>
              <td>
                <input type="text" name="writer" value="{{  isset($article) ? $article->writer : old('writer') }}" class='form-control input-sm' id='writer'>
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
                  <li class="mt-1">
                    <input type="file" name="uploads[]" class="form-control" style="display: none;"> 
                    <label class="align-top">{{$file->file_name}}</label> 
                    <button type="button" class="btn-close delete-file" aria-label="Delete"  user-attr-file-id="{{$file->id}}"></button>
                  </li>
                @empty
                  <li class="mt-1">
                    <input type="file" name="uploads[]" class="form-control">
                  </li>
                  @endforelse
                </ul>
              </td>
            </tr>
            <tr>
              <th> @lang('bbs::messages.bbs.title.keywords')</th>
              <td>
                <input type="text" name="keywords" value="{{  isset($article) ? $article->keywords : old('keywords') }}" class='form-control input-sm' id='writer'>
              </td>
            </tr>
        </table>
      </div><!-- .card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-primary btn-sm"> @lang('bbs::messages.bbs.button.store')</button>
        <a href="{{ route('bbs.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>@lang('bbs::messages.bbs.button.list')</a>
      </div><!-- .card-footer -->
      </form>
    </div><!-- .card -->
  </div>
</div>
@stop

@section ('styles')
@parent
@include ('bbs.templates.user.'.$cfg->skin.'.style')
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
