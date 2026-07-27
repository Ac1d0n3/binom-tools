/**
 * Shared Custom Stack Builder — layer/product chips for hub modal + advisory tool.
 */

export const CUSTOM_STACK_STORAGE_KEY = 'binom-governance-custom-stack';

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
        const parsed = JSON.parse(raw);
        const base = emptySelection();
        STACK_LAYERS.forEach((layer) => {
            const values = Array.isArray(parsed?.[layer.id]) ? parsed[layer.id].map(String) : [];
            const allowed = new Set(layer.products.map((product) => product.id));
            base[layer.id] = values.filter((id) => allowed.has(id));
        });
        return base;
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
 * }} options
 */
export function mountStackBuilder(host, options = {}) {
    if (!(host instanceof HTMLElement)) {
        return { getSelection: () => emptySelection(), setSelection: () => {} };
    }

    let selection = options.selection ? { ...emptySelection(), ...options.selection } : readCustomStack();
    const lang = locale();

    const render = () => {
        const summary = summarizeSelection(selection, lang);
        const tags = derivePlatformTags(selection);
        host.replaceChildren();
        host.classList.add('stack-builder');
        if (options.compact) {
            host.classList.add('stack-builder--compact');
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
            layer.products.forEach((product) => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'stack-builder__chip';
                chip.textContent = product.label;
                chip.setAttribute('aria-pressed', String((selection[layer.id] || []).includes(product.id)));
                if ((selection[layer.id] || []).includes(product.id)) {
                    chip.classList.add('stack-builder__chip--active');
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
        syncFromToolFields: (root) => {
            selection = selectionFromToolFields(root);
            writeCustomStack(selection);
            render();
        },
    };
}
