@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/dbt-governance-macro-generator/index.js'],
])

@section('title', 'Governance Macro Generator — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="govMacro.pageTitle">Governance Macro Generator</h1>

        <x-tools.workflow-nav tool-id="dbt-governance-macro-generator" />

        <p class="tools-page-lead tools-page-lead--below-workflow" data-i18n="govMacro.pageLead"></p>

        <div class="pii-policy-generator" id="dbt-governance-macro-generator-app">
            <x-tools.collapsible-info summary-key="govMacro.howto.summary" :open="true">
                <p data-i18n="govMacro.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="govMacro.howto.overview.step1"></li>
                    <li data-i18n="govMacro.howto.overview.step2"></li>
                    <li data-i18n="govMacro.howto.overview.step3"></li>
                    <li data-i18n="govMacro.howto.overview.step4"></li>
                    <li data-i18n="govMacro.howto.overview.step5"></li>
                    <li data-i18n="govMacro.howto.overview.step6"></li>
                    <li data-i18n="govMacro.howto.overview.step7"></li>
                </ol>
                <p data-i18n="govMacro.howto.overview.tip"></p>
            </x-tools.collapsible-info>

            <section class="pii-policy-panel" aria-labelledby="gov-warehouse-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gov-warehouse-title" data-i18n="govMacro.warehouse.title">Warehouse</h3>
                    <p data-i18n="govMacro.warehouse.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="govMacro.howto.summary" :compact="true">
                    <p data-i18n="govMacro.howto.warehouse.intro"></p>
                    <ol>
                        <li data-i18n="govMacro.howto.warehouse.step1"></li>
                        <li data-i18n="govMacro.howto.warehouse.step2"></li>
                    </ol>
                    <p data-i18n="govMacro.howto.warehouse.tip"></p>
                </x-tools.collapsible-info>
                <label class="pii-policy-field">
                    <span>Warehouse</span>
                    <select id="gov-warehouse" class="pii-policy-input"></select>
                </label>
            </section>

            <section class="pii-policy-panel" aria-labelledby="gov-access-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gov-access-title" data-i18n="govMacro.access.title">Access configuration</h3>
                </header>
                <x-tools.collapsible-info summary-key="govMacro.howto.summary" :compact="true">
                    <p data-i18n="govMacro.howto.access.intro"></p>
                    <ol>
                        <li data-i18n="govMacro.howto.access.step1"></li>
                        <li data-i18n="govMacro.howto.access.step2"></li>
                        <li data-i18n="govMacro.howto.access.step3"></li>
                    </ol>
                    <p data-i18n="govMacro.howto.access.tip"></p>
                </x-tools.collapsible-info>
                <label class="pii-policy-checkbox">
                    <input type="checkbox" id="gov-use-access-roles" checked />
                    <span data-i18n="govMacro.access.useRoles">Use access roles</span>
                </label>
                <div class="pii-policy-panel__grid pii-policy-access-roles" id="gov-access-roles-panel">
                    <label class="pii-policy-field pii-policy-field--full">
                        <span data-i18n="govMacro.access.defaultRoles">Default access_roles</span>
                        <input id="gov-default-access-roles" class="pii-policy-input" type="text" value="analyst, support" />
                    </label>
                </div>
                <div class="pii-policy-panel__grid pii-policy-access-rules" id="gov-access-rules-panel" hidden>
                    <label class="pii-policy-field">
                        <span data-i18n="govMacro.access.maskedRoles">access_rules.masked</span>
                        <input id="gov-masked-roles" class="pii-policy-input" type="text" value="analyst, support" />
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="govMacro.access.unmaskedRoles">access_rules.unmasked</span>
                        <input id="gov-unmasked-roles" class="pii-policy-input" type="text" value="admin, dpo" />
                    </label>
                </div>
                <label class="pii-policy-field pii-policy-field--full">
                    <span data-i18n="govMacro.access.groups">Default model access_groups</span>
                    <input id="gov-access-groups" class="pii-policy-input" type="text" value="analyst, dpo" />
                </label>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="gov-governance-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gov-governance-title" data-i18n="govMacro.output.governance">macros/pii_governance.sql</h3>
                </header>
                <x-tools.collapsible-info summary-key="govMacro.howto.summary" :compact="true">
                    <p data-i18n="govMacro.howto.governance.intro"></p>
                    <ol>
                        <li data-i18n="govMacro.howto.governance.step1"></li>
                        <li data-i18n="govMacro.howto.governance.step2"></li>
                        <li data-i18n="govMacro.howto.governance.step3"></li>
                    </ol>
                    <p data-i18n="govMacro.howto.governance.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="gov-governance-pre"></pre>
                <button type="button" class="tools-btn" id="gov-copy-governance-btn" data-i18n="govMacro.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="gov-test-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gov-test-title" data-i18n="govMacro.output.test">tests/generic/pii_reviewed.sql</h3>
                </header>
                <x-tools.collapsible-info summary-key="govMacro.howto.summary" :compact="true">
                    <p data-i18n="govMacro.howto.test.intro"></p>
                    <ol>
                        <li data-i18n="govMacro.howto.test.step1"></li>
                        <li data-i18n="govMacro.howto.test.step2"></li>
                    </ol>
                    <p data-i18n="govMacro.howto.test.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="gov-test-pre"></pre>
                <button type="button" class="tools-btn" id="gov-copy-test-btn" data-i18n="govMacro.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="gov-setup-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gov-setup-title" data-i18n="govMacro.output.setup">SETUP.md</h3>
                </header>
                <x-tools.collapsible-info summary-key="govMacro.howto.summary" :compact="true">
                    <p data-i18n="govMacro.howto.setup.intro"></p>
                    <ol>
                        <li data-i18n="govMacro.howto.setup.step1"></li>
                        <li data-i18n="govMacro.howto.setup.step2"></li>
                    </ol>
                    <p data-i18n="govMacro.howto.setup.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="gov-setup-pre"></pre>
                <button type="button" class="tools-btn" id="gov-copy-setup-btn" data-i18n="govMacro.copy">Copy</button>
            </section>

            <p class="pii-policy-sync-status" id="gov-sync-status" aria-live="polite"></p>
        </div>
    </div>
@endsection
