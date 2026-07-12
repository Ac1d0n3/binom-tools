import { afterEach, describe, expect, it } from 'vitest';

import {
    __resetPlaybookReadStoreForTests,
    clearAllPlaybookRead,
    getReadPlaybookSlugs,
    hasAnyPlaybookRead,
    isPlaybookRead,
    markPlaybookRead,
} from './read-state.js';

afterEach(() => {
    __resetPlaybookReadStoreForTests();
});

describe('playbook read state', () => {
    it('marks a slug as read once and dispatches an event', () => {
        let dispatchedSlug = null;

        window.addEventListener('binom-tools:playbook-read', (event) => {
            dispatchedSlug = /** @type {CustomEvent} */ (event).detail.slug;
        });

        expect(markPlaybookRead('eight-pillars')).toBe(true);
        expect(isPlaybookRead('eight-pillars')).toBe(true);
        expect(markPlaybookRead('eight-pillars')).toBe(false);
        expect(dispatchedSlug).toBe('eight-pillars');
        expect(getReadPlaybookSlugs()).toEqual(['eight-pillars']);
    });

    it('clears all read markers and dispatches reset', () => {
        let reset = false;

        window.addEventListener('binom-tools:playbook-read-reset', () => {
            reset = true;
        });

        markPlaybookRead('story-a');
        markPlaybookRead('story-b');

        expect(hasAnyPlaybookRead()).toBe(true);
        expect(clearAllPlaybookRead()).toBe(true);
        expect(hasAnyPlaybookRead()).toBe(false);
        expect(isPlaybookRead('story-a')).toBe(false);
        expect(reset).toBe(true);
        expect(clearAllPlaybookRead()).toBe(false);
    });
});
