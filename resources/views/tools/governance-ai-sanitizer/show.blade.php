@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/governance-ai-sanitizer/index.js'],
])

@section('title', 'Governance AI Sanitizer — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="governance.pageTitle">Governance AI Sanitizer</h1>
        <p class="tools-page-lead" data-i18n="governance.pageLead">
            Walk through prompt sanitization, copy the outbound message to your LLM, then restore mapped PII from the AI reply.
        </p>

        <div class="gov-tool-playground" id="governance-ai-sanitizer-app">
            <x-tools.collapsible-info summary-key="gov.howto.summary" :open="true">
                <p data-i18n="gov.howto.overview.intro"></p>
                <ol>
                    <li data-i18n="gov.howto.overview.step1"></li>
                    <li data-i18n="gov.howto.overview.step2"></li>
                    <li data-i18n="gov.howto.overview.step3"></li>
                    <li data-i18n="gov.howto.overview.step4"></li>
                    <li data-i18n="gov.howto.overview.step5"></li>
                </ol>
                <p data-i18n="gov.howto.overview.tip"></p>
            </x-tools.collapsible-info>

            <section class="bn-prompt-sanitizer-panel" aria-labelledby="gov-prompt-title">
                <header class="bn-prompt-sanitizer-panel__header">
                    <h3 id="gov-prompt-title" data-i18n="panels.promptSanitizer.title">Prompt sanitizer</h3>
                    <p data-i18n="panels.promptSanitizer.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="gov.howto.summary" :compact="true">
                    <p data-i18n="gov.howto.prompt.intro"></p>
                    <ol>
                        <li data-i18n="gov.howto.prompt.step1"></li>
                        <li data-i18n="gov.howto.prompt.step2"></li>
                        <li data-i18n="gov.howto.prompt.step3"></li>
                    </ol>
                    <p data-i18n="gov.howto.prompt.tip"></p>
                </x-tools.collapsible-info>
                <label class="bn-visually-hidden" for="gov-prompt-input" data-i18n="panels.promptSanitizer.title">Prompt</label>
                <textarea
                    id="gov-prompt-input"
                    class="bn-prompt-sanitizer-panel__input"
                    rows="8"
                    data-i18n-placeholder="panels.promptSanitizer.inputPlaceholder"
                ></textarea>
                <div class="bn-prompt-sanitizer-panel__actions">
                    <button type="button" class="tools-btn tools-btn--primary" id="gov-sanitize-btn" data-i18n="panels.promptSanitizer.sanitizeLabel">
                        Sanitize
                    </button>
                </div>
                <div id="gov-validation-banner" class="tools-validation-banner" hidden role="alert"></div>
                <p class="bn-prompt-sanitizer-panel__summary" id="gov-findings-summary" hidden aria-live="polite"></p>
            </section>

            <section class="gov-tool-playground__llm-block" id="gov-outbound-block" hidden>
                <h4 id="gov-outbound-heading" data-i18n="outbound.heading">Copy to LLM</h4>
                <x-tools.collapsible-info summary-key="gov.howto.summary" :compact="true">
                    <p data-i18n="gov.howto.outbound.intro"></p>
                    <ol>
                        <li data-i18n="gov.howto.outbound.step1"></li>
                        <li data-i18n="gov.howto.outbound.step2"></li>
                        <li data-i18n="gov.howto.outbound.step3"></li>
                    </ol>
                    <p data-i18n="gov.howto.outbound.tip"></p>
                </x-tools.collapsible-info>
                <pre class="gov-tool-playground__llm-pre" id="gov-outbound-pre" aria-labelledby="gov-outbound-heading"></pre>
                <button type="button" class="tools-btn" id="gov-copy-outbound-btn" data-i18n="outbound.copy">Copy</button>
                <p class="gov-tool-playground__hint" data-i18n="outbound.hint"></p>
            </section>

            <section class="bn-governance-ai-response-panel" aria-labelledby="gov-ai-title">
                <header class="bn-governance-ai-response-panel__header">
                    <h3 id="gov-ai-title" data-i18n="panels.aiResponse.title">AI response</h3>
                    <p data-i18n="panels.aiResponse.description"></p>
                </header>
                <x-tools.collapsible-info summary-key="gov.howto.summary" :compact="true">
                    <p data-i18n="gov.howto.aiResponse.intro"></p>
                    <ol>
                        <li data-i18n="gov.howto.aiResponse.step1"></li>
                        <li data-i18n="gov.howto.aiResponse.step2"></li>
                        <li data-i18n="gov.howto.aiResponse.step3"></li>
                    </ol>
                    <p data-i18n="gov.howto.aiResponse.tip"></p>
                </x-tools.collapsible-info>
                <label class="bn-visually-hidden" for="gov-ai-input" data-i18n="panels.aiResponse.title">AI response</label>
                <textarea
                    id="gov-ai-input"
                    class="bn-governance-ai-response-panel__input"
                    rows="6"
                    data-i18n-placeholder="panels.aiResponse.inputPlaceholder"
                ></textarea>
                <div class="bn-governance-ai-response-panel__actions">
                    <button type="button" class="tools-btn" id="gov-simulate-btn" data-i18n="panels.aiResponse.simulateLabel">
                        Simulate demo reply
                    </button>
                    <button type="button" class="tools-btn tools-btn--primary" id="gov-restore-btn" disabled data-i18n="panels.aiResponse.restoreLabel">
                        Restore
                    </button>
                </div>
                <p class="bn-governance-ai-response-panel__hint" data-i18n="panels.aiResponse.restoreHint"></p>
                <article class="bn-governance-ai-response-panel__restored" id="gov-restored-block" hidden aria-live="polite">
                    <h4 data-i18n="panels.aiResponse.restoredHeading">Restored response</h4>
                    <pre class="bn-governance-ai-response-panel__restored-text" id="gov-restored-pre"></pre>
                </article>
            </section>

            <section class="bn-governance-findings-table" aria-labelledby="gov-findings-title">
                <h3 id="gov-findings-title" data-i18n="panels.findings.title">Findings</h3>
                <x-tools.collapsible-info summary-key="gov.howto.summary" :compact="true">
                    <p data-i18n="gov.howto.findings.intro"></p>
                    <ol>
                        <li data-i18n="gov.howto.findings.step1"></li>
                        <li data-i18n="gov.howto.findings.step2"></li>
                        <li data-i18n="gov.howto.findings.step3"></li>
                        <li data-i18n="gov.howto.findings.step4"></li>
                    </ol>
                </x-tools.collapsible-info>
                <p class="bn-governance-findings-table__empty" id="gov-findings-empty" data-i18n="panels.findings.empty">No findings yet.</p>
                <table id="gov-findings-table" hidden>
                    <thead>
                        <tr>
                            <th scope="col" data-i18n="panels.findings.typeColumn">Type</th>
                            <th scope="col" data-i18n="panels.findings.valueColumn">Value</th>
                            <th scope="col" data-i18n="panels.findings.positionColumn">Position</th>
                            <th scope="col" data-i18n="panels.findings.confidenceColumn">Confidence</th>
                        </tr>
                    </thead>
                    <tbody id="gov-findings-body"></tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
