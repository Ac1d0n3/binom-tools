/** Client-side search and tag filtering for overview index pages. */
export function initOverviewFilters() {
    const root = document.querySelector('[data-overview-filter-root]');
    if (!root) return;

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
