@props([
    'tagCounts' => [],
    'storyCount' => 0,
])

@if (count($tagCounts) > 0)
    <aside class="tools-tag-sidebar" data-tag-sidebar>
        <div
            class="tools-tag-sidebar__panel"
            id="playbook-tag-sidebar-panel"
            data-tag-sidebar-panel
            role="group"
            aria-label="Tags"
        >
            <h2 class="tools-tag-sidebar__title" data-i18n="overview.tagsTitle">Tags</h2>
            <p class="tools-tag-sidebar__lead" data-i18n="overview.tagsLead">
                Filter stories by topic. Numbers show how many stories use each tag.
            </p>

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
                    <span class="tools-tag-sidebar__option-label" data-i18n="overview.tagAll">All</span>
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
        </div>
    </aside>
@endif
