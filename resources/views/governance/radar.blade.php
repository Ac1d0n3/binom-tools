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
                    </div>
                @endif
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
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-text-de="Typ" data-text-en="Type">Type</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-governance-radar-type>
                            <option value="" data-text-de="Alle Typen" data-text-en="All types">All types</option>
                            @foreach ($filters['types'] as $type)
                                @php
                                    $typeLabelEn = $type['label']['en'] ?? $type['value'];
                                    $typeLabelDe = $type['label']['de'] ?? $typeLabelEn;
                                @endphp
                                <option
                                    value="{{ $type['value'] }}"
                                    data-text-de="{{ $typeLabelDe }}"
                                    data-text-en="{{ $typeLabelEn }}"
                                >{{ $typeLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
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
                <button type="button" class="governance-hub__button" data-governance-radar-reset>
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span data-text-de="Zurücksetzen" data-text-en="Reset">Reset</span>
                </button>
                <button
                    type="button"
                    class="governance-hub__button governance-radar__compact-toggle"
                    data-governance-radar-compact-toggle
                    aria-pressed="false"
                    aria-controls="governance-radar-intro"
                >
                    <i class="fa-solid fa-compress" aria-hidden="true" data-compact-icon></i>
                    <span data-compact-label data-text-de="Kompakt" data-text-en="Compact">Compact</span>
                </button>
                <span class="governance-radar__count" data-governance-radar-count>{{ count($items) }}</span>
            </div>
        </div>

        <div class="tools-overview-scroll governance-radar-scroll">
            <section class="governance-radar__items" id="governance-radar-results" aria-label="Radar matches">
                <div class="governance-radar__list">
                    @foreach ($items as $item)
                        @php
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
                            $search = strtolower(implode(' ', [
                                $item['title'] ?? '',
                                $item['summary'] ?? '',
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
                            data-search="{{ $search }}"
                            data-topics="{{ implode('||', $rawTopics) }}"
                            data-type="{{ $itemType }}"
                            data-stack="{{ implode(' ', $stackValues) }}"
                            data-region="{{ $item['region'] ?? '' }}"
                            data-impact="{{ $impact }}"
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
                                            </div>
                                            <span class="governance-radar__impact governance-radar__impact--{{ $impactTone }}">{{ $impact }}</span>
                                        </div>
                                        <h3>{{ $item['title'] }}</h3>
                                        <p class="governance-radar__summary-text">{{ $item['summary'] }}</p>
                                    </div>
                                    <div class="governance-radar__item-footer">
                                        <p class="governance-radar__action-note">
                                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                            <span>{{ $item['recommended_action'] }}</span>
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
                                                <a class="governance-hub__button governance-hub__button--primary" href="{{ $itemHref }}" @if (! str_starts_with($itemUrl, '/')) target="_blank" rel="noopener" @endif>
                                                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                                    <span data-text-de="Quelle öffnen" data-text-en="Open source">Open source</span>
                                                </a>
                                                @if ($showAdvisor)
                                                    <a class="governance-hub__button" href="{{ locale_route('governance.index') }}" data-governance-radar-advisor>
                                                        <i class="fa-solid fa-compass" aria-hidden="true"></i>
                                                        <span data-text-de="Im Advisor prüfen" data-text-en="Review in advisor">Review in advisor</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <p class="governance-radar__empty" data-governance-radar-empty hidden data-text-de="Keine Treffer für diese Filter." data-text-en="No matches for these filters.">No matches for these filters.</p>
            </section>

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
