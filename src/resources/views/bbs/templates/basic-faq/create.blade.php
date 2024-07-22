@extends($cfg->extends)
@section ($cfg->section)
<div class='basic-table create'>
    @if (isset($article->id))
    <form method="post" 
            action="{{ route('bbs.update', [$cfg->table_name, $article->id]) }}" 
            class='form-horizontal' 
            enctype='multipart/form-data'>
            @csrf
            @method('PUT')
    @else
    <form method="post" 
            action="{{ route('bbs.store', [$cfg->table_name]) }}" 
            class='form-horizontal' 
            enctype='multipart/form-data'>
            @csrf
    @endif

    <input type="hidden" name="text_type" value="{{$cfg->editor == 'none' ? 'br' : 'html'}}">
    <input type="hidden" name="parent_id" value="{{isset($article) ? $article->id : ''}}">
    <h1 class='title'>
        {{ $cfg->name }}
    </h1>
    @if (!$errors->isEmpty())
        <div class="alert alert-danger" role="alert">
            {!! $errors->first() !!}
        </div>
    @endif
    <table>
    <colgroup>
        <col width='120' />
        <col width='' />
    </colgroup>
    <thead>
        <tr>
            <th>질문</th>
            <td>
            <input type="text" name="title" value="{{  isset($article) ? $article->title : old('title') }}" class='form-control input-sm' id='title'>
            </td>
        </tr>
        <tr style="display: none;">
            <th>내용</th>
            <td style='padding: 5px 10px;'>
                @include ('bbs::plugins.editor', ['cfg'=>$cfg, 'article'=>isset($article) ? $article:null, 'attr'=> ['class' => 'form-control input-sm']])
            </td>
        </tr>
    </thead>
    </table>

    <div class='form-group'>
        <div class='col-sm-12 text-right'>
        <button type="submit" class="btn btn-primary btn-sm">작성완료</button>
        <a href="{{ route('bbs.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>목록</a>
        </div>
    </div>
</div>
</form>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.templates.'.$cfg->skin.'.css.style')
</style>
@stop

@section ('scripts')
@parent
<script>
$('.file-control-btn').click(function() {
    $(this).closest('li').find('input[type=file]').trigger('click');
});

$('.file-controls').change(function() {
    var $btnControl = $(this).closest('li').find('.file-control-btn');

    if ($(this).val() != '') {
        $btnControl.removeClass('btn-default');
        $btnControl.addClass('btn-primary');
    } else {
        $btnControl.removeClass('btn-primary');
        $btnControl.addClass('btn-default');
    }
});
</script>
@stop
