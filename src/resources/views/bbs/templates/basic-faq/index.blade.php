@extends($urlParams->dec['blade_extends'])
@section ('content')
<div class='basic-table index'>
    <h1 class='title'>
        {{ $cfg->name }}
    </h1>
    <div class="basic-faq">
    @foreach ($articles as $index => $article)
        @if ($cfg->hasPermission('write'))
        <div class="faq-title">
            {!! Html::link(route('bbs.show', [$cfg->table_name, $article->id, 'urlParams='.$urlParams->enc]), $article->title) !!}
        </div>
        @else
        <div class="faq-title view" user-attr-id="{{$article->id}}"><!-- 링크 클릭시 ajax를 이용하여 답변글 호출 -->
            <div class="title">{!! $article->title !!}</div>
            <div class="answer">
            </div>
        </div>
        @endif
    @endforeach
    </div>

    <div class='navigation'>
        {!! $articles->render() !!}
    </div>

    <div class='btn-area text-right'>
        @if ($cfg->hasPermission('write'))
            {!! Html::link(route('bbs.create', [$cfg->table_name, 'urlParams='.$urlParams->enc]), '글쓰기', [
                'role' => 'button',
                'class' => 'btn btn-sm btn-default',
            ]) !!}
        @endif
    </div>
</div>
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
function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
$(function(){
    $('.faq-title.view').click(function(){
        var $answer = $(this).find('.answer');
        if ($answer.hasClass('on')) {
            $answer.removeClass('on')
        } else {
            // 데이타 가져오기
            var tblName = '{{$cfg->table_name}}';
            var articleId = $(this).attr('user-attr-id');
            var url = tblName + '/' + articleId + '/first-comment';
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    console.log(res);
                    console.log(res[0].content);
                    $answer.html(nl2br(res[0].content));
                }
            });
            $(this).find('.answer').addClass('on');
        }
    });
});
</script>
@stop
