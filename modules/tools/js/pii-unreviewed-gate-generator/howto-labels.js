/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const gateHowtoLabels = {
    de: {
        'gate.howto.summary': 'So funktioniert\'s',

        'gate.howto.overview.intro':
            'Schritt 3/4 — Ungeprüfte Models identifizieren und für normale Rollen verstecken, bis meta.pii-reviewed: true (Step 2).',
        'gate.howto.overview.step1':
            'Review-Rollen festlegen — wer darf unreviewed Models sehen (z. B. dpo, security).',
        'gate.howto.overview.step2':
            'Default access_groups setzen — geteilt mit Step 1/2/4.',
        'gate.howto.overview.step3':
            'pii_table_gate.sql nach macros/ kopieren — referenziert pii_model_accessible() aus Step 1.',
        'gate.howto.overview.step4':
            'Gated-View-Beispiel und Gate-YAML für neue Tabellen kopieren.',
        'gate.howto.overview.step5':
            'dbt run-operation pii_audit_unreviewed_models — listet Models ohne pii-reviewed: true.',
        'gate.howto.overview.tip':
            'Nach Review in Step 2 pii-reviewed: true setzen — dann greift pii_model_accessible() statt Gate.',

        'gate.howto.reviewRoles.intro':
            'Review-Rollen sehen unreviewed Models zum Klassifizieren. Alle anderen Rollen erhalten keinen Zugriff.',
        'gate.howto.reviewRoles.step1':
            'Kommagetrennt eintragen: dpo, security, admin …',
        'gate.howto.reviewRoles.step2':
            'Wird in dbt_project.yml als var pii_review_roles überschreibbar.',
        'gate.howto.reviewRoles.tip':
            'Nicht mit access_groups verwechseln — Review-Rollen sind nur für den Übergangszustand.',

        'gate.howto.macro.intro':
            'Laufzeit-Gate — pii_model_visible_for_role() kombiniert Review-Status mit Step-1-Zugriff.',
        'gate.howto.macro.step1':
            'Kopiere nach macros/pii_table_gate.sql.',
        'gate.howto.macro.step2':
            'Nutze in Secure Views: {% if pii_model_visible_for_role(model, user_role) %}.',
        'gate.howto.macro.tip':
            'Step 1 pii_governance.sql muss im Projekt liegen.',

        'gate.howto.yaml.intro':
            'Beispiel-meta für neue Tabellen — pii-reviewed: false bis Step 2 abgeschlossen ist.',
        'gate.howto.yaml.step1':
            'Als Vorlage für schema.yml neuer Models verwenden.',
        'gate.howto.yaml.tip':
            'Nach Review: pii-reviewed: true und pii_details aus Step 2.',

        'gate.howto.view.intro':
            'Beispiel-Gated-View — zeigt Daten nur wenn pii_model_visible_for_role() true zurückgibt.',
        'gate.howto.view.step1':
            'Anpassen und nach models/marts/ kopieren.',
        'gate.howto.view.tip':
            'Für Sources analog ref() durch source() ersetzen.',
    },
    en: {
        'gate.howto.summary': 'How it works',

        'gate.howto.overview.intro':
            'Step 3/4 — identify unreviewed models and hide them from normal roles until meta.pii-reviewed: true (step 2).',
        'gate.howto.overview.step1':
            'Set review roles — who may see unreviewed models (e.g. dpo, security).',
        'gate.howto.overview.step2':
            'Set default access_groups — shared with steps 1/2/4.',
        'gate.howto.overview.step3':
            'Copy pii_table_gate.sql to macros/ — references pii_model_accessible() from step 1.',
        'gate.howto.overview.step4':
            'Copy gated view example and gate YAML for new tables.',
        'gate.howto.overview.step5':
            'dbt run-operation pii_audit_unreviewed_models — lists models missing pii-reviewed: true.',
        'gate.howto.overview.tip':
            'After review in step 2 set pii-reviewed: true — then pii_model_accessible() applies instead of the gate.',

        'gate.howto.reviewRoles.intro':
            'Review roles see unreviewed models for classification. All other roles get no access.',
        'gate.howto.reviewRoles.step1':
            'Enter comma-separated: dpo, security, admin …',
        'gate.howto.reviewRoles.step2':
            'Overridable in dbt_project.yml as var pii_review_roles.',
        'gate.howto.reviewRoles.tip':
            'Do not confuse with access_groups — review roles are for the transition state only.',

        'gate.howto.macro.intro':
            'Runtime gate — pii_model_visible_for_role() combines review status with step 1 access.',
        'gate.howto.macro.step1':
            'Copy to macros/pii_table_gate.sql.',
        'gate.howto.macro.step2':
            'Use in secure views: {% if pii_model_visible_for_role(model, user_role) %}.',
        'gate.howto.macro.tip':
            'Step 1 pii_governance.sql must be in the project.',

        'gate.howto.yaml.intro':
            'Example meta for new tables — pii-reviewed: false until step 2 is complete.',
        'gate.howto.yaml.step1':
            'Use as template for schema.yml of new models.',
        'gate.howto.yaml.tip':
            'After review: pii-reviewed: true and pii_details from step 2.',

        'gate.howto.view.intro':
            'Example gated view — returns rows only when pii_model_visible_for_role() is true.',
        'gate.howto.view.step1':
            'Adapt and copy to models/marts/.',
        'gate.howto.view.tip':
            'For sources replace ref() with source() accordingly.',
    },
};
