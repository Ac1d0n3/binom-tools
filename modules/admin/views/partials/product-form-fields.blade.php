@php
    $isCreate = ! empty($isCreate);
    $idPrefix = $idPrefix ?? ($modalId ?? 'admin-product');
    $families = $families ?? [];
    $vendors = $vendors ?? [];
    $linkGroups = $linkGroups ?? [];
@endphp

<x-admin.field label="Id">
    <input class="tools-input" name="id" required pattern="[a-z0-9\-]+" @if (! $isCreate) readonly @endif>
</x-admin.field>
<x-admin.field label="Vendor">
    <select class="tools-input" name="vendor" required data-admin-product-vendor>
        @foreach ($vendors as $vendorId => $labels)
            <option value="{{ $vendorId }}">{{ $labels['en'] ?? $vendorId }}</option>
        @endforeach
    </select>
</x-admin.field>
<x-admin.field label="Family">
    <select class="tools-input" name="family" required>
        @foreach ($families as $familyId => $familyLabels)
            <option value="{{ $familyId }}">{{ is_array($familyLabels) ? ($familyLabels['en'] ?? $familyId) : $familyId }}</option>
        @endforeach
    </select>
</x-admin.field>

<div class="admin-hub__section-tabs" data-admin-section-tabs data-admin-tabs data-admin-product-tabs>
    <div class="admin-hub__tablist" role="tablist">
        <button type="button" class="admin-hub__tab is-active" role="tab" id="{{ $idPrefix }}-tab-meta" data-tab-id="{{ $idPrefix }}-panel-meta" aria-controls="{{ $idPrefix }}-panel-meta" aria-selected="true" tabindex="0">Meta</button>
        @foreach ($linkGroups as $group => $label)
            <button type="button" class="admin-hub__tab" role="tab" id="{{ $idPrefix }}-tab-{{ $group }}" data-tab-id="{{ $idPrefix }}-panel-{{ $group }}" aria-controls="{{ $idPrefix }}-panel-{{ $group }}" aria-selected="false" tabindex="-1">{{ $label }}</button>
        @endforeach
    </div>
    <div class="admin-hub__tab-panels">
        <div class="admin-hub__tab-panel" role="tabpanel" id="{{ $idPrefix }}-panel-meta" data-admin-tab-panel="{{ $idPrefix }}-panel-meta">
            <x-admin.locale-tabs :name="$idPrefix.'-meta'">
                <x-slot:de>
                    <x-admin.field label="Label DE">
                        <input class="tools-input" name="label_de" required>
                    </x-admin.field>
                    <x-admin.field label="Purpose DE">
                        <textarea name="purpose_de" class="admin-hub__textarea admin-hub__textarea--short tools-input"></textarea>
                    </x-admin.field>
                </x-slot:de>
                <x-slot:en>
                    <x-admin.field label="Label EN">
                        <input class="tools-input" name="label_en" required>
                    </x-admin.field>
                    <x-admin.field label="Purpose EN">
                        <textarea name="purpose_en" class="admin-hub__textarea admin-hub__textarea--short tools-input"></textarea>
                    </x-admin.field>
                </x-slot:en>
            </x-admin.locale-tabs>
            <x-admin.field label="Brand color">
                <input class="tools-input" name="brandColor" placeholder="#64748b">
            </x-admin.field>
            <x-admin.field label="Logo">
                <input class="tools-input" name="logo" placeholder="amazon.svg">
            </x-admin.field>
            <x-admin.field label="Models (comma-separated)">
                <input class="tools-input" name="models">
            </x-admin.field>
            <x-admin.field label="Residency (comma-separated)">
                <input class="tools-input" name="residency">
            </x-admin.field>
        </div>

        @foreach ($linkGroups as $group => $label)
            <div class="admin-hub__tab-panel" role="tabpanel" id="{{ $idPrefix }}-panel-{{ $group }}" data-admin-tab-panel="{{ $idPrefix }}-panel-{{ $group }}" hidden>
                <div class="admin-hub__toolbar">
                    <strong>{{ $label }} links</strong>
                    <button type="button" class="tools-btn tools-btn--small" data-admin-link-add="{{ $group }}">Add link</button>
                </div>
                <div class="admin-hub__link-rows" data-admin-link-list="{{ $group }}">
                    @include('admin::partials.link-row', ['group' => $group, 'withId' => $group === 'compliance'])
                </div>
            </div>
        @endforeach
    </div>
</div>
