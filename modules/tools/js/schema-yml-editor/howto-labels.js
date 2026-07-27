/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const schemaHowtoLabels = {
    de: {
        'schema.howto.summary': 'So funktioniert\'s',
        'schema.howto.overview.intro':
            'Der Schema YML Editor bearbeitet schema.yml ausschließlich über Formularfelder — keine YAML-Tippfehler.',
        'schema.howto.overview.step1':
            'Im PII Policy Generator Spalten klassifizieren — Daten landen automatisch in localStorage.',
        'schema.howto.overview.step2':
            'Hier Model- und Spalten-Beschreibungen ergänzen sowie PII-Felder prüfen.',
        'schema.howto.overview.step3':
            'YAML-Vorschau kopieren und ins dbt-Projekt einfügen.',
        'schema.howto.overview.step4':
            'Änderungen synchronisieren sich mit dem PII Policy Generator (und umgekehrt).',
        'schema.howto.overview.tip':
            'Tipp: YAML kannst du direkt einfügen und bearbeiten — das Formular aktualisiert sich live mit.',
        'schema.howto.sync.intro': 'Es wird nur PII-Meta gespeichert (Kategorien, Rollen) — keine Model-Beschreibungen.',
        'schema.howto.sync.step1': 'Auto-Save: PII-Klassifizierung wird nach kurzer Verzögerung gespeichert.',
        'schema.howto.sync.step2': '„Aus Storage laden“: Explizit den letzten Stand aus localStorage holen.',
        'schema.howto.sync.step3': '„Storage leeren“: Setzt auf Demo-Defaults zurück.',
        'schema.howto.scenario.intro':
            'Das Zugriffsszenario bestimmt, ob schema.yml access_roles oder access_rules pro PII-Spalte schreibt.',
        'schema.howto.scenario.roles1': 'access_roles: Nur Rollen in der Liste erhalten unmaskierte Werte.',
        'schema.howto.scenario.roles2': 'Alle anderen Rollen erhalten maskierte Ausgabe.',
        'schema.howto.scenario.rules1': 'access_rules.masked: Diese Rollen sehen maskierte Werte.',
        'schema.howto.scenario.rules2': 'access_rules.unmasked: Diese Rollen sehen Rohdaten.',
        'schema.howto.scenario.rules3': 'Rollen in keiner Liste erhalten null — kein Zugriff auf die Spalte.',
        'schema.howto.access.intro':
            'Wie im PII Policy Generator: access_roles (Whitelist) oder access_rules (masked/unmasked).',
        'schema.howto.access.step1':
            'Use Access Roles aktiv: Pro Spalte access_roles in der YAML.',
        'schema.howto.access.step2':
            'Use Access Roles inaktiv: access_rules mit masked/unmasked pro PII-Spalte.',
        'schema.howto.access.step3':
            'Die Access-Mode-Zeile in der Model-Beschreibung wird beim Umschalten automatisch angepasst.',
        'schema.howto.access.tip':
            'Rollen kommagetrennt eingeben: analyst, support, admin',
        'schema.howto.model.intro': 'Model-Metadaten und Beschreibung für dbt docs.',
        'schema.howto.columns.intro': 'Spalten mit Beschreibung und PII-Klassifizierung — alles über Selects und Textfelder.',
        'schema.howto.yaml.intro':
            'YAML live bearbeiten oder einfügen — Formular und Editor bleiben synchron (Zwei-Wege).',
    },
    en: {
        'schema.howto.summary': 'How it works',
        'schema.howto.overview.intro':
            'The Schema YML Editor edits schema.yml only through form fields — no YAML typos.',
        'schema.howto.overview.step1':
            'Classify columns in the PII Policy Generator — data is saved to localStorage automatically.',
        'schema.howto.overview.step2':
            'Add model and column descriptions here and review PII fields.',
        'schema.howto.overview.step3':
            'Copy the YAML preview into your dbt project.',
        'schema.howto.overview.step4':
            'Changes sync with the PII Policy Generator (and vice versa).',
        'schema.howto.overview.tip':
            'Tip: you can paste and edit YAML directly — the form updates live with it.',
        'schema.howto.sync.intro': 'Only PII meta is stored (categories, roles) — not model descriptions.',
        'schema.howto.sync.step1': 'Auto-save: PII classification is saved after a short delay.',
        'schema.howto.sync.step2': 'Load from storage: explicitly fetch the latest state from localStorage.',
        'schema.howto.sync.step3': 'Clear storage: resets to demo defaults.',
        'schema.howto.scenario.intro':
            'The access scenario controls whether schema.yml writes access_roles or access_rules per PII column.',
        'schema.howto.scenario.roles1': 'access_roles: only listed roles receive unmasked values.',
        'schema.howto.scenario.roles2': 'All other roles receive masked output.',
        'schema.howto.scenario.rules1': 'access_rules.masked: these roles see masked values.',
        'schema.howto.scenario.rules2': 'access_rules.unmasked: these roles see raw data.',
        'schema.howto.scenario.rules3': 'Roles in neither list get null — no access to the column.',
        'schema.howto.access.intro':
            'Same as the PII Policy Generator: access_roles (whitelist) or access_rules (masked/unmasked).',
        'schema.howto.access.step1':
            'Use Access Roles on: per-column access_roles in YAML.',
        'schema.howto.access.step2':
            'Use Access Roles off: access_rules with masked/unmasked per PII column.',
        'schema.howto.access.step3':
            'The access mode line in the model description updates automatically when toggling.',
        'schema.howto.access.tip':
            'Enter roles comma-separated: analyst, support, admin',
        'schema.howto.model.intro': 'Model metadata and description for dbt docs.',
        'schema.howto.columns.intro': 'Columns with description and PII classification — all via selects and text fields.',
        'schema.howto.yaml.intro':
            'Edit or paste YAML live — form and editor stay in sync (two-way).',
    },
};
