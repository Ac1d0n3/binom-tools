import { resolveLocalizedLabel } from './localized-label.js';
import { t } from './labels.js';
import {
    createDefaultWorkspace,
    debouncedSaveWorkspace,
    loadRecent,
    loadSeries,
    loadWorkspace,
    normalizeWorkspace,
    saveRecent,
    saveWorkspace,
} from './storage.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./config-validator.js').PromptRoleDef} PromptRoleDef */
/** @typedef {import('./config-validator.js').PromptTaskDef} PromptTaskDef */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */
/** @typedef {import('./storage.js').PromptWorkspaceState} PromptWorkspaceState */
/** @typedef {import('./storage.js').PromptRecentEntry} PromptRecentEntry */

/** @typedef {'library' | 'favorites' | 'templates' | 'recent' | 'workflows'} WorkspaceTab */

/**
 * @typedef {Object} WorkspaceItem
 * @property {string} id
 * @property {'template' | 'task' | 'chain' | 'recent' | 'custom-role'} kind
 * @property {string} title
 * @property {string} [subtitle]
 * @property {string[]} [tags]
 * @property {string} [roleId]
 * @property {string} [taskId]
 * @property {boolean} [favorite]
 * @property {string} [folderId]
 */

/**
 * Workspace sidebar: library, favorites, recent, search, folders, tabs.
 */
export class WorkspaceManager {
    /**
     * @param {PromptStudioConfig} config
     * @param {{ userTemplates?: Array<{ id: string, name: string, roleId?: string, taskId?: string, tags?: string[] }> }} [options]
     */
    constructor(config, options = {}) {
        this.config = config;
        this.userTemplates = options.userTemplates ?? [];

        const saved = loadWorkspace();
        /** @type {PromptWorkspaceState} */
        this.workspace = saved && 'data' in saved ? saved.data : createDefaultWorkspace();

        /** @type {WorkspaceTab} */
        this.activeTab = 'workflows';
        this.searchQuery = '';
    }

    /** @param {WorkspaceTab} tab */
    setTab(tab) {
        this.activeTab = tab;
    }

    /** @param {string} query */
    setSearch(query) {
        this.searchQuery = query.trim().toLowerCase();
    }

    /** @returns {PromptRoleDef[]} */
    getAllRoles() {
        return [...this.config.roles, ...this.workspace.customRoles];
    }

    /**
     * @param {string} itemId
     * @returns {boolean}
     */
    isFavorite(itemId) {
        return this.workspace.favorites.includes(itemId);
    }

    /** @param {string} itemId */
    toggleFavorite(itemId) {
        if (this.isFavorite(itemId)) {
            this.workspace.favorites = this.workspace.favorites.filter((id) => id !== itemId);
        } else {
            this.workspace.favorites = [...this.workspace.favorites, itemId];
        }
        debouncedSaveWorkspace(this.workspace, 'auto');
    }

    /**
     * @param {string} name
     * @param {string | null} [parentId]
     * @returns {string}
     */
    addFolder(name, parentId = null) {
        const id = `folder_${Date.now().toString(36)}`;
        this.workspace.folders.push({ id, name, parentId });
        debouncedSaveWorkspace(this.workspace, 'manual');
        return id;
    }

    /**
     * @param {string} itemId
     * @param {string | null} folderId
     */
    assignToFolder(itemId, folderId) {
        if (!folderId) {
            delete this.workspace.itemFolders[itemId];
        } else {
            const existing = this.workspace.itemFolders[folderId] ?? [];
            if (!existing.includes(itemId)) {
                this.workspace.itemFolders[folderId] = [...existing, itemId];
            }
        }
        debouncedSaveWorkspace(this.workspace, 'manual');
    }

    /**
     * @param {string} name
     * @param {import('./config-validator.js').PromptRoleDef} [role]
     */
    addCustomRole(name, role) {
        if (role) {
            this.workspace.customRoles = [...this.workspace.customRoles.filter((r) => r.id !== role.id), role];
        } else {
            const id = `custom_${Date.now().toString(36)}`;
            this.workspace.customRoles = [
                ...this.workspace.customRoles,
                {
                    id,
                    label: { de: name, en: name },
                    icon: 'fa-user',
                    taskIds: this.config.tasks.slice(0, 3).map((t) => t.id),
                },
            ];
        }
        debouncedSaveWorkspace(this.workspace, 'manual');
    }

    /** @param {Array<{ id: string, name: string }>} templates */
    refreshUserTemplates(templates) {
        this.userTemplates = templates;
    }

    reload() {
        const saved = loadWorkspace();
        this.workspace = saved && 'data' in saved ? saved.data : createDefaultWorkspace();
    }

    /** @returns {WorkspaceItem[]} */
    getVisibleItems() {
        return this.getActiveTabItems();
    }

    /** @param {PromptRecentEntry} entry */
    addRecent(entry) {
        const loaded = loadRecent();
        const list = loaded && 'data' in loaded ? loaded.data.filter((r) => r.id !== entry.id) : [];
        list.unshift(entry);
        saveRecent(list.slice(0, 20), 'auto');
    }

    /** @returns {WorkspaceItem[]} */
    getLibraryItems() {
        /** @type {WorkspaceItem[]} */
        const items = [];

        for (const task of this.config.tasks) {
            if (task.id === 'custom-block') continue;
            const roleId = task.roleIds[0] ?? '';
            const role = this.config.roles.find((r) => r.id === roleId);
            const locale = 'en';
            items.push({
                id: `task:${task.id}`,
                kind: 'task',
                title: resolveLocalizedLabel(task.label, locale, task.id),
                subtitle:
                    resolveLocalizedLabel(task.description, locale, '') ||
                    resolveLocalizedLabel(role?.label, locale, ''),
                roleId,
                taskId: task.id,
                tags: ['task'],
            });
        }

        return this.filterItems(items);
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {WorkspaceItem[]}
     */
    getLibraryItemsForLocale(locale = 'en') {
        /** @type {WorkspaceItem[]} */
        const items = [];

        for (const task of this.config.tasks) {
            if (task.id === 'custom-block') continue;
            const roleId = task.roleIds[0] ?? '';
            const role = this.config.roles.find((r) => r.id === roleId);
            items.push({
                id: `task:${task.id}`,
                kind: 'task',
                title: resolveLocalizedLabel(task.label, locale, task.id),
                subtitle:
                    resolveLocalizedLabel(task.description, locale, '') ||
                    resolveLocalizedLabel(role?.label, locale, ''),
                roleId,
                taskId: task.id,
                tags: ['task'],
            });
        }

        return this.filterItems(items);
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {WorkspaceItem[]}
     */
    getWorkflowItems(locale = 'en') {
        const items = (this.config.chains ?? []).map((chain) => ({
            id: `workflow:${chain.id}`,
            kind: /** @type {const} */ ('chain'),
            title: resolveLocalizedLabel(chain.label, locale, chain.id),
            subtitle:
                resolveLocalizedLabel(chain.description, locale, '') ||
                t(locale, 'promptStudio.workspace.stepCount', { count: chain.steps?.length ?? 0 }),
            tags: ['workflow'],
        }));
        return this.filterItems(items);
    }

    /** @returns {WorkspaceItem[]} */
    getFavoriteItems() {
        const all = [...this.getLibraryItemsForLocale('en'), ...this.getWorkflowItems('en'), ...this.getTemplateItems(), ...this.getRecentItems()];
        return this.filterItems(all.filter((item) => this.isFavorite(item.id)));
    }

    /** @returns {WorkspaceItem[]} */
    getTemplateItems() {
        const items = this.userTemplates.map((tpl) => ({
            id: `user:${tpl.id}`,
            kind: /** @type {const} */ ('template'),
            title: tpl.name,
            subtitle: tpl.taskId ?? '',
            roleId: tpl.roleId,
            taskId: tpl.taskId,
            tags: tpl.tags ?? ['template'],
            favorite: this.isFavorite(`user:${tpl.id}`),
        }));
        return this.filterItems(items);
    }

    /** @returns {WorkspaceItem[]} */
    getRecentItems() {
        const loaded = loadRecent();
        const recent = loaded && 'data' in loaded ? loaded.data : [];
        const items = recent.map((entry) => ({
            id: `recent:${entry.id}`,
            kind: /** @type {const} */ ('recent'),
            title: entry.title || entry.taskId,
            subtitle: entry.savedAt,
            roleId: entry.roleId,
            taskId: entry.taskId,
            tags: ['recent'],
        }));
        return this.filterItems(items);
    }

    /**
     * @param {WorkspaceItem[]} items
     * @returns {WorkspaceItem[]}
     */
    filterItems(items) {
        if (!this.searchQuery) return items;
        return items.filter((item) => {
            const haystack = [item.title, item.subtitle ?? '', ...(item.tags ?? []), item.roleId ?? '', item.taskId ?? '']
                .join(' ')
                .toLowerCase();
            return haystack.includes(this.searchQuery);
        });
    }

    /**
     * @param {ToolsLocale} [locale]
     * @returns {WorkspaceItem[]}
     */
    getSeriesItems(locale = 'en') {
        const loaded = loadSeries();
        const series = loaded && 'data' in loaded ? loaded.data : [];
        const items = series.map((entry) => ({
            id: `series:${entry.id}`,
            kind: /** @type {const} */ ('chain'),
            title: entry.name,
            subtitle: t(locale, 'promptStudio.workspace.variantCount', { count: entry.values.length }),
            tags: ['series'],
        }));
        return this.filterItems(items);
    }

    /** @returns {WorkspaceItem[]} */
    getActiveTabItems() {
        switch (this.activeTab) {
            case 'favorites':
                return this.getFavoriteItems();
            case 'templates':
                return this.getTemplateItems();
            case 'recent':
                return this.getRecentItems();
            case 'workflows':
                return this.getWorkflowItems('en');
            default:
                return this.getLibraryItems();
        }
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {WorkspaceItem[]}
     */
    getActiveTabItemsForLocale(locale = 'en') {
        switch (this.activeTab) {
            case 'favorites': {
                const all = [
                    ...this.getLibraryItemsForLocale(locale),
                    ...this.getWorkflowItems(locale),
                    ...this.getTemplateItems(),
                    ...this.getRecentItems(),
                ];
                return this.filterItems(all.filter((item) => this.isFavorite(item.id)));
            }
            case 'templates':
                return this.getTemplateItems();
            case 'recent':
                return this.getRecentItems();
            case 'workflows':
                return this.getWorkflowItems(locale);
            default:
                return this.getLibraryItemsForLocale(locale);
        }
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {string}
     */
    renderListHtml(locale = 'en') {
        const items = this.getActiveTabItemsForLocale(locale);
        if (items.length === 0) {
            return `<p class="prompt-studio__workspace-empty">${t(locale, 'promptStudio.empty.workspace')}</p>`;
        }

        return items
            .map((item) => {
                const fav = this.isFavorite(item.id);
                return `<button type="button" class="prompt-studio__workspace-item" data-item-id="${escapeAttr(item.id)}" data-kind="${item.kind}" data-role-id="${escapeAttr(item.roleId ?? '')}" data-task-id="${escapeAttr(item.taskId ?? '')}" data-workflow-id="${escapeAttr(item.id.startsWith('workflow:') ? item.id.replace('workflow:', '') : '')}">
                    <span class="prompt-studio__workspace-item-title">${escapeHtml(item.title)}</span>
                    ${item.subtitle ? `<span class="prompt-studio__workspace-item-sub">${escapeHtml(item.subtitle)}</span>` : ''}
                    <span class="prompt-studio__workspace-item-fav${fav ? ' is-active' : ''}" data-fav-id="${escapeAttr(item.id)}" aria-label="${escapeAttr(t(locale, 'promptStudio.workspace.favorite'))}">★</span>
                </button>`;
            })
            .join('');
    }

    /** @returns {PromptWorkspaceState} */
    getWorkspaceState() {
        return normalizeWorkspace(this.workspace);
    }

    /** @param {PromptWorkspaceState} workspace */
    importWorkspace(workspace) {
        this.workspace = normalizeWorkspace(workspace);
        saveWorkspace(this.workspace, 'import');
    }
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeAttr(value) {
    return value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeHtml(value) {
    return escapeAttr(value).replaceAll('>', '&gt;');
}

/**
 * @param {PromptStudioConfig} config
 * @param {{ userTemplates?: Array<{ id: string, name: string }> }} [options]
 * @returns {WorkspaceManager}
 */
export function createWorkspaceManager(config, options) {
    return new WorkspaceManager(config, options);
}
