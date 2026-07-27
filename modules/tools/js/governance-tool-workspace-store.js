const STORAGE_KEY = 'bn-tools:governance-tool-workspace:v1';

function nowIso() {
    return new Date().toISOString();
}

function newId(prefix) {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return `${prefix}_${crypto.randomUUID()}`;
    }

    return `${prefix}_${Date.now()}_${Math.random().toString(36).slice(2, 9)}`;
}

export function emptyGovernanceToolWorkspace() {
    return {
        version: 1,
        records: [],
        updatedAt: nowIso(),
    };
}

function normalizeField(value) {
    const data = value && typeof value === 'object' ? value : {};
    return {
        label: String(data.label || ''),
        labelDe: String(data.labelDe || data.label || ''),
        labelEn: String(data.labelEn || data.label || ''),
        help: String(data.help || ''),
        value: String(data.value || ''),
    };
}

function normalizeRecord(value) {
    const data = value && typeof value === 'object' ? value : {};
    const timestamp = nowIso();

    return {
        id: String(data.id || newId('governance')),
        toolId: String(data.toolId || 'governance-tool'),
        title: String(data.title || ''),
        note: String(data.note || ''),
        fields: Array.isArray(data.fields) ? data.fields.map(normalizeField) : [],
        reportMarkdown: String(data.reportMarkdown || ''),
        score: Number.isFinite(Number(data.score)) ? Number(data.score) : 0,
        createdAt: String(data.createdAt || timestamp),
        updatedAt: String(data.updatedAt || timestamp),
    };
}

export function normalizeGovernanceToolWorkspace(value) {
    if (!value || typeof value !== 'object') {
        return emptyGovernanceToolWorkspace();
    }

    return {
        version: 1,
        records: Array.isArray(value.records) ? value.records.filter(Boolean).map(normalizeRecord) : [],
        updatedAt: String(value.updatedAt || nowIso()),
    };
}

export function loadGovernanceToolWorkspace() {
    if (typeof localStorage === 'undefined') {
        return emptyGovernanceToolWorkspace();
    }

    try {
        return normalizeGovernanceToolWorkspace(JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null'));
    } catch {
        return emptyGovernanceToolWorkspace();
    }
}

export function saveGovernanceToolWorkspace(workspace) {
    const next = normalizeGovernanceToolWorkspace({
        ...workspace,
        updatedAt: nowIso(),
    });

    if (typeof localStorage !== 'undefined') {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(next));
        window.dispatchEvent(new CustomEvent('binom-tools:governance-tool-workspace', { detail: { workspace: next } }));
    }

    return next;
}

export function recordsForTool(toolId) {
    return loadGovernanceToolWorkspace()
        .records
        .filter((record) => record.toolId === toolId)
        .sort((left, right) => String(right.updatedAt).localeCompare(String(left.updatedAt)));
}

export function upsertGovernanceToolRecord(record) {
    const workspace = loadGovernanceToolWorkspace();
    const timestamp = nowIso();
    const id = String(record.id || newId('governance'));
    const existing = workspace.records.find((item) => item.id === id);
    const next = normalizeRecord({
        ...existing,
        ...record,
        id,
        createdAt: existing?.createdAt || record.createdAt || timestamp,
        updatedAt: timestamp,
    });

    workspace.records = [next, ...workspace.records.filter((item) => item.id !== id)];
    saveGovernanceToolWorkspace(workspace);
    return next;
}

export function deleteGovernanceToolRecord(toolId, id) {
    const workspace = loadGovernanceToolWorkspace();
    workspace.records = workspace.records.filter((record) => !(record.toolId === toolId && record.id === id));
    saveGovernanceToolWorkspace(workspace);
}
