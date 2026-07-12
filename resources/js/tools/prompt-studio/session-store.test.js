import { describe, it, expect, beforeEach } from 'vitest';
import { loadForbiddenSongWords, SESSION_STORAGE_KEY } from './session-store.js';

describe('session-store', () => {
    beforeEach(() => {
        localStorage.clear();
    });

    it('loadForbiddenSongWords returns [] when field is missing in legacy session', () => {
        localStorage.setItem(
            SESSION_STORAGE_KEY,
            JSON.stringify({
                draft: { roleId: 'songwriter', taskId: 'album', modelId: 'chatgpt' },
                variants: { enabled: false },
            }),
        );

        expect(loadForbiddenSongWords()).toEqual([]);
    });

    it('loadForbiddenSongWords returns stored words when present', () => {
        localStorage.setItem(
            SESSION_STORAGE_KEY,
            JSON.stringify({
                draft: {},
                forbiddenSongWords: ['foo', 'bar'],
            }),
        );

        expect(loadForbiddenSongWords()).toEqual(['foo', 'bar']);
    });
});
