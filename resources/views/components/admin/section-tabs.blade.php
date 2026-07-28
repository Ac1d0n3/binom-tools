@props([
    'tabs' => [],
    'active' => null,
])

@php
    $tabItems = $tabs;
    $first = $active ?? (array_key_first($tabItems) ?: null);
@endphp

<div class="admin-hub__section-tabs" data-admin-section-tabs data-admin-tabs>
    <div class="admin-hub__tablist" role="tablist">
        @foreach ($tabItems as $id => $label)
            <button
                type="button"
                class="admin-hub__tab {{ $first === $id ? 'is-active' : '' }}"
                role="tab"
                id="admin-section-tab-{{ $id }}"
                data-tab-id="admin-section-panel-{{ $id }}"
                aria-controls="admin-section-panel-{{ $id }}"
                aria-selected="{{ $first === $id ? 'true' : 'false' }}"
                tabindex="{{ $first === $id ? '0' : '-1' }}"
            >{{ $label }}</button>
        @endforeach
    </div>
    <div class="admin-hub__tab-panels">
        {{ $slot }}
    </div>
</div>
