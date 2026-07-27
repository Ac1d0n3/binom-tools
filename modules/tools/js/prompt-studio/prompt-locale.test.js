import { describe, it, expect, beforeEach } from 'vitest';
import {
    PROMPT_LOCALE_KEY,
    normalizePromptLocale,
    getPromptLocale,
    setPromptLocale,
} from './prompt-locale.js';

describe('prompt-locale', () => {
    beforeEach(() => {
        localStorage.removeItem(PROMPT_LOCALE_KEY);
    });

    it('normalizes and persists independently of page locale', () => {
        expect(normalizePromptLocale('de')).toBe('de');
        expect(normalizePromptLocale('nope')).toBe('en');
        expect(getPromptLocale('de')).toBe('de');
        setPromptLocale('en');
        expect(getPromptLocale('de')).toBe('en');
        setPromptLocale('de');
        expect(getPromptLocale()).toBe('de');
    });
});
