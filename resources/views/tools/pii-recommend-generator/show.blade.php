@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/pii-recommend-generator/index.js'],
])

@section('title', 'PII Recommend Generator — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="piiRec.pageTitle">PII Recommend Generator</h1>
        <p class="tools-page-lead" data-i18n="piiRec.pageLead"></p>

        <x-tools.workflow-nav tool-id="pii-recommend-generator" />

        <div class="pii-policy-generator" id="pii-recommend-generator-app">
            <x-tools.collapsible-info summary-key="piiRec.howto.summary" :open="true">
                <p data-i18n="piiRec.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="piiRec.howto.overview.step1"></li>
                    <li data-i18n="piiRec.howto.overview.step2"></li>
                    <li data-i18n="piiRec.howto.overview.step3"></li>
                    <li data-i18n="piiRec.howto.overview.step4"></li>
                    <li data-i18n="piiRec.howto.overview.step5"></li>
                </ol>
                <p data-i18n="piiRec.howto.overview.tip"></p>
            </x-tools.collapsible-info>

            <section class="pii-policy-panel" aria-labelledby="rec-compare-title">
                <header class="pii-policy-panel__header">
                    <h3 id="rec-compare-title" data-i18n="piiRec.compare.title">Two audit types compared</h3>
                </header>
                <div class="pii-heuristic-compare">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th data-i18n="piiRec.howto.compare.colName">Column names</th>
                                <th data-i18n="piiRec.howto.compare.colContent">Cell values</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th data-i18n="piiRec.howto.compare.what">What is checked?</th>
                                <td data-i18n="piiRec.howto.compare.whatName"></td>
                                <td data-i18n="piiRec.howto.compare.whatContent"></td>
                            </tr>
                            <tr>
                                <th data-i18n="piiRec.howto.compare.ruleType">Rule type</th>
                                <td data-i18n="piiRec.howto.compare.ruleName"></td>
                                <td data-i18n="piiRec.howto.compare.ruleContent"></td>
                            </tr>
                            <tr>
                                <th data-i18n="piiRec.howto.compare.command">dbt command</th>
                                <td><code data-i18n="piiRec.howto.compare.commandName"></code></td>
                                <td><code data-i18n="piiRec.howto.compare.commandContent"></code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="pii-policy-panel" aria-labelledby="rec-name-rules-title">
                <header class="pii-policy-panel__header">
                    <span class="pii-heuristic-badge" data-i18n="piiRec.nameRules.badge">Column name</span>
                    <h3 id="rec-name-rules-title" data-i18n="piiRec.nameRules.title">Rules for column names</h3>
                    <p data-i18n="piiRec.nameRules.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="piiRec.howto.summary" :compact="true">
                    <p data-i18n="piiRec.howto.nameRules.intro"></p>
                    <ol>
                        <li data-i18n="piiRec.howto.nameRules.step1"></li>
                        <li data-i18n="piiRec.howto.nameRules.step2"></li>
                    </ol>
                    <p data-i18n="piiRec.howto.nameRules.tip"></p>
                </x-tools.collapsible-info>
                <div class="pii-policy-table-wrap">
                    <table class="pii-policy-table">
                        <thead>
                            <tr>
                                <th data-i18n="piiRec.nameRules.searchText">Search text in column name</th>
                                <th data-i18n="piiRec.nameRules.piiType">Suggested PII type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="rec-name-rules-body"></tbody>
                    </table>
                </div>
                <button type="button" class="tools-btn" id="rec-add-name-rule-btn" data-i18n="piiRec.nameRules.add">Add rule</button>
            </section>

            <section class="pii-policy-panel" aria-labelledby="rec-content-rules-title">
                <header class="pii-policy-panel__header">
                    <span class="pii-heuristic-badge" data-i18n="piiRec.contentRules.badge">Cell value</span>
                    <h3 id="rec-content-rules-title" data-i18n="piiRec.contentRules.title">Rules for cell values</h3>
                    <p data-i18n="piiRec.contentRules.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="piiRec.howto.summary" :compact="true">
                    <p data-i18n="piiRec.howto.contentRules.intro"></p>
                    <ol>
                        <li data-i18n="piiRec.howto.contentRules.step1"></li>
                        <li data-i18n="piiRec.howto.contentRules.step2"></li>
                    </ol>
                    <p data-i18n="piiRec.howto.contentRules.tip"></p>
                </x-tools.collapsible-info>
                <div class="pii-policy-table-wrap">
                    <table class="pii-policy-table">
                        <thead>
                            <tr>
                                <th data-i18n="piiRec.contentRules.regex">Regex (cell value)</th>
                                <th data-i18n="piiRec.contentRules.piiType">Suggested PII type</th>
                                <th data-i18n="piiRec.contentRules.minRate">Min. match rate (%)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="rec-content-rules-body"></tbody>
                    </table>
                </div>
                <button type="button" class="tools-btn" id="rec-add-content-rule-btn" data-i18n="piiRec.contentRules.add">Add rule</button>
            </section>

            <section class="pii-policy-panel" aria-labelledby="rec-config-title">
                <header class="pii-policy-panel__header">
                    <h3 id="rec-config-title" data-i18n="piiRec.access.title">Access & warehouse</h3>
                </header>
                <x-tools.collapsible-info summary-key="piiRec.howto.summary" :compact="true">
                    <p data-i18n="piiRec.howto.access.intro"></p>
                    <ol>
                        <li data-i18n="piiRec.howto.access.step1"></li>
                    </ol>
                    <ul id="rec-howto-access-roles" class="pii-howto-roles">
                        <li data-i18n="piiRec.howto.access.roles1"></li>
                        <li data-i18n="piiRec.howto.access.roles2"></li>
                    </ul>
                    <ul id="rec-howto-access-rules" class="pii-howto-rules" hidden>
                        <li data-i18n="piiRec.howto.access.rules1"></li>
                        <li data-i18n="piiRec.howto.access.rules2"></li>
                        <li data-i18n="piiRec.howto.access.rules3"></li>
                    </ul>
                    <ol start="2">
                        <li data-i18n="piiRec.howto.access.step2"></li>
                    </ol>
                    <p data-i18n="piiRec.howto.access.tip"></p>
                </x-tools.collapsible-info>
                <div class="pii-policy-panel__grid">
                    <label class="pii-policy-field">
                        <span>Warehouse</span>
                        <select id="rec-warehouse" class="pii-policy-input"></select>
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="piiRec.scope.title">Default scope</span>
                        <select id="rec-default-scope" class="pii-policy-input"></select>
                    </label>
                </div>
                <label class="pii-policy-checkbox">
                    <input type="checkbox" id="rec-use-access-roles" checked />
                    <span data-i18n="govMacro.access.useRoles">Use access roles</span>
                </label>
                <div class="pii-policy-panel__grid pii-policy-access-roles" id="rec-access-roles-panel">
                    <label class="pii-policy-field pii-policy-field--full">
                        <span data-i18n="govMacro.access.defaultRoles">Default access_roles</span>
                        <input id="rec-default-access-roles" class="pii-policy-input" type="text" value="analyst, support" />
                    </label>
                </div>
                <div class="pii-policy-panel__grid pii-policy-access-rules" id="rec-access-rules-panel" hidden>
                    <label class="pii-policy-field">
                        <span data-i18n="govMacro.access.maskedRoles">access_rules.masked</span>
                        <input id="rec-masked-roles" class="pii-policy-input" type="text" value="analyst, support" />
                    </label>
                    <label class="pii-policy-field">
                        <span data-i18n="govMacro.access.unmaskedRoles">access_rules.unmasked</span>
                        <input id="rec-unmasked-roles" class="pii-policy-input" type="text" value="admin, dpo" />
                    </label>
                </div>
                <label class="pii-policy-field pii-policy-field--full">
                    <span data-i18n="piiRec.access.groups">Model access_groups</span>
                    <input id="rec-access-groups" class="pii-policy-input" type="text" value="analyst, dpo" />
                </label>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="rec-audit-name-title">
                <header class="pii-policy-panel__header">
                    <h3 id="rec-audit-name-title" data-i18n="piiRec.output.auditName">macros/pii_audit_by_name.sql</h3>
                </header>
                <x-tools.collapsible-info summary-key="piiRec.howto.summary" :compact="true">
                    <p data-i18n="piiRec.howto.auditName.intro"></p>
                    <ol>
                        <li data-i18n="piiRec.howto.auditName.step1"></li>
                        <li data-i18n="piiRec.howto.auditName.step2"></li>
                    </ol>
                    <p data-i18n="piiRec.howto.auditName.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="rec-audit-name-pre"></pre>
                <button type="button" class="tools-btn" id="rec-copy-audit-name-btn" data-i18n="piiRec.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="rec-audit-content-title">
                <header class="pii-policy-panel__header">
                    <h3 id="rec-audit-content-title" data-i18n="piiRec.output.auditContent">macros/pii_content_scan.sql</h3>
                </header>
                <x-tools.collapsible-info summary-key="piiRec.howto.summary" :compact="true">
                    <p data-i18n="piiRec.howto.auditContent.intro"></p>
                    <ol>
                        <li data-i18n="piiRec.howto.auditContent.step1"></li>
                        <li data-i18n="piiRec.howto.auditContent.step2"></li>
                    </ol>
                    <p data-i18n="piiRec.howto.auditContent.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="rec-audit-content-pre"></pre>
                <button type="button" class="tools-btn" id="rec-copy-audit-content-btn" data-i18n="piiRec.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="rec-yaml-title">
                <header class="pii-policy-panel__header">
                    <h3 id="rec-yaml-title" data-i18n="piiRec.output.yaml">models/schema/example_table.yml</h3>
                </header>
                <x-tools.collapsible-info summary-key="piiRec.howto.summary" :compact="true">
                    <p data-i18n="piiRec.howto.yaml.intro"></p>
                    <ol>
                        <li data-i18n="piiRec.howto.yaml.step1"></li>
                        <li data-i18n="piiRec.howto.yaml.step2"></li>
                    </ol>
                    <p data-i18n="piiRec.howto.yaml.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="rec-yaml-pre"></pre>
                <button type="button" class="tools-btn" id="rec-copy-yaml-btn" data-i18n="piiRec.copy">Copy</button>
            </section>

            <p class="pii-policy-sync-status" id="rec-sync-status" aria-live="polite"></p>
        </div>
    </div>
@endsection
