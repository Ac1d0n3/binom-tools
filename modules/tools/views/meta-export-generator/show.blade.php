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
                <ul>
                    <li>
                        <strong data-i18n="metaExport.section.schemas"></strong>
                        —
                        <span data-i18n="metaExport.howto.schemas.intro"></span>
                    </li>
                    <li>
                        <strong data-i18n="metaExport.section.tables"></strong>
                        —
                        <span data-i18n="metaExport.howto.tables.intro"></span>
                    </li>
                    <li>
                        <strong data-i18n="metaExport.section.columns"></strong>
                        —
                        <span data-i18n="metaExport.howto.columns.intro"></span>
                    </li>
                    <li>
                        <strong data-i18n="metaExport.section.access"></strong>
                        —
                        <span data-i18n="metaExport.howto.access.intro"></span>
                    </li>
                </ul>
            </div>
        </x-slot:help>

        <label class="sp-field schema-editor-field meta-export-platform">
            <span id="meta-platform-title" data-i18n="metaExport.platform">Platform</span>
            <select id="meta-platform" class="tools-input" aria-labelledby="meta-platform-title"></select>
        </label>

        <p class="meta-export-note" id="meta-platform-note" hidden>
            <strong data-i18n="metaExport.platformNote">Note</strong>:
            <span id="meta-platform-note-text"></span>
        </p>

        <div class="meta-export-queries">
            <x-tools.prism-code
                box-id="meta-schemas-box"
                language="sql"
                title-key="metaExport.section.schemas"
                box-title="Catalog / Schemas"
            />
            <x-tools.prism-code
                box-id="meta-tables-box"
                language="sql"
                title-key="metaExport.section.tables"
                box-title="Tables / Collections"
            />
            <x-tools.prism-code
                box-id="meta-columns-box"
                language="sql"
                title-key="metaExport.section.columns"
                box-title="Columns / Fields"
            />
            <x-tools.prism-code
                box-id="meta-access-box"
                language="sql"
                title-key="metaExport.section.access"
                box-title="Access / Grants"
            />
        </div>
    </x-tools.generator-page>
@endsection
