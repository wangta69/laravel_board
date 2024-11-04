@extends($cfg->extends)
@section ($cfg->section)
<div class="container">
  <div class="bbs index">
    <h1 class='title'>{{ $cfg->name }}</h1>

    <div class="card">
      <div class="card-header">
      <form method='get' action="{{url()->current()}}">
          <div class="col-5 float-end">
            <div  class=" input-group">
              <select name="f" id="" class="form-select" >
                <option value="title" @if (request()->get('f') == 'title') selected @endif>Title</option>
                <option value="content" @if (request()->get('f') == 'content') selected @endif>Contents</option>
              </select>
              <input type="text" name="s" placeholder="Keyword Search" value="{{request()->get('s')}}"  class="form-control"/>
              <button type="submit" class="btn btn-primary">@lang('bbs::messages.bbs.button.search')</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-body">
        <div class="accordion" id="faq-list">
        @foreach ($articles as $index => $article)
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading{{$article->id}}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$article->id}}" aria-expanded="false" aria-controls="collapse{{$article->id}}">
              {{$article->title}} 
            </button>
          </h2>
          <div id="collapse{{$article->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$article->id}}" data-bs-parent="#faq-list">
            <div class="accordion-body">
            {!! nl2br($article->content) !!}
            </div>
          </div>
        </div>
        @endforeach
        </div>
        <div class='navigation'>
          {!! $articles->render() !!}
        </div>
      </div><!-- .card-body -->
      @if ($cfg->hasPermission('write'))
      <div class="card-footer">
        <a href="{{ route('bbs.create', [$cfg->table_name]) }}" role='button' class='btn btn-sm btn-primary'>@lang('bbs::messages.bbs.button.write')</a>
      </div>
      @endif
    </div><!-- .card -->
  </div><!-- .bbs index -->
</div><!-- .container -->



        


@stop

@section ('styles')
@parent
@include ('bbs.templates.user.'.$cfg->skin.'.style')
@stop

@section ('scripts')
@parent
<script src="/pondol/route.js"></script>
<script src="/pondol/bbs/bbs.js"></script>
@stop
