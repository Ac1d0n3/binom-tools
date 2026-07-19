import '../../../css/tools/discovery-canvas.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { mountTableCanvas } from '../discovery-shared/table-canvas.js';

const labels = mergeDiscoveryLabels({
    de: {
        'impactEffort.pageTitle': 'Impact–Effort Prioritizer',
        'impactEffort.pageLead':
            'Initiativen nach Wirkung und Aufwand bewerten, in einer 2×2-Matrix sehen und den Pilot-Kandidaten markieren.',
        'impactEffort.howto.intro':
            'Für Woche 9: aus Findings Initiativen machen, scoren und mit Stakeholdern den Pilot wählen.',
        'impactEffort.howto.step1': 'Initiativen mit kurzer Beschreibung anlegen.',
        'impactEffort.howto.step2': 'Impact und Aufwand von 1–5 setzen; optional Pilot markieren.',
        'impactEffort.howto.step3': 'Matrix prüfen, dann Markdown kopieren/herunterladen und wo nötig ablegen.',
        'impactEffort.howto.tip':
            'Nichts wird im Tool gespeichert. Lieber ein kleiner Pilot, der ein Muster beweist.',
        'impactEffort.col.name': 'Initiative',
        'impactEffort.col.impact': 'Impact (1–5)',
        'impactEffort.col.effort': 'Aufwand (1–5)',
        'impactEffort.col.notes': 'Notizen',
        'impactEffort.col.pilot': 'Pilot',
        'impactEffort.matrix.xlabel': 'Aufwand →',
        'impactEffort.matrix.ylabel': 'Impact →',
        'impactEffort.matrix.quickWins': 'Quick Wins',
        'impactEffort.matrix.major': 'Große Wetten',
        'impactEffort.matrix.fill': 'Auffüllen',
        'impactEffort.matrix.avoid': 'Vermeiden / später',
        'discovery.exportTitle': 'Priorisierungsmatrix',
    },
    en: {
        'impactEffort.pageTitle': 'Impact–Effort Prioritizer',
        'impactEffort.pageLead':
            'Score initiatives by impact and effort, place them on a 2×2 matrix, and mark the pilot candidate.',
        'impactEffort.howto.intro':
            'For week 9: turn findings into initiatives, score them, and agree the pilot with stakeholders.',
        'impactEffort.howto.step1': 'Add initiatives with a short description.',
        'impactEffort.howto.step2': 'Set impact and effort from 1–5; optionally mark the pilot.',
        'impactEffort.howto.step3': 'Review the matrix, then copy/download Markdown and keep it where you need it.',
        'impactEffort.howto.tip':
            'Nothing is stored in the tool. Prefer a small pilot that proves a pattern.',
        'impactEffort.col.name': 'Initiative',
        'impactEffort.col.impact': 'Impact (1–5)',
        'impactEffort.col.effort': 'Effort (1–5)',
        'impactEffort.col.notes': 'Notes',
        'impactEffort.col.pilot': 'Pilot',
        'impactEffort.matrix.xlabel': 'Effort →',
        'impactEffort.matrix.ylabel': 'Impact →',
        'impactEffort.matrix.quickWins': 'Quick wins',
        'impactEffort.matrix.major': 'Major projects',
        'impactEffort.matrix.fill': 'Fill-ins',
        'impactEffort.matrix.avoid': 'Avoid / later',
        'discovery.exportTitle': 'Prioritization matrix',
    },
});

const { t, applyLabels } = createLabelApi(labels);

const app = document.getElementById('impact-effort-app');
if (!app) {
    throw new Error('Impact–effort root not found');
}

/**
 * @param {number} value
 * @param {number} fallback
 */
function clampScore(value, fallback = 3) {
    const n = Number(value);
    if (!Number.isFinite(n)) return fallback;
    return Math.min(5, Math.max(1, Math.round(n)));
}

/**
 * @param {HTMLElement} host
 * @param {Record<string, unknown>[]} rows
 */
function renderMatrix(host, rows) {
    /** @type {Record<string, Record<string, unknown>[]>} */
    const buckets = {
        quick: [],
        major: [],
        fill: [],
        avoid: [],
    };

    for (const row of rows) {
        const impact = clampScore(row.impact);
        const effort = clampScore(row.effort);
        const highImpact = impact >= 3;
        const lowEffort = effort <= 3;
        if (highImpact && lowEffort) buckets.quick.push(row);
        else if (highImpact && !lowEffort) buckets.major.push(row);
        else if (!highImpact && lowEffort) buckets.fill.push(row);
        else buckets.avoid.push(row);
    }

    /**
     * @param {string} key
     * @param {string} titleKey
     * @param {string} className
     */
    function cell(key, titleKey, className) {
        const el = document.createElement('div');
        el.className = `discovery-matrix__cell ${className}`;
        const title = document.createElement('div');
        title.className = 'discovery-matrix__cell-title';
        title.dataset.i18n = titleKey;
        title.textContent = t(titleKey);
        el.appendChild(title);
        for (const row of buckets[key]) {
            const chip = document.createElement('span');
            chip.className = 'discovery-matrix__chip';
            if (row.pilot) chip.classList.add('discovery-matrix__chip--pilot');
            chip.textContent = String(row.name || '—');
            el.appendChild(chip);
        }
        return el;
    }

    host.innerHTML = '';
    const matrix = document.createElement('div');
    matrix.className = 'discovery-matrix';
    matrix.setAttribute('aria-label', t('impactEffort.pageTitle'));

    const corner = document.createElement('div');
    corner.className = 'discovery-matrix__corner';
    matrix.appendChild(corner);

    const xlabel = document.createElement('div');
    xlabel.className = 'discovery-matrix__xlabel';
    xlabel.dataset.i18n = 'impactEffort.matrix.xlabel';
    xlabel.textContent = t('impactEffort.matrix.xlabel');
    matrix.appendChild(xlabel);

    const ylabel = document.createElement('div');
    ylabel.className = 'discovery-matrix__ylabel';
    ylabel.dataset.i18n = 'impactEffort.matrix.ylabel';
    ylabel.textContent = t('impactEffort.matrix.ylabel');
    matrix.appendChild(ylabel);

    // Grid placement: row2 col2 = high impact / low effort (quick)
    // row2 col3 = high impact / high effort (major)
    // row3 col2 = low impact / low effort (fill)
    // row3 col3 = low impact / high effort (avoid)
    const quick = cell('quick', 'impactEffort.matrix.quickWins', 'discovery-matrix__cell--quick');
    quick.style.gridColumn = '2';
    quick.style.gridRow = '2';
    const major = cell('major', 'impactEffort.matrix.major', 'discovery-matrix__cell--major');
    major.style.gridColumn = '3';
    major.style.gridRow = '2';
    const fill = cell('fill', 'impactEffort.matrix.fill', 'discovery-matrix__cell--fill');
    fill.style.gridColumn = '2';
    fill.style.gridRow = '3';
    const avoid = cell('avoid', 'impactEffort.matrix.avoid', 'discovery-matrix__cell--avoid');
    avoid.style.gridColumn = '3';
    avoid.style.gridRow = '3';

    matrix.appendChild(quick);
    matrix.appendChild(major);
    matrix.appendChild(fill);
    matrix.appendChild(avoid);
    host.appendChild(matrix);
}

mountTableCanvas({
    root: app,
    legacyStorageKeys: ['bn-tools:impact-effort:v1'],
    t,
    applyLabels,
    columns: [
        { id: 'name', labelKey: 'impactEffort.col.name', type: 'text' },
        { id: 'impact', labelKey: 'impactEffort.col.impact', type: 'number', min: 1, max: 5 },
        { id: 'effort', labelKey: 'impactEffort.col.effort', type: 'number', min: 1, max: 5 },
        { id: 'notes', labelKey: 'impactEffort.col.notes', type: 'textarea' },
        { id: 'pilot', labelKey: 'impactEffort.col.pilot', type: 'checkbox' },
    ],
    renderExtra: (host, rows) => {
        renderMatrix(host, rows);
    },
});
