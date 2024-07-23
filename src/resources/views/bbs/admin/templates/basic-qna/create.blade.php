@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">


    <div class='basic-qna create'>
        <h2 class='title'>
            {{ $cfg->name }}
        </h2>
        @if (isset($article->id))
        <form method="post" 
            action="{{ route('bbs.admin.tbl.update', [$cfg->table_name, $article->id]) }}" 
            class='form-horizontal' 
            enctype='multipart/form-data'>
            @csrf
            @method('PUT')
        @else
        <form method="post" 
            action="{{ route('bbs.admin.tbl.store', [$cfg->table_name]) }}" 
            class='form-horizontal' 
            enctype='multipart/form-data'>
            @csrf

        @endif

        <input type="hidden" name="text_type" value="{{$cfg->editor == 'none' ? 'br' : 'html'}}">
        <input type="hidden" name="parent_id" value="{{isset($article) ? $article->id : ''}}">

        @if (!$errors->isEmpty())
        <div class="alert alert-danger" role="alert">
            {!! $errors->first() !!}
        </div>
        @endif

        <div class="form-group row">
            <div class='col-sm-12'>
            <input type="text" name="title" value="{{  isset($article) ? $article->title : old('title') }}" class='form-control input-sm' id='title' placeholder='문의 제목을 입력해 주세요'>
            </div>
        </div>
        <div class="form-group row">
            <div class='col-sm-12'>
                @if($cfg->editor == 'smartEditor')
                    @include ('editor::smart-editor.editor', ['name'=>'content', 'id'=>'content-id', 'value'=>isset($article) ? $article->content : old('content'), 
                    'attr'=>['class'=>'form-control input-sm', 'placeholder'=>'문의 내용을 입력해 주세요']])
                @else
                    <textarea name="content" class="form-control" placeholder='문의 내용을 입력해 주세요'>{{  isset($article) ? $article->content : old('content') }}</textarea>
                @endif
            </div>
        </div>

        <div class='form-group mt-5'>
            <div class='col-sm-12 text-right'>
            <button type="submit" class="btn btn-primary btn-sm">작성완료</button>
            <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>목록</a>
            </div>
        </div>
    </div>
    </form>
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style') @include ('bbs.admin.templates.'.$cfg->skin.'.css.style')

</style>
@stop

@section ('scripts')
@parent
<script>
    $('.file-control-btn').click(function () {
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
    });

</script>
@stop
