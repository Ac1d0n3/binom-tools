@extends('admin::layouts.shell')

@section('title', 'Glossary — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($terms)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search value="{{ $q }}" placeholder="Search terms…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                <button type="button" class="tools-btn tools-btn--primary" data-admin-open-modal="admin-glossary-create-modal" data-text-de="Term anlegen" data-text-en="Add term">Add term</button>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'glossary-term-saved' => 'Saved',
            'glossary-term-deleted' => 'Deleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden>No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach ($terms as $term)
                    @php
                        $termId = $term['id'] ?? '';
                        $termEn = $term['term']['en'] ?? $termId ?: '-';
                        $termDe = $term['term']['de'] ?? '';
                        $category = $term['category'] ?? '';
                        $searchText = implode(' ', [
                            $termId,
                            $termDe,
                            $termEn,
                            $term['definition']['de'] ?? '',
                            $term['definition']['en'] ?? '',
                            $category,
                        ]);
                        $termFill = [
                            'term_de' => $termDe,
                            'term_en' => $term['term']['en'] ?? '',
                            'definition_de' => $term['definition']['de'] ?? '',
                            'definition_en' => $term['definition']['en'] ?? '',
                            'category' => $term['category'] ?? 'data',
                        ];
                        $fillJson = json_encode($termFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
                    @endphp
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ $searchText }}">
                        <h3 class="admin-hub__card-title">{{ $termEn }}</h3>
                        <p class="admin-hub__card-meta">
                            {{ $termId }} · {{ $category }}
                            @if ($termDe !== '' && $termDe !== $termEn)
                                · DE {{ $termDe }}
                            @endif
                        </p>
                        <div class="admin-hub__card-actions">
                            <x-admin.icon-btn
                                kind="edit"
                                type="button"
                                data-admin-open-modal="admin-glossary-edit-modal"
                                data-admin-modal-title="Edit {{ $termId }}"
                                data-admin-glossary-id="{{ $termId }}"
                                data-admin-fill="{{ $fillJson }}"
                            />
                            <form method="post" action="{{ locale_route('admin.glossary.destroy', ['termId' => $termId]) }}" data-admin-confirm-delete data-confirm-message="Delete term?">
                                @csrf
                                @method('DELETE')
                                <x-admin.icon-btn kind="delete" type="submit" />
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="supplier-table-wrap" data-admin-overview-panel="table">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>Term EN</th>
                            <th>Term DE</th>
                            <th>Category</th>
                            <th>Id</th>
                            <th class="admin-hub__table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terms as $term)
                            @php
                                $termId = $term['id'] ?? '';
                                $termEn = $term['term']['en'] ?? $termId ?: '-';
                                $termDe = $term['term']['de'] ?? '';
                                $category = $term['category'] ?? '';
                                $searchText = implode(' ', [
                                    $termId,
                                    $termDe,
                                    $termEn,
                                    $term['definition']['de'] ?? '',
                                    $term['definition']['en'] ?? '',
                                    $category,
                                ]);
                                $termFill = [
                                    'term_de' => $termDe,
                                    'term_en' => $term['term']['en'] ?? '',
                                    'definition_de' => $term['definition']['de'] ?? '',
                                    'definition_en' => $term['definition']['en'] ?? '',
                                    'category' => $term['category'] ?? 'data',
                                ];
                                $fillJson = json_encode($termFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
                            @endphp
                            <tr data-overview-item data-search-text="{{ $searchText }}">
                                <td><strong>{{ $termEn }}</strong></td>
                                <td>{{ $termDe !== '' ? $termDe : '—' }}</td>
                                <td>{{ $category }}</td>
                                <td><code>{{ $termId }}</code></td>
                                <td class="admin-hub__table-actions">
                                    <x-admin.icon-btn
                                        kind="edit"
                                        type="button"
                                        data-admin-open-modal="admin-glossary-edit-modal"
                                        data-admin-modal-title="Edit {{ $termId }}"
                                        data-admin-glossary-id="{{ $termId }}"
                                        data-admin-fill="{{ $fillJson }}"
                                    />
                                    <form method="post" action="{{ locale_route('admin.glossary.destroy', ['termId' => $termId]) }}" data-admin-confirm-delete data-confirm-message="Delete term?">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.icon-btn kind="delete" type="submit" />
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
