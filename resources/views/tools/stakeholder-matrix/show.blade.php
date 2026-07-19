@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/stakeholder-matrix/index.js'],
])

@section('title', 'Stakeholder Matrix — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="stakeholderMatrix.pageTitle"
        lead-key="stakeholderMatrix.pageLead"
        tool-id="stakeholder-matrix"
        app-id="stakeholder-matrix-app"
    >
        <x-tools.collapsible-info summary-key="discovery.howto.summary" :open="true">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="stakeholderMatrix.howto.intro"></p>
            <ol>
                <li data-i18n="stakeholderMatrix.howto.step1"></li>
                <li data-i18n="stakeholderMatrix.howto.step2"></li>
                <li data-i18n="stakeholderMatrix.howto.step3"></li>
            </ol>
            <p data-i18n="stakeholderMatrix.howto.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
