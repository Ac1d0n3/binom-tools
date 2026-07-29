/**
 * Shared Custom Stack Builder — layer/product chips for hub modal + advisory tool.
 */

export const CUSTOM_STACK_STORAGE_KEY = 'binom-governance-custom-stack';
export const STARTING_POINT_PRODUCT_KEY = 'binom-governance-starting-point-product';

/** @type {Record<string, string>} */
const STARTING_POINT_LABELS = {
    fabric: 'Microsoft Fabric',
    databricks: 'Databricks + Unity Catalog',
    snowflake: 'Snowflake',
    bigquery: 'BigQuery',
    dbt: 'dbt Governance Control Layer',
    multiple: 'Multiple platforms',
};

/**
 * Persist Starting-Point Decision product for soft influence on Stack Builder.
 * @param {string} productId
 */
export function writeStartingPointProduct(productId) {
    try {
        const id = String(productId || '').trim().toLowerCase();
        if (!id) {
            sessionStorage.removeItem(STARTING_POINT_PRODUCT_KEY);
            return;
        }
        sessionStorage.setItem(STARTING_POINT_PRODUCT_KEY, id);
    } catch {
        /* ignore quota / private mode */
    }
}

/**
 * @returns {string}
 */
export function readStartingPointProduct() {
    try {
        return String(sessionStorage.getItem(STARTING_POINT_PRODUCT_KEY) || '').trim().toLowerCase();
    } catch {
        return '';
    }
}

/**
 * Map Starting-Point Decision product → stack-builder chip ids to highlight.
 * @param {string} productId
 * @returns {string[]}
 */
export function preferredProductIdsForStartingPoint(productId) {
    const id = String(productId || '').trim().toLowerCase();
    if (!id || id === 'multiple') {
        return [];
    }

    /** @type {string[]} */
    const ids = [];

    if (id === 'dbt') {
        ids.push('dbt', 'dbt-cloud');
    } else if (id === 'bigquery') {
        ids.push('bigquery');
    } else if (id === 'snowflake') {
        STACK_LAYERS.forEach((layer) => {
            layer.products.forEach((product) => {
                if (product.id === 'snowflake' || product.tags.includes('snowflake-dbt')) {
                    ids.push(product.id);
                }
            });
        });
    } else {
        STACK_LAYERS.forEach((layer) => {
            layer.products.forEach((product) => {
                if (product.id === id || product.tags.includes(id)) {
                    ids.push(product.id);
                }
            });
        });
    }

    return [...new Set(ids)];
}

/**
 * Banner when a starting-point product should soft-filter the builder.
 * @param {string} productId
 * @param {'de'|'en'} [lang]
 * @returns {string}
 */
export function startingPointStackBanner(productId, lang = 'en') {
    const id = String(productId || '').trim().toLowerCase();
    if (!id) {
        return '';
    }
    const label = STARTING_POINT_LABELS[id] || id;
    if (id === 'multiple') {
        return lang === 'de'
            ? 'Starting-Point: mehrere Plattformen — Stack bewusst übergreifend wählen.'
            : 'Starting-Point: multiple platforms — choose the stack across platforms intentionally.';
    }
    return lang === 'de'
        ? `Starting-Point: ${label} — passende Produkte sind hervorgehoben.`
        : `Starting-Point: ${label} — matching products are highlighted.`;
}

export const STACK_LAYERS = [
    {
        id: 'ingest',
        label: { de: 'Ingest / CDC', en: 'Ingest / CDC' },
        products: [
            { id: 'fivetran', label: 'Fivetran', tags: [] },
            { id: 'airbyte', label: 'Airbyte', tags: ['opensource'] },
            { id: 'adf', label: 'Azure Data Factory', tags: ['fabric'] },
            { id: 'informatica', label: 'Informatica', tags: [] },
            { id: 'kafka', label: 'Kafka / Debezium', tags: ['opensource'] },
        ],
    },
    {
        id: 'storage',
        label: { de: 'Storage / Warehouse', en: 'Storage / Warehouse' },
        products: [
            { id: 'snowflake', label: 'Snowflake', tags: ['snowflake-dbt'] },
            { id: 'bigquery', label: 'BigQuery', tags: [] },
            { id: 'redshift', label: 'Redshift', tags: [] },
            { id: 'fabric-lakehouse', label: 'Fabric Lakehouse', tags: ['fabric'] },
            { id: 'databricks', label: 'Databricks', tags: ['databricks'] },
            { id: 'postgres', label: 'Postgres', tags: ['opensource'] },
        ],
    },
    {
        id: 'transform',
        label: { de: 'Transform', en: 'Transform' },
        products: [
            { id: 'dbt', label: 'dbt', tags: ['snowflake-dbt', 'opensource'] },
            { id: 'spark', label: 'Spark', tags: ['databricks', 'opensource'] },
            { id: 'dataflow', label: 'Dataflow', tags: [] },
            { id: 'fabric-pipelines', label: 'Fabric Data Factory', tags: ['fabric'] },
            { id: 'sqlmesh', label: 'SQLMesh', tags: ['opensource'] },
        ],
    },
    {
        id: 'bi',
        label: { de: 'BI / Semantic', en: 'BI / Semantic' },
        products: [
            { id: 'powerbi', label: 'Power BI', tags: ['fabric'] },
            { id: 'tableau', label: 'Tableau', tags: [] },
            { id: 'looker', label: 'Looker', tags: [] },
            { id: 'qlik', label: 'Qlik', tags: [] },
            { id: 'omni', label: 'Omni', tags: [] },
        ],
    },
    {
        id: 'catalog',
        label: { de: 'Catalog / Governance', en: 'Catalog / Governance' },
        products: [
            { id: 'purview', label: 'Microsoft Purview', tags: ['fabric'] },
            { id: 'unity-catalog', label: 'Unity Catalog', tags: ['databricks'] },
            { id: 'collibra', label: 'Collibra', tags: [] },
            { id: 'alation', label: 'Alation', tags: [] },
            { id: 'openmetadata', label: 'OpenMetadata', tags: ['opensource'] },
        ],
    },
    {
        id: 'orchestration',
        label: { de: 'Orchestration', en: 'Orchestration' },
        products: [
            { id: 'airflow', label: 'Airflow', tags: ['opensource'] },
            { id: 'dagster', label: 'Dagster', tags: ['opensource'] },
            { id: 'prefect', label: 'Prefect', tags: ['opensource'] },
            { id: 'fabric-orch', label: 'Fabric Pipelines', tags: ['fabric'] },
            { id: 'dbt-cloud', label: 'dbt Cloud jobs', tags: ['snowflake-dbt'] },
        ],
    },
];

const LAYER_FIELD_INDEX = {
    ingest: 1,
    storage: 2,
    transform: 3,
    bi: 4,
    catalog: 5,
    orchestration: 6,
};

const TAG_FIELD_INDEX = 7;

function locale() {
    return document.documentElement.lang === 'de' ? 'de' : 'en';
}

function emptySelection() {
    return STACK_LAYERS.reduce((acc, layer) => {
        acc[layer.id] = [];
        return acc;
    }, {});
}

export function readCustomStack() {
    try {
        const raw = sessionStorage.getItem(CUSTOM_STACK_STORAGE_KEY);
        if (!raw) {
            return emptySelection();
        }
        return normalizeSelection(JSON.parse(raw));
    } catch {
        return emptySelection();
    }
}

export function writeCustomStack(selection) {
    try {
        sessionStorage.setItem(CUSTOM_STACK_STORAGE_KEY, JSON.stringify(selection));
    } catch {
        // ignore storage failures
    }
}

export const SAVED_STACKS_STORAGE_KEY = 'binom-governance-saved-stacks';

/**
 * @param {unknown} raw
 * @returns {Record<string, string[]>}
 */
export function normalizeSelection(raw) {
    const base = emptySelection();
    if (!raw || typeof raw !== 'object') {
        return base;
    }
    STACK_LAYERS.forEach((layer) => {
        const values = Array.isArray(raw[layer.id]) ? raw[layer.id].map(String) : [];
        const allowed = new Set(layer.products.map((product) => product.id));
        base[layer.id] = values.filter((id) => allowed.has(id));
    });
    return base;
}

/**
 * @returns {Array<{ id: string, name: string, selection: Record<string, string[]>, updatedAt: string }>}
 */
export function readSavedStacksLocal() {
    try {
        const raw = localStorage.getItem(SAVED_STACKS_STORAGE_KEY);
        if (!raw) {
            return [];
        }
        const parsed = JSON.parse(raw);
        if (!Array.isArray(parsed)) {
            return [];
        }
        return parsed
            .filter((item) => item && typeof item === 'object' && typeof item.id === 'string' && typeof item.name === 'string')
            .map((item) => ({
                id: String(item.id),
                name: String(item.name).trim() || 'Stack',
                selection: normalizeSelection(item.selection),
                updatedAt: typeof item.updatedAt === 'string' ? item.updatedAt : new Date().toISOString(),
            }));
    } catch {
        return [];
    }
}

/**
 * @param {Array<{ id: string, name: string, selection: Record<string, string[]>, updatedAt: string }>} items
 */
export function writeSavedStacksLocal(items) {
    try {
        localStorage.setItem(SAVED_STACKS_STORAGE_KEY, JSON.stringify(items));
    } catch {
        // ignore storage failures
    }
}

/**
 * @param {string} name
 * @param {Record<string, string[]>} selection
 */
export function saveNamedStackLocal(name, selection) {
    const trimmed = String(name || '').trim();
    if (!trimmed) {
        return null;
    }
    const items = readSavedStacksLocal();
    const now = new Date().toISOString();
    const existing = items.find((item) => item.name.toLowerCase() === trimmed.toLowerCase());
    if (existing) {
        existing.name = trimmed;
        existing.selection = normalizeSelection(selection);
        existing.updatedAt = now;
        writeSavedStacksLocal(items);
        return existing;
    }
    const created = {
        id: `stack_${Date.now().toString(36)}_${Math.random().toString(36).slice(2, 8)}`,
        name: trimmed,
        selection: normalizeSelection(selection),
        updatedAt: now,
    };
    items.unshift(created);
    writeSavedStacksLocal(items.slice(0, 40));
    return created;
}

export function deleteNamedStackLocal(id) {
    writeSavedStacksLocal(readSavedStacksLocal().filter((item) => item.id !== id));
}

export function derivePlatformTags(selection) {
    const tags = new Set();
    STACK_LAYERS.forEach((layer) => {
        const selected = new Set(selection[layer.id] || []);
        layer.products.forEach((product) => {
            if (selected.has(product.id)) {
                (product.tags || []).forEach((tag) => tags.add(tag));
            }
        });
    });
    if (tags.size === 0) {
        tags.add('custom');
    }
    return Array.from(tags);
}

export function summarizeSelection(selection, lang = locale()) {
    const parts = [];
    STACK_LAYERS.forEach((layer) => {
        const selected = selection[layer.id] || [];
        if (selected.length === 0) {
            return;
        }
        const labels = selected.map((id) => layer.products.find((product) => product.id === id)?.label || id);
        parts.push(...labels);
    });
    if (parts.length === 0) {
        return lang === 'de' ? 'Eigener Stack' : 'Custom stack';
    }
    const head = parts.slice(0, 3).join(' + ');
    const more = parts.length > 3 ? ` +${parts.length - 3}` : '';
    return lang === 'de' ? `Eigener Stack · ${head}${more}` : `Custom stack · ${head}${more}`;
}

function productLabelsForLayer(layer, selectedIds) {
    return selectedIds
        .map((id) => layer.products.find((product) => product.id === id)?.label || id)
        .join(', ');
}

export function syncSelectionToToolFields(root, selection) {
    STACK_LAYERS.forEach((layer) => {
        const index = LAYER_FIELD_INDEX[layer.id];
        const field = root.querySelector(`[data-governance-tool-field][name="field_${index}"]`);
        if (field instanceof HTMLTextAreaElement) {
            field.value = productLabelsForLayer(layer, selection[layer.id] || []);
            field.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
    const tagField = root.querySelector(`[data-governance-tool-field][name="field_${TAG_FIELD_INDEX}"]`);
    if (tagField instanceof HTMLTextAreaElement) {
        tagField.value = derivePlatformTags(selection).join(', ');
        tagField.dispatchEvent(new Event('input', { bubbles: true }));
    }
}

function selectionFromToolFields(root) {
    const selection = emptySelection();
    STACK_LAYERS.forEach((layer) => {
        const index = LAYER_FIELD_INDEX[layer.id];
        const field = root.querySelector(`[data-governance-tool-field][name="field_${index}"]`);
        if (!(field instanceof HTMLTextAreaElement) || !field.value.trim()) {
            return;
        }
        const tokens = field.value.split(/[,;|/]+/).map((part) => part.trim().toLowerCase()).filter(Boolean);
        layer.products.forEach((product) => {
            if (tokens.includes(product.label.toLowerCase()) || tokens.includes(product.id)) {
                selection[layer.id].push(product.id);
            }
        });
    });
    return selection;
}

/**
 * @param {HTMLElement} host
 * @param {{
 *   selection?: Record<string, string[]>,
 *   onChange?: (selection: Record<string, string[]>) => void,
 *   compact?: boolean,
 *   context?: { orgContext?: string, regulationPressure?: string },
 *   preferredProductIds?: string[],
 *   contextBanner?: string,
 * }} options
 */
export function mountStackBuilder(host, options = {}) {
    if (!(host instanceof HTMLElement)) {
        return { getSelection: () => emptySelection(), setSelection: () => {}, setContext: () => {} };
    }

    let selection = options.selection ? { ...emptySelection(), ...options.selection } : readCustomStack();
    let preferredIds = new Set(Array.isArray(options.preferredProductIds) ? options.preferredProductIds : []);
    let contextBanner = typeof options.contextBanner === 'string' ? options.contextBanner : '';
    const lang = locale();

    const render = () => {
        const summary = summarizeSelection(selection, lang);
        const tags = derivePlatformTags(selection);
        host.replaceChildren();
        host.classList.add('stack-builder');
        if (options.compact) {
            host.classList.add('stack-builder--compact');
        }

        if (contextBanner) {
            const banner = document.createElement('p');
            banner.className = 'stack-builder__context-banner';
            banner.textContent = contextBanner;
            host.append(banner);
        }

        const head = document.createElement('div');
        head.className = 'stack-builder__head';
        const title = document.createElement('strong');
        title.textContent = lang === 'de' ? 'Produkte je Funktion wählen' : 'Choose products by function';
        const meta = document.createElement('span');
        meta.className = 'stack-builder__summary';
        meta.textContent = `${summary} · ${tags.join(', ')}`;
        head.append(title, meta);
        host.append(head);

        STACK_LAYERS.forEach((layer) => {
            const row = document.createElement('div');
            row.className = 'stack-builder__layer';
            row.dataset.stackLayer = layer.id;

            const label = document.createElement('span');
            label.className = 'stack-builder__layer-label';
            label.textContent = layer.label[lang] || layer.label.en;
            row.append(label);

            const chips = document.createElement('div');
            chips.className = 'stack-builder__chips';
            const products = [...layer.products].sort((a, b) => {
                const aPref = preferredIds.has(a.id) ? 0 : 1;
                const bPref = preferredIds.has(b.id) ? 0 : 1;
                return aPref - bPref;
            });
            products.forEach((product) => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'stack-builder__chip';
                chip.textContent = product.label;
                chip.setAttribute('aria-pressed', String((selection[layer.id] || []).includes(product.id)));
                if ((selection[layer.id] || []).includes(product.id)) {
                    chip.classList.add('stack-builder__chip--active');
                }
                if (preferredIds.has(product.id)) {
                    chip.classList.add('stack-builder__chip--preferred');
                    chip.title = lang === 'de' ? 'Zum Kontext empfohlen' : 'Recommended for context';
                } else if (preferredIds.size > 0) {
                    chip.classList.add('stack-builder__chip--muted');
                }
                chip.addEventListener('click', () => {
                    const current = new Set(selection[layer.id] || []);
                    if (current.has(product.id)) {
                        current.delete(product.id);
                    } else {
                        current.add(product.id);
                    }
                    selection = {
                        ...selection,
                        [layer.id]: Array.from(current),
                    };
                    writeCustomStack(selection);
                    options.onChange?.(selection);
                    render();
                });
                chips.append(chip);
            });
            row.append(chips);
            host.append(row);
        });
    };

    render();

    return {
        getSelection: () => selection,
        setSelection: (next) => {
            selection = { ...emptySelection(), ...next };
            writeCustomStack(selection);
            render();
            options.onChange?.(selection);
        },
        setContext: ({ preferredProductIds: nextPreferred, contextBanner: nextBanner } = {}) => {
            preferredIds = new Set(Array.isArray(nextPreferred) ? nextPreferred : []);
            contextBanner = typeof nextBanner === 'string' ? nextBanner : '';
            render();
        },
        syncFromToolFields: (root) => {
            selection = selectionFromToolFields(root);
            writeCustomStack(selection);
            render();
        },
    };
}
