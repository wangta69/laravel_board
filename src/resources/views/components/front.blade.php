@if ($cfg->blade == 'component')
    <x-dynamic-component :component="$cfg->component" :meta="$meta ?? null">
        {{ $slot }}
    </x-dynamic-component>
@else
    @include('bbs::components.front-extends')
@endif
