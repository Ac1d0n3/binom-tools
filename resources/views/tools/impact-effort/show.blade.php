@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/impact-effort/index.js'],
])

@section('title', 'Impact–Effort Prioritizer — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="impactEffort.pageTitle"
        lead-key="impactEffort.pageLead"
        tool-id="impact-effort"
        app-id="impact-effort-app"
    >
        <x-tools.collapsible-info summary-key="discovery.howto.summary" :open="true">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="impactEffort.howto.intro"></p>
            <ol>
                <li data-i18n="impactEffort.howto.step1"></li>
                <li data-i18n="impactEffort.howto.step2"></li>
                <li data-i18n="impactEffort.howto.step3"></li>
            </ol>
            <p data-i18n="impactEffort.howto.tip"></p>
        </x-tools.collapsible-info>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
