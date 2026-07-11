import { getLocale } from '../../locale';

const labels = {
    de: {
        'dqMacro.pageTitle': 'DQ Macro Generator',
        'dqMacro.warehouse.title': 'Warehouse',
        'dqMacro.warehouse.description': 'SQL-Dialekt für generierte DQ-Checks.',
        'dqMacro.output.governance': 'macros/dq_governance.sql',
        'dqMacro.output.test': 'tests/generic/dq_rule.sql',
        'dqMacro.output.setup': 'SETUP_DQ.md',
        'dqMacro.copy': 'Kopieren',
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
        'dqMacro.pageTitle': 'DQ Macro Generator',
        'dqMacro.warehouse.title': 'Warehouse',
        'dqMacro.warehouse.description': 'SQL dialect for generated DQ checks.',
        'dqMacro.output.governance': 'macros/dq_governance.sql',
        'dqMacro.output.test': 'tests/generic/dq_rule.sql',
        'dqMacro.output.setup': 'SETUP_DQ.md',
        'dqMacro.copy': 'Copy',
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

export function applyDqMacroLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('dqMacro.') && !key?.startsWith('shared.')) return;
        const text = t(locale, key);
        if (text) el.textContent = text;
    });
}
