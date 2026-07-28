@extends('admin::layouts.shell')

@section('title', 'Vendors — Admin — ' . config('app.name'))

@section('admin_content')
    @php
        $linkGroups = [
            'help' => 'Help',
            'governance' => 'Governance',
            'learning' => 'Learning',
            'certifications' => 'Certifications',
            'compliance' => 'Compliance',
        ];
        $familyOptions = $families ?? [];
    @endphp
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search vendors / products…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <button type="button" class="tools-btn" data-admin-open-modal="admin-product-create-modal" data-text-de="Produkt anlegen" data-text-en="Add product">Add product</button>
                <button type="button" class="tools-btn tools-btn--primary" data-admin-open-modal="admin-vendor-create-modal" data-text-de="Vendor anlegen" data-text-en="Add vendor">Add vendor</button>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'vendor-saved' => 'Saved',
            'vendor-deleted' => 'Deleted',
            'product-saved' => 'Product saved',
            'product-deleted' => 'Product deleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-text-de="Keine Treffer." data-text-en="No matches.">No matches.</p>

        <div class="sp-list">
            @foreach ($vendors as $id => $labels)
                @php
                    $products = $productsByVendor[$id] ?? [];
                    $searchBits = [$id, $labels['de'] ?? '', $labels['en'] ?? ''];
                    foreach ($products as $product) {
                        $searchBits[] = $product['id'] ?? '';
                        $searchBits[] = $product['label']['en'] ?? '';
                        $searchBits[] = $product['label']['de'] ?? '';
                    }
                    $vendorFill = [
                        'name_de' => $labels['de'] ?? '',
                        'name_en' => $labels['en'] ?? '',
                    ];
                    $firstProductId = $products[0]['id'] ?? '';
                @endphp
                <div class="admin-hub__vendor-block" data-overview-item data-search-text="{{ implode(' ', $searchBits) }}">
                    <div class="sp-list__row admin-hub__vendor-head">
                        <button
                            type="button"
                            class="admin-hub__vendor-toggle"
                            aria-expanded="false"
                            aria-controls="admin-vendor-products-{{ $id }}"
                            data-admin-vendor-toggle
                        >
                            <span class="admin-hub__vendor-chevron" aria-hidden="true"></span>
                            <span class="sp-list__identity">
                                <strong>{{ $labels['en'] ?? $id }}</strong>
                                <span class="admin-hub__meta">{{ $id }} · {{ count($products) }} products · DE {{ $labels['de'] ?? '—' }}</span>
                            </span>
                        </button>
                        <div class="sp-list__actions">
                            <button
                                type="button"
                                class="tools-btn tools-btn--small tools-btn--primary"
                                data-admin-edit-vendor
                                data-admin-modal-title="Edit {{ $labels['en'] ?? $id }}"
                                data-admin-fill="{{ json_encode($vendorFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                data-admin-vendor-id="{{ $id }}"
                                data-admin-product-id="{{ $firstProductId }}"
                                data-text-de="Bearbeiten"
                                data-text-en="Edit"
                            >Edit</button>
                            <form method="post" action="{{ locale_route('admin.vendors.destroy', ['vendorId' => $id]) }}" style="display:inline" data-admin-confirm-delete data-confirm-message="Delete vendor label?">
                                @csrf
                                @method('DELETE')
                                <button class="tools-btn tools-btn--small tools-btn--danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="admin-hub__product-list" id="admin-vendor-products-{{ $id }}" hidden>
                        @forelse ($products as $product)
                            <div class="sp-list__row">
                                <div class="sp-list__identity">
                                    <strong>{{ $product['label']['en'] ?? $product['id'] ?? '-' }}</strong>
                                    <span class="admin-hub__meta">{{ $product['id'] ?? '' }} · {{ $product['family'] ?? '' }}</span>
                                </div>
                                <div class="sp-list__actions">
                                    <button
                                        type="button"
                                        class="tools-btn tools-btn--small tools-btn--primary"
                                        data-admin-edit-vendor
                                        data-admin-modal-title="Edit {{ $labels['en'] ?? $id }}"
                                        data-admin-fill="{{ json_encode($vendorFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                        data-admin-vendor-id="{{ $id }}"
                                        data-admin-product-id="{{ $product['id'] }}"
                                        data-admin-edit-product="{{ json_encode($product, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                        data-text-de="Bearbeiten"
                                        data-text-en="Edit"
                                    >Edit</button>
                                    <form method="post" action="{{ locale_route('admin.vendors.products.destroy', ['productId' => $product['id']]) }}" data-admin-confirm-delete data-confirm-message="Delete product?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="tools-btn tools-btn--small tools-btn--danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="admin-hub__meta">No products for this vendor.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <x-admin.modal id="admin-vendor-create-modal" title="Add vendor" titleDe="Vendor anlegen" titleEn="Add vendor">
            <form method="post" action="{{ locale_route('admin.vendors.store') }}" class="admin-hub__editor">
                @csrf
                <x-admin.field label="Id">
                    <input class="tools-input" name="id" required pattern="[a-z0-9\-]+">
                </x-admin.field>
                <x-admin.locale-tabs name="vendor-create">
                    <x-slot:de>
                        <x-admin.field label="Name DE">
                            <input class="tools-input" name="name_de" required>
                        </x-admin.field>
                    </x-slot:de>
                    <x-slot:en>
                        <x-admin.field label="Name EN">
                            <input class="tools-input" name="name_en" required>
                        </x-admin.field>
                    </x-slot:en>
                </x-admin.locale-tabs>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>

        {{-- One edit form: vendor names (DE/EN tabs) + product meta/links --}}
        <x-admin.modal id="admin-vendor-edit-modal" title="Edit vendor" titleDe="Vendor bearbeiten" titleEn="Edit vendor" :wide="true">
            <form
                method="post"
                action="#"
                class="admin-hub__editor"
                data-admin-vendor-workspace
                data-vendor-action-template="{{ url('/admin/vendors/__ID__') }}"
                data-product-action-template="{{ url('/admin/vendors/products/__ID__') }}"
            >
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <x-admin.locale-tabs name="vendor-edit-name">
                    <x-slot:de>
                        <x-admin.field label="Vendor name DE">
                            <input class="tools-input" name="name_de" required data-admin-vendor-name>
                        </x-admin.field>
                    </x-slot:de>
                    <x-slot:en>
                        <x-admin.field label="Vendor name EN">
                            <input class="tools-input" name="name_en" required data-admin-vendor-name>
                        </x-admin.field>
                    </x-slot:en>
                </x-admin.locale-tabs>

                <x-admin.field label="Product" label-de="Produkt" label-en="Product">
                    <select class="tools-input" data-admin-product-switcher></select>
                </x-admin.field>
                <p class="admin-hub__meta" data-admin-vendor-products-empty hidden data-text-de="Keine Produkte — lege eines an." data-text-en="No products — add one first.">No products — add one first.</p>

                <div data-admin-vendor-product-fields>
                    @include('admin::partials.product-form-fields', [
                        'isCreate' => false,
                        'idPrefix' => 'admin-vendor-edit',
                        'vendors' => $vendors,
                        'families' => $familyOptions,
                        'linkGroups' => $linkGroups,
                    ])
                </div>

                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit" data-text-de="Speichern" data-text-en="Save">Save</button>
                </div>
            </form>
        </x-admin.modal>

        @include('admin::partials.product-form-modal', [
            'modalId' => 'admin-product-create-modal',
            'title' => 'Add product',
            'titleDe' => 'Produkt anlegen',
            'titleEn' => 'Add product',
            'action' => locale_route('admin.vendors.products.store'),
            'method' => 'POST',
            'vendors' => $vendors,
            'families' => $familyOptions,
            'linkGroups' => $linkGroups,
            'isCreate' => true,
        ])
    </div>
@endsection
