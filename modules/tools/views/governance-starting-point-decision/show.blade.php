@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/tools/js/governance-starting-point-decision/index.js'],
])

@section('title', 'Governance Starting-Point Decision — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="gspd.pageTitle"
        lead-key="gspd.pageLead"
        tool-id="governance-starting-point-decision"
        app-id="gspd-app"
    >
        <x-tools.collapsible-info summary-key="discovery.howto.summary" :open="true">
            <p data-i18n="gspd.howto.intro"></p>
            <ol>
                <li data-i18n="gspd.howto.step1"></li>
                <li data-i18n="gspd.howto.step2"></li>
                <li data-i18n="gspd.howto.step3"></li>
                <li data-i18n="gspd.howto.step4"></li>
            </ol>
            <p data-i18n="gspd.howto.tip"></p>
        </x-tools.collapsible-info>

        <div class="discovery-canvas gspd">
            <x-tools.discovery-ephemeral-banner />

            <p class="gspd-stack-hint" data-stack-hint hidden>
                <span data-i18n="gspd.stackHint"></span>
                <a href="{{ locale_route('tools.custom-stack-builder') }}" data-i18n="gspd.stackHintLink"></a>
            </p>

            <form class="gspd-form" data-gspd-form id="gspd-decision-form" novalidate autocomplete="off"></form>

            <section class="tools-panel discovery-export" aria-labelledby="gspd-export-title">
                <h2 id="gspd-export-title" class="discovery-check-section__title" data-i18n="discovery.exportPreview">
                    Export preview
                </h2>
                <p class="discovery-export__hint" data-i18n="gspd.exportHint"></p>
                <div class="discovery-export__actions">
                    <button type="button" class="tools-btn tools-btn--primary" data-copy-md data-i18n="discovery.copyMarkdown">Copy Markdown</button>
                    <button type="button" class="tools-btn" data-download-md data-i18n="discovery.downloadMarkdown">Download .md</button>
                    <button type="button" class="tools-btn" data-save-draft data-i18n="gspd.saveDraft">Save draft</button>
                    <button type="button" class="tools-btn" data-load-draft data-i18n="gspd.loadDraft">Load draft</button>
                    <button type="button" class="tools-btn tools-btn--ghost" data-clear data-i18n="discovery.clear">Reset</button>
                    <button type="button" class="tools-btn tools-btn--primary" data-plan-only data-apply-to-plan hidden data-i18n="discovery.applyToPlan">Apply to plan</button>
                </div>
                <p class="gspd-status" data-gspd-status aria-live="polite"></p>
                <pre class="discovery-export__preview" data-export-preview></pre>
            </section>
        </div>
    </x-tools.generator-page>
@endsection
