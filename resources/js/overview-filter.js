/** Client-side search and tag filtering for overview index pages. */
const TAG_SIDEBAR_STORAGE_KEY = 'binom-tools-tag-sidebar';

export function initOverviewFilters() {
    const root = document.querySelector('[data-overview-filter-root]');
    if (!root) return;

    initTagSidebar(root);
    initTagSidebarSearch(root);

    const searchInput = /** @type {HTMLInputElement | null} */ (
        root.querySelector('[data-overview-search]')
    );
    const tagButtons = root.querySelectorAll('[data-overview-tag]');
    const items = root.querySelectorAll('[data-overview-item]');
    const emptyEl = root.querySelector('[data-overview-empty]');

    /** @type {string} */
    let activeTag = 'all';

    /** @param {string} value */
    const normalize = (value) => value.toLowerCase().trim();

    const apply = () => {
        const query = normalize(searchInput?.value ?? '');
        let visible = 0;

        items.forEach((item) => {
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

    searchInput?.addEventListener('input', apply);

    tagButtons.forEach((button) => {
        button.addEventListener('click', () => {
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
