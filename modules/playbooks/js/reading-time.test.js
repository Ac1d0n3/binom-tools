import { describe, expect, it } from 'vitest';

import { buildSeriesCardMetaText, formatReadingTime } from './reading-time.js';

describe('formatReadingTime', () => {
    it('formats minutes below one hour', () => {
        expect(formatReadingTime(0, 'en')).toBe('0 min');
        expect(formatReadingTime(12, 'de')).toBe('12 min');
        expect(formatReadingTime(59, 'en')).toBe('59 min');
    });

    it('formats whole hours', () => {
        expect(formatReadingTime(60, 'en')).toBe('1 h');
        expect(formatReadingTime(60, 'de')).toBe('1 Std');
        expect(formatReadingTime(120, 'en')).toBe('2 h');
    });

    it('formats hours and minutes', () => {
        expect(formatReadingTime(65, 'en')).toBe('1 h 5 min');
        expect(formatReadingTime(65, 'de')).toBe('1 Std 5 min');
        expect(formatReadingTime(142, 'en')).toBe('2 h 22 min');
        expect(formatReadingTime(142, 'de')).toBe('2 Std 22 min');
    });
});

describe('buildSeriesCardMetaText', () => {
    it('shows total without progress when nothing is read', () => {
        expect(buildSeriesCardMetaText({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 0,
            locale: 'en',
        })).toBe('9 parts · 2 h 22 min total');

        expect(buildSeriesCardMetaText({
            partCount: 9,
            totalMinutes: 142,
            locale: 'de',
        })).toBe('9 Teile · 2 Std 22 min gesamt');
    });

    it('shows read progress when parts are read', () => {
        expect(buildSeriesCardMetaText({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 22,
            locale: 'en',
        })).toBe('9 parts · 22 min of 2 h 22 min');

        expect(buildSeriesCardMetaText({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 22,
            locale: 'de',
        })).toBe('9 Teile · 22 min von 2 Std 22 min');
    });
});
