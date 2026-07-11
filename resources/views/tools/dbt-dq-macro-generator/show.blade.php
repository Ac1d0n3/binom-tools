@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/dbt-dq-macro-generator/index.js'],
])

@section('title', 'DQ Macro Generator — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="dqMacro.pageTitle">DQ Macro Generator</h1>

        <x-tools.workflow-nav tool-id="dbt-dq-macro-generator" />

        <div class="pii-policy-generator" id="dbt-dq-macro-generator-app">
            <section class="pii-policy-panel" aria-labelledby="dq-warehouse-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-warehouse-title" data-i18n="dqMacro.warehouse.title">Warehouse</h3>
                    <p data-i18n="dqMacro.warehouse.description"></p>
                </header>
                <label class="pii-policy-field">
                    <span>Warehouse</span>
                    <select id="dq-warehouse" class="pii-policy-input"></select>
                </label>
            </section>

            <div id="dq-macro-validation-banner" class="tools-validation-banner" hidden role="alert"></div>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-governance-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-governance-title" data-i18n="dqMacro.output.governance">macros/dq_governance.sql</h3>
                </header>
                <pre class="pii-policy-code" id="dq-governance-pre"></pre>
                <button type="button" class="tools-btn" id="dq-copy-governance-btn" data-i18n="dqMacro.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-test-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-test-title" data-i18n="dqMacro.output.test">tests/generic/dq_rule.sql</h3>
                </header>
                <pre class="pii-policy-code" id="dq-test-pre"></pre>
                <button type="button" class="tools-btn" id="dq-copy-test-btn" data-i18n="dqMacro.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="dq-setup-title">
                <header class="pii-policy-panel__header">
                    <h3 id="dq-setup-title" data-i18n="dqMacro.output.setup">SETUP_DQ.md</h3>
                </header>
                <pre class="pii-policy-code" id="dq-setup-pre"></pre>
                <button type="button" class="tools-btn" id="dq-copy-setup-btn" data-i18n="dqMacro.copy">Copy</button>
            </section>

            <p class="pii-policy-sync-status" id="dq-sync-status" aria-live="polite"></p>
        </div>
    </div>
@endsection
