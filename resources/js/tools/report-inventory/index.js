import '../../../css/tools/discovery-canvas.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { mountTableCanvas } from '../discovery-shared/table-canvas.js';

const labels = mergeDiscoveryLabels({
    de: {
        'reportInventory.pageTitle': 'Report Inventory Canvas',
        'reportInventory.pageLead':
            'Aktive Reports und Dashboards erfassen — Owner, Tool, Rhythmus und Geschäftsfrage. Export als Markdown/CSV oder Download — für Notizen, Wiki oder Plan.',
        'reportInventory.howto.intro':
            'Baue ein schlankes Report-Inventar für den First-Quarter-Plan (Woche 2). Lieber Belege als Annahmen.',
        'reportInventory.howto.step1': 'Pro aktivem Report eine Zeile anlegen.',
        'reportInventory.howto.step2': 'Owner, BI-Tool, Aktualisierungsrhythmus und Geschäftsfrage ausfüllen.',
        'reportInventory.howto.step3': 'Markdown/CSV kopieren oder herunterladen — für Notizen, Wiki, Tickets oder den Sprint Planner.',
        'reportInventory.howto.tip':
            'Nichts wird im Tool gespeichert. Ergebnis übernehmen (Copy/Download); Persistenz liegt bei dir (Doku/Plan). Schatten-Excel und verwaiste Dashboards mit aufnehmen.',
        'reportInventory.col.report': 'Report',
        'reportInventory.col.owner': 'Owner',
        'reportInventory.col.tool': 'Tool',
        'reportInventory.col.cadence': 'Rhythmus',
        'reportInventory.col.question': 'Geschäftsfrage',
        'reportInventory.col.critical': 'Kritisch',
        'discovery.exportTitle': 'Report-Inventar',
    },
    en: {
        'reportInventory.pageTitle': 'Report Inventory Canvas',
        'reportInventory.pageLead':
            'Capture active reports and dashboards — owner, tool, cadence, and business question. Export Markdown/CSV or download — for notes, wiki, or a plan.',
        'reportInventory.howto.intro':
            'Build a lean report inventory for the first-quarter plan (week 2). Prefer evidence over assumptions.',
        'reportInventory.howto.step1': 'Add one row per active report.',
        'reportInventory.howto.step2': 'Fill owner, BI tool, refresh cadence, and business question.',
        'reportInventory.howto.step3': 'Copy or download Markdown/CSV — for notes, wiki, tickets, or the Sprint Planner.',
        'reportInventory.howto.tip':
            'Nothing is stored in the tool. Transfer the result (copy/download); persistence is yours (docs/plan). Include shadow Excel and orphaned dashboards.',
        'reportInventory.col.report': 'Report',
        'reportInventory.col.owner': 'Owner',
        'reportInventory.col.tool': 'Tool',
        'reportInventory.col.cadence': 'Cadence',
        'reportInventory.col.question': 'Business question',
        'reportInventory.col.critical': 'Critical',
        'discovery.exportTitle': 'Report inventory',
    },
});

const { t, applyLabels } = createLabelApi(labels);

const app = document.getElementById('report-inventory-app');
if (!app) {
    throw new Error('Report inventory root not found');
}

mountTableCanvas({
    root: app,
    legacyStorageKeys: ['bn-tools:report-inventory:v1'],
    t,
    applyLabels,
    columns: [
        { id: 'report', labelKey: 'reportInventory.col.report', type: 'text' },
        { id: 'owner', labelKey: 'reportInventory.col.owner', type: 'text' },
        { id: 'tool', labelKey: 'reportInventory.col.tool', type: 'text' },
        { id: 'cadence', labelKey: 'reportInventory.col.cadence', type: 'text' },
        { id: 'question', labelKey: 'reportInventory.col.question', type: 'textarea' },
        { id: 'critical', labelKey: 'reportInventory.col.critical', type: 'checkbox' },
    ],
});
