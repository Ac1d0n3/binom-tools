import { describe, it, expect } from 'vitest';
import {
    normalizeArtistNames,
    postProcessCompiledPrompt,
    stripArtistReferences,
} from './artist-name-stripper.js';

describe('stripArtistReferences', () => {
    it('removes direct artist names', () => {
        const result = stripArtistReferences('Create a pop song like Taylor Swift would write.', ['Taylor Swift']);

        expect(result.text).not.toMatch(/Taylor Swift/i);
        expect(result.removed).toContain('Taylor Swift');
    });

    it('removes style-of phrases', () => {
        const result = stripArtistReferences('Suno style: in the style of Drake, dark mood', ['Drake']);

        expect(result.text).not.toMatch(/Drake/i);
        expect(result.removed).toContain('Drake');
    });

    it('removes German style phrases', () => {
        const result = stripArtistReferences('Klingt ähnlich wie Helene Fischer im Chorus.', ['Helene Fischer']);

        expect(result.text).not.toMatch(/Helene Fischer/i);
        expect(result.removed).toContain('Helene Fischer');
    });

    it('removes Suno style bracket tags', () => {
        const result = stripArtistReferences('Use [Style: Billie Eilish] for the verse.', ['Billie Eilish']);

        expect(result.text).not.toMatch(/Billie Eilish/i);
        expect(result.removed).toContain('Billie Eilish');
    });

    it('prefers longer names first', () => {
        const result = stripArtistReferences('Inspired by The Weeknd tonight.', ['The Weeknd', 'Weeknd']);

        expect(result.text).not.toMatch(/Weeknd/i);
        expect(result.removed).toContain('The Weeknd');
    });
});

describe('postProcessCompiledPrompt', () => {
    it('merges blocklist and user chips', () => {
        const result = postProcessCompiledPrompt('Feat. Custom Artist One and Taylor Swift.', {
            blocklist: ['Taylor Swift'],
            parameterValues: { forbiddenArtistNames: ['Custom Artist One'] },
        });

        expect(result.text).not.toMatch(/Taylor Swift/i);
        expect(result.text).not.toMatch(/Custom Artist One/i);
        expect(result.removed).toEqual(expect.arrayContaining(['Taylor Swift', 'Custom Artist One']));
    });

    it('can be disabled via parameter', () => {
        const result = postProcessCompiledPrompt('Inspired by Taylor Swift.', {
            blocklist: ['Taylor Swift'],
            parameterValues: { stripArtistNames: 'no' },
        });

        expect(result.text).toMatch(/Taylor Swift/i);
        expect(result.removed).toEqual([]);
    });
});

describe('normalizeArtistNames', () => {
    it('deduplicates and sorts by length', () => {
        expect(normalizeArtistNames(['Drake', 'Taylor Swift', 'Drake'])).toEqual(['Taylor Swift', 'Drake']);
    });
});
