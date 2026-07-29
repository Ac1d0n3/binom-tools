import { DQ_REGIONS, DEFAULT_DQ_REGION, normalizeDqRegionId } from './dq-regions.js';
import { listPacksForRegion, packLabel } from './dq-rule-packs.js';
import {
    artifactsForTool,
    deleteToolArtifact,
    fetchActiveWorkspace,
    readDqWorkspaceConfig,
    saveToolArtifact,
} from './dq-workspace-artifacts.js';

/**
 * @typedef {Object} DqPackPanelOptions
 * @property {HTMLElement} root
 * @property {string} toolId
 * @property {() => 'de' | 'en'} locale
 * @property {(key: string, params?: Record<string, string | number>) => string} t
 * @property {() => unknown} getPayload
 * @property {(payload: unknown, meta?: { region?: string }) => void} applyPayload
 * @property {(packId: string, regionId: string) => void} applyPack
 * @property {(regionId: string) => void} [onRegionChange]
 * @property {string[]} [relatedToolIds]
 */

/**
 * Mount region + pack + workspace save panel into `[data-dq-packs-panel]`.
 * Looks in `options.root` first, then the surrounding tools page (header save slot lives outside the app root).
 * @param {DqPackPanelOptions} options
 */
export function mountDqPacksPanel(options) {
    const scope =
        options.root.closest('.tools-content') ||
        options.root.closest('[data-tool-page-header]')?.parentElement ||
        document;
    const panel =
        options.root.querySelector('[data-dq-packs-panel]') ||
        /** @type {ParentNode} */ (scope).querySelector('[data-dq-packs-panel]');
    if (!panel) return null;

    const configRoot =
        /** @type {ParentNode} */ (scope).querySelector('[data-dq-workspace-config]')
            ? scope
            : document;
    const config = readDqWorkspaceConfig(configRoot);
    /** @type {string} */
    let region = DEFAULT_DQ_REGION;
    /** @type {import('./dq-workspace-artifacts.js').DqToolArtifact[]} */
    let artifacts = [];
    /** @type {string | null} */
    let workspaceName = null;
    /** @type {boolean} */
    let workspaceReady = false;

    const els = {
        region: /** @type {HTMLSelectElement | null} */ (panel.querySelector('[data-dq-region]')),
        packs: /** @type {HTMLElement | null} */ (panel.querySelector('[data-dq-packs]')),
        status: /** @type {HTMLElement | null} */ (panel.querySelector('[data-dq-workspace-status]')),
        name: /** @type {HTMLInputElement | null} */ (panel.querySelector('[data-dq-save-name]')),
        saveBtn: /** @type {HTMLButtonElement | null} */ (panel.querySelector('[data-dq-save-btn]')),
        list: /** @type {HTMLElement | null} */ (panel.querySelector('[data-dq-saved-list]')),
    };

    function fillRegionSelect() {
        if (!els.region) return;
        const locale = options.locale();
        els.region.innerHTML = DQ_REGIONS.map((item) => {
            const label = item.label[locale] ?? item.label.en;
            return `<option value="${item.id}"${item.id === region ? ' selected' : ''}>${label}</option>`;
        }).join('');
    }

    function renderPacks() {
        if (!els.packs) return;
        const locale = options.locale();
        const packs = listPacksForRegion(region);
        els.packs.innerHTML = packs
            .map((pack) => {
                const label = pack.label[locale] ?? pack.label.en;
                const desc = pack.description[locale] ?? pack.description.en;
                return `<button type="button" class="tools-btn tools-btn--ghost tools-btn--sm" data-dq-apply-pack="${pack.id}" title="${escapeAttr(desc)}">${escapeHtml(label)}</button>`;
            })
            .join('');
        els.packs.querySelectorAll('[data-dq-apply-pack]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const packId = btn.getAttribute('data-dq-apply-pack');
                if (!packId) return;
                options.applyPack(packId, region);
                if (els.status) {
                    els.status.textContent = options.t('dqPacks.applied', { pack: packLabel(packId, locale) });
                }
            });
        });
    }

    function toolIds() {
        return [options.toolId, ...(options.relatedToolIds || [])];
    }

    function renderSavedList() {
        if (!els.list) return;
        const relevant = artifacts.filter((item) => toolIds().includes(item.toolId));
        if (!workspaceReady) {
            els.list.innerHTML = `<p class="tools-panel-meta">${escapeHtml(options.t('dqPacks.workspace.needActive'))}</p>`;
            return;
        }
        if (relevant.length === 0) {
            els.list.innerHTML = `<p class="tools-panel-meta">${escapeHtml(options.t('dqPacks.workspace.empty'))}</p>`;
            return;
        }
        els.list.innerHTML = relevant
            .map(
                (item) => `<div class="dq-saved-item" data-artifact-id="${escapeAttr(item.id)}">
                    <span>${escapeHtml(item.name)}${item.region ? ` · ${escapeHtml(item.region)}` : ''}</span>
                    <span class="dq-saved-item__actions">
                        <button type="button" class="tools-btn tools-btn--sm" data-dq-load-artifact="${escapeAttr(item.id)}">${escapeHtml(options.t('dqPacks.workspace.load'))}</button>
                        <button type="button" class="tools-btn tools-btn--ghost tools-btn--sm" data-dq-delete-artifact="${escapeAttr(item.id)}">${escapeHtml(options.t('dqPacks.workspace.delete'))}</button>
                    </span>
                </div>`,
            )
            .join('');

        els.list.querySelectorAll('[data-dq-load-artifact]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-dq-load-artifact');
                const item = artifacts.find((a) => a.id === id);
                if (!item) return;
                if (item.region) {
                    region = normalizeDqRegionId(item.region);
                    if (els.region) els.region.value = region;
                    renderPacks();
                    options.onRegionChange?.(region);
                }
                options.applyPayload(item.payload, { region: item.region || region });
                if (els.status) {
                    els.status.textContent = options.t('dqPacks.workspace.loaded', { name: item.name });
                }
            });
        });

        els.list.querySelectorAll('[data-dq-delete-artifact]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-dq-delete-artifact');
                if (!id) return;
                const result = await deleteToolArtifact(config, id);
                if ('error' in result) {
                    if (els.status) els.status.textContent = options.t('dqPacks.workspace.deleteFailed');
                    return;
                }
                artifacts = result.toolArtifacts;
                renderSavedList();
                if (els.status) els.status.textContent = options.t('dqPacks.workspace.deleted');
            });
        });
    }

    async function refreshWorkspace() {
        if (!config.enabled) {
            workspaceReady = false;
            if (els.status) els.status.textContent = options.t('dqPacks.workspace.loginRequired');
            renderSavedList();
            return;
        }
        const active = await fetchActiveWorkspace(config);
        if (!active) {
            workspaceReady = false;
            workspaceName = null;
            artifacts = [];
            if (els.status) els.status.textContent = options.t('dqPacks.workspace.needActive');
            renderSavedList();
            return;
        }
        workspaceReady = true;
        workspaceName = active.name;
        artifacts = active.toolArtifacts;
        if (els.status) {
            els.status.textContent = options.t('dqPacks.workspace.active', { name: workspaceName });
        }
        renderSavedList();
    }

    function bind() {
        els.region?.addEventListener('change', () => {
            region = normalizeDqRegionId(els.region?.value);
            renderPacks();
            options.onRegionChange?.(region);
        });
        els.saveBtn?.addEventListener('click', async () => {
            const name = els.name?.value?.trim() || '';
            if (!name) {
                if (els.status) els.status.textContent = options.t('dqPacks.workspace.nameRequired');
                return;
            }
            if (!workspaceReady) {
                if (els.status) els.status.textContent = options.t('dqPacks.workspace.needActive');
                return;
            }
            const result = await saveToolArtifact(config, {
                name,
                toolId: options.toolId,
                payload: options.getPayload(),
                region,
                kind: 'dq-config',
            });
            if ('error' in result) {
                if (els.status) {
                    els.status.textContent =
                        result.error === 'workspace_disabled'
                            ? options.t('dqPacks.workspace.needActive')
                            : options.t('dqPacks.workspace.saveFailed');
                }
                return;
            }
            artifacts = result.toolArtifacts;
            renderSavedList();
            if (els.status) {
                els.status.textContent = options.t('dqPacks.workspace.saved', {
                    name: result.toolArtifact?.name || name,
                });
            }
        });
    }

    fillRegionSelect();
    renderPacks();
    bind();
    void refreshWorkspace();

    return {
        getRegion: () => region,
        setRegion(next) {
            region = normalizeDqRegionId(next);
            if (els.region) els.region.value = region;
            renderPacks();
        },
        refresh: refreshWorkspace,
        rerenderLabels() {
            fillRegionSelect();
            renderPacks();
            renderSavedList();
            void refreshWorkspace();
        },
    };
}

/** @param {string} value */
function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

/** @param {string} value */
function escapeAttr(value) {
    return escapeHtml(value).replace(/'/g, '&#39;');
}

export { artifactsForTool };
