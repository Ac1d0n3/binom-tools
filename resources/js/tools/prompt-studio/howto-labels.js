/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const promptStudioHowtoLabels = {
    de: {
        'promptStudio.howto.summary': 'So funktioniert\'s',
        'promptStudio.howto.overview.intro':
            'Baue KI-Prompts Schritt für Schritt — einzeln oder als Workflow-Kette mit mehreren Prompts.',
        'promptStudio.howto.overview.step1': 'Rolle wählen: Welche Perspektive soll die KI einnehmen?',
        'promptStudio.howto.overview.step2': 'Aufgabe wählen: Nur passende Aufgaben erscheinen für diese Rolle.',
        'promptStudio.howto.overview.step3': 'Felder ausfüllen: Die Eingaben passen sich automatisch an die Aufgabe an.',
        'promptStudio.howto.overview.step4': 'Prompt kopieren oder einzeln anonymisieren → AI Sanitizer.',
        'promptStudio.howto.overview.step5':
            'Workflows (z. B. Song + Cover): Mehrere Prompts nacheinander — Schritt 2 baut auf der KI-Antwort aus Schritt 1 auf.',
        'promptStudio.howto.overview.tip':
            'Dein Arbeitsstand wird automatisch gespeichert. Varianten eignen sich z. B. für 6 Bilder mit gleichem Stil, aber unterschiedlichem Motiv.',

        'promptStudio.howto.builder.summary': 'Erstellen',
        'promptStudio.howto.builder.intro': 'Hier stellst du Rolle, Aufgabe und die passenden Eingabefelder ein.',
        'promptStudio.howto.builder.step1': 'Rolle = KI-Perspektive (Geschäftsanalyst, Bilddesigner, Songwriter …).',
        'promptStudio.howto.builder.step2': 'Aufgabe = Was du erstellen willst (Analyse, Hero-Bild, Lyrics …).',
        'promptStudio.howto.builder.step3': 'Varianten: Ein Feld variiert (z. B. Motiv) — Stil und Farben bleiben gleich.',

        'promptStudio.howto.workflow.summary': 'Workflows',
        'promptStudio.howto.workflow.intro': 'Mehrschritt-Ketten helfen beim Prompt-Timing: nicht alles in einen Prompt packen.',
        'promptStudio.howto.workflow.step1': 'Preset wählen: Song-Produktion, Dokument analysieren, Business + Visual …',
        'promptStudio.howto.workflow.step2': 'Pro Schritt: eigenen Prompt kopieren und bei Bedarf anonymisieren.',
        'promptStudio.howto.workflow.step3': 'KI-Antwort aus Schritt N in „Vorherige Ausgabe“ einfügen → nächster Schritt.',

        'promptStudio.howto.preview.summary': 'Vorschau',
        'promptStudio.howto.preview.intro': 'Der fertige Prompt zum Kopieren in ChatGPT, Claude, Midjourney usw.',
        'promptStudio.howto.preview.step1': 'Standardmodus: ein zusammenhängender Prompt.',
        'promptStudio.howto.preview.step2': 'Expertenmodus: einzelne Abschnitte bearbeitbar.',
    },
    en: {
        'promptStudio.howto.summary': 'How it works',
        'promptStudio.howto.overview.intro':
            'Build AI prompts step by step — single prompts or multi-step workflow chains.',
        'promptStudio.howto.overview.step1': 'Pick a role: which perspective should the AI take?',
        'promptStudio.howto.overview.step2': 'Pick a task: only matching tasks appear for that role.',
        'promptStudio.howto.overview.step3': 'Fill in fields: inputs adapt automatically to the task.',
        'promptStudio.howto.overview.step4': 'Copy the prompt or anonymize it → AI Sanitizer.',
        'promptStudio.howto.overview.step5':
            'Workflows (e.g. song + cover): multiple prompts in sequence — step 2 builds on step 1\'s AI output.',
        'promptStudio.howto.overview.tip':
            'Your work state is saved automatically. Variants work well for e.g. 6 images with the same style but different subjects.',

        'promptStudio.howto.builder.summary': 'Builder',
        'promptStudio.howto.builder.intro': 'Set role, task, and matching input fields here.',
        'promptStudio.howto.builder.step1': 'Role = AI perspective (Business Analyst, Image Designer, Songwriter …).',
        'promptStudio.howto.builder.step2': 'Task = what you want to create (analysis, hero image, lyrics …).',
        'promptStudio.howto.builder.step3': 'Variants: one field varies (e.g. subject) — style and colors stay the same.',

        'promptStudio.howto.workflow.summary': 'Workflows',
        'promptStudio.howto.workflow.intro': 'Multi-step chains teach prompt timing: don\'t cram everything into one prompt.',
        'promptStudio.howto.workflow.step1': 'Pick a preset: song production, document analysis, business + visual …',
        'promptStudio.howto.workflow.step2': 'Per step: copy that prompt and anonymize if needed.',
        'promptStudio.howto.workflow.step3': 'Paste AI response from step N into "Previous output" → next step.',

        'promptStudio.howto.preview.summary': 'Preview',
        'promptStudio.howto.preview.intro': 'The finished prompt to copy into ChatGPT, Claude, Midjourney, etc.',
        'promptStudio.howto.preview.step1': 'Regular mode: one combined prompt block.',
        'promptStudio.howto.preview.step2': 'Tech mode: editable sections.',
    },
};

/**
 * @param {ToolsLocale} locale
 * @param {string} key
 * @returns {string}
 */
export function howtoT(locale, key) {
    return promptStudioHowtoLabels[locale]?.[key] ?? promptStudioHowtoLabels.en[key] ?? key;
}

/** @param {ToolsLocale} locale */
export function applyHowtoLabels(locale) {
    document.querySelectorAll('[data-howto-i18n], [data-i18n^="promptStudio.howto."]').forEach((el) => {
        const key = el.getAttribute('data-howto-i18n') ?? el.getAttribute('data-i18n');
        if (!key) return;
        const value = howtoT(locale, key);
        if (value) el.textContent = value;
    });
}
