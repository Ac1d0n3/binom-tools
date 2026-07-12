import { describe, it, expect } from 'vitest';
import { renderParameterField } from './field-renderer.js';

describe('field-renderer', () => {
    it('renders select option labels without [object Object]', () => {
        const html = renderParameterField(
            {
                id: 'genre',
                type: 'select',
                label: { de: 'Genre', en: 'Genre' },
                options: [
                    { value: 'pop', label: { de: 'Pop', en: 'Pop' } },
                    { value: 'rock', label: { de: 'Rock', en: 'Rock' } },
                ],
                default: 'pop',
            },
            'pop',
            'de',
        );

        expect(html).not.toContain('[object Object]');
        expect(html).toContain('>Pop</option>');
    });

    it('renders multiselect checkbox labels without [object Object]', () => {
        const html = renderParameterField(
            {
                id: 'instruments',
                type: 'multiselect',
                label: { de: 'Instrumente', en: 'Instruments' },
                options: [
                    { value: 'guitar', label: { de: 'Gitarre', en: 'Guitar' } },
                    { value: 'drums', label: { de: 'Schlagzeug', en: 'Drums' } },
                ],
            },
            ['guitar'],
            'de',
        );

        expect(html).not.toContain('[object Object]');
        expect(html).toContain('Gitarre');
        expect(html).toContain('Schlagzeug');
    });
});
