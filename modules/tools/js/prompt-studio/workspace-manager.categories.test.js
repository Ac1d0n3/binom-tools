import { describe, it, expect, beforeEach } from 'vitest';
import { WorkspaceManager } from './workspace-manager.js';

/** @returns {import('./config-validator.js').PromptStudioConfig} */
function mockConfig() {
    return {
        roles: [
            {
                id: 'image-designer',
                label: { de: 'Bilddesigner', en: 'Image Designer' },
                taskIds: ['cover', 'lyrics'],
            },
            {
                id: 'songwriter',
                label: { de: 'Songwriter', en: 'Songwriter' },
                taskIds: ['lyrics'],
            },
        ],
        tasks: [
            {
                id: 'cover',
                label: { de: 'Cover', en: 'Cover' },
                description: { de: 'Albumcover', en: 'Album cover' },
                roleIds: ['image-designer'],
                parameterIds: ['motiv'],
                templateId: 'cover',
                category: 'image',
            },
            {
                id: 'lyrics',
                label: { de: 'Songtext', en: 'Lyrics' },
                description: { de: 'Songtext schreiben', en: 'Write lyrics' },
                roleIds: ['songwriter'],
                parameterIds: ['story'],
                templateId: 'lyrics',
                category: 'music',
            },
            {
                id: 'custom-block',
                label: { de: 'Custom', en: 'Custom' },
                roleIds: [],
                parameterIds: [],
                templateId: 'custom-block',
            },
        ],
        parameters: [],
        templates: [],
        models: [],
        chains: [
            {
                id: 'song-production',
                label: { de: 'Song-Produktion', en: 'Song production' },
                description: { de: 'Musik-Workflow', en: 'Music workflow' },
                steps: [{ roleId: 'songwriter', taskId: 'lyrics' }],
                category: 'music',
            },
            {
                id: 'business-visual',
                label: { de: 'Business Visual', en: 'Business visual' },
                steps: [{ roleId: 'image-designer', taskId: 'cover' }],
                category: 'business',
            },
        ],
    };
}

describe('WorkspaceManager category filter', () => {
    /** @type {WorkspaceManager} */
    let manager;

    beforeEach(() => {
        manager = new WorkspaceManager(mockConfig());
        manager.setTab('library');
    });

    it('filters library tasks by category', () => {
        manager.setCategoryFilter('image');
        const items = manager.getLibraryItemsForLocale('de');
        expect(items.map((i) => i.taskId)).toEqual(['cover']);
        expect(items[0].category).toBe('image');
        expect(items[0].subtitle).toContain('Bilder');
    });

    it('filters workflows by category', () => {
        manager.setTab('workflows');
        manager.setCategoryFilter('music');
        const items = manager.getWorkflowItems('en');
        expect(items.map((i) => i.workflowId)).toEqual(['song-production']);
    });

    it('includes category labels in search', () => {
        manager.setCategoryFilter('all');
        manager.setSearch('bilder');
        const items = manager.getLibraryItemsForLocale('de');
        expect(items.some((i) => i.taskId === 'cover')).toBe(true);
        expect(items.some((i) => i.taskId === 'lyrics')).toBe(false);
    });
});
