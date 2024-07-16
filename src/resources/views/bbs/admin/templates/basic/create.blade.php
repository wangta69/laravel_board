@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
  <div class='basic create'>
    <h1 class='title'>{{ $cfg->name }}</h1>
    @if (isset($article->id))
      {!! Form::open([
      'route' => ['bbs.admin.tbl.update', $cfg->table_name, $article->id],
      'class' => 'form-horizontal',
      'method' => 'put',
      'enctype' => 'multipart/form-data',
      ]) !!}
    @else
      {!! Form::open([
      'route' => ['bbs.admin.tbl.store', $cfg->table_name],
      'class' => 'form-horizontal',
      'enctype' => 'multipart/form-data',
      ]) !!}
    @endif

    {{ Form::hidden('text_type', $cfg->editor == 'none' ? 'br' : 'html') }}
    {{ Form::hidden('parent_id', isset($article) ? $article->id : '') }}

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
          <th>제목</th>
          <td>
              {!! Form::text('title', isset($article) ? $article->title : old('title'), [
              'class' => 'form-control input-sm',
              'id' => 'title'
              ]) !!}
          </td>
        </tr>
        <tr>
          <th>내용</th>
          <td>
              @include ('bbs::plugins.editor', ['cfg'=>$cfg, 'article'=>isset($article) ? $article:null,
              'attr'=> ['class' => 'form-control input-sm']])
          </td>
        </tr>
        <tr class='file-control'>
          <th>파일 <i class="fa fa-plus" id="add-file"></i></th>
          <td>
         

            <ul class='list-inline' id="file-box">
            @forelse ($article->files as $file)
            <!-- 파일 다운로드 경로 등을 넣으세요.. -->
            <li><input type="file" name="uploads[]" class="file-controls"> ({{$file->file_name}} <span class="delete-file" user-attr-file-id="{{$file->id}}">X</span>)</li>
            @empty
              <li>
                <input type="file" name="uploads[]" class="file-controls">
              </li>
              @endforelse
            </ul>
          </td>
        </tr>
    </table>

    <div class='form-group col-sm-12 text-right'>

      {!! Form::submit('작성완료', [
      'class' => 'btn btn-primary btn-sm',
      ]) !!}
      {!! Html::link(route('bbs.admin.tbl.index', [$cfg->table_name]), '목록', [
      'class' => 'btn btn-default btn-sm',
      ]) !!}
    </div>
  </div>
  {!! Form::close() !!}
</div>
@stop

@section ('styles')
@parent
<style>
  @include ('bbs.admin.css.style')
  @include ('bbs::templates.'.$cfg->skin_admin.'.css.style')
</style>
@stop

@section ('scripts')
@parent
{{ Html::script('assets/pondol/bbs/bbs.js') }}

<script>
  function createFileElements(src) {
    var ele = `<li>` + 
    `<input type="file" name="uploads[]" class="file-controls">`;
    ele = ele + `<li>`;
    return ele;
  }

  $(function(){
    // 첨부파일 추가
    $("#add-file").click('on', function() {
      $elm = createFileElements();
      $('#file-box').append($elm);
      /* var index = $(".category-box").length; */
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
  /* $('.file-control-btn').click(function () {
    $(this).closest('li').find('input[type=file]').trigger('click');
  });

  $('.file-controls').change(function () {
    var $btnControl = $(this).closest('li').find('.file-control-btn');

    if ($(this).val() != '') {
      $btnControl.removeClass('btn-default');
      $btnControl.addClass('btn-primary');
    } else {
      $btnControl.removeClass('btn-primary');
      $btnControl.addClass('btn-default');
    }
  }); */

</script>
@stop
