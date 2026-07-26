/** @vitest-environment jsdom */

import { beforeEach, describe, expect, it, vi } from 'vitest';
import {
    OVERVIEW_ITEM_UNREVEALED_CLASS,
    OVERVIEW_REVEAL_BATCH,
    attachOverviewProgressiveReveal,
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
});
