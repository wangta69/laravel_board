@php
$path = $path ?? [];
@endphp
<x-pondol-common::app>
  <div class="wrapper">
    <x-dynamic-component :component="config('pondol-bbs.component.admin.lnb')" />
    <div class="container">
      @if(count($path))
      <x-pondol-common::partials.main-top-navigation :path="$path"/>
      @endif
      {{ $slot }}
      <x-pondol-common::partials.footer />
    </div><!--. container -->
  </div>

  <x-pondol-common::partials.toaster />

@section('styles')
@parent
<style>
  #footer {border-top: 1px solid #ced4da;}
</style>
@endsection

@section('scripts')
@parent
<script src="/pondol/auth/admin.js"></script>
@endsection
</x-pondol-common::app>