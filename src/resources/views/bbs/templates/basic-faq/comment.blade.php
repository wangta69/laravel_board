@php
    $comment = $article->comments()->orderBy('order_num')->orderBy('reply_depth')->first();
    if(!$comment) { // initialize
        $comment = new stdClass;
        $comment->id = 0;
        $comment->content = '';
    }
@endphp

{!! Form::open([
    'method' => '',
    'route' => ['bbs.comment.update', $cfg->table_name, $article->id, $comment->id, 'urlParams='.$urlParams->enc],
    'class' => 'form-horizontal',
    'id' => 'comment-form',
    'enctype' => 'multipart/form-data',
]) !!}
    {{ Form::hidden('text_type', 'br') }}
    <section class="comment-input">
        <div class="container-fluid">
            <div style="float:right;">
                <button type="button" id="save-comment" class="btn btn-success" style="width:70px; height: 70px;">저장</button>
            </div>
            <div style="margin-right: 80px;">
            <textarea name="content" maxlength="10000" required="" class="form-control input-sm" style="height:70px;" >{{$comment->content}}</textarea>
            </div>

        </div>
    </section>
{!! Form::close() !!}
</section>
@section ('scripts')
@parent
<script>
@if($comment->id === 0)
var url = '{!! route("bbs.comment.store", [$cfg->table_name, $article->id, 0, "urlParams=".$urlParams->enc]) !!}';
var method = 'POST';
@else
var url = '{!! route("bbs.comment.update", [$cfg->table_name, $article->id, $comment->id, "urlParams=".$urlParams->enc]) !!}';
var method = 'PUT';
@endif

$(function(){
    $("#save-comment").click(function(){
        $.ajax({
            url: url,
            method: method,
            data : $('#comment-form').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res){
                if (res.result === true) {
                    window.location.reload();
                } else {
                    alert(res.message);
                }
            }
        });
    });
})
</script>
@stop
