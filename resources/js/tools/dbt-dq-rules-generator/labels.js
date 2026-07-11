import { getLocale } from '../../locale';
import { RULE_TYPE_DEFS, RULE_TYPE_IDS, SEVERITY_OPTIONS } from '../dq-shared/rule-types.js';

const labels = {
    de: {
        'dqRules.pageTitle': 'DQ Rules Generator',
        'dqRules.model.title': 'Model',
        'dqRules.model.name': 'Model-Name',
        'dqRules.model.description': 'Beschreibung',
        'dqRules.columns.title': 'Spalten & Regeln',
        'dqRules.columns.add': 'Spalte hinzufügen',
        'dqRules.rules.add': 'Regel hinzufügen',
        'dqRules.rules.type': 'Typ',
        'dqRules.rules.id': 'ID',
        'dqRules.rules.severity': 'Severity',
        'dqRules.rules.remove': 'Entfernen',
        'dqRules.modelRules.title': 'Model-Regeln',
        'dqRules.output.yaml': 'models/schema/example_dq_schema.yml',
        'dqRules.output.tests': 'Generic Tests (Snippet)',
        'dqRules.copy': 'Kopieren',
        'shared.syncStatus': 'Einstellungen zuletzt von {source} ({time})',
        'shared.copied': 'Kopiert!',
        'dq.validation.modelName': 'Model-Name fehlt.',
        'dq.validation.columnName': 'Spaltenname fehlt.',
        'dq.validation.duplicateColumn': 'Doppelter Spaltenname: {name}.',
        'dq.validation.ruleId': 'Regel-ID fehlt ({scope}).',
        'dq.validation.duplicateRuleId': 'Doppelte Regel-ID {id} ({scope}).',
        'dq.validation.ruleType': 'Ungültiger Regeltyp für {id}.',
        'dq.validation.regex': 'Ungültiges Regex-Muster für {id}.',
        'dq.validation.acceptedValues': 'accepted_values ohne Werte für {id}.',
        'dq.validation.pattern': 'Regex ohne pattern für {id}.',
        'dq.validation.expression': 'Expression ohne SQL für {id}.',
    },
    en: {
        'dqRules.pageTitle': 'DQ Rules Generator',
        'dqRules.model.title': 'Model',
        'dqRules.model.name': 'Model name',
        'dqRules.model.description': 'Description',
        'dqRules.columns.title': 'Columns & rules',
        'dqRules.columns.add': 'Add column',
        'dqRules.rules.add': 'Add rule',
        'dqRules.rules.type': 'Type',
        'dqRules.rules.id': 'ID',
        'dqRules.rules.severity': 'Severity',
        'dqRules.rules.remove': 'Remove',
        'dqRules.modelRules.title': 'Model-level rules',
        'dqRules.output.yaml': 'models/schema/example_dq_schema.yml',
        'dqRules.output.tests': 'Generic tests (snippet)',
        'dqRules.copy': 'Copy',
        'shared.syncStatus': 'Settings last saved by {source} ({time})',
        'shared.copied': 'Copied!',
        'dq.validation.modelName': 'Model name is required.',
        'dq.validation.columnName': 'Column name is required.',
        'dq.validation.duplicateColumn': 'Duplicate column name: {name}.',
        'dq.validation.ruleId': 'Rule id is missing ({scope}).',
        'dq.validation.duplicateRuleId': 'Duplicate rule id {id} ({scope}).',
        'dq.validation.ruleType': 'Invalid rule type for {id}.',
        'dq.validation.regex': 'Invalid regex pattern for {id}.',
        'dq.validation.acceptedValues': 'accepted_values without values for {id}.',
        'dq.validation.pattern': 'Regex rule missing pattern for {id}.',
        'dq.validation.expression': 'Expression rule missing SQL for {id}.',
    },
};

/** @param {'de' | 'en'} locale @param {string} key @param {Record<string, string | number>} [params] */
export function t(locale, key, params = {}) {
    let text = labels[locale]?.[key] ?? labels.en[key] ?? key;
    for (const [k, v] of Object.entries(params)) {
        text = text.replace(`{${k}}`, String(v));
    }
    return text;
}

export function applyDqRulesLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('dqRules.') && !key?.startsWith('shared.')) return;
        const text = t(locale, key);
        if (text) el.textContent = text;
    });
}

export { RULE_TYPE_DEFS, RULE_TYPE_IDS, SEVERITY_OPTIONS };
