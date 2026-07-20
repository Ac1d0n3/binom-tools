import '../../../css/tools/discovery-canvas.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { copyTextToClipboard } from '../pii-shared/tool-utils.js';

const labels = mergeDiscoveryLabels({
    de: {
        'biPythonToolkit.pageTitle': 'BI Python Export Toolkit',
        'biPythonToolkit.pageLead':
            'Python-Tools lokal ausführen, BI-Metadaten exportieren und die Ergebnisse in Plan-Schritten weiterverwenden.',
        'biPythonToolkit.howto.intro':
            'BI-Exports laufen lokal, nicht im Browser. Lade das passende Script herunter, lege es in einen Python-Projektordner, führe es gegen Qlik, Power BI oder Tableau aus und nutze CSV, Markdown oder Plan-JSON in der Planarbeit.',
        'biPythonToolkit.howto.step1':
            'Python lokal vorbereiten: Ordner anlegen, virtuelles Environment aktivieren, optionale Abhängigkeiten installieren.',
        'biPythonToolkit.howto.step2':
            'Script auswählen: KPI-Formeln oder Qlik App-/Sheet-Inventar.',
        'biPythonToolkit.howto.step3':
            'Export ausführen und das Ergebnis in der passenden Sprint-Planner-Aufgabe anhängen oder einfügen.',
        'biPythonToolkit.howto.tip':
            'Der Export ist Rohinventar, nicht die fertige Governance-Entscheidung. Owner, fachliche Bedeutung, Status und offene Fragen müssen danach geprüft werden.',
        'biPythonToolkit.setupStoryLink':
            'Python-Anleitung: Python installieren, Ordner initialisieren, Exports ausführen',
        'biPythonToolkit.openSetupStory': 'Python Setup Story',
        'biPythonToolkit.purpose.title': 'Welches Script brauche ich?',
        'biPythonToolkit.choice.script': 'Script',
        'biPythonToolkit.choice.use': 'Wofür',
        'biPythonToolkit.choice.output': 'Nützlicher Output',
        'biPythonToolkit.choice.next': 'Nächster Schritt',
        'biPythonToolkit.choice.kpi.use':
            'KPI-/Measure-Formeln aus Qlik, Power BI model.bim oder Tableau Workbook-Dateien.',
        'biPythonToolkit.choice.kpi.next':
            'In die KPI-Definition-Aufgabe übernehmen und Owner, Grain, Filter und Status ergänzen.',
        'biPythonToolkit.choice.inventory.use':
            'Qlik Cloud App-Inventar und optional Sheet-Inventar inklusive Published-/Approved-Status.',
        'biPythonToolkit.choice.inventory.next':
            'Für App-Scope, Impact oder Cleanup nutzen und entscheiden, welche Apps/Sheets Review brauchen.',
        'biPythonToolkit.downloads.title': 'Downloads',
        'biPythonToolkit.downloads.lead':
            'Speichere die Dateien in deinen lokalen Python-Projektordner. API Keys bleiben auf deinem Rechner.',
        'biPythonToolkit.download.kpi': 'KPI Export Script',
        'biPythonToolkit.download.inventory': 'Qlik App Inventory Script',
        'biPythonToolkit.download.readme': 'README herunterladen',
        'biPythonToolkit.commands.title': 'Beispiel-Kommandos',
        'biPythonToolkit.commands.lead': 'Passe Dateinamen, Tenant und API Key an deine Umgebung an.',
        'biPythonToolkit.export.title': 'Was mache ich mit dem Export?',
        'biPythonToolkit.export.csv.title': 'CSV',
        'biPythonToolkit.export.csv.body':
            'Nutze CSV für Excel, schnelle Filterung, App-Inventare und manuelle Cleanup-Listen.',
        'biPythonToolkit.export.markdown.title': 'Markdown',
        'biPythonToolkit.export.markdown.body':
            'Nutze Markdown für Sprint-Planner-Notizen, Tickets, Pull Requests oder Wiki-Seiten.',
        'biPythonToolkit.export.planJson.title': 'Plan JSON',
        'biPythonToolkit.export.planJson.body':
            'Nutze Plan-JSON, wenn du strukturierte Zeilen willst, die zu Planner-Tabellen und späterer Automatisierung passen.',
        'biPythonToolkit.export.review':
            'Nach dem Import jede Zeile prüfen: Dubletten entfernen, Owner setzen, Status markieren und unklare Formeln oder unveröffentlichte Sheets in Folgeaufgaben überführen.',
        'biPythonToolkit.scripts.title': 'Scripts ansehen und kopieren',
        'biPythonToolkit.scripts.lead':
            'Du kannst die Python-Dateien direkt hier prüfen, kopieren oder als Datei herunterladen.',
        'biPythonToolkit.copyScript': 'Script kopieren',
        'biPythonToolkit.downloadScript': 'Download',
    },
    en: {
        'biPythonToolkit.pageTitle': 'BI Python Export Toolkit',
        'biPythonToolkit.pageLead':
            'Run Python tools locally, export BI metadata, and reuse the results in plan steps.',
        'biPythonToolkit.howto.intro':
            'Run BI exports locally, not in the browser. Download the script, place it in a Python project folder, run it against Qlik, Power BI or Tableau, then use the generated CSV, Markdown or plan JSON in your plan work.',
        'biPythonToolkit.howto.step1':
            'Prepare Python locally: create a folder, activate a virtual environment, install optional dependencies.',
        'biPythonToolkit.howto.step2': 'Choose the script: KPI formulas or Qlik app/sheet inventory.',
        'biPythonToolkit.howto.step3':
            'Run the export and attach or copy the result into the relevant Sprint Planner task.',
        'biPythonToolkit.howto.tip':
            'The export is raw inventory, not the final governance decision. Review owners, business meaning, status and open questions before closing the task.',
        'biPythonToolkit.setupStoryLink':
            'Python setup guide: install Python, initialize a folder, run the exports',
        'biPythonToolkit.openSetupStory': 'Python Setup Story',
        'biPythonToolkit.purpose.title': 'Which script should I use?',
        'biPythonToolkit.choice.script': 'Script',
        'biPythonToolkit.choice.use': 'Use it for',
        'biPythonToolkit.choice.output': 'Useful output',
        'biPythonToolkit.choice.next': 'Next step',
        'biPythonToolkit.choice.kpi.use':
            'KPI / measure formulas from Qlik, Power BI model.bim or Tableau workbook files.',
        'biPythonToolkit.choice.kpi.next':
            'Load the result into the KPI Definition task and complete owner, grain, filters and status.',
        'biPythonToolkit.choice.inventory.use':
            'Qlik Cloud app inventory and, optionally, sheet inventory including published/approved state.',
        'biPythonToolkit.choice.inventory.next':
            'Use it in app-scope, impact or cleanup tasks to decide which apps/sheets need review.',
        'biPythonToolkit.downloads.title': 'Downloads',
        'biPythonToolkit.downloads.lead':
            'Save the files into your local Python project folder. API keys stay on your machine.',
        'biPythonToolkit.download.kpi': 'KPI export script',
        'biPythonToolkit.download.inventory': 'Qlik app inventory script',
        'biPythonToolkit.download.readme': 'Download README',
        'biPythonToolkit.commands.title': 'Example commands',
        'biPythonToolkit.commands.lead': 'Adjust filenames, tenant and API key for your environment.',
        'biPythonToolkit.export.title': 'What do I do with the export?',
        'biPythonToolkit.export.csv.title': 'CSV',
        'biPythonToolkit.export.csv.body':
            'Use CSV for Excel, quick filtering, app inventories and manual cleanup lists.',
        'biPythonToolkit.export.markdown.title': 'Markdown',
        'biPythonToolkit.export.markdown.body':
            'Use Markdown for Sprint Planner notes, tickets, pull requests or wiki pages.',
        'biPythonToolkit.export.planJson.title': 'Plan JSON',
        'biPythonToolkit.export.planJson.body':
            'Use plan JSON when you want structured rows that match planner-style tables and later automation.',
        'biPythonToolkit.export.review':
            'After import, review each row: remove duplicates, assign an owner, mark status, and turn unclear formulas or unpublished sheets into follow-up tasks.',
        'biPythonToolkit.scripts.title': 'View and copy scripts',
        'biPythonToolkit.scripts.lead':
            'Review the Python files directly here, copy them, or download them as files.',
        'biPythonToolkit.copyScript': 'Copy script',
        'biPythonToolkit.downloadScript': 'Download',
    },
});

const { applyLabels } = createLabelApi(labels);

function copiedLabel() {
    return document.documentElement.lang === 'de' ? 'Kopiert!' : 'Copied!';
}

document.querySelectorAll('[data-copy-code]').forEach((button) => {
    if (!(button instanceof HTMLButtonElement)) {
        return;
    }

    const targetId = button.getAttribute('data-copy-code');
    const target = targetId ? document.getElementById(targetId) : null;
    if (!target) {
        return;
    }

    button.addEventListener('click', async () => {
        const original = button.textContent;
        const text = target.textContent || '';
        try {
            await copyTextToClipboard(text);
            button.textContent = copiedLabel();
            window.setTimeout(() => {
                button.textContent = original;
            }, 1600);
        } catch {
            window.prompt('Copy:', text);
        }
    });
});

applyLabels();
window.addEventListener('binom-tools:locale', applyLabels);
