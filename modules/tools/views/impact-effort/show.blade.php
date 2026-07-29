@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/tools/js/impact-effort/index.js'],
])

@section('title', 'Impact–Effort Prioritizer — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="impactEffort.pageTitle"
        lead-key="impactEffort.pageLead"
        tool-id="impact-effort"
        app-id="impact-effort-app"
    
        :shared-header="true"
        eyebrow-de="Governance Tool"
        eyebrow-en="Governance tool"
    >
        <x-slot:help>
            <div class="governance-advisor__helpbox-content">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="impactEffort.howto.intro"></p>
            <ol>
                <li data-i18n="impactEffort.howto.step1"></li>
                <li data-i18n="impactEffort.howto.step2"></li>
                <li data-i18n="impactEffort.howto.step3"></li>
            </ol>
            <p data-i18n="impactEffort.howto.tip"></p>
        
            </div>
        </x-slot:help>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
