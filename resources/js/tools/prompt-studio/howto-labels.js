/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const promptStudioHowtoLabels = {
    de: {
        'promptStudio.howto.summary': 'So funktioniert\'s',
        'promptStudio.howto.overview.intro':
            'Baue KI-Arbeit als Einzelaufgabe oder optional als Workflow — Prompt, Projekt-Rule oder Agent-MD.',
        'promptStudio.howto.overview.step1': 'Tab Aufgaben: eine Aufgabe wählen (Prompt / Rule / Agent).',
        'promptStudio.howto.overview.step2': 'Felder ausfüllen — die Ausgabe-Art kommt von der Aufgabe.',
        'promptStudio.howto.overview.step3': 'Prompt: kopieren. Rule/Agent: als .md herunterladen.',
        'promptStudio.howto.overview.step4': 'Workflows nur wenn du mehrere Schritte verkettet brauchst.',
        'promptStudio.howto.overview.step5':
            'Eigene Vorlagen, Workflows und Rollen speichern — lokal; eingeloggt auch am Account.',
        'promptStudio.howto.overview.tip':
            'Hilfe zugeklappt lassen — Bibliothek öffnen zeigt zuerst Aufgaben, nicht Workflows.',

        'promptStudio.howto.builder.summary': 'Erstellen',
        'promptStudio.howto.builder.intro': 'Hier stellst du Rolle, Aufgabe und die passenden Eingabefelder ein.',
        'promptStudio.howto.builder.step1': 'Rolle = KI-Perspektive (Angular, Debug, Songwriter …).',
        'promptStudio.howto.builder.step2': 'Aufgabe = einzelnes Ergebnis (Lyrics, Signals-Rule, Debug-Agent …).',
        'promptStudio.howto.builder.step3': 'Varianten: Ein Feld variiert — Stil bleibt gleich.',

        'promptStudio.howto.workflow.summary': 'Workflows',
        'promptStudio.howto.workflow.intro':
            'Ein Workflow verkettet mehrere Aufgaben. Standard bleibt die Einzelaufgabe.',
        'promptStudio.howto.workflow.step1': 'Nur im Tab Workflows starten — oder optionalen Hinweis im Builder nutzen.',
        'promptStudio.howto.workflow.step2': 'Pro Schritt: Prompt/MD kopieren bzw. laden → in die KI einfügen.',
        'promptStudio.howto.workflow.step3': 'Antwort zurück in „Vorherige Ausgabe“ → Nächster Schritt.',

        'promptStudio.howto.kinds.summary': 'Ausgabe-Arten',
        'promptStudio.howto.kinds.intro': 'Steckt an der Aufgabe — kein separater Schalter.',
        'promptStudio.howto.kinds.step1': 'Prompt-Aufgabe: Chat-/Modelltext zum Kopieren.',
        'promptStudio.howto.kinds.step2': 'Projekt-Rule-Aufgabe: Markdown mit Frontmatter (Cursor Rules) zum Download.',
        'promptStudio.howto.kinds.step3': 'Agent-Aufgabe: strukturiertes MD für Agent-Planung zum Download.',

        'promptStudio.howto.preview.summary': 'Vorschau',
        'promptStudio.howto.preview.intro': 'Der fertige Text zum Kopieren oder Download.',
        'promptStudio.howto.preview.step1': 'Standardmodus: ein zusammenhängender Prompt.',
        'promptStudio.howto.preview.step2': 'Expertenmodus: einzelne Abschnitte bearbeitbar.',
    },
    en: {
        'promptStudio.howto.summary': 'How it works',
        'promptStudio.howto.overview.intro':
            'Build AI work as a single task or optionally as a workflow — prompt, project rule, or agent MD.',
        'promptStudio.howto.overview.step1': 'Tasks tab: pick one task (Prompt / Rule / Agent).',
        'promptStudio.howto.overview.step2': 'Fill the fields — output kind comes from the task.',
        'promptStudio.howto.overview.step3': 'Prompt: copy. Rule/Agent: download as .md.',
        'promptStudio.howto.overview.step4': 'Use Workflows only when you need chained steps.',
        'promptStudio.howto.overview.step5':
            'Save your own templates, workflows, and roles — locally; when logged in also on your account.',
        'promptStudio.howto.overview.tip':
            'Keep help collapsed — opening the library shows tasks first, not workflows.',

        'promptStudio.howto.builder.summary': 'Builder',
        'promptStudio.howto.builder.intro': 'Set role, task, and matching input fields here.',
        'promptStudio.howto.builder.step1': 'Role = AI perspective (Angular, Debug, Songwriter …).',
        'promptStudio.howto.builder.step2': 'Task = one deliverable (lyrics, Signals rule, debug agent …).',
        'promptStudio.howto.builder.step3': 'Variants: one field varies — style stays the same.',

        'promptStudio.howto.workflow.summary': 'Workflows',
        'promptStudio.howto.workflow.intro':
            'A workflow chains several tasks. The default remains a single task.',
        'promptStudio.howto.workflow.step1': 'Start only from the Workflows tab — or the optional builder hint.',
        'promptStudio.howto.workflow.step2': 'Per step: copy/load prompt or MD → paste into your AI tool.',
        'promptStudio.howto.workflow.step3': 'Paste the reply into Previous output → Next step.',

        'promptStudio.howto.kinds.summary': 'Output kinds',
        'promptStudio.howto.kinds.intro': 'Comes from the task — not a separate toggle.',
        'promptStudio.howto.kinds.step1': 'Prompt task: chat/model text to copy.',
        'promptStudio.howto.kinds.step2': 'Project-rule task: Markdown with frontmatter (Cursor rules) to download.',
        'promptStudio.howto.kinds.step3': 'Agent task: structured MD for agent planning to download.',

        'promptStudio.howto.preview.summary': 'Preview',
        'promptStudio.howto.preview.intro': 'The finished text to copy or download.',
        'promptStudio.howto.preview.step1': 'Regular mode: one continuous prompt.',
        'promptStudio.howto.preview.step2': 'Tech mode: editable sections.',
    },
};

/** @param {ToolsLocale} locale @param {string} key */
export function howtoT(locale, key) {
    return promptStudioHowtoLabels[locale]?.[key] ?? promptStudioHowtoLabels.en[key] ?? key;
}

/** @param {ToolsLocale} [locale] */
export function applyHowtoLabels(locale = 'en') {
    // Covered by applyPromptStudioLabels (howto keys are merged into main labels).
    void locale;
}
