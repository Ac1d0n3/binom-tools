/** Client-side search and tag filtering for overview index pages. */
const TAG_SIDEBAR_STORAGE_KEY = 'binom-tools-tag-sidebar';
const OVERVIEW_VIEW_STORAGE_KEY = 'binom-tools-overview-view';

export function initOverviewFilters() {
    const root = document.querySelector('[data-overview-filter-root]');
    if (!root) return;

    initTagSidebar(root);
    initTagSidebarSearch(root);
    initOverviewViewToggle(root);

    const searchInput = /** @type {HTMLInputElement | null} */ (
        root.querySelector('[data-overview-search]')
    );
    const tagButtons = root.querySelectorAll('[data-overview-tag]');
    const storyItems = root.querySelectorAll('[data-overview-item]');
    const seriesItems = root.querySelectorAll('[data-overview-series-item]');
    const emptyEl = root.querySelector('[data-overview-empty]');
    const seriesEmptyEl = root.querySelector('[data-overview-series-empty]');

    /** @type {string} */
    let activeTag = 'all';

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    /** @returns {'stories' | 'series'} */
    const activeView = () => {
        const layout = root.querySelector('.tools-overview-layout');

        return layout?.classList.contains('tools-overview-layout--view-series') ? 'series' : 'stories';
    };

    const applyStories = () => {
        const query = normalize(searchInput?.value ?? '');
        let visible = 0;

        storyItems.forEach((item) => {
            const text = normalize(item.getAttribute('data-search-text') ?? '');
            const tags = (item.getAttribute('data-tags') ?? '')
                .split(',')
                .map((tag) => tag.trim())
                .filter(Boolean);
            const matchesSearch = query === '' || text.includes(query);
            const matchesTag = activeTag === 'all' || tags.includes(activeTag);
            const show = matchesSearch && matchesTag;

            item.hidden = !show;
            if (show) visible += 1;
        });

        if (emptyEl instanceof HTMLElement) {
            emptyEl.hidden = visible > 0;
        }
    };

    const applySeries = () => {
        const query = normalize(searchInput?.value ?? '');
        let visible = 0;

        seriesItems.forEach((item) => {
            const text = normalize(item.getAttribute('data-search-text') ?? '');
            const show = query === '' || text.includes(query);

            item.hidden = !show;
            if (show) visible += 1;
        });

        if (seriesEmptyEl instanceof HTMLElement) {
            seriesEmptyEl.hidden = visible > 0;
        }
    };

    const apply = () => {
        if (activeView() === 'series') {
            if (emptyEl instanceof HTMLElement) {
                emptyEl.hidden = true;
            }
            applySeries();
            return;
        }

        if (seriesEmptyEl instanceof HTMLElement) {
            seriesEmptyEl.hidden = true;
        }
        applyStories();
    };

    searchInput?.addEventListener('input', apply);

    tagButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (activeView() === 'series') {
                return;
            }

            activeTag = button.getAttribute('data-overview-tag') ?? 'all';
            tagButtons.forEach((other) => {
                other.classList.toggle('tools-filter-chip--active', other === button);
            });
            apply();
        });
    });

    apply();
}

/**
 * @param {ParentNode} root
 */
function initTagSidebar(root) {
    const sidebar = root.querySelector('[data-tag-sidebar]');
    const toggle = root.querySelector('[data-tag-sidebar-toggle]');

    if (!(sidebar instanceof HTMLElement) || !(toggle instanceof HTMLElement)) {
        return;
    }

    const stored = localStorage.getItem(TAG_SIDEBAR_STORAGE_KEY);
    const collapsed = stored === 'collapsed';

    setTagSidebarCollapsed(sidebar, toggle, collapsed);

    toggle.addEventListener('click', () => {
        const nextCollapsed = sidebar.dataset.collapsed !== 'true';
        setTagSidebarCollapsed(sidebar, toggle, nextCollapsed);
        localStorage.setItem(TAG_SIDEBAR_STORAGE_KEY, nextCollapsed ? 'collapsed' : 'open');
    });
}

/**
 * @param {ParentNode} root
 */
function initOverviewViewToggle(root) {
    const toggles = root.querySelectorAll('[data-overview-view-toggle]');
    const storiesPanel = root.querySelector('[data-overview-view-panel="stories"]');
    const seriesPanel = root.querySelector('[data-overview-view-panel="series"]');

    if (toggles.length === 0 || !(storiesPanel instanceof HTMLElement)) {
        return;
    }

    const layout = root.querySelector('.tools-overview-layout');
    const stored = localStorage.getItem(OVERVIEW_VIEW_STORAGE_KEY);
    const initialView = stored === 'series' && seriesPanel instanceof HTMLElement ? 'series' : 'stories';

    /** @param {'stories' | 'series'} view */
    const setView = (view) => {
        const isSeries = view === 'series' && seriesPanel instanceof HTMLElement;

        layout?.classList.toggle('tools-overview-layout--view-series', isSeries);

        storiesPanel.hidden = isSeries;
        if (seriesPanel instanceof HTMLElement) {
            seriesPanel.hidden = !isSeries;
        }

        toggles.forEach((button) => {
            const active = button.getAttribute('data-overview-view-toggle') === view;
            button.classList.toggle('tools-overview-view-toggle__button--active', active);

            if (button instanceof HTMLElement) {
                button.setAttribute('aria-selected', active ? 'true' : 'false');
            }
        });

        localStorage.setItem(OVERVIEW_VIEW_STORAGE_KEY, view);

        const searchInput = root.querySelector('[data-overview-search]');
        if (searchInput instanceof HTMLInputElement) {
            searchInput.dispatchEvent(new Event('input'));
        }
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            const view = button.getAttribute('data-overview-view-toggle');

            if (view === 'series' || view === 'stories') {
                setView(view);
            }
        });
    });

    setView(initialView);
}

/**
 * @param {HTMLElement} sidebar
 * @param {Element | null} toggle
 * @param {boolean} collapsed
 */
function setTagSidebarCollapsed(sidebar, toggle, collapsed) {
    sidebar.dataset.collapsed = collapsed ? 'true' : 'false';

    const layout = sidebar.closest('.tools-overview-layout');
    layout?.classList.toggle('tools-overview-layout--tags-collapsed', collapsed);

    if (toggle instanceof HTMLElement) {
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    }
}

/**
 * @param {ParentNode} root
 */
function initTagSidebarSearch(root) {
    const sidebar = root.querySelector('[data-tag-sidebar]');
    if (!(sidebar instanceof HTMLElement)) {
        return;
    }

    const tagSearchInput = /** @type {HTMLInputElement | null} */ (
        sidebar.querySelector('[data-tag-sidebar-search]')
    );
    const tagButtons = sidebar.querySelectorAll('[data-overview-tag]');
    const emptyEl = sidebar.querySelector('[data-tag-sidebar-empty]');

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    const applyTagSearch = () => {
        const query = normalize(tagSearchInput?.value ?? '');
        let visibleTags = 0;

        tagButtons.forEach((button) => {
            const tag = button.getAttribute('data-overview-tag') ?? '';

            if (tag === 'all') {
                button.hidden = false;
                return;
            }

            const label = normalize(
                button.querySelector('.tools-tag-sidebar__option-label')?.textContent ?? tag,
            );
            const show = query === '' || label.includes(query);

            button.hidden = !show;
            if (show) {
                visibleTags += 1;
            }
        });

        if (emptyEl instanceof HTMLElement) {
            emptyEl.hidden = query === '' || visibleTags > 0;
        }
    };

    tagSearchInput?.addEventListener('input', applyTagSearch);
    applyTagSearch();
}
