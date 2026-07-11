/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const piiHowtoLabels = {
    de: {
        'pii.howto.summary': 'So funktioniert\'s',

        'pii.howto.overview.intro':
            'Dieses Tool führt dich durch die erste lauffähige PII Governance in einem dbt-Projekt — von der Spalten-Klassifizierung bis zum getesteten Secure-View.',
        'pii.howto.overview.step1':
            'Spalten einfügen und PII-Typ + Scope zuweisen (oder Vorschläge nutzen). Felder ohne PII auf none setzen.',
        'pii.howto.overview.step2':
            'Zugriffsmodell wählen: access_roles (Whitelist) oder access_rules (masked/unmasked).',
        'pii.howto.overview.step3':
            'Execute klicken — schema.yml mit meta.pii_details wird generiert.',
        'pii.howto.overview.step4':
            'DBT Macro nach macros/pii_governance.sql kopieren.',
        'pii.howto.overview.step5':
            'sources.yml anlegen (siehe Model-Beispiel) und Secure-View-SQL nach models/marts/example_table_secure.sql kopieren.',
        'pii.howto.overview.step6':
            'In dbt_project.yml die Variable pii_user_role setzen — z. B. analyst für Analysten, admin für Vollzugriff.',
        'pii.howto.overview.step7':
            'Testen: dbt run --select example_table_secure --vars \'{"pii_user_role": "analyst"}\'',
        'pii.howto.overview.step8':
            'Ergebnis prüfen: analyst sieht maskierte PII, admin sieht Rohdaten (je nach gewähltem Modus).',
        'pii.howto.overview.tip':
            'Tipp: Beginne mit access_roles — einfacher für den Einstieg. access_rules eignet sich, wenn du explizit masked und unmasked Rollen trennen willst.',

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
            'Die schema.yml ist die zentrale Policy-Definition für dbt docs und Governance.',
        'pii.howto.yaml.step1':
            'Execute aktualisiert das YAML aus dem Formular.',
        'pii.howto.yaml.step2':
            'Load from YAML: Bestehende schema.yml importieren und im Tool bearbeiten.',
        'pii.howto.yaml.step3':
            'Kopiere das YAML nach models/schema.yml (oder models/marts/schema.yml) in dein dbt-Projekt.',
        'pii.howto.yaml.tip':
            'Das YAML-Format entspricht dbt version: 2 mit models → columns → meta.pii_details.',

        'pii.howto.macro.intro':
            'Das Macro implementiert die Laufzeit-Logik für rollenbasierte Maskierung in SQL.',
        'pii.howto.macro.step1':
            'Kopiere den gesamten Block nach macros/pii_governance.sql.',
        'pii.howto.macro.step2':
            'pii_mask: Erzeugt maskierte Ausgabe für sensitive Spalten.',
        'pii.howto.macro.step3':
            'pii_column_for_role: Entscheidet pro Spalte und Rolle zwischen Rohdaten, Maske oder null.',
        'pii.howto.macro.step4':
            'pii_meta_for_column: Liest die Policy aus dem eingebetteten Mapping (aus deiner schema.yml abgeleitet).',
        'pii.howto.macro.tip':
            'Nach Policy-Änderungen Macro neu generieren und ersetzen — das Mapping ist modell-spezifisch.',

        'pii.howto.policy.intro':
            'Die Policy-YAML ist eine Governance-Referenz für Audits und Dokumentation — nicht runtime-kritisch.',
        'pii.howto.policy.step1':
            'Enthält access_mode, Rollenlisten und Spalten-Kategorien als lesbare Übersicht.',
        'pii.howto.policy.step2':
            'Kopiere nach macros/pii_policy.yml oder in euer Governance-Repo.',
        'pii.howto.policy.step3':
            'Nutze sie für Reviews: Welche Rolle sieht welche Spalte in welcher Form?',

        'pii.howto.modelExample.intro':
            'Das SQL-Modell zeigt, wie du PII-Spalten im SELECT mit pii_column_for_role() absicherst.',
        'pii.howto.modelExample.step1':
            'Lege models/sources.yml an (siehe Snippet unten).',
        'pii.howto.modelExample.step2':
            'Kopiere das Model nach models/marts/example_table_secure.sql.',
        'pii.howto.modelExample.step3':
            'Setze var pii_user_role in dbt_project.yml oder per --vars beim dbt run.',
        'pii.howto.modelExample.step4':
            'Teste mit analyst (maskiert) und admin (unmaskiert) und vergleiche die Ausgabe.',
        'pii.howto.modelExample.sourcesTitle': 'sources.yml Beispiel:',
    },
    en: {
        'pii.howto.summary': 'How it works',

        'pii.howto.overview.intro':
            'This tool walks you through your first runnable PII governance in a dbt project — from column classification to a tested secure view.',
        'pii.howto.overview.step1':
            'Insert columns and assign PII type + scope (or use suggestions). Set non-sensitive fields to none.',
        'pii.howto.overview.step2':
            'Choose access model: access_roles (whitelist) or access_rules (masked/unmasked).',
        'pii.howto.overview.step3':
            'Click Execute — schema.yml with meta.pii_details is generated.',
        'pii.howto.overview.step4':
            'Copy the DBT macro to macros/pii_governance.sql.',
        'pii.howto.overview.step5':
            'Create sources.yml (see model example) and copy the secure view SQL to models/marts/example_table_secure.sql.',
        'pii.howto.overview.step6':
            'Set var pii_user_role in dbt_project.yml — e.g. analyst for analysts, admin for full access.',
        'pii.howto.overview.step7':
            'Test: dbt run --select example_table_secure --vars \'{"pii_user_role": "analyst"}\'',
        'pii.howto.overview.step8':
            'Verify: analyst sees masked PII, admin sees raw data (depending on the selected mode).',
        'pii.howto.overview.tip':
            'Tip: Start with access_roles — simpler for onboarding. access_rules fits when you need explicit masked vs. unmasked role lists.',

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
            'schema.yml is the central policy definition for dbt docs and governance.',
        'pii.howto.yaml.step1':
            'Execute updates YAML from the form.',
        'pii.howto.yaml.step2':
            'Load from YAML: Import an existing schema.yml and edit in the tool.',
        'pii.howto.yaml.step3':
            'Copy the YAML to models/schema.yml (or models/marts/schema.yml) in your dbt project.',
        'pii.howto.yaml.tip':
            'YAML format follows dbt version: 2 with models → columns → meta.pii_details.',

        'pii.howto.macro.intro':
            'The macro implements runtime logic for role-based masking in SQL.',
        'pii.howto.macro.step1':
            'Copy the entire block to macros/pii_governance.sql.',
        'pii.howto.macro.step2':
            'pii_mask: Produces masked output for sensitive columns.',
        'pii.howto.macro.step3':
            'pii_column_for_role: Decides per column and role between raw, masked, or null.',
        'pii.howto.macro.step4':
            'pii_meta_for_column: Reads policy from the embedded mapping (derived from your schema.yml).',
        'pii.howto.macro.tip':
            'After policy changes, regenerate and replace the macro — the mapping is model-specific.',

        'pii.howto.policy.intro':
            'Policy YAML is a governance reference for audits and documentation — not runtime-critical.',
        'pii.howto.policy.step1':
            'Contains access_mode, role lists, and column categories as a readable overview.',
        'pii.howto.policy.step2':
            'Copy to macros/pii_policy.yml or your governance repo.',
        'pii.howto.policy.step3':
            'Use for reviews: which role sees which column in what form?',

        'pii.howto.modelExample.intro':
            'The SQL model shows how to secure PII columns in SELECT with pii_column_for_role().',
        'pii.howto.modelExample.step1':
            'Create models/sources.yml (see snippet below).',
        'pii.howto.modelExample.step2':
            'Copy the model to models/marts/example_table_secure.sql.',
        'pii.howto.modelExample.step3':
            'Set var pii_user_role in dbt_project.yml or via --vars on dbt run.',
        'pii.howto.modelExample.step4':
            'Test with analyst (masked) and admin (unmasked) and compare output.',
        'pii.howto.modelExample.sourcesTitle': 'sources.yml example:',
    },
};
