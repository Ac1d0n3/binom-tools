/**
 * Profile Workspace toolArtifacts client for DQ generators.
 */

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * @typedef {Object} DqWorkspaceConfig
 * @property {string} [activeUrl]
 * @property {string} [storeUrl]
 * @property {string} [destroyUrlTemplate]
 * @property {boolean} [enabled]
 */

/**
 * @typedef {Object} DqToolArtifact
 * @property {string} id
 * @property {string} toolId
 * @property {string} name
 * @property {string} [kind]
 * @property {unknown} payload
 * @property {string | null} [region]
 * @property {string} [updatedAt]
 */

/**
 * @returns {DqWorkspaceConfig}
 */
export function readDqWorkspaceConfig(root = document) {
    const el = root.querySelector('[data-dq-workspace-config]');
    if (!el) {
        return { enabled: false };
    }
    try {
        const raw = el.getAttribute('data-dq-workspace-config') || '{}';
        const parsed = JSON.parse(raw);
        return {
            enabled: Boolean(parsed?.enabled && parsed?.activeUrl && parsed?.storeUrl),
            activeUrl: parsed?.activeUrl || '',
            storeUrl: parsed?.storeUrl || '',
            destroyUrlTemplate: parsed?.destroyUrlTemplate || '',
        };
    } catch {
        return { enabled: false };
    }
}

/**
 * @param {DqWorkspaceConfig} config
 * @returns {Promise<{ id: string, name: string, toolArtifacts: DqToolArtifact[] } | null>}
 */
export async function fetchActiveWorkspace(config) {
    if (!config.enabled || !config.activeUrl) return null;
    try {
        const response = await fetch(config.activeUrl, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });
        if (!response.ok) return null;
        const payload = await response.json();
        const workspace = payload?.workspace;
        if (!workspace?.id) return null;
        return {
            id: String(workspace.id),
            name: String(workspace.name || ''),
            toolArtifacts: Array.isArray(workspace.toolArtifacts) ? workspace.toolArtifacts : [],
        };
    } catch {
        return null;
    }
}

/**
 * @param {DqWorkspaceConfig} config
 * @param {{ name: string, toolId: string, payload: unknown, region?: string | null, kind?: string, id?: string }} input
 * @returns {Promise<{ toolArtifact: DqToolArtifact, toolArtifacts: DqToolArtifact[] } | { error: string }>}
 */
export async function saveToolArtifact(config, input) {
    if (!config.enabled || !config.storeUrl) {
        return { error: 'workspace_disabled' };
    }
    try {
        const response = await fetch(config.storeUrl, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                name: input.name,
                toolId: input.toolId,
                payload: input.payload,
                region: input.region ?? null,
                kind: input.kind ?? 'dq-config',
                id: input.id ?? undefined,
            }),
        });
        const payload = await response.json().catch(() => ({}));
        if (!response.ok) {
            return { error: String(payload?.error || 'save_failed') };
        }
        return {
            toolArtifact: payload.toolArtifact,
            toolArtifacts: Array.isArray(payload.toolArtifacts) ? payload.toolArtifacts : [],
        };
    } catch {
        return { error: 'save_failed' };
    }
}

/**
 * @param {DqWorkspaceConfig} config
 * @param {string} artifactId
 * @returns {Promise<{ toolArtifacts: DqToolArtifact[] } | { error: string }>}
 */
export async function deleteToolArtifact(config, artifactId) {
    if (!config.enabled || !config.destroyUrlTemplate) {
        return { error: 'workspace_disabled' };
    }
    const url = config.destroyUrlTemplate.replace('__ID__', encodeURIComponent(artifactId));
    try {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
        const payload = await response.json().catch(() => ({}));
        if (!response.ok) {
            return { error: String(payload?.error || 'delete_failed') };
        }
        return {
            toolArtifacts: Array.isArray(payload.toolArtifacts) ? payload.toolArtifacts : [],
        };
    } catch {
        return { error: 'delete_failed' };
    }
}

/**
 * @param {DqToolArtifact[]} artifacts
 * @param {string} toolId
 * @returns {DqToolArtifact[]}
 */
export function artifactsForTool(artifacts, toolId) {
    return artifacts
        .filter((item) => item && item.toolId === toolId)
        .sort((a, b) => String(b.updatedAt || '').localeCompare(String(a.updatedAt || '')));
}
