@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/tools/js/architecture-fit/index.js'],
])

@section('title', 'Architecture Fit — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="architectureFit.pageTitle"
        lead-key="architectureFit.pageLead"
        tool-id="architecture-fit"
        app-id="architecture-fit-app"
    
        :shared-header="true"
        eyebrow-de="Governance Tool"
        eyebrow-en="Governance tool"
    >
        <x-slot:help>
            <div class="governance-advisor__helpbox-content">
            <p data-i18n="discovery.ephemeral"></p>
            <p data-i18n="architectureFit.howto.intro"></p>
            <ol>
                <li data-i18n="architectureFit.howto.step1"></li>
                <li data-i18n="architectureFit.howto.step2"></li>
                <li data-i18n="architectureFit.howto.step3"></li>
            </ol>
            <p data-i18n="architectureFit.howto.tip"></p>
        
            </div>
        </x-slot:help>

        <div class="discovery-canvas">
            <x-tools.discovery-ephemeral-banner />

            <div data-discovery-checklist></div>

            <section class="tools-panel discovery-export" aria-labelledby="architecture-export-title">
                <h2 id="architecture-export-title" class="discovery-check-section__title" data-i18n="discovery.exportPreview">
                    Export preview
                </h2>
                <p class="discovery-export__hint" data-i18n="discovery.exportHint">
                    Copy or download now — content is lost when you leave this page.
                </p>
                <div class="discovery-export__actions">
                    <button type="button" class="tools-btn tools-btn--primary" data-copy-md data-i18n="discovery.copyMarkdown">Copy Markdown</button>
                    <button type="button" class="tools-btn" data-download-md data-i18n="discovery.downloadMarkdown">Download .md</button>
                    <button type="button" class="tools-btn tools-btn--ghost" data-clear data-i18n="discovery.clear">Reset</button>
                </div>
                <pre class="discovery-export__preview" data-export-preview></pre>
            </section>
        </div>
    </x-tools.generator-page>
@endsection
