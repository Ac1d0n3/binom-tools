@props([
    'tagCounts' => [],
    'categoryCounts' => [],
    'storyCount' => 0,
])

@if (count($tagCounts) > 0 || count($categoryCounts) > 0)
    <aside class="tools-tag-sidebar" data-tag-sidebar>
        <div
            class="tools-tag-sidebar__panel"
            id="playbook-tag-sidebar-panel"
            data-tag-sidebar-panel
            role="group"
            aria-label="Filter"
        >
            <div class="tools-filter-sidebar__header">
                <h2 class="tools-tag-sidebar__title" data-i18n="overview.filterTitle">Filter</h2>
                <button
                    type="button"
                    class="tools-filter-sidebar__reset"
                    data-overview-filter-reset
                    disabled
                    aria-disabled="true"
                    data-i18n="overview.filterReset"
                >
                    Reset filters
                </button>
            </div>
            <p class="tools-tag-sidebar__lead" data-i18n="overview.filterLead">
                Narrow stories by category and topic.
            </p>

            @if (count($categoryCounts) > 0)
                <section class="tools-filter-sidebar__section">
                    <h3 class="tools-filter-sidebar__section-title" data-i18n="overview.categoryTitle">Category</h3>
                    <div class="tools-tag-sidebar__list tools-tag-sidebar__list--compact">
                        <button
                            type="button"
                            class="tools-tag-sidebar__option tools-filter-chip tools-filter-chip--active"
                            data-overview-category="all"
                        >
                            <span class="tools-tag-sidebar__option-label" data-i18n="overview.categoryAll">ALL</span>
                            <span class="tools-tag-sidebar__count">{{ $storyCount }}</span>
                        </button>

                        @foreach ($categoryCounts as $category)
                            <button
                                type="button"
                                class="tools-tag-sidebar__option tools-filter-chip"
                                data-overview-category="{{ $category['key'] }}"
                            >
                                <span
                                    class="tools-tag-sidebar__option-label"
                                    data-text-de="{{ $category['labelDe'] }}"
                                    data-text-en="{{ $category['labelEn'] }}"
                                >{{ $category['labelEn'] }}</span>
                                <span class="tools-tag-sidebar__count">{{ $category['count'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($tagCounts) > 0)
                <section class="tools-filter-sidebar__section">
                    <div class="tools-filter-sidebar__section-header">
                        <h3 class="tools-filter-sidebar__section-title" data-i18n="overview.tagsSectionTitle">Tags</h3>
                        <div
                            class="tools-filter-sidebar__tag-mode"
                            data-tag-match-mode="or"
                            role="group"
                            aria-label="Tag match mode"
                        >
                            <button
                                type="button"
                                class="tools-filter-sidebar__tag-mode-btn tools-filter-sidebar__tag-mode-btn--active"
                                data-overview-tag-mode-toggle="or"
                                aria-pressed="true"
                            >
                                <span data-i18n="overview.tagModeOr">OR</span>
                            </button>
                            <button
                                type="button"
                                class="tools-filter-sidebar__tag-mode-btn"
                                data-overview-tag-mode-toggle="and"
                                aria-pressed="false"
                            >
                                <span data-i18n="overview.tagModeAnd">AND</span>
                            </button>
                        </div>
                    </div>

                    <label class="tools-tag-sidebar__search">
                        <span class="sr-only" data-i18n="overview.tagsSearchLabel">Search tags</span>
                        <i class="fa-solid fa-magnifying-glass tools-tag-sidebar__search-icon" aria-hidden="true"></i>
                        <input
                            type="search"
                            class="tools-tag-sidebar__search-input"
                            data-tag-sidebar-search
                            autocomplete="off"
                            data-i18n-placeholder="overview.tagsSearchPlaceholder"
                            placeholder="Search tags…"
                        />
                    </label>

                    <p class="tools-tag-sidebar__empty" data-tag-sidebar-empty hidden data-i18n="overview.tagsNoResults">
                        No matching tags.
                    </p>

                    <div class="tools-tag-sidebar__list">
                        <button
                            type="button"
                            class="tools-tag-sidebar__option tools-filter-chip tools-filter-chip--active"
                            data-overview-tag="all"
                        >
                            <span class="tools-tag-sidebar__option-label" data-i18n="overview.tagAll">ALL</span>
                            <span class="tools-tag-sidebar__count">{{ $storyCount }}</span>
                        </button>

                        @foreach ($tagCounts as $tag)
                            <button
                                type="button"
                                class="tools-tag-sidebar__option tools-filter-chip"
                                data-overview-tag="{{ $tag['name'] }}"
                            >
                                <span class="tools-tag-sidebar__option-label">{{ $tag['name'] }}</span>
                                <span class="tools-tag-sidebar__count">{{ $tag['count'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </aside>
@endif
