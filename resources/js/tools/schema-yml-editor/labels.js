/** @typedef {'de' | 'en'} ToolsLocale */

import { schemaHowtoLabels } from './howto-labels';

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const schemaEditorLabels = {
    de: {
        ...schemaHowtoLabels.de,
        'schema.pageTitle': 'Schema YML Editor',
        'schema.pageLead':
            'Hilfs-Tool: einzelne schema.yml typo-sicher bearbeiten — nicht Teil der dbt-Einrichtungs-Kette (Steps 1–3).',
        'schema.standaloneNotice':
            'Dieses Tool ist nicht Teil der dbt PII Governance Einrichtung. Nutze es später zum Bearbeiten einzelner YAML-Dateien.',
        'schema.sync.title': 'Sync',
        'schema.sync.status': 'Bereit',
        'schema.sync.saved': 'PII-Meta in localStorage gespeichert',
        'schema.sync.loadedFromGenerator': 'Aus PII Policy Generator geladen',
        'schema.sync.load': 'Aus Storage laden',
        'schema.sync.clear': 'Storage leeren',
        'schema.sync.openGenerator': 'PII Policy Generator öffnen',
        'schema.scenario.title': 'Zugriffsszenario',
        'schema.scenario.roles':
            'Mit access_roles: Nur Rollen in der Liste sehen unmaskierte Werte. Alle anderen erhalten maskierte Ausgabe.',
        'schema.scenario.rules':
            'Mit access_rules: masked-Rollen sehen maskierte Werte, unmasked-Rollen sehen Rohdaten. Rollen außerhalb beider Listen erhalten null.',
        'schema.model.title': 'Model',
        'schema.model.name': 'Model-Name',
        'schema.model.sourceTable': 'Quell-Tabelle',
        'schema.model.piiVersion': 'PII details version',
        'schema.model.modelDescription': 'Model-Beschreibung',
        'schema.model.modelDescriptionHint': 'Kompletter description-Block — 1:1 mit YAML (nicht angehängt).',
        'schema.access.title': 'Zugriffskonfiguration',
        'schema.access.useRoles': 'Use Access Roles',
        'schema.access.defaultRoles': 'Standard access_roles (kommagetrennt)',
        'schema.access.maskedRoles': 'access_rules.masked (kommagetrennt)',
        'schema.access.unmaskedRoles': 'access_rules.unmasked (kommagetrennt)',
        'schema.columns.title': 'Spalten',
        'schema.columns.descriptionHint': 'Spalte aufklappen → Feld „Beschreibung“ für dbt column description.',
        'schema.columns.colName': 'Spalte',
        'schema.columns.colDescription': 'Beschreibung',
        'schema.columns.colDescriptionHint': 'z. B. Internal user identifier',
        'schema.columns.colPiiType': 'PII-Typ',
        'schema.columns.colScope': 'Scope',
        'schema.columns.colCategory': 'Kategorie',
        'schema.columns.colAccessRoles': 'access_roles',
        'schema.columns.addRow': 'Spalte hinzufügen',
        'schema.columns.remove': 'Entfernen',
        'schema.yaml.title': 'YAML-Editor',
        'schema.yaml.description': 'Live bearbeitbar — Änderungen im Formular oder hier synchronisieren sich automatisch.',
        'schema.yaml.parseError': 'YAML konnte nicht gelesen werden. Syntax oder Struktur prüfen.',
        'schema.copy': 'Kopieren',
        'schema.copied': 'Kopiert!',
        'schema.scope.internal': 'internal',
        'schema.scope.external': 'external',
        'schema.scope.none': 'none',
        'schema.type.none': 'none',
    },
    en: {
        ...schemaHowtoLabels.en,
        'schema.pageTitle': 'Schema YML Editor',
        'schema.pageLead':
            'Helper tool: edit individual schema.yml safely — not part of the dbt setup chain (steps 1–3).',
        'schema.standaloneNotice':
            'This tool is not part of the dbt PII Governance setup. Use it later to edit individual YAML files.',
        'schema.sync.title': 'Sync',
        'schema.sync.status': 'Ready',
        'schema.sync.saved': 'PII meta saved to localStorage',
        'schema.sync.loadedFromGenerator': 'Loaded from PII Policy Generator',
        'schema.sync.load': 'Load from storage',
        'schema.sync.clear': 'Clear storage',
        'schema.sync.openGenerator': 'Open PII Policy Generator',
        'schema.scenario.title': 'Access scenario',
        'schema.scenario.roles':
            'With access_roles: only listed roles see unmasked values. Everyone else gets masked output.',
        'schema.scenario.rules':
            'With access_rules: masked roles see masked values, unmasked roles see raw data. Roles outside both lists get null.',
        'schema.model.title': 'Model',
        'schema.model.name': 'Model name',
        'schema.model.sourceTable': 'Source table',
        'schema.model.piiVersion': 'PII details version',
        'schema.model.modelDescription': 'Model description',
        'schema.model.modelDescriptionHint': 'Full description block — 1:1 with YAML (not appended).',
        'schema.access.title': 'Access configuration',
        'schema.access.useRoles': 'Use Access Roles',
        'schema.access.defaultRoles': 'Default access_roles (comma-separated)',
        'schema.access.maskedRoles': 'access_rules.masked (comma-separated)',
        'schema.access.unmaskedRoles': 'access_rules.unmasked (comma-separated)',
        'schema.columns.title': 'Columns',
        'schema.columns.descriptionHint': 'Expand a column → Description field for dbt column description.',
        'schema.columns.colName': 'Column',
        'schema.columns.colDescription': 'Description',
        'schema.columns.colDescriptionHint': 'e.g. Internal user identifier',
        'schema.columns.colPiiType': 'PII type',
        'schema.columns.colScope': 'Scope',
        'schema.columns.colCategory': 'Category',
        'schema.columns.colAccessRoles': 'access_roles',
        'schema.columns.addRow': 'Add column',
        'schema.columns.remove': 'Remove',
        'schema.yaml.title': 'YAML editor',
        'schema.yaml.description': 'Editable live — changes in the form or here sync automatically.',
        'schema.yaml.parseError': 'Could not parse YAML. Check syntax or structure.',
        'schema.copy': 'Copy',
        'schema.copied': 'Copied!',
        'schema.scope.internal': 'internal',
        'schema.scope.external': 'external',
        'schema.scope.none': 'none',
        'schema.type.none': 'none',
    },
};

/** @param {ToolsLocale} locale @param {string} key */
export function t(locale, key) {
    return schemaEditorLabels[locale][key] ?? schemaEditorLabels.en[key] ?? key;
}

/** @param {ToolsLocale} locale */
export function applySchemaEditorLabels(locale) {
    document.querySelectorAll('#schema-yml-editor-app [data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (key) el.textContent = t(locale, key);
    });
}
