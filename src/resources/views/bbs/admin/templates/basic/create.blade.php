@extends($cfg->extends)
@section ($cfg->section)
<div class="bbs-admin">
    <div class='basic create'>
        <h1 class='title'>
            {{ $cfg->name }}
        </h1>
        @if (isset($article))
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
        <table class="table">
            <colgroup>
                <col width='120' />
                <col width='' />
            </colgroup>
            <thead>
                <tr>
                    <th>제목</th>
                    <td>
                    <input type="text" name="title" value="{{  isset($article) ? $article->title : old('title') }}" class='form-control input-sm' id='title'>
                    </td>
                </tr>
                <tr>
                    <th>내용</th>
                    <td style='padding: 5px 10px;'>
                        @include ('bbs::plugins.editor', ['cfg'=>$cfg, 'article'=>isset($article) ? $article:null,
                        'attr'=> ['class' => 'form-control input-sm']])
                    </td>
                </tr>
                <tr class='file-control'>
                    <th>파일</th>
                    <td>
                        <ul class='list-inline'>
                            @for ($i = 0; $i < 5; $i ++) <li>
                            <input id="uploads[{{i}}]" class="file-controls" name="uploads[{{i}}]" type="file">
                                <button type='button' class='btn btn-default btn-sm pull-left file-control-btn'>
                                    <span class='glyphicon glyphicon-paperclip'></span>
                                    &nbsp;파일 추가
                                </button>
                                </li>
                                @endfor
                        </ul>
                    </td>
                </tr>
            </thead>
        </table>

        <div class='form-group'>
            <div class='col-sm-12 text-right'>
                <button type="submit" class="btn btn-primary btn-sm">작성완료</button>
                <a href="{{ route('bbs.admin.tbl.index', [$cfg->table_name]) }}" class='btn btn-default btn-sm'>목록</a>
            </div>
        </div>
    </div>
    </form>
    @stop

    @section ('styles')
    @parent
    <style>
        @include ('bbs.admin.css.style')
        @include ('bbs::templates.'.$cfg->skin.'.css.style')
    </style>
    @stop

    @section ('scripts')
    @parent
    <script src="/assets/pondol/bbs/bbs.js"></script>
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
