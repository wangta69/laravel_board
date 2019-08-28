@if($cfg->editor == 'smartEditor')
        @include ('bbs::plugins.smart_editor.editor', ['name' => 'content', 'value' => isset($article) ? $article->content : old('content')])
@else
        @php
            if(isset($attr)){
                $class = isset($attr['class']) ? $attr['class'] : null;
                $placeholder = isset($attr['placeholder']) ? $attr['placeholder'] : null;
            }
        @endphp
        {!! Form::textarea('content', isset($article) ? $article->content : old('content'),
        [
            'id' => 'content',
            'class' => isset($class)? $class : null,
            'placeholder' => isset($placeholder)? $placeholder : null
        ]) !!}
@endif
