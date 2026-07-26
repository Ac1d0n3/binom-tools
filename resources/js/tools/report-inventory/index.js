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
        'reportInventory.placeholder.report': 'z. B. Executive Finance Dashboard',
        'reportInventory.placeholder.owner': 'z. B. CFO Office, Sales Ops Lead',
        'reportInventory.placeholder.tool': 'z. B. Power BI, Qlik, Tableau, Excel',
        'reportInventory.placeholder.cadence': 'z. B. täglich 06:00, monatlich M+3',
        'reportInventory.placeholder.question': 'z. B. Sind Umsatz und offene Forderungen im Zielkorridor?',
        'reportInventory.help.report': 'Name des Reports oder Dashboards, wie ihn Nutzer wirklich kennen.',
        'reportInventory.help.owner': 'Fachlicher Owner, der Inhalt, Nutzung und Änderungen verantwortet.',
        'reportInventory.help.tool': 'BI-/Reporting-Tool oder Schattenquelle, damit Migration und Scope sichtbar werden.',
        'reportInventory.help.cadence': 'Wie oft wird der Report genutzt oder aktualisiert? Das steuert Priorität und DQ-Anforderungen.',
        'reportInventory.help.question': 'Welche Geschäftsentscheidung beantwortet der Report? Ohne Frage ist er oft nur Bestand.',
        'reportInventory.help.critical': 'Markiere Reports, die Vorstand, Finance Close, Operations oder Compliance direkt steuern.',
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
        'reportInventory.placeholder.report': 'e.g. Executive Finance Dashboard',
        'reportInventory.placeholder.owner': 'e.g. CFO Office, Sales Ops Lead',
        'reportInventory.placeholder.tool': 'e.g. Power BI, Qlik, Tableau, Excel',
        'reportInventory.placeholder.cadence': 'e.g. daily 06:00, monthly M+3',
        'reportInventory.placeholder.question': 'e.g. Are revenue and open receivables within target?',
        'reportInventory.help.report': 'Report or dashboard name as users actually know it.',
        'reportInventory.help.owner': 'Business owner accountable for content, usage, and changes.',
        'reportInventory.help.tool': 'BI/reporting tool or shadow source so migration and scope become visible.',
        'reportInventory.help.cadence': 'How often is the report used or refreshed? This drives priority and DQ needs.',
        'reportInventory.help.question': 'Which business decision does the report answer? Without a question, it is often just inventory.',
        'reportInventory.help.critical': 'Mark reports that directly drive board, finance close, operations, or compliance.',
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
        { id: 'report', labelKey: 'reportInventory.col.report', type: 'text', placeholderKey: 'reportInventory.placeholder.report', helpKey: 'reportInventory.help.report' },
        { id: 'owner', labelKey: 'reportInventory.col.owner', type: 'text', placeholderKey: 'reportInventory.placeholder.owner', helpKey: 'reportInventory.help.owner' },
        { id: 'tool', labelKey: 'reportInventory.col.tool', type: 'text', placeholderKey: 'reportInventory.placeholder.tool', helpKey: 'reportInventory.help.tool' },
        { id: 'cadence', labelKey: 'reportInventory.col.cadence', type: 'text', placeholderKey: 'reportInventory.placeholder.cadence', helpKey: 'reportInventory.help.cadence' },
        { id: 'question', labelKey: 'reportInventory.col.question', type: 'textarea', placeholderKey: 'reportInventory.placeholder.question', helpKey: 'reportInventory.help.question' },
        { id: 'critical', labelKey: 'reportInventory.col.critical', type: 'checkbox', helpKey: 'reportInventory.help.critical' },
    ],
});
