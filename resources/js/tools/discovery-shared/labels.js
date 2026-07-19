import { getLocale } from '../../locale.js';

/** Shared discovery UI strings merged into each tool's label map. */
export const discoveryCommonLabels = {
    de: {
        'discovery.addRow': 'Zeile hinzufügen',
        'discovery.remove': 'Entfernen',
        'discovery.actions': 'Aktionen',
        'discovery.empty': 'Noch keine Einträge — füge die erste Zeile hinzu.',
        'discovery.copyMarkdown': 'Markdown kopieren',
        'discovery.downloadMarkdown': 'Markdown herunterladen',
        'discovery.copyCsv': 'CSV kopieren',
        'discovery.downloadCsv': 'CSV herunterladen',
        'discovery.clear': 'Zurücksetzen',
        'discovery.clearConfirm': 'Eingaben in diesem Tab löschen? (Nichts wird dauerhaft im Tool gespeichert.)',
        'discovery.copied': 'Kopiert — jetzt in Notizen, Wiki oder Plan einfügen',
        'discovery.downloaded': 'Heruntergeladen',
        'discovery.exportPreview': 'Export (nicht im Tool gespeichert)',
        'discovery.exportHint':
            'Jetzt kopieren oder herunterladen — z. B. für Notizen, Wiki, Tickets oder den Sprint Planner. Beim Verlassen der Seite ist der Inhalt weg.',
        'discovery.exportTitle': 'Inventar',
        'discovery.yes': 'Ja',
        'discovery.no': 'Nein',
        'discovery.howto.summary': 'So funktioniert’s',
        'discovery.ephemeral':
            'Standalone nutzbar: nichts wird im Tool gespeichert. Ergebnis kopieren/herunterladen und wo du es brauchst ablegen (Dokumentation, Ticket, Sprint Planner — dort ggf. Login/Rechte).',
        'discovery.warnTitle': 'Nicht gespeichert — Ergebnis jetzt übernehmen',
        'discovery.warnBody':
            'Eingaben existieren nur auf dieser Seite. Beim Wechseln, Neuladen oder Schließen sind sie weg. Kopiere oder lade die Datei herunter und lege sie in deinen Notizen, im Wiki oder im Sprint Planner ab.',
        'discovery.planWarnTitle': 'Zurück in den Plan übernehmen',
        'discovery.planWarnBody':
            'Du kommst aus dem Sprint Planner. Nichts wird im Tool gespeichert — übernimm das Ergebnis in die aktuelle Aufgabe (Tabelle oder Notiz), oder kopiere/lade zusätzlich herunter.',
        'discovery.planExportHint':
            'Optional zusätzlich kopieren/herunterladen. „In Plan übernehmen“ schreibt in die Aufgabe, von der du gekommen bist.',
        'discovery.applyToPlan': 'In aktuelle Aufgabe übernehmen',
        'discovery.applyEmpty': 'Noch nichts zum Übernehmen — füge zuerst Einträge hinzu.',
        'discovery.leaveConfirm':
            'Ergebnis noch nicht übernommen (kopiert/heruntergeladen). Beim Verlassen gehen die Eingaben verloren.',
    },
    en: {
        'discovery.addRow': 'Add row',
        'discovery.remove': 'Remove',
        'discovery.actions': 'Actions',
        'discovery.empty': 'No entries yet — add the first row.',
        'discovery.copyMarkdown': 'Copy Markdown',
        'discovery.downloadMarkdown': 'Download Markdown',
        'discovery.copyCsv': 'Copy CSV',
        'discovery.downloadCsv': 'Download CSV',
        'discovery.clear': 'Reset',
        'discovery.clearConfirm': 'Clear entries in this tab? (Nothing is persisted in the tool.)',
        'discovery.copied': 'Copied — paste into notes, wiki, or a plan',
        'discovery.downloaded': 'Downloaded',
        'discovery.exportPreview': 'Export (not stored in the tool)',
        'discovery.exportHint':
            'Copy or download now — e.g. for notes, wiki, tickets, or the Sprint Planner. Leaving this page discards the content.',
        'discovery.exportTitle': 'Inventory',
        'discovery.yes': 'Yes',
        'discovery.no': 'No',
        'discovery.howto.summary': 'How it works',
        'discovery.ephemeral':
            'Works standalone: nothing is stored in the tool. Copy/download the result and keep it where you need it (docs, ticket, Sprint Planner — login/rights may apply there).',
        'discovery.warnTitle': 'Not saved — transfer the result now',
        'discovery.warnBody':
            'Entries exist only on this page. Navigating away, reloading, or closing discards them. Copy or download the file and keep it in your notes, wiki, or Sprint Planner.',
        'discovery.planWarnTitle': 'Apply back to your plan',
        'discovery.planWarnBody':
            'You opened this from the Sprint Planner. Nothing is stored in the tool — apply the result to the current task (table or note), or also copy/download.',
        'discovery.planExportHint':
            'Optionally copy/download as well. “Apply to plan” writes into the task you came from.',
        'discovery.applyToPlan': 'Apply to current task',
        'discovery.applyEmpty': 'Nothing to apply yet — add entries first.',
        'discovery.leaveConfirm':
            'Result not transferred yet (copy/download). Leaving will discard your entries.',
    },
};

/**
 * @param {Record<'de'|'en', Record<string, string>>} toolLabels
 */
export function mergeDiscoveryLabels(toolLabels) {
    return {
        de: { ...discoveryCommonLabels.de, ...toolLabels.de },
        en: { ...discoveryCommonLabels.en, ...toolLabels.en },
    };
}

/**
 * @param {Record<'de'|'en', Record<string, string>>} labels
 */
export function createLabelApi(labels) {
    /** @param {string} key */
    function t(key) {
        const locale = getLocale();
        return labels[locale]?.[key] ?? labels.en[key] ?? key;
    }

    function applyLabels() {
        const locale = getLocale();
        const map = labels[locale] || labels.en;
        document.querySelectorAll('[data-i18n]').forEach((el) => {
            const key = el.getAttribute('data-i18n');
            if (key && map[key]) {
                el.textContent = map[key];
            }
        });
    }

    return { t, applyLabels };
}
