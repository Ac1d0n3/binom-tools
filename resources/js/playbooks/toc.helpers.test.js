import { describe, expect, it } from 'vitest';
import {
    buildTocGroups,
    findActiveHeadingId,
    isInViewport,
    syncExpandedGroups,
    toggleExpandedGroup,
} from './toc.helpers.js';

describe('buildTocGroups', () => {
    it('groups h3 entries under preceding h2', () => {
        const groups = buildTocGroups([
            { id: 'a', text: 'A', level: 2 },
            { id: 'a1', text: 'A1', level: 3 },
            { id: 'b', text: 'B', level: 2 },
        ]);

        expect(groups).toHaveLength(2);
        expect(groups[0].children).toEqual([{ id: 'a1', label: 'A1' }]);
        expect(groups[1].children).toEqual([]);
    });
});

describe('findActiveHeadingId', () => {
    it('returns last heading above marker', () => {
        const scrollRoot = { getBoundingClientRect: () => ({ top: 0 }) };
        const headings = [
            { id: 'one', getBoundingClientRect: () => ({ top: -10 }) },
            { id: 'two', getBoundingClientRect: () => ({ top: 20 }) },
            { id: 'three', getBoundingClientRect: () => ({ top: 80 }) },
        ];

        expect(findActiveHeadingId(headings, scrollRoot, 24)).toBe('two');
    });
});

describe('syncExpandedGroups', () => {
    const groups = buildTocGroups([
        { id: 'branch', text: 'Branch', level: 2 },
        { id: 'child', text: 'Child', level: 3 },
        { id: 'leaf', text: 'Leaf', level: 2 },
    ]);

    it('expands branch containing active child on scroll', () => {
        const result = syncExpandedGroups(groups, 'child', null, { fromScroll: true });

        expect(result.expandedGroupIds.has('branch')).toBe(true);
        expect(result.manualExpandedGroupId).toBe('branch');
    });

    it('does not expand leaf-only groups', () => {
        const result = syncExpandedGroups(groups, 'leaf', null, { fromScroll: true });

        expect(result.expandedGroupIds.size).toBe(0);
    });
});

describe('toggleExpandedGroup', () => {
    const groups = buildTocGroups([
        { id: 'a', text: 'A', level: 2 },
        { id: 'a1', text: 'A1', level: 3 },
    ]);

    it('opens and closes branch accordion-style', () => {
        const opened = toggleExpandedGroup(groups, 'a', new Set());
        expect(opened.expandedGroupIds.has('a')).toBe(true);

        const closed = toggleExpandedGroup(groups, 'a', opened.expandedGroupIds);
        expect(closed.expandedGroupIds.has('a')).toBe(false);
    });
});

describe('isInViewport', () => {
    it('detects fully visible element', () => {
        const root = {
            getBoundingClientRect: () => ({
                top: 44,
                bottom: 900,
                left: 0,
                right: 1280,
            }),
        };

        const el = {
            getBoundingClientRect: () => ({
                top: 50,
                bottom: 100,
                left: 10,
                right: 200,
                width: 190,
                height: 50,
            }),
        };

        expect(isInViewport(el, { root, paddingTop: 44 })).toBe(true);
    });

    it('rejects clipped element above viewport', () => {
        const root = {
            getBoundingClientRect: () => ({
                top: 44,
                bottom: 900,
                left: 0,
                right: 1280,
            }),
        };

        const el = {
            getBoundingClientRect: () => ({
                top: 10,
                bottom: 100,
                left: 10,
                right: 200,
                width: 190,
                height: 90,
            }),
        };

        expect(isInViewport(el, { root, paddingTop: 44 })).toBe(false);
    });
});
