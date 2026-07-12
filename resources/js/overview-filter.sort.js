/** @typedef {'date-desc' | 'date-asc' | 'name-asc' | 'name-desc'} OverviewSortKey */

/** @param {string} value */
const normalize = (value) => value.toLowerCase().trim();

/** @param {number} timestampSeconds */
const daySortKey = (timestampSeconds) => {
    const date = new Date(timestampSeconds * 1000);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}${month}${day}`;
};

/**
 * @param {Element} a
 * @param {Element} b
 * @param {OverviewSortKey} activeSort
 * @param {'de' | 'en'} locale
 */
export function compareStoryItemsForSort(a, b, activeSort, locale) {
    const titleKey = locale === 'de' ? 'data-sort-title-de' : 'data-sort-title-en';
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

        const timeCmp = dateA - dateB;

        if (timeCmp !== 0) {
            return activeSort === 'date-desc' ? -timeCmp : timeCmp;
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

        return titleA.localeCompare(titleB, locale);
    }

    const nameCmp = titleA.localeCompare(titleB, locale);

    if (nameCmp !== 0) {
        return activeSort === 'name-desc' ? -nameCmp : nameCmp;
    }

    if (partA !== partB) {
        return partA - partB;
    }

    return dayB.localeCompare(dayA);
}
