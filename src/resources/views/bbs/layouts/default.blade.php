@include ('bbs::layouts.partials.header')
@yield ('styles')

<div class='container'>
	@yield ('content')
</div>

@yield ('scripts')
@include ('bbs::layouts.partials.footer')

