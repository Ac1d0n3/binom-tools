@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/fabric-dq-pattern-generator/index.js'],
])

@section('title', 'Fabric DQ Pattern Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="lakehouseDq.fabric.pageTitle"
        lead-key="lakehouseDq.fabric.lead"
        tool-id="fabric-dq-pattern-generator"
        app-id="lakehouse-dq-pattern-generator-app"
    >
        <x-tools.collapsible-info summary-key="lakehouseDq.howto.summary" :open="true">
            <p data-i18n="lakehouseDq.fabric.howto.intro"></p>
            <ol>
                <li data-i18n="lakehouseDq.howto.step1"></li>
                <li data-i18n="lakehouseDq.howto.step2"></li>
                <li data-i18n="lakehouseDq.howto.step3"></li>
            </ol>
            <p data-i18n="lakehouseDq.fabric.howto.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.panel heading-id="lakehouse-dq-input-title" title-key="lakehouseDq.input.title" description-key="lakehouseDq.input.description">
            <div class="pii-policy-panel__grid">
                <x-tools.field label-key="lakehouseDq.input.table">
                    <input id="lakehouse-dq-table" class="pii-policy-input" type="text" value="sales.orders_curated">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.keys">
                    <input id="lakehouse-dq-keys" class="pii-policy-input" type="text" value="order_id">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.required">
                    <input id="lakehouse-dq-required" class="pii-policy-input" type="text" value="order_id, customer_id, order_date, amount">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.freshness">
                    <input id="lakehouse-dq-freshness" class="pii-policy-input" type="text" value="updated_at">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.pii">
                    <input id="lakehouse-dq-pii" class="pii-policy-input" type="text" value="customer_email, customer_name">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.owner">
                    <input id="lakehouse-dq-owner" class="pii-policy-input" type="text" value="data-owner-sales">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.pattern">
                    <select id="lakehouse-dq-pattern" class="pii-policy-input">
                        <option value="dq">DQ Checks</option>
                        <option value="delta">Delta Load</option>
                        <option value="scd2">SCD2</option>
                        <option value="governance">Governance Gate</option>
                    </select>
                </x-tools.field>
            </div>
        </x-tools.panel>

        <x-tools.panel-code heading-id="lakehouse-dq-sql-title" title-key="lakehouseDq.fabric.output.sql" pre-id="lakehouse-dq-sql-pre" copy-btn-id="lakehouse-dq-copy-sql" copy-key="lakehouseDq.copy" />
        <x-tools.panel-code heading-id="lakehouse-dq-notebook-title" title-key="lakehouseDq.fabric.output.notebook" pre-id="lakehouse-dq-notebook-pre" copy-btn-id="lakehouse-dq-copy-notebook" copy-key="lakehouseDq.copy" />
        <x-tools.panel-code heading-id="lakehouse-dq-runbook-title" title-key="lakehouseDq.output.runbook" pre-id="lakehouse-dq-runbook-pre" copy-btn-id="lakehouse-dq-copy-runbook" copy-key="lakehouseDq.copy" />
    </x-tools.generator-page>
@endsection
