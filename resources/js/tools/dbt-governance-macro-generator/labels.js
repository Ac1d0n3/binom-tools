import { getLocale } from '../../locale';
import { govMacroHowtoLabels } from './howto-labels';

const labels = {
    de: {
        ...govMacroHowtoLabels.de,
        'govMacro.pageTitle': 'Governance Macro Generator',
        'govMacro.pageLead':
            'Schritt 1/4: Laufzeit-Makros und Tests für dein dbt-Projekt kopieren — Einstellungen werden mit den anderen Setup-Tools geteilt.',
        'govMacro.warehouse.title': 'Warehouse',
        'govMacro.warehouse.description': 'Steuert SQL-Syntax in den generierten Makros.',
        'govMacro.access.title': 'Access-Konfiguration',
        'govMacro.access.useRoles': 'Access Roles verwenden',
        'govMacro.access.defaultRoles': 'Default access_roles',
        'govMacro.access.maskedRoles': 'access_rules.masked',
        'govMacro.access.unmaskedRoles': 'access_rules.unmasked',
        'govMacro.access.groups': 'Default model access_groups',
        'govMacro.output.governance': 'macros/pii_governance.sql',
        'govMacro.output.test': 'tests/generic/pii_reviewed.sql',
        'govMacro.output.setup': 'SETUP.md',
        'govMacro.copy': 'Kopieren',
        'shared.syncStatus': 'Einstellungen zuletzt von {source} ({time})',
        'shared.copied': 'Kopiert!',
    },
    en: {
        ...govMacroHowtoLabels.en,
        'govMacro.pageTitle': 'Governance Macro Generator',
        'govMacro.pageLead':
            'Step 1/4: Copy runtime macros and tests into your dbt project — settings are shared with other setup tools.',
        'govMacro.warehouse.title': 'Warehouse',
        'govMacro.warehouse.description': 'Controls SQL syntax in generated macros.',
        'govMacro.access.title': 'Access configuration',
        'govMacro.access.useRoles': 'Use access roles',
        'govMacro.access.defaultRoles': 'Default access_roles',
        'govMacro.access.maskedRoles': 'access_rules.masked',
        'govMacro.access.unmaskedRoles': 'access_rules.unmasked',
        'govMacro.access.groups': 'Default model access_groups',
        'govMacro.output.governance': 'macros/pii_governance.sql',
        'govMacro.output.test': 'tests/generic/pii_reviewed.sql',
        'govMacro.output.setup': 'SETUP.md',
        'govMacro.copy': 'Copy',
        'shared.syncStatus': 'Settings last saved by {source} ({time})',
        'shared.copied': 'Copied!',
    },
};

/** @param {'de' | 'en'} locale @param {string} key */
export function t(locale, key) {
    return labels[locale]?.[key] ?? labels.en[key] ?? key;
}

export function applyGovernanceMacroLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('govMacro.') && !key?.startsWith('shared.')) return;
        const text = t(locale, key);
        if (text) el.textContent = text;
    });
}
