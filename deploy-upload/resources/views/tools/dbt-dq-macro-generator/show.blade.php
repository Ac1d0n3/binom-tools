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
        <x-tools.panel heading-id="dq-warehouse-title" title-key="dqMacro.warehouse.title" description-key="dqMacro.warehouse.description">
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
        />

        <x-tools.panel-code
            heading-id="dq-test-title"
            title-key="dqMacro.output.test"
            pre-id="dq-test-pre"
            copy-btn-id="dq-copy-test-btn"
            copy-key="dqMacro.copy"
        />

        <x-tools.panel-code
            heading-id="dq-setup-title"
            title-key="dqMacro.output.setup"
            pre-id="dq-setup-pre"
            copy-btn-id="dq-copy-setup-btn"
            copy-key="dqMacro.copy"
        />

        <x-tools.sync-status id="dq-sync-status" />
    </x-tools.generator-page>
@endsection
