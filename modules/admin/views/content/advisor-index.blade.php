@extends('admin::layouts.shell')

@section('title', 'Advisor recommendations — ' . config('app.name'))

@section('admin_content')
    @php
        $kindLabels = [
            'story' => 'Story',
            'supplier' => 'Source',
            'vendor' => 'Vendor',
        ];
        $csv = static fn ($list): string => is_array($list) ? implode(', ', $list) : '';
    @endphp
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($items)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search advisor items…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                @if ($canCreateStory || $canCreateVendorSource)
                    <button type="button" class="tools-btn tools-btn--primary" data-admin-open-modal="admin-advisor-create-modal" data-text-de="Empfehlung anlegen" data-text-en="Add recommendation">Add recommendation</button>
                @endif
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'advisor-item-saved' => 'Recommendation saved',
            'advisor-item-deleted' => 'Recommendation deleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-text-de="Keine Treffer." data-text-en="No matches.">No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach ($items as $item)
                    @php
                        $itemId = (string) ($item['id'] ?? '');
                        $kind = (string) ($item['kind'] ?? 'story');
                        $titleEn = is_array($item['title'] ?? null) ? (string) ($item['title']['en'] ?? '') : (string) ($item['title'] ?? '');
                        $titleDe = is_array($item['title'] ?? null) ? (string) ($item['title']['de'] ?? '') : '';
                        $display = $titleEn !== '' ? $titleEn : ($titleDe !== '' ? $titleDe : $itemId);
                        $searchText = implode(' ', [
                            $itemId,
                            $kind,
                            $item['ref'] ?? '',
                            $titleDe,
                            $titleEn,
                            $csv($item['tags'] ?? []),
                        ]);
                        $itemFill = [
                            'kind' => $kind,
                            'ref' => $item['ref'] ?? '',
                            'enabled' => ! empty($item['enabled']),
                            'group' => $item['group'] ?? 'resources',
                            'icon' => $item['icon'] ?? '',
                            'score' => (int) ($item['score'] ?? 70),
                            'tags' => $csv($item['tags'] ?? []),
                            'title_de' => $titleDe !== '' ? $titleDe : $display,
                            'title_en' => $titleEn !== '' ? $titleEn : $display,
                            'reason_de' => is_array($item['reason'] ?? null) ? (string) ($item['reason']['de'] ?? '') : '',
                            'reason_en' => is_array($item['reason'] ?? null) ? (string) ($item['reason']['en'] ?? '') : '',
                            'when_goals' => $csv($item['when']['goals'] ?? []),
                            'when_scenarios' => $csv($item['when']['scenarios'] ?? []),
                            'when_domains' => $csv($item['when']['domains'] ?? []),
                            'when_platforms' => $csv($item['when']['platforms'] ?? []),
                            'when_roles' => $csv($item['when']['roles'] ?? []),
                        ];
                        $fillJson = json_encode($itemFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
                    @endphp
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ $searchText }}">
                        <h3 class="admin-hub__card-title">{{ $display }}</h3>
                        <p class="admin-hub__card-meta">
                            {{ $kindLabels[$kind] ?? $kind }} · {{ $item['ref'] ?? '' }} · score {{ (int) ($item['score'] ?? 0) }}
                            @if (empty($item['enabled']))
                                · disabled
                            @endif
                        </p>
                        <div class="admin-hub__card-actions">
                            <x-admin.icon-btn
                                kind="edit"
                                type="button"
                                data-admin-open-modal="admin-advisor-edit-modal"
                                data-admin-modal-title="Edit {{ $itemId }}"
                                data-admin-item-id="{{ $itemId }}"
                                data-admin-fill="{{ $fillJson }}"
                            />
                            <form method="post" action="{{ locale_route('admin.advisor.items.destroy', ['itemId' => $itemId]) }}" data-admin-confirm-delete data-confirm-message="Delete recommendation?">
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
                            <th>Title EN</th>
                            <th>Kind</th>
                            <th>Ref</th>
                            <th>Score</th>
                            <th>Id</th>
                            <th class="admin-hub__table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @php
                                $itemId = (string) ($item['id'] ?? '');
                                $kind = (string) ($item['kind'] ?? 'story');
                                $titleEn = is_array($item['title'] ?? null) ? (string) ($item['title']['en'] ?? '') : (string) ($item['title'] ?? '');
                                $titleDe = is_array($item['title'] ?? null) ? (string) ($item['title']['de'] ?? '') : '';
                                $display = $titleEn !== '' ? $titleEn : ($titleDe !== '' ? $titleDe : $itemId);
                                $searchText = implode(' ', [
                                    $itemId,
                                    $kind,
                                    $item['ref'] ?? '',
                                    $titleDe,
                                    $titleEn,
                                    $csv($item['tags'] ?? []),
                                ]);
                                $itemFill = [
                                    'kind' => $kind,
                                    'ref' => $item['ref'] ?? '',
                                    'enabled' => ! empty($item['enabled']),
                                    'group' => $item['group'] ?? 'resources',
                                    'icon' => $item['icon'] ?? '',
                                    'score' => (int) ($item['score'] ?? 70),
                                    'tags' => $csv($item['tags'] ?? []),
                                    'title_de' => $titleDe !== '' ? $titleDe : $display,
                                    'title_en' => $titleEn !== '' ? $titleEn : $display,
                                    'reason_de' => is_array($item['reason'] ?? null) ? (string) ($item['reason']['de'] ?? '') : '',
                                    'reason_en' => is_array($item['reason'] ?? null) ? (string) ($item['reason']['en'] ?? '') : '',
                                    'when_goals' => $csv($item['when']['goals'] ?? []),
                                    'when_scenarios' => $csv($item['when']['scenarios'] ?? []),
                                    'when_domains' => $csv($item['when']['domains'] ?? []),
                                    'when_platforms' => $csv($item['when']['platforms'] ?? []),
                                    'when_roles' => $csv($item['when']['roles'] ?? []),
                                ];
                                $fillJson = json_encode($itemFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
                            @endphp
                            <tr data-overview-item data-search-text="{{ $searchText }}">
                                <td>{{ $display }}</td>
                                <td>{{ $kindLabels[$kind] ?? $kind }}</td>
                                <td>{{ $item['ref'] ?? '' }}</td>
                                <td>{{ (int) ($item['score'] ?? 0) }}</td>
                                <td>{{ $itemId }}</td>
                                <td class="admin-hub__table-actions">
                                    <x-admin.icon-btn
                                        kind="edit"
                                        type="button"
                                        data-admin-open-modal="admin-advisor-edit-modal"
                                        data-admin-modal-title="Edit {{ $itemId }}"
                                        data-admin-item-id="{{ $itemId }}"
                                        data-admin-fill="{{ $fillJson }}"
                                    />
                                    <form method="post" action="{{ locale_route('admin.advisor.items.destroy', ['itemId' => $itemId]) }}" style="display:inline" data-admin-confirm-delete data-confirm-message="Delete recommendation?">
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

        <x-admin.modal id="admin-advisor-create-modal" title="Add recommendation" titleDe="Empfehlung anlegen" titleEn="Add recommendation">
            <form method="post" action="{{ locale_route('admin.advisor.items.store') }}" class="admin-hub__editor" data-admin-advisor-form>
                @csrf
                @include('admin::content.partials.advisor-item-fields', ['isCreate' => true])
                <div class="admin-hub__modal-actions">
                    <button type="submit" class="tools-btn tools-btn--primary" data-text-de="Speichern" data-text-en="Save">Save</button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="admin-advisor-edit-modal" title="Edit recommendation" titleDe="Empfehlung bearbeiten" titleEn="Edit recommendation">
            <form method="post" action="#" class="admin-hub__editor" data-admin-advisor-form data-admin-advisor-edit-form data-action-template="{{ url('/admin/advisor/items/__ID__') }}">
                @csrf
                @method('PUT')
                @include('admin::content.partials.advisor-item-fields', ['isCreate' => false])
                <div class="admin-hub__modal-actions">
                    <button type="submit" class="tools-btn tools-btn--primary" data-text-de="Speichern" data-text-en="Save">Save</button>
                </div>
            </form>
        </x-admin.modal>
    </div>

    <script>
        (function () {
            function syncKind(form) {
                if (!form) return;
                const kindSelect = form.querySelector('[data-admin-advisor-kind]');
                if (!kindSelect) return;
                const kind = kindSelect.value || 'story';
                form.querySelectorAll('[data-admin-advisor-ref-wrap]').forEach((wrap) => {
                    const match = wrap.getAttribute('data-admin-advisor-ref-wrap') === kind;
                    wrap.hidden = !match;
                    const select = wrap.querySelector('select');
                    if (!select) return;
                    select.disabled = !match;
                    if (match) {
                        select.setAttribute('name', 'ref');
                        select.required = true;
                    } else {
                        select.removeAttribute('name');
                        select.required = false;
                    }
                });
            }

            document.querySelectorAll('[data-admin-advisor-form]').forEach((form) => {
                form.addEventListener('change', (event) => {
                    if (event.target && event.target.matches('[data-admin-advisor-kind]')) {
                        syncKind(form);
                    }
                });
                syncKind(form);
            });

            document.querySelectorAll('[data-admin-open-modal="admin-advisor-edit-modal"]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const form = document.querySelector('[data-admin-advisor-edit-form]');
                    if (!form) return;
                    const itemId = btn.getAttribute('data-admin-item-id') || '';
                    const template = form.getAttribute('data-action-template') || '';
                    if (itemId && template) {
                        form.action = template.replace('__ID__', encodeURIComponent(itemId));
                    }
                    window.setTimeout(() => {
                        try {
                            const fill = JSON.parse(btn.getAttribute('data-admin-fill') || '{}');
                            const enabled = form.querySelector('input[name="enabled"]');
                            if (enabled) {
                                enabled.checked = !!fill.enabled;
                            }
                            const kindSelect = form.querySelector('[data-admin-advisor-kind]');
                            if (kindSelect && fill.kind) {
                                kindSelect.value = fill.kind;
                            }
                            syncKind(form);
                            const refSelect = form.querySelector(`[data-admin-advisor-ref="${fill.kind || 'story'}"]`);
                            if (refSelect && fill.ref) {
                                refSelect.value = fill.ref;
                            }
                        } catch (e) {
                            syncKind(form);
                        }
                    }, 0);
                });
            });
        })();
    </script>
@endsection
