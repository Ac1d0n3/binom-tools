@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/tools/js/kpi-definition/index.js'],
])

@section('title', 'KPI Definition — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="kpiDefinition.pageTitle"
        lead-key="kpiDefinition.pageLead"
        tool-id="kpi-definition"
        app-id="kpi-definition-app"
    
        :shared-header="true"
        eyebrow-de="Governance Tool"
        eyebrow-en="Governance tool"
    >
        <x-slot:help>
            <div class="governance-advisor__helpbox-content">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="kpiDefinition.howto.intro"></p>
            <ol>
                <li data-i18n="kpiDefinition.howto.step1"></li>
                <li data-i18n="kpiDefinition.howto.step2"></li>
                <li data-i18n="kpiDefinition.howto.step3"></li>
            </ol>
            <p data-i18n="kpiDefinition.howto.tip"></p>
            <p>
                <a href="{{ locale_route('playbooks.show', ['slug' => 'define-kpi']) }}" data-i18n="kpiDefinition.playbookLink">
                    KPI Definition Playbook — Grain, Owner, Versioning
                </a>
            </p>
            <p>
                <a href="{{ locale_route('learning-paths.show', ['slug' => 'trusted-metrics']) }}" data-i18n="kpiDefinition.trustedMetricsLink">
                    Learning path: Trusted metrics
                </a>
            </p>
        
            </div>
        </x-slot:help>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
