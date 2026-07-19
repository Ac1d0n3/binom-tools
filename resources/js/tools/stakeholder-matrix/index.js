import '../../../css/tools/discovery-canvas.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { mountTableCanvas } from '../discovery-shared/table-canvas.js';

const labels = mergeDiscoveryLabels({
    de: {
        'stakeholderMatrix.pageTitle': 'Stakeholder & RACI Matrix',
        'stakeholderMatrix.pageLead':
            'Entscheider, Nutzer und Owner namentlich erfassen — Einfluss, Interesse und optionale RACI. Export/Download für Mandat, Owner-Matrix oder Dokumentation.',
        'stakeholderMatrix.howto.intro':
            'Nutze die Matrix für Woche 1 (Stakeholder-Liste) und Woche 3 (Owner-Matrix) im First-Quarter-Plan.',
        'stakeholderMatrix.howto.step1': 'Personen und Rollen eintragen — lieber Namen als nur Abteilungen.',
        'stakeholderMatrix.howto.step2': 'Einfluss und Interesse einschätzen; optional RACI setzen.',
        'stakeholderMatrix.howto.step3': 'Markdown kopieren oder herunterladen — für Notizen, Wiki oder den Sprint Planner.',
        'stakeholderMatrix.howto.tip':
            'Nichts wird im Tool gespeichert. Ergebnis übernehmen (Copy/Download); Persistenz liegt bei dir (Doku/Plan). Achte auf fehlende operative Owner.',
        'stakeholderMatrix.col.name': 'Name',
        'stakeholderMatrix.col.role': 'Rolle',
        'stakeholderMatrix.col.influence': 'Einfluss',
        'stakeholderMatrix.col.interest': 'Interesse',
        'stakeholderMatrix.col.owner': 'Owner-Bezug',
        'stakeholderMatrix.col.raci': 'RACI',
        'stakeholderMatrix.level.high': 'Hoch',
        'stakeholderMatrix.level.medium': 'Mittel',
        'stakeholderMatrix.level.low': 'Niedrig',
        'stakeholderMatrix.raci.none': '—',
        'stakeholderMatrix.raci.r': 'R — Responsible',
        'stakeholderMatrix.raci.a': 'A — Accountable',
        'stakeholderMatrix.raci.c': 'C — Consulted',
        'stakeholderMatrix.raci.i': 'I — Informed',
        'discovery.exportTitle': 'Stakeholder-Matrix',
    },
    en: {
        'stakeholderMatrix.pageTitle': 'Stakeholder & RACI Matrix',
        'stakeholderMatrix.pageLead':
            'Capture decision-makers, users, and owners by name — influence, interest, and optional RACI. Export for mandate and owner matrix.',
        'stakeholderMatrix.howto.intro':
            'Use this matrix for week 1 (stakeholder list) and week 3 (owner matrix) in the first-quarter plan.',
        'stakeholderMatrix.howto.step1': 'Add people and roles — prefer names over departments only.',
        'stakeholderMatrix.howto.step2': 'Rate influence and interest; optionally set RACI.',
        'stakeholderMatrix.howto.step3': 'Copy or download Markdown — for notes, wiki, or the Sprint Planner.',
        'stakeholderMatrix.howto.tip':
            'Nothing is stored in the tool. Transfer the result (copy/download); persistence is yours (docs/plan). Watch for missing operational owners.',
        'stakeholderMatrix.col.name': 'Name',
        'stakeholderMatrix.col.role': 'Role',
        'stakeholderMatrix.col.influence': 'Influence',
        'stakeholderMatrix.col.interest': 'Interest',
        'stakeholderMatrix.col.owner': 'Owner link',
        'stakeholderMatrix.col.raci': 'RACI',
        'stakeholderMatrix.level.high': 'High',
        'stakeholderMatrix.level.medium': 'Medium',
        'stakeholderMatrix.level.low': 'Low',
        'stakeholderMatrix.raci.none': '—',
        'stakeholderMatrix.raci.r': 'R — Responsible',
        'stakeholderMatrix.raci.a': 'A — Accountable',
        'stakeholderMatrix.raci.c': 'C — Consulted',
        'stakeholderMatrix.raci.i': 'I — Informed',
        'discovery.exportTitle': 'Stakeholder matrix',
    },
});

const { t, applyLabels } = createLabelApi(labels);

const levelOptions = [
    { value: 'high', labelKey: 'stakeholderMatrix.level.high' },
    { value: 'medium', labelKey: 'stakeholderMatrix.level.medium' },
    { value: 'low', labelKey: 'stakeholderMatrix.level.low' },
];

const raciOptions = [
    { value: '', labelKey: 'stakeholderMatrix.raci.none' },
    { value: 'R', labelKey: 'stakeholderMatrix.raci.r' },
    { value: 'A', labelKey: 'stakeholderMatrix.raci.a' },
    { value: 'C', labelKey: 'stakeholderMatrix.raci.c' },
    { value: 'I', labelKey: 'stakeholderMatrix.raci.i' },
];

const app = document.getElementById('stakeholder-matrix-app');
if (!app) {
    throw new Error('Stakeholder matrix root not found');
}

mountTableCanvas({
    root: app,
    legacyStorageKeys: ['bn-tools:stakeholder-matrix:v1'],
    t,
    applyLabels,
    columns: [
        { id: 'name', labelKey: 'stakeholderMatrix.col.name', type: 'text' },
        { id: 'role', labelKey: 'stakeholderMatrix.col.role', type: 'text' },
        { id: 'influence', labelKey: 'stakeholderMatrix.col.influence', type: 'select', options: levelOptions },
        { id: 'interest', labelKey: 'stakeholderMatrix.col.interest', type: 'select', options: levelOptions },
        { id: 'owner', labelKey: 'stakeholderMatrix.col.owner', type: 'text' },
        { id: 'raci', labelKey: 'stakeholderMatrix.col.raci', type: 'select', options: raciOptions },
    ],
});
