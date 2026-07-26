@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
    'viteEntries' => ['resources/js/governance/radar.js'],
])

@section('title', 'Governance Radar - ' . config('app.name'))
@section('meta_description', 'Governance Radar für Data Governance News, RSS-Quellen, Richtlinien-Änderungen, Standards, Vendor Updates und eigene Hinweise mit Suche und Filter.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Governance Radar - {{ config('app.name') }}">
    <meta property="og:description" content="Kuratierter Radar für Data Governance, Datenschutz, Standards, Vendor Updates und eigene Governance-Hinweise.">
    <script>
        try {
            if (localStorage.getItem('binom-tools-governance-radar-compact') === 'true') {
                document.documentElement.dataset.radarCompactBoot = 'true';
            }
        } catch (error) {}
    </script>
    <style>
        html[data-radar-compact-boot='true'] .governance-radar__intro {
            display: none !important;
        }
    </style>
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Governance Radar',
            'description' => 'Curated governance news radar for RSS sources, policy changes, standards, vendor updates, and internal notes.',
            'url' => url()->current(),
            'author' => [
                '@type' => 'Person',
                'name' => 'Thomas Lindackers',
                'url' => config('playbooks.author_url', 'https://binom.net'),
            ],
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name'),
                'url' => url('/'),
            ],
            'mainEntity' => [
                '@type' => 'ItemList',
                'numberOfItems' => count($items),
                'itemListElement' => array_map(static fn (array $item, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['title'] ?? '',
                    'url' => $item['url'] ?? url()->current(),
                ], $items, array_keys($items)),
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    @php
        $advisorImpacts = ['Handlungsbedarf', 'Prüfen', 'Architektur prüfen'];
    @endphp
    <div
        class="tools-content tools-content--overview tools-content--governance-radar governance-radar"
        data-governance-radar
        @if ($radarSourcesApiUrl)
            data-radar-sources-api-url="{{ $radarSourcesApiUrl }}"
        @endif
        @if ($radarFeedSyncApiUrl ?? null)
            data-radar-feed-sync-api-url="{{ $radarFeedSyncApiUrl }}"
        @endif
        @if ($radarOverlaysApiUrl ?? null)
            data-radar-overlays-api-url="{{ $radarOverlaysApiUrl }}"
        @endif
    >
        <div class="tools-overview-sticky-header governance-radar-sticky">
            <div class="governance-radar__intro" id="governance-radar-intro" data-governance-radar-intro>
                <div class="governance-radar-sticky__heading">
                    <div>
                        <p class="governance-hub__eyebrow" data-text-de="Governance Radar" data-text-en="Governance Radar">Governance Radar</p>
                        <h1 class="tools-page-title" data-text-de="News, Richtlinien und Vendor-Änderungen beobachten" data-text-en="Track news, policy changes, and vendor updates">Track news, policy changes, and vendor updates</h1>
                    </div>
                    <aside class="governance-radar__summary" aria-label="Radar coverage">
                        <div>
                            <strong>{{ count($items) }}</strong>
                            <span data-text-de="Radar Einträge" data-text-en="radar items">radar items</span>
                        </div>
                        <div>
                            <strong>{{ count($sources) }}</strong>
                            <span data-text-de="Quellen" data-text-en="sources">sources</span>
                        </div>
                        <div>
                            <strong>{{ count($filters['topics']) }}</strong>
                            <span data-text-de="Themen" data-text-en="topics">topics</span>
                        </div>
                    </aside>
                </div>
                <p
                    class="tools-page-lead governance-radar-sticky__lead"
                    data-hub-lead
                    data-text-de="Ein kuratierter Monitor für Data Governance: offizielle Quellen, RSS-Feeds, Standards, Vendor Release Notes und eigene Hinweise. Beobachten und filtern — in den Advisor nur, wenn ein Treffer wirklich Handlungsbedarf hat."
                    data-text-en="A curated monitor for data governance: official sources, RSS feeds, standards, vendor release notes, and internal notes. Watch and filter — open the advisor only when an item truly needs action."
                >A curated monitor for data governance: official sources, RSS feeds, standards, vendor release notes, and internal notes. Watch and filter — open the advisor only when an item truly needs action.</p>
                <p class="governance-radar__notice" data-hub-lead aria-label="Radar Hinweis">
                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                    <span
                        data-text-de="Der Radar ersetzt keine rechtliche, regulatorische oder technische Prüfung. Er hilft, Änderungen früh zu sehen und zu filtern. Nur bei klarerem Handlungsbedarf lohnt der Sprung in den Advisor."
                        data-text-en="The radar does not replace legal, regulatory, or technical review. It helps spot and filter changes early. Jump to the advisor only when there is clearer action needed."
                    >The radar does not replace legal, regulatory, or technical review. It helps spot and filter changes early. Jump to the advisor only when there is clearer action needed.</span>
                </p>
                @if ($radarSourcesApiUrl)
                    <div class="governance-radar__actions">
                        <a class="governance-hub__button" href="#governance-radar-manage">
                            <i class="fa-solid fa-rss" aria-hidden="true"></i>
                            <span data-text-de="RSS verwalten" data-text-en="Manage RSS">Manage RSS</span>
                        </a>
                        @if ($radarFeedSyncApiUrl ?? null)
                            <button
                                type="button"
                                class="governance-hub__button"
                                data-governance-radar-feed-sync
                                data-text-de="Updates aktualisieren"
                                data-text-en="Refresh updates"
                            >
                                <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                                <span data-text-de="Updates aktualisieren" data-text-en="Refresh updates">Refresh updates</span>
                            </button>
                        @endif
                    </div>
                @endif
                <p class="governance-radar__feed-status" data-governance-radar-feed-status>
                    <span data-text-de="Updates:" data-text-en="Updates:">Updates:</span>
                    <time
                        data-governance-radar-feed-synced-at
                        @if (! empty($feedSyncedAt))
                            datetime="{{ $feedSyncedAt }}"
                        @endif
                    >
                        @if (! empty($feedSyncedAt))
                            {{ $feedSyncedAt }}
                        @else
                            <span data-text-de="noch nicht synchronisiert" data-text-en="not synced yet">not synced yet</span>
                        @endif
                    </time>
                </p>
                <details
                    class="governance-radar__feed-errors"
                    data-governance-radar-feed-errors
                    @if (($feedSyncErrorCount ?? 0) < 1) hidden @endif
                >
                    <summary>
                        <span
                            data-governance-radar-feed-error-summary
                            data-text-de="{{ (int) ($feedSyncErrorCount ?? 0) }} Quellen mit Sync-Problemen"
                            data-text-en="{{ (int) ($feedSyncErrorCount ?? 0) }} sources with sync issues"
                        >{{ (int) ($feedSyncErrorCount ?? 0) }} sources with sync issues</span>
                    </summary>
                    <ul class="governance-radar__feed-error-list" data-governance-radar-feed-error-list>
                        @foreach ($feedSyncErrors ?? [] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </details>
            </div>

            <div class="tools-overview-toolbar governance-radar__toolbar" aria-label="Radar search and filters">
                <label class="tools-overview-search">
                    <span class="sr-only" data-text-de="Suche" data-text-en="Search">Search</span>
                    <i class="fa-solid fa-magnifying-glass tools-overview-search__icon" aria-hidden="true"></i>
                    <input
                        type="search"
                        class="tools-overview-search__input"
                        data-governance-radar-search
                        autocomplete="off"
                        placeholder="EDPB, Unity Catalog, Datenschutz, Clean Rooms..."
                    >
                </label>
                <div class="governance-radar__multi" data-governance-radar-type-multi>
                    <span class="sr-only" data-text-de="Typen" data-text-en="Types">Types</span>
                    <button
                        type="button"
                        class="governance-radar__multi-toggle"
                        data-governance-radar-type-toggle
                        aria-expanded="false"
                        aria-haspopup="listbox"
                    >
                        <span data-governance-radar-type-label data-text-de="Alle Typen" data-text-en="All types">All types</span>
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="governance-radar__multi-panel" data-governance-radar-type-panel hidden role="listbox" aria-multiselectable="true">
                        @foreach ($filters['types'] as $type)
                            @php
                                $typeLabelEn = $type['label']['en'] ?? $type['value'];
                                $typeLabelDe = $type['label']['de'] ?? $typeLabelEn;
                            @endphp
                            <label class="governance-radar__multi-option">
                                <input
                                    type="checkbox"
                                    value="{{ $type['value'] }}"
                                    data-governance-radar-type-option
                                    data-text-de="{{ $typeLabelDe }}"
                                    data-text-en="{{ $typeLabelEn }}"
                                >
                                <span data-text-de="{{ $typeLabelDe }}" data-text-en="{{ $typeLabelEn }}">{{ $typeLabelEn }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-text-de="Stack" data-text-en="Stack">Stack</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-governance-radar-stack>
                            <option value="" data-text-de="Alle Stacks" data-text-en="All stacks">All stacks</option>
                            @foreach ($filters['stacks'] as $stack)
                                <option value="{{ $stack }}">{{ $stack }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-text-de="Region" data-text-en="Region">Region</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-governance-radar-region>
                            <option value="" data-text-de="Alle Regionen" data-text-en="All regions">All regions</option>
                            @foreach ($filters['regions'] as $region)
                                <option value="{{ $region }}">{{ $region }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-text-de="Thema" data-text-en="Topic">Topic</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-governance-radar-topic>
                            <option value="" data-text-de="Alle Themen" data-text-en="All topics">All topics</option>
                            @foreach ($filters['topics'] as $topic)
                                <option
                                    value="{{ $topic }}"
                                    data-topic-types="{{ implode('|', $topicTypeMap[$topic] ?? []) }}"
                                >{{ $topic }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <button type="button" class="governance-hub__button governance-radar__toolbar-action" data-governance-radar-reset title="Reset filters">
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span class="sr-only" data-text-de="Zurücksetzen" data-text-en="Reset">Reset</span>
                </button>
                <button
                    type="button"
                    class="governance-hub__button governance-radar__compact-toggle governance-radar__toolbar-action"
                    data-governance-radar-compact-toggle
                    aria-pressed="false"
                    aria-controls="governance-radar-intro"
                    title="Compact"
                >
                    <i class="fa-solid fa-compress" aria-hidden="true" data-compact-icon></i>
                    <span class="sr-only" data-compact-label data-text-de="Kompakt" data-text-en="Compact">Compact</span>
                </button>
                <div class="tools-overview-read-controls governance-radar__read-controls" role="group" aria-label="Read status">
                    <button
                        type="button"
                        class="tools-overview-read-controls__button tools-overview-read-controls__button--active"
                        data-governance-radar-hide-read
                        aria-pressed="true"
                        title="Show read items"
                        aria-label="Show read items"
                    >
                        <i class="fa-solid fa-eye-slash" aria-hidden="true"></i>
                        <span class="sr-only">Show read items</span>
                    </button>
                    <button
                        type="button"
                        class="tools-overview-read-controls__button tools-overview-read-controls__button--reset"
                        data-governance-radar-read-reset
                        disabled
                        aria-disabled="true"
                        title="Reset read status"
                        aria-label="Reset read status"
                    >
                        <i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i>
                        <span class="sr-only">Reset read status</span>
                    </button>
                </div>
                <span class="governance-radar__count" data-governance-radar-count>{{ count($items) }}</span>
            </div>
        </div>

        <div class="tools-overview-scroll governance-radar-scroll">
            <section class="governance-radar__items" id="governance-radar-results" aria-label="Radar matches">
                <div class="governance-radar__list">
                    @foreach ($items as $item)
                        @php
                            $itemId = (string) ($item['id'] ?? '');
                            $source = $sourceNames[$item['source_id'] ?? ''] ?? ($item['source_id'] ?? 'Quelle');
                            $itemUrl = (string) ($item['url'] ?? '');
                            $itemHref = str_starts_with($itemUrl, '/') ? url($itemUrl) : $itemUrl;
                            $rawTopics = array_values(array_filter(array_map(
                                static fn ($topic) => is_string($topic) ? $topic : '',
                                (array) ($item['topics'] ?? []),
                            )));
                            $stackValues = array_values(array_filter(
                                array_map(static fn ($stack) => is_string($stack) ? $stack : '', (array) ($item['stack'] ?? [])),
                                static fn (string $stack): bool => $stack !== '' && $stack !== 'Alle Stacks',
                            ));
                            $itemType = (string) ($item['type'] ?? '');
                            $typeIcon = (string) ($typeMeta[$itemType]['icon'] ?? 'fa-circle');
                            $typeTone = (string) ($typeMeta[$itemType]['tone'] ?? 'news');
                            $impact = (string) ($item['impact'] ?? '');
                            $impactTone = match ($impact) {
                                'Handlungsbedarf' => 'critical',
                                'Prüfen', 'Architektur prüfen' => 'watch',
                                'Relevant' => 'signal',
                                'Best Practice' => 'practice',
                                default => 'info',
                            };
                            $showAdvisor = in_array($impact, $advisorImpacts, true);
                            $origin = (string) ($item['origin'] ?? 'example');
                            $language = (string) ($item['language'] ?? 'de');
                            $displayLanguage = (string) ($item['display_language'] ?? $language);
                            $hasOverlay = (bool) ($item['has_overlay'] ?? false);
                            $enrichable = (bool) ($item['enrichable'] ?? false);
                            $overlay = is_array($item['overlay'] ?? null) ? $item['overlay'] : [];
                            $search = strtolower(implode(' ', [
                                $item['title'] ?? '',
                                $item['summary'] ?? '',
                                $overlay['titleDe'] ?? '',
                                $overlay['summaryDe'] ?? '',
                                $overlay['recommendedActionDe'] ?? '',
                                $source,
                                $itemType,
                                $impact,
                                $item['region'] ?? '',
                                implode(' ', $rawTopics),
                                implode(' ', $stackValues),
                            ]));
                        @endphp
                        <article
                            class="governance-radar__item governance-radar__item--{{ $typeTone }}"
                            data-governance-radar-item
                            data-item-id="{{ $itemId }}"
                            data-origin="{{ $origin }}"
                            data-language="{{ $language }}"
                            data-search="{{ $search }}"
                            data-topics="{{ implode('||', $rawTopics) }}"
                            data-type="{{ $itemType }}"
                            data-stack="{{ implode(' ', $stackValues) }}"
                            data-region="{{ $item['region'] ?? '' }}"
                            data-impact="{{ $impact }}"
                            @if ($hasOverlay)
                                data-has-overlay="true"
                            @endif
                        >
                            <div class="governance-radar__item-row">
                                <div
                                    class="governance-radar__item-icon governance-radar__item-icon--{{ $typeTone }}"
                                    aria-hidden="true"
                                    title="{{ $itemType }}"
                                >
                                    <i class="fa-solid {{ $typeIcon }}"></i>
                                </div>
                                <div class="governance-radar__item-content">
                                    <div class="governance-radar__item-main">
                                        <div class="governance-radar__item-top">
                                            <div class="governance-radar__meta">
                                                <span class="governance-radar__meta-type">{{ $itemType }}</span>
                                                <span class="governance-radar__meta-source">{{ $source }}</span>
                                                @if (! empty($item['published_at']))
                                                    <time datetime="{{ $item['published_at'] }}">{{ $item['published_at'] }}</time>
                                                @endif
                                                @if (! empty($item['region']))
                                                    <span class="governance-radar__meta-region">{{ $item['region'] }}</span>
                                                @endif
                                                @if ($origin === 'example')
                                                    <span class="governance-radar__badge" data-text-de="Beispiel" data-text-en="Example">Example</span>
                                                @elseif ($origin === 'vendor')
                                                    <span class="governance-radar__badge" data-text-de="Vendor" data-text-en="Vendor">Vendor</span>
                                                @elseif ($origin === 'feed')
                                                    <span class="governance-radar__badge governance-radar__badge--live" data-text-de="Live" data-text-en="Live">Live</span>
                                                @endif
                                                @if (strtoupper($displayLanguage) !== 'DE' || $language !== 'de')
                                                    <span class="governance-radar__badge governance-radar__badge--lang">{{ strtoupper($displayLanguage) }}</span>
                                                @endif
                                                @if ($hasOverlay)
                                                    <span class="governance-radar__badge governance-radar__badge--curated" data-text-de="Kuratiert" data-text-en="Curated">Curated</span>
                                                @endif
                                            </div>
                                            <span class="governance-radar__impact governance-radar__impact--{{ $impactTone }}">{{ $impact }}</span>
                                        </div>
                                        <h3 data-radar-item-title>{{ $item['title'] }}</h3>
                                        <p class="governance-radar__summary-text" data-radar-item-summary>{{ $item['summary'] }}</p>
                                        @if (! empty($item['editorial_note']))
                                            <p class="governance-radar__editorial-note">{{ $item['editorial_note'] }}</p>
                                        @endif
                                    </div>
                                    <div class="governance-radar__item-footer">
                                        <p class="governance-radar__action-note">
                                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                            <span data-radar-item-action>{{ $item['recommended_action'] }}</span>
                                        </p>
                                        <div class="governance-radar__item-bar">
                                            @php
                                                $topicVisible = array_slice($rawTopics, 0, 4);
                                                $topicMore = max(0, count($rawTopics) - count($topicVisible));
                                            @endphp
                                            <div class="governance-radar__chips" aria-label="Tags">
                                                @foreach ($stackValues as $stackValue)
                                                    <span class="governance-radar__chip governance-radar__chip--stack">{{ $stackValue }}</span>
                                                @endforeach
                                                @foreach ($topicVisible as $topic)
                                                    <span class="governance-radar__chip">{{ $topic }}</span>
                                                @endforeach
                                                @if ($topicMore > 0)
                                                    <span class="governance-radar__chip governance-radar__chip--more">+{{ $topicMore }}</span>
                                                @endif
                                            </div>
                                            <div class="governance-radar__item-actions">
                                                @if ($showAdvisor)
                                                    <a class="governance-hub__button" href="{{ locale_route('governance.index') }}" data-governance-radar-advisor>
                                                        <i class="fa-solid fa-compass" aria-hidden="true"></i>
                                                        <span data-text-de="Im Advisor prüfen" data-text-en="Review in advisor">Review in advisor</span>
                                                    </a>
                                                @endif
                                                @if (($canEnrichRadarItems ?? false) && $enrichable)
                                                    <button
                                                        type="button"
                                                        class="governance-hub__button"
                                                        data-governance-radar-enrich
                                                        data-item-id="{{ $itemId }}"
                                                        data-overlay-title-de="{{ $overlay['titleDe'] ?? '' }}"
                                                        data-overlay-summary-de="{{ $overlay['summaryDe'] ?? '' }}"
                                                        data-overlay-action-de="{{ $overlay['recommendedActionDe'] ?? '' }}"
                                                        data-overlay-note="{{ $overlay['editorialNote'] ?? '' }}"
                                                        data-overlay-impact="{{ $overlay['impact'] ?? '' }}"
                                                        data-original-title="{{ $item['original_title'] ?? $item['title'] ?? '' }}"
                                                        data-original-summary="{{ $item['original_summary'] ?? '' }}"
                                                        data-original-action="{{ $item['original_recommended_action'] ?? '' }}"
                                                    >
                                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                        <span data-text-de="Anreichern" data-text-en="Enrich">Enrich</span>
                                                    </button>
                                                @endif
                                                <button
                                                    type="button"
                                                    class="governance-hub__button governance-radar__mark-read"
                                                    data-governance-radar-mark-read
                                                    data-item-id="{{ $itemId }}"
                                                    aria-pressed="false"
                                                    title="Mark as read"
                                                    aria-label="Mark as read"
                                                >
                                                    <i class="fa-solid fa-eye" aria-hidden="true" data-mark-read-icon></i>
                                                    <span class="sr-only" data-mark-read-label>Mark read</span>
                                                </button>
                                                <a class="governance-hub__button governance-hub__button--primary governance-radar__open-source" href="{{ $itemHref }}" @if (! str_starts_with($itemUrl, '/')) target="_blank" rel="noopener" @endif>
                                                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                                    <span data-text-de="Quelle öffnen" data-text-en="Open source">Open source</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <p class="governance-radar__empty" data-governance-radar-empty hidden data-text-de="Keine Treffer für diese Filter." data-text-en="No matches for these filters.">No matches for these filters.</p>
                <p class="governance-radar__empty" data-governance-radar-unread-empty hidden data-text-de="Alle passenden Einträge sind bereits gelesen." data-text-en="All matching items are already read.">All matching items are already read.</p>
            </section>

            @if ($canEnrichRadarItems ?? false)
                <dialog class="governance-radar__enrich-dialog" data-governance-radar-enrich-dialog>
                    <form method="dialog" class="governance-radar__enrich-form" data-governance-radar-enrich-form>
                        <div class="governance-radar__enrich-head">
                            <h2 data-text-de="Eintrag anreichern" data-text-en="Enrich item">Enrich item</h2>
                            <button type="submit" class="governance-hub__button" value="cancel" data-text-de="Schließen" data-text-en="Close">Close</button>
                        </div>
                        <p class="governance-radar__enrich-original" data-governance-radar-enrich-original></p>
                        <input type="hidden" name="itemId" data-enrich-item-id>
                        <label>
                            <span data-text-de="Titel (DE)" data-text-en="Title (DE)">Title (DE)</span>
                            <input class="tools-input" type="text" name="titleDe" data-enrich-title-de maxlength="500">
                        </label>
                        <label>
                            <span data-text-de="Zusammenfassung (DE)" data-text-en="Summary (DE)">Summary (DE)</span>
                            <textarea class="tools-input" name="summaryDe" data-enrich-summary-de rows="4" maxlength="4000"></textarea>
                        </label>
                        <label>
                            <span data-text-de="Empfohlene Aktion (DE)" data-text-en="Recommended action (DE)">Recommended action (DE)</span>
                            <textarea class="tools-input" name="recommendedActionDe" data-enrich-action-de rows="3" maxlength="2000"></textarea>
                        </label>
                        <label>
                            <span data-text-de="Redaktionelle Notiz" data-text-en="Editorial note">Editorial note</span>
                            <textarea class="tools-input" name="editorialNote" data-enrich-note rows="2" maxlength="2000"></textarea>
                        </label>
                        <label>
                            <span data-text-de="Impact Override" data-text-en="Impact override">Impact override</span>
                            <input class="tools-input" type="text" name="impact" data-enrich-impact maxlength="64" placeholder="Prüfen, Relevant, …">
                        </label>
                        <p class="governance-radar__source-status" data-governance-radar-enrich-status hidden></p>
                        <div class="governance-radar__form-actions">
                            <button type="button" class="governance-hub__button governance-hub__button--primary" data-governance-radar-enrich-save>
                                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                <span data-text-de="Speichern" data-text-en="Save">Save</span>
                            </button>
                            <button type="button" class="governance-hub__button" data-governance-radar-enrich-reset>
                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                <span data-text-de="Overlay löschen" data-text-en="Delete overlay">Delete overlay</span>
                            </button>
                        </div>
                    </form>
                </dialog>
            @endif

            @if ($radarSourcesApiUrl)
                <section class="governance-radar__manage" id="governance-radar-manage" aria-labelledby="governance-radar-manage-title" data-governance-radar-admin>
                    <div class="governance-radar__section-head">
                        <div>
                            <p class="governance-hub__eyebrow" data-text-de="Eingeloggt" data-text-en="Signed in">Signed in</p>
                            <h2 id="governance-radar-manage-title" class="tools-section__title" data-text-de="RSS-Quellen verwalten" data-text-en="Manage RSS sources">Manage RSS sources</h2>
                        </div>
                        <span class="governance-radar__count" data-governance-radar-source-count>{{ count($customSources) }}</span>
                    </div>
                    <p class="governance-radar__manage-lead" data-text-de="Hier legst du eigene Unternehmens-, Vendor- oder Behördenfeeds ab. Die Quellen bleiben bei Login persistent und sind für Radar-Reviews, spätere Ingest-Jobs und Monitoring vorbereitet." data-text-en="Store internal, vendor, or authority feeds here. Signed-in sources persist and are prepared for radar reviews, later ingest jobs, and monitoring.">Store internal, vendor, or authority feeds here. Signed-in sources persist and are prepared for radar reviews, later ingest jobs, and monitoring.</p>
                    <form class="governance-radar__source-form" data-governance-radar-source-form>
                        <label>
                            <span data-text-de="Name" data-text-en="Name">Name</span>
                            <input class="tools-input" name="name" type="text" placeholder="Interner Governance Blog" required>
                        </label>
                        <label>
                            <span data-text-de="RSS/Atom URL" data-text-en="RSS/Atom URL">RSS/Atom URL</span>
                            <input class="tools-input" name="feedUrl" type="url" placeholder="https://example.com/feed.xml" required>
                        </label>
                        <label>
                            <span data-text-de="Typ" data-text-en="Type">Type</span>
                            <select class="tools-input" name="type">
                                <option value="Eigene Quelle" data-text-de="Eigene Quelle" data-text-en="Internal source">Internal source</option>
                                <option value="Behörde" data-text-de="Behörde" data-text-en="Authority">Authority</option>
                                <option value="Vendor" data-text-de="Vendor" data-text-en="Vendor">Vendor</option>
                                <option value="Standard" data-text-de="Standard" data-text-en="Standard">Standard</option>
                            </select>
                        </label>
                        <label>
                            <span data-text-de="Themen" data-text-en="Topics">Topics</span>
                            <input class="tools-input" name="topics" type="text" placeholder="PII, Data Quality, Fabric">
                        </label>
                        <div class="governance-radar__form-actions">
                            <button type="submit" class="governance-hub__button governance-hub__button--primary">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                <span data-text-de="Quelle hinzufügen" data-text-en="Add source">Add source</span>
                            </button>
                            <button type="reset" class="governance-hub__button">
                                <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                                <span data-text-de="Leeren" data-text-en="Clear">Clear</span>
                            </button>
                        </div>
                    </form>
                    <p class="governance-radar__source-status" data-governance-radar-source-status role="status"></p>
                    <div class="governance-radar__source-list" data-governance-radar-custom-source-list>
                        @foreach ($customSources as $source)
                            <article class="governance-radar__custom-source" data-source-id="{{ $source['id'] }}">
                                <div>
                                    <h3>{{ $source['name'] }}</h3>
                                    <p>{{ $source['feedUrl'] }}</p>
                                </div>
                                <button type="button" class="governance-hub__button" data-governance-radar-delete-source="{{ $source['id'] }}">
                                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                    <span data-text-de="Löschen" data-text-en="Delete">Delete</span>
                                </button>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
