/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const govMacroHowtoLabels = {
    de: {
        'govMacro.howto.summary': 'So funktioniert\'s',

        'govMacro.howto.overview.intro':
            'Schritt 1/4 der dbt PII Governance Einrichtung — hier kopierst du Laufzeit-Makros, Generic Tests und SETUP.md in dein dbt-Projekt.',
        'govMacro.howto.overview.step1':
            'Warehouse wählen — steuert Mask- und Null-SQL in pii_governance.sql (Snowflake, BigQuery, …).',
        'govMacro.howto.overview.step2':
            'Access-Rollen und access_groups setzen — Einstellungen werden mit Steps 2–4 geteilt (localStorage).',
        'govMacro.howto.overview.step3':
            'pii_governance.sql nach macros/ kopieren — Basis für pii_column_for_role() in Step 2.',
        'govMacro.howto.overview.step4':
            'pii_reviewed.sql nach tests/generic/ kopieren — CI-Gate für meta.pii-reviewed: true.',
        'govMacro.howto.overview.step5':
            'SETUP.md ins Projekt-Root kopieren — Dateibaum aller 4 Steps und dbt-Befehle.',
        'govMacro.howto.overview.step6':
            'Weiter zu Step 2 (Policy Generator) für pii_details schema.yml und Secure Model.',
        'govMacro.howto.overview.step7':
            'Step 3 (Table Gate) für unreviewed Models; Step 4 (PII Recommend) für Spalten-Audits.',
        'govMacro.howto.overview.tip':
            'Tipp: Warehouse und Rollen einmal hier setzen — in Steps 2–4 sind sie bereits vorausgefüllt.',

        'govMacro.howto.warehouse.intro':
            'Das Warehouse bestimmt die SQL-Syntax in den generierten Makros — Maskierung und Null-Werte sind dialect-spezifisch.',
        'govMacro.howto.warehouse.step1':
            'Snowflake, BigQuery, Redshift, Databricks oder Postgres wählen.',
        'govMacro.howto.warehouse.step2':
            'Nach Änderung wird pii_governance.sql automatisch neu generiert.',
        'govMacro.howto.warehouse.tip':
            'Muss zum Warehouse deines dbt-Profils passen.',

        'govMacro.howto.access.intro':
            'Zugriffsmodell und Model access_groups — werden in Steps 2–4 für YAML-Beispiele mitgenutzt.',
        'govMacro.howto.access.step1':
            'Use Access Roles: Whitelist pro Spalte (wer sieht unmaskiert).',
        'govMacro.howto.access.step2':
            'Use Access Roles aus: masked- und unmasked-Listen.',
        'govMacro.howto.access.step3':
            'Default model access_groups: meta.access_groups für Gate (Step 3) und neue Models.',
        'govMacro.howto.access.tip':
            'Rollen kommagetrennt: analyst, support, admin, dpo',

        'govMacro.howto.governance.intro':
            'Laufzeit-Makros — lesen meta.pii_details aus schema.yml (Step 2), nicht pii_recommend.',
        'govMacro.howto.governance.step1':
            'pii_mask: maskierte Ausgabe je nach Kategorie.',
        'govMacro.howto.governance.step2':
            'pii_column_for_role: Rohdaten, Maske oder null pro Rolle.',
        'govMacro.howto.governance.step3':
            'pii_effective_meta / pii_model_accessible: liest Graph-Metadaten zur Laufzeit.',
        'govMacro.howto.governance.tip':
            'Nach Warehouse- oder Rollen-Änderung neu kopieren und in macros/pii_governance.sql ersetzen.',

        'govMacro.howto.test.intro':
            'Generic Test als CI-Gate — schlägt fehl, wenn ein Model meta.pii-reviewed: true fehlt.',
        'govMacro.howto.test.step1':
            'Kopiere nach tests/generic/pii_reviewed.sql.',
        'govMacro.howto.test.step2':
            'Ausführen: dbt test --select test_type:generic',
        'govMacro.howto.test.tip':
            'Erst nach manuellem Review pii-reviewed auf true setzen (Step 2 Beispiel).',

        'govMacro.howto.setup.intro':
            'SETUP.md dokumentiert den kompletten Copy-Paste-Ablauf aller 4 Steps.',
        'govMacro.howto.setup.step1':
            'Dateibaum: welche Datei aus welchem Step wohin gehört.',
        'govMacro.howto.setup.step2':
            'dbt_project.yml vars (pii_user_role) und Verify-Befehle.',
        'govMacro.howto.setup.tip':
            'Im Projekt-Root ablegen — Referenz für das Team.',
    },
    en: {
        'govMacro.howto.summary': 'How it works',

        'govMacro.howto.overview.intro':
            'Step 1/4 of the dbt PII governance setup — copy runtime macros, generic tests, and SETUP.md into your dbt project.',
        'govMacro.howto.overview.step1':
            'Choose warehouse — controls mask and null SQL in pii_governance.sql (Snowflake, BigQuery, …).',
        'govMacro.howto.overview.step2':
            'Set access roles and access_groups — settings shared with steps 2–4 (localStorage).',
        'govMacro.howto.overview.step3':
            'Copy pii_governance.sql to macros/ — basis for pii_column_for_role() in step 2.',
        'govMacro.howto.overview.step4':
            'Copy pii_reviewed.sql to tests/generic/ — CI gate for meta.pii-reviewed: true.',
        'govMacro.howto.overview.step5':
            'Copy SETUP.md to project root — file tree for all 4 steps and dbt commands.',
        'govMacro.howto.overview.step6':
            'Continue to step 2 (Policy Generator) for pii_details schema.yml and secure model.',
        'govMacro.howto.overview.step7':
            'Step 3 (Table Gate) for unreviewed models; step 4 (PII Recommend) for column audits.',
        'govMacro.howto.overview.tip':
            'Tip: Set warehouse and roles once here — steps 2–4 are pre-filled.',

        'govMacro.howto.warehouse.intro':
            'Warehouse determines SQL syntax in generated macros — masking and null values are dialect-specific.',
        'govMacro.howto.warehouse.step1':
            'Choose Snowflake, BigQuery, Redshift, Databricks, or Postgres.',
        'govMacro.howto.warehouse.step2':
            'pii_governance.sql regenerates automatically after changes.',
        'govMacro.howto.warehouse.tip':
            'Must match the warehouse of your dbt profile.',

        'govMacro.howto.access.intro':
            'Access model and model access_groups — used in steps 2–4 YAML examples too.',
        'govMacro.howto.access.step1':
            'Use Access Roles: per-column whitelist (who sees unmasked).',
        'govMacro.howto.access.step2':
            'Use Access Roles off: masked and unmasked lists.',
        'govMacro.howto.access.step3':
            'Default model access_groups: meta.access_groups for gate (step 3) and new models.',
        'govMacro.howto.access.tip':
            'Enter roles comma-separated: analyst, support, admin, dpo',

        'govMacro.howto.governance.intro':
            'Runtime macros — read meta.pii_details from schema.yml (step 2), not pii_recommend.',
        'govMacro.howto.governance.step1':
            'pii_mask: masked output per category.',
        'govMacro.howto.governance.step2':
            'pii_column_for_role: raw, masked, or null per role.',
        'govMacro.howto.governance.step3':
            'pii_effective_meta / pii_model_accessible: reads graph metadata at runtime.',
        'govMacro.howto.governance.tip':
            'Re-copy and replace macros/pii_governance.sql after warehouse or role changes.',

        'govMacro.howto.test.intro':
            'Generic test as CI gate — fails when a model is missing meta.pii-reviewed: true.',
        'govMacro.howto.test.step1':
            'Copy to tests/generic/pii_reviewed.sql.',
        'govMacro.howto.test.step2':
            'Run: dbt test --select test_type:generic',
        'govMacro.howto.test.tip':
            'Set pii-reviewed to true only after manual review (step 2 example).',

        'govMacro.howto.setup.intro':
            'SETUP.md documents the full copy-paste flow for all 4 steps.',
        'govMacro.howto.setup.step1':
            'File tree: which file from which step goes where.',
        'govMacro.howto.setup.step2':
            'dbt_project.yml vars (pii_user_role) and verify commands.',
        'govMacro.howto.setup.tip':
            'Place in project root — team reference.',
    },
};
