@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/kpi-definition/index.js'],
])

@section('title', 'KPI Definition — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="kpiDefinition.pageTitle"
        lead-key="kpiDefinition.pageLead"
        tool-id="kpi-definition"
        app-id="kpi-definition-app"
    >
        <x-tools.collapsible-info summary-key="discovery.howto.summary" :open="true">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="kpiDefinition.howto.intro"></p>
            <ol>
                <li data-i18n="kpiDefinition.howto.step1"></li>
                <li data-i18n="kpiDefinition.howto.step2"></li>
                <li data-i18n="kpiDefinition.howto.step3"></li>
            </ol>
            <p data-i18n="kpiDefinition.howto.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
