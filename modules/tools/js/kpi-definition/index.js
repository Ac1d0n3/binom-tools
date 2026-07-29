import '../../css/discovery-canvas.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { mountTableCanvas } from '../discovery-shared/table-canvas.js';
import { loadKpiWorkspace, replaceKpiRegisterRows } from '../kpi-workspace-store.js';

const labels = mergeDiscoveryLabels({
    de: {
        'kpiDefinition.pageTitle': 'KPI Definition Card',
        'kpiDefinition.pageLead':
            'Ohne Grain und Owner bleibt jede Report-Kennzahl unklar. Erfasse KPIs mit Formel, Grain, Filtern und Owner — dann Inventar und Definitions-Backlog exportieren.',
        'kpiDefinition.howto.intro':
            'Das KPI-Register ist die Tabelle nach dem Intake: geprüfte KPI-Klärungen landen hier als Zeilen, weitere KPIs kannst du direkt ergänzen.',
        'kpiDefinition.howto.step1': 'KPI-Namen und Synonyme aus Reports und Interviews eintragen.',
        'kpiDefinition.howto.step2': 'Formel, Grain, Filter und Owner dokumentieren.',
        'kpiDefinition.howto.step3': 'Status setzen, dann Report prüfen, kopieren, laden oder in den Plan übernehmen.',
        'kpiDefinition.howto.tip':
            'KPI-Intakes werden im lokalen Workspace mit dem Register verbunden. Beim späteren DB-Store bleibt dieselbe Struktur nutzbar.',
        'kpiDefinition.playbookLink':
            'KPI Definition Playbook — Grain, Owner, Versioning',
        'kpiDefinition.inventoryPlaybookLink':
            'Vom Report-Inventar zur Trusted Metric',
        'kpiDefinition.semanticPlaybookLink':
            'Semantic Layer vs Measure im Report',
        'kpiDefinition.trustedMetricsLink': 'Lernpfad: Trusted Metrics',
        'kpiDefinition.col.name': 'KPI',
        'kpiDefinition.col.synonyms': 'Synonyme',
        'kpiDefinition.col.formula': 'Formel',
        'kpiDefinition.col.grain': 'Grain',
        'kpiDefinition.col.filters': 'Filter',
        'kpiDefinition.col.owner': 'Owner',
        'kpiDefinition.col.source': 'Quelle / Report',
        'kpiDefinition.col.status': 'Status',
        'kpiDefinition.placeholder.name': 'z. B. Net Revenue, Churn Rate, Offene Forderungen',
        'kpiDefinition.placeholder.synonyms': 'z. B. Umsatz netto, Revenue, Sales',
        'kpiDefinition.placeholder.formula': 'z. B. Rechnungsbetrag - Gutschriften - Stornos',
        'kpiDefinition.placeholder.grain': 'z. B. Firma + Kunde + Monat',
        'kpiDefinition.placeholder.filters': 'z. B. nur gebuchte Rechnungen, ohne Intercompany',
        'kpiDefinition.placeholder.owner': 'z. B. Finance Owner, Sales Ops Lead',
        'kpiDefinition.placeholder.source': 'z. B. SAP S/4HANA Faktura, Executive Dashboard',
        'kpiDefinition.help.name': 'Welche Kennzahl soll fachlich entschieden oder gebaut werden?',
        'kpiDefinition.help.synonyms': 'Alternative Namen aus Reports/Teams helfen, doppelte KPIs zu erkennen.',
        'kpiDefinition.help.formula': 'Formel in Worten genügt. Wichtig sind Rechenlogik, Ausschlüsse und Sonderfälle.',
        'kpiDefinition.help.grain': 'Auf welcher Ebene ist die KPI eindeutig? Ohne Grain ist das Mart-Design unsicher.',
        'kpiDefinition.help.filters': 'Welche fachlichen Filter verändern das Ergebnis?',
        'kpiDefinition.help.owner': 'Wer darf Definition, Änderung und Konflikte entscheiden?',
        'kpiDefinition.help.source': 'Aus welchem Report, System oder Interview stammt diese Definition?',
        'kpiDefinition.help.status': 'Entwurf = offen, Konflikt = widersprüchlich, Abgestimmt = entscheidbar.',
        'kpiDefinition.status.draft': 'Entwurf',
        'kpiDefinition.status.conflict': 'Konflikt',
        'kpiDefinition.status.agreed': 'Abgestimmt',
        'kpiDefinition.result.eyebrow': 'Ergebnis',
        'kpiDefinition.result.title': 'KPI Report Zusammenfassung',
        'kpiDefinition.result.ready': 'entscheidungsbereit',
        'kpiDefinition.result.review': 'Review nötig',
        'kpiDefinition.result.total': 'KPIs',
        'kpiDefinition.result.agreed': 'abgestimmt',
        'kpiDefinition.result.conflicts': 'Konflikte',
        'kpiDefinition.result.sources': 'Quellen',
        'kpiDefinition.result.empty': 'Noch keine KPI erfasst. Lege eine Zeile an, damit der Report bewertet werden kann.',
        'kpiDefinition.result.conflictHint': 'Konflikte müssen durch Owner oder Sponsor entschieden werden.',
        'kpiDefinition.result.ownerHint': 'Mindestens eine KPI hat noch keinen Owner.',
        'kpiDefinition.result.grainHint': 'Mindestens eine KPI hat noch keinen Grain. Ohne Grain ist das Mart-Design unsicher.',
        'kpiDefinition.result.readyHint': 'Alle KPI-Karten haben Owner und Grain; der Stand kann in Mart Design oder Decision Brief übernommen werden.',
        'kpiDefinition.result.intakeHint': 'Einträge aus dem KPI Intake bleiben als Quelle verknüpft und können im Register weiter verfeinert werden.',
        'discovery.exportTitle': 'kpi-cards',
    },
    en: {
        'kpiDefinition.pageTitle': 'KPI Definition Card',
        'kpiDefinition.pageLead':
            'Without grain and owner, every report metric stays ambiguous. Capture KPIs with formula, grain, filters, and owner — then export inventory plus definition backlog.',
        'kpiDefinition.howto.intro':
            'The KPI register is the table after intake: reviewed KPI clarifications become rows, and more KPIs can be added directly.',
        'kpiDefinition.howto.step1': 'Add KPI names and synonyms from reports and interviews.',
        'kpiDefinition.howto.step2': 'Document formula, grain, filters, and owner.',
        'kpiDefinition.howto.step3': 'Set status, then review, copy, download, or move the report into the plan.',
        'kpiDefinition.howto.tip':
            'KPI intakes are connected to the register through the local workspace. The same shape can move to the database store later.',
        'kpiDefinition.playbookLink':
            'KPI Definition playbook — grain, owner, versioning',
        'kpiDefinition.inventoryPlaybookLink':
            'From report inventory to trusted metric',
        'kpiDefinition.semanticPlaybookLink':
            'Semantic layer vs measure in the report',
        'kpiDefinition.trustedMetricsLink': 'Learning path: Trusted metrics',
        'kpiDefinition.col.name': 'KPI',
        'kpiDefinition.col.synonyms': 'Synonyms',
        'kpiDefinition.col.formula': 'Formula',
        'kpiDefinition.col.grain': 'Grain',
        'kpiDefinition.col.filters': 'Filters',
        'kpiDefinition.col.owner': 'Owner',
        'kpiDefinition.col.source': 'Source / report',
        'kpiDefinition.col.status': 'Status',
        'kpiDefinition.placeholder.name': 'e.g. Net Revenue, Churn Rate, Open Receivables',
        'kpiDefinition.placeholder.synonyms': 'e.g. net sales, revenue, sales',
        'kpiDefinition.placeholder.formula': 'e.g. invoice amount - credits - cancellations',
        'kpiDefinition.placeholder.grain': 'e.g. company + customer + month',
        'kpiDefinition.placeholder.filters': 'e.g. posted invoices only, exclude intercompany',
        'kpiDefinition.placeholder.owner': 'e.g. Finance Owner, Sales Ops Lead',
        'kpiDefinition.placeholder.source': 'e.g. SAP S/4HANA Billing, Executive Dashboard',
        'kpiDefinition.help.name': 'Which metric should be decided or built?',
        'kpiDefinition.help.synonyms': 'Alternative names from reports/teams help detect duplicate KPIs.',
        'kpiDefinition.help.formula': 'Formula in words is enough. Capture calculation logic, exclusions, and edge cases.',
        'kpiDefinition.help.grain': 'At which level is the KPI unique? Without grain, mart design is unsafe.',
        'kpiDefinition.help.filters': 'Which business filters change the result?',
        'kpiDefinition.help.owner': 'Who can approve definition, changes, and conflicts?',
        'kpiDefinition.help.source': 'Which report, system, or interview provided this definition?',
        'kpiDefinition.help.status': 'Draft = open, Conflict = contradictory, Agreed = ready for decision.',
        'kpiDefinition.status.draft': 'Draft',
        'kpiDefinition.status.conflict': 'Conflict',
        'kpiDefinition.status.agreed': 'Agreed',
        'kpiDefinition.result.eyebrow': 'Result',
        'kpiDefinition.result.title': 'KPI report summary',
        'kpiDefinition.result.ready': 'decision-ready',
        'kpiDefinition.result.review': 'review needed',
        'kpiDefinition.result.total': 'KPIs',
        'kpiDefinition.result.agreed': 'agreed',
        'kpiDefinition.result.conflicts': 'conflicts',
        'kpiDefinition.result.sources': 'sources',
        'kpiDefinition.result.empty': 'No KPI captured yet. Add a row so the report can be assessed.',
        'kpiDefinition.result.conflictHint': 'Conflicts need a decision by owner or sponsor.',
        'kpiDefinition.result.ownerHint': 'At least one KPI has no owner yet.',
        'kpiDefinition.result.grainHint': 'At least one KPI has no grain yet. Without grain, mart design is uncertain.',
        'kpiDefinition.result.readyHint': 'All KPI cards have owner and grain; this can move into mart design or decision brief.',
        'kpiDefinition.result.intakeHint': 'Rows from KPI Intake stay linked as a source and can be refined in the register.',
        'discovery.exportTitle': 'kpi-cards',
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

function renderKpiSummary(host, rows) {
    const total = rows.length;
    const agreed = rows.filter((row) => row.status === 'agreed').length;
    const conflicts = rows.filter((row) => row.status === 'conflict').length;
    const missingOwner = rows.filter((row) => !String(row.owner || '').trim()).length;
    const missingGrain = rows.filter((row) => !String(row.grain || '').trim()).length;
    const sources = new Set(rows.map((row) => String(row.source || '').trim()).filter(Boolean));
    const ready = total > 0 && conflicts === 0 && missingOwner === 0 && missingGrain === 0;

    host.innerHTML = '';

    const section = document.createElement('section');
    section.className = 'tools-panel discovery-result-summary';

    const heading = document.createElement('div');
    heading.className = 'discovery-result-summary__head';
    heading.innerHTML = `
        <div>
            <p>${t('kpiDefinition.result.eyebrow')}</p>
            <h2>${t('kpiDefinition.result.title')}</h2>
        </div>
        <strong>${ready ? t('kpiDefinition.result.ready') : t('kpiDefinition.result.review')}</strong>
    `;

    const stats = document.createElement('div');
    stats.className = 'discovery-result-summary__stats';
    [
        [t('kpiDefinition.result.total'), total],
        [t('kpiDefinition.result.agreed'), agreed],
        [t('kpiDefinition.result.conflicts'), conflicts],
        [t('kpiDefinition.result.sources'), sources.size],
    ].forEach(([label, value]) => {
        const item = document.createElement('div');
        item.innerHTML = `<span>${label}</span><strong>${value}</strong>`;
        stats.appendChild(item);
    });

    const findings = document.createElement('ul');
    findings.className = 'discovery-result-summary__findings';
    const messages = [];
    if (total === 0) {
        messages.push(t('kpiDefinition.result.empty'));
    }
    if (conflicts > 0) {
        messages.push(t('kpiDefinition.result.conflictHint'));
    }
    if (missingOwner > 0) {
        messages.push(t('kpiDefinition.result.ownerHint'));
    }
    if (missingGrain > 0) {
        messages.push(t('kpiDefinition.result.grainHint'));
    }
    if (ready) {
        messages.push(t('kpiDefinition.result.readyHint'));
    }
    if (rows.some((row) => row.intakeId)) {
        messages.push(t('kpiDefinition.result.intakeHint'));
    }
    messages.forEach((message) => {
        const item = document.createElement('li');
        item.textContent = message;
        findings.appendChild(item);
    });

    section.append(heading, stats, findings);
    host.appendChild(section);
}

function normalizeRegisterRowForStore(row) {
    return {
        id: String(row.id || ''),
        name: String(row.name || ''),
        synonyms: String(row.synonyms || ''),
        formula: String(row.formula || ''),
        grain: String(row.grain || ''),
        filters: String(row.filters || ''),
        owner: String(row.owner || ''),
        source: String(row.source || ''),
        status: row.status === 'agreed' || row.status === 'conflict' ? row.status : 'draft',
        intakeId: row.intakeId ? String(row.intakeId) : undefined,
        updatedAt: new Date().toISOString(),
    };
}

const workspaceRows = loadKpiWorkspace().registerRows.map(normalizeRegisterRowForStore);

mountTableCanvas({
    root: app,
    initialRows: workspaceRows,
    legacyStorageKeys: ['bn-tools:kpi-definition:v1'],
    t,
    applyLabels,
    onChange: (rows) => {
        replaceKpiRegisterRows(rows.map(normalizeRegisterRowForStore));
    },
    columns: [
        { id: 'name', labelKey: 'kpiDefinition.col.name', type: 'text', placeholderKey: 'kpiDefinition.placeholder.name', helpKey: 'kpiDefinition.help.name' },
        { id: 'synonyms', labelKey: 'kpiDefinition.col.synonyms', type: 'text', placeholderKey: 'kpiDefinition.placeholder.synonyms', helpKey: 'kpiDefinition.help.synonyms' },
        { id: 'formula', labelKey: 'kpiDefinition.col.formula', type: 'textarea', placeholderKey: 'kpiDefinition.placeholder.formula', helpKey: 'kpiDefinition.help.formula' },
        { id: 'grain', labelKey: 'kpiDefinition.col.grain', type: 'text', placeholderKey: 'kpiDefinition.placeholder.grain', helpKey: 'kpiDefinition.help.grain' },
        { id: 'filters', labelKey: 'kpiDefinition.col.filters', type: 'text', placeholderKey: 'kpiDefinition.placeholder.filters', helpKey: 'kpiDefinition.help.filters' },
        { id: 'owner', labelKey: 'kpiDefinition.col.owner', type: 'text', placeholderKey: 'kpiDefinition.placeholder.owner', helpKey: 'kpiDefinition.help.owner' },
        { id: 'source', labelKey: 'kpiDefinition.col.source', type: 'text', placeholderKey: 'kpiDefinition.placeholder.source', helpKey: 'kpiDefinition.help.source' },
        { id: 'status', labelKey: 'kpiDefinition.col.status', type: 'select', options: statusOptions, helpKey: 'kpiDefinition.help.status' },
    ],
    renderExtra: renderKpiSummary,
});
