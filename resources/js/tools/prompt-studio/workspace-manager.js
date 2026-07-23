import { resolveLocalizedLabel } from './localized-label.js';
import { t } from './labels.js';
import {
    createDefaultWorkspace,
    debouncedSaveWorkspace,
    loadChains,
    loadRecent,
    loadSeries,
    loadWorkspace,
    normalizeWorkspace,
    saveRecent,
    saveWorkspace,
} from './storage.js';
import { normalizeOutputKind, getTaskOutputKind } from './md-export.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./config-validator.js').PromptRoleDef} PromptRoleDef */
/** @typedef {import('./config-validator.js').PromptTaskDef} PromptTaskDef */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */
/** @typedef {import('./storage.js').PromptWorkspaceState} PromptWorkspaceState */
/** @typedef {import('./storage.js').PromptRecentEntry} PromptRecentEntry */

/** @typedef {'library' | 'favorites' | 'templates' | 'recent' | 'workflows' | 'roles'} WorkspaceTab */

/** Featured standalone tasks shown first in the Aufgaben tab (not only via workflows). */
const FEATURED_TASK_IDS = [
    'song-develop',
    'lyrics',
    'hook',
    'suno-style',
    'cover',
    'album',
    'debug-investigate',
    'angular-signals',
    'automate-repo-task',
];

/**
 * @param {PromptTaskDef[]} tasks
 * @returns {PromptTaskDef[]}
 */
function sortTasksForLibrary(tasks) {
    const featuredIndex = new Map(FEATURED_TASK_IDS.map((id, index) => [id, index]));
    return [...tasks].sort((a, b) => {
        const ai = featuredIndex.has(a.id) ? /** @type {number} */ (featuredIndex.get(a.id)) : Number.MAX_SAFE_INTEGER;
        const bi = featuredIndex.has(b.id) ? /** @type {number} */ (featuredIndex.get(b.id)) : Number.MAX_SAFE_INTEGER;
        if (ai !== bi) return ai - bi;
        return a.id.localeCompare(b.id);
    });
}

/**
 * @typedef {Object} WorkspaceItem
 * @property {string} id
 * @property {'template' | 'task' | 'chain' | 'recent' | 'custom-role' | 'role'} kind
 * @property {string} title
 * @property {string} [subtitle]
 * @property {string[]} [tags]
 * @property {string} [roleId]
 * @property {string} [taskId]
 * @property {string} [workflowId]
 * @property {string} [outputKind]
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
        this.activeTab = 'library';
        this.searchQuery = '';
        /** @type {import('./md-export.js').OutputKind | 'all'} */
        this.templateKindFilter = 'all';
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

    /** @param {import('./md-export.js').OutputKind | 'all'} kind */
    setTemplateKindFilter(kind) {
        this.templateKindFilter = kind === 'all' ? 'all' : normalizeOutputKind(kind);
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
        return this.workspace.customRoles[0]?.id;
    }

    /**
     * @param {string} id
     */
    removeCustomRole(id) {
        this.workspace.customRoles = this.workspace.customRoles.filter((r) => r.id !== id);
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
        return this.getLibraryItemsForLocale('en');
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {WorkspaceItem[]}
     */
    getLibraryItemsForLocale(locale = 'en') {
        /** @type {WorkspaceItem[]} */
        const items = [];

        for (const task of sortTasksForLibrary(this.config.tasks)) {
            if (task.id === 'custom-block') continue;
            const outputKind = getTaskOutputKind(task);
            if (this.templateKindFilter !== 'all' && outputKind !== this.templateKindFilter) continue;
            const roleId = task.roleIds[0] ?? '';
            const role = this.config.roles.find((r) => r.id === roleId);
            const kindLabel = t(locale, kindLabelKey(outputKind));
            const description =
                resolveLocalizedLabel(task.description, locale, '') ||
                resolveLocalizedLabel(role?.label, locale, '');
            items.push({
                id: `task:${task.id}`,
                kind: 'task',
                title: resolveLocalizedLabel(task.label, locale, task.id),
                subtitle: description ? `${kindLabel} · ${description}` : kindLabel,
                roleId,
                taskId: task.id,
                outputKind,
                tags: ['task', outputKind],
            });
        }

        return this.filterItems(items);
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {WorkspaceItem[]}
     */
    getWorkflowItems(locale = 'en') {
        const workflowLabel = t(locale, 'promptStudio.badge.workflow');
        const presetItems = (this.config.chains ?? []).map((chain) => ({
            id: `workflow:${chain.id}`,
            kind: /** @type {const} */ ('chain'),
            title: resolveLocalizedLabel(chain.label, locale, chain.id),
            subtitle: `${workflowLabel} · ${
                resolveLocalizedLabel(chain.description, locale, '') ||
                t(locale, 'promptStudio.workspace.stepCount', { count: chain.steps?.length ?? 0 })
            }`,
            workflowId: chain.id,
            tags: ['workflow', 'preset'],
        }));
        const loaded = loadChains();
        const userChains = loaded && 'data' in loaded ? loaded.data : [];
        const userItems = userChains.map((chain) => ({
            id: `user-workflow:${chain.id}`,
            kind: /** @type {const} */ ('chain'),
            title: chain.name,
            subtitle: `${workflowLabel} · ${t(locale, 'promptStudio.workspace.stepCount', { count: chain.steps?.length ?? 0 })}`,
            workflowId: chain.id,
            tags: ['workflow', 'mine'],
        }));
        return this.filterItems([...presetItems, ...userItems]);
    }

    /** @returns {WorkspaceItem[]} */
    getTemplateItems() {
        const items = this.userTemplates
            .filter((tpl) => {
                if (this.templateKindFilter === 'all') return true;
                return normalizeOutputKind(tpl.kind) === this.templateKindFilter;
            })
            .map((tpl) => ({
                id: `user:${tpl.id}`,
                kind: /** @type {const} */ ('template'),
                title: tpl.name,
                subtitle: [normalizeOutputKind(tpl.kind), tpl.taskId ?? ''].filter(Boolean).join(' · '),
                roleId: tpl.roleId,
                taskId: tpl.taskId,
                outputKind: normalizeOutputKind(tpl.kind),
                tags: tpl.tags ?? ['template'],
                favorite: this.isFavorite(`user:${tpl.id}`),
            }));
        return this.filterItems(items);
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {WorkspaceItem[]}
     */
    getRoleItems(locale = 'en') {
        const configRoles = this.config.roles.map((role) => ({
            id: `role:${role.id}`,
            kind: /** @type {const} */ ('role'),
            title: resolveLocalizedLabel(role.label, locale, role.id),
            subtitle: t(locale, 'promptStudio.workspace.taskCount', { count: role.taskIds?.length ?? 0 }),
            roleId: role.id,
            tags: ['role', 'preset'],
        }));
        const custom = this.workspace.customRoles.map((role) => ({
            id: `custom-role:${role.id}`,
            kind: /** @type {const} */ ('custom-role'),
            title: resolveLocalizedLabel(role.label, locale, role.id),
            subtitle: t(locale, 'promptStudio.roles.custom'),
            roleId: role.id,
            tags: ['role', 'mine'],
        }));
        return this.filterItems([...configRoles, ...custom]);
    }

    /** @returns {WorkspaceItem[]} */
    getFavoriteItems() {
        const all = [
            ...this.getLibraryItemsForLocale('en'),
            ...this.getWorkflowItems('en'),
            ...this.getTemplateItems(),
            ...this.getRecentItems(),
            ...this.getRoleItems('en'),
        ];
        return this.filterItems(all.filter((item) => this.isFavorite(item.id)));
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
            case 'roles':
                return this.getRoleItems('en');
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
                    ...this.getRoleItems(locale),
                ];
                return this.filterItems(all.filter((item) => this.isFavorite(item.id)));
            }
            case 'templates':
                return this.getTemplateItems();
            case 'recent':
                return this.getRecentItems();
            case 'workflows':
                return this.getWorkflowItems(locale);
            case 'roles':
                return this.getRoleItems(locale);
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
                const badge =
                    item.kind === 'chain'
                        ? t(locale, 'promptStudio.badge.workflow')
                        : item.outputKind
                          ? t(locale, kindLabelKey(item.outputKind))
                          : '';
                return `<button type="button" class="prompt-studio__workspace-item" data-item-id="${escapeAttr(item.id)}" data-kind="${item.kind}" data-role-id="${escapeAttr(item.roleId ?? '')}" data-task-id="${escapeAttr(item.taskId ?? '')}" data-workflow-id="${escapeAttr(item.workflowId ?? '')}">
                    ${badge ? `<span class="prompt-studio__workspace-item-badge">${escapeHtml(badge)}</span>` : ''}
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
 * @param {import('./md-export.js').OutputKind} kind
 * @returns {string}
 */
function kindLabelKey(kind) {
    if (kind === 'rule') return 'promptStudio.kind.rule';
    if (kind === 'agent-task') return 'promptStudio.kind.agentTask';
    return 'promptStudio.kind.prompt';
}

/**
 * @param {PromptStudioConfig} config
 * @param {{ userTemplates?: Array<{ id: string, name: string }> }} [options]
 * @returns {WorkspaceManager}
 */
export function createWorkspaceManager(config, options) {
    return new WorkspaceManager(config, options);
}
