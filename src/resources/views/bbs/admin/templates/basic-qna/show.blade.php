@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class="basic-qna view">
        <h2 class='title'>
            {{ $cfg->name }}
        </h2>
        <div class="form-group row title">
            <div class='col-sm-8'>
                {{ $article->title }}
            </div>
            <div class='col-sm-4 text-right'>
                {{ date('Y-m-d', strtotime($article->created_at)) }}
            </div>
        </div>


        <div class="form-group row content">
            <div class='col-sm-12'>
                {!! nl2br($article->content) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class='col-sm-12 text-right'>
                {!! Form::open([
                'route' => ['bbs.admin.tbl.destroy', $cfg->table_name, $article->id],
                'method' => 'delete',
                ]) !!}
                @if ($article->isOwner(Auth::user()) || $isAdmin)
                {!! Html::link(route('bbs.admin.tbl.edit', [$cfg->table_name, $article->id]), '수정', [
                'role' => 'button',
                'class' => 'btn btn-primary btn-sm',
                ]) !!}
                {!! Form::submit('삭제', [
                'class' => 'btn btn-danger btn-sm',
                ]) !!}
                @endif
                {!! Html::link(route('bbs.admin.tbl.index', [$cfg->table_name]), '목록', [
                'class' => 'btn btn-secondary btn-sm',
                ]) !!}
                {!! Form::close() !!}
            </div>
        </div>

        @if ($cfg->enable_comment == 1)
        @include ('bbs.admin.templates.basic-qna.comment')
        @endif
        @stop
    </div>
</div>
@section ('styles')
@parent
<style>
    @include ('bbs.admin.css.style') 
    @include ('bbs.admin.templates.'.$cfg->skin.'.css.style')
</style>
@stop

@section ('scripts')
@parent
<script type="text/javascript" src="/assets/pondol/bbs/bbs.js"></script>
<script>
    BBS.tbl_name = "{{$cfg->table_name}}";
    BBS.article_id = {
        {
            $article - > id
        }
    };

</script>
@stop
