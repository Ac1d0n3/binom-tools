/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const promptStudioHowtoLabels = {
    de: {
        'promptStudio.howto.summary': 'So funktioniert\'s',

        'promptStudio.howto.area.prompt.title': 'Bereich: Prompt',
        'promptStudio.howto.area.prompt.intro':
            'Hier erstellst du normale KI-Prompts zum Kopieren — Bilder, Musik, Code, Business, Text.',
        'promptStudio.howto.area.prompt.step1': 'Oben „Prompt“ wählen, in der Bibliothek eine Aufgabe oder einen Workflow starten.',
        'promptStudio.howto.area.prompt.step2': 'Felder ausfüllen (Motiv, Ziel, Format, …).',
        'promptStudio.howto.area.prompt.step3': 'Fertigen Prompt kopieren und in deine KI einfügen.',
        'promptStudio.howto.area.prompt.tip':
            'Kategorie-Filter (Bilder, Musik, …) hilft beim Finden. Workflows verketten mehrere Schritte.',

        'promptStudio.howto.area.rule.title': 'Bereich: Projekt-Rule',
        'promptStudio.howto.area.rule.intro':
            'Hier baust du Cursor-/Projekt-Rules als Markdown-Datei (mit Frontmatter) — nicht zum Chatten, sondern zur Projekt-Konfiguration.',
        'promptStudio.howto.area.rule.step1': 'Oben „Projekt-Rule“ wählen und eine Rule-Vorlage in der Bibliothek öffnen.',
        'promptStudio.howto.area.rule.step2': 'Felder ausfüllen (Standards, Scope, Verbote, …).',
        'promptStudio.howto.area.rule.step3': 'Als .md herunterladen und in dein Projekt legen (z. B. Cursor Rules).',
        'promptStudio.howto.area.rule.tip':
            'Ergebnis ist eine Markdown-Datei, kein Chat-Prompt. Badge und Download-Button zeigen das an.',

        'promptStudio.howto.area.agent.title': 'Bereich: Agent',
        'promptStudio.howto.area.agent.intro':
            'Hier erstellst du Agent-Aufgaben als strukturiertes Markdown — Debug-Pläne, Repo-Automation, Checklisten für AI-Agenten.',
        'promptStudio.howto.area.agent.step1': 'Oben „Agent“ wählen und eine Agent-Hilfe in der Bibliothek öffnen.',
        'promptStudio.howto.area.agent.step2': 'Kontext ausfüllen (Repro, Ziel, Constraints, …).',
        'promptStudio.howto.area.agent.step3': 'Als .md herunterladen und dem Agenten / der IDE geben.',
        'promptStudio.howto.area.agent.tip':
            'Agent-MDs sind Arbeitsaufträge, keine Chat-Prompts. Nutze Workflows, wenn mehrere Schritte nötig sind.',

        'promptStudio.howto.builder.summary': 'Erstellen',
        'promptStudio.howto.builder.intro': 'Im Standard nur Felder. Rolle und Dropdowns sitzen im Expertenmodus.',
        'promptStudio.howto.builder.step1': 'Zuerst den Bereich oben wählen (Prompt, Projekt-Rule oder Agent).',
        'promptStudio.howto.builder.step2': 'Dann in der Bibliothek eine passende Vorlage wählen und Eingaben ausfüllen.',
        'promptStudio.howto.builder.step3': 'Experte: Rolle, Aufgabe und Modell manuell anpassen.',

        'promptStudio.howto.workflow.summary': 'Workflows',
        'promptStudio.howto.workflow.intro':
            'Ein Workflow verkettet mehrere Schritte. Am sinnvollsten im Bereich Prompt; Agent kann eigene Ketten haben.',
        'promptStudio.howto.workflow.step1': 'Tab Workflows öffnen und einen Workflow starten.',
        'promptStudio.howto.workflow.step2': 'Pro Schritt: Prompt/MD kopieren bzw. laden → in die KI einfügen.',
        'promptStudio.howto.workflow.step3': 'Antwort zurück in „Vorherige Ausgabe“ → Nächster Schritt.',

        'promptStudio.howto.preview.summary': 'Vorschau',
        'promptStudio.howto.preview.intro': 'Der fertige Text zum Kopieren oder Download.',
        'promptStudio.howto.preview.step1': 'Standardmodus: ein zusammenhängender Text.',
        'promptStudio.howto.preview.step2': 'Expertenmodus: einzelne Abschnitte bearbeitbar.',
    },
    en: {
        'promptStudio.howto.summary': 'How it works',

        'promptStudio.howto.area.prompt.title': 'Area: Prompt',
        'promptStudio.howto.area.prompt.intro':
            'Build normal AI prompts to copy — images, music, code, business, writing.',
        'promptStudio.howto.area.prompt.step1': 'Choose “Prompt” above, then start a task or workflow in the library.',
        'promptStudio.howto.area.prompt.step2': 'Fill the fields (subject, goal, format, …).',
        'promptStudio.howto.area.prompt.step3': 'Copy the finished prompt into your AI tool.',
        'promptStudio.howto.area.prompt.tip':
            'Use the category filter (Images, Music, …) to find tasks. Workflows chain multiple steps.',

        'promptStudio.howto.area.rule.title': 'Area: Project rule',
        'promptStudio.howto.area.rule.intro':
            'Build Cursor/project rules as Markdown (with frontmatter) — for project config, not chat.',
        'promptStudio.howto.area.rule.step1': 'Choose “Project rule” above and open a rule template in the library.',
        'promptStudio.howto.area.rule.step2': 'Fill the fields (standards, scope, constraints, …).',
        'promptStudio.howto.area.rule.step3': 'Download as .md and place it in your project (e.g. Cursor rules).',
        'promptStudio.howto.area.rule.tip':
            'The result is a Markdown file, not a chat prompt. The badge and download button reflect that.',

        'promptStudio.howto.area.agent.title': 'Area: Agent',
        'promptStudio.howto.area.agent.intro':
            'Build agent tasks as structured Markdown — debug plans, repo automation, checklists for AI agents.',
        'promptStudio.howto.area.agent.step1': 'Choose “Agent” above and open an agent helper in the library.',
        'promptStudio.howto.area.agent.step2': 'Fill in context (repro, goal, constraints, …).',
        'promptStudio.howto.area.agent.step3': 'Download as .md and give it to the agent / IDE.',
        'promptStudio.howto.area.agent.tip':
            'Agent MDs are work orders, not chat prompts. Use workflows when you need multiple steps.',

        'promptStudio.howto.builder.summary': 'Builder',
        'promptStudio.howto.builder.intro': 'Regular mode shows fields only. Role and dropdowns live in Tech mode.',
        'promptStudio.howto.builder.step1': 'First pick the area above (Prompt, Project rule, or Agent).',
        'promptStudio.howto.builder.step2': 'Then pick a matching template in the library and fill the inputs.',
        'promptStudio.howto.builder.step3': 'Tech: adjust role, task, and model manually.',

        'promptStudio.howto.workflow.summary': 'Workflows',
        'promptStudio.howto.workflow.intro':
            'A workflow chains several steps. Most useful in Prompt; Agent can have its own chains.',
        'promptStudio.howto.workflow.step1': 'Open the Workflows tab and start a workflow.',
        'promptStudio.howto.workflow.step2': 'Per step: copy/load prompt or MD → paste into your AI tool.',
        'promptStudio.howto.workflow.step3': 'Paste the reply into Previous output → Next step.',

        'promptStudio.howto.preview.summary': 'Preview',
        'promptStudio.howto.preview.intro': 'The finished text to copy or download.',
        'promptStudio.howto.preview.step1': 'Regular mode: one continuous text.',
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
