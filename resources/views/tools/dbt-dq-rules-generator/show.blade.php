@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/dbt-dq-rules-generator/index.js'],
])

@section('title', 'DQ Rules Generator — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="dqRules.pageTitle">DQ Rules Generator</h1>

        <x-tools.workflow-nav tool-id="dbt-dq-rules-generator" />

        <div class="pii-policy-generator" id="dbt-dq-rules-generator-app">
            <section class="pii-policy-panel" aria-labelledby="dq-rules-model-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-rules-model-title" data-i18n="dqRules.model.title">Model</h3>
                </header>
                <label class="pii-policy-field">
                    <span data-i18n="dqRules.model.name">Model name</span>
                    <input id="dq-rules-model-name" class="pii-policy-input" type="text" value="orders" />
                </label>
                <label class="pii-policy-field pii-policy-field--full">
                    <span data-i18n="dqRules.model.description">Description</span>
                    <textarea id="dq-rules-model-description" class="pii-policy-input" rows="3">Example orders model for data quality rules.</textarea>
                </label>
            </section>

            <section class="pii-policy-panel" aria-labelledby="dq-rules-columns-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-rules-columns-title" data-i18n="dqRules.columns.title">Columns & rules</h3>
                </header>
                <div id="dq-rules-columns-root"></div>
                <button type="button" class="tools-btn tools-btn--ghost" id="dq-rules-add-column-btn" data-i18n="dqRules.columns.add">Add column</button>
            </section>

            <section class="pii-policy-panel" aria-labelledby="dq-rules-model-rules-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-rules-model-rules-title" data-i18n="dqRules.modelRules.title">Model-level rules</h3>
                </header>
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
            </section>

            <div id="dq-rules-validation-banner" class="tools-validation-banner" hidden role="alert"></div>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-rules-yaml-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-rules-yaml-title" data-i18n="dqRules.output.yaml">models/schema/example_dq_schema.yml</h3>
                </header>
                <pre class="pii-policy-code" id="dq-rules-yaml-pre"></pre>
                <button type="button" class="tools-btn" id="dq-rules-copy-yaml-btn" data-i18n="dqRules.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-rules-tests-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-rules-tests-title" data-i18n="dqRules.output.tests">Generic tests (snippet)</h3>
                </header>
                <pre class="pii-policy-code" id="dq-rules-tests-pre"></pre>
                <button type="button" class="tools-btn" id="dq-rules-copy-tests-btn" data-i18n="dqRules.copy">Copy</button>
            </section>

            <p class="pii-policy-sync-status" id="dq-rules-sync-status" aria-live="polite"></p>
        </div>
    </div>
@endsection
