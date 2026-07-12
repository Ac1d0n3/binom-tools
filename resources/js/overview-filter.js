/** Client-side search and tag filtering for overview index pages. */
import { getLocale } from './locale';

const TAG_SIDEBAR_STORAGE_KEY = 'binom-tools-tag-sidebar';
const OVERVIEW_VIEW_STORAGE_KEY = 'binom-tools-overview-view';
const OVERVIEW_SORT_STORAGE_KEY = 'binom-tools-overview-sort';
const OVERVIEW_LAYOUT_STORAGE_KEY = 'binom-tools-overview-layout';

/** @typedef {'date-desc' | 'date-asc' | 'name-asc' | 'name-desc'} OverviewSortKey */
/** @typedef {'grid' | 'list'} OverviewLayoutMode */

export function initOverviewFilters() {
    const root = document.querySelector('[data-overview-filter-root]');
    if (!root) return;

    initTagSidebar(root);
    initTagSidebarSearch(root);
    initOverviewViewToggle(root);
    initOverviewLayoutToggle(root);
    initOverviewSort(root);

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

    /** @type {OverviewSortKey} */
    let activeSort = readOverviewSort(root);

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    /** @returns {ToolsLocale} */
    const locale = () => getLocale();

    /**
     * @param {Element} grid
     * @param {string} itemSelector
     * @param {(a: Element, b: Element) => number} compare
     */
    const reorderGrid = (grid, itemSelector, compare) => {
        const items = Array.from(grid.querySelectorAll(itemSelector));
        const visible = items.filter((item) => !(item instanceof HTMLElement && item.hidden));
        const hidden = items.filter((item) => item instanceof HTMLElement && item.hidden);

        visible.sort(compare);
        [...visible, ...hidden].forEach((item) => grid.appendChild(item));
    };

    /** @param {number} timestampSeconds */
    const daySortKey = (timestampSeconds) => {
        const date = new Date(timestampSeconds * 1000);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}${month}${day}`;
    };

    /** @param {Element} a @param {Element} b */
    const compareStoryItems = (a, b) => {
        const titleKey = locale() === 'de' ? 'data-sort-title-de' : 'data-sort-title-en';
        const dateA = Number(a.getAttribute('data-sort-date') ?? 0);
        const dateB = Number(b.getAttribute('data-sort-date') ?? 0);
        const dayA = daySortKey(dateA);
        const dayB = daySortKey(dateB);
        const seriesA = a.getAttribute('data-sort-series-id') ?? '';
        const seriesB = b.getAttribute('data-sort-series-id') ?? '';
        const partA = Number(a.getAttribute('data-sort-series-part') ?? 0);
        const partB = Number(b.getAttribute('data-sort-series-part') ?? 0);
        const titleA = normalize(a.getAttribute(titleKey) ?? '');
        const titleB = normalize(b.getAttribute(titleKey) ?? '');

        if (activeSort.startsWith('date-')) {
            const cmp = dayA.localeCompare(dayB);

            if (cmp !== 0) {
                return activeSort === 'date-desc' ? -cmp : cmp;
            }

            if (seriesA !== seriesB) {
                if (seriesA === '') {
                    return 1;
                }

                if (seriesB === '') {
                    return -1;
                }

                return seriesA.localeCompare(seriesB);
            }

            if (partA !== partB) {
                return activeSort === 'date-desc' ? partB - partA : partA - partB;
            }

            return titleA.localeCompare(titleB, locale());
        }

        const nameCmp = titleA.localeCompare(titleB, locale());

        if (nameCmp !== 0) {
            return activeSort === 'name-desc' ? -nameCmp : nameCmp;
        }

        if (partA !== partB) {
            return partA - partB;
        }

        return dayB.localeCompare(dayA);
    };

    /** @param {Element} a @param {Element} b */
    const compareSeriesItems = (a, b) => {
        const titleKey = locale() === 'de' ? 'data-sort-title-de' : 'data-sort-title-en';
        const dateA = Number(a.getAttribute('data-sort-date') ?? 0);
        const dateB = Number(b.getAttribute('data-sort-date') ?? 0);
        const countA = Number(a.getAttribute('data-sort-part-count') ?? 0);
        const countB = Number(b.getAttribute('data-sort-part-count') ?? 0);
        const titleA = normalize(a.getAttribute(titleKey) ?? '');
        const titleB = normalize(b.getAttribute(titleKey) ?? '');

        if (activeSort.startsWith('date-')) {
            const cmp = dateA - dateB;

            if (cmp !== 0) {
                return activeSort === 'date-desc' ? -cmp : cmp;
            }

            if (countA !== countB) {
                return countB - countA;
            }

            return titleA.localeCompare(titleB, locale());
        }

        const nameCmp = titleA.localeCompare(titleB, locale());

        if (nameCmp !== 0) {
            return activeSort === 'name-desc' ? -nameCmp : nameCmp;
        }

        if (countA !== countB) {
            return countB - countA;
        }

        return dateB - dateA;
    };

    const sortStories = () => {
        const grid = root.querySelector('[data-overview-stories-grid]');

        if (grid instanceof HTMLElement) {
            reorderGrid(grid, '[data-overview-item]', compareStoryItems);
        }
    };

    const sortSeries = () => {
        const grid = root.querySelector('#playbook-overview-series .tools-card-grid');

        if (grid instanceof HTMLElement) {
            reorderGrid(grid, '[data-overview-series-item]', compareSeriesItems);
        }
    };

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

        sortStories();
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

        sortSeries();
    };

    const apply = () => {
        const sortSelect = root.querySelector('[data-overview-sort]');

        if (sortSelect instanceof HTMLSelectElement) {
            const value = sortSelect.value;

            if (
                value === 'date-desc'
                || value === 'date-asc'
                || value === 'name-asc'
                || value === 'name-desc'
            ) {
                activeSort = value;
            }
        }

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
 * @returns {OverviewSortKey}
 */
function readOverviewSort(root) {
    const select = root.querySelector('[data-overview-sort]');
    const stored = localStorage.getItem(OVERVIEW_SORT_STORAGE_KEY);

    /** @type {OverviewSortKey} */
    const fallback = stored === 'date-asc'
        || stored === 'name-asc'
        || stored === 'name-desc'
        ? stored
        : 'date-desc';

    if (select instanceof HTMLSelectElement) {
        select.value = fallback;
    }

    return fallback;
}

/**
 * @param {ParentNode} root
 */
function initOverviewLayoutToggle(root) {
    const toggles = root.querySelectorAll('[data-overview-layout-toggle]');
    const storiesGrid = root.querySelector('[data-overview-stories-grid]');

    if (toggles.length === 0 || !(storiesGrid instanceof HTMLElement)) {
        return;
    }

    const stored = localStorage.getItem(OVERVIEW_LAYOUT_STORAGE_KEY);
    const initialLayout = stored === 'list' ? 'list' : 'grid';

    /** @param {OverviewLayoutMode} layout */
    const setLayout = (layout) => {
        const isList = layout === 'list';

        storiesGrid.classList.toggle('tools-card-grid--list', isList);

        toggles.forEach((button) => {
            const active = button.getAttribute('data-overview-layout-toggle') === layout;
            button.classList.toggle('tools-overview-layout-toggle__button--active', active);

            if (button instanceof HTMLElement) {
                button.setAttribute('aria-pressed', active ? 'true' : 'false');
            }
        });

        localStorage.setItem(OVERVIEW_LAYOUT_STORAGE_KEY, layout);
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            const layout = button.getAttribute('data-overview-layout-toggle');

            if (layout === 'grid' || layout === 'list') {
                setLayout(layout);
            }
        });
    });

    setLayout(initialLayout);
}

/**
 * @param {ParentNode} root
 */
function initOverviewSort(root) {
    const select = /** @type {HTMLSelectElement | null} */ (
        root.querySelector('[data-overview-sort]')
    );

    if (!select) {
        return;
    }

    select.addEventListener('change', () => {
        const value = select.value;

        if (
            value === 'date-desc'
            || value === 'date-asc'
            || value === 'name-asc'
            || value === 'name-desc'
        ) {
            localStorage.setItem(OVERVIEW_SORT_STORAGE_KEY, value);
        }

        const searchInput = root.querySelector('[data-overview-search]');

        if (searchInput instanceof HTMLInputElement) {
            searchInput.dispatchEvent(new Event('input'));
        }
    });
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
