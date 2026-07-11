@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/dbt-dq-rules-generator/index.js'],
])

@section('title', 'DQ Rules Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="dqRules.pageTitle"
        tool-id="dbt-dq-rules-generator"
        app-id="dbt-dq-rules-generator-app"
    >
        <x-tools.collapsible-info summary-key="dqRules.howto.summary" :open="true">
            <p data-i18n="dqRules.howto.overview.intro"></p>
            <ol>
                <li data-i18n="dqRules.howto.overview.step1"></li>
                <li data-i18n="dqRules.howto.overview.step2"></li>
                <li data-i18n="dqRules.howto.overview.step3"></li>
                <li data-i18n="dqRules.howto.overview.step4"></li>
            </ol>
            <p data-i18n="dqRules.howto.overview.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.panel heading-id="dq-rules-model-title" title-key="dqRules.model.title">
            <x-tools.collapsible-info summary-key="dqRules.howto.summary" :compact="true">
                <p data-i18n="dqRules.howto.model.intro"></p>
                <ol>
                    <li data-i18n="dqRules.howto.model.step1"></li>
                    <li data-i18n="dqRules.howto.model.step2"></li>
                </ol>
                <p data-i18n="dqRules.howto.model.tip"></p>
            </x-tools.collapsible-info>
            <x-tools.field label-key="dqRules.model.name">
                <input id="dq-rules-model-name" class="pii-policy-input" type="text" value="orders" />
            </x-tools.field>
            <x-tools.field label-key="dqRules.model.sourceTable">
                <input id="dq-rules-source-table" class="pii-policy-input" type="text" value="raw.orders" />
            </x-tools.field>
            <p class="tools-panel-meta" id="dq-rules-warehouse-hint" data-i18n="dqRules.model.warehouseHint"></p>
            <x-tools.field label-key="dqRules.model.description" full>
                <textarea id="dq-rules-model-description" class="pii-policy-input" rows="3">Example orders model for data quality rules.</textarea>
            </x-tools.field>
        </x-tools.panel>

        <x-tools.panel heading-id="dq-rules-columns-title" title-key="dqRules.columns.title">
            <x-tools.collapsible-info summary-key="dqRules.howto.summary" :compact="true">
                <p data-i18n="dqRules.howto.columns.intro"></p>
                <ol>
                    <li data-i18n="dqRules.howto.columns.step1"></li>
                    <li data-i18n="dqRules.howto.columns.step2"></li>
                    <li data-i18n="dqRules.howto.columns.step3"></li>
                </ol>
                <p data-i18n="dqRules.howto.columns.tip"></p>
            </x-tools.collapsible-info>
            <x-tools.column-accordion id="dq-rules-columns-root" lead-key="dqRules.columns.lead" />
            <button type="button" class="tools-btn tools-btn--ghost" id="dq-rules-add-column-btn" data-i18n="dqRules.columns.add">Add column</button>
        </x-tools.panel>

        <x-tools.panel heading-id="dq-rules-model-rules-title" title-key="dqRules.modelRules.title">
            <table class="pii-policy-table dq-rules-table">
                <thead>
                    <tr>
                        <th data-i18n="dqRules.rules.id">ID</th>
                        <th data-i18n="dqRules.rules.type">Type</th>
                        <th data-i18n="dqRules.rules.severity">Severity</th>
                        <th>Params</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="dq-rules-model-rules-body"></tbody>
            </table>
            <button type="button" class="tools-btn tools-btn--ghost" id="dq-rules-add-model-rule-btn" data-i18n="dqRules.rules.add">Add rule</button>
        </x-tools.panel>

        <x-tools.validation-banner id="dq-rules-validation-banner" />

        <x-tools.panel-code
            heading-id="dq-rules-yaml-title"
            title-key="dqRules.output.yaml"
            pre-id="dq-rules-yaml-pre"
            copy-btn-id="dq-rules-copy-yaml-btn"
            copy-key="dqRules.copy"
        >
            <x-tools.collapsible-info summary-key="dqRules.howto.summary" :compact="true">
                <p data-i18n="dqRules.howto.yaml.intro"></p>
                <ol>
                    <li data-i18n="dqRules.howto.yaml.step1"></li>
                    <li data-i18n="dqRules.howto.yaml.step2"></li>
                </ol>
                <p data-i18n="dqRules.howto.yaml.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-rules-sources-title"
            title-key="dqRules.output.sources"
            pre-id="dq-rules-sources-pre"
            copy-btn-id="dq-rules-copy-sources-btn"
            copy-key="dqRules.copy"
        >
            <x-tools.collapsible-info summary-key="dqRules.howto.summary" :compact="true">
                <p data-i18n="dqRules.howto.sources.intro"></p>
                <ol>
                    <li data-i18n="dqRules.howto.sources.step1"></li>
                </ol>
                <p data-i18n="dqRules.howto.sources.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-rules-model-sql-title"
            title-key="dqRules.output.modelSql"
            pre-id="dq-rules-model-sql-pre"
            copy-btn-id="dq-rules-copy-model-sql-btn"
            copy-key="dqRules.copy"
        >
            <x-tools.collapsible-info summary-key="dqRules.howto.summary" :compact="true">
                <p data-i18n="dqRules.howto.modelSql.intro"></p>
                <ol>
                    <li data-i18n="dqRules.howto.modelSql.step1"></li>
                </ol>
                <p data-i18n="dqRules.howto.modelSql.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.panel-code
            heading-id="dq-rules-governance-title"
            title-key="dqRules.output.governanceMacro"
            intro-key="dqRules.output.macroIntro"
            pre-id="dq-rules-governance-pre"
            copy-btn-id="dq-rules-copy-governance-btn"
            copy-key="dqRules.copy"
        />

        <x-tools.panel-code
            heading-id="dq-rules-dq-rule-title"
            title-key="dqRules.output.dqRuleTest"
            intro-key="dqRules.output.macroIntro"
            pre-id="dq-rules-dq-rule-pre"
            copy-btn-id="dq-rules-copy-dq-rule-btn"
            copy-key="dqRules.copy"
        />

        <x-tools.panel-code
            heading-id="dq-rules-tests-title"
            title-key="dqRules.output.tests"
            pre-id="dq-rules-tests-pre"
            copy-btn-id="dq-rules-copy-tests-btn"
            copy-key="dqRules.copy"
        >
            <x-tools.collapsible-info summary-key="dqRules.howto.summary" :compact="true">
                <p data-i18n="dqRules.howto.tests.intro"></p>
                <ol>
                    <li data-i18n="dqRules.howto.tests.step1"></li>
                </ol>
                <p data-i18n="dqRules.howto.tests.tip"></p>
            </x-tools.collapsible-info>
        </x-tools.panel-code>

        <x-tools.sync-status id="dq-rules-sync-status" />
    </x-tools.generator-page>
@endsection
