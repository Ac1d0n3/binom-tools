@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/pii-unreviewed-gate-generator/index.js'],
])

@section('title', 'Unreviewed Table Gate Generator — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="gate.pageTitle">Unreviewed Table Gate Generator</h1>

        <x-tools.workflow-nav tool-id="pii-unreviewed-gate-generator" />

        <p class="tools-page-lead tools-page-lead--below-workflow" data-i18n="gate.pageLead"></p>

        <div class="pii-policy-generator" id="pii-unreviewed-gate-generator-app">
            <x-tools.collapsible-info summary-key="gate.howto.summary" :open="true">
                <p data-i18n="gate.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="gate.howto.overview.step1"></li>
                    <li data-i18n="gate.howto.overview.step2"></li>
                    <li data-i18n="gate.howto.overview.step3"></li>
                    <li data-i18n="gate.howto.overview.step4"></li>
                    <li data-i18n="gate.howto.overview.step5"></li>
                </ol>
                <p data-i18n="gate.howto.overview.tip"></p>
            </x-tools.collapsible-info>

            <section class="pii-policy-panel" aria-labelledby="gate-review-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gate-review-title" data-i18n="gate.reviewRoles.title">Review roles</h3>
                    <p data-i18n="gate.reviewRoles.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="gate.howto.summary" :compact="true">
                    <p data-i18n="gate.howto.reviewRoles.intro"></p>
                    <ol>
                        <li data-i18n="gate.howto.reviewRoles.step1"></li>
                        <li data-i18n="gate.howto.reviewRoles.step2"></li>
                    </ol>
                    <p data-i18n="gate.howto.reviewRoles.tip"></p>
                </x-tools.collapsible-info>
                <label class="pii-policy-field pii-policy-field--full">
                    <span data-i18n="gate.reviewRoles.title">Review roles</span>
                    <input id="gate-review-roles" class="pii-policy-input" type="text" value="dpo, security" />
                </label>
            </section>

            <section class="pii-policy-panel" aria-labelledby="gate-access-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gate-access-title" data-i18n="gate.access.title">Model access_groups</h3>
                </header>
                <label class="pii-policy-field pii-policy-field--full">
                    <span data-i18n="gate.access.groups">Default access_groups</span>
                    <input id="gate-access-groups" class="pii-policy-input" type="text" value="analyst, dpo" />
                </label>
            </section>

            <div id="gate-validation-banner" class="tools-validation-banner" hidden role="alert"></div>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="gate-macro-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gate-macro-title" data-i18n="gate.output.macro">macros/pii_table_gate.sql</h3>
                </header>
                <x-tools.collapsible-info summary-key="gate.howto.summary" :compact="true">
                    <p data-i18n="gate.howto.macro.intro"></p>
                    <ol>
                        <li data-i18n="gate.howto.macro.step1"></li>
                        <li data-i18n="gate.howto.macro.step2"></li>
                    </ol>
                    <p data-i18n="gate.howto.macro.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="gate-macro-pre"></pre>
                <button type="button" class="tools-btn" id="gate-copy-macro-btn" data-i18n="gate.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="gate-yaml-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gate-yaml-title" data-i18n="gate.output.yaml">models/schema/example_table_gate.yml</h3>
                </header>
                <x-tools.collapsible-info summary-key="gate.howto.summary" :compact="true">
                    <p data-i18n="gate.howto.yaml.intro"></p>
                    <ol>
                        <li data-i18n="gate.howto.yaml.step1"></li>
                    </ol>
                    <p data-i18n="gate.howto.yaml.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="gate-yaml-pre"></pre>
                <button type="button" class="tools-btn" id="gate-copy-yaml-btn" data-i18n="gate.copy">Copy</button>
            </section>

            <section class="pii-policy-panel pii-policy-panel--code" aria-labelledby="gate-view-title">
                <header class="pii-policy-panel__header">
                    <h3 id="gate-view-title" data-i18n="gate.output.view">models/marts/example_table_gated.sql</h3>
                </header>
                <x-tools.collapsible-info summary-key="gate.howto.summary" :compact="true">
                    <p data-i18n="gate.howto.view.intro"></p>
                    <ol>
                        <li data-i18n="gate.howto.view.step1"></li>
                    </ol>
                    <p data-i18n="gate.howto.view.tip"></p>
                </x-tools.collapsible-info>
                <pre class="pii-policy-code" id="gate-view-pre"></pre>
                <button type="button" class="tools-btn" id="gate-copy-view-btn" data-i18n="gate.copy">Copy</button>
            </section>

            <p class="pii-policy-sync-status" id="gate-sync-status" aria-live="polite"></p>
        </div>
    </div>
@endsection
