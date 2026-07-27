/** @vitest-environment jsdom */

import { beforeEach, describe, expect, it, vi } from 'vitest';
import { initToolsPhoneGate } from './tools-phone-gate.js';

describe('initToolsPhoneGate', () => {
    /** @type {{ matches: boolean, listeners: Array<(e: MediaQueryListEvent) => void> }} */
    let mediaState;

    beforeEach(() => {
        document.body.innerHTML = `
            <div class="tools-shell" id="tools-shell">
                <main class="tools-shell__main">
                    <div data-tools-phone-gate hidden>Gate</div>
                    <div class="tool-content">Tool</div>
                </main>
            </div>
        `;

        mediaState = { matches: true, listeners: [] };
        vi.stubGlobal('matchMedia', (query) => ({
            matches: query.includes('768') ? mediaState.matches : false,
            media: query,
            addEventListener: (_type, listener) => {
                mediaState.listeners.push(listener);
            },
            removeEventListener: () => {},
            addListener: (listener) => {
                mediaState.listeners.push(listener);
            },
            removeListener: () => {},
        }));
    });

    it('activates the gate on phone viewports', () => {
        initToolsPhoneGate();

        const shell = document.getElementById('tools-shell');
        const gate = document.querySelector('[data-tools-phone-gate]');

        expect(shell?.classList.contains('tools-shell--phone-gate')).toBe(true);
        expect(gate?.hidden).toBe(false);
    });

    it('deactivates the gate when the viewport grows to tablet', () => {
        initToolsPhoneGate();

        mediaState.matches = false;
        mediaState.listeners.forEach((listener) => {
            listener(/** @type {MediaQueryListEvent} */ ({ matches: false }));
        });

        const shell = document.getElementById('tools-shell');
        const gate = document.querySelector('[data-tools-phone-gate]');

        expect(shell?.classList.contains('tools-shell--phone-gate')).toBe(false);
        expect(gate?.hidden).toBe(true);
    });
});
