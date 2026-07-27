import { describe, it, expect } from 'vitest';
import { renderTemplate, buildPrompt, formatForModel } from './prompt-builder.js';

describe('prompt-builder', () => {
    it('renders equality conditionals', () => {
        expect(
            renderTemplate('A{{#if imageFormat==svg}} SVG{{/if}}{{#if imageFormat==png}} PNG{{/if}}', {
                imageFormat: 'svg',
            }),
        ).toBe('A SVG');
        expect(
            renderTemplate('{{#if imageFormat==svg}}yes{{/if}}', {
                imageFormat: 'png',
            }),
        ).toBe('');
    });

    it('compiles template sections for model output', () => {
        const built = buildPrompt({
            template: {
                id: 'test',
                sections: [
                    { id: 'system', template: 'You are a {{roleLabel}}' },
                    { id: 'user', template: 'Task: {{goal}}' },
                ],
            },
            parameterValues: { goal: 'Analyze sales' },
            model: {
                id: 'claude',
                label: { en: 'Claude' },
                sectionOrder: ['system', 'user'],
            },
            extraContext: { roleLabel: 'Analyst' },
        });

        expect(built.sections.system).toContain('Analyst');
        expect(built.compiled).toContain('Analyze sales');
    });

    it('compiles bilingual section templates by locale', () => {
        const builtDe = buildPrompt({
            template: {
                id: 'test',
                sections: [
                    {
                        id: 'system',
                        template: {
                            en: 'You are an experienced {{roleLabel}}.',
                            de: 'Du bist ein erfahrener {{roleLabel}}.',
                        },
                    },
                    {
                        id: 'task',
                        template: {
                            en: 'Write lyrics for {{taskLabel}}.',
                            de: 'Schreibe Lyrics für {{taskLabel}}.',
                        },
                    },
                ],
            },
            parameterValues: {},
            model: {
                id: 'chatgpt',
                label: { en: 'ChatGPT' },
                sectionOrder: ['system', 'task'],
            },
            extraContext: { roleLabel: 'Songwriter', taskLabel: 'Songtext' },
            locale: 'de',
        });

        expect(builtDe.compiled).toContain('Du bist ein erfahrener Songwriter.');
        expect(builtDe.compiled).toContain('Schreibe Lyrics für Songtext.');
        expect(builtDe.compiled).not.toContain('You are an experienced');
    });

    it('applies ChatGPT section headers via formatRules', () => {
        const text = formatForModel(
            { system: 'You are helpful.', task: 'Write a brief.' },
            {
                id: 'chatgpt',
                label: { en: 'ChatGPT', de: 'ChatGPT' },
                sectionOrder: ['system', 'task'],
                formatRules: {
                    joinSections: '\n\n',
                    includeSectionHeaders: true,
                    headerFormat: '## {section}',
                },
            },
        );
        expect(text).toContain('## system\nYou are helpful.');
        expect(text).toContain('## task\nWrite a brief.');
    });

    it('omits system/output and appends Midjourney suffixes', () => {
        const text = formatForModel(
            {
                system: 'Ignore me',
                task: 'neon skyline',
                parameters: 'cinematic lighting',
                output: 'single prompt',
            },
            {
                id: 'midjourney',
                label: { en: 'Midjourney', de: 'Midjourney' },
                sectionOrder: ['system', 'task', 'parameters', 'output'],
                formatRules: {
                    joinSections: ', ',
                    omitSystemSection: true,
                    omitOutputSection: true,
                    suffixParams: ['--ar', '--v'],
                    negativePromptPrefix: '--no',
                },
            },
            {
                parameterValues: {
                    aspectRatio: '16:9',
                    modelVersion: '6',
                    negativePrompt: 'blur, watermark',
                },
            },
        );
        expect(text).toBe('neon skyline, cinematic lighting --ar 16:9 --v 6\n--no blur, watermark');
        expect(text).not.toContain('Ignore me');
        expect(text).not.toContain('single prompt');
    });

    it('compacts Suno-style tags and enforces maxLength', () => {
        const text = formatForModel(
            {
                task: 'Create style',
                parameters: 'Genre: indie\nMood: warm',
            },
            {
                id: 'suno',
                label: { en: 'Suno', de: 'Suno' },
                sectionOrder: ['task', 'parameters'],
                formatRules: {
                    joinSections: ', ',
                    compactTags: true,
                    maxLength: 40,
                },
            },
        );
        expect(text).toContain('Create style, Genre: indie, Mood: warm');
        expect(text.length).toBeLessThanOrEqual(40);
    });

    it('uses modelLabel in compiled templates', () => {
        const built = buildPrompt({
            template: {
                id: 'hero',
                sections: [{ id: 'output', template: 'Ready for {{modelLabel}}' }],
            },
            parameterValues: {},
            model: {
                id: 'flux',
                label: { en: 'Flux', de: 'Flux' },
                sectionOrder: ['output'],
            },
            extraContext: { modelLabel: 'Flux' },
        });
        expect(built.compiled).toBe('Ready for Flux');
    });
});
