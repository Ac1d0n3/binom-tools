import '../../../css/tools/meta-export-generator.css';
import { applyMetaExportLabels, t } from './labels.js';
import {
    buildSections,
    getPlatform,
    groupedPlatforms,
    normalizePlatformId,
} from './sql-builders.js';

const STORAGE_KEY = 'bn-tools:meta-export-platform:v1';

const app = document.getElementById('meta-export-generator-app');
if (!app) {
    throw new Error('Meta export root element not found');
}

const SECTION_BOXES = [
    { id: 'schemas', el: document.getElementById('meta-schemas-box'), titleKey: 'metaExport.section.schemas' },
    { id: 'tables', el: document.getElementById('meta-tables-box'), titleKey: 'metaExport.section.tables' },
    { id: 'columns', el: document.getElementById('meta-columns-box'), titleKey: 'metaExport.section.columns' },
    { id: 'access', el: document.getElementById('meta-access-box'), titleKey: 'metaExport.section.access' },
];

const els = {
    platform: /** @type {HTMLSelectElement|null} */ (app.querySelector('#meta-platform')),
    note: /** @type {HTMLElement|null} */ (app.querySelector('#meta-platform-note')),
    noteText: /** @type {HTMLElement|null} */ (app.querySelector('#meta-platform-note-text')),
};

/** @type {null | typeof import('../../playbooks/prism-init.js')} */
let prismApi = null;

async function ensurePrism() {
    if (!prismApi) {
        prismApi = await import('../../playbooks/prism-init.js');
    }
    return prismApi;
}

function readStoredPlatform() {
    try {
        return normalizePlatformId(localStorage.getItem(STORAGE_KEY) || 'snowflake');
    } catch {
        return 'snowflake';
    }
}

function initPlatformSelect() {
    if (!els.platform) return;
    els.platform.innerHTML = '';
    for (const group of groupedPlatforms()) {
        const optgroup = document.createElement('optgroup');
        optgroup.label = t(group.labelKey);
        optgroup.dataset.i18nGroup = group.labelKey;
        for (const platform of group.platforms) {
            const option = document.createElement('option');
            option.value = platform.id;
            option.textContent = platform.label;
            optgroup.appendChild(option);
        }
        els.platform.appendChild(optgroup);
    }
    els.platform.value = readStoredPlatform();
}

function refreshOptgroupLabels() {
    if (!els.platform) return;
    els.platform.querySelectorAll('optgroup').forEach((group) => {
        const key = group.dataset.i18nGroup;
        if (key) {
            group.label = t(key);
        }
    });
}

/** @param {string} platformId */
function languageForPlatform(platformId) {
    return platformId === 'mongodb' ? 'javascript' : 'sql';
}

async function render() {
    const platformId = normalizePlatformId(els.platform?.value || 'snowflake');
    try {
        localStorage.setItem(STORAGE_KEY, platformId);
    } catch {
        /* ignore */
    }

    const platform = getPlatform(platformId);
    if (els.note && els.noteText) {
        if (platform.notes) {
            els.note.hidden = false;
            els.noteText.textContent = platform.notes;
        } else {
            els.note.hidden = true;
            els.noteText.textContent = '';
        }
    }

    const map = Object.fromEntries(buildSections(platformId).map((s) => [s.id, s.query]));
    const language = languageForPlatform(platformId);
    const { setPlaybookCodeContent } = await ensurePrism();

    for (const box of SECTION_BOXES) {
        if (!box.el) continue;
        setPlaybookCodeContent(box.el, map[box.id] || '', {
            language,
            title: t(box.titleKey),
        });
    }
}

initPlatformSelect();
applyMetaExportLabels();
void render();

els.platform?.addEventListener('change', () => {
    void render();
});

window.addEventListener('binom-tools:locale', () => {
    applyMetaExportLabels();
    refreshOptgroupLabels();
    void render();
});

window.addEventListener('binom-tools:color-scheme', () => {
    void render();
});
