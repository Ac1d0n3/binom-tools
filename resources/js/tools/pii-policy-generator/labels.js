/** @typedef {'de' | 'en'} ToolsLocale */

import { piiHowtoLabels } from './howto-labels';

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const piiPolicyLabels = {
    de: {
        ...piiHowtoLabels.de,
        'pii.pageTitle': 'PII Policy Generator',
        'pii.pageLead':
            'Schritt 2/4: pii_details schema.yml-Beispiel und Secure Model — Zielzustand nach Review. Einstellungen werden mit Steps 1, 3 und 4 geteilt.',
        'pii.scenario.title': 'Zugriffsszenario',
        'pii.scenario.roles':
            'Mit access_roles: Nur Rollen in der Liste sehen unmaskierte Werte. Alle anderen erhalten maskierte Ausgabe via pii_column_for_role().',
        'pii.scenario.rules':
            'Mit access_rules: masked-Rollen sehen maskierte Werte, unmasked-Rollen sehen Rohdaten. Rollen außerhalb beider Listen erhalten null.',
        'pii.access.title': 'Zugriffskonfiguration',
        'pii.access.useRoles': 'Use Access Roles',
        'pii.access.defaultRoles': 'Standard access_roles (kommagetrennt)',
        'pii.access.maskedRoles': 'access_rules.masked (kommagetrennt)',
        'pii.access.unmaskedRoles': 'access_rules.unmasked (kommagetrennt)',
        'pii.model.title': 'Model',
        'pii.model.description': 'dbt-Model-Metadaten für schema.yml.',
        'pii.model.name': 'Model-Name',
        'pii.model.sourceTable': 'Quell-Tabelle',
        'pii.model.piiVersion': 'PII details version',
        'pii.model.modelDescription': 'Model-Beschreibung (dbt docs)',
        'pii.model.modelDescriptionHint': 'Kompletter description-Block in schema.yml — 1:1, nicht angehängt.',
        'pii.model.updateYaml': 'YAML aktualisieren',
        'pii.model.descriptionExtra': 'Zusätzliche Beschreibung',
        'pii.model.defaultScope': 'Standard-Scope für Vorschläge',
        'pii.columns.title': 'Spalten',
        'pii.columns.description': 'PII-Kategorie pro Spalte — none für nicht-sensitive Felder (z. B. info_mail).',
        'pii.columns.descriptionHint': 'Spalten-Beschreibung: Spalte aufklappen → Feld „Beschreibung“ (erscheint als description in schema.yml).',
        'pii.columns.colName': 'Spaltenname',
        'pii.columns.colDescription': 'Beschreibung',
        'pii.columns.colDescriptionHint': 'z. B. Full name of the support agent',
        'pii.columns.colPiiType': 'PII-Typ',
        'pii.columns.colScope': 'Scope',
        'pii.columns.colCategory': 'Kategorie',
        'pii.columns.colAccessRoles': 'access_roles',
        'pii.columns.remove': 'Entfernen',
        'pii.columns.addRow': 'Spalte hinzufügen',
        'pii.columns.bulkLabel': 'Spalten einfügen (kommagetrennt)',
        'pii.columns.bulkPlaceholder': 'id, agent_id, agent_name, info_mail, ...',
        'pii.columns.bulkAdd': 'Einfügen & vorschlagen',
        'pii.columns.applyScopeAll': 'Scope auf alle anwenden',
        'pii.columns.suggestAll': 'Alle Kategorien neu vorschlagen',
        'pii.yaml.title': 'schema.yml',
        'pii.yaml.description': 'YAML einfügen oder bearbeiten — wird bei Execute aktualisiert.',
        'pii.yaml.load': 'Aus YAML laden',
        'pii.yaml.execute': 'Execute',
        'pii.yaml.openEditor': 'Im Schema Editor öffnen',
        'pii.sync.saved': 'Gespeichert in localStorage',
        'pii.sync.loadedFromEditor': 'Aus Schema Editor geladen',
        'shared.syncStatus': 'Einstellungen zuletzt von {source} ({time})',
        'pii.yaml.parseError': 'YAML konnte nicht gelesen werden. Bitte dbt-Format prüfen.',
        'pii.macro.title': 'DBT Macros (pii_governance.sql)',
        'pii.macro.description': 'Kopiere nach macros/pii_governance.sql — enthält pii_mask und pii_column_for_role.',
        'pii.policy.title': 'DBT Policy (Referenz)',
        'pii.policy.description': 'Governance-Referenz mit Semantik für gewähltes Zugriffsmodell.',
        'pii.modelExample.title': 'DBT Model-Beispiel',
        'pii.modelExample.description': 'Lauffähiges SQL-Modell mit var pii_user_role.',
        'pii.copy': 'Kopieren',
        'pii.copied': 'Kopiert!',
        'pii.scope.internal': 'internal',
        'pii.scope.external': 'external',
        'pii.scope.none': 'none',
        'pii.type.none': 'none',
    },
    en: {
        ...piiHowtoLabels.en,
        'pii.pageTitle': 'PII Policy Generator',
        'pii.pageLead':
            'Step 2/4: pii_details schema.yml example and secure model — target state after review. Settings shared with steps 1, 3, and 4.',
        'pii.scenario.title': 'Access scenario',
        'pii.scenario.roles':
            'With access_roles: only listed roles see unmasked values. Everyone else gets masked output via pii_column_for_role().',
        'pii.scenario.rules':
            'With access_rules: masked roles see masked values, unmasked roles see raw data. Roles outside both lists get null.',
        'pii.access.title': 'Access configuration',
        'pii.access.useRoles': 'Use Access Roles',
        'pii.access.defaultRoles': 'Default access_roles (comma-separated)',
        'pii.access.maskedRoles': 'access_rules.masked (comma-separated)',
        'pii.access.unmaskedRoles': 'access_rules.unmasked (comma-separated)',
        'pii.model.title': 'Model',
        'pii.model.description': 'dbt model metadata for schema.yml.',
        'pii.model.name': 'Model name',
        'pii.model.sourceTable': 'Source table',
        'pii.model.piiVersion': 'PII details version',
        'pii.model.modelDescription': 'Model description (dbt docs)',
        'pii.model.modelDescriptionHint': 'Full description block in schema.yml — 1:1, not appended.',
        'pii.model.updateYaml': 'Update YAML',
        'pii.model.descriptionExtra': 'Additional description',
        'pii.model.defaultScope': 'Default scope for suggestions',
        'pii.columns.title': 'Columns',
        'pii.columns.description': 'PII category per column — use none for non-sensitive fields (e.g. info_mail).',
        'pii.columns.descriptionHint': 'Column description: expand a column → Description field (becomes description in schema.yml).',
        'pii.columns.colName': 'Column name',
        'pii.columns.colDescription': 'Description',
        'pii.columns.colDescriptionHint': 'e.g. Full name of the support agent',
        'pii.columns.colPiiType': 'PII type',
        'pii.columns.colScope': 'Scope',
        'pii.columns.colCategory': 'Category',
        'pii.columns.colAccessRoles': 'access_roles',
        'pii.columns.remove': 'Remove',
        'pii.columns.addRow': 'Add column',
        'pii.columns.bulkLabel': 'Insert columns (comma-separated)',
        'pii.columns.bulkPlaceholder': 'id, agent_id, agent_name, info_mail, ...',
        'pii.columns.bulkAdd': 'Insert & suggest',
        'pii.columns.applyScopeAll': 'Apply scope to all',
        'pii.columns.suggestAll': 'Re-suggest all categories',
        'pii.yaml.title': 'schema.yml',
        'pii.yaml.description': 'Paste or edit YAML — updated on Execute.',
        'pii.yaml.load': 'Load from YAML',
        'pii.yaml.execute': 'Execute',
        'pii.yaml.openEditor': 'Open in Schema Editor',
        'pii.sync.saved': 'Saved to localStorage',
        'pii.sync.loadedFromEditor': 'Loaded from Schema Editor',
        'shared.syncStatus': 'Settings last saved by {source} ({time})',
        'pii.yaml.parseError': 'Could not parse YAML. Please check dbt format.',
        'pii.macro.title': 'DBT macros (pii_governance.sql)',
        'pii.macro.description': 'Copy to macros/pii_governance.sql — includes pii_mask and pii_column_for_role.',
        'pii.policy.title': 'DBT policy (reference)',
        'pii.policy.description': 'Governance reference describing the selected access mode.',
        'pii.modelExample.title': 'DBT model example',
        'pii.modelExample.description': 'Runnable SQL model using var pii_user_role.',
        'pii.copy': 'Copy',
        'pii.copied': 'Copied!',
        'pii.scope.internal': 'internal',
        'pii.scope.external': 'external',
        'pii.scope.none': 'none',
        'pii.type.none': 'none',
    },
};

/** @param {ToolsLocale} locale @param {string} key */
export function t(locale, key) {
    return piiPolicyLabels[locale][key] ?? piiPolicyLabels.en[key] ?? key;
}

/** @param {ToolsLocale} locale */
export function applyPiiPolicyLabels(locale) {
    document.querySelectorAll('#pii-policy-generator-app [data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (key) el.textContent = t(locale, key);
    });

    document.querySelectorAll('#pii-policy-generator-app [data-i18n-placeholder]').forEach((el) => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (key && 'placeholder' in el) {
            el.placeholder = t(locale, key);
        }
    });
}
