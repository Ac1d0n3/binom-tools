import { describe, it, expect } from 'vitest';
import { renderTemplate, buildPrompt, formatForModel } from './prompt-builder.js';

describe('prompt-builder', () => {
    it('renders variables and conditionals', () => {
        const result = renderTemplate('Hello {{name}}{{#if extra}} - {{extra}}{{/if}}', {
            name: 'World',
            extra: '',
        });
        expect(result).toBe('Hello World');
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

    it('formats comma-separated model output', () => {
        const formatted = formatForModel(
            { user: 'hero image, blue tones' },
            {
                id: 'midjourney',
                label: { en: 'Midjourney' },
                sectionOrder: ['user'],
                formatRules: { style: 'comma-separated' },
            },
        );
        expect(formatted).toBe('hero image, blue tones');
    });
});
