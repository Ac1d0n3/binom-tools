const STORAGE_KEY = 'bn-tools:kpi-workspace:v1';

/**
 * @typedef {{
 *   id: string,
 *   toolId: 'kpi-requirements-intake',
 *   title: string,
 *   note: string,
 *   fields: Array<{ label: string, labelDe?: string, labelEn?: string, help?: string, value: string }>,
 *   acceptedAt?: string,
 *   updatedAt: string,
 *   createdAt: string,
 * }} KpiIntakeRecord
 *
 * @typedef {{
 *   id: string,
 *   name: string,
 *   synonyms: string,
 *   formula: string,
 *   grain: string,
 *   filters: string,
 *   owner: string,
 *   source: string,
 *   status: 'draft'|'conflict'|'agreed',
 *   intakeId?: string,
 *   updatedAt?: string,
 * }} KpiRegisterRow
 *
 * @typedef {{
 *   version: 1,
 *   intakes: KpiIntakeRecord[],
 *   registerRows: KpiRegisterRow[],
 *   updatedAt: string,
 * }} KpiWorkspace
 */

function nowIso() {
    return new Date().toISOString();
}

function newId(prefix) {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return `${prefix}_${crypto.randomUUID()}`;
    }
    return `${prefix}_${Date.now()}_${Math.random().toString(36).slice(2, 9)}`;
}

/** @returns {KpiWorkspace} */
export function emptyKpiWorkspace() {
    return {
        version: 1,
        intakes: [],
        registerRows: [],
        updatedAt: nowIso(),
    };
}

/**
 * @param {unknown} value
 * @returns {KpiWorkspace}
 */
export function normalizeKpiWorkspace(value) {
    if (!value || typeof value !== 'object') {
        return emptyKpiWorkspace();
    }

    const data = /** @type {Record<string, unknown>} */ (value);
    return {
        version: 1,
        intakes: Array.isArray(data.intakes) ? data.intakes.filter(Boolean).map(normalizeIntake) : [],
        registerRows: Array.isArray(data.registerRows) ? data.registerRows.filter(Boolean).map(normalizeRegisterRow) : [],
        updatedAt: String(data.updatedAt || nowIso()),
    };
}

/**
 * @param {unknown} value
 * @returns {KpiIntakeRecord}
 */
function normalizeIntake(value) {
    const data = /** @type {Record<string, unknown>} */ (value || {});
    const timestamp = nowIso();
    return {
        id: String(data.id || newId('intake')),
        toolId: 'kpi-requirements-intake',
        title: String(data.title || ''),
        note: String(data.note || ''),
        fields: Array.isArray(data.fields)
            ? data.fields.map((field) => {
                const row = /** @type {Record<string, unknown>} */ (field || {});
                return {
                    label: String(row.label || ''),
                    labelDe: String(row.labelDe || row.label || ''),
                    labelEn: String(row.labelEn || row.label || ''),
                    help: String(row.help || ''),
                    value: String(row.value || ''),
                };
            })
            : [],
        acceptedAt: data.acceptedAt ? String(data.acceptedAt) : undefined,
        createdAt: String(data.createdAt || timestamp),
        updatedAt: String(data.updatedAt || timestamp),
    };
}

/**
 * @param {unknown} value
 * @returns {KpiRegisterRow}
 */
function normalizeRegisterRow(value) {
    const data = /** @type {Record<string, unknown>} */ (value || {});
    const status = String(data.status || 'draft');
    return {
        id: String(data.id || newId('kpi')),
        name: String(data.name || ''),
        synonyms: String(data.synonyms || ''),
        formula: String(data.formula || ''),
        grain: String(data.grain || ''),
        filters: String(data.filters || ''),
        owner: String(data.owner || ''),
        source: String(data.source || ''),
        status: status === 'agreed' || status === 'conflict' ? status : 'draft',
        intakeId: data.intakeId ? String(data.intakeId) : undefined,
        updatedAt: data.updatedAt ? String(data.updatedAt) : undefined,
    };
}

/** @returns {KpiWorkspace} */
export function loadKpiWorkspace() {
    if (typeof localStorage === 'undefined') {
        return emptyKpiWorkspace();
    }

    try {
        return normalizeKpiWorkspace(JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null'));
    } catch {
        return emptyKpiWorkspace();
    }
}

/** @param {KpiWorkspace} workspace */
export function saveKpiWorkspace(workspace) {
    if (typeof localStorage === 'undefined') {
        return normalizeKpiWorkspace(workspace);
    }

    const next = normalizeKpiWorkspace({
        ...workspace,
        updatedAt: nowIso(),
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(next));
    window.dispatchEvent(new CustomEvent('binom-tools:kpi-workspace', { detail: { workspace: next } }));
    return next;
}

/**
 * @param {Partial<KpiIntakeRecord>} record
 * @returns {KpiIntakeRecord}
 */
export function upsertKpiIntake(record) {
    const workspace = loadKpiWorkspace();
    const timestamp = nowIso();
    const id = String(record.id || newId('intake'));
    const existing = workspace.intakes.find((item) => item.id === id);
    const next = normalizeIntake({
        ...existing,
        ...record,
        id,
        createdAt: existing?.createdAt || record.createdAt || timestamp,
        updatedAt: timestamp,
    });

    workspace.intakes = [next, ...workspace.intakes.filter((item) => item.id !== id)];
    saveKpiWorkspace(workspace);
    return next;
}

/**
 * @param {string} id
 */
export function deleteKpiIntake(id) {
    const workspace = loadKpiWorkspace();
    workspace.intakes = workspace.intakes.filter((item) => item.id !== id);
    saveKpiWorkspace(workspace);
}

/**
 * @param {KpiIntakeRecord} intake
 * @returns {KpiRegisterRow}
 */
export function buildRegisterRowFromIntake(intake) {
    const byEn = Object.fromEntries(intake.fields.map((field) => [field.labelEn || field.label, field.value]));
    const hasCore = Boolean(byEn.KPI && byEn['Formula'] && byEn.Grain && byEn['Owner / approver']);
    const sourceParts = [byEn['Decision supported'], intake.note].filter(Boolean);
    const filterParts = [
        byEn['Time logic'] ? `Zeitlogik: ${byEn['Time logic']}` : '',
        byEn.Dimensions ? `Dimensionen: ${byEn.Dimensions}` : '',
        byEn['Business question'] ? `Geschäftsfrage: ${byEn['Business question']}` : '',
        byEn['Acceptance example'] ? `Beispiel: ${byEn['Acceptance example']}` : '',
    ].filter(Boolean);

    return {
        id: `kpi_from_${intake.id}`,
        name: byEn.KPI || intake.title || '',
        synonyms: '',
        formula: byEn.Formula || '',
        grain: byEn.Grain || '',
        filters: filterParts.join(' | '),
        owner: byEn['Owner / approver'] || '',
        source: sourceParts.join(' | '),
        status: hasCore ? 'agreed' : 'draft',
        intakeId: intake.id,
        updatedAt: nowIso(),
    };
}

/**
 * @param {KpiIntakeRecord} intake
 * @returns {KpiRegisterRow}
 */
export function acceptKpiIntake(intake) {
    const workspace = loadKpiWorkspace();
    const row = buildRegisterRowFromIntake(intake);
    workspace.registerRows = [row, ...workspace.registerRows.filter((item) => item.intakeId !== intake.id && item.id !== row.id)];
    workspace.intakes = workspace.intakes.map((item) => item.id === intake.id ? { ...item, acceptedAt: nowIso(), updatedAt: nowIso() } : item);
    saveKpiWorkspace(workspace);
    return row;
}

/**
 * @param {KpiRegisterRow[]} rows
 */
export function replaceKpiRegisterRows(rows) {
    const workspace = loadKpiWorkspace();
    workspace.registerRows = rows.map(normalizeRegisterRow);
    saveKpiWorkspace(workspace);
}
