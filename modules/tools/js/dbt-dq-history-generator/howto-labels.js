/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const dqHistoryHowtoLabels = {
    de: {
        'dqHistory.howto.summary': 'So funktioniert\'s',

        'dqHistory.howto.overview.intro':
            'Schritt 3/3 — History-Mart, Report-Views und dq_collect_results für Trends und offene Failures.',
        'dqHistory.howto.overview.step1': 'Nutzt Model-Name aus Step 2 (localStorage).',
        'dqHistory.howto.overview.step2': 'dq_run_history.sql und Report-Views nach models/marts/ kopieren.',
        'dqHistory.howto.overview.step3': 'dq_collect_results.sql nach macros/ — Runbook in SETUP_DQ_HISTORY.md.',
        'dqHistory.howto.overview.tip':
            'Steps 1–2 müssen deployed sein, bevor History sinnvoll befüllt wird.',

        'dqHistory.howto.history.intro':
            'Inkrementelles Fact für jeden DQ-Testlauf — Basis für alle Reports.',
        'dqHistory.howto.history.step1': 'Nach models/marts/dq_run_history.sql kopieren.',
        'dqHistory.howto.history.step2': 'Wird von dq_collect_results befüllt (post-hook oder Runbook).',
        'dqHistory.howto.history.tip': 'unique_key verhindert Duplikate bei Re-Runs.',

        'dqHistory.howto.score.intro': 'Täglicher DQ-Score pro Model — Aggregation aus dq_run_history.',
        'dqHistory.howto.score.step1': 'View dq_score_daily für Dashboards.',
        'dqHistory.howto.score.tip': 'Mit BI-Tool oder dbt docs verbinden.',

        'dqHistory.howto.trend.intro': 'Wöchentlicher Trend — Fehlerrate über Zeit.',
        'dqHistory.howto.trend.step1': 'View dq_trend_weekly aus History-Mart.',
        'dqHistory.howto.trend.tip': 'Zeitraum in der View anpassen, falls nötig.',

        'dqHistory.howto.failures.intro': 'Offene Failures — letzter Status pro Regel noch failed.',
        'dqHistory.howto.failures.step1': 'View dq_open_failures für Ops-Alerts.',
        'dqHistory.howto.failures.tip': 'Severity aus meta.dq_rules (Step 2) wird mitgeführt.',

        'dqHistory.howto.collect.intro':
            'Makro sammelt Test-Ergebnisse nach jedem dbt test/run.',
        'dqHistory.howto.collect.step1': 'Nach macros/dq_collect_results.sql kopieren.',
        'dqHistory.howto.collect.step2': 'Im Runbook: wann und wie aufrufen (on-run-end).',
        'dqHistory.howto.collect.tip': 'Erfordert dq_governance.sql aus Step 1.',

        'dqHistory.howto.setup.intro': 'SETUP_DQ_HISTORY.md — Runbook für Step 3.',
        'dqHistory.howto.setup.step1': 'Reihenfolge: Models deployen, Macro einbinden, testen.',
        'dqHistory.howto.setup.step2': 'Verify: dbt run --select dq_run_history+',
        'dqHistory.howto.setup.tip': 'Zusammen mit SETUP_DQ.md aus Step 1 im Repo ablegen.',
    },
    en: {
        'dqHistory.howto.summary': 'How it works',

        'dqHistory.howto.overview.intro':
            'Step 3/3 — history mart, report views, and dq_collect_results for trends and open failures.',
        'dqHistory.howto.overview.step1': 'Uses model name from step 2 (localStorage).',
        'dqHistory.howto.overview.step2': 'Copy dq_run_history.sql and report views to models/marts/.',
        'dqHistory.howto.overview.step3': 'Copy dq_collect_results.sql to macros/ — runbook in SETUP_DQ_HISTORY.md.',
        'dqHistory.howto.overview.tip':
            'Steps 1–2 must be deployed before history can be populated.',

        'dqHistory.howto.history.intro':
            'Incremental fact for each DQ test run — base for all reports.',
        'dqHistory.howto.history.step1': 'Copy to models/marts/dq_run_history.sql.',
        'dqHistory.howto.history.step2': 'Populated by dq_collect_results (post-hook or runbook).',
        'dqHistory.howto.history.tip': 'unique_key prevents duplicates on re-runs.',

        'dqHistory.howto.score.intro': 'Daily DQ score per model — aggregated from dq_run_history.',
        'dqHistory.howto.score.step1': 'View dq_score_daily for dashboards.',
        'dqHistory.howto.score.tip': 'Connect to BI or dbt docs.',

        'dqHistory.howto.trend.intro': 'Weekly trend — failure rate over time.',
        'dqHistory.howto.trend.step1': 'View dq_trend_weekly from the history mart.',
        'dqHistory.howto.trend.tip': 'Adjust time window in the view if needed.',

        'dqHistory.howto.failures.intro': 'Open failures — latest status per rule still failed.',
        'dqHistory.howto.failures.step1': 'View dq_open_failures for ops alerts.',
        'dqHistory.howto.failures.tip': 'Severity from meta.dq_rules (step 2) is carried through.',

        'dqHistory.howto.collect.intro':
            'Macro collects test results after each dbt test/run.',
        'dqHistory.howto.collect.step1': 'Copy to macros/dq_collect_results.sql.',
        'dqHistory.howto.collect.step2': 'Runbook explains when and how to call it (on-run-end).',
        'dqHistory.howto.collect.tip': 'Requires dq_governance.sql from step 1.',

        'dqHistory.howto.setup.intro': 'SETUP_DQ_HISTORY.md — runbook for step 3.',
        'dqHistory.howto.setup.step1': 'Order: deploy models, wire macro, test.',
        'dqHistory.howto.setup.step2': 'Verify: dbt run --select dq_run_history+',
        'dqHistory.howto.setup.tip': 'Keep alongside SETUP_DQ.md from step 1 in the repo.',
    },
};
