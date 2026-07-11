import { getLocale } from '../../locale';
import { dqHistoryHowtoLabels } from './howto-labels.js';

const labels = {
    de: {
        ...dqHistoryHowtoLabels.de,
        'dqHistory.pageTitle': 'DQ History Generator',
        'dqHistory.output.history': 'models/marts/dq_run_history.sql',
        'dqHistory.output.score': 'models/marts/dq_score_daily.sql',
        'dqHistory.output.trend': 'models/marts/dq_trend_weekly.sql',
        'dqHistory.output.failures': 'models/marts/dq_open_failures.sql',
        'dqHistory.output.collect': 'macros/dq_collect_results.sql',
        'dqHistory.output.setup': 'SETUP_DQ_HISTORY.md',
        'dqHistory.copy': 'Kopieren',
        'shared.syncStatus': 'Einstellungen zuletzt von {source} ({time})',
        'shared.copied': 'Kopiert!',
        'dq.validation.modelName': 'Model-Name fehlt.',
    },
    en: {
        ...dqHistoryHowtoLabels.en,
        'dqHistory.pageTitle': 'DQ History Generator',
        'dqHistory.output.history': 'models/marts/dq_run_history.sql',
        'dqHistory.output.score': 'models/marts/dq_score_daily.sql',
        'dqHistory.output.trend': 'models/marts/dq_trend_weekly.sql',
        'dqHistory.output.failures': 'models/marts/dq_open_failures.sql',
        'dqHistory.output.collect': 'macros/dq_collect_results.sql',
        'dqHistory.output.setup': 'SETUP_DQ_HISTORY.md',
        'dqHistory.copy': 'Copy',
        'shared.syncStatus': 'Settings last saved by {source} ({time})',
        'shared.copied': 'Copied!',
        'dq.validation.modelName': 'Model name is required.',
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

export function applyDqHistoryLabels() {
    const locale = getLocale();
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key?.startsWith('dqHistory.') && !key?.startsWith('shared.')) return;
        const text = t(locale, key);
        if (text) el.textContent = text;
    });
}
