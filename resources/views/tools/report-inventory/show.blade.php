@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/report-inventory/index.js'],
])

@section('title', 'Report Inventory — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="reportInventory.pageTitle"
        lead-key="reportInventory.pageLead"
        tool-id="report-inventory"
        app-id="report-inventory-app"
    >
        <x-tools.collapsible-info summary-key="discovery.howto.summary" :open="true">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="reportInventory.howto.intro"></p>
            <ol>
                <li data-i18n="reportInventory.howto.step1"></li>
                <li data-i18n="reportInventory.howto.step2"></li>
                <li data-i18n="reportInventory.howto.step3"></li>
            </ol>
            <p data-i18n="reportInventory.howto.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
