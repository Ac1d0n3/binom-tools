@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/prompt-studio/index.js'],
])

@section('title', 'Prompt Studio — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="promptStudio.pageTitle">Prompt Studio</h1>
        <p class="tools-page-lead" data-i18n="promptStudio.pageLead">
            Build professional AI prompts in seconds — role, task, and model-aware.
        </p>

        <div class="tools-page-stack">
            <x-tools.workflow-nav tool-id="prompt-studio" />

            <div id="prompt-studio-app" class="prompt-studio prompt-studio--regular" data-config-base="{{ prompt_studio_config_path() }}">
                <div class="prompt-studio__chrome">
                    <div class="prompt-studio__chrome-top">
                        <div class="prompt-studio__mode-toggle" role="group" aria-label="UI mode">
                            <button type="button" class="prompt-studio__mode-btn prompt-studio__mode-btn--active" data-ps-mode="regular" data-i18n="promptStudio.mode.regular">Regular</button>
                            <button type="button" class="prompt-studio__mode-btn" data-ps-mode="tech" data-i18n="promptStudio.mode.tech">Tech</button>
                        </div>
                    </div>

                    <div class="prompt-studio__chrome-drawers">
                        <details class="prompt-studio__drawer" id="ps-library-drawer">
                            <summary class="prompt-studio__drawer-summary" data-i18n="promptStudio.library.title">Library & workflows</summary>
                            <div class="prompt-studio__drawer-body">
                                <div class="prompt-studio__workspace-tabs" role="tablist">
                                    <button type="button" class="prompt-studio__tab prompt-studio__tab--active" data-ps-tab="workflows" role="tab" data-i18n="promptStudio.tabs.workflows">Workflows</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="library" role="tab" data-i18n="promptStudio.tabs.library">Tasks</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="recent" role="tab" data-i18n="promptStudio.tabs.recent">Recent</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="favorites" role="tab" data-i18n="promptStudio.tabs.favorites">Favorites</button>
                                    <button type="button" class="prompt-studio__tab prompt-studio__tab--tech" data-ps-tab="templates" role="tab" data-i18n="promptStudio.tabs.templates">Templates</button>
                                </div>
                                <div class="prompt-studio__workspace-search">
                                    <input type="search" id="ps-search" class="tools-input" data-i18n-placeholder="promptStudio.searchPlaceholder" placeholder="Search…" />
                                </div>
                                <div id="ps-workspace-list" class="prompt-studio__workspace-list prompt-studio__workspace-grid"></div>
                            </div>
                        </details>

                        <details class="prompt-studio__drawer" id="ps-help-drawer">
                            <summary class="prompt-studio__drawer-summary" data-i18n="promptStudio.howto.summary">How it works</summary>
                            <div class="prompt-studio__drawer-body prompt-studio__help-body">
                                <section class="prompt-studio__help-section">
                                    <p data-i18n="promptStudio.howto.overview.intro"></p>
                                    <ol>
                                        <li data-i18n="promptStudio.howto.overview.step1"></li>
                                        <li data-i18n="promptStudio.howto.overview.step2"></li>
                                        <li data-i18n="promptStudio.howto.overview.step3"></li>
                                        <li data-i18n="promptStudio.howto.overview.step4"></li>
                                        <li data-i18n="promptStudio.howto.overview.step5"></li>
                                    </ol>
                                    <p data-i18n="promptStudio.howto.overview.tip"></p>
                                </section>
                                <section class="prompt-studio__help-section">
                                    <h4 data-i18n="promptStudio.howto.builder.summary">Builder</h4>
                                    <p data-i18n="promptStudio.howto.builder.intro"></p>
                                    <ul>
                                        <li data-i18n="promptStudio.howto.builder.step1"></li>
                                        <li data-i18n="promptStudio.howto.builder.step2"></li>
                                        <li data-i18n="promptStudio.howto.builder.step3"></li>
                                    </ul>
                                </section>
                                <section class="prompt-studio__help-section">
                                    <h4 data-i18n="promptStudio.howto.workflow.summary">Workflows</h4>
                                    <p data-i18n="promptStudio.howto.workflow.intro"></p>
                                    <ul>
                                        <li data-i18n="promptStudio.howto.workflow.step1"></li>
                                        <li data-i18n="promptStudio.howto.workflow.step2"></li>
                                        <li data-i18n="promptStudio.howto.workflow.step3"></li>
                                    </ul>
                                </section>
                                <section class="prompt-studio__help-section">
                                    <h4 data-i18n="promptStudio.howto.preview.summary">Preview</h4>
                                    <p data-i18n="promptStudio.howto.preview.intro"></p>
                                    <ul>
                                        <li data-i18n="promptStudio.howto.preview.step1"></li>
                                        <li data-i18n="promptStudio.howto.preview.step2"></li>
                                    </ul>
                                </section>
                            </div>
                        </details>
                    </div>
                </div>

                <div class="prompt-studio__workspace">
                    <section class="prompt-studio__builder" aria-label="Prompt builder">
                        <header class="prompt-studio__builder-header">
                            <div class="prompt-studio__builder-heading">
                                <h2 data-i18n="promptStudio.builder.title">Role & task</h2>
                                <p data-i18n="promptStudio.builder.lead">Pick a role and task — fields adapt automatically.</p>
                            </div>
                            <div class="prompt-studio__steps" aria-label="Builder steps">
                                <span class="prompt-studio__step prompt-studio__step--active" data-i18n="promptStudio.steps.role">1. Role</span>
                                <span class="prompt-studio__step" data-i18n="promptStudio.steps.task">2. Task</span>
                                <span class="prompt-studio__step" data-i18n="promptStudio.steps.fill">3. Fill in</span>
                            </div>
                        </header>

                        <div class="prompt-studio__builder-controls">
                            <div id="ps-bridge-banner" class="prompt-studio__bridge-banner" hidden role="status"></div>
                            <div id="ps-task-change-hint" class="prompt-studio__task-hint" hidden role="status"></div>
                            <div id="ps-workflow-suggestion" class="prompt-studio__workflow-suggestion" hidden></div>

                            <div class="prompt-studio__builder-toolbar">
                            <div class="prompt-studio__toolbar-group" data-ps-toolbar="edit">
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-undo-btn" disabled data-i18n="promptStudio.undo">Undo</button>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-redo-btn" disabled data-i18n="promptStudio.redo">Redo</button>
                            </div>
                            <div class="prompt-studio__toolbar-group prompt-studio__toolbar-group--tech" data-ps-toolbar="workflow">
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-btn" data-i18n="promptStudio.chain">Chain</button>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-split-btn" data-i18n="promptStudio.split">Split task</button>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-optimize-btn" data-i18n="promptStudio.optimize">Improve prompt</button>
                            </div>
                            <div class="prompt-studio__toolbar-group prompt-studio__toolbar-group--tech" data-ps-toolbar="data">
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-import-btn" data-i18n="promptStudio.import">Import</button>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-export-btn" data-i18n="promptStudio.export">Export</button>
                                <input type="file" id="ps-import-file" accept="application/json,.json" hidden />
                            </div>
                        </div>

                        <div class="prompt-studio__builder-body">
                            <div id="ps-chain-panel" class="prompt-studio__chain-panel" hidden>
                                <header class="prompt-studio__subpanel-header">
                                    <h3 data-i18n="promptStudio.chainTitle">Prompt chain</h3>
                                    <p data-i18n="promptStudio.chain.lead">Multi-step workflow — copy and anonymize each step separately.</p>
                                </header>
                                <div id="ps-chain-learning" class="prompt-studio__chain-learning" hidden></div>
                                <div id="ps-chain-steps"></div>
                                <div class="prompt-studio__chain-actions">
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-prev-btn" data-i18n="promptStudio.chain.prev">Previous step</button>
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-next-btn" data-i18n="promptStudio.chain.next">Next step</button>
                                    <button type="button" class="tools-btn tools-btn--sm prompt-studio__toolbar-group--tech" id="ps-chain-add-btn" data-i18n="promptStudio.chainAdd">+ Step</button>
                                    <button type="button" class="tools-btn tools-btn--sm prompt-studio__toolbar-group--tech" id="ps-chain-custom-btn" data-i18n="promptStudio.chain.custom">+ Custom block</button>
                                </div>
                            </div>

                            <section class="prompt-studio__form-section">
                                <div class="prompt-studio__builder-fields prompt-studio__builder-fields--row">
                                    <label class="tools-field">
                                        <span class="tools-field__label" data-i18n="promptStudio.role">Role</span>
                                        <select id="ps-role-select" class="tools-select"></select>
                                        <p class="tools-field__help" id="ps-role-help"></p>
                                    </label>
                                    <label class="tools-field">
                                        <span class="tools-field__label" data-i18n="promptStudio.task">Task</span>
                                        <select id="ps-task-select" class="tools-select"></select>
                                        <p class="tools-field__help" id="ps-task-help"></p>
                                    </label>
                                    <label class="tools-field">
                                        <span class="tools-field__label" data-i18n="promptStudio.model">Target model</span>
                                        <select id="ps-model-select" class="tools-select"></select>
                                        <p class="tools-field__help tools-field__help--spacer" aria-hidden="true">&nbsp;</p>
                                    </label>
                                </div>
                            </section>

                            <section class="prompt-studio__form-section prompt-studio__form-section--params">
                                <h3 class="prompt-studio__form-section-title" data-i18n="promptStudio.fields.title">Task fields</h3>
                                <div id="ps-dynamic-fields" class="prompt-studio__dynamic-fields"></div>
                            </section>

                            <section id="ps-variants-panel" class="prompt-studio__variants-panel">
                                <header class="prompt-studio__variants-header">
                                    <label class="prompt-studio__variants-toggle">
                                        <input type="checkbox" id="ps-variants-enabled" />
                                        <span data-i18n="promptStudio.variants.title">Variants</span>
                                    </label>
                                    <label class="tools-field prompt-studio__variants-field-select">
                                        <span class="tools-field__label" data-i18n="promptStudio.variants.field">Varying field</span>
                                        <select id="ps-variants-field" class="tools-select"></select>
                                    </label>
                                </header>
                                <div id="ps-variants-body" class="prompt-studio__variants-body" hidden>
                                    <textarea id="ps-variants-bulk" class="tools-textarea" rows="4" data-i18n-placeholder="promptStudio.variants.bulkPlaceholder" placeholder="One value per line…"></textarea>
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-variants-add-btn" data-i18n="promptStudio.variants.add">Add values</button>
                                    <ul id="ps-variants-list" class="prompt-studio__variants-list"></ul>
                                    <div class="prompt-studio__variants-nav">
                                        <button type="button" class="tools-btn tools-btn--sm" id="ps-variants-prev" data-i18n-aria="promptStudio.variants.prev" aria-label="Previous">◀</button>
                                        <span id="ps-variants-counter"></span>
                                        <button type="button" class="tools-btn tools-btn--sm" id="ps-variants-next" data-i18n-aria="promptStudio.variants.next" aria-label="Next">▶</button>
                                        <button type="button" class="tools-btn tools-btn--sm" id="ps-variants-copy-all" data-i18n="promptStudio.variants.copyAll">Copy all</button>
                                    </div>
                                </div>
                            </section>

                            <div id="ps-continue-panel" class="prompt-studio__continue-panel prompt-studio__toolbar-group--tech" hidden>
                                <h3 data-i18n="promptStudio.continueTitle">Conversation history</h3>
                                <div id="ps-continue-timeline"></div>
                                <textarea id="ps-continue-response" class="tools-textarea" rows="3" data-i18n-placeholder="promptStudio.continueResponsePlaceholder" placeholder="Paste AI response…"></textarea>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-continue-btn" data-i18n="promptStudio.continue">Continue</button>
                            </div>

                            <div id="ps-meta-panel" class="prompt-studio__meta-panel prompt-studio__toolbar-group--tech" hidden>
                                <h3 id="ps-meta-title"></h3>
                                <pre id="ps-meta-pre" class="prompt-studio__meta-pre"></pre>
                                <button type="button" class="tools-btn" id="ps-meta-copy-btn" data-i18n="promptStudio.copyMeta">Copy meta-prompt</button>
                                <button type="button" class="tools-btn" id="ps-meta-apply-btn" data-i18n="promptStudio.applyMeta">Apply as draft</button>
                                <button type="button" class="tools-btn" id="ps-meta-sanitize-btn" data-i18n="promptStudio.metaSanitize">Send to sanitizer</button>
                            </div>
                        </div>
                    </section>

                    <section class="prompt-studio__preview" aria-label="Live preview">
                        <header class="prompt-studio__preview-header">
                            <h2 data-i18n="promptStudio.preview">Live preview</h2>
                            <span id="ps-char-count" class="prompt-studio__char-count"></span>
                        </header>
                        <div class="prompt-studio__preview-body">
                            <div id="ps-preview-single" class="prompt-studio__preview-single" hidden>
                                <textarea id="ps-preview-text" class="tools-textarea prompt-studio__preview-text" rows="16" readonly></textarea>
                            </div>
                            <div id="ps-sections" class="prompt-studio__sections"></div>
                            <div id="ps-artist-strip-notice" class="prompt-studio__artist-strip-notice" hidden></div>
                            <div id="ps-pii-preview" class="prompt-studio__pii-preview" hidden></div>
                        </div>
                        <footer class="prompt-studio__preview-actions">
                            <button type="button" class="tools-btn" id="ps-copy-btn" data-i18n="promptStudio.copy">Copy prompt</button>
                            <button type="button" class="tools-btn tools-btn--primary" id="ps-sanitize-btn" data-i18n="promptStudio.sanitize">Anonymize →</button>
                            <button type="button" class="tools-btn prompt-studio__toolbar-group--tech" id="ps-save-template-btn" data-i18n="promptStudio.saveTemplate">Save template</button>
                        </footer>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
