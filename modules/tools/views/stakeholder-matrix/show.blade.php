@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/tools/js/stakeholder-matrix/index.js'],
])

@section('title', 'Stakeholder Matrix — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="stakeholderMatrix.pageTitle"
        lead-key="stakeholderMatrix.pageLead"
        tool-id="stakeholder-matrix"
        app-id="stakeholder-matrix-app"
    
        :shared-header="true"
        eyebrow-de="Governance Tool"
        eyebrow-en="Governance tool"
    >
        <x-slot:help>
            <div class="governance-advisor__helpbox-content">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="stakeholderMatrix.howto.intro"></p>
            <ol>
                <li data-i18n="stakeholderMatrix.howto.step1"></li>
                <li data-i18n="stakeholderMatrix.howto.step2"></li>
                <li data-i18n="stakeholderMatrix.howto.step3"></li>
            </ol>
            <p data-i18n="stakeholderMatrix.howto.tip"></p>
        
            </div>
        </x-slot:help>

        <x-tools.discovery-canvas />
    </x-tools.generator-page>
@endsection
