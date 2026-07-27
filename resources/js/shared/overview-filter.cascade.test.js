import { describe, expect, it } from 'vitest';

import { syncSelectOptionAvailability } from './overview-filter.cascade.js';

describe('syncSelectOptionAvailability', () => {
    const makeSelect = (values, selected = 'all') => {
        const select = document.createElement('select');

        values.forEach((value) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            select.appendChild(option);
        });

        select.value = selected;
        return select;
    };

    it('removes unavailable options and keeps all visible', () => {
        const select = makeSelect(['all', 'microsoft', 'sap', 'moodle'], 'all');

        const reset = syncSelectOptionAvailability(select, (value) =>
            ['microsoft', 'sap'].includes(value),
        );

        expect(reset).toBe(false);
        expect(select.value).toBe('all');
        expect(Array.from(select.options).map((option) => option.value)).toEqual([
            'all',
            'microsoft',
            'sap',
        ]);
    });

    it('resets the current value when it becomes unavailable', () => {
        const select = makeSelect(['all', 'microsoft', 'moodle'], 'moodle');

        const reset = syncSelectOptionAvailability(select, (value) => value === 'microsoft');

        expect(reset).toBe(true);
        expect(select.value).toBe('all');
        expect(Array.from(select.options).map((option) => option.value)).toEqual([
            'all',
            'microsoft',
        ]);
    });

    it('restores previously removed options when they become available again', () => {
        const select = makeSelect(['all', 'microsoft', 'moodle'], 'all');

        syncSelectOptionAvailability(select, (value) => value === 'microsoft');
        syncSelectOptionAvailability(select, () => true);

        expect(Array.from(select.options).map((option) => option.value)).toEqual([
            'all',
            'microsoft',
            'moodle',
        ]);
    });
});
