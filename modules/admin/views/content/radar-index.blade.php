@extends('admin::layouts.shell')

@section('title', 'Radar admin — ' . config('app.name'))

@section('admin_content')
    @php
        $sourceOptions = collect($sources)->mapWithKeys(fn ($s) => [($s['id'] ?? '') => ($s['name'] ?? $s['id'] ?? '')])->filter()->all();
        $renderNewsRow = function (array $item) use ($sourceOptions): void {
            $titleEn = is_array($item['title_i18n'] ?? null)
                ? ($item['title_i18n']['en'] ?? '')
                : (string) ($item['title'] ?? '');
            $titleDe = is_array($item['title_i18n'] ?? null)
                ? ($item['title_i18n']['de'] ?? '')
                : (string) ($item['title'] ?? '');
            $summaryEn = is_array($item['summary_i18n'] ?? null)
                ? ($item['summary_i18n']['en'] ?? '')
                : (string) ($item['summary'] ?? '');
            $summaryDe = is_array($item['summary_i18n'] ?? null)
                ? ($item['summary_i18n']['de'] ?? '')
                : (string) ($item['summary'] ?? '');
            $display = $titleEn !== '' ? $titleEn : ($titleDe !== '' ? $titleDe : ($item['id'] ?? '-'));
            $itemFill = [
                'title_de' => $titleDe !== '' ? $titleDe : $display,
                'title_en' => $titleEn !== '' ? $titleEn : $display,
                'summary_de' => $summaryDe,
                'summary_en' => $summaryEn,
                'url' => $item['url'] ?? '',
                'source_id' => $item['source_id'] ?? 'manual',
                'type' => $item['type'] ?? 'Governance News',
            ];
            ?>
            <div class="sp-list__row">
                <div class="sp-list__identity">
                    <strong>{{ $display }}</strong>
                    <span class="admin-hub__meta">{{ $item['id'] ?? '' }} · {{ $item['published_at'] ?? '' }} · {{ $item['language'] ?? '' }}</span>
                </div>
                <div class="sp-list__actions">
                    <x-admin.icon-btn
                        kind="edit"
                        type="button"
                        data-admin-open-modal="admin-radar-news-edit-modal"
                        data-admin-modal-title="Edit {{ $item['id'] ?? '' }}"
                        data-admin-item-id="{{ $item['id'] }}"
                        data-admin-fill="{{ json_encode($itemFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                    />
                    <form method="post" action="{{ locale_route('admin.radar.items.destroy', ['itemId' => $item['id']]) }}" data-admin-confirm-delete data-confirm-message="Delete news item?">
                        @csrf
                        @method('DELETE')
                        <x-admin.icon-btn kind="delete" type="submit" />
                    </form>
                </div>
            </div>
            <?php
        };
    @endphp
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($sources) + (count($orphanItems) > 0 ? 1 : 0)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search sources / news…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                <button type="button" class="tools-btn" data-admin-open-modal="admin-radar-news-create-modal" data-text-de="News anlegen" data-text-en="Add news">Add news</button>
                <button type="button" class="tools-btn tools-btn--primary" data-admin-open-modal="admin-radar-source-create-modal" data-text-de="Quelle hinzufügen" data-text-en="Add source">Add source</button>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'radar-source-saved' => 'Source saved',
            'radar-source-deleted' => 'Source deleted',
            'radar-item-saved' => 'News saved',
            'radar-item-deleted' => 'News deleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-text-de="Keine Treffer." data-text-en="No matches.">No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach ($sources as $source)
                    @php
                        $sid = (string) ($source['id'] ?? '');
                        $news = $itemsBySource[$sid] ?? [];
                        $sourceUrl = $source['source_url'] ?? $source['url'] ?? '';
                        $feedUrl = $source['feed_url'] ?? '';
                        $searchBits = [$sid, $source['name'] ?? '', $source['short_name'] ?? '', $sourceUrl, $feedUrl, $source['language'] ?? '', $source['type'] ?? ''];
                        foreach ($news as $item) {
                            $searchBits[] = $item['id'] ?? '';
                            $searchBits[] = is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['en'] ?? '') : ($item['title'] ?? '');
                            $searchBits[] = is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['de'] ?? '') : '';
                        }
                        $sourceFill = [
                            'name' => $source['name'] ?? '',
                            'short_name' => $source['short_name'] ?? '',
                            'source_url' => $sourceUrl,
                            'feed_url' => $feedUrl,
                            'language' => $source['language'] ?? 'en',
                            'type' => $source['type'] ?? 'Governance News',
                        ];
                        $sourceFillJson = json_encode($sourceFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
                        $sourceName = $source['name'] ?? $sid;
                    @endphp
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ implode(' ', $searchBits) }}">
                        <button
                            type="button"
                            class="admin-hub__expand-toggle"
                            aria-expanded="false"
                            aria-controls="admin-radar-news-{{ $sid }}"
                            data-admin-expand-toggle
                        >
                            <span class="admin-hub__expand-chevron" aria-hidden="true"></span>
                            <span>
                                <h3 class="admin-hub__card-title">{{ $sourceName }}</h3>
                                <p class="admin-hub__card-meta">{{ $sid }} · {{ count($news) }} news · {{ $source['language'] ?? '' }} · {{ $source['type'] ?? '' }}</p>
                            </span>
                        </button>
                        <div class="admin-hub__card-actions">
                            <x-admin.icon-btn
                                kind="edit"
                                type="button"
                                data-admin-open-modal="admin-radar-source-edit-modal"
                                data-admin-modal-title="Edit {{ $sourceName }}"
                                data-admin-source-id="{{ $sid }}"
                                data-admin-fill="{{ $sourceFillJson }}"
                            />
                            <form method="post" action="{{ locale_route('admin.radar.sources.destroy', ['sourceId' => $sid]) }}" style="display:inline" data-admin-confirm-delete data-confirm-message="Delete source?">
                                @csrf
                                @method('DELETE')
                                <x-admin.icon-btn kind="delete" type="submit" />
                            </form>
                        </div>
                        <div class="admin-hub__expand-children" id="admin-radar-news-{{ $sid }}" hidden>
                            @forelse ($news as $item)
                                @php $renderNewsRow($item); @endphp
                            @empty
                                <p class="admin-hub__meta" data-text-de="Keine News für diese Quelle." data-text-en="No news for this source.">No news for this source.</p>
                            @endforelse
                        </div>
                    </article>
                @endforeach

                @if (count($orphanItems) > 0)
                    @php
                        $orphanSearch = ['manual', 'orphan', 'unassigned'];
                        foreach ($orphanItems as $item) {
                            $orphanSearch[] = $item['id'] ?? '';
                            $orphanSearch[] = is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['en'] ?? '') : ($item['title'] ?? '');
                        }
                    @endphp
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ implode(' ', $orphanSearch) }}">
                        <button
                            type="button"
                            class="admin-hub__expand-toggle"
                            aria-expanded="false"
                            aria-controls="admin-radar-news-manual"
                            data-admin-expand-toggle
                        >
                            <span class="admin-hub__expand-chevron" aria-hidden="true"></span>
                            <span>
                                <h3 class="admin-hub__card-title" data-text-de="Ohne Quelle / Manual" data-text-en="Unassigned / manual">Unassigned / manual</h3>
                                <p class="admin-hub__card-meta">{{ count($orphanItems) }} news</p>
                            </span>
                        </button>
                        <div class="admin-hub__expand-children" id="admin-radar-news-manual" hidden>
                            @foreach ($orphanItems as $item)
                                @php $renderNewsRow($item); @endphp
                            @endforeach
                        </div>
                    </article>
                @endif
            </div>

            <div class="supplier-table-wrap" data-admin-overview-panel="table">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Id</th>
                            <th>News</th>
                            <th>Lang</th>
                            <th>Type</th>
                            <th class="admin-hub__table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sources as $source)
                            @php
                                $sid = (string) ($source['id'] ?? '');
                                $news = $itemsBySource[$sid] ?? [];
                                $sourceUrl = $source['source_url'] ?? $source['url'] ?? '';
                                $feedUrl = $source['feed_url'] ?? '';
                                $searchBits = [$sid, $source['name'] ?? '', $source['short_name'] ?? '', $sourceUrl, $feedUrl, $source['language'] ?? '', $source['type'] ?? ''];
                                foreach ($news as $item) {
                                    $searchBits[] = $item['id'] ?? '';
                                    $searchBits[] = is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['en'] ?? '') : ($item['title'] ?? '');
                                    $searchBits[] = is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['de'] ?? '') : '';
                                }
                                $sourceFill = [
                                    'name' => $source['name'] ?? '',
                                    'short_name' => $source['short_name'] ?? '',
                                    'source_url' => $sourceUrl,
                                    'feed_url' => $feedUrl,
                                    'language' => $source['language'] ?? 'en',
                                    'type' => $source['type'] ?? 'Governance News',
                                ];
                                $sourceFillJson = json_encode($sourceFill, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
                                $sourceName = $source['name'] ?? $sid;
                            @endphp
                            <tr data-overview-item data-search-text="{{ implode(' ', $searchBits) }}">
                                <td><strong>{{ $sourceName }}</strong></td>
                                <td><code>{{ $sid }}</code></td>
                                <td>{{ count($news) }}</td>
                                <td>{{ $source['language'] ?? '' }}</td>
                                <td>{{ $source['type'] ?? '' }}</td>
                                <td class="admin-hub__table-actions">
                                    <x-admin.icon-btn
                                        kind="edit"
                                        type="button"
                                        data-admin-open-modal="admin-radar-source-edit-modal"
                                        data-admin-modal-title="Edit {{ $sourceName }}"
                                        data-admin-source-id="{{ $sid }}"
                                        data-admin-fill="{{ $sourceFillJson }}"
                                    />
                                    <form method="post" action="{{ locale_route('admin.radar.sources.destroy', ['sourceId' => $sid]) }}" style="display:inline" data-admin-confirm-delete data-confirm-message="Delete source?">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.icon-btn kind="delete" type="submit" />
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if (count($orphanItems) > 0)
                            @php
                                $orphanSearch = ['manual', 'orphan', 'unassigned'];
                                foreach ($orphanItems as $item) {
                                    $orphanSearch[] = $item['id'] ?? '';
                                    $orphanSearch[] = is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['en'] ?? '') : ($item['title'] ?? '');
                                }
                            @endphp
                            <tr data-overview-item data-search-text="{{ implode(' ', $orphanSearch) }}">
                                <td>
                                    <strong data-text-de="Ohne Quelle / Manual" data-text-en="Unassigned / manual">Unassigned / manual</strong>
                                </td>
                                <td><code>manual</code></td>
                                <td>{{ count($orphanItems) }}</td>
                                <td>—</td>
                                <td>—</td>
                                <td class="admin-hub__table-actions">
                                    <span class="admin-hub__meta" data-text-de="News in Karten-Ansicht" data-text-en="Edit news in Cards view">Edit news in Cards view</span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <x-admin.modal id="admin-radar-news-create-modal" title="Add news" titleDe="News anlegen" titleEn="Add news">
            <form method="post" action="{{ locale_route('admin.radar.items.store') }}" class="admin-hub__editor">
                @csrf
                <x-admin.field label="Source">
                    <select name="source_id" class="tools-input">
                        <option value="manual">manual</option>
                        @foreach ($sourceOptions as $optId => $optLabel)
                            <option value="{{ $optId }}">{{ $optLabel }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
                <x-admin.field label="URL">
                    <input class="tools-input" name="url" type="url" required value="{{ old('url') }}">
                </x-admin.field>
                <x-admin.locale-tabs name="radar-news-create">
                    <x-slot:de>
                        <x-admin.field label="Title DE">
                            <input class="tools-input" name="title_de" required value="{{ old('title_de') }}">
                        </x-admin.field>
                        <x-admin.field label="Summary DE">
                            <textarea name="summary_de" class="admin-hub__textarea admin-hub__textarea--short tools-input">{{ old('summary_de') }}</textarea>
                        </x-admin.field>
                    </x-slot:de>
                    <x-slot:en>
                        <x-admin.field label="Title EN">
                            <input class="tools-input" name="title_en" required value="{{ old('title_en') }}">
                        </x-admin.field>
                        <x-admin.field label="Summary EN">
                            <textarea name="summary_en" class="admin-hub__textarea admin-hub__textarea--short tools-input">{{ old('summary_en') }}</textarea>
                        </x-admin.field>
                    </x-slot:en>
                </x-admin.locale-tabs>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="admin-radar-news-edit-modal" title="Edit news" titleDe="News bearbeiten" titleEn="Edit news">
            <form method="post" action="#" class="admin-hub__editor" data-admin-item-edit-form data-action-template="{{ url('/admin/radar/items/__ID__') }}">
                @csrf
                @method('PUT')
                <x-admin.field label="Source">
                    <select name="source_id" class="tools-input">
                        <option value="manual">manual</option>
                        @foreach ($sourceOptions as $optId => $optLabel)
                            <option value="{{ $optId }}">{{ $optLabel }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
                <x-admin.field label="URL">
                    <input class="tools-input" name="url" type="url" required>
                </x-admin.field>
                <input type="hidden" name="type" value="Governance News">
                <x-admin.locale-tabs name="radar-news-edit">
                    <x-slot:de>
                        <x-admin.field label="Title DE">
                            <input class="tools-input" name="title_de" required>
                        </x-admin.field>
                        <x-admin.field label="Summary DE">
                            <textarea name="summary_de" class="admin-hub__textarea admin-hub__textarea--short tools-input"></textarea>
                        </x-admin.field>
                    </x-slot:de>
                    <x-slot:en>
                        <x-admin.field label="Title EN">
                            <input class="tools-input" name="title_en" required>
                        </x-admin.field>
                        <x-admin.field label="Summary EN">
                            <textarea name="summary_en" class="admin-hub__textarea admin-hub__textarea--short tools-input"></textarea>
                        </x-admin.field>
                    </x-slot:en>
                </x-admin.locale-tabs>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="admin-radar-source-create-modal" title="Add source" titleDe="Quelle hinzufügen" titleEn="Add source">
            <form method="post" action="{{ locale_route('admin.radar.sources.store') }}" class="admin-hub__editor">
                @csrf
                <x-admin.field label="Id (optional)">
                    <input class="tools-input" name="id" pattern="[a-z0-9\-]+" value="{{ old('id') }}">
                </x-admin.field>
                <x-admin.field label="Name">
                    <input class="tools-input" name="name" required value="{{ old('name') }}">
                </x-admin.field>
                <x-admin.field label="Short name">
                    <input class="tools-input" name="short_name" value="{{ old('short_name') }}">
                </x-admin.field>
                <x-admin.field label="Source URL">
                    <input class="tools-input" name="source_url" type="url" required value="{{ old('source_url') }}">
                </x-admin.field>
                <x-admin.field label="Feed URL">
                    <input class="tools-input" name="feed_url" type="url" value="{{ old('feed_url') }}">
                </x-admin.field>
                <x-admin.field label="Language">
                    <select class="tools-input" name="language"><option value="en">en</option><option value="de">de</option></select>
                </x-admin.field>
                <x-admin.field label="Type">
                    <input class="tools-input" name="type" value="{{ old('type', 'Governance News') }}">
                </x-admin.field>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="admin-radar-source-edit-modal" title="Edit source" titleDe="Quelle bearbeiten" titleEn="Edit source">
            <form method="post" action="#" class="admin-hub__editor" data-admin-source-edit-form data-action-template="{{ url('/admin/radar/sources/__ID__') }}">
                @csrf
                @method('PUT')
                <x-admin.field label="Name">
                    <input class="tools-input" name="name" required>
                </x-admin.field>
                <x-admin.field label="Short name">
                    <input class="tools-input" name="short_name">
                </x-admin.field>
                <x-admin.field label="Source URL">
                    <input class="tools-input" name="source_url" type="url" required>
                </x-admin.field>
                <x-admin.field label="Feed URL">
                    <input class="tools-input" name="feed_url" type="url">
                </x-admin.field>
                <x-admin.field label="Language">
                    <select class="tools-input" name="language"><option value="en">en</option><option value="de">de</option></select>
                </x-admin.field>
                <x-admin.field label="Type">
                    <input class="tools-input" name="type">
                </x-admin.field>
                <div class="admin-hub__modal-footer">
                    <button type="button" class="tools-btn" data-shared-modal-close>Cancel</button>
                    <button class="tools-btn tools-btn--primary" type="submit">Save</button>
                </div>
            </form>
        </x-admin.modal>
    </div>
@endsection
