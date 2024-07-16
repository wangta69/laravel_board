@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">


    <div class='basic-qna create'>
        <h2 class='title'>
            {{ $cfg->name }}
        </h2>
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

        <div class="form-group row">
            <div class='col-sm-12'>
                {!! Form::text('title', isset($article) ? $article->title : old('title'), [
                'class' => 'form-control input-sm',
                'id' => 'title',
                'placeholder'=>'문의 제목을 입력해 주세요'
                ]) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class='col-sm-12'>
                @include ('bbs::plugins.editor', [
                'cfg'=>$cfg,
                'article'=>isset($article) ? $article:null,
                'attr'=> ['class' => 'form-control input-sm', 'placeholder'=>'문의 내용을 입력해 주세요']
                ])
            </div>
        </div>

        <div class='form-group mt-5'>
            <div class='col-sm-12 text-right'>
                {!! Form::submit('작성완료', [
                'class' => 'btn btn-primary btn-sm',
                ]) !!}
                {!! Html::link(route('bbs.admin.tbl.index', [$cfg->table_name]), '목록', [
                'class' => 'btn btn-secondary btn-sm',
                ]) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop

@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style') @include ('bbs.admin.templates.'.$cfg->skin_admin.'.css.style')

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
