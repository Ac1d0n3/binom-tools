import { describe, it, expect } from 'vitest';
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';
import { buildPrompt, filterPromptParameterValues, renderTemplate } from './prompt-builder.js';
import { estimateSyllables, estimateLineSyllables, analyzeLyrics } from './lyrics-meter.js';
import { getPresetTextForGenre, resolveFamilyIdForGenre, normalizeMusicStructures } from './music-structures.js';
import { validateParameters } from './config-validator.js';

const root = join(dirname(fileURLToPath(import.meta.url)), '../../../../');
const musicStructuresJson = JSON.parse(
    readFileSync(join(root, 'public/prompt-studio/config/music-structures.json'), 'utf8'),
);

describe('filterPromptParameterValues', () => {
    it('excludes includeInPrompt:false fields', () => {
        const filtered = filterPromptParameterValues(
            {
                genre: 'hip-hop',
                lyricsDraft: 'one two three four\nfive six',
                story: 'night drive',
            },
            [
                { id: 'genre' },
                { id: 'lyricsDraft', includeInPrompt: false },
                { id: 'story' },
            ],
        );

        expect(filtered).toEqual({ genre: 'hip-hop', story: 'night drive' });
        expect(filtered.lyricsDraft).toBeUndefined();
    });

    it('keeps all values when defs are missing', () => {
        expect(filterPromptParameterValues({ a: 1, lyricsDraft: 'x' })).toEqual({ a: 1, lyricsDraft: 'x' });
    });
});

describe('music prompt templates', () => {
    it('renders genre hybrids and suno tags without lyrics meter text', () => {
        const built = buildPrompt({
            template: {
                id: 'suno-style',
                sections: [
                    {
                        id: 'parameters',
                        template:
                            'Genre: {{genre}}{{#if genreSecondary}} + {{genreSecondary}} (multi-genre hybrid){{/if}}\nVocal timbre: {{vocalTimbre}}\nEffects: {{vocalEffects}}, {{productionEffects}}\n{{#if lyricsDraft}}LEAK:{{lyricsDraft}}{{/if}}',
                    },
                ],
            },
            parameterValues: {
                genre: 'Cinematic',
                genreSecondary: ['Epic', 'Orchestral', 'Ambient'],
                vocalTimbre: 'warm, smoky',
                vocalEffects: 'reverb',
                productionEffects: 'vinyl crackle',
                lyricsDraft: 'THIS MUST NOT APPEAR',
            },
            parameterDefs: [
                { id: 'genre' },
                { id: 'genreSecondary' },
                { id: 'vocalTimbre' },
                { id: 'vocalEffects' },
                { id: 'productionEffects' },
                { id: 'lyricsDraft', includeInPrompt: false },
            ],
            model: { id: 'suno', label: { en: 'Suno', de: 'Suno' }, sectionOrder: ['parameters'] },
        });

        expect(built.compiled).toContain('Genre: Cinematic + Epic, Orchestral, Ambient (multi-genre hybrid)');
        expect(built.compiled).toContain('Vocal timbre: warm, smoky');
        expect(built.compiled).toContain('vinyl crackle');
        expect(built.compiled).not.toContain('THIS MUST NOT APPEAR');
        expect(built.compiled).not.toContain('LEAK');
    });

    it('includes song structure lines in prompt', () => {
        const text = renderTemplate(
            '{{#if songStructure}}Structure:\n{{songStructure}}{{/if}}',
            { songStructure: 'Intro (4 bars)\nVerse 1 (16 bars)' },
        );
        expect(text).toContain('Intro (4 bars)');
        expect(text).toContain('Verse 1 (16 bars)');
    });
});

describe('music-structures', () => {
    const config = normalizeMusicStructures(musicStructuresJson);

    it('maps boom-bap to hip-hop family distinct from soul', () => {
        expect(resolveFamilyIdForGenre('boom-bap', config)).toBe('hip-hop');
        expect(resolveFamilyIdForGenre('soul', config)).toBe('soul');
        expect(resolveFamilyIdForGenre('epic', config)).toBe('cinematic');
        expect(resolveFamilyIdForGenre('cinematic', config)).toBe('cinematic');
        const hipHop = getPresetTextForGenre('boom-bap', config, 'en');
        const soul = getPresetTextForGenre('soul', config, 'en');
        expect(hipHop).toContain('Hook');
        expect(soul).toContain('Pre-Chorus');
        expect(hipHop).not.toEqual(soul);
    });
});

describe('lyrics-meter', () => {
    it('estimates english syllables', () => {
        expect(estimateSyllables('beautiful', 'en')).toBeGreaterThanOrEqual(3);
        expect(estimateLineSyllables('one two three four', 'en')).toBe(4);
    });

    it('estimates german syllables', () => {
        expect(estimateSyllables('Musik', 'de')).toBeGreaterThanOrEqual(2);
        expect(estimateLineSyllables('Hallo Welt', 'de')).toBeGreaterThanOrEqual(3);
    });

    it('summarizes bars without requiring prompt export', () => {
        const summary = analyzeLyrics('cat dog bird fish\nred blue green gold', {
            syllablesPerBar: 4,
            locale: 'en',
        });
        expect(summary.totalSyllables).toBe(8);
        expect(summary.lines).toHaveLength(2);
        expect(summary.totalBars).toBeGreaterThan(0);
    });
});

describe('config-validator music params', () => {
    it('accepts structure-editor, lyrics-meter and includeInPrompt', () => {
        const result = validateParameters([
            {
                id: 'songStructure',
                type: 'structure-editor',
                label: { de: 'Struktur', en: 'Structure' },
            },
            {
                id: 'lyricsDraft',
                type: 'lyrics-meter',
                includeInPrompt: false,
                label: { de: 'Draft', en: 'Draft' },
            },
        ]);
        expect(result.valid).toBe(true);
        expect(result.issues).toEqual([]);
    });
});
