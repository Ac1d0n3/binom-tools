import { describe, it, expect } from 'vitest';
import { resolveLocalizedLabel, formatDisplayValue, localizeParameterValues } from './localized-label.js';

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

    it('localizes select/chips values for prompt output', () => {
        const defs = [
            {
                id: 'voice',
                type: 'select',
                options: [
                    { value: 'male', label: { de: 'Männlich', en: 'Male' } },
                    { value: 'female', label: { de: 'Weiblich', en: 'Female' } },
                ],
            },
            {
                id: 'mood',
                type: 'chips',
                suggestions: [{ value: 'energetic', label: { de: 'Energisch', en: 'Energetic' } }],
            },
            { id: 'story', type: 'textarea' },
        ];

        const localized = localizeParameterValues(
            { voice: 'male', mood: ['energetic'], story: 'Freiheit' },
            defs,
            'de',
        );

        expect(localized.voice).toBe('Männlich');
        expect(localized.mood).toEqual(['Energisch']);
        expect(localized.story).toBe('Freiheit');
    });
});
