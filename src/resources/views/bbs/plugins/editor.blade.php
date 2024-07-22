
@if($cfg->editor == 'smartEditor')
        @include ('bbs::plugins.smart_editor.editor', ['id' => 'bbs-content', 'name' => 'content', 'value' => isset($article) ? $article->content : old('content')])
@else
        @php
            if(isset($attr)){
                $class = isset($attr['class']) ? $attr['class'] : null;
                $placeholder = isset($attr['placeholder']) ? $attr['placeholder'] : null;
            }
        @endphp
        <textarea id="content" class="{{isset($class) ? $class : null}}" name="content" cols="50" rows="10" placeholder="{{isset($placeholder)? $placeholder : null}}">{{ isset($article) ? $article->content : old('content') }}</textarea>
@endif
