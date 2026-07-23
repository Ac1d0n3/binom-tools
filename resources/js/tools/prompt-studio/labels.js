/** @typedef {'de' | 'en'} ToolsLocale */

import { promptStudioHowtoLabels } from './howto-labels.js';

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const promptStudioLabels = {
    de: {
        ...promptStudioHowtoLabels.de,
        'promptStudio.pageTitle': 'Prompt Studio',
        'promptStudio.pageLead':
            'Professionelle KI-Prompts in Sekunden — Rolle, Aufgabe und passende Felder.',
        'promptStudio.mode.regular': 'Standard',
        'promptStudio.mode.tech': 'Experte',
        'promptStudio.area.prompt': 'Prompt',
        'promptStudio.area.rule': 'Projekt-Rule',
        'promptStudio.area.agent': 'Agent',
        'promptStudio.library.title': 'Bibliothek',
        'promptStudio.tabs.workflows': 'Workflows',
        'promptStudio.tabs.library': 'Vorlagen',
        'promptStudio.tabs.favorites': 'Favoriten',
        'promptStudio.tabs.templates': 'Meine Vorlagen',
        'promptStudio.tabs.recent': 'Zuletzt',
        'promptStudio.searchPlaceholder': 'Suchen…',
        'promptStudio.builder.title': 'Erstellen',
        'promptStudio.builder.lead':
            'Oben den Bereich wählen (Prompt, Projekt-Rule oder Agent), dann Vorlage und Felder.',
        'promptStudio.builder.lead.prompt':
            'Bereich Prompt: Vorlage wählen, Felder ausfüllen, Prompt kopieren.',
        'promptStudio.builder.lead.rule':
            'Bereich Projekt-Rule: Rule-Vorlage wählen, Felder ausfüllen, .md herunterladen.',
        'promptStudio.builder.lead.agent':
            'Bereich Agent: Agent-Hilfe wählen, Kontext ausfüllen, .md herunterladen.',
        'promptStudio.steps.role': '1. Rolle',
        'promptStudio.steps.task': '2. Aufgabe',
        'promptStudio.steps.fill': '3. Ausfüllen',
        'promptStudio.roleHelp': 'Welche Perspektive soll die KI einnehmen?',
        'promptStudio.taskChanged': 'Felder angepasst an: {{task}}',
        'promptStudio.workflowSuggest': 'Passender Workflow: {{name}}',
        'promptStudio.workflowSuggestOptional': 'Optional als Workflow: {{name}}',
        'promptStudio.workflowStart': 'Workflow starten',
        'promptStudio.badge.workflow': 'Workflow',
        'promptStudio.outputKind.prompt': 'Ergebnis: {{kind}} — kopieren und in die KI einfügen.',
        'promptStudio.outputKind.md': 'Ergebnis: {{kind}} — Markdown-Datei für Projekt-/AI-Konfiguration (herunterladen).',
        'promptStudio.undo': 'Rückgängig',
        'promptStudio.redo': 'Wiederholen',
        'promptStudio.chain': 'Workflow',
        'promptStudio.chain.lead': 'Mehrschritt-Workflow — Prompt kopieren, KI-Antwort in „Vorherige Ausgabe“ einfügen, dann weiter.',
        'promptStudio.chain.prev': 'Vorheriger Schritt',
        'promptStudio.chain.next': 'Nächster Schritt',
        'promptStudio.chain.custom': '+ Eigener Block',
        'promptStudio.split': 'Aufgabe zerlegen',
        'promptStudio.optimize': 'Prompt verbessern',
        'promptStudio.import': 'Importieren',
        'promptStudio.export': 'Exportieren',
        'promptStudio.role': 'Rolle',
        'promptStudio.task': 'Aufgabe',
        'promptStudio.model': 'Ziel-AI',
        'promptStudio.modelPlan.label': 'Suno-Plan',
        'promptStudio.modelPlan.free': 'Free',
        'promptStudio.modelPlan.paid': 'Bezahlt',
        'promptStudio.fields.title': 'Aufgaben-Felder',
        'promptStudio.chainTitle': 'Workflow',
        'promptStudio.chainAdd': '+ Schritt',
        'promptStudio.continueTitle': 'Konversationsverlauf',
        'promptStudio.continueResponsePlaceholder': 'KI-Antwort einfügen…',
        'promptStudio.continue': 'Weiter',
        'promptStudio.copyMeta': 'Meta-Prompt kopieren',
        'promptStudio.applyMeta': 'Als Entwurf übernehmen',
        'promptStudio.metaSanitize': 'An Sanitizer senden',
        'promptStudio.preview': 'Live-Vorschau',
        'promptStudio.charCount': '{{count}} Zeichen',
        'promptStudio.charCount.limit': '{{count}} / {{limit}} Zeichen',
        'promptStudio.limit.lyrics':
            'Lyrics-Limit: {{limit}} Zeichen ({{plan}}). Style separat über Aufgabe „Suno Style-Prompt“ (1000).',
        'promptStudio.limit.style': 'Style-Prompt-Limit: {{limit}} Zeichen (Suno).',
        'promptStudio.limit.default': 'Zeichenlimit für diese AI: {{limit}}.',
        'promptStudio.copy': 'Prompt kopieren',
        'promptStudio.copied': 'Kopiert!',
        'promptStudio.sanitize': 'Anonymisieren →',
        'promptStudio.saveTemplate': 'Vorlage speichern',
        'promptStudio.advancedFields': 'Weitere Optionen',
        'promptStudio.variants.title': 'Varianten',
        'promptStudio.variants.field': 'Variierendes Feld',
        'promptStudio.variants.help':
            'Ein Feld variiert (z. B. Motiv), der Rest bleibt gleich. Pro Zeile ein Wert — dann durchblättern oder alle kopieren.',
        'promptStudio.variants.bulkPlaceholder': 'Ein Wert pro Zeile, z. B. 6 Motive',
        'promptStudio.variants.add': 'Werte hinzufügen',
        'promptStudio.variants.copyAll': 'Alle kopieren',
        'promptStudio.variants.prev': 'Zurück',
        'promptStudio.variants.next': 'Weiter',
        'promptStudio.variants.counter': 'Variante {{current}} von {{total}}',
        'promptStudio.forbidden.useSaved': 'Gespeicherte verbotene Wörter übernehmen',
        'promptStudio.artists.removed': 'Entfernte Künstlernamen: {{names}}',
        'promptStudio.bridge.banner':
            'Anonymisierter/restaurierter Prompt vom AI Sanitizer übernommen.',
        'promptStudio.bridge.apply': 'Als Entwurf übernehmen',
        'promptStudio.bridge.dismiss': 'Schließen',
        'promptStudio.chain.load': 'Laden',
        'promptStudio.chain.copy': 'Kopieren',
        'promptStudio.chain.empty': 'Kein Workflow aktiv',
        'promptStudio.chain.customBlock': 'Eigener Block',
        'promptStudio.chain.customPrompt': 'Was soll dieser Block tun?',
        'promptStudio.chain.remove': 'Schritt entfernen',
        'promptStudio.saveTemplate.prompt': 'Name der Vorlage',
        'promptStudio.split.prompt': 'Ziel für die Aufgabenteilung',
        'promptStudio.workspace.favorite': 'Favorit',
        'promptStudio.workspace.stepCount': '{{count}} Schritte',
        'promptStudio.workspace.variantCount': '{{count}} Varianten',
        'promptStudio.section.edited': 'bearbeitet',
        'promptStudio.continue.empty': 'Noch keine Konversation.',
        'promptStudio.continue.prompt': 'Prompt',
        'promptStudio.continue.response': 'Antwort',
        'promptStudio.continue.user': 'Nutzer',
        'promptStudio.continue.assistant': 'Assistent',
        'promptStudio.field.chipsPlaceholder': 'a, b, c',
        'promptStudio.pii.none': 'Keine PII-Funde erkannt.',
        'promptStudio.pii.summary': 'Fund(e) erkannt',
        'promptStudio.pii.type': 'Typ',
        'promptStudio.pii.value': 'Wert',
        'promptStudio.pii.confidence': 'Konfidenz',
        'promptStudio.pii.hint': 'Für vollständige Anonymisierung den AI Sanitizer öffnen.',
        'promptStudio.pii.types.personName': 'Personenname',
        'promptStudio.pii.types.email': 'E-Mail',
        'promptStudio.pii.types.phone': 'Telefon',
        'promptStudio.pii.types.postalAddress': 'Postadresse',
        'promptStudio.pii.types.geoCoordinates': 'Geo-Koordinaten',
        'promptStudio.pii.types.companyName': 'Firmenname',
        'promptStudio.pii.types.url': 'URL',
        'promptStudio.pii.types.ipv4': 'IPv4',
        'promptStudio.pii.types.ipv6': 'IPv6',
        'promptStudio.pii.types.creditCard': 'Kreditkarte',
        'promptStudio.pii.types.iban': 'IBAN',
        'promptStudio.pii.types.taxVatId': 'Steuer-ID',
        'promptStudio.pii.types.passportId': 'Passnummer',
        'promptStudio.pii.types.licensePlate': 'Kennzeichen',
        'promptStudio.pii.types.dateOfBirth': 'Geburtsdatum',
        'promptStudio.pii.types.customRegex': 'Eigenes Muster',
        'promptStudio.validation.missingRequired': 'Bitte alle Pflichtfelder ausfüllen.',
        'promptStudio.validation.importFailed': 'Import fehlgeschlagen.',
        'promptStudio.validation.configFailed': 'Prompt-Studio-Konfiguration konnte nicht geladen werden.',
        'promptStudio.validation.configFailedHint': 'Pfad: {{base}} — prüfe public/prompt-studio/config auf dem Server.',
        'promptStudio.tabs.roles': 'Rollen',
        'promptStudio.chain.howto1': 'Prompt des Schritts kopieren und in die KI einfügen.',
        'promptStudio.chain.howto2': 'Antwort in „Vorherige Ausgabe“ einfügen.',
        'promptStudio.chain.howto3': '„Nächster Schritt“ klicken.',
        'promptStudio.previewToggle': 'Vorschau ausblenden',
        'promptStudio.previewToggle.show': 'Vorschau einblenden',
        'promptStudio.previewToggle.hide': 'Vorschau ausblenden',
        'promptStudio.kind.prompt': 'Prompt',
        'promptStudio.kind.rule': 'Projekt-Rule',
        'promptStudio.kind.agentTask': 'Agent-Aufgabe',
        'promptStudio.category.all': 'Alle Kategorien',
        'promptStudio.category.image': 'Bilder',
        'promptStudio.category.video': 'Videos',
        'promptStudio.category.music': 'Musik',
        'promptStudio.category.code': 'Code / Dev',
        'promptStudio.category.business': 'Business',
        'promptStudio.category.writing': 'Text / Doku',
        'promptStudio.category.mail': 'E-Mails',
        'promptStudio.category.personal': 'Persönlich',
        'promptStudio.category.agent': 'Agent / Rules',
        'promptStudio.categoryFilter': 'Kategorie',
        'promptStudio.downloadMd': 'Als .md herunterladen',
        'promptStudio.workflow.save': 'Workflow speichern',
        'promptStudio.workflow.savePrompt': 'Name des Workflows',
        'promptStudio.roles.add': 'Rolle hinzufügen',
        'promptStudio.roles.addPrompt': 'Name der Rolle',
        'promptStudio.roles.custom': 'Eigene Rolle',
        'promptStudio.workspace.taskCount': '{{count}} Aufgaben',
        'promptStudio.empty.workspace': 'Keine Einträge',
        'promptStudio.empty.pickTask':
            'Oben Bereich und Vorlage wählen — dann geht’s los.',
        'promptStudio.empty.pick.prompt':
            'Im Bereich Prompt eine Aufgabe oder einen Workflow in der Bibliothek wählen.',
        'promptStudio.empty.pick.rule':
            'Im Bereich Projekt-Rule eine Rule-Vorlage in der Bibliothek wählen.',
        'promptStudio.empty.pick.agent':
            'Im Bereich Agent eine Agent-Hilfe in der Bibliothek wählen.',
    },
    en: {
        ...promptStudioHowtoLabels.en,
        'promptStudio.pageTitle': 'Prompt Studio',
        'promptStudio.pageLead':
            'Build professional AI prompts in seconds — role, task, and matching fields.',
        'promptStudio.mode.regular': 'Regular',
        'promptStudio.mode.tech': 'Tech',
        'promptStudio.area.prompt': 'Prompt',
        'promptStudio.area.rule': 'Project rule',
        'promptStudio.area.agent': 'Agent',
        'promptStudio.library.title': 'Library',
        'promptStudio.tabs.workflows': 'Workflows',
        'promptStudio.tabs.library': 'Templates',
        'promptStudio.tabs.favorites': 'Favorites',
        'promptStudio.tabs.templates': 'My templates',
        'promptStudio.tabs.recent': 'Recent',
        'promptStudio.searchPlaceholder': 'Search…',
        'promptStudio.builder.title': 'Build',
        'promptStudio.builder.lead':
            'Pick an area above (Prompt, Project rule, or Agent), then a template and fields.',
        'promptStudio.builder.lead.prompt':
            'Prompt area: pick a template, fill fields, copy the prompt.',
        'promptStudio.builder.lead.rule':
            'Project-rule area: pick a rule template, fill fields, download .md.',
        'promptStudio.builder.lead.agent':
            'Agent area: pick an agent helper, fill context, download .md.',
        'promptStudio.steps.role': '1. Role',
        'promptStudio.steps.task': '2. Task',
        'promptStudio.steps.fill': '3. Fill in',
        'promptStudio.roleHelp': 'Which perspective should the AI take?',
        'promptStudio.taskChanged': 'Fields adapted for: {{task}}',
        'promptStudio.workflowSuggest': 'Matching workflow: {{name}}',
        'promptStudio.workflowSuggestOptional': 'Optional as workflow: {{name}}',
        'promptStudio.workflowStart': 'Start workflow',
        'promptStudio.badge.workflow': 'Workflow',
        'promptStudio.outputKind.prompt': 'Output: {{kind}} — copy into your AI tool.',
        'promptStudio.outputKind.md': 'Output: {{kind}} — Markdown file for project/AI config (download).',
        'promptStudio.undo': 'Undo',
        'promptStudio.redo': 'Redo',
        'promptStudio.chain': 'Workflow',
        'promptStudio.chain.lead': 'Multi-step workflow — copy the prompt, paste the AI reply into Previous output, then continue.',
        'promptStudio.chain.prev': 'Previous step',
        'promptStudio.chain.next': 'Next step',
        'promptStudio.chain.custom': '+ Custom block',
        'promptStudio.split': 'Split task',
        'promptStudio.optimize': 'Improve prompt',
        'promptStudio.import': 'Import',
        'promptStudio.export': 'Export',
        'promptStudio.role': 'Role',
        'promptStudio.task': 'Task',
        'promptStudio.model': 'Target AI',
        'promptStudio.modelPlan.label': 'Suno plan',
        'promptStudio.modelPlan.free': 'Free',
        'promptStudio.modelPlan.paid': 'Paid',
        'promptStudio.fields.title': 'Task fields',
        'promptStudio.chainTitle': 'Workflow',
        'promptStudio.chainAdd': '+ Step',
        'promptStudio.continueTitle': 'Conversation history',
        'promptStudio.continueResponsePlaceholder': 'Paste AI response…',
        'promptStudio.continue': 'Continue',
        'promptStudio.copyMeta': 'Copy meta-prompt',
        'promptStudio.applyMeta': 'Apply as draft',
        'promptStudio.metaSanitize': 'Send to sanitizer',
        'promptStudio.preview': 'Live preview',
        'promptStudio.charCount': '{{count}} chars',
        'promptStudio.charCount.limit': '{{count}} / {{limit}} chars',
        'promptStudio.limit.lyrics':
            'Lyrics limit: {{limit}} characters ({{plan}}). Style separately via “Suno style prompt” (1000).',
        'promptStudio.limit.style': 'Style prompt limit: {{limit}} characters (Suno).',
        'promptStudio.limit.default': 'Character limit for this AI: {{limit}}.',
        'promptStudio.copy': 'Copy prompt',
        'promptStudio.copied': 'Copied!',
        'promptStudio.sanitize': 'Anonymize →',
        'promptStudio.saveTemplate': 'Save template',
        'promptStudio.advancedFields': 'More options',
        'promptStudio.variants.title': 'Variants',
        'promptStudio.variants.field': 'Varying field',
        'promptStudio.variants.help':
            'One field varies (e.g. motif), everything else stays the same. One value per line — then step through or copy all.',
        'promptStudio.variants.bulkPlaceholder': 'One value per line, e.g. 6 subjects',
        'promptStudio.variants.add': 'Add values',
        'promptStudio.variants.copyAll': 'Copy all',
        'promptStudio.variants.prev': 'Previous',
        'promptStudio.variants.next': 'Next',
        'promptStudio.variants.counter': 'Variant {{current}} of {{total}}',
        'promptStudio.forbidden.useSaved': 'Use saved forbidden words',
        'promptStudio.artists.removed': 'Removed artist names: {{names}}',
        'promptStudio.bridge.banner':
            'Anonymized/restored prompt received from AI Sanitizer.',
        'promptStudio.bridge.apply': 'Apply as draft',
        'promptStudio.bridge.dismiss': 'Dismiss',
        'promptStudio.chain.load': 'Load',
        'promptStudio.chain.copy': 'Copy',
        'promptStudio.chain.empty': 'No active workflow',
        'promptStudio.chain.customBlock': 'Custom block',
        'promptStudio.chain.customPrompt': 'What should this block do?',
        'promptStudio.chain.remove': 'Remove step',
        'promptStudio.saveTemplate.prompt': 'Template name',
        'promptStudio.split.prompt': 'Goal for splitting the task',
        'promptStudio.workspace.favorite': 'Favorite',
        'promptStudio.workspace.stepCount': '{{count}} steps',
        'promptStudio.workspace.variantCount': '{{count}} variants',
        'promptStudio.section.edited': 'edited',
        'promptStudio.continue.empty': 'No conversation yet.',
        'promptStudio.continue.prompt': 'Prompt',
        'promptStudio.continue.response': 'Response',
        'promptStudio.continue.user': 'User',
        'promptStudio.continue.assistant': 'Assistant',
        'promptStudio.field.chipsPlaceholder': 'a, b, c',
        'promptStudio.pii.none': 'No PII findings detected.',
        'promptStudio.pii.summary': 'finding(s) detected',
        'promptStudio.pii.type': 'Type',
        'promptStudio.pii.value': 'Value',
        'promptStudio.pii.confidence': 'Confidence',
        'promptStudio.pii.hint': 'Open AI Sanitizer for full anonymization.',
        'promptStudio.pii.types.personName': 'Person name',
        'promptStudio.pii.types.email': 'Email',
        'promptStudio.pii.types.phone': 'Phone',
        'promptStudio.pii.types.postalAddress': 'Postal address',
        'promptStudio.pii.types.geoCoordinates': 'Geo coordinates',
        'promptStudio.pii.types.companyName': 'Company name',
        'promptStudio.pii.types.url': 'URL',
        'promptStudio.pii.types.ipv4': 'IPv4',
        'promptStudio.pii.types.ipv6': 'IPv6',
        'promptStudio.pii.types.creditCard': 'Credit card',
        'promptStudio.pii.types.iban': 'IBAN',
        'promptStudio.pii.types.taxVatId': 'Tax/VAT ID',
        'promptStudio.pii.types.passportId': 'Passport ID',
        'promptStudio.pii.types.licensePlate': 'License plate',
        'promptStudio.pii.types.dateOfBirth': 'Date of birth',
        'promptStudio.pii.types.customRegex': 'Custom pattern',
        'promptStudio.validation.missingRequired': 'Please fill in all required fields.',
        'promptStudio.validation.importFailed': 'Import failed.',
        'promptStudio.validation.configFailed': 'Failed to load Prompt Studio configuration.',
        'promptStudio.validation.configFailedHint': 'Path: {{base}} — verify public/prompt-studio/config exists on the server.',
        'promptStudio.tabs.roles': 'Roles',
        'promptStudio.chain.howto1': 'Copy the step prompt into your AI tool.',
        'promptStudio.chain.howto2': 'Paste the reply into Previous output.',
        'promptStudio.chain.howto3': 'Click Next step.',
        'promptStudio.previewToggle': 'Hide preview',
        'promptStudio.previewToggle.show': 'Show preview',
        'promptStudio.previewToggle.hide': 'Hide preview',
        'promptStudio.kind.prompt': 'Prompt',
        'promptStudio.kind.rule': 'Project rule',
        'promptStudio.kind.agentTask': 'Agent task',
        'promptStudio.category.all': 'All categories',
        'promptStudio.category.image': 'Images',
        'promptStudio.category.video': 'Video',
        'promptStudio.category.music': 'Music',
        'promptStudio.category.code': 'Code / Dev',
        'promptStudio.category.business': 'Business',
        'promptStudio.category.writing': 'Writing / Docs',
        'promptStudio.category.mail': 'Email',
        'promptStudio.category.personal': 'Personal',
        'promptStudio.category.agent': 'Agent / Rules',
        'promptStudio.categoryFilter': 'Category',
        'promptStudio.kindFilter.all': 'All kinds',
        'promptStudio.downloadMd': 'Download .md',
        'promptStudio.workflow.save': 'Save workflow',
        'promptStudio.workflow.savePrompt': 'Workflow name',
        'promptStudio.roles.add': 'Add role',
        'promptStudio.roles.addPrompt': 'Role name',
        'promptStudio.roles.custom': 'Custom role',
        'promptStudio.workspace.taskCount': '{{count}} tasks',
        'promptStudio.empty.workspace': 'No items',
        'promptStudio.empty.pickTask':
            'Pick an area and template above to get started.',
        'promptStudio.empty.pick.prompt':
            'In the Prompt area, pick a task or workflow in the library.',
        'promptStudio.empty.pick.rule':
            'In the Project-rule area, pick a rule template in the library.',
        'promptStudio.empty.pick.agent':
            'In the Agent area, pick an agent helper in the library.',
    },
};

/**
 * @param {ToolsLocale} locale
 * @param {string} key
 * @param {Record<string, string | number>} [params]
 * @returns {string}
 */
export function t(locale, key, params = {}) {
    let value = promptStudioLabels[locale]?.[key] ?? promptStudioLabels.en[key] ?? key;
    for (const [name, replacement] of Object.entries(params)) {
        value = value.replaceAll(`{{${name}}}`, String(replacement));
    }
    return value;
}

/** @param {ToolsLocale} locale */
export function applyPromptStudioLabels(locale) {
    const knownKeys = new Set([
        ...Object.keys(promptStudioLabels.de),
        ...Object.keys(promptStudioHowtoLabels.de),
    ]);

    const applyTextLabel = (el, key) => {
        if (!key || !knownKeys.has(key)) return;
        const value = promptStudioLabels[locale]?.[key] ?? promptStudioLabels.en[key];
        if (!value) return;
        el.textContent = value;
    };

    document.querySelectorAll('[data-i18n]').forEach((el) => {
        applyTextLabel(el, el.getAttribute('data-i18n'));
    });

    document.querySelectorAll('[data-howto-i18n]').forEach((el) => {
        applyTextLabel(el, el.getAttribute('data-howto-i18n'));
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (!key || !(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement)) return;
        const value = promptStudioLabels[locale]?.[key] ?? promptStudioLabels.en[key];
        if (!value) return;
        el.placeholder = value;
    });

    document.querySelectorAll('[data-i18n-aria]').forEach((el) => {
        const key = el.getAttribute('data-i18n-aria');
        if (!key) return;
        const value = promptStudioLabels[locale]?.[key] ?? promptStudioLabels.en[key];
        if (!value) return;
        el.setAttribute('aria-label', value);
    });
}
