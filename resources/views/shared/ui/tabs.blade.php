@props([
    'variant' => 'underline', // folder | underline
    'ariaLabel' => null,
])

@php
    $variantClass = $variant === 'folder' ? 'bn-tabs bn-tabs--folder' : 'bn-tabs bn-tabs--underline';
    $label = $ariaLabel ?? $attributes->get('aria-label', 'Tabs');
@endphp

<nav
    {{ $attributes->class([$variantClass])->merge([
        'role' => 'tablist',
        'aria-label' => $label,
    ]) }}
>
    {{ $slot }}
</nav>
