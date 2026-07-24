@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Stories — ' . config('app.name'))
@section('meta_description', 'Governance stories and playbooks — searchable guides on data quality, PII, lineage, KPIs, ownership, and more.')

@section('content')
    <div
        class="playbook-offline-banner"
        data-playbook-offline-banner
        hidden
        role="status"
    ></div>
    <div class="tools-content tools-content--overview" data-overview-filter-root>
        <div @class([
            'tools-overview-layout',
            'tools-overview-layout--with-tags' => count($tagCounts) > 0 || count($categoryCounts) > 0,
        ])>
            <div class="tools-overview-main">
                @if (config('playbooks.overview.show_title'))
                    <h1
                        class="tools-page-title"
                        @if (filled(config('playbooks.overview.title_en')) || filled(config('playbooks.overview.title_de')))
                            data-text-de="{{ config('playbooks.overview.title_de') ?? config('playbooks.overview.title_en') }}"
                            data-text-en="{{ config('playbooks.overview.title_en') ?? config('playbooks.overview.title_de') }}"
                        @else
                            data-i18n="playbooks.indexTitle"
                        @endif
                    >{{ config('playbooks.overview.title_en') ?? 'Stories' }}</h1>
                @endif

                @if (config('playbooks.overview.show_lead'))
                    <p
                        class="tools-page-lead"
                        @if (filled(config('playbooks.overview.lead_en')) || filled(config('playbooks.overview.lead_de')))
                            data-text-de="{{ config('playbooks.overview.lead_de') ?? config('playbooks.overview.lead_en') }}"
                            data-text-en="{{ config('playbooks.overview.lead_en') ?? config('playbooks.overview.lead_de') }}"
                        @else
                            data-i18n="playbooks.indexLead"
                        @endif
                    >Step-by-step governance guides — from idea to implementation.</p>
                @endif

                <div class="tools-overview-toolbar">
                    @if (count($seriesList) > 0)
                        <div class="tools-overview-view-toggle" role="tablist" aria-label="Overview view">
                            <button
                                type="button"
                                class="tools-overview-view-toggle__button tools-overview-view-toggle__button--active"
                                data-overview-view-toggle="stories"
                                role="tab"
                                aria-selected="true"
                                aria-controls="playbook-overview-stories"
                            >
                                <span data-i18n="overview.viewStories">Stories</span>
                            </button>
                            <button
                                type="button"
                                class="tools-overview-view-toggle__button"
                                data-overview-view-toggle="series"
                                role="tab"
                                aria-selected="false"
                                aria-controls="playbook-overview-series"
                            >
                                <span data-i18n="overview.viewSeries">Series</span>
                            </button>
                        </div>
                    @endif

                    @if (count($playbooks) > 0)
                        <div
                            class="tools-overview-layout-toggle"
                            role="group"
                        >
                            <button
                                type="button"
                                class="tools-overview-layout-toggle__button tools-overview-layout-toggle__button--active"
                                data-overview-layout-toggle="grid"
                                aria-pressed="true"
                                data-i18n-aria="overview.layoutGrid"
                                aria-label="Grid view"
                                title="Grid view"
                            >
                                <i class="fa-solid fa-grip" aria-hidden="true"></i>
                                <span class="sr-only" data-i18n="overview.layoutGrid">Grid view</span>
                            </button>
                            <button
                                type="button"
                                class="tools-overview-layout-toggle__button"
                                data-overview-layout-toggle="list"
                                aria-pressed="false"
                                data-i18n-aria="overview.layoutList"
                                aria-label="List view"
                                title="List view"
                            >
                                <i class="fa-solid fa-list" aria-hidden="true"></i>
                                <span class="sr-only" data-i18n="overview.layoutList">List view</span>
                            </button>
                        </div>

                        <div class="tools-overview-read-controls" role="group">
                            <button
                                type="button"
                                class="tools-overview-read-controls__button"
                                data-overview-hide-read
                                aria-pressed="false"
                                data-i18n-aria="overview.hideRead"
                                aria-label="Hide read stories"
                                title="Hide read stories"
                            >
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                <span class="sr-only" data-i18n="overview.hideRead">Hide read stories</span>
                            </button>
                            <button
                                type="button"
                                class="tools-overview-read-controls__button tools-overview-read-controls__button--reset"
                                data-overview-read-reset
                                disabled
                                aria-disabled="true"
                                data-i18n-aria="overview.resetRead"
                                aria-label="Reset read status"
                                title="Reset read status"
                            >
                                <i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i>
                                <span class="sr-only" data-i18n="overview.resetRead">Reset read status</span>
                            </button>
                        </div>
                    @endif

                    <div class="tools-overview-sort">
                        <label class="tools-overview-sort__label" for="playbook-overview-sort" data-i18n="overview.sortLabel">Sort</label>
                        <div class="tools-overview-sort__field">
                            <select
                                id="playbook-overview-sort"
                                class="tools-overview-sort__select"
                                data-overview-sort
                            >
                                <option value="date-desc" data-i18n="overview.sortDateDesc">Newest first</option>
                                <option value="date-asc" data-i18n="overview.sortDateAsc">Oldest first</option>
                                <option value="name-asc" data-i18n="overview.sortNameAsc">Name A–Z</option>
                                <option value="name-desc" data-i18n="overview.sortNameDesc">Name Z–A</option>
                            </select>
                            <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                        </div>
                    </div>

                    @if (count($availableProducts ?? []) > 0)
                        <label class="tools-overview-product-filter">
                            <span class="sr-only" data-i18n="overview.productLabel">Product</span>
                            <span class="tools-overview-sort__field">
                                <select class="tools-overview-sort__select" data-overview-product>
                                    <option value="all" data-i18n="overview.productAll">All products</option>
                                    @foreach ($availableProducts as $product)
                                        <option value="{{ $product }}">{{ \App\Playbooks\PlaybookProducts::label($product) }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                            </span>
                        </label>
                    @endif

                    @if (count($tagCounts) > 0 || count($categoryCounts) > 0)
                        <button
                            type="button"
                            class="tools-tag-sidebar__toggle"
                            data-tag-sidebar-toggle
                            aria-expanded="true"
                            aria-controls="playbook-tag-sidebar-panel"
                        >
                            <i class="fa-solid fa-filter" aria-hidden="true"></i>
                            <span class="tools-tag-sidebar__toggle-label" data-i18n="overview.filterTitle">Filter</span>
                            <i class="fa-solid fa-chevron-right tools-tag-sidebar__toggle-icon" aria-hidden="true"></i>
                        </button>
                    @endif

                    <div class="playbook-offline playbook-offline--index" data-playbook-offline-index>
                        <button
                            type="button"
                            class="tools-btn tools-btn--secondary playbook-offline__btn"
                            data-playbook-offline-open
                            data-i18n-aria="playbooks.offline.manageTitle"
                            aria-label="Offline stories"
                            title="Offline stories"
                        >
                            <i class="fa-solid fa-download" aria-hidden="true"></i>
                            <span data-i18n="playbooks.offline.saveAllShort">Offline</span>
                            <span
                                class="playbook-offline__count"
                                data-playbook-offline-count
                                hidden
                            ></span>
                        </button>
                    </div>

                    <label class="tools-overview-search">
                        <span class="sr-only" data-i18n="overview.searchLabel">Search</span>
                        <i class="fa-solid fa-magnifying-glass tools-overview-search__icon" aria-hidden="true"></i>
                        <input
                            type="search"
                            class="tools-overview-search__input"
                            data-overview-search
                            autocomplete="off"
                            data-i18n-placeholder="overview.searchPlaceholder"
                            placeholder="Search playbooks…"
                        />
                    </label>
                </div>

                <div class="tools-overview-scroll">
                    <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                        No matches for your search.
                    </p>

                    <p class="tools-overview-empty" data-overview-unread-empty hidden data-i18n="overview.noUnreadResults">
                        All matching stories are already read.
                    </p>

                    <p class="tools-overview-empty" data-overview-series-empty hidden data-i18n="overview.seriesNoResults">
                        No matching series.
                    </p>

                    @if (count($playbooks) === 0)
                        <p class="tools-section__lead" data-i18n="playbooks.empty">No playbooks published yet.</p>
                    @else
                        <div id="playbook-overview-stories" data-overview-view-panel="stories">
                            <div class="tools-card-grid" data-overview-stories-grid>
                                @foreach ($playbooks as $item)
                                    <x-playbooks.card :item="$item" />
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($seriesList) > 0)
                        <div id="playbook-overview-series" data-overview-view-panel="series" hidden>
                            <div class="tools-card-grid tools-card-grid--series">
                                @foreach ($seriesList as $series)
                                    <x-playbooks.series-card :series="$series" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <x-playbooks.tag-sidebar
                :tag-counts="$tagCounts"
                :category-counts="$categoryCounts"
                :story-count="count($playbooks)"
            />
        </div>
    </div>

    <dialog class="playbook-offline-modal" data-playbook-offline-modal aria-labelledby="playbook-offline-modal-title">
        <div class="playbook-offline-modal__panel">
            <header class="playbook-offline-modal__header">
                <h2 id="playbook-offline-modal-title" class="playbook-offline-modal__title" data-i18n="playbooks.offline.manageTitle">
                    Offline stories
                </h2>
                <button
                    type="button"
                    class="tools-btn tools-btn--ghost tools-btn--compact playbook-offline-modal__close"
                    data-playbook-offline-close
                    data-i18n-aria="playbooks.offline.close"
                    aria-label="Close"
                >
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </header>

            <p class="playbook-offline-modal__summary" data-playbook-offline-summary></p>

            <div class="playbook-offline-modal__actions">
                <button
                    type="button"
                    class="tools-btn tools-btn--primary"
                    data-playbook-offline-download-all
                >
                    <i class="fa-solid fa-download" aria-hidden="true"></i>
                    <span data-playbook-offline-download-label data-i18n="playbooks.offline.downloadAllAction">Download all</span>
                </button>
                <p class="playbook-offline-modal__size-hint" data-playbook-offline-size-hint hidden></p>
            </div>

            <div class="playbook-offline-modal__progress" data-playbook-offline-progress hidden>
                <progress
                    class="playbook-offline-modal__progress-bar"
                    data-playbook-offline-progress-bar
                    max="100"
                    value="0"
                ></progress>
                <p
                    class="playbook-offline-modal__progress-label"
                    data-playbook-offline-progress-label
                    role="status"
                    aria-live="polite"
                ></p>
            </div>

            <p
                class="playbook-offline-modal__empty"
                data-playbook-offline-empty
                hidden
                data-i18n="playbooks.offline.manageEmpty"
            >No stories saved offline yet.</p>

            <ul class="playbook-offline-modal__list" data-playbook-offline-list></ul>

            <footer class="playbook-offline-modal__footer">
                <div class="playbook-offline-modal__confirm" data-playbook-offline-confirm-remove hidden>
                    <p data-i18n="playbooks.offline.confirmRemoveAllInline">Remove all offline copies?</p>
                    <div class="playbook-offline-modal__confirm-actions">
                        <button
                            type="button"
                            class="tools-btn tools-btn--ghost tools-btn--sm"
                            data-playbook-offline-confirm-no
                            data-i18n="playbooks.offline.confirmNo"
                        >Cancel</button>
                        <button
                            type="button"
                            class="tools-btn tools-btn--accent tools-btn--sm"
                            data-playbook-offline-confirm-yes
                            data-i18n="playbooks.offline.confirmYes"
                        >Remove</button>
                    </div>
                </div>
                <button
                    type="button"
                    class="tools-btn tools-btn--ghost"
                    data-playbook-offline-remove-all
                    disabled
                >
                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                    <span data-i18n="playbooks.offline.removeAllShort">Remove all</span>
                </button>
            </footer>
        </div>
    </dialog>
@endsection
