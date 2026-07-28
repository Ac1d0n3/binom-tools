@extends('admin::layouts.shell')

@section('title', 'Sources — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search sources…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <button type="button" class="tools-btn tools-btn--primary" data-admin-open-modal="admin-supplier-create-modal" data-text-de="Quelle anlegen" data-text-en="Add source">Add source</button>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'supplier-saved' => 'Saved',
            'supplier-deleted' => 'Deleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-text-de="Keine Treffer." data-text-en="No matches.">No matches.</p>

        <div class="sp-list">
            @foreach ($products as $product)
                @php
                    $id = (string) ($product['id'] ?? '');
                    $labelEn = $product['label']['en'] ?? $id;
                    $labelDe = $product['label']['de'] ?? '';
                    $domain = (string) ($product['domain'] ?? '');
                    $domainLabel = is_array($domains[$domain] ?? null) ? ($domains[$domain]['en'] ?? $domain) : $domain;
                    $entityCount = count($product['entities'] ?? []);
                    $searchText = implode(' ', [$id, $labelEn, $labelDe, $domain, $domainLabel]);
                    $fill = [
                        'domain' => $domain,
                        'order' => (string) ($product['order'] ?? 100),
                        'label_de' => $labelDe,
                        'label_en' => $labelEn,
                        'purpose_de' => $product['shortPurpose']['de'] ?? '',
                        'purpose_en' => $product['shortPurpose']['en'] ?? '',
                    ];
                @endphp
                <div class="sp-list__row" data-overview-item data-search-text="{{ $searchText }}">
                    <div class="sp-list__identity">
                        <strong>{{ $labelEn }}</strong>
                        <span class="admin-hub__meta">{{ $id }} · {{ $domainLabel }} · {{ $entityCount }} entities · DE {{ $labelDe !== '' ? $labelDe : '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <button
                            type="button"
                            class="tools-btn tools-btn--small tools-btn--primary"
                            data-admin-open-modal="admin-supplier-edit-modal"
                            data-admin-modal-title="Edit {{ $labelEn }}"
                            data-admin-supplier-id="{{ $id }}"
                            data-admin-fill="{{ json_encode($fill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                            data-text-de="Bearbeiten"
                            data-text-en="Edit"
                        >Edit</button>
                        <form method="post" action="{{ locale_route('admin.suppliers.destroy', ['supplierId' => $id]) }}" onsubmit="return confirm('Delete source {{ $id }}?');">
                            @csrf
                            @method('DELETE')
                            <button class="tools-btn tools-btn--small tools-btn--danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <x-admin.modal id="admin-supplier-create-modal" title="Add source" titleDe="Quelle anlegen" titleEn="Add source">
            <form method="post" action="{{ locale_route('admin.suppliers.store') }}" class="admin-hub__editor">
                @csrf
                <x-admin.field label="Id">
                    <input class="tools-input" name="id" required pattern="[a-z0-9\-]+">
                </x-admin.field>
                <x-admin.field label="Domain">
                    <select class="tools-input" name="domain" required>
                        @foreach ($domains as $domainId => $labels)
                            <option value="{{ $domainId }}">{{ is_array($labels) ? ($labels['en'] ?? $domainId) : $domainId }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
                <x-admin.field label="Order">
                    <input class="tools-input" name="order" type="number" min="0" value="100">
                </x-admin.field>
                <x-admin.locale-tabs name="supplier-create">
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
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="admin-supplier-edit-modal" title="Edit source" titleDe="Quelle bearbeiten" titleEn="Edit source">
            <form method="post" action="#" class="admin-hub__editor" data-admin-supplier-edit-form data-action-template="{{ url('/admin/suppliers/__ID__') }}">
                @csrf
                @method('PUT')
                <x-admin.field label="Domain">
                    <select class="tools-input" name="domain" required>
                        @foreach ($domains as $domainId => $labels)
                            <option value="{{ $domainId }}">{{ is_array($labels) ? ($labels['en'] ?? $domainId) : $domainId }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
                <x-admin.field label="Order">
                    <input class="tools-input" name="order" type="number" min="0" value="100">
                </x-admin.field>
                <x-admin.locale-tabs name="supplier-edit">
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
                <p class="admin-hub__meta" data-text-de="Entities / SQL / Quality bleiben unverändert — hier nur Stammdaten." data-text-en="Entities / SQL / quality stay unchanged — meta only here.">Entities / SQL / quality stay unchanged — meta only here.</p>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>
    </div>
@endsection
