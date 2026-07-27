@props([
    'tagCounts' => [],
    'categoryCounts' => [],
    'productCounts' => [],
    'storyCount' => 0,
])

@php
    $hasProducts = count($productCounts) > 0;
    $hasCategories = count($categoryCounts) > 0;
    $hasTags = count($tagCounts) > 0;
    $defaultTab = $hasProducts ? 'product' : ($hasCategories ? 'category' : 'tags');
@endphp

@if ($hasProducts || $hasCategories || $hasTags)
    <aside class="tools-tag-sidebar" data-tag-sidebar data-filter-tab-root data-filter-tab-active="{{ $defaultTab }}">
        <div
            class="tools-tag-sidebar__panel"
            id="playbook-tag-sidebar-panel"
            data-tag-sidebar-panel
            role="group"
            aria-label="Filter"
        >
            <div class="tools-filter-sidebar__header">
                <h2 class="tools-tag-sidebar__title" data-i18n="overview.filterTitle">Filter</h2>
                <div class="tools-filter-sidebar__header-actions">
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
                    <button
                        type="button"
                        class="tools-filter-sidebar__collapse"
                        data-tag-sidebar-toggle
                        aria-expanded="true"
                        aria-controls="playbook-tag-sidebar-panel"
                        data-i18n-aria="overview.filterHide"
                        aria-label="Hide filters"
                        title="Hide filters"
                    >
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        <span class="sr-only" data-i18n="overview.filterHide">Hide filters</span>
                    </button>
                </div>
            </div>

            <x-shared.ui.tabs
                variant="underline"
                aria-label="Filter dimensions"
                class="tools-filter-sidebar__tabs"
            >
                @if ($hasProducts)
                    <button
                        type="button"
                        class="tools-filter-sidebar__tab {{ $defaultTab === 'product' ? 'tools-filter-sidebar__tab--active' : '' }}"
                        role="tab"
                        id="playbook-filter-tab-product"
                        data-filter-tab="product"
                        aria-selected="{{ $defaultTab === 'product' ? 'true' : 'false' }}"
                        aria-controls="playbook-filter-panel-product"
                        tabindex="{{ $defaultTab === 'product' ? '0' : '-1' }}"
                    >
                        <span data-i18n="overview.productTitle">Product</span>
                    </button>
                @endif
                @if ($hasCategories)
                    <button
                        type="button"
                        class="tools-filter-sidebar__tab {{ $defaultTab === 'category' ? 'tools-filter-sidebar__tab--active' : '' }}"
                        role="tab"
                        id="playbook-filter-tab-category"
                        data-filter-tab="category"
                        aria-selected="{{ $defaultTab === 'category' ? 'true' : 'false' }}"
                        aria-controls="playbook-filter-panel-category"
                        tabindex="{{ $defaultTab === 'category' ? '0' : '-1' }}"
                    >
                        <span data-i18n="overview.categoryTitle">Category</span>
                    </button>
                @endif
                @if ($hasTags)
                    <button
                        type="button"
                        class="tools-filter-sidebar__tab {{ $defaultTab === 'tags' ? 'tools-filter-sidebar__tab--active' : '' }}"
                        role="tab"
                        id="playbook-filter-tab-tags"
                        data-filter-tab="tags"
                        aria-selected="{{ $defaultTab === 'tags' ? 'true' : 'false' }}"
                        aria-controls="playbook-filter-panel-tags"
                        tabindex="{{ $defaultTab === 'tags' ? '0' : '-1' }}"
                    >
                        <span data-i18n="overview.tagsSectionTitle">Tags</span>
                    </button>
                @endif
            </x-shared.ui.tabs>

            <div class="tools-filter-sidebar__panels">
                @if ($hasProducts)
                    <section
                        class="tools-filter-sidebar__section tools-filter-sidebar__section--panel"
                        id="playbook-filter-panel-product"
                        role="tabpanel"
                        data-filter-tab-panel="product"
                        aria-labelledby="playbook-filter-tab-product"
                        @if ($defaultTab !== 'product') hidden @endif
                    >
                        <div class="tools-tag-sidebar__list tools-tag-sidebar__list--stack">
                            <button
                                type="button"
                                class="tools-tag-sidebar__option tools-filter-chip tools-filter-chip--active"
                                data-overview-product="all"
                            >
                                <span class="tools-tag-sidebar__option-label" data-i18n="overview.productAllShort">ALL</span>
                                <span class="tools-tag-sidebar__count">{{ $storyCount }}</span>
                            </button>

                            @foreach ($productCounts as $product)
                                <button
                                    type="button"
                                    class="tools-tag-sidebar__option tools-filter-chip"
                                    data-overview-product="{{ $product['id'] }}"
                                >
                                    <span class="tools-tag-sidebar__option-label">{{ $product['label'] }}</span>
                                    <span class="tools-tag-sidebar__count">{{ $product['count'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($hasCategories)
                    <section
                        class="tools-filter-sidebar__section tools-filter-sidebar__section--panel"
                        id="playbook-filter-panel-category"
                        role="tabpanel"
                        data-filter-tab-panel="category"
                        aria-labelledby="playbook-filter-tab-category"
                        @if ($defaultTab !== 'category') hidden @endif
                    >
                        <div class="tools-tag-sidebar__list tools-tag-sidebar__list--stack">
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

                @if ($hasTags)
                    <section
                        class="tools-filter-sidebar__section tools-filter-sidebar__section--panel tools-filter-sidebar__section--tags"
                        id="playbook-filter-panel-tags"
                        role="tabpanel"
                        data-filter-tab-panel="tags"
                        aria-labelledby="playbook-filter-tab-tags"
                        @if ($defaultTab !== 'tags') hidden @endif
                    >
                        <div class="tools-filter-sidebar__tags-toolbar">
                            <label class="tools-tag-sidebar__search tools-tag-sidebar__search--inline">
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
                                    data-i18n-aria="overview.tagModeOr"
                                    aria-label="OR"
                                    title="OR"
                                >
                                    <span aria-hidden="true">||</span>
                                    <span class="sr-only" data-i18n="overview.tagModeOr">OR</span>
                                </button>
                                <button
                                    type="button"
                                    class="tools-filter-sidebar__tag-mode-btn"
                                    data-overview-tag-mode-toggle="and"
                                    aria-pressed="false"
                                    data-i18n-aria="overview.tagModeAnd"
                                    aria-label="AND"
                                    title="AND"
                                >
                                    <span aria-hidden="true">&amp;</span>
                                    <span class="sr-only" data-i18n="overview.tagModeAnd">AND</span>
                                </button>
                            </div>
                        </div>

                        <p class="tools-tag-sidebar__empty" data-tag-sidebar-empty hidden data-i18n="overview.tagsNoResults">
                            No matching tags.
                        </p>

                        <div class="tools-tag-sidebar__list tools-tag-sidebar__list--tags">
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
        </div>
    </aside>
@endif
