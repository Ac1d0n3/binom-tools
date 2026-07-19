import '../../../css/tools/discovery-canvas.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { mountTableCanvas } from '../discovery-shared/table-canvas.js';

const labels = mergeDiscoveryLabels({
    de: {
        'kpiDefinition.pageTitle': 'KPI Definition Card',
        'kpiDefinition.pageLead':
            'KPIs mit Formel, Grain, Filtern und Owner erfassen. Status tracken und Inventar plus Definitions-Backlog exportieren.',
        'kpiDefinition.howto.intro':
            'Für Woche 6 im First-Quarter-Plan: Kennzahlen sammeln, Synonyme deduplizieren und Konflikte sichtbar machen.',
        'kpiDefinition.howto.step1': 'KPI-Namen und Synonyme aus Reports und Interviews eintragen.',
        'kpiDefinition.howto.step2': 'Formel, Grain, Filter und Owner dokumentieren.',
        'kpiDefinition.howto.step3': 'Status setzen, dann Markdown kopieren/herunterladen und wo nötig ablegen.',
        'kpiDefinition.howto.tip':
            'Nichts wird im Tool gespeichert. Konflikte als Status „Konflikt“ belassen, bis ein Owner entscheidet.',
        'kpiDefinition.col.name': 'KPI',
        'kpiDefinition.col.synonyms': 'Synonyme',
        'kpiDefinition.col.formula': 'Formel',
        'kpiDefinition.col.grain': 'Grain',
        'kpiDefinition.col.filters': 'Filter',
        'kpiDefinition.col.owner': 'Owner',
        'kpiDefinition.col.source': 'Quelle / Report',
        'kpiDefinition.col.status': 'Status',
        'kpiDefinition.status.draft': 'Entwurf',
        'kpiDefinition.status.conflict': 'Konflikt',
        'kpiDefinition.status.agreed': 'Abgestimmt',
        'discovery.exportTitle': 'KPI-Inventar',
    },
    en: {
        'kpiDefinition.pageTitle': 'KPI Definition Card',
        'kpiDefinition.pageLead':
            'Capture KPIs with formula, grain, filters, and owner. Track status and export inventory plus definition backlog.',
        'kpiDefinition.howto.intro':
            'For week 6 in the first-quarter plan: collect metrics, dedupe synonyms, and surface conflicts.',
        'kpiDefinition.howto.step1': 'Add KPI names and synonyms from reports and interviews.',
        'kpiDefinition.howto.step2': 'Document formula, grain, filters, and owner.',
        'kpiDefinition.howto.step3': 'Set status, then copy/download Markdown and keep it where you need it.',
        'kpiDefinition.howto.tip':
            'Nothing is stored in the tool. Keep conflicts as “Conflict” until an owner decides.',
        'kpiDefinition.col.name': 'KPI',
        'kpiDefinition.col.synonyms': 'Synonyms',
        'kpiDefinition.col.formula': 'Formula',
        'kpiDefinition.col.grain': 'Grain',
        'kpiDefinition.col.filters': 'Filters',
        'kpiDefinition.col.owner': 'Owner',
        'kpiDefinition.col.source': 'Source / report',
        'kpiDefinition.col.status': 'Status',
        'kpiDefinition.status.draft': 'Draft',
        'kpiDefinition.status.conflict': 'Conflict',
        'kpiDefinition.status.agreed': 'Agreed',
        'discovery.exportTitle': 'KPI inventory',
    },
});

const { t, applyLabels } = createLabelApi(labels);

const statusOptions = [
    { value: 'draft', labelKey: 'kpiDefinition.status.draft' },
    { value: 'conflict', labelKey: 'kpiDefinition.status.conflict' },
    { value: 'agreed', labelKey: 'kpiDefinition.status.agreed' },
];

const app = document.getElementById('kpi-definition-app');
if (!app) {
    throw new Error('KPI definition root not found');
}

mountTableCanvas({
    root: app,
    legacyStorageKeys: ['bn-tools:kpi-definition:v1'],
    t,
    applyLabels,
    columns: [
        { id: 'name', labelKey: 'kpiDefinition.col.name', type: 'text' },
        { id: 'synonyms', labelKey: 'kpiDefinition.col.synonyms', type: 'text' },
        { id: 'formula', labelKey: 'kpiDefinition.col.formula', type: 'textarea' },
        { id: 'grain', labelKey: 'kpiDefinition.col.grain', type: 'text' },
        { id: 'filters', labelKey: 'kpiDefinition.col.filters', type: 'text' },
        { id: 'owner', labelKey: 'kpiDefinition.col.owner', type: 'text' },
        { id: 'source', labelKey: 'kpiDefinition.col.source', type: 'text' },
        { id: 'status', labelKey: 'kpiDefinition.col.status', type: 'select', options: statusOptions },
    ],
});
