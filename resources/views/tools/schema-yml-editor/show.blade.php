@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/schema-yml-editor/index.js'],
])

@section('title', 'Schema YML Editor — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="schema.pageTitle">Schema YML Editor</h1>
        <p class="tools-page-lead" data-i18n="schema.pageLead"></p>

        <p class="tools-standalone-notice" data-i18n="schema.standaloneNotice"></p>

        <div class="schema-yml-editor" id="schema-yml-editor-app">
            <x-tools.collapsible-info summary-key="schema.howto.summary" :open="true">
                <p data-i18n="schema.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="schema.howto.overview.step1"></li>
                    <li data-i18n="schema.howto.overview.step2"></li>
                    <li data-i18n="schema.howto.overview.step3"></li>
                    <li data-i18n="schema.howto.overview.step4"></li>
                </ol>
                <p data-i18n="schema.howto.overview.tip"></p>
            </x-tools.collapsible-info>

            <section class="schema-editor-panel" aria-labelledby="schema-sync-title">
                <header class="schema-editor-panel__header">
                    <h3 id="schema-sync-title" data-i18n="schema.sync.title">Sync</h3>
                </header>
                <x-tools.collapsible-info summary-key="schema.howto.summary" :compact="true">
                    <p data-i18n="schema.howto.sync.intro"></p>
                    <ol>
                        <li data-i18n="schema.howto.sync.step1"></li>
                        <li data-i18n="schema.howto.sync.step2"></li>
                        <li data-i18n="schema.howto.sync.step3"></li>
                    </ol>
                </x-tools.collapsible-info>
                <span class="schema-editor-sync-badge" id="schema-sync-badge" data-i18n="schema.sync.status">Ready</span>
                <div class="schema-editor-panel__actions">
                    <button type="button" class="tools-btn" id="schema-load-storage-btn" data-i18n="schema.sync.load">Load from storage</button>
                    <button type="button" class="tools-btn" id="schema-clear-storage-btn" data-i18n="schema.sync.clear">Clear storage</button>
                    <a href="{{ route('tools.pii-policy-generator') }}" class="tools-btn" data-i18n="schema.sync.openGenerator">Open DBT Policy Generator</a>
                </div>
            </section>

            <section class="schema-editor-panel" aria-labelledby="schema-model-title">
                <header class="schema-editor-panel__header">
                    <h3 id="schema-model-title" data-i18n="schema.model.title">Model</h3>
                </header>
                <x-tools.collapsible-info summary-key="schema.howto.summary" :compact="true">
                    <p data-i18n="schema.howto.model.intro"></p>
                </x-tools.collapsible-info>
                <div class="schema-editor-panel__grid">
                    <label class="schema-editor-field">
                        <span data-i18n="schema.model.name">Model name</span>
                        <input id="schema-model-name" class="schema-editor-input" type="text" value="example_table" />
                    </label>
                    <label class="schema-editor-field">
                        <span data-i18n="schema.model.sourceTable">Source table</span>
                        <input id="schema-source-table" class="schema-editor-input" type="text" value="raw.example_table" />
                    </label>
                    <label class="schema-editor-field">
                        <span data-i18n="schema.model.piiVersion">PII details version</span>
                        <input id="schema-pii-version" class="schema-editor-input" type="text" value="cf38c9353be46d305f35c22a8d926c62" />
                    </label>
                    <label class="schema-editor-field schema-editor-field--full">
                        <span data-i18n="schema.model.modelDescription">Model description</span>
                        <textarea id="schema-model-description" class="schema-editor-input schema-editor-input--textarea" rows="6"></textarea>
                        <span class="schema-editor-field-hint" data-i18n="schema.model.modelDescriptionHint"></span>
                    </label>
                </div>
            </section>

            <section class="schema-editor-panel schema-editor-panel--scenario" aria-labelledby="schema-scenario-title">
                <header class="schema-editor-panel__header">
                    <h3 id="schema-scenario-title" data-i18n="schema.scenario.title">Access scenario</h3>
                </header>
                <x-tools.collapsible-info summary-key="schema.howto.summary" :compact="true">
                    <p data-i18n="schema.howto.scenario.intro"></p>
                    <ul id="schema-howto-scenario-roles" class="schema-editor-howto-list">
                        <li data-i18n="schema.howto.scenario.roles1"></li>
                        <li data-i18n="schema.howto.scenario.roles2"></li>
                    </ul>
                    <ul id="schema-howto-scenario-rules" class="schema-editor-howto-list" hidden>
                        <li data-i18n="schema.howto.scenario.rules1"></li>
                        <li data-i18n="schema.howto.scenario.rules2"></li>
                        <li data-i18n="schema.howto.scenario.rules3"></li>
                    </ul>
                </x-tools.collapsible-info>
                <p class="schema-editor-scenario" id="schema-scenario-text" data-i18n="schema.scenario.roles"></p>
            </section>

            <section class="schema-editor-panel" aria-labelledby="schema-access-title">
                <header class="schema-editor-panel__header">
                    <h3 id="schema-access-title" data-i18n="schema.access.title">Access</h3>
                </header>
                <x-tools.collapsible-info summary-key="schema.howto.summary" :compact="true">
                    <p data-i18n="schema.howto.access.intro"></p>
                    <ol>
                        <li data-i18n="schema.howto.access.step1"></li>
                        <li data-i18n="schema.howto.access.step2"></li>
                        <li data-i18n="schema.howto.access.step3"></li>
                    </ol>
                    <p data-i18n="schema.howto.access.tip"></p>
                </x-tools.collapsible-info>
                <label class="schema-editor-checkbox">
                    <input type="checkbox" id="schema-use-access-roles" checked />
                    <span data-i18n="schema.access.useRoles">Use Access Roles</span>
                </label>
                <div class="schema-editor-panel__grid" id="schema-access-roles-panel">
                    <label class="schema-editor-field schema-editor-field--full">
                        <span data-i18n="schema.access.defaultRoles">Default access_roles</span>
                        <input id="schema-default-access-roles" class="schema-editor-input" type="text" value="analyst, support" />
                    </label>
                </div>
                <div class="schema-editor-panel__grid" id="schema-access-rules-panel" hidden>
                    <label class="schema-editor-field">
                        <span data-i18n="schema.access.maskedRoles">access_rules.masked</span>
                        <input id="schema-masked-roles" class="schema-editor-input" type="text" value="analyst, support" />
                    </label>
                    <label class="schema-editor-field">
                        <span data-i18n="schema.access.unmaskedRoles">access_rules.unmasked</span>
                        <input id="schema-unmasked-roles" class="schema-editor-input" type="text" value="admin, dpo" />
                    </label>
                </div>
            </section>

            <section class="schema-editor-panel" aria-labelledby="schema-columns-title">
                <header class="schema-editor-panel__header">
                    <h3 id="schema-columns-title" data-i18n="schema.columns.title">Columns</h3>
                    <p class="schema-editor-field-hint" data-i18n="schema.columns.descriptionHint"></p>
                </header>
                <x-tools.collapsible-info summary-key="schema.howto.summary" :compact="true">
                    <p data-i18n="schema.howto.columns.intro"></p>
                </x-tools.collapsible-info>
                <div class="tools-column-accordion" id="schema-columns-body"></div>
                <button type="button" class="tools-btn" id="schema-add-row-btn" data-i18n="schema.columns.addRow">Add column</button>
            </section>

            <section class="schema-editor-panel" aria-labelledby="schema-yaml-title">
                <header class="schema-editor-panel__header">
                    <h3 id="schema-yaml-title" data-i18n="schema.yaml.title">YAML preview</h3>
                    <p data-i18n="schema.yaml.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="schema.howto.summary" :compact="true">
                    <p data-i18n="schema.howto.yaml.intro"></p>
                </x-tools.collapsible-info>
                <textarea
                    id="schema-yaml-textarea"
                    class="schema-editor-input schema-editor-input--yaml"
                    rows="20"
                    spellcheck="false"
                    aria-label="schema.yml"
                ></textarea>
                <p class="schema-editor-yaml-error" id="schema-yaml-parse-error" hidden data-i18n="schema.yaml.parseError"></p>
                <button type="button" class="tools-btn" id="schema-copy-yaml-btn" data-i18n="schema.copy">Copy</button>
            </section>
        </div>
    </div>
@endsection
