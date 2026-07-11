@props([
    'id',
    'leadKey' => null,
])

@if ($leadKey)
    <p class="tools-panel-lead" data-i18n="{{ $leadKey }}"></p>
@endif

<div {{ $attributes->merge(['id' => $id, 'class' => 'tools-column-accordion']) }}>
    {{ $slot }}
</div>
