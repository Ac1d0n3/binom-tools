import { describe, it, expect } from 'vitest';
import { PROMPT_CATEGORIES, normalizeCategory, categoryLabel } from './categories.js';
import { t } from './labels.js';

describe('categories', () => {
    it('lists expected category ids', () => {
        expect(PROMPT_CATEGORIES).toEqual([
            'image',
            'video',
            'music',
            'code',
            'business',
            'writing',
            'mail',
            'personal',
            'agent',
        ]);
    });

    it('normalizes known categories and rejects unknown', () => {
        expect(normalizeCategory('image')).toBe('image');
        expect(normalizeCategory(' IMAGE ')).toBe('');
        expect(normalizeCategory('unknown')).toBe('');
        expect(normalizeCategory(null)).toBe('');
    });

    it('resolves localized labels', () => {
        expect(categoryLabel('de', 'image', t)).toBe('Bilder');
        expect(categoryLabel('en', 'music', t)).toBe('Music');
        expect(categoryLabel('de', 'mail', t)).toBe('E-Mails');
        expect(categoryLabel('en', 'personal', t)).toBe('Personal');
        expect(categoryLabel('de', 'video', t)).toBe('Videos');
        expect(categoryLabel('en', '', t)).toBe('');
    });
});
