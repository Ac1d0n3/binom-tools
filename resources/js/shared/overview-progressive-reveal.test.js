/** @vitest-environment jsdom */

import { beforeEach, describe, expect, it, vi } from 'vitest';
import {
    OVERVIEW_ITEM_UNREVEALED_CLASS,
    OVERVIEW_REVEAL_BATCH,
    attachOverviewProgressiveReveal,
    findOverviewScrollRoot,
} from './overview-progressive-reveal.js';

describe('attachOverviewProgressiveReveal', () => {
    beforeEach(() => {
        document.body.innerHTML = '';
        vi.stubGlobal(
            'IntersectionObserver',
            class {
                observe() {}
                disconnect() {}
                unobserve() {}
            },
        );
    });

    it('reveals the first batch and defers the rest', () => {
        const root = document.createElement('div');
        root.setAttribute('data-overview-filter-root', '');
        const grid = document.createElement('div');
        grid.className = 'tools-card-grid';
        grid.setAttribute('data-overview-stories-grid', '');

        for (let i = 0; i < OVERVIEW_REVEAL_BATCH + 5; i += 1) {
            const item = document.createElement('article');
            item.setAttribute('data-overview-item', '');
            grid.appendChild(item);
        }

        root.appendChild(grid);
        document.body.appendChild(root);

        const api = attachOverviewProgressiveReveal(root, { getSearchQuery: () => '' });

        const items = Array.from(grid.querySelectorAll('[data-overview-item]'));
        expect(items.filter((el) => !el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toHaveLength(
            OVERVIEW_REVEAL_BATCH,
        );
        expect(items.filter((el) => el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toHaveLength(5);
        expect(grid.querySelector('[data-overview-reveal-sentinel]')).not.toBeNull();

        api.destroy();
    });

    it('reveals all matches when search yields a small result set', () => {
        const root = document.createElement('div');
        const grid = document.createElement('div');
        grid.className = 'tools-card-grid';
        grid.setAttribute('data-overview-stories-grid', '');

        for (let i = 0; i < 8; i += 1) {
            const item = document.createElement('article');
            item.setAttribute('data-overview-item', '');
            if (i >= 3) {
                item.hidden = true;
            }
            grid.appendChild(item);
        }

        root.appendChild(grid);
        document.body.appendChild(root);

        attachOverviewProgressiveReveal(root, { getSearchQuery: () => 'governance' });

        const visible = Array.from(grid.querySelectorAll('[data-overview-item]')).filter((el) => !el.hidden);
        expect(visible.every((el) => !el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toBe(true);
    });

    it('only reveals cards in the active series panel', () => {
        const root = document.createElement('div');

        const storiesPanel = document.createElement('div');
        storiesPanel.setAttribute('data-overview-view-panel', 'stories');
        storiesPanel.hidden = true;
        const storiesGrid = document.createElement('div');
        storiesGrid.setAttribute('data-overview-stories-grid', '');
        for (let i = 0; i < 30; i += 1) {
            const item = document.createElement('article');
            item.setAttribute('data-overview-item', '');
            storiesGrid.appendChild(item);
        }
        storiesPanel.appendChild(storiesGrid);

        const seriesPanel = document.createElement('div');
        seriesPanel.setAttribute('data-overview-view-panel', 'series');
        const seriesGrid = document.createElement('div');
        seriesGrid.setAttribute('data-overview-series-grid', '');
        for (let i = 0; i < 5; i += 1) {
            const item = document.createElement('article');
            item.setAttribute('data-overview-series-item', '');
            seriesGrid.appendChild(item);
        }
        seriesPanel.appendChild(seriesGrid);

        root.appendChild(storiesPanel);
        root.appendChild(seriesPanel);
        document.body.appendChild(root);

        attachOverviewProgressiveReveal(root, { getSearchQuery: () => '' });

        const seriesItems = Array.from(seriesGrid.querySelectorAll('[data-overview-series-item]'));
        expect(seriesItems.every((el) => !el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toBe(true);

        const storyItems = Array.from(storiesGrid.querySelectorAll('[data-overview-item]'));
        expect(storyItems.every((el) => !el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toBe(true);
    });

    it('places the sentinel in glossary hub grids', () => {
        const root = document.createElement('div');
        root.setAttribute('data-overview-filter-root', '');
        const scroll = document.createElement('div');
        scroll.className = 'tools-overview-scroll';
        const grid = document.createElement('div');
        grid.className = 'glossary-hub-grid';
        grid.setAttribute('data-overview-stories-grid', '');

        for (let i = 0; i < OVERVIEW_REVEAL_BATCH + 4; i += 1) {
            const item = document.createElement('a');
            item.setAttribute('data-overview-item', '');
            grid.appendChild(item);
        }

        scroll.appendChild(grid);
        root.appendChild(scroll);
        document.body.appendChild(root);

        vi.spyOn(window, 'getComputedStyle').mockImplementation((el) => {
            if (el === scroll) {
                return /** @type {CSSStyleDeclaration} */ ({ overflowY: 'auto' });
            }
            return /** @type {CSSStyleDeclaration} */ ({ overflowY: 'visible' });
        });

        const api = attachOverviewProgressiveReveal(root, { getSearchQuery: () => '' });

        expect(grid.querySelector('[data-overview-reveal-sentinel]')).not.toBeNull();
        const items = Array.from(grid.querySelectorAll('[data-overview-item]'));
        expect(items.filter((el) => el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toHaveLength(4);
        expect(findOverviewScrollRoot(root)).toBe(scroll);

        api.destroy();
    });

    it('keeps revealing while the sentinel stays in the scroll viewport', () => {
        const root = document.createElement('div');
        const scroll = document.createElement('div');
        scroll.className = 'tools-overview-scroll';
        Object.defineProperty(scroll, 'getBoundingClientRect', {
            value: () => ({ top: 0, bottom: 800, left: 0, right: 400, width: 400, height: 800 }),
        });

        vi.spyOn(window, 'getComputedStyle').mockImplementation((el) => {
            if (el === scroll) {
                return /** @type {CSSStyleDeclaration} */ ({ overflowY: 'auto' });
            }
            return /** @type {CSSStyleDeclaration} */ ({ overflowY: 'visible' });
        });

        const grid = document.createElement('div');
        grid.className = 'glossary-hub-grid';
        grid.setAttribute('data-overview-stories-grid', '');

        for (let i = 0; i < OVERVIEW_REVEAL_BATCH * 3; i += 1) {
            const item = document.createElement('a');
            item.setAttribute('data-overview-item', '');
            grid.appendChild(item);
        }

        scroll.appendChild(grid);
        root.appendChild(scroll);
        document.body.appendChild(root);

        /** @type {IntersectionObserverCallback | null} */
        let ioCallback = null;
        vi.stubGlobal(
            'IntersectionObserver',
            class {
                /**
                 * @param {IntersectionObserverCallback} cb
                 */
                constructor(cb) {
                    ioCallback = cb;
                }
                observe() {}
                disconnect() {}
                unobserve() {}
            },
        );

        attachOverviewProgressiveReveal(root, { getSearchQuery: () => '' });

        const sentinel = grid.querySelector('[data-overview-reveal-sentinel]');
        expect(sentinel).not.toBeNull();
        Object.defineProperty(/** @type {HTMLElement} */ (sentinel), 'getBoundingClientRect', {
            value: () => ({ top: 700, bottom: 701, left: 0, right: 400, width: 400, height: 1 }),
        });

        ioCallback?.(
            [{ isIntersecting: true, target: /** @type {Element} */ (sentinel) }],
            /** @type {IntersectionObserver} */ ({}),
        );

        const items = Array.from(grid.querySelectorAll('[data-overview-item]'));
        expect(items.every((el) => !el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toBe(true);
        expect(/** @type {HTMLElement} */ (sentinel).hidden).toBe(true);
    });

    it('does not hide items when there is no reveal host (admin tables)', () => {
        const root = document.createElement('div');
        root.className = 'admin-hub';
        root.setAttribute('data-overview-filter-root', '');
        const table = document.createElement('table');
        const tbody = document.createElement('tbody');

        for (let i = 0; i < OVERVIEW_REVEAL_BATCH + 10; i += 1) {
            const row = document.createElement('tr');
            row.setAttribute('data-overview-item', '');
            tbody.appendChild(row);
        }

        table.appendChild(tbody);
        root.appendChild(table);
        document.body.appendChild(root);

        attachOverviewProgressiveReveal(root, { getSearchQuery: () => '' });

        const items = Array.from(root.querySelectorAll('[data-overview-item]'));
        expect(items).toHaveLength(OVERVIEW_REVEAL_BATCH + 10);
        expect(items.every((el) => !el.classList.contains(OVERVIEW_ITEM_UNREVEALED_CLASS))).toBe(true);
    });
});
