@extends('admin::layouts.shell')

@section('title', 'Glossary — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search value="{{ $q }}" placeholder="Search terms…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <button type="button" class="tools-btn tools-btn--primary" data-admin-open-modal="admin-glossary-create-modal" data-text-de="Term anlegen" data-text-en="Add term">Add term</button>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'glossary-term-saved' => 'Saved',
            'glossary-term-deleted' => 'Deleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden>No matches.</p>

        <div class="sp-list">
            @foreach ($terms as $term)
                @php
                    $searchText = implode(' ', [
                        $term['id'] ?? '',
                        $term['term']['de'] ?? '',
                        $term['term']['en'] ?? '',
                        $term['definition']['de'] ?? '',
                        $term['definition']['en'] ?? '',
                        $term['category'] ?? '',
                    ]);
                @endphp
                <div class="sp-list__row" data-overview-item data-search-text="{{ $searchText }}">
                    <div class="sp-list__identity">
                        <strong>{{ $term['term']['en'] ?? $term['id'] ?? '-' }}</strong>
                        <span class="admin-hub__meta">{{ $term['id'] ?? '' }} · {{ $term['category'] ?? '' }} · DE {{ $term['term']['de'] ?? '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        @php
                            $termFill = [
                                'term_de' => $term['term']['de'] ?? '',
                                'term_en' => $term['term']['en'] ?? '',
                                'definition_de' => $term['definition']['de'] ?? '',
                                'definition_en' => $term['definition']['en'] ?? '',
                                'category' => $term['category'] ?? 'data',
                            ];
                        @endphp
                        <button
                            type="button"
                            class="tools-btn tools-btn--small"
                            data-admin-open-modal="admin-glossary-edit-modal"
                            data-admin-modal-title="Edit {{ $term['id'] ?? '' }}"
                            data-admin-glossary-id="{{ $term['id'] }}"
                            data-admin-fill="{{ json_encode($termFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                        >Edit</button>
                        <form method="post" action="{{ locale_route('admin.glossary.destroy', ['termId' => $term['id']]) }}" data-admin-confirm-delete data-confirm-message="Delete term?">
                            @csrf
                            @method('DELETE')
                            <button class="tools-btn tools-btn--small tools-btn--danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <x-admin.modal id="admin-glossary-create-modal" title="Add term" titleDe="Term anlegen" titleEn="Add term">
            <form method="post" action="{{ locale_route('admin.glossary.store') }}" class="admin-hub__editor">
                @csrf
                <x-admin.field label="Id (optional)">
                    <input name="id" pattern="[a-z0-9\-]+">
                </x-admin.field>
                <x-admin.field label="Category">
                    <input name="category" value="data">
                </x-admin.field>
                <x-admin.locale-tabs name="glossary-create">
                    <x-slot:de>
                        <x-admin.field label="Term DE">
                            <input name="term_de" required>
                        </x-admin.field>
                        <x-admin.field label="Definition DE">
                            <textarea name="definition_de" class="admin-hub__textarea admin-hub__textarea--short" required></textarea>
                        </x-admin.field>
                    </x-slot:de>
                    <x-slot:en>
                        <x-admin.field label="Term EN">
                            <input name="term_en" required>
                        </x-admin.field>
                        <x-admin.field label="Definition EN">
                            <textarea name="definition_en" class="admin-hub__textarea admin-hub__textarea--short" required></textarea>
                        </x-admin.field>
                    </x-slot:en>
                </x-admin.locale-tabs>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="admin-glossary-edit-modal" title="Edit term" titleDe="Term bearbeiten" titleEn="Edit term">
            <form method="post" action="#" class="admin-hub__editor" data-admin-glossary-edit-form data-action-template="{{ url('/admin/glossary/__ID__') }}">
                @csrf
                @method('PUT')
                <x-admin.field label="Category">
                    <input name="category" value="data">
                </x-admin.field>
                <x-admin.locale-tabs name="glossary-edit">
                    <x-slot:de>
                        <x-admin.field label="Term DE">
                            <input name="term_de" required>
                        </x-admin.field>
                        <x-admin.field label="Definition DE">
                            <textarea name="definition_de" class="admin-hub__textarea admin-hub__textarea--short" required></textarea>
                        </x-admin.field>
                    </x-slot:de>
                    <x-slot:en>
                        <x-admin.field label="Term EN">
                            <input name="term_en" required>
                        </x-admin.field>
                        <x-admin.field label="Definition EN">
                            <textarea name="definition_en" class="admin-hub__textarea admin-hub__textarea--short" required></textarea>
                        </x-admin.field>
                    </x-slot:en>
                </x-admin.locale-tabs>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>
    </div>
@endsection
