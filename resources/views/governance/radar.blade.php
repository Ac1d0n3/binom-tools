@extends('layouts.tools', ['viteEntries' => ['resources/js/governance/radar.js']])

@section('title', 'Governance Radar - ' . config('app.name'))
@section('meta_description', 'Governance Radar für Data Governance News, RSS-Quellen, Richtlinien-Änderungen, Standards, Vendor Updates und eigene Hinweise mit Suche und Filter.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Governance Radar - {{ config('app.name') }}">
    <meta property="og:description" content="Kuratierter Radar für Data Governance, Datenschutz, Standards, Vendor Updates und eigene Governance-Hinweise.">
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
    <div
        class="tools-content governance-radar"
        data-governance-radar
        @if ($radarSourcesApiUrl)
            data-radar-sources-api-url="{{ $radarSourcesApiUrl }}"
        @endif
    >
        <header class="governance-radar__hero">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Governance Radar" data-text-en="Governance Radar">Governance Radar</p>
                <h1 class="tools-page-title" data-text-de="News, Richtlinien und Vendor-Änderungen beobachten" data-text-en="Track news, policy changes, and vendor updates">Track news, policy changes, and vendor updates</h1>
                <p
                    class="tools-page-lead"
                    data-hub-lead
                    data-text-de="Ein kuratierter Monitor für Data Governance: offizielle Quellen, RSS-Feeds, Standards, Vendor Release Notes und eigene Hinweise. Keine Werbeplattform, sondern eine Arbeitsliste für Entscheidungen, Reviews und Change Requests."
                    data-text-en="A curated monitor for data governance: official sources, RSS feeds, standards, vendor release notes, and internal notes. Not an ad platform, but a working list for decisions, reviews, and change requests."
                >A curated monitor for data governance: official sources, RSS feeds, standards, vendor release notes, and internal notes. Not an ad platform, but a working list for decisions, reviews, and change requests.</p>
                <div class="governance-radar__actions">
                    @if ($radarSourcesApiUrl)
                        <a class="governance-hub__button" href="#governance-radar-manage">
                            <i class="fa-solid fa-rss" aria-hidden="true"></i>
                            <span data-text-de="RSS verwalten" data-text-en="Manage RSS">Manage RSS</span>
                        </a>
                    @endif
                </div>
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
        </header>

        <section class="governance-radar__notice" aria-label="Radar Hinweis">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
            <p
                data-text-de="Der Radar ersetzt keine rechtliche, regulatorische oder technische Prüfung. Er hilft, Änderungen früh zu sehen, sie zu filtern und als Aufgabe, Review oder Change Request einzuordnen."
                data-text-en="The radar does not replace legal, regulatory, or technical review. It helps spot changes early, filter them, and classify them as tasks, reviews, or change requests."
            >The radar does not replace legal, regulatory, or technical review. It helps spot changes early, filter them, and classify them as tasks, reviews, or change requests.</p>
        </section>

        <section class="governance-radar__filters" aria-labelledby="governance-radar-filter-title">
            <div class="governance-radar__section-head">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Suche und Filter" data-text-en="Search and filter">Search and filter</p>
                    <h2 id="governance-radar-filter-title" class="tools-section__title" data-text-de="Was willst du beobachten?" data-text-en="What do you want to monitor?">What do you want to monitor?</h2>
                </div>
                <button type="button" class="governance-hub__button" data-governance-radar-reset>
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span data-text-de="Zurücksetzen" data-text-en="Reset">Reset</span>
                </button>
            </div>
            <div class="governance-radar__filter-grid">
                <label>
                    <span data-text-de="Suche" data-text-en="Search">Search</span>
                    <input class="tools-input" type="search" placeholder="EDPB, Unity Catalog, Datenschutz, Clean Rooms..." data-governance-radar-search>
                </label>
                <label>
                    <span data-text-de="Thema" data-text-en="Topic">Topic</span>
                    <select class="tools-input" data-governance-radar-topic>
                        <option value="" data-text-de="Alle Themen" data-text-en="All topics">All topics</option>
                        @foreach ($filters['topics'] as $topic)
                            <option value="{{ $topic }}">{{ $topic }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span data-text-de="Typ" data-text-en="Type">Type</span>
                    <select class="tools-input" data-governance-radar-type>
                        <option value="" data-text-de="Alle Typen" data-text-en="All types">All types</option>
                        @foreach ($filters['types'] as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span data-text-de="Stack" data-text-en="Stack">Stack</span>
                    <select class="tools-input" data-governance-radar-stack>
                        <option value="" data-text-de="Alle Stacks" data-text-en="All stacks">All stacks</option>
                        @foreach ($filters['stacks'] as $stack)
                            <option value="{{ $stack }}">{{ $stack }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </section>

        <section class="governance-radar__items" id="governance-radar-results" aria-labelledby="governance-radar-results-title">
            <div class="governance-radar__section-head">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Radar Treffer" data-text-en="Radar matches">Radar matches</p>
                    <h2 id="governance-radar-results-title" class="tools-section__title" data-text-de="Einträge für Review, Plan oder Change Request" data-text-en="Items for review, plan, or change request">Items for review, plan, or change request</h2>
                </div>
                <span class="governance-radar__count" data-governance-radar-count>{{ count($items) }}</span>
            </div>

            <div class="governance-radar__list">
                @foreach ($items as $item)
                    @php
                        $source = $sourceNames[$item['source_id'] ?? ''] ?? ($item['source_id'] ?? 'Quelle');
                        $itemUrl = (string) ($item['url'] ?? '');
                        $itemHref = str_starts_with($itemUrl, '/') ? url($itemUrl) : $itemUrl;
                        $topics = implode(' ', $item['topics'] ?? []);
                        $stacks = implode(' ', $item['stack'] ?? []);
                        $search = strtolower(implode(' ', [
                            $item['title'] ?? '',
                            $item['summary'] ?? '',
                            $source,
                            $item['type'] ?? '',
                            $item['impact'] ?? '',
                            $item['region'] ?? '',
                            $topics,
                            $stacks,
                        ]));
                    @endphp
                    <article
                        class="governance-radar__item"
                        data-governance-radar-item
                        data-search="{{ $search }}"
                        data-topic="{{ $topics }}"
                        data-type="{{ $item['type'] ?? '' }}"
                        data-stack="{{ $stacks }}"
                    >
                        <div class="governance-radar__item-main">
                            <div class="governance-radar__meta">
                                <span>{{ $source }}</span>
                                <span>{{ $item['published_at'] ?? '' }}</span>
                                <span>{{ $item['region'] ?? '' }}</span>
                            </div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['summary'] }}</p>
                            <div class="governance-radar__chips">
                                <span>{{ $item['type'] }}</span>
                                <span>{{ $item['impact'] }}</span>
                                @foreach ($item['topics'] as $topic)
                                    <span>{{ $topic }}</span>
                                @endforeach
                            </div>
                            <p class="governance-radar__action-note">{{ $item['recommended_action'] }}</p>
                        </div>
                        <div class="governance-radar__item-actions">
                            <a class="governance-hub__button governance-hub__button--primary" href="{{ $itemHref }}" target="_blank" rel="noopener">
                                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                <span data-text-de="Quelle öffnen" data-text-en="Open source">Open source</span>
                            </a>
                            <a class="governance-hub__button" href="{{ locale_route('governance.index') }}">
                                <i class="fa-solid fa-compass" aria-hidden="true"></i>
                                <span data-text-de="Im Advisor prüfen" data-text-en="Review in advisor">Review in advisor</span>
                            </a>
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
                <p class="governance-radar__manage-lead" data-text-de="Hier legst du eigene Unternehmens-, Vendor- oder Behördenfeeds ab. Die Quellen bleiben bei Login persistent und sind für Radar-Reviews, spätere Ingest-Jobs und Change-Request-Monitoring vorbereitet." data-text-en="Store internal, vendor, or authority feeds here. Signed-in sources persist and are prepared for radar reviews, later ingest jobs, and change-request monitoring.">Store internal, vendor, or authority feeds here. Signed-in sources persist and are prepared for radar reviews, later ingest jobs, and change-request monitoring.</p>
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
@endsection
