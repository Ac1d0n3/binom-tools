import { getLocale } from '../../locale';
import { gateHowtoLabels } from './howto-labels';

const labels = {
    de: {
        ...gateHowtoLabels.de,
        'gate.pageTitle': 'Unreviewed Table Gate Generator',
        'gate.pageLead':
            'Schritt 3/4: Ungeprüfte Models identifizieren und verstecken — Makros, Gate-YAML und Gated-View-Beispiel.',
        'gate.reviewRoles.title': 'Review-Rollen',
        'gate.reviewRoles.description':
            'Wer darf Models ohne pii-reviewed: true sehen? (Kommagetrennt)',
        'gate.access.title': 'Model access_groups',
        'gate.access.groups': 'Default access_groups',
        'gate.output.macro': 'macros/pii_table_gate.sql',
        'gate.output.yaml': 'models/schema/example_table_gate.yml',
        'gate.output.view': 'models/marts/example_table_gated.sql',
        'gate.copy': 'Kopieren',
        'shared.syncStatus': 'Einstellungen zuletzt von {source} ({time})',
        'shared.copied': 'Kopiert!',
    },
    en: {
        ...gateHowtoLabels.en,
        'gate.pageTitle': 'Unreviewed Table Gate Generator',
        'gate.pageLead':
            'Step 3/4: Identify and hide unreviewed models — macros, gate YAML, and gated view example.',
        'gate.reviewRoles.title': 'Review roles',
        'gate.reviewRoles.description':
            'Who may see models without pii-reviewed: true? (comma-separated)',
        'gate.access.title': 'Model access_groups',
        'gate.access.groups': 'Default access_groups',
        'gate.output.macro': 'macros/pii_table_gate.sql',
        'gate.output.yaml': 'models/schema/example_table_gate.yml',
        'gate.output.view': 'models/marts/example_table_gated.sql',
        'gate.copy': 'Copy',
        'shared.syncStatus': 'Settings last saved by {source} ({time})',
        'shared.copied': 'Copied!',
    },
};

/** @param {'de' | 'en'} locale @param {string} key */
export function t(locale, key) {
    return labels[locale]?.[key] ?? labels.en[key] ?? key;
}

export function applyGateLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('gate.') && !key?.startsWith('shared.')) return;
        const text = t(locale, key);
        if (text) el.textContent = text;
    });
}
