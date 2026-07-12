@extends('layouts.tools')

@section('title', 'Stories — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--overview" data-overview-filter-root>
        <div @class([
            'tools-overview-layout',
            'tools-overview-layout--with-tags' => count($tagCounts) > 0,
        ])>
            <div class="tools-overview-main">
                <h1 class="tools-page-title" data-i18n="playbooks.indexTitle">Stories</h1>
                <p class="tools-page-lead" data-i18n="playbooks.indexLead">
                    Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.
                </p>

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

                    @if (count($tagCounts) > 0)
                        <button
                            type="button"
                            class="tools-tag-sidebar__toggle"
                            data-tag-sidebar-toggle
                            aria-expanded="true"
                            aria-controls="playbook-tag-sidebar-panel"
                        >
                            <i class="fa-solid fa-tags" aria-hidden="true"></i>
                            <span class="tools-tag-sidebar__toggle-label" data-i18n="overview.tagsTitle">Tags</span>
                            <i class="fa-solid fa-chevron-right tools-tag-sidebar__toggle-icon" aria-hidden="true"></i>
                        </button>
                    @endif
                </div>

                <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                    No matches for your search.
                </p>

                <p class="tools-overview-empty" data-overview-series-empty hidden data-i18n="overview.seriesNoResults">
                    No matching series.
                </p>

                @if (count($playbooks) === 0)
                    <p class="tools-section__lead" data-i18n="playbooks.empty">No playbooks published yet.</p>
                @else
                    <div id="playbook-overview-stories" data-overview-view-panel="stories">
                        <div class="tools-card-grid">
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

            <x-playbooks.tag-sidebar :tag-counts="$tagCounts" :story-count="count($playbooks)" />
        </div>
    </div>
@endsection
