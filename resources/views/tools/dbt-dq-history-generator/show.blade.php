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
        <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :open="true">
            <p data-i18n="dqHistory.howto.overview.intro"></p>
            <ol>
                <li data-i18n="dqHistory.howto.overview.step1"></li>
                <li data-i18n="dqHistory.howto.overview.step2"></li>
                <li data-i18n="dqHistory.howto.overview.step3"></li>
            </ol>
            <p data-i18n="dqHistory.howto.overview.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.validation-banner id="dq-history-validation-banner" />

        <x-tools.panel-code
            heading-id="dq-history-model-title"
            title-key="dqHistory.output.history"
            pre-id="dq-history-pre"
            copy-btn-id="dq-history-copy-btn"
            copy-key="dqHistory.copy"
        >
            <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :compact="true">
                <p data-i18n="dqHistory.howto.history.intro"></p>
                <ol>
                    <li data-i18n="dqHistory.howto.history.step1"></li>
                    <li data-i18n="dqHistory.howto.history.step2"></li>
                </ol>
                <p data-i18n="dqHistory.howto.history.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-score-title"
            title-key="dqHistory.output.score"
            pre-id="dq-score-pre"
            copy-btn-id="dq-score-copy-btn"
            copy-key="dqHistory.copy"
        >
            <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :compact="true">
                <p data-i18n="dqHistory.howto.score.intro"></p>
                <ol>
                    <li data-i18n="dqHistory.howto.score.step1"></li>
                </ol>
                <p data-i18n="dqHistory.howto.score.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-trend-title"
            title-key="dqHistory.output.trend"
            pre-id="dq-trend-pre"
            copy-btn-id="dq-trend-copy-btn"
            copy-key="dqHistory.copy"
        >
            <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :compact="true">
                <p data-i18n="dqHistory.howto.trend.intro"></p>
                <ol>
                    <li data-i18n="dqHistory.howto.trend.step1"></li>
                </ol>
                <p data-i18n="dqHistory.howto.trend.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-failures-title"
            title-key="dqHistory.output.failures"
            pre-id="dq-failures-pre"
            copy-btn-id="dq-failures-copy-btn"
            copy-key="dqHistory.copy"
        >
            <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :compact="true">
                <p data-i18n="dqHistory.howto.failures.intro"></p>
                <ol>
                    <li data-i18n="dqHistory.howto.failures.step1"></li>
                </ol>
                <p data-i18n="dqHistory.howto.failures.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-collect-title"
            title-key="dqHistory.output.collect"
            pre-id="dq-collect-pre"
            copy-btn-id="dq-collect-copy-btn"
            copy-key="dqHistory.copy"
        >
            <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :compact="true">
                <p data-i18n="dqHistory.howto.collect.intro"></p>
                <ol>
                    <li data-i18n="dqHistory.howto.collect.step1"></li>
                    <li data-i18n="dqHistory.howto.collect.step2"></li>
                </ol>
                <p data-i18n="dqHistory.howto.collect.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-history-setup-title"
            title-key="dqHistory.output.setup"
            pre-id="dq-history-setup-pre"
            copy-btn-id="dq-history-setup-copy-btn"
            copy-key="dqHistory.copy"
        >
            <x-tools.collapsible-info summary-key="dqHistory.howto.summary" :compact="true">
                <p data-i18n="dqHistory.howto.setup.intro"></p>
                <ol>
                    <li data-i18n="dqHistory.howto.setup.step1"></li>
                    <li data-i18n="dqHistory.howto.setup.step2"></li>
                </ol>
                <p data-i18n="dqHistory.howto.setup.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.sync-status id="dq-history-sync-status" />
    </x-tools.generator-page>
@endsection
