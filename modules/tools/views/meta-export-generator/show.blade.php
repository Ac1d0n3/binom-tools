@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/tools/js/meta-export-generator/index.js'],
])

@section('title', 'Meta Export Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="metaExport.pageTitle"
        lead-key="metaExport.pageLead"
        tool-id="meta-export-generator"
        app-id="meta-export-generator-app"
        :shared-header="true"
        eyebrow-de="Governance Tool"
        eyebrow-en="Governance tool"
    >
        <x-slot:help>
            <div class="governance-advisor__helpbox-head">
                <span class="governance-advisor__helpbox-icon">
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                </span>
                <span>
                    <span class="governance-hub__eyebrow" data-i18n="metaExport.howto.summary">How it works</span>
                    <strong data-i18n="metaExport.pageLead"></strong>
                </span>
            </div>
            <div class="governance-advisor__helpbox-content" data-tool-help>
                <p data-i18n="metaExport.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="metaExport.howto.overview.step1"></li>
                    <li data-i18n="metaExport.howto.overview.step2"></li>
                    <li data-i18n="metaExport.howto.overview.step3"></li>
                </ol>
                <p data-i18n="metaExport.howto.overview.tip"></p>
            </div>
        </x-slot:help>

        <section class="tools-panel meta-export-controls" aria-labelledby="meta-platform-title">
            <label class="sp-field schema-editor-field">
                <span id="meta-platform-title" data-i18n="metaExport.platform">Platform</span>
                <select id="meta-platform" class="tools-input"></select>
            </label>
        </section>

        <p class="meta-export-note" id="meta-platform-note" hidden>
            <strong data-i18n="metaExport.platformNote">Note</strong>:
            <span id="meta-platform-note-text"></span>
        </p>

        <x-tools.prism-code
            heading-id="meta-schemas-title"
            title-key="metaExport.section.schemas"
            box-id="meta-schemas-box"
            language="sql"
            box-title="Catalog / Schemas"
        >
            <x-tools.collapsible-info summary-key="metaExport.howto.summary" :compact="true">
                <p data-i18n="metaExport.howto.schemas.intro"></p>
            </x-tools.collapsible-info>
        </x-tools.prism-code>

        <x-tools.prism-code
            heading-id="meta-tables-title"
            title-key="metaExport.section.tables"
            box-id="meta-tables-box"
            language="sql"
            box-title="Tables / Collections"
        >
            <x-tools.collapsible-info summary-key="metaExport.howto.summary" :compact="true">
                <p data-i18n="metaExport.howto.tables.intro"></p>
            </x-tools.collapsible-info>
        </x-tools.prism-code>

        <x-tools.prism-code
            heading-id="meta-columns-title"
            title-key="metaExport.section.columns"
            box-id="meta-columns-box"
            language="sql"
            box-title="Columns / Fields"
        >
            <x-tools.collapsible-info summary-key="metaExport.howto.summary" :compact="true">
                <p data-i18n="metaExport.howto.columns.intro"></p>
            </x-tools.collapsible-info>
        </x-tools.prism-code>

        <x-tools.prism-code
            heading-id="meta-access-title"
            title-key="metaExport.section.access"
            box-id="meta-access-box"
            language="sql"
            box-title="Access / Grants"
        >
            <x-tools.collapsible-info summary-key="metaExport.howto.summary" :compact="true">
                <p data-i18n="metaExport.howto.access.intro"></p>
            </x-tools.collapsible-info>
        </x-tools.prism-code>
    </x-tools.generator-page>
@endsection
