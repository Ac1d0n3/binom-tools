import { getLocale } from '../../locale';
import { piiTypeOptions } from '../pii-shared/demo-model.js';
import { piiRecHowtoLabels } from './howto-labels';

const labels = {
    de: {
        ...piiRecHowtoLabels.de,
        'piiRec.pageTitle': 'PII Recommend Generator',
        'piiRec.pageLead':
            'Schritt 4/4: Zwei Audit-Arten — Spaltennamen und Zellinhalte — jeweils mit eigenen Regeln.',
        'piiRec.compare.title': 'Zwei Audit-Arten im Vergleich',
        'piiRec.nameRules.badge': 'Spaltenname',
        'piiRec.nameRules.title': 'Regeln für Spaltennamen',
        'piiRec.nameRules.description':
            'Wenn der Spaltenname einen Suchtext enthält, schlägt das Audit einen PII-Typ vor.',
        'piiRec.nameRules.searchText': 'Suchtext im Spaltennamen',
        'piiRec.nameRules.piiType': 'Vorgeschlagener PII-Typ',
        'piiRec.nameRules.add': 'Regel hinzufügen',
        'piiRec.nameRules.remove': 'Entfernen',
        'piiRec.contentRules.badge': 'Zellinhalt',
        'piiRec.contentRules.title': 'Regeln für Zellinhalte (Content-Scan)',
        'piiRec.contentRules.description':
            'Wenn genug Zellwerte einem Regex entsprechen, schlägt das Audit einen PII-Typ vor — unabhängig vom Spaltennamen.',
        'piiRec.contentRules.regex': 'Regex (Zellinhalt)',
        'piiRec.contentRules.piiType': 'Vorgeschlagener PII-Typ',
        'piiRec.contentRules.minRate': 'Min. Trefferquote (%)',
        'piiRec.contentRules.add': 'Regel hinzufügen',
        'piiRec.contentRules.remove': 'Entfernen',
        'piiRec.scope.title': 'Default scope',
        'piiRec.warehouse.title': 'Warehouse',
        'piiRec.warehouse.hint':
            'Wirkt auf Content-Scan-SQL (Regex/Sample), nicht auf Name-Audit oder YAML. Vorschau (regex):',
        'piiRec.access.title': 'Access & Warehouse',
        'piiRec.access.groups': 'Model access_groups',
        'piiRec.output.auditName': 'macros/pii_audit_by_name.sql',
        'piiRec.output.auditContent': 'macros/pii_content_scan.sql',
        'piiRec.output.yaml': 'models/schema/example_table.yml (pii_recommend)',
        'piiRec.copy': 'Kopieren',
        'shared.syncStatus': 'Einstellungen zuletzt von {source} ({time})',
        'shared.copied': 'Kopiert!',
    },
    en: {
        ...piiRecHowtoLabels.en,
        'piiRec.pageTitle': 'PII Recommend Generator',
        'piiRec.pageLead':
            'Step 4/4: Two audit types — column names and cell values — each with its own rules.',
        'piiRec.compare.title': 'Two audit types compared',
        'piiRec.nameRules.badge': 'Column name',
        'piiRec.nameRules.title': 'Rules for column names',
        'piiRec.nameRules.description':
            'When the column name contains a search text, the audit suggests a PII type.',
        'piiRec.nameRules.searchText': 'Search text in column name',
        'piiRec.nameRules.piiType': 'Suggested PII type',
        'piiRec.nameRules.add': 'Add rule',
        'piiRec.nameRules.remove': 'Remove',
        'piiRec.contentRules.badge': 'Cell value',
        'piiRec.contentRules.title': 'Rules for cell values (content scan)',
        'piiRec.contentRules.description':
            'When enough cell values match a regex, the audit suggests a PII type — independent of the column name.',
        'piiRec.contentRules.regex': 'Regex (cell value)',
        'piiRec.contentRules.piiType': 'Suggested PII type',
        'piiRec.contentRules.minRate': 'Min. match rate (%)',
        'piiRec.contentRules.add': 'Add rule',
        'piiRec.contentRules.remove': 'Remove',
        'piiRec.scope.title': 'Default scope',
        'piiRec.warehouse.title': 'Warehouse',
        'piiRec.warehouse.hint':
            'Affects content-scan SQL (regex/sample), not name audit or YAML. Preview (regex):',
        'piiRec.access.title': 'Access & warehouse',
        'piiRec.access.groups': 'Model access_groups',
        'piiRec.output.auditName': 'macros/pii_audit_by_name.sql',
        'piiRec.output.auditContent': 'macros/pii_content_scan.sql',
        'piiRec.output.yaml': 'models/schema/example_table.yml (pii_recommend)',
        'piiRec.copy': 'Copy',
        'shared.syncStatus': 'Settings last saved by {source} ({time})',
        'shared.copied': 'Copied!',
    },
};

/** @param {'de' | 'en'} locale @param {string} key */
export function t(locale, key) {
    return labels[locale]?.[key] ?? labels.en[key] ?? key;
}

export function applyPiiRecommendLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('piiRec.') && !key?.startsWith('shared.')) return;
        const text = t(locale, key);
        if (text) el.textContent = text;
    });
}

export function renderPiiTypeOptions(selected) {
    return piiTypeOptions
        .map((type) => `<option value="${type}" ${type === selected ? 'selected' : ''}>${type}</option>`)
        .join('');
}
