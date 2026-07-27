/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const piiRecHowtoLabels = {
    de: {
        'piiRec.howto.summary': 'So funktioniert\'s',

        'piiRec.howto.overview.intro':
            'Schritt 4/4 — Zwei Audit-Arten: Spaltennamen und Zellinhalte. Beide erzeugen pii_recommend-Vorschläge zum manuellen Review.',
        'piiRec.howto.overview.step1':
            'Regeln für Spaltennamen pflegen — Suchtext im Spaltennamen (z. B. email in user_email).',
        'piiRec.howto.overview.step2':
            'Regeln für Zellinhalte pflegen — Regex auf Zellwerte (z. B. E-Mail-Muster in contact_value).',
        'piiRec.howto.overview.step3':
            'pii_audit_by_name.sql kopieren — dbt run-operation pii_audit_by_name.',
        'piiRec.howto.overview.step4':
            'pii_content_scan.sql kopieren — dbt run-operation pii_audit_by_content --args \'{sample_limit: 1000}\'.',
        'piiRec.howto.overview.step5':
            'Vorschläge prüfen, dann in pii_details überführen und pii-reviewed: true setzen (Step 2).',
        'piiRec.howto.overview.tip':
            'pii_recommend ist nur Vorschlag — Laufzeit-Makros (Step 1) lesen ausschließlich pii_details.',

        'piiRec.howto.compare.colName': 'Spaltennamen',
        'piiRec.howto.compare.colContent': 'Zellinhalte',
        'piiRec.howto.compare.what': 'Was wird geprüft?',
        'piiRec.howto.compare.whatName': 'Der Name der Spalte (z. B. user_email)',
        'piiRec.howto.compare.whatContent': 'Die Werte in der Spalte (z. B. max@firma.de)',
        'piiRec.howto.compare.ruleType': 'Regel-Typ',
        'piiRec.howto.compare.ruleName': 'Suchtext (Substring, Groß/Klein egal)',
        'piiRec.howto.compare.ruleContent': 'Regex (warehouse-spezifisch)',
        'piiRec.howto.compare.command': 'dbt-Befehl',
        'piiRec.howto.compare.commandName': 'dbt run-operation pii_audit_by_name',
        'piiRec.howto.compare.commandContent': 'dbt run-operation pii_audit_by_content',

        'piiRec.howto.nameRules.intro':
            'Wenn der Spaltenname einen Suchtext enthält, schlägt das Audit einen PII-Typ vor — ohne Zellwerte zu lesen.',
        'piiRec.howto.nameRules.step1':
            'Beispiel: Spalte user_email + Regel email → Vorschlag email_internal.',
        'piiRec.howto.nameRules.step2':
            'Regel created_at → none — Spalte explizit ignorieren.',
        'piiRec.howto.nameRules.tip':
            'Groß/Klein egal; der Suchtext muss im Spaltennamen vorkommen.',

        'piiRec.howto.contentRules.intro':
            'Wenn genug Zellwerte einem Regex entsprechen, schlägt das Audit einen PII-Typ vor — unabhängig vom Spaltennamen.',
        'piiRec.howto.contentRules.step1':
            'Beispiel: Spalte contact_value (neutraler Name) → Content-Scan findet E-Mails → Vorschlag email_internal.',
        'piiRec.howto.contentRules.step2':
            'Min. Trefferquote pro Regel — Standard 5 % der Stichprobe.',
        'piiRec.howto.contentRules.tip':
            'Regex-Syntax hängt vom gewählten Warehouse ab.',

        'piiRec.howto.access.intro':
            'Geteilte Einstellungen — Scope, Rollen und Warehouse wirken auf Audit-Output und YAML-Beispiel.',
        'piiRec.howto.access.step1':
            'Default scope: internal/external für Kategorie-Vorschläge.',
        'piiRec.howto.access.roles1':
            'access_roles: Nur gelistete Rollen erhalten unmaskierte Werte in Beispiel-YAML.',
        'piiRec.howto.access.roles2':
            'Alle anderen Rollen: maskierte Ausgabe via pii_column_for_role() (Step 1).',
        'piiRec.howto.access.rules1':
            'access_rules.masked: Rollen mit maskierter Ausgabe.',
        'piiRec.howto.access.rules2':
            'access_rules.unmasked: Rollen mit Rohdaten.',
        'piiRec.howto.access.rules3':
            'Rollen in keiner Liste: null — kein Spaltenzugriff.',
        'piiRec.howto.access.step2':
            'Warehouse: relevant für Content-Scan (Regex-Syntax).',
        'piiRec.howto.access.tip':
            'Model access_groups: meta.access_groups für Gate und Policy (Steps 2/3).',

        'piiRec.howto.auditName.intro':
            'Scannt Spaltennamen anhand deiner Suchtext-Regeln. Log-Zeilen enthalten source: name_heuristic.',
        'piiRec.howto.auditName.step1':
            'Kopiere nach macros/pii_audit_by_name.sql.',
        'piiRec.howto.auditName.step2':
            'dbt run-operation pii_audit_by_name — Ausgabe im Terminal.',
        'piiRec.howto.auditName.tip':
            'Liest keine Zellwerte — nur Spaltennamen.',

        'piiRec.howto.auditContent.intro':
            'Stichprobt Zellwerte anhand deiner Regex-Regeln. Log-Zeilen enthalten source: content_scan.',
        'piiRec.howto.auditContent.step1':
            'Kopiere nach macros/pii_content_scan.sql.',
        'piiRec.howto.auditContent.step2':
            'dbt run-operation pii_audit_by_content --args \'{sample_limit: 1000}\'.',
        'piiRec.howto.auditContent.tip':
            'Unabhängig vom Spaltennamen — findet PII in generisch benannten Spalten.',

        'piiRec.howto.yaml.intro':
            'Ganzes Model-Beispiel mit pii_recommend und pii-reviewed: false — Einsteiger-/Review-Zustand.',
        'piiRec.howto.yaml.step1':
            'Gleiche Spalten wie Step 2 — aber meta.pii_recommend statt pii_details.',
        'piiRec.howto.yaml.step2':
            'Mit Step-2-YAML vergleichen: dort pii_details + pii-reviewed: true (Zielzustand).',
        'piiRec.howto.yaml.tip':
            'Nach Review: pii_recommend → pii_details manuell überführen.',
    },
    en: {
        'piiRec.howto.summary': 'How it works',

        'piiRec.howto.overview.intro':
            'Step 4/4 — two audit types: column names and cell values. Both produce pii_recommend suggestions for manual review.',
        'piiRec.howto.overview.step1':
            'Maintain column name rules — search text in the column name (e.g. email in user_email).',
        'piiRec.howto.overview.step2':
            'Maintain cell content rules — regex on cell values (e.g. email pattern in contact_value).',
        'piiRec.howto.overview.step3':
            'Copy pii_audit_by_name.sql — dbt run-operation pii_audit_by_name.',
        'piiRec.howto.overview.step4':
            'Copy pii_content_scan.sql — dbt run-operation pii_audit_by_content --args \'{sample_limit: 1000}\'.',
        'piiRec.howto.overview.step5':
            'Review suggestions, then promote to pii_details and set pii-reviewed: true (step 2).',
        'piiRec.howto.overview.tip':
            'pii_recommend is suggestion only — runtime macros (step 1) read pii_details exclusively.',

        'piiRec.howto.compare.colName': 'Column names',
        'piiRec.howto.compare.colContent': 'Cell values',
        'piiRec.howto.compare.what': 'What is checked?',
        'piiRec.howto.compare.whatName': 'The column name (e.g. user_email)',
        'piiRec.howto.compare.whatContent': 'Values in the column (e.g. max@company.com)',
        'piiRec.howto.compare.ruleType': 'Rule type',
        'piiRec.howto.compare.ruleName': 'Search text (substring, case-insensitive)',
        'piiRec.howto.compare.ruleContent': 'Regex (warehouse-specific)',
        'piiRec.howto.compare.command': 'dbt command',
        'piiRec.howto.compare.commandName': 'dbt run-operation pii_audit_by_name',
        'piiRec.howto.compare.commandContent': 'dbt run-operation pii_audit_by_content',

        'piiRec.howto.nameRules.intro':
            'When the column name contains a search text, the audit suggests a PII type — without reading cell values.',
        'piiRec.howto.nameRules.step1':
            'Example: column user_email + rule email → suggestion email_internal.',
        'piiRec.howto.nameRules.step2':
            'Rule created_at → none — explicitly ignore the column.',
        'piiRec.howto.nameRules.tip':
            'Case-insensitive; the search text must appear in the column name.',

        'piiRec.howto.contentRules.intro':
            'When enough cell values match a regex, the audit suggests a PII type — independent of the column name.',
        'piiRec.howto.contentRules.step1':
            'Example: column contact_value (neutral name) → content scan finds emails → suggestion email_internal.',
        'piiRec.howto.contentRules.step2':
            'Min. match rate per rule — default 5% of the sample.',
        'piiRec.howto.contentRules.tip':
            'Regex syntax depends on the selected warehouse.',

        'piiRec.howto.access.intro':
            'Shared settings — scope, roles, and warehouse affect audit output and YAML example.',
        'piiRec.howto.access.step1':
            'Default scope: internal/external for category suggestions.',
        'piiRec.howto.access.roles1':
            'access_roles: only listed roles receive unmasked values in example YAML.',
        'piiRec.howto.access.roles2':
            'All other roles: masked output via pii_column_for_role() (step 1).',
        'piiRec.howto.access.rules1':
            'access_rules.masked: roles with masked output.',
        'piiRec.howto.access.rules2':
            'access_rules.unmasked: roles with raw data.',
        'piiRec.howto.access.rules3':
            'Roles in neither list: null — no column access.',
        'piiRec.howto.access.step2':
            'Warehouse: relevant for content scan (regex syntax).',
        'piiRec.howto.access.tip':
            'Model access_groups: meta.access_groups for gate and policy (steps 2/3).',

        'piiRec.howto.auditName.intro':
            'Scans column names using your search text rules. Log lines include source: name_heuristic.',
        'piiRec.howto.auditName.step1':
            'Copy to macros/pii_audit_by_name.sql.',
        'piiRec.howto.auditName.step2':
            'dbt run-operation pii_audit_by_name — output in terminal.',
        'piiRec.howto.auditName.tip':
            'Does not read cell values — column names only.',

        'piiRec.howto.auditContent.intro':
            'Samples cell values using your regex rules. Log lines include source: content_scan.',
        'piiRec.howto.auditContent.step1':
            'Copy to macros/pii_content_scan.sql.',
        'piiRec.howto.auditContent.step2':
            'dbt run-operation pii_audit_by_content --args \'{sample_limit: 1000}\'.',
        'piiRec.howto.auditContent.tip':
            'Independent of column name — finds PII in generically named columns.',

        'piiRec.howto.yaml.intro':
            'Full model example with pii_recommend and pii-reviewed: false — onboarding/review state.',
        'piiRec.howto.yaml.step1':
            'Same columns as step 2 — but meta.pii_recommend instead of pii_details.',
        'piiRec.howto.yaml.step2':
            'Compare with step 2 YAML: there pii_details + pii-reviewed: true (target state).',
        'piiRec.howto.yaml.tip':
            'After review: manually promote pii_recommend → pii_details.',
    },
};
