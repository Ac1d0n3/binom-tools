@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/pii-policy-generator/index.js'],
])

@section('title', 'PII Policy Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="pii.pageTitle"
        tool-id="pii-policy-generator"
        app-id="pii-policy-generator-app"
    >
            <x-tools.collapsible-info summary-key="pii.howto.summary" :open="true">
                <p data-i18n="pii.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="pii.howto.overview.step1"></li>
                    <li data-i18n="pii.howto.overview.step2"></li>
                    <li data-i18n="pii.howto.overview.step3"></li>
                    <li data-i18n="pii.howto.overview.step4"></li>
                    <li data-i18n="pii.howto.overview.step5"></li>
                    <li data-i18n="pii.howto.overview.step6"></li>
                    <li data-i18n="pii.howto.overview.step7"></li>
                    <li data-i18n="pii.howto.overview.step8"></li>
                    <li data-i18n="pii.howto.overview.step9"></li>
                </ol>
                <p data-i18n="pii.howto.overview.tip"></p>
            </x-tools.collapsible-info>

            <section class="pii-policy-panel pii-policy-panel--scenario" aria-labelledby="pii-scenario-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-scenario-title" data-i18n="pii.scenario.title">Access scenario</h3>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.scenario.intro"></p>
                    <ul id="pii-howto-scenario-roles" class="pii-howto-roles">
                        <li data-i18n="pii.howto.scenario.roles1"></li>
                        <li data-i18n="pii.howto.scenario.roles2"></li>
                    </ul>
                    <ul id="pii-howto-scenario-rules" class="pii-howto-rules" hidden>
                        <li data-i18n="pii.howto.scenario.rules1"></li>
                        <li data-i18n="pii.howto.scenario.rules2"></li>
                        <li data-i18n="pii.howto.scenario.rules3"></li>
                    </ul>
                </x-tools.collapsible-info>
                <p class="pii-policy-scenario" id="pii-scenario-text" data-i18n="pii.scenario.roles"></p>
            </section>

            <section class="pii-policy-panel" aria-labelledby="pii-access-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-access-title" data-i18n="pii.access.title">Access configuration</h3>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.access.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.access.step1"></li>
                        <li data-i18n="pii.howto.access.step2"></li>
                        <li data-i18n="pii.howto.access.step3"></li>
                    </ol>
                    <p data-i18n="pii.howto.access.tip"></p>
                </x-tools.collapsible-info>
                <label class="pii-policy-checkbox">
                    <input type="checkbox" id="pii-use-access-roles" checked />
                    <span data-i18n="pii.access.useRoles">Use Access Roles</span>
                </label>
                <div class="pii-policy-panel__grid pii-policy-access-roles" id="pii-access-roles-panel">
                    <label class="pii-policy-field pii-policy-field--full">
                        <span data-i18n="pii.access.defaultRoles">Default access_roles</span>
                        <input id="pii-default-access-roles" class="pii-policy-input" type="text" value="analyst, support" />
                    </label>
                </div>
                <div class="pii-policy-panel__grid pii-policy-access-rules" id="pii-access-rules-panel" hidden>
                    <label class="pii-policy-field">
                        <span data-i18n="pii.access.maskedRoles">access_rules.masked</span>
                        <input id="pii-masked-roles" class="pii-policy-input" type="text" value="analyst, support" />
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="pii.access.unmaskedRoles">access_rules.unmasked</span>
                        <input id="pii-unmasked-roles" class="pii-policy-input" type="text" value="admin, dpo" />
                    </label>
                </div>
            </section>

            <section class="pii-policy-panel" aria-labelledby="pii-model-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-model-title" data-i18n="pii.model.title">Model</h3>
                    <p data-i18n="pii.model.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.model.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.model.step1"></li>
                        <li data-i18n="pii.howto.model.step2"></li>
                        <li data-i18n="pii.howto.model.step3"></li>
                        <li data-i18n="pii.howto.model.step4"></li>
                    </ol>
                </x-tools.collapsible-info>
                <div class="pii-policy-panel__grid">
                    <label class="pii-policy-field">
                        <span data-i18n="pii.model.name">Model name</span>
                        <input id="pii-model-name" class="pii-policy-input" type="text" value="example_table" />
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="pii.model.sourceTable">Source table</span>
                        <input id="pii-source-table" class="pii-policy-input" type="text" value="raw.example_table" />
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="pii.model.piiVersion">PII details version</span>
                        <input id="pii-version" class="pii-policy-input" type="text" value="cf38c9353be46d305f35c22a8d926c62" />
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="pii.model.defaultScope">Default scope</span>
                        <select id="pii-default-scope" class="pii-policy-input">
                            <option value="internal">internal</option>
                            <option value="external">external</option>
                            <option value="none">none</option>
                        </select>
                    </label>
                    <label class="pii-policy-field pii-policy-field--full">
                        <span data-i18n="pii.model.modelDescription">Model description</span>
                        <textarea id="pii-model-description" class="pii-policy-input pii-policy-input--textarea" rows="2"></textarea>
                        <span class="pii-policy-field-hint" data-i18n="pii.model.modelDescriptionHint"></span>
                    </label>
                    <label class="pii-policy-field pii-policy-field--full">
                        <span data-i18n="pii.model.descriptionExtra">Additional description</span>
                        <textarea id="pii-description-extra" class="pii-policy-input pii-policy-input--textarea" rows="2"></textarea>
                    </label>
                </div>
                <div class="pii-policy-panel__actions">
                    <button type="button" class="tools-btn tools-btn--primary" id="pii-update-model-btn" data-i18n="pii.model.updateYaml">YAML aktualisieren</button>
                </div>
            </section>

            <section class="pii-policy-panel" aria-labelledby="pii-columns-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-columns-title" data-i18n="pii.columns.title">Columns</h3>
                    <p data-i18n="pii.columns.description"></p>
                    <p class="pii-policy-field-hint" data-i18n="pii.columns.descriptionHint"></p>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.columns.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.columns.step1"></li>
                        <li data-i18n="pii.howto.columns.step2"></li>
                        <li data-i18n="pii.howto.columns.step3"></li>
                        <li data-i18n="pii.howto.columns.step4"></li>
                    </ol>
                    <p data-i18n="pii.howto.columns.tip"></p>
                </x-tools.collapsible-info>

                <div class="pii-policy-bulk">
                    <label class="pii-policy-field pii-policy-field--full">
                        <span data-i18n="pii.columns.bulkLabel">Insert columns</span>
                        <input id="pii-bulk-columns" class="pii-policy-input" type="text" data-i18n-placeholder="pii.columns.bulkPlaceholder" placeholder="id, agent_id, agent_name, info_mail, ..." />
                    </label>
                    <div class="pii-policy-panel__actions">
                        <button type="button" class="tools-btn" id="pii-bulk-add-btn" data-i18n="pii.columns.bulkAdd">Insert & suggest</button>
                        <button type="button" class="tools-btn" id="pii-apply-scope-btn" data-i18n="pii.columns.applyScopeAll">Apply scope to all</button>
                        <button type="button" class="tools-btn" id="pii-suggest-all-btn" data-i18n="pii.columns.suggestAll">Re-suggest all</button>
                    </div>
                </div>

                <div class="tools-column-accordion" id="pii-columns-body"></div>
                <button type="button" class="tools-btn" id="pii-add-row-btn" data-i18n="pii.columns.addRow">Add column</button>
            </section>

            <section class="pii-policy-panel" aria-labelledby="pii-yaml-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-yaml-title" data-i18n="pii.yaml.title">schema.yml</h3>
                    <p data-i18n="pii.yaml.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.yaml.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.yaml.step1"></li>
                        <li data-i18n="pii.howto.yaml.step2"></li>
                        <li data-i18n="pii.howto.yaml.step3"></li>
                    </ol>
                    <p data-i18n="pii.howto.yaml.tip"></p>
                </x-tools.collapsible-info>
                <textarea id="pii-yaml-textarea" class="pii-policy-input pii-policy-input--yaml" rows="20" spellcheck="false"></textarea>
                <p class="pii-policy-error" id="pii-parse-error" hidden data-i18n="pii.yaml.parseError"></p>
                <div class="pii-policy-panel__actions">
                    <button type="button" class="tools-btn" id="pii-load-yaml-btn" data-i18n="pii.yaml.load">Load from YAML</button>
                    <button type="button" class="tools-btn tools-btn--primary" id="pii-execute-btn" data-i18n="pii.yaml.execute">Execute</button>
                    <a href="{{ locale_route('tools.schema-yml-editor') }}" class="tools-btn" data-i18n="pii.yaml.openEditor">Open in Schema Editor</a>
                </div>
                <p class="pii-policy-sync-status" id="pii-sync-status" aria-live="polite"></p>
            </section>

            <div id="pii-validation-banner" class="tools-validation-banner" hidden role="alert"></div>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="pii-macro-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-macro-title" data-i18n="pii.macro.title">DBT macro</h3>
                    <p data-i18n="pii.macro.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.macro.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.macro.step1"></li>
                        <li data-i18n="pii.howto.macro.step2"></li>
                        <li data-i18n="pii.howto.macro.step3"></li>
                        <li data-i18n="pii.howto.macro.step4"></li>
                    </ol>
                    <p data-i18n="pii.howto.macro.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="pii-macro-pre"></pre>
                <button type="button" class="tools-btn" id="pii-copy-macro-btn" data-i18n="pii.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="pii-policy-out-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-policy-out-title" data-i18n="pii.policy.title">DBT policy</h3>
                    <p data-i18n="pii.policy.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.policy.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.policy.step1"></li>
                        <li data-i18n="pii.howto.policy.step2"></li>
                        <li data-i18n="pii.howto.policy.step3"></li>
                    </ol>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="pii-policy-pre"></pre>
                <button type="button" class="tools-btn" id="pii-copy-policy-btn" data-i18n="pii.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="pii-model-example-title">
                <header class="pii-policy-panel__header">
                    <h3 id="pii-model-example-title" data-i18n="pii.modelExample.title">DBT model example</h3>
                    <p data-i18n="pii.modelExample.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="pii.howto.summary" :compact="true">
                    <p data-i18n="pii.howto.modelExample.intro"></p>
                    <ol>
                        <li data-i18n="pii.howto.modelExample.step1"></li>
                        <li data-i18n="pii.howto.modelExample.step2"></li>
                        <li data-i18n="pii.howto.modelExample.step3"></li>
                        <li data-i18n="pii.howto.modelExample.step4"></li>
                    </ol>
                    <p data-i18n="pii.howto.modelExample.sourcesTitle"></p>
                    <pre>version: 2
sources:
  - name: raw
    tables:
      - name: example_table</pre>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="pii-model-example-pre"></pre>
                <button type="button" class="tools-btn" id="pii-copy-model-btn" data-i18n="pii.copy">Copy</button>
            </section>
    </x-tools.generator-page>
@endsection
