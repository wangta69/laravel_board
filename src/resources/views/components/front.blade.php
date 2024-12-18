@if($cfg->blade =='component')
<x-dynamic-component :component="$cfg->component">
  {{ $slot }}
</x-dynamic-component>
@else
  @include('bbs::components.front-extends')
@endif
