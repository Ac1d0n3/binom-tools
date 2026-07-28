@php
    $group = $group ?? 'help';
    $withId = ! empty($withId);
    $prefix = 'links['.$group.'][0]';
@endphp
<div class="admin-hub__link-row" data-admin-link-row>
    @if ($withId)
        <x-admin.field label="Id">
            <input data-admin-link-field="id" name="{{ $prefix }}[id]" value="">
        </x-admin.field>
    @endif
    <x-admin.field label="URL">
        <input data-admin-link-field="href" name="{{ $prefix }}[href]" type="url" value="" placeholder="https://…">
    </x-admin.field>
    <x-admin.locale-tabs :name="'link-'.$group.'-'.uniqid()">
        <x-slot:de>
            <x-admin.field label="Label DE">
                <input data-admin-link-field="label_de" name="{{ $prefix }}[label_de]" value="">
            </x-admin.field>
            <x-admin.field label="Description DE">
                <input data-admin-link-field="description_de" name="{{ $prefix }}[description_de]" value="">
            </x-admin.field>
        </x-slot:de>
        <x-slot:en>
            <x-admin.field label="Label EN">
                <input data-admin-link-field="label_en" name="{{ $prefix }}[label_en]" value="">
            </x-admin.field>
            <x-admin.field label="Description EN">
                <input data-admin-link-field="description_en" name="{{ $prefix }}[description_en]" value="">
            </x-admin.field>
        </x-slot:en>
    </x-admin.locale-tabs>
    <button type="button" class="tools-btn tools-btn--small tools-btn--danger" data-admin-link-remove data-text-de="Link entfernen" data-text-en="Remove link">Remove link</button>
</div>
