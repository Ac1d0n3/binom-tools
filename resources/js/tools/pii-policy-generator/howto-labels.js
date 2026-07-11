/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const piiHowtoLabels = {
    de: {
        'pii.howto.summary': 'So funktioniert\'s',

        'pii.howto.overview.intro':
            'Schritt 2/4 — Zielzustand nach Review: pii_details schema.yml-Beispiel und Secure Model. Voraussetzung: macros/pii_governance.sql aus Step 1.',
        'pii.howto.overview.step1':
            'Spalten einfügen und PII-Typ + Scope zuweisen (oder Vorschläge nutzen). Felder ohne PII auf none setzen.',
        'pii.howto.overview.step2':
            'Zugriffsmodell wählen: access_roles (Whitelist) oder access_rules (masked/unmasked).',
        'pii.howto.overview.step3':
            'Execute klicken — schema.yml mit meta.pii_details und pii-reviewed: true wird generiert.',
        'pii.howto.overview.step4':
            'Secure-View-SQL nach models/marts/example_table_secure.sql kopieren — nutzt pii_column_for_role() aus Step 1.',
        'pii.howto.overview.step5':
            'sources.yml anlegen (siehe Model-Beispiel) und schema.yml ins dbt-Projekt kopieren.',
        'pii.howto.overview.step6':
            'In dbt_project.yml die Variable pii_user_role setzen — z. B. analyst für Analysten, admin für Vollzugriff.',
        'pii.howto.overview.step7':
            'Testen: dbt test --select test_type:generic und dbt run --select example_table_secure --vars \'{"pii_user_role": "analyst"}\'',
        'pii.howto.overview.step8':
            'Step 3 (Table Gate): unreviewed Models verstecken bis pii-reviewed: true. Step 4 (PII Recommend): Spalten-Audits.',
        'pii.howto.overview.step9':
            'Nach Review hierher zurück — pii_details + pii-reviewed: true ist der Zielzustand.',
        'pii.howto.overview.tip':
            'Tipp: Step 1 Makros müssen im Projekt liegen, bevor das Secure Model lauffähig ist.',

        'pii.howto.scenario.intro':
            'Das Zugriffsszenario bestimmt, wie pii_column_for_role() PII-Spalten pro Benutzerrolle ausgibt.',
        'pii.howto.scenario.roles1':
            'access_roles: Nur Rollen in der Liste erhalten unmaskierte Werte.',
        'pii.howto.scenario.roles2':
            'Alle anderen Rollen erhalten maskierte Ausgabe (z. B. ***Jo…hn).',
        'pii.howto.scenario.rules1':
            'access_rules.masked: Diese Rollen sehen maskierte Werte.',
        'pii.howto.scenario.rules2':
            'access_rules.unmasked: Diese Rollen sehen Rohdaten.',
        'pii.howto.scenario.rules3':
            'Rollen in keiner Liste erhalten null — kein Zugriff auf die Spalte.',

        'pii.howto.access.intro':
            'Hier legst du fest, welche Rollen PII sehen dürfen und in welcher Form.',
        'pii.howto.access.step1':
            'Use Access Roles aktiv: Pro Spalte eine Whitelist (wer darf unmaskiert lesen).',
        'pii.howto.access.step2':
            'Use Access Roles inaktiv: Zwei Listen — masked (tokenisiert) und unmasked (Rohdaten).',
        'pii.howto.access.step3':
            'Standard access_roles gelten für alle PII-Spalten, sofern du nichts pro Spalte überschreibst.',
        'pii.howto.access.tip':
            'Rollen kommagetrennt eingeben: analyst, support, admin',

        'pii.howto.model.intro':
            'Model-Metadaten landen in der schema.yml-Beschreibung und steuern die generierten Artefakte.',
        'pii.howto.model.step1':
            'Model-Name: dbt-Model-Identifier (z. B. example_table).',
        'pii.howto.model.step2':
            'Quell-Tabelle: Format raw.example_table — wird in sources.yml und SQL referenziert.',
        'pii.howto.model.step3':
            'PII details version: Hash zur Nachverfolgung von Policy-Änderungen (Audit).',
        'pii.howto.model.step4':
            'Standard-Scope: Beeinflusst Kategorie-Vorschläge (internal vs. external).',

        'pii.howto.columns.intro':
            'Jede Spalte wird als PII oder nicht-PII klassifiziert. Nur PII-Spalten erhalten meta.pii_details.',
        'pii.howto.columns.step1':
            'Bulk-Insert: Spaltennamen kommagetrennt einfügen — Kategorien werden automatisch vorgeschlagen.',
        'pii.howto.columns.step2':
            'PII-Typ none: Für IDs, Timestamps und nicht-sensitive Felder (z. B. info_mail).',
        'pii.howto.columns.step3':
            'Kategorie = Typ + Scope, z. B. name_internal oder email_external.',
        'pii.howto.columns.step4':
            'Bei access_roles: Pro Spalte optional eigene Rollenliste setzen.',
        'pii.howto.columns.tip':
            'Nach Änderungen immer Execute klicken, damit YAML und Macro aktuell bleiben.',

        'pii.howto.yaml.intro':
            'Die schema.yml ist die produktive Policy-Definition — meta.pii_details + pii-reviewed: true.',
        'pii.howto.yaml.step1':
            'Execute aktualisiert das YAML aus dem Formular.',
        'pii.howto.yaml.step2':
            'Load from YAML: Bestehende schema.yml importieren und im Tool bearbeiten.',
        'pii.howto.yaml.step3':
            'Kopiere das YAML nach models/schema/ in dein dbt-Projekt — Zielzustand nach Review.',
        'pii.howto.yaml.tip':
            'Vergleiche mit Step 3: dort pii_recommend + pii-reviewed: false (Empfehlungs-Zustand).',

        'pii.howto.macro.intro':
            'Laufzeit-Makros kommen aus Step 1 (PII Macro Generator) — hier nur Verweis und Beispiel.',
        'pii.howto.macro.step1':
            'Kopiere pii_governance.sql aus Step 1 nach macros/ — falls noch nicht geschehen.',
        'pii.howto.macro.step2':
            'pii_mask und pii_column_for_role werden dort generiert — warehouse-spezifische SQL-Syntax.',
        'pii.howto.macro.step3':
            'pii_column_for_role liest meta.pii_details aus dem dbt-Graph zur Laufzeit.',
        'pii.howto.macro.step4':
            'Dieses Tool erzeugt kein separates Macro mehr — vermeidet Duplikate.',
        'pii.howto.macro.tip':
            'Nach Warehouse- oder Rollen-Änderung in Step 1: Makro neu kopieren.',

        'pii.howto.policy.intro':
            'Die Policy-YAML ist eine Governance-Referenz für Audits und Dokumentation — nicht runtime-kritisch.',
        'pii.howto.policy.step1':
            'Enthält access_mode, Rollenlisten und Spalten-Kategorien als lesbare Übersicht.',
        'pii.howto.policy.step2':
            'Kopiere nach macros/pii_policy.yml oder in euer Governance-Repo.',
        'pii.howto.policy.step3':
            'Nutze sie für Reviews: Welche Rolle sieht welche Spalte in welcher Form?',

        'pii.howto.modelExample.intro':
            'Das SQL-Modell zeigt den Secure View mit pii_column_for_role(column, role, model_name) aus Step 1.',
        'pii.howto.modelExample.step1':
            'Lege models/sources.yml an (siehe Snippet unten).',
        'pii.howto.modelExample.step2':
            'Kopiere das Model nach models/marts/example_table_secure.sql.',
        'pii.howto.modelExample.step3':
            'Setze var pii_user_role in dbt_project.yml oder per --vars beim dbt run.',
        'pii.howto.modelExample.step4':
            'Teste mit analyst (maskiert) und admin (unmaskiert) — Ergebnis hängt vom Zugriffsmodell ab.',
        'pii.howto.modelExample.sourcesTitle': 'sources.yml Beispiel:',
    },
    en: {
        'pii.howto.summary': 'How it works',

        'pii.howto.overview.intro':
            'Step 2/4 — target state after review: pii_details schema.yml example and secure model. Prerequisite: macros/pii_governance.sql from step 1.',
        'pii.howto.overview.step1':
            'Insert columns and assign PII type + scope (or use suggestions). Set non-sensitive fields to none.',
        'pii.howto.overview.step2':
            'Choose access model: access_roles (whitelist) or access_rules (masked/unmasked).',
        'pii.howto.overview.step3':
            'Click Execute — schema.yml with meta.pii_details and pii-reviewed: true is generated.',
        'pii.howto.overview.step4':
            'Copy secure view SQL to models/marts/example_table_secure.sql — uses pii_column_for_role() from step 1.',
        'pii.howto.overview.step5':
            'Create sources.yml (see model example) and copy schema.yml into your dbt project.',
        'pii.howto.overview.step6':
            'Set var pii_user_role in dbt_project.yml — e.g. analyst for analysts, admin for full access.',
        'pii.howto.overview.step7':
            'Test: dbt test --select test_type:generic and dbt run --select example_table_secure --vars \'{"pii_user_role": "analyst"}\'',
        'pii.howto.overview.step8':
            'Step 3 (Table Gate): hide unreviewed models until pii-reviewed: true. Step 4 (PII Recommend): column audits.',
        'pii.howto.overview.step9':
            'After review return here — pii_details + pii-reviewed: true is the target state.',
        'pii.howto.overview.tip':
            'Tip: Step 1 macros must be in the project before the secure model runs.',

        'pii.howto.scenario.intro':
            'The access scenario defines how pii_column_for_role() outputs PII columns per user role.',
        'pii.howto.scenario.roles1':
            'access_roles: Only listed roles receive unmasked values.',
        'pii.howto.scenario.roles2':
            'All other roles receive masked output (e.g. ***Jo…hn).',
        'pii.howto.scenario.rules1':
            'access_rules.masked: These roles see masked values.',
        'pii.howto.scenario.rules2':
            'access_rules.unmasked: These roles see raw data.',
        'pii.howto.scenario.rules3':
            'Roles in neither list receive null — no access to the column.',

        'pii.howto.access.intro':
            'Configure which roles may see PII and in what form.',
        'pii.howto.access.step1':
            'Use Access Roles on: Per-column whitelist (who may read unmasked).',
        'pii.howto.access.step2':
            'Use Access Roles off: Two lists — masked (tokenized) and unmasked (raw).',
        'pii.howto.access.step3':
            'Default access_roles apply to all PII columns unless overridden per column.',
        'pii.howto.access.tip':
            'Enter roles comma-separated: analyst, support, admin',

        'pii.howto.model.intro':
            'Model metadata lands in the schema.yml description and drives generated artifacts.',
        'pii.howto.model.step1':
            'Model name: dbt model identifier (e.g. example_table).',
        'pii.howto.model.step2':
            'Source table: Format raw.example_table — referenced in sources.yml and SQL.',
        'pii.howto.model.step3':
            'PII details version: Hash for tracking policy changes (audit).',
        'pii.howto.model.step4':
            'Default scope: Influences category suggestions (internal vs. external).',

        'pii.howto.columns.intro':
            'Each column is classified as PII or non-PII. Only PII columns receive meta.pii_details.',
        'pii.howto.columns.step1':
            'Bulk insert: Paste comma-separated column names — categories are suggested automatically.',
        'pii.howto.columns.step2':
            'PII type none: For IDs, timestamps, and non-sensitive fields (e.g. info_mail).',
        'pii.howto.columns.step3':
            'Category = type + scope, e.g. name_internal or email_external.',
        'pii.howto.columns.step4':
            'With access_roles: Optionally set per-column role lists.',
        'pii.howto.columns.tip':
            'Always click Execute after changes so YAML and macro stay in sync.',

        'pii.howto.yaml.intro':
            'schema.yml is the production policy definition — meta.pii_details + pii-reviewed: true.',
        'pii.howto.yaml.step1':
            'Execute updates YAML from the form.',
        'pii.howto.yaml.step2':
            'Load from YAML: Import an existing schema.yml and edit in the tool.',
        'pii.howto.yaml.step3':
            'Copy the YAML to models/schema/ in your dbt project — target state after review.',
        'pii.howto.yaml.tip':
            'Compare with step 3: there pii_recommend + pii-reviewed: false (recommendation state).',

        'pii.howto.macro.intro':
            'Runtime macros come from step 1 (PII Macro Generator) — reference and example only here.',
        'pii.howto.macro.step1':
            'Copy pii_governance.sql from step 1 to macros/ — if not done yet.',
        'pii.howto.macro.step2':
            'pii_mask and pii_column_for_role are generated there — warehouse-specific SQL syntax.',
        'pii.howto.macro.step3':
            'pii_column_for_role reads meta.pii_details from the dbt graph at runtime.',
        'pii.howto.macro.step4':
            'This tool no longer generates a separate macro — avoids duplication.',
        'pii.howto.macro.tip':
            'After warehouse or role changes in step 1: re-copy the macro.',

        'pii.howto.policy.intro':
            'Policy YAML is a governance reference for audits and documentation — not runtime-critical.',
        'pii.howto.policy.step1':
            'Contains access_mode, role lists, and column categories as a readable overview.',
        'pii.howto.policy.step2':
            'Copy to macros/pii_policy.yml or your governance repo.',
        'pii.howto.policy.step3':
            'Use for reviews: which role sees which column in what form?',

        'pii.howto.modelExample.intro':
            'The SQL model shows the secure view with pii_column_for_role(column, role, model_name) from step 1.',
        'pii.howto.modelExample.step1':
            'Create models/sources.yml (see snippet below).',
        'pii.howto.modelExample.step2':
            'Copy the model to models/marts/example_table_secure.sql.',
        'pii.howto.modelExample.step3':
            'Set var pii_user_role in dbt_project.yml or via --vars on dbt run.',
        'pii.howto.modelExample.step4':
            'Test with analyst (masked) and admin (unmasked) — outcome depends on the access model.',
        'pii.howto.modelExample.sourcesTitle': 'sources.yml example:',
    },
};
