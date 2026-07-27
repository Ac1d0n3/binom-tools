/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const dqRulesHowtoLabels = {
    de: {
        'dqRules.howto.summary': 'So funktioniert\'s',

        'dqRules.howto.overview.intro':
            'Schritt 2/3 — definiere meta.dq_rules pro Spalte und Model in schema.yml. Quell-Tabelle und Warehouse kommen aus Step 1.',
        'dqRules.howto.overview.step1': 'Model-Name und Quell-Tabelle setzen (z. B. raw.orders).',
        'dqRules.howto.overview.step2': 'Spalten & Regeln pflegen — Demo-Seed oder manuell.',
        'dqRules.howto.overview.step3': 'YAML, sources.yml, Model-SQL und Test-Snippets kopieren.',
        'dqRules.howto.overview.step4': 'Weiter zu Step 3 (History Generator) für dq_run_history und Reports.',
        'dqRules.howto.overview.tip':
            'Makros aus Step 1 müssen bereits im dbt-Projekt liegen.',

        'dqRules.howto.model.intro':
            'Model-Metadaten steuern Dateinamen und Provenance-Kommentare in den Outputs.',
        'dqRules.howto.model.step1': 'Quell-Tabelle im Format schema.table (z. B. raw.orders).',
        'dqRules.howto.model.step2': 'Warehouse wird read-only aus Step 1 angezeigt.',
        'dqRules.howto.model.tip': 'Beschreibung landet in schema.yml unter dem Model.',

        'dqRules.howto.columns.intro':
            'Jede Spalte kann eigene dq_rules haben — Typ, Severity und Parameter je Regel.',
        'dqRules.howto.columns.step1': 'Spaltenname und Beschreibung anpassen.',
        'dqRules.howto.columns.step2': 'Regeln hinzufügen: not_null, accepted_values, regex, expression, …',
        'dqRules.howto.columns.step3': 'Model-Regeln (Tab „Model-Regeln“) gelten auf Model-Ebene.',
        'dqRules.howto.columns.tip': 'Eindeutige rule IDs pro Model — Validator prüft Duplikate.',

        'dqRules.howto.yaml.intro': 'schema.yml mit meta.dq_rules — Kern-Artefakt für dbt test.',
        'dqRules.howto.yaml.step1': 'Kopiere nach models/schema/ (Pfad an euer Projekt anpassen).',
        'dqRules.howto.yaml.step2': 'Header-Kommentare zeigen Model, Source und Warehouse.',
        'dqRules.howto.yaml.tip': 'Mit dbt parse / dbt test validieren.',

        'dqRules.howto.sources.intro': 'sources.yml verknüpft das Model mit der Quell-Tabelle.',
        'dqRules.howto.sources.step1': 'Source-Name und Tabelle aus dem Feld Quell-Tabelle.',
        'dqRules.howto.sources.tip': 'Nur nötig, wenn die Source noch nicht im Projekt existiert.',

        'dqRules.howto.modelSql.intro': 'Beispiel-Staging/Mart-SQL für das konfigurierte Model.',
        'dqRules.howto.modelSql.step1': 'select * from {{ source(...) }} als Ausgangspunkt.',
        'dqRules.howto.modelSql.tip': 'An eure Naming-Conventions anpassen.',

        'dqRules.howto.tests.intro': 'Fertige dq_rule-Test-Zeilen mit allen Parametern.',
        'dqRules.howto.tests.step1': 'Snippet unter tests: im schema.yml einfügen.',
        'dqRules.howto.tests.tip': 'dq_rule.sql muss aus Step 1 vorhanden sein.',
    },
    en: {
        'dqRules.howto.summary': 'How it works',

        'dqRules.howto.overview.intro':
            'Step 2/3 — define meta.dq_rules per column and model in schema.yml. Source table and warehouse come from step 1.',
        'dqRules.howto.overview.step1': 'Set model name and source table (e.g. raw.orders).',
        'dqRules.howto.overview.step2': 'Edit columns & rules — demo seed or manual.',
        'dqRules.howto.overview.step3': 'Copy YAML, sources.yml, model SQL, and test snippets.',
        'dqRules.howto.overview.step4': 'Continue to step 3 (History Generator) for dq_run_history and reports.',
        'dqRules.howto.overview.tip':
            'Macros from step 1 must already be in the dbt project.',

        'dqRules.howto.model.intro':
            'Model metadata drives file names and provenance comments in outputs.',
        'dqRules.howto.model.step1': 'Source table as schema.table (e.g. raw.orders).',
        'dqRules.howto.model.step2': 'Warehouse is shown read-only from step 1.',
        'dqRules.howto.model.tip': 'Description is written into schema.yml under the model.',

        'dqRules.howto.columns.intro':
            'Each column can have its own dq_rules — type, severity, and parameters per rule.',
        'dqRules.howto.columns.step1': 'Adjust column name and description.',
        'dqRules.howto.columns.step2': 'Add rules: not_null, accepted_values, regex, expression, …',
        'dqRules.howto.columns.step3': 'Model-level rules (Model rules panel) apply to the whole model.',
        'dqRules.howto.columns.tip': 'Unique rule IDs per model — validator checks duplicates.',

        'dqRules.howto.yaml.intro': 'schema.yml with meta.dq_rules — core artefact for dbt test.',
        'dqRules.howto.yaml.step1': 'Copy to models/schema/ (adjust path to your project).',
        'dqRules.howto.yaml.step2': 'Header comments show model, source, and warehouse.',
        'dqRules.howto.yaml.tip': 'Validate with dbt parse / dbt test.',

        'dqRules.howto.sources.intro': 'sources.yml links the model to the source table.',
        'dqRules.howto.sources.step1': 'Source name and table from the source table field.',
        'dqRules.howto.sources.tip': 'Only needed if the source does not exist in the project yet.',

        'dqRules.howto.modelSql.intro': 'Example staging/mart SQL for the configured model.',
        'dqRules.howto.modelSql.step1': 'select * from {{ source(...) }} as a starting point.',
        'dqRules.howto.modelSql.tip': 'Adapt to your naming conventions.',

        'dqRules.howto.tests.intro': 'Ready-made dq_rule test lines with all parameters.',
        'dqRules.howto.tests.step1': 'Paste snippet under tests: in schema.yml.',
        'dqRules.howto.tests.tip': 'dq_rule.sql from step 1 must be present.',
    },
};
