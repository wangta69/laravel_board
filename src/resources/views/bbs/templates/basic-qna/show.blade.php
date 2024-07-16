@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-view">
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
                'route' => ['bbs.destroy', $cfg->table_name, $article->id],
                'method' => 'delete',
            ]) !!}
                @if ($article->isOwner(Auth::user()) || $isAdmin)
                    {!! Html::link(route('bbs.edit', [$cfg->table_name, $article->id]), '수정', [
                        'role' => 'button',
                        'class' => 'btn btn-primary btn-sm',
                    ]) !!}
                    {!! Form::submit('삭제', [
                        'class' => 'btn btn-danger btn-sm',
                    ]) !!}
                @endif
                {!! Html::link(route('bbs.index', [$cfg->table_name]), '목록', [
                    'class' => 'btn btn-secondary btn-sm',
                ]) !!}
            {!! Form::close() !!}
        </div>
    </div>

    <section class="comment-list">

         @forelse ($article->comments()->orderBy('order_num')->orderBy('reply_depth')->get() as $comment)
            <!-- 파일 다운로드 경로 등을 넣으세요.. -->
            <article class="comment-article" user-attr-comment-id="{{ $comment->id }}">
                <ul>
                    <li class="view depth-{{ strlen($comment->reply_depth) }}">
                       <header>
                            <div class="info">
                                <span class="comment-time-info"><time datetime="{{ $comment->created_at }}">{{ date("Y-m-d H:i", strtotime($comment->created_at))}}</time></span>
                            </div>
                        </header>
                        <!-- 댓글 출력 -->


                        <div class='comment-content'>{{ $comment->content }}</div>
                        <div class='footer'>

                            @if ($comment->isOwner(Auth::user()))
                                {!! Form::button('수정', [
                                    'class' => 'btn btn-primary btn-sm comment-update-form',
                                ]) !!}
                                {!! Form::button('삭제', [
                                    'class' => 'btn btn-danger btn-sm comment-delete',
                                ]) !!}
                            @endif
                        </div>
                        <!--
                        <div class="re_comment">

                        </div> 답변 -->
                    </li>

                </ul>
            </article>
        @empty
        @endforelse
    </section>
@if ($cfg->enable_comment == 1)
@include ('bbs.templates.'.$cfg->skin.'.comment')
@endif
@stop
</div>
@section ('styles')
@parent
<style>
    @include ('bbs.templates.'.$cfg->skin.'.css.style')
</style>
@stop

@section ('scripts')
@parent
{{ Html::script('assets/pondol/bbs/bbs.js') }}
<script>
    BBS.tbl_name = "{{$cfg->table_name}}";
    BBS.article_id = {{$article->id}};
</script>
@stop
