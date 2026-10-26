{{-- pondol-common::common-admin 등 컴포넌트 기반 레이아웃 호출 --}}
<x-dynamic-component :component="$layoutName" :path="$path" :title="$title">
    {{ $slot }}
</x-dynamic-component>
