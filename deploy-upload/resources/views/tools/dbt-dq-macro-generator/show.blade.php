@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/dbt-dq-macro-generator/index.js'],
])

@section('title', 'DQ Macro Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="dqMacro.pageTitle"
        tool-id="dbt-dq-macro-generator"
        app-id="dbt-dq-macro-generator-app"
    >
        <x-tools.collapsible-info summary-key="dqMacro.howto.summary" :open="true">
            <p data-i18n="dqMacro.howto.overview.intro"></p>
            <ol>
                <li data-i18n="dqMacro.howto.overview.step1"></li>
                <li data-i18n="dqMacro.howto.overview.step2"></li>
                <li data-i18n="dqMacro.howto.overview.step3"></li>
                <li data-i18n="dqMacro.howto.overview.step4"></li>
            </ol>
            <p data-i18n="dqMacro.howto.overview.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.panel heading-id="dq-warehouse-title" title-key="dqMacro.warehouse.title" description-key="dqMacro.warehouse.description">
            <x-tools.collapsible-info summary-key="dqMacro.howto.summary" :compact="true">
                <p data-i18n="dqMacro.howto.warehouse.intro"></p>
                <ol>
                    <li data-i18n="dqMacro.howto.warehouse.step1"></li>
                    <li data-i18n="dqMacro.howto.warehouse.step2"></li>
                </ol>
                <p data-i18n="dqMacro.howto.warehouse.tip"></p>
            </x-tools.collapsible-info>
            <x-tools.field label="Warehouse">
                <select id="dq-warehouse" class="pii-policy-input"></select>
            </x-tools.field>
        </x-tools.panel>

        <x-tools.validation-banner id="dq-macro-validation-banner" />

        <x-tools.panel-code
            heading-id="dq-governance-title"
            title-key="dqMacro.output.governance"
            pre-id="dq-governance-pre"
            copy-btn-id="dq-copy-governance-btn"
            copy-key="dqMacro.copy"
        >
            <x-tools.collapsible-info summary-key="dqMacro.howto.summary" :compact="true">
                <p data-i18n="dqMacro.howto.governance.intro"></p>
                <ol>
                    <li data-i18n="dqMacro.howto.governance.step1"></li>
                    <li data-i18n="dqMacro.howto.governance.step2"></li>
                    <li data-i18n="dqMacro.howto.governance.step3"></li>
                </ol>
                <p data-i18n="dqMacro.howto.governance.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-test-title"
            title-key="dqMacro.output.test"
            pre-id="dq-test-pre"
            copy-btn-id="dq-copy-test-btn"
            copy-key="dqMacro.copy"
        >
            <x-tools.collapsible-info summary-key="dqMacro.howto.summary" :compact="true">
                <p data-i18n="dqMacro.howto.test.intro"></p>
                <ol>
                    <li data-i18n="dqMacro.howto.test.step1"></li>
                    <li data-i18n="dqMacro.howto.test.step2"></li>
                </ol>
                <p data-i18n="dqMacro.howto.test.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-setup-title"
            title-key="dqMacro.output.setup"
            pre-id="dq-setup-pre"
            copy-btn-id="dq-copy-setup-btn"
            copy-key="dqMacro.copy"
        >
            <x-tools.collapsible-info summary-key="dqMacro.howto.summary" :compact="true">
                <p data-i18n="dqMacro.howto.setup.intro"></p>
                <ol>
                    <li data-i18n="dqMacro.howto.setup.step1"></li>
                    <li data-i18n="dqMacro.howto.setup.step2"></li>
                </ol>
                <p data-i18n="dqMacro.howto.setup.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.sync-status id="dq-sync-status" />
    </x-tools.generator-page>
@endsection
