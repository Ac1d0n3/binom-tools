@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/playbooks.css',
        'resources/css/sprint-planner.css',
        'resources/js/sprint-planner/show.js',
    ],
    'mainClass' => 'tools-shell__main--sprint-planner',
])

@section('title', 'Sprint Plan — ' . config('app.name'))

@section('content')
    <div
        class="tools-content tools-content--wide sp-app"
        id="sp-app"
        data-sp-page="show"
        data-sp-instance-id="{{ $instanceId }}"
        data-sp-templates="{{ json_encode($templatesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        data-sp-stories="{{ json_encode($storiesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        data-sp-index-url="{{ locale_route('sprint-planner.index') }}"
        data-sp-settings-url="{{ locale_route('sprint-planner.settings', ['instanceId' => $instanceId]) }}"
        @include('components.sprint-planner.accounts-attrs')
    >
        @include('components.sprint-planner.bootstrap-json')

        <p id="sp-ephemeral-banner" class="sp-local-banner" role="status" hidden>
            <i class="fa-solid fa-flask" aria-hidden="true"></i>
            <span>{{ current_locale() === 'de'
                ? 'Demo-Plan — nur in dieser Browser-Session. Optional: Plan-Passwort in den Einstellungen (ohne Login, nur lokal). Zum Speichern und Teilen auf dem Server brauchst du lokale Login-Daten.'
                : 'Demo plan — this browser session only. Optional: set a plan password in settings (no login, local soft-lock). Sign in with local credentials to save and share on the server.' }}</span>
            @if (! empty($loginUrl))
                <a
                    id="sp-ephemeral-login"
                    href="{{ $loginUrl }}"
                    class="tools-btn tools-btn--secondary tools-btn--small"
                >{{ current_locale() === 'de' ? 'Anmelden' : 'Sign in' }}</a>
            @endif
        </p>

        <div id="sp-plan-missing" class="sp-empty" hidden>
            <p data-i18n="sp.error.planMissing">This plan was not found in local storage.</p>
            <a href="{{ locale_route('sprint-planner.index') }}?list=1" class="tools-btn tools-btn--primary" data-i18n="sp.action.backToPlans">Back to plans</a>
        </div>

        <div id="sp-plan-locked" class="sp-empty sp-lock-panel" hidden>
            <h2 class="sp-section__title" data-i18n="sp.password.unlockTitle">Unlock plan</h2>
            <p data-i18n="sp.password.unlockLead">This plan is locally protected. Enter the password.</p>
            <p class="sp-password-note" data-i18n="sp.password.note">Local soft lock in this browser only — not real access control.</p>
            <form id="sp-unlock-form" class="sp-lock-form">
                <label class="sp-field">
                    <span data-i18n="sp.password.current">Current password</span>
                    <input type="password" id="sp-unlock-password" class="tools-input" autocomplete="current-password" required minlength="4">
                </label>
                <p id="sp-unlock-error" class="sp-field-error" hidden></p>
                <div class="sp-action-row">
                    <a href="{{ locale_route('sprint-planner.index') }}?list=1" class="tools-btn tools-btn--secondary" data-i18n="sp.action.backToPlans">Back to plans</a>
                    <button type="submit" class="tools-btn tools-btn--primary" data-i18n="sp.password.unlock">Unlock</button>
                </div>
            </form>
        </div>

        <div id="sp-plan-view" class="sp-plan-shell" hidden>
            <header class="sp-plan-header" id="sp-plan-header" data-expanded="0">
                <div class="sp-plan-header__toolbar">
                    <label class="sp-plan-header__search">
                        <span class="visually-hidden" data-i18n="sp.filter.search">Search</span>
                        <input
                            type="search"
                            id="sp-plan-search"
                            class="tools-input"
                            data-i18n-placeholder="sp.filter.searchPlaceholder"
                            placeholder="Search tasks…"
                            autocomplete="off"
                        >
                    </label>
                    <div class="sp-plan-header__toolbar-actions">
                        <button
                            type="button"
                            class="tools-btn tools-btn--secondary tools-btn--small sp-chrome-icon-btn"
                            id="sp-expand-all-sprints"
                            data-sp-chrome="expand-all"
                            data-i18n-aria="sp.action.expandAllSprints"
                            title="Expand all"
                        >
                            <i class="fa-solid fa-angles-down" aria-hidden="true"></i>
                            <span class="sr-only" data-i18n="sp.action.expandAllSprints">Expand all</span>
                        </button>
                        <button
                            type="button"
                            class="tools-btn tools-btn--secondary tools-btn--small sp-chrome-icon-btn"
                            id="sp-collapse-all-sprints"
                            data-sp-chrome="collapse-all"
                            data-i18n-aria="sp.action.collapseAllSprints"
                            title="Collapse all"
                        >
                            <i class="fa-solid fa-angles-up" aria-hidden="true"></i>
                            <span class="sr-only" data-i18n="sp.action.collapseAllSprints">Collapse all</span>
                        </button>
                        <button
                            type="button"
                            class="tools-btn tools-btn--secondary tools-btn--small sp-filter-toggle"
                            id="sp-filter-sidebar-toggle"
                            data-sp-chrome="filters-toggle"
                            aria-expanded="true"
                            data-i18n-aria="sp.action.hideFilters"
                            title="Hide filters"
                        >
                            <i class="fa-solid fa-filter" aria-hidden="true" data-sp-filter-icon></i>
                            <span data-i18n="sp.action.toggleFilters">Filter</span>
                        </button>
                        <button
                            type="button"
                            class="playbook-focus-toggle tools-btn tools-btn--ghost tools-btn--small"
                            data-playbook-focus-button
                            aria-pressed="false"
                            data-i18n-aria="playbooks.focusExpand"
                            title="Hide sidebars"
                        >
                            <i class="fa-solid fa-up-right-and-down-left-from-center" aria-hidden="true"></i>
                            <span class="sr-only" data-playbook-focus-label data-i18n="playbooks.focusExpand">Hide sidebars</span>
                        </button>
                        <button type="button" class="tools-btn tools-btn--secondary tools-btn--small" id="sp-undo-btn" data-i18n="sp.action.undo" hidden>Undo</button>
                        <button type="button" class="tools-btn tools-btn--secondary tools-btn--small" id="sp-history-btn" data-i18n="sp.action.history" hidden>History</button>
                        <button type="button" class="tools-btn tools-btn--secondary tools-btn--small" id="sp-status-report-btn" data-i18n="sp.action.statusReport">Report</button>
                        <a href="{{ locale_route('sprint-planner.settings', ['instanceId' => $instanceId]) }}" class="tools-btn tools-btn--secondary tools-btn--small" data-i18n="sp.action.settings">Settings</a>
                        <button type="button" class="tools-btn tools-btn--primary tools-btn--small" id="sp-add-sprint" data-i18n="sp.action.addSprint">Add sprint</button>
                    </div>
                </div>
                <div class="sp-plan-header__title-row">
                    <h1 class="sp-plan-header__title" id="sp-plan-title"></h1>
                    <button
                        type="button"
                        class="sp-icon-btn sp-plan-header__size-toggle"
                        id="sp-header-size-toggle"
                        data-sp-chrome="header-toggle"
                        aria-expanded="false"
                        data-i18n-aria="sp.action.expandHeader"
                        title="Expand header"
                    >
                        <i class="fa-solid fa-chevron-down" aria-hidden="true" data-sp-header-icon></i>
                        <span class="sr-only" data-i18n="sp.action.expandHeader">Expand header</span>
                    </button>
                </div>
                <div class="sp-plan-header__expanded" id="sp-plan-header-expanded">
                    <div class="sp-plan-header__main">
                        <p class="tools-page-lead" id="sp-plan-description"></p>
                        <dl class="sp-meta">
                            <div><dt data-i18n="sp.field.startDate">Start date</dt><dd id="sp-plan-started"></dd></div>
                            <div><dt data-i18n="sp.field.owner">Owner</dt><dd id="sp-plan-owner"></dd></div>
                            <div><dt data-i18n="sp.field.currentSprint">Current sprint</dt><dd id="sp-plan-current-sprint"></dd></div>
                            <div><dt data-i18n="sp.field.status">Status</dt><dd id="sp-plan-status"></dd></div>
                            <div><dt data-i18n="sp.field.team">Team</dt><dd id="sp-plan-team"></dd></div>
                            <div><dt data-i18n="sp.field.participants">Participants</dt><dd id="sp-plan-participants"></dd></div>
                        </dl>
                        <div
                            id="sp-plan-status-overview"
                            class="sp-status-overview"
                            role="group"
                            aria-label="Item status overview"
                            hidden
                        ></div>
                        <div class="sp-progress" id="sp-plan-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="sp-progress__bar" id="sp-plan-progress-bar"></div>
                            <span class="sp-progress__label" id="sp-plan-progress-label">0%</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="sp-plan-body" id="sp-plan-layout">
                <div class="sp-plan-scroll" id="sp-plan-scroll">
                    <div id="sp-unassigned-banner" class="sp-unassigned-banner" role="status" hidden>
                        <p id="sp-unassigned-banner-text" data-i18n="sp.unassigned.banner">All tasks are unassigned.</p>
                        <button type="button" class="tools-btn tools-btn--primary tools-btn--small" id="sp-claim-all-btn" data-i18n="sp.action.claimAll">Claim all as me</button>
                    </div>

                    <div id="sp-template-missing" class="sp-empty sp-template-recover" hidden>
                        <p id="sp-template-missing-text"></p>
                        <p class="sp-password-note" data-i18n="sp.recover.lead">
                            Choose the original template to restore this plan’s tasks. Progress and assignments are kept when status keys still match.
                        </p>
                        <div class="sp-action-row sp-template-recover__row">
                            <label class="sp-field">
                                <span data-i18n="sp.recover.pickTemplate">Template</span>
                                <select id="sp-recover-template" class="tools-input"></select>
                            </label>
                            <button type="button" class="tools-btn tools-btn--primary" id="sp-recover-template-btn" data-i18n="sp.recover.apply">
                                Restore template
                            </button>
                        </div>
                    </div>

                    <div id="sp-blockers" class="sp-blockers" hidden></div>
                    <p id="sp-filter-empty" class="sp-empty sp-filter-empty" hidden data-i18n="sp.filter.empty">No tasks match these filters.</p>
                    <div id="sp-sprints" class="sp-sprints"></div>
                </div>

                <aside class="sp-filter-sidebar" id="sp-filter-sidebar" aria-label="Plan filters">
                    <div class="sp-filter-sidebar__head">
                        <h2 class="sp-filter-sidebar__title" data-i18n="sp.filter.sidebarTitle">Filters</h2>
                        <button
                            type="button"
                            class="tools-btn tools-btn--secondary tools-btn--small"
                            id="sp-filter-reset"
                            data-i18n="sp.filter.reset"
                            disabled
                        >Reset filters</button>
                    </div>
                    <div class="sp-filters sp-filters--plan" role="group" aria-label="Plan item filters">
                        <div class="sp-filter-logic" role="group" aria-label="Filter logic">
                            <span class="sp-filter-logic__label" data-i18n="sp.filter.logicLabel">Combine filters</span>
                            <label class="sp-check sp-check--inline">
                                <input type="radio" name="sp-filter-logic" id="sp-filter-logic-and" value="and">
                                <span data-i18n="sp.filter.logicAnd">AND</span>
                            </label>
                            <label class="sp-check sp-check--inline">
                                <input type="radio" name="sp-filter-logic" id="sp-filter-logic-or" value="or" checked>
                                <span data-i18n="sp.filter.logicOr">OR</span>
                            </label>
                        </div>
                        <label class="sp-check"><input type="checkbox" id="sp-filter-current-week"> <span data-i18n="sp.filter.currentWeek">Current plan week only</span></label>
                        <label class="sp-check"><input type="checkbox" id="sp-filter-hide-done"> <span data-i18n="sp.filter.hideDone">Hide completed</span></label>
                        <label class="sp-check"><input type="checkbox" id="sp-filter-open-only"> <span data-i18n="sp.filter.openOnly">Open items only</span></label>
                        <label class="sp-check"><input type="checkbox" id="sp-filter-blocked"> <span data-i18n="sp.filter.blocked">Blocked items</span></label>
                        <label class="sp-check"><input type="checkbox" id="sp-filter-my-tasks" checked> <span data-i18n="sp.filter.myTasks">My tasks</span></label>
                        <label class="sp-check"><input type="checkbox" id="sp-filter-unassigned" checked> <span data-i18n="sp.filter.unassigned">Unassigned</span></label>
                        <label class="sp-field sp-field--compact">
                            <span data-i18n="sp.filter.person">Person</span>
                            <select id="sp-filter-person" class="tools-input"></select>
                        </label>
                        <label class="sp-field sp-field--compact">
                            <span data-i18n="sp.filter.teamMembers">Team members</span>
                            <select id="sp-filter-team" class="tools-input"></select>
                        </label>
                        <label class="sp-field sp-field--compact">
                            <span data-i18n="sp.filter.status">Status</span>
                            <select id="sp-filter-status" class="tools-input">
                                <option value="" data-i18n="sp.filter.any">Any</option>
                                <option value="open" data-i18n="sp.status.open">Open</option>
                                <option value="in_progress" data-i18n="sp.status.inProgress">In progress</option>
                                <option value="on_hold" data-i18n="sp.status.onHold">On hold</option>
                                <option value="blocked" data-i18n="sp.status.blocked">Blocked</option>
                                <option value="completed" data-i18n="sp.status.completed">Completed</option>
                            </select>
                        </label>
                        <label class="sp-field sp-field--compact">
                            <span data-i18n="sp.filter.priority">Priority</span>
                            <select id="sp-filter-priority" class="tools-input">
                                <option value="" data-i18n="sp.filter.any">Any</option>
                                <option value="low" data-i18n="sp.priority.low">Low</option>
                                <option value="normal" data-i18n="sp.priority.normal">Normal</option>
                                <option value="high" data-i18n="sp.priority.high">High</option>
                                <option value="critical" data-i18n="sp.priority.critical">Critical</option>
                            </select>
                        </label>
                    </div>
                </aside>
            </div>
        </div>

        <dialog id="sp-sprint-dialog" class="sp-dialog">
            <form method="dialog" id="sp-sprint-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" data-i18n="sp.dialog.sprintTitle">Sprint</h2>
                <input type="hidden" id="sp-sprint-edit-id">
                <label class="sp-field"><span data-i18n="sp.field.titleDe">Title (DE)</span><input type="text" id="sp-sprint-title-de" class="tools-input" required></label>
                <label class="sp-field"><span data-i18n="sp.field.titleEn">Title (EN)</span><input type="text" id="sp-sprint-title-en" class="tools-input"></label>
                <label class="sp-field"><span data-i18n="sp.field.goalDe">Goal (DE)</span><textarea id="sp-sprint-goal-de" class="tools-input" rows="2" required></textarea></label>
                <label class="sp-field"><span data-i18n="sp.field.goalEn">Goal (EN)</span><textarea id="sp-sprint-goal-en" class="tools-input" rows="2"></textarea></label>
                <label class="sp-field"><span data-i18n="sp.field.position">Position</span><input type="number" id="sp-sprint-position" class="tools-input" min="1" step="1"></label>
                <label class="sp-check"><input type="checkbox" id="sp-sprint-notes-enabled" checked> <span data-i18n="sp.field.enableNotes">Enable notes</span></label>
                <label class="sp-field">
                    <span data-i18n="sp.field.sprintStories">Playbook stories (slugs)</span>
                    <textarea id="sp-sprint-stories" class="tools-input" rows="2" data-i18n-placeholder="sp.field.sprintStoriesHint" placeholder="one-slug-per-line"></textarea>
                </label>
                <label class="sp-field">
                    <span data-i18n="sp.field.sprintLinks">Links</span>
                    <textarea id="sp-sprint-links" class="tools-input" rows="3" data-i18n-placeholder="sp.field.linksHint" placeholder="Label | https://…"></textarea>
                </label>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" data-i18n="sp.action.save">Save</button>
                </div>
            </form>
        </dialog>

        <dialog id="sp-add-sprint-template-dialog" class="sp-dialog">
            <form method="dialog" id="sp-add-sprint-template-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" data-i18n="sp.dialog.addSprintTitle">Sprint from template</h2>
                <p class="sp-password-note" data-i18n="sp.dialog.addSprintLead">
                    Choose a template first, then the sprint to copy into this plan.
                </p>
                <label class="sp-field">
                    <span data-i18n="sp.field.pickTemplate">Template</span>
                    <select id="sp-add-sprint-template" class="tools-input" required></select>
                </label>
                <label class="sp-field">
                    <span data-i18n="sp.field.pickSprint">Sprint from template</span>
                    <select id="sp-add-sprint-week" class="tools-input" required></select>
                </label>
                <p id="sp-add-sprint-template-error" class="sp-field-error" hidden></p>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" data-i18n="sp.action.addSprint">Add sprint</button>
                </div>
            </form>
        </dialog>

        <dialog id="sp-item-dialog" class="sp-dialog">
            <form method="dialog" id="sp-item-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" id="sp-item-dialog-title" data-i18n="sp.dialog.itemTitle">Item</h2>
                <p class="sp-password-note" id="sp-item-plan-only-note" data-i18n="sp.dialog.planOnlyNote" hidden>
                    Plan fields only — help text and stories are edited in the template.
                </p>
                <input type="hidden" id="sp-item-sprint-id">
                <input type="hidden" id="sp-item-type">
                <input type="hidden" id="sp-item-id">
                <div id="sp-item-label-fields">
                    <label class="sp-field"><span data-i18n="sp.field.labelDe">Label (DE)</span><input type="text" id="sp-item-label-de" class="tools-input"></label>
                    <label class="sp-field"><span data-i18n="sp.field.labelEn">Label (EN)</span><input type="text" id="sp-item-label-en" class="tools-input"></label>
                </div>
                <input type="hidden" id="sp-item-assignee-type" value="person">
                <label class="sp-field"><span data-i18n="sp.field.assignee">Assignee</span><select id="sp-item-assignee-id" class="tools-input"></select></label>
                <label class="sp-field"><span data-i18n="sp.field.status">Status</span>
                    <select id="sp-item-status" class="tools-input">
                        <option value="open" data-i18n="sp.status.open">Open</option>
                        <option value="in_progress" data-i18n="sp.status.inProgress">In progress</option>
                        <option value="on_hold" data-i18n="sp.status.onHold">On hold</option>
                        <option value="blocked" data-i18n="sp.status.blocked">Blocked</option>
                        <option value="completed" data-i18n="sp.status.completed">Completed</option>
                    </select>
                </label>
                <label class="sp-field"><span data-i18n="sp.field.priority">Priority</span>
                    <select id="sp-item-priority" class="tools-input">
                        <option value="low" data-i18n="sp.priority.low">Low</option>
                        <option value="normal" selected data-i18n="sp.priority.normal">Normal</option>
                        <option value="high" data-i18n="sp.priority.high">High</option>
                        <option value="critical" data-i18n="sp.priority.critical">Critical</option>
                    </select>
                </label>
                <label class="sp-field"><span data-i18n="sp.field.dueDate">Due date</span><input type="date" id="sp-item-due" class="tools-input"></label>
                <div class="sp-time-fields">
                    <label class="sp-field sp-field--compact">
                        <span data-i18n="sp.field.plannedMinutes">Planned</span>
                        <select id="sp-item-planned" class="tools-input"></select>
                    </label>
                    <label class="sp-field sp-field--compact" id="sp-item-planned-custom-wrap" hidden>
                        <span data-i18n="sp.field.customMinutes">Custom (min)</span>
                        <input type="number" id="sp-item-planned-custom" class="tools-input" min="0" step="5" inputmode="numeric">
                    </label>
                    <label class="sp-field sp-field--compact">
                        <span data-i18n="sp.field.actualMinutes">Actual</span>
                        <select id="sp-item-actual" class="tools-input"></select>
                    </label>
                    <label class="sp-field sp-field--compact" id="sp-item-actual-custom-wrap" hidden>
                        <span data-i18n="sp.field.customMinutes">Custom (min)</span>
                        <input type="number" id="sp-item-actual-custom" class="tools-input" min="0" step="5" inputmode="numeric">
                    </label>
                </div>
                <p class="sp-field__hint" id="sp-item-last-time" hidden></p>
                <label class="sp-field" id="sp-item-blocker-field" hidden>
                    <span data-i18n="sp.field.blockerReason">Blocker reason</span>
                    <textarea id="sp-item-blocker-reason" class="tools-input" rows="2" maxlength="2000"></textarea>
                </label>
                <label class="sp-field">
                    <span data-i18n="sp.field.dependsOn">Depends on</span>
                    <select id="sp-item-depends-on" class="tools-input" multiple size="5"></select>
                    <span class="sp-field__hint" data-i18n="sp.field.dependsOnHint">Empty = parallel; selection = waits on predecessors (same sprint).</span>
                </label>
                <label class="sp-field"><span data-i18n="sp.field.note">Note</span><textarea id="sp-item-note" class="tools-input" rows="2" maxlength="4000"></textarea></label>
                <fieldset class="sp-field" id="sp-item-table-field">
                    <legend data-i18n="sp.field.table">Data table</legend>
                    <div id="sp-item-table-editor"></div>
                </fieldset>
                <fieldset class="sp-field sp-attachments-field">
                    <legend data-i18n="sp.field.attachments">Attachments</legend>
                    <ul id="sp-item-attachments-list" class="sp-attachments-list"></ul>
                    <div class="sp-attachments-add">
                        <label class="sp-field sp-field--compact">
                            <span data-i18n="sp.field.attachmentLinkLabel">Link label</span>
                            <input type="text" id="sp-item-att-label" class="tools-input" maxlength="200">
                        </label>
                        <label class="sp-field sp-field--compact">
                            <span data-i18n="sp.field.attachmentLinkUrl">URL</span>
                            <input type="url" id="sp-item-att-url" class="tools-input" maxlength="2000" placeholder="https://">
                        </label>
                        <button type="button" class="tools-btn tools-btn--secondary tools-btn--small" id="sp-item-att-add-link" data-i18n="sp.action.addAttachmentLink">Add link</button>
                        <label class="tools-btn tools-btn--secondary tools-btn--small sp-file-btn">
                            <span data-i18n="sp.action.addAttachmentFile">Attach file</span>
                            <input type="file" id="sp-item-att-file" accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.csv,image/*,application/pdf" hidden>
                        </label>
                    </div>
                </fieldset>
                <div class="sp-dialog__actions">
                    <button type="button" id="sp-item-delete" class="tools-btn tools-btn--danger" hidden data-i18n="sp.action.delete">Delete</button>
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" data-i18n="sp.action.save">Save</button>
                </div>
            </form>
        </dialog>

        <dialog id="sp-assign-dialog" class="sp-dialog">
            <form method="dialog" id="sp-assign-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" data-i18n="sp.dialog.assignTitle">Quick assign</h2>
                <p class="sp-password-note" id="sp-assign-item-label"></p>
                <input type="hidden" id="sp-assign-sprint-id">
                <input type="hidden" id="sp-assign-kind">
                <input type="hidden" id="sp-assign-key">
                <input type="hidden" id="sp-assign-custom" value="0">
                <input type="hidden" id="sp-assign-assignee-type" value="person">
                <label class="sp-field"><span data-i18n="sp.field.assignee">Person</span><select id="sp-assign-assignee-id" class="tools-input"></select></label>
                <p class="sp-field__hint" data-i18n="sp.assign.personOnlyHint">Tasks are assigned to people. Teams belong on the plan.</p>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" data-i18n="sp.action.assign">Assign</button>
                </div>
            </form>
        </dialog>

        <div id="sp-help-backdrop" class="sp-help-backdrop" hidden></div>
        <aside id="sp-help-panel" class="sp-help-panel" hidden aria-hidden="true">
            <div class="sp-help-panel__header">
                <h2 id="sp-help-panel-title" class="sp-help-panel__title" data-i18n="sp.help.title">Help</h2>
                <button type="button" id="sp-help-close" class="tools-btn tools-btn--secondary tools-btn--small" data-i18n="sp.help.close" aria-label="Close">Close</button>
            </div>
            <div id="sp-help-panel-body" class="sp-help-panel__body"></div>
        </aside>

        <div id="sp-status-report" class="sp-status-report" hidden aria-hidden="true">
            <div class="sp-status-report__header">
                <h2 class="sp-dialog__title" data-i18n="sp.report.title">Report</h2>
                <div class="sp-status-report__modes" role="group" aria-label="Report mode">
                    <button type="button" id="sp-report-mode-executive" class="tools-btn tools-btn--secondary tools-btn--small" data-report-mode="executive" data-i18n="sp.report.mode.executive">Executive (CEO)</button>
                    <button type="button" id="sp-report-mode-detailed" class="tools-btn tools-btn--secondary tools-btn--small" data-report-mode="detailed" data-i18n="sp.report.mode.detailed">Detailed</button>
                    <button type="button" id="sp-report-mode-time" class="tools-btn tools-btn--secondary tools-btn--small" data-report-mode="time" data-i18n="sp.report.mode.time">Time</button>
                    <button type="button" id="sp-report-mode-documentation" class="tools-btn tools-btn--secondary tools-btn--small" data-report-mode="documentation" data-i18n="sp.report.mode.documentation">Documentation</button>
                </div>
                <div class="sp-status-report__actions">
                    <button type="button" id="sp-status-report-print" class="tools-btn tools-btn--secondary tools-btn--small" data-i18n="sp.action.printReport">Print</button>
                    <button type="button" id="sp-status-report-close" class="tools-btn tools-btn--secondary tools-btn--small" data-i18n="sp.help.close" aria-label="Close">Close</button>
                </div>
            </div>
            <div id="sp-status-report-body" class="sp-status-report__body"></div>
        </div>

        <dialog id="sp-history-dialog" class="sp-dialog sp-history-dialog">
            <div class="sp-dialog__form">
                <div class="sp-history-dialog__header">
                    <h2 class="sp-dialog__title" data-i18n="sp.history.title">Plan history</h2>
                    <button type="button" id="sp-history-close" class="tools-btn tools-btn--secondary tools-btn--small" data-i18n="sp.help.close">Close</button>
                </div>
                <p class="sp-password-note" data-i18n="sp.history.lead">Recent changes with actor. Restore replaces the current plan with the state before that change.</p>
                <div id="sp-history-list" class="sp-history-list"></div>
                <div id="sp-history-detail" class="sp-history-detail" hidden></div>
            </div>
        </dialog>

        <div id="sp-toast" class="sp-toast" role="status" aria-live="polite" hidden></div>
        <p id="sp-save-status" class="sp-save-status" aria-live="polite"></p>
    </div>
@endsection
