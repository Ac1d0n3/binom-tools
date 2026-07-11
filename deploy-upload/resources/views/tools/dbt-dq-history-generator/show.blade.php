@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/dbt-dq-history-generator/index.js'],
])

@section('title', 'DQ History Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="dqHistory.pageTitle"
        tool-id="dbt-dq-history-generator"
        app-id="dbt-dq-history-generator-app"
    >
        <x-tools.validation-banner id="dq-history-validation-banner" />

        <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-history-model-title">
            <header class="pii-policy-panel__header">
                <h3 id="dq-history-model-title" data-i18n="dqHistory.output.history">models/marts/dq_run_history.sql</h3>
            </header>
            <pre class="pii-policy-code" id="dq-history-pre"></pre>
            <button type="button" class="tools-btn" data-dq-copy="dq-history-pre" data-i18n="dqHistory.copy">Copy</button>
        </section>

        <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-score-title">
            <header class="pii-policy-panel__header">
                <h3 id="dq-score-title" data-i18n="dqHistory.output.score">models/marts/dq_score_daily.sql</h3>
            </header>
            <pre class="pii-policy-code" id="dq-score-pre"></pre>
            <button type="button" class="tools-btn" data-dq-copy="dq-score-pre" data-i18n="dqHistory.copy">Copy</button>
        </section>

        <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-trend-title">
            <header class="pii-policy-panel__header">
                <h3 id="dq-trend-title" data-i18n="dqHistory.output.trend">models/marts/dq_trend_weekly.sql</h3>
            </header>
            <pre class="pii-policy-code" id="dq-trend-pre"></pre>
            <button type="button" class="tools-btn" data-dq-copy="dq-trend-pre" data-i18n="dqHistory.copy">Copy</button>
        </section>

        <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-failures-title">
            <header class="pii-policy-panel__header">
                <h3 id="dq-failures-title" data-i18n="dqHistory.output.failures">models/marts/dq_open_failures.sql</h3>
            </header>
            <pre class="pii-policy-code" id="dq-failures-pre"></pre>
            <button type="button" class="tools-btn" data-dq-copy="dq-failures-pre" data-i18n="dqHistory.copy">Copy</button>
        </section>

        <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-collect-title">
            <header class="pii-policy-panel__header">
                <h3 id="dq-collect-title" data-i18n="dqHistory.output.collect">macros/dq_collect_results.sql</h3>
            </header>
            <pre class="pii-policy-code" id="dq-collect-pre"></pre>
            <button type="button" class="tools-btn" data-dq-copy="dq-collect-pre" data-i18n="dqHistory.copy">Copy</button>
        </section>

        <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-history-setup-title">
            <header class="pii-policy-panel__header">
                <h3 id="dq-history-setup-title" data-i18n="dqHistory.output.setup">SETUP_DQ_HISTORY.md</h3>
            </header>
            <pre class="pii-policy-code" id="dq-history-setup-pre"></pre>
            <button type="button" class="tools-btn" data-dq-copy="dq-history-setup-pre" data-i18n="dqHistory.copy">Copy</button>
        </section>

        <x-tools.sync-status id="dq-history-sync-status" />
    </x-tools.generator-page>
@endsection
