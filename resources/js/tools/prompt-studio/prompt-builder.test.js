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
});
