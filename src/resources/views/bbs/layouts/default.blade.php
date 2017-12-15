@include ('bbs.layouts.partials.header')
@yield ('css')

<div class='container'>
	@yield ('content')
</div>

@include ('bbs.layouts.partials.footer')

@yield ('script')
