import { describe, it, expect } from 'vitest';
import { resolveLocalizedLabel, formatDisplayValue } from './localized-label.js';

describe('localized-label', () => {
    it('resolves de/en label records', () => {
        expect(resolveLocalizedLabel({ de: 'Analyse', en: 'Analysis' }, 'de')).toBe('Analyse');
        expect(resolveLocalizedLabel({ de: 'Analyse', en: 'Analysis' }, 'en')).toBe('Analysis');
    });

    it('does not stringify label objects as [object Object]', () => {
        const label = { de: 'PDF', en: 'PDF' };
        expect(resolveLocalizedLabel(label, 'de')).not.toBe('[object Object]');
    });

    it('formats suggestion objects in template values', () => {
        expect(
            formatDisplayValue([{ value: 'management', label: { de: 'Management', en: 'Management' } }], 'de'),
        ).toBe('management');
    });
});
