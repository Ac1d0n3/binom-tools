@php
    $isCreate = ! empty($isCreate);
    $updateTemplate = $isCreate ? '' : ($action ?? '');
    $idPrefix = $modalId ?? 'admin-product';
@endphp
<x-admin.modal :id="$modalId" :title="$title" :title-de="$titleDe" :title-en="$titleEn" :wide="true">
    <form
        method="post"
        action="{{ $isCreate ? $action : '#' }}"
        class="admin-hub__editor"
        data-admin-product-form
        @if (! $isCreate) data-update-action-template="{{ $updateTemplate }}" @endif
    >
        @csrf
        @unless ($isCreate)
            <input type="hidden" name="_method" value="PUT">
        @endunless

        @include('admin::partials.product-form-fields', [
            'isCreate' => $isCreate,
            'idPrefix' => $idPrefix,
            'vendors' => $vendors,
            'families' => $families,
            'linkGroups' => $linkGroups,
        ])

        <div class="admin-hub__modal-footer">
            <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
            <button class="tools-btn tools-btn--primary" type="submit">Save</button>
        </div>
    </form>
</x-admin.modal>
