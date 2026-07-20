@extends('layouts.tools', [
    'viteEntries' => [$platform === 'fabric' ? 'resources/js/tools/fabric-dq-pattern-generator/index.js' : 'resources/js/tools/databricks-dq-pattern-generator/index.js'],
])

@section('title', $pageTitle . ' — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        :title-key="$titleKey"
        :lead-key="$leadKey"
        :tool-id="$toolId"
        app-id="lakehouse-dq-pattern-generator-app"
    >
        <x-tools.collapsible-info summary-key="lakehouseDq.howto.summary" :open="true">
            <p data-i18n="{{ $introKey }}"></p>
            <ol>
                <li data-i18n="lakehouseDq.howto.step1"></li>
                <li data-i18n="lakehouseDq.howto.step2"></li>
                <li data-i18n="lakehouseDq.howto.step3"></li>
            </ol>
            <p data-i18n="{{ $tipKey }}"></p>
        </x-tools.collapsible-info>

        <x-tools.panel heading-id="lakehouse-dq-input-title" title-key="lakehouseDq.input.title" description-key="lakehouseDq.input.description">
            <div class="pii-policy-panel__grid">
                <x-tools.field label-key="lakehouseDq.input.table">
                    <input id="lakehouse-dq-table" class="pii-policy-input" type="text" value="{{ $table }}">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.keys">
                    <input id="lakehouse-dq-keys" class="pii-policy-input" type="text" value="{{ $keys }}">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.required">
                    <input id="lakehouse-dq-required" class="pii-policy-input" type="text" value="{{ $required }}">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.freshness">
                    <input id="lakehouse-dq-freshness" class="pii-policy-input" type="text" value="{{ $freshness }}">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.pii">
                    <input id="lakehouse-dq-pii" class="pii-policy-input" type="text" value="{{ $pii }}">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.owner">
                    <input id="lakehouse-dq-owner" class="pii-policy-input" type="text" value="{{ $owner }}">
                </x-tools.field>
                <x-tools.field label-key="lakehouseDq.input.pattern">
                    <select id="lakehouse-dq-pattern" class="pii-policy-input">
                        @foreach ($patterns as $value => $label)
                            <option value="{{ $value }}" @selected($value === $selectedPattern)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-tools.field>
            </div>
        </x-tools.panel>

        <x-tools.panel-code heading-id="lakehouse-dq-sql-title" :title-key="$sqlTitleKey" pre-id="lakehouse-dq-sql-pre" copy-btn-id="lakehouse-dq-copy-sql" copy-key="lakehouseDq.copy" />
        <x-tools.panel-code heading-id="lakehouse-dq-notebook-title" :title-key="$notebookTitleKey" pre-id="lakehouse-dq-notebook-pre" copy-btn-id="lakehouse-dq-copy-notebook" copy-key="lakehouseDq.copy" />
        <x-tools.panel-code heading-id="lakehouse-dq-runbook-title" title-key="lakehouseDq.output.runbook" pre-id="lakehouse-dq-runbook-pre" copy-btn-id="lakehouse-dq-copy-runbook" copy-key="lakehouseDq.copy" />
    </x-tools.generator-page>
@endsection
