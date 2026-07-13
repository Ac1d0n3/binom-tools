import { describe, expect, it } from 'vitest';

import { compareStoryItemsForSort } from './overview-filter.sort.js';

describe('overview story sort', () => {
    const item = (attrs) => {
        const element = document.createElement('a');

        Object.entries(attrs).forEach(([key, value]) => {
            element.setAttribute(key, String(value));
        });

        return element;
    };

    it('sorts standalone stories by timestamp within the same calendar day', () => {
        const bigFive = item({
            'data-sort-date': '1783878469',
            'data-sort-title-de': 'BIG 5',
            'data-sort-title-en': 'BIG 5',
            'data-sort-series-id': '',
            'data-sort-series-part': '0',
        });
        const seriesPart = item({
            'data-sort-date': '1783874202',
            'data-sort-title-de': 'Lifecycle',
            'data-sort-title-en': 'Lifecycle',
            'data-sort-series-id': 'governance-pillars',
            'data-sort-series-part': '9',
        });

        expect(compareStoryItemsForSort(bigFive, seriesPart, 'date-desc', 'en')).toBeLessThan(0);
    });

    it('groups series parts when timestamps match on the same day', () => {
        const partNine = item({
            'data-sort-date': '1783874202',
            'data-sort-title-de': 'Lifecycle',
            'data-sort-title-en': 'Lifecycle',
            'data-sort-series-id': 'governance-pillars',
            'data-sort-series-part': '9',
        });
        const partEight = item({
            'data-sort-date': '1783874202',
            'data-sort-title-de': 'Access',
            'data-sort-title-en': 'Access',
            'data-sort-series-id': 'governance-pillars',
            'data-sort-series-part': '8',
        });

        expect(compareStoryItemsForSort(partNine, partEight, 'date-desc', 'en')).toBeLessThan(0);
    });

    it('sorts series parts by embedded index timestamp when base times match', () => {
        const base = 1783940908;
        const partSix = item({
            'data-sort-date': String(base + 6),
            'data-sort-title-de': 'Part 6',
            'data-sort-title-en': 'Part 6',
            'data-sort-series-id': 'missing-pieces',
            'data-sort-series-part': '6',
        });
        const partThree = item({
            'data-sort-date': String(base + 3),
            'data-sort-title-de': 'Part 3',
            'data-sort-title-en': 'Part 3',
            'data-sort-series-id': 'missing-pieces',
            'data-sort-series-part': '3',
        });
        const partFive = item({
            'data-sort-date': String(base + 5),
            'data-sort-title-de': 'Part 5',
            'data-sort-title-en': 'Part 5',
            'data-sort-series-id': 'missing-pieces',
            'data-sort-series-part': '5',
        });

        expect(compareStoryItemsForSort(partSix, partFive, 'date-desc', 'en')).toBeLessThan(0);
        expect(compareStoryItemsForSort(partFive, partThree, 'date-desc', 'en')).toBeLessThan(0);
    });
});
