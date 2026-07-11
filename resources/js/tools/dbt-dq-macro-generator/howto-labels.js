/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const dqMacroHowtoLabels = {
    de: {
        'dqMacro.howto.summary': 'So funktioniert\'s',

        'dqMacro.howto.overview.intro':
            'Schritt 1/3 der DQ-Einrichtung — hier kopierst du Laufzeit-Makros, den Generic Test dq_rule und SETUP_DQ.md in dein dbt-Projekt.',
        'dqMacro.howto.overview.step1': 'Warehouse wählen — steuert SQL-Syntax in dq_governance.sql.',
        'dqMacro.howto.overview.step2': 'macros/dq_governance.sql und tests/generic/dq_rule.sql kopieren.',
        'dqMacro.howto.overview.step3': 'SETUP_DQ.md ins Projekt-Root — Verweis auf Steps 2 und 3.',
        'dqMacro.howto.overview.step4': 'Weiter zu Step 2 (Rules Generator) für meta.dq_rules in schema.yml.',
        'dqMacro.howto.overview.tip':
            'Warehouse wird in localStorage gespeichert und in Steps 2–3 wiederverwendet.',

        'dqMacro.howto.warehouse.intro':
            'Das Warehouse bestimmt Dialekt-spezifische SQL-Fragmente in den generierten Makros.',
        'dqMacro.howto.warehouse.step1': 'Snowflake, BigQuery, Redshift, Databricks oder Postgres wählen.',
        'dqMacro.howto.warehouse.step2': 'Nach Änderung werden alle Outputs automatisch neu generiert.',
        'dqMacro.howto.warehouse.tip': 'Muss zum Profil in profiles.yml passen.',

        'dqMacro.howto.governance.intro':
            'Laufzeit-Makros lesen meta.dq_rules aus schema.yml (Step 2).',
        'dqMacro.howto.governance.step1': 'dq_effective_rules / dq_model_rules: Regeln aus YAML.',
        'dqMacro.howto.governance.step2': 'dq_run_rule_check: führt Checks zur Laufzeit aus.',
        'dqMacro.howto.governance.step3': 'Nach macros/ kopieren und dbt deps/run testen.',
        'dqMacro.howto.governance.tip': 'Bei Warehouse-Wechsel hier neu kopieren.',

        'dqMacro.howto.test.intro': 'Generic Test dq_rule — bindet meta.dq_rules an dbt test.',
        'dqMacro.howto.test.step1': 'Kopiere nach tests/generic/dq_rule.sql.',
        'dqMacro.howto.test.step2': 'In schema.yml: tests: - dq_rule (Parameter aus Step 2).',
        'dqMacro.howto.test.tip': 'Snippet mit vollständigen Parametern liefert Step 2.',

        'dqMacro.howto.setup.intro': 'SETUP_DQ.md dokumentiert den Copy-Paste-Ablauf aller 3 DQ-Steps.',
        'dqMacro.howto.setup.step1': 'Dateibaum: welche Datei aus welchem Step wohin gehört.',
        'dqMacro.howto.setup.step2': 'Verify-Befehle: dbt test, dbt run für History-Mart.',
        'dqMacro.howto.setup.tip': 'Im Projekt-Root ablegen — Team-Referenz.',
    },
    en: {
        'dqMacro.howto.summary': 'How it works',

        'dqMacro.howto.overview.intro':
            'Step 1/3 of the DQ setup — copy runtime macros, the dq_rule generic test, and SETUP_DQ.md into your dbt project.',
        'dqMacro.howto.overview.step1': 'Pick a warehouse — controls SQL dialect in dq_governance.sql.',
        'dqMacro.howto.overview.step2': 'Copy macros/dq_governance.sql and tests/generic/dq_rule.sql.',
        'dqMacro.howto.overview.step3': 'Copy SETUP_DQ.md to the project root — points to steps 2 and 3.',
        'dqMacro.howto.overview.step4': 'Continue to step 2 (Rules Generator) for meta.dq_rules in schema.yml.',
        'dqMacro.howto.overview.tip':
            'Warehouse is stored in localStorage and reused in steps 2–3.',

        'dqMacro.howto.warehouse.intro':
            'The warehouse drives dialect-specific SQL fragments in generated macros.',
        'dqMacro.howto.warehouse.step1': 'Choose Snowflake, BigQuery, Redshift, Databricks, or Postgres.',
        'dqMacro.howto.warehouse.step2': 'All outputs regenerate when you change the warehouse.',
        'dqMacro.howto.warehouse.tip': 'Must match the profile in profiles.yml.',

        'dqMacro.howto.governance.intro':
            'Runtime macros read meta.dq_rules from schema.yml (step 2).',
        'dqMacro.howto.governance.step1': 'dq_effective_rules / dq_model_rules: rules from YAML.',
        'dqMacro.howto.governance.step2': 'dq_run_rule_check: executes checks at runtime.',
        'dqMacro.howto.governance.step3': 'Copy to macros/ and verify with dbt deps/run.',
        'dqMacro.howto.governance.tip': 'Re-copy here after warehouse changes.',

        'dqMacro.howto.test.intro': 'Generic test dq_rule — wires meta.dq_rules into dbt test.',
        'dqMacro.howto.test.step1': 'Copy to tests/generic/dq_rule.sql.',
        'dqMacro.howto.test.step2': 'In schema.yml: tests: - dq_rule (params from step 2).',
        'dqMacro.howto.test.tip': 'Step 2 provides a snippet with full rule parameters.',

        'dqMacro.howto.setup.intro': 'SETUP_DQ.md documents the copy-paste flow for all 3 DQ steps.',
        'dqMacro.howto.setup.step1': 'File tree: which step produces which target path.',
        'dqMacro.howto.setup.step2': 'Verify commands: dbt test, dbt run for the history mart.',
        'dqMacro.howto.setup.tip': 'Keep at project root — team reference.',
    },
};
