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

            <div
                id="prompt-studio-app"
                class="prompt-studio prompt-studio--regular"
                data-config-base="{{ prompt_studio_config_path() }}"
                data-accounts-enabled="{{ ! empty($accountsEnabled) ? '1' : '0' }}"
                data-account-user-id="{{ $accountUser['id'] ?? '' }}"
                data-library-api-url="{{ ! empty($accountsEnabled) && ! empty($accountUser) ? url('/api/prompt-studio/library') : '' }}"
            >
                <div class="prompt-studio__chrome">
                        <div class="prompt-studio__chrome-top">
                        <div class="prompt-studio__area-toggle" role="tablist" aria-label="Studio area">
                            <button type="button" class="prompt-studio__area-btn prompt-studio__area-btn--active" data-ps-area="prompt" role="tab" aria-selected="true" data-i18n="promptStudio.area.prompt">Prompt</button>
                            <button type="button" class="prompt-studio__area-btn" data-ps-area="rule" role="tab" aria-selected="false" data-i18n="promptStudio.area.rule">Project rule</button>
                            <button type="button" class="prompt-studio__area-btn" data-ps-area="agent" role="tab" aria-selected="false" data-i18n="promptStudio.area.agent">Agent</button>
                        </div>
                        <div class="prompt-studio__chrome-actions">
                            <button type="button" class="tools-btn tools-btn--sm" id="ps-preview-toggle" aria-pressed="true" data-i18n="promptStudio.previewToggle">
                                Hide preview
                            </button>
                            <div class="prompt-studio__mode-toggle" role="group" aria-label="UI mode">
                                <button type="button" class="prompt-studio__mode-btn prompt-studio__mode-btn--active" data-ps-mode="regular" data-i18n="promptStudio.mode.regular">Regular</button>
                                <button type="button" class="prompt-studio__mode-btn" data-ps-mode="tech" data-i18n="promptStudio.mode.tech">Tech</button>
                            </div>
                        </div>
                    </div>

                    <div class="prompt-studio__chrome-drawers">
                        <details class="prompt-studio__drawer" id="ps-help-drawer">
                            <summary class="prompt-studio__drawer-summary" data-i18n="promptStudio.howto.summary">How it works</summary>
                            <div class="prompt-studio__drawer-body prompt-studio__help-body">
                                <div class="tools-column-accordion prompt-studio__help-accordion" id="ps-help-accordion">
                                    <details class="tools-column-accordion__item" data-ps-help-item="prompt" open>
                                        <summary class="tools-column-accordion__summary">
                                            <span class="tools-column-accordion__summary-label" data-i18n="promptStudio.howto.area.prompt.title">Prompt</span>
                                        </summary>
                                        <div class="tools-column-accordion__body">
                                            <p data-i18n="promptStudio.howto.area.prompt.intro"></p>
                                            <ol>
                                                <li data-i18n="promptStudio.howto.area.prompt.step1"></li>
                                                <li data-i18n="promptStudio.howto.area.prompt.step2"></li>
                                                <li data-i18n="promptStudio.howto.area.prompt.step3"></li>
                                            </ol>
                                            <p data-i18n="promptStudio.howto.area.prompt.tip"></p>
                                        </div>
                                    </details>
                                    <details class="tools-column-accordion__item" data-ps-help-item="rule" hidden>
                                        <summary class="tools-column-accordion__summary">
                                            <span class="tools-column-accordion__summary-label" data-i18n="promptStudio.howto.area.rule.title">Project rule</span>
                                        </summary>
                                        <div class="tools-column-accordion__body">
                                            <p data-i18n="promptStudio.howto.area.rule.intro"></p>
                                            <ol>
                                                <li data-i18n="promptStudio.howto.area.rule.step1"></li>
                                                <li data-i18n="promptStudio.howto.area.rule.step2"></li>
                                                <li data-i18n="promptStudio.howto.area.rule.step3"></li>
                                            </ol>
                                            <p data-i18n="promptStudio.howto.area.rule.tip"></p>
                                        </div>
                                    </details>
                                    <details class="tools-column-accordion__item" data-ps-help-item="agent" hidden>
                                        <summary class="tools-column-accordion__summary">
                                            <span class="tools-column-accordion__summary-label" data-i18n="promptStudio.howto.area.agent.title">Agent</span>
                                        </summary>
                                        <div class="tools-column-accordion__body">
                                            <p data-i18n="promptStudio.howto.area.agent.intro"></p>
                                            <ol>
                                                <li data-i18n="promptStudio.howto.area.agent.step1"></li>
                                                <li data-i18n="promptStudio.howto.area.agent.step2"></li>
                                                <li data-i18n="promptStudio.howto.area.agent.step3"></li>
                                            </ol>
                                            <p data-i18n="promptStudio.howto.area.agent.tip"></p>
                                        </div>
                                    </details>
                                    <details class="tools-column-accordion__item" data-ps-help-item="builder">
                                        <summary class="tools-column-accordion__summary">
                                            <span class="tools-column-accordion__summary-label" data-i18n="promptStudio.howto.builder.summary">Builder</span>
                                        </summary>
                                        <div class="tools-column-accordion__body">
                                            <p data-i18n="promptStudio.howto.builder.intro"></p>
                                            <ul>
                                                <li data-i18n="promptStudio.howto.builder.step1"></li>
                                                <li data-i18n="promptStudio.howto.builder.step2"></li>
                                                <li data-i18n="promptStudio.howto.builder.step3"></li>
                                            </ul>
                                        </div>
                                    </details>
                                    <details class="tools-column-accordion__item" data-ps-help-item="workflow">
                                        <summary class="tools-column-accordion__summary">
                                            <span class="tools-column-accordion__summary-label" data-i18n="promptStudio.howto.workflow.summary">Workflows</span>
                                        </summary>
                                        <div class="tools-column-accordion__body">
                                            <p data-i18n="promptStudio.howto.workflow.intro"></p>
                                            <ul>
                                                <li data-i18n="promptStudio.howto.workflow.step1"></li>
                                                <li data-i18n="promptStudio.howto.workflow.step2"></li>
                                                <li data-i18n="promptStudio.howto.workflow.step3"></li>
                                            </ul>
                                        </div>
                                    </details>
                                </div>
                            </div>
                        </details>

                        <details class="prompt-studio__drawer" id="ps-library-drawer">
                            <summary class="prompt-studio__drawer-summary" data-i18n="promptStudio.library.title">Library & workflows</summary>
                            <div class="prompt-studio__drawer-body">
                                <div class="prompt-studio__workspace-tabs" role="tablist">
                                    <button type="button" class="prompt-studio__tab prompt-studio__tab--active" data-ps-tab="library" role="tab" data-i18n="promptStudio.tabs.library">Aufgaben</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="workflows" role="tab" data-i18n="promptStudio.tabs.workflows">Workflows</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="templates" role="tab" data-i18n="promptStudio.tabs.templates">My templates</button>
                                    <button type="button" class="prompt-studio__tab prompt-studio__tech-only" data-ps-tab="roles" role="tab" data-i18n="promptStudio.tabs.roles">Roles</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="recent" role="tab" data-i18n="promptStudio.tabs.recent">Recent</button>
                                    <button type="button" class="prompt-studio__tab" data-ps-tab="favorites" role="tab" data-i18n="promptStudio.tabs.favorites">Favorites</button>
                                </div>
                                <div class="prompt-studio__workspace-toolbar">
                                    <input type="search" id="ps-search" class="tools-input" data-i18n-placeholder="promptStudio.searchPlaceholder" placeholder="Search…" />
                                    <select id="ps-category-filter" class="tools-select" aria-label="Category" data-i18n-aria-label="promptStudio.categoryFilter">
                                        <option value="all" data-i18n="promptStudio.category.all">All categories</option>
                                        <option value="image" data-i18n="promptStudio.category.image">Images</option>
                                        <option value="video" data-i18n="promptStudio.category.video">Video</option>
                                        <option value="music" data-i18n="promptStudio.category.music">Music</option>
                                        <option value="code" data-i18n="promptStudio.category.code">Code / Dev</option>
                                        <option value="business" data-i18n="promptStudio.category.business">Business</option>
                                        <option value="writing" data-i18n="promptStudio.category.writing">Writing / Docs</option>
                                        <option value="mail" data-i18n="promptStudio.category.mail">Email</option>
                                        <option value="personal" data-i18n="promptStudio.category.personal">Personal</option>
                                    </select>
                                    <select id="ps-template-kind-filter" class="tools-select" hidden>
                                        <option value="all" data-i18n="promptStudio.kindFilter.all">All kinds</option>
                                        <option value="prompt" data-i18n="promptStudio.kind.prompt">Prompt</option>
                                        <option value="rule" data-i18n="promptStudio.kind.rule">Project rule</option>
                                        <option value="agent-task" data-i18n="promptStudio.kind.agentTask">Agent task</option>
                                    </select>
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-add-role-btn" hidden data-i18n="promptStudio.roles.add">Add role</button>
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-save-workflow-btn" hidden data-i18n="promptStudio.workflow.save">Save workflow</button>
                                </div>
                                <div id="ps-workspace-list" class="prompt-studio__workspace-list prompt-studio__workspace-grid"></div>
                            </div>
                        </details>
                    </div>
                </div>

                <div class="prompt-studio__workspace">
                    <section class="prompt-studio__builder" aria-label="Prompt builder">
                        <header class="prompt-studio__builder-header">
                            <div class="prompt-studio__builder-heading">
                                <h2 data-i18n="promptStudio.builder.title">Builder</h2>
                                <p data-i18n="promptStudio.builder.lead">Pick a task or workflow above, then fill the fields.</p>
                            </div>
                            <div class="prompt-studio__steps prompt-studio__tech-only" aria-label="Builder steps">
                                <span class="prompt-studio__step prompt-studio__step--active" data-i18n="promptStudio.steps.role">1. Role</span>
                                <span class="prompt-studio__step" data-i18n="promptStudio.steps.task">2. Task</span>
                                <span class="prompt-studio__step" data-i18n="promptStudio.steps.fill">3. Fill in</span>
                            </div>
                        </header>

                        <div class="prompt-studio__builder-controls">
                            <div id="ps-bridge-banner" class="prompt-studio__bridge-banner" hidden role="status"></div>
                            <div id="ps-task-change-hint" class="prompt-studio__task-hint" hidden role="status"></div>
                            <div id="ps-workflow-suggestion" class="prompt-studio__workflow-suggestion" hidden></div>

                            <p id="ps-output-kind-badge" class="prompt-studio__output-kind-badge" role="status"></p>

                            <div id="ps-selection-summary" class="prompt-studio__selection-summary" hidden>
                                <p id="ps-selection-title" class="prompt-studio__selection-title"></p>
                                <p id="ps-selection-meta" class="prompt-studio__selection-meta"></p>
                            </div>
                            <p id="ps-empty-pick" class="prompt-studio__empty-pick" data-i18n="promptStudio.empty.pickTask">
                                Pick a task or workflow in the library above to start.
                            </p>

                            <section id="ps-target-ai" class="prompt-studio__target-ai" hidden>
                                <label class="tools-field prompt-studio__target-ai-model">
                                    <span class="tools-field__label" data-i18n="promptStudio.model">Target AI</span>
                                    <select id="ps-model-select" class="tools-select"></select>
                                </label>
                                <div id="ps-model-plan" class="prompt-studio__model-plan" hidden role="group" aria-label="Plan">
                                    <span class="tools-field__label" data-i18n="promptStudio.modelPlan.label">Plan</span>
                                    <div class="prompt-studio__model-plan-toggle">
                                        <button type="button" class="prompt-studio__plan-btn" data-ps-plan="free" data-i18n="promptStudio.modelPlan.free">Free</button>
                                        <button type="button" class="prompt-studio__plan-btn" data-ps-plan="paid" data-i18n="promptStudio.modelPlan.paid">Paid</button>
                                    </div>
                                </div>
                                <p id="ps-limit-hint" class="prompt-studio__limit-hint" hidden></p>
                            </section>

                            <div class="prompt-studio__builder-toolbar">
                            <div class="prompt-studio__toolbar-group" data-ps-toolbar="edit">
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-undo-btn" disabled data-i18n="promptStudio.undo">Undo</button>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-redo-btn" disabled data-i18n="promptStudio.redo">Redo</button>
                            </div>
                            <div class="prompt-studio__toolbar-group prompt-studio__tech-only" data-ps-toolbar="workflow">
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-btn" data-i18n="promptStudio.chain">Workflow</button>
                                <button type="button" class="tools-btn tools-btn--sm prompt-studio__toolbar-group--tech" id="ps-split-btn" data-i18n="promptStudio.split">Split task</button>
                                <button type="button" class="tools-btn tools-btn--sm prompt-studio__toolbar-group--tech" id="ps-optimize-btn" data-i18n="promptStudio.optimize">Improve prompt</button>
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
                                    <h3 data-i18n="promptStudio.chainTitle">Workflow</h3>
                                    <p data-i18n="promptStudio.chain.lead">Multi-step workflow — copy each step, paste the AI reply into Previous output, then continue.</p>
                                </header>
                                <ol class="prompt-studio__chain-howto" data-i18n-list="promptStudio.chain.howto">
                                    <li data-i18n="promptStudio.chain.howto1">Copy the step prompt into your AI tool.</li>
                                    <li data-i18n="promptStudio.chain.howto2">Paste the AI reply into “Previous output”.</li>
                                    <li data-i18n="promptStudio.chain.howto3">Click Next step.</li>
                                </ol>
                                <div id="ps-chain-learning" class="prompt-studio__chain-learning" hidden></div>
                                <div id="ps-chain-steps"></div>
                                <div class="prompt-studio__chain-actions">
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-prev-btn" data-i18n="promptStudio.chain.prev">Previous step</button>
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-next-btn" data-i18n="promptStudio.chain.next">Next step</button>
                                    <button type="button" class="tools-btn tools-btn--sm" id="ps-chain-save-btn" data-i18n="promptStudio.workflow.save">Save workflow</button>
                                    <button type="button" class="tools-btn tools-btn--sm prompt-studio__toolbar-group--tech" id="ps-chain-add-btn" data-i18n="promptStudio.chainAdd">+ Step</button>
                                    <button type="button" class="tools-btn tools-btn--sm prompt-studio__toolbar-group--tech" id="ps-chain-custom-btn" data-i18n="promptStudio.chain.custom">+ Custom block</button>
                                </div>
                            </div>

                            <section class="prompt-studio__form-section prompt-studio__tech-only" id="ps-role-task-section">
                                <div class="prompt-studio__builder-fields prompt-studio__builder-fields--row prompt-studio__builder-fields--row-2">
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
                                </header>
                                <div id="ps-variants-body" class="prompt-studio__variants-body" hidden>
                                    <p class="prompt-studio__variants-help" data-i18n="promptStudio.variants.help">
                                        One field varies (e.g. motif), everything else stays the same. One value per line, then step through or copy all.
                                    </p>
                                    <label class="tools-field prompt-studio__variants-field-select">
                                        <span class="tools-field__label" data-i18n="promptStudio.variants.field">Varying field</span>
                                        <select id="ps-variants-field" class="tools-select"></select>
                                    </label>
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

                    <section class="prompt-studio__preview" aria-label="Live preview" id="ps-preview-panel">
                        <header class="prompt-studio__preview-header">
                            <h2 data-i18n="promptStudio.preview">Live preview</h2>
                            <div class="prompt-studio__preview-header-actions">
                                <span id="ps-char-count" class="prompt-studio__char-count"></span>
                                <button type="button" class="tools-btn tools-btn--sm" id="ps-preview-collapse-btn" aria-expanded="true" data-i18n="promptStudio.previewToggle.hide">
                                    Hide preview
                                </button>
                            </div>
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
                            <button type="button" class="tools-btn" id="ps-download-md-btn" data-i18n="promptStudio.downloadMd" hidden>Download .md</button>
                            <button type="button" class="tools-btn tools-btn--primary" id="ps-sanitize-btn" data-i18n="promptStudio.sanitize">Anonymize →</button>
                            <button type="button" class="tools-btn" id="ps-save-template-btn" data-i18n="promptStudio.saveTemplate">Save template</button>
                        </footer>
                    </section>
                </div>
                <button type="button" class="tools-btn tools-btn--sm prompt-studio__preview-reopen" id="ps-preview-reopen-btn" hidden data-i18n="promptStudio.previewToggle.show">
                    Show live preview
                </button>
            </div>
        </div>
    </div>
@endsection
