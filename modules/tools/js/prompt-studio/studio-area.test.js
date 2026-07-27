import { describe, it, expect, beforeEach } from 'vitest';
import {
    STUDIO_AREAS,
    normalizeStudioArea,
    outputKindForArea,
    areaForOutputKind,
    setStudioArea,
    getStudioArea,
    STUDIO_AREA_KEY,
} from './studio-area.js';
import { WorkspaceManager } from './workspace-manager.js';

describe('studio-area', () => {
    beforeEach(() => {
        localStorage.removeItem(STUDIO_AREA_KEY);
    });

    it('normalizes areas and maps to output kinds', () => {
        expect(STUDIO_AREAS).toEqual(['prompt', 'rule', 'agent']);
        expect(normalizeStudioArea('rule')).toBe('rule');
        expect(normalizeStudioArea('nope')).toBe('prompt');
        expect(outputKindForArea('prompt')).toBe('prompt');
        expect(outputKindForArea('rule')).toBe('rule');
        expect(outputKindForArea('agent')).toBe('agent-task');
        expect(areaForOutputKind('agent-task')).toBe('agent');
    });

    it('persists studio area', () => {
        expect(getStudioArea()).toBe('prompt');
        setStudioArea('agent');
        expect(getStudioArea()).toBe('agent');
        expect(localStorage.getItem(STUDIO_AREA_KEY)).toBe('agent');
    });
});

describe('WorkspaceManager area filtering', () => {
    /** @returns {import('./config-validator.js').PromptStudioConfig} */
    function mockConfig() {
        return {
            roles: [{ id: 'r', label: { de: 'R', en: 'R' }, taskIds: ['cover', 'angular-signals', 'debug-investigate'] }],
            tasks: [
                {
                    id: 'cover',
                    label: { de: 'Cover', en: 'Cover' },
                    roleIds: ['r'],
                    parameterIds: [],
                    templateId: 'cover',
                    category: 'image',
                },
                {
                    id: 'angular-signals',
                    label: { de: 'Rule', en: 'Rule' },
                    roleIds: ['r'],
                    parameterIds: [],
                    templateId: 'angular-signals',
                    outputKind: 'rule',
                    category: 'agent',
                },
                {
                    id: 'debug-investigate',
                    label: { de: 'Debug', en: 'Debug' },
                    roleIds: ['r'],
                    parameterIds: [],
                    templateId: 'debug-investigate',
                    outputKind: 'agent-task',
                    category: 'agent',
                },
            ],
            parameters: [],
            templates: [],
            models: [],
            chains: [],
        };
    }

    beforeEach(() => {
        localStorage.removeItem(STUDIO_AREA_KEY);
    });

    it('lists only prompt tasks in prompt area', () => {
        setStudioArea('prompt');
        const manager = new WorkspaceManager(mockConfig());
        expect(manager.getLibraryItemsForLocale('en').map((i) => i.taskId)).toEqual(['cover']);
    });

    it('lists only rule tasks in rule area', () => {
        setStudioArea('rule');
        const manager = new WorkspaceManager(mockConfig());
        expect(manager.getLibraryItemsForLocale('en').map((i) => i.taskId)).toEqual(['angular-signals']);
    });

    it('lists only agent tasks in agent area', () => {
        setStudioArea('agent');
        const manager = new WorkspaceManager(mockConfig());
        expect(manager.getLibraryItemsForLocale('en').map((i) => i.taskId)).toEqual(['debug-investigate']);
    });
});
