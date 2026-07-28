import { describe, expect, it } from 'vitest';

import { buildSeriesCardMetaLines, buildSeriesCardMetaText, formatReadingTime } from './reading-time.js';

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

describe('buildSeriesCardMetaLines', () => {
    it('keeps totals on the primary line without progress', () => {
        expect(buildSeriesCardMetaLines({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 0,
            locale: 'en',
        })).toEqual({
            primary: '9 parts · 2 h 22 min total',
            progress: '',
        });

        expect(buildSeriesCardMetaLines({
            partCount: 9,
            totalMinutes: 142,
            locale: 'de',
        })).toEqual({
            primary: '9 Teile · 2 Std 22 min gesamt',
            progress: '',
        });
    });

    it('puts read progress on a second line', () => {
        expect(buildSeriesCardMetaLines({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 22,
            readPartCount: 2,
            locale: 'en',
        })).toEqual({
            primary: '9 parts · 2 h 22 min total',
            progress: '2/9 · 22 min read',
        });

        expect(buildSeriesCardMetaLines({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 22,
            readPartCount: 2,
            locale: 'de',
        })).toEqual({
            primary: '9 Teile · 2 Std 22 min gesamt',
            progress: '2/9 · 22 min gelesen',
        });
    });
});

describe('buildSeriesCardMetaText', () => {
    it('joins primary and progress with a newline', () => {
        expect(buildSeriesCardMetaText({
            partCount: 9,
            totalMinutes: 142,
            readMinutes: 22,
            readPartCount: 2,
            locale: 'en',
        })).toBe('9 parts · 2 h 22 min total\n2/9 · 22 min read');
    });
});
