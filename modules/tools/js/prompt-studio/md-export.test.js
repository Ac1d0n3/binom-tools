import { describe, expect, it } from 'vitest';
import {
    buildMarkdownDocument,
    getTaskOutputKind,
    markdownFilename,
    normalizeOutputKind,
} from './md-export.js';
import { mergeById } from './library-api.js';
import { isPreviewDrawerOpen, PREVIEW_OPEN_KEY, setPreviewDrawerOpen } from './session-store.js';

describe('md-export', () => {
    it('normalizes output kinds', () => {
        expect(normalizeOutputKind('rule')).toBe('rule');
        expect(normalizeOutputKind('agent-task')).toBe('agent-task');
        expect(normalizeOutputKind('nope')).toBe('prompt');
    });

    it('reads outputKind from task definitions', () => {
        expect(getTaskOutputKind({ outputKind: 'rule' })).toBe('rule');
        expect(getTaskOutputKind({ outputKind: 'agent-task' })).toBe('agent-task');
        expect(getTaskOutputKind({})).toBe('prompt');
        expect(getTaskOutputKind(null)).toBe('prompt');
    });

    it('builds project rule markdown with frontmatter', () => {
        const md = buildMarkdownDocument({
            kind: 'rule',
            title: 'Angular Signals',
            body: 'Use signal() and @if only.',
            globs: '**/*.ts',
            alwaysApply: false,
        });
        expect(md).toContain('---');
        expect(md).toContain('globs: **/*.ts');
        expect(md).toContain('# Angular Signals');
        expect(md).toContain('Use signal()');
        expect(markdownFilename('Angular Signals', 'rule')).toBe('angular-signals.mdc.md');
    });

    it('builds agent-task markdown checklist', () => {
        const md = buildMarkdownDocument({
            kind: 'agent-task',
            title: 'Debug login',
            body: 'Find why login fails',
        });
        expect(md).toContain('kind: agent-task');
        expect(md).toContain('## Goal');
        expect(md).toContain('## Done when');
        expect(markdownFilename('Debug login', 'agent-task')).toBe('debug-login.agent-task.md');
    });
});

describe('library merge', () => {
    it('lets remote win on same id', () => {
        const merged = mergeById(
            [{ id: 'a', name: 'local' }, { id: 'b', name: 'keep' }],
            [{ id: 'a', name: 'remote' }],
        );
        expect(merged.find((x) => x.id === 'a')?.name).toBe('remote');
        expect(merged.find((x) => x.id === 'b')?.name).toBe('keep');
    });
});

describe('preview drawer flag', () => {
    it('persists open state', () => {
        localStorage.removeItem(PREVIEW_OPEN_KEY);
        expect(isPreviewDrawerOpen()).toBe(true);
        setPreviewDrawerOpen(false);
        expect(isPreviewDrawerOpen()).toBe(false);
        setPreviewDrawerOpen(true);
        expect(isPreviewDrawerOpen()).toBe(true);
    });
});
