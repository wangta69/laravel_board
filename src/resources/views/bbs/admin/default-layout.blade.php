@extends('admin.layouts.admin')
@section('title', 'HOME')

@section('head')
@endsection
@section('content')
<div class="main-contents">
    <div class="section__content section__content--p30">
        @yield('bbs-content')
    </div>
</div>
@endsection

@section ('styles')
@parent
@endsection

@section ('scripts')
@parent
@endsection
