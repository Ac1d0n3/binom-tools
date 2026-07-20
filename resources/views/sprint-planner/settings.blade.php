@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/sprint-planner.css',
        'resources/js/sprint-planner/settings.js',
    ],
])

@section('title', 'Sprint Plan Settings — ' . config('app.name'))

@section('content')
    <div
        class="tools-content tools-content--wide sp-app"
        id="sp-app"
        data-sp-page="settings"
        data-sp-instance-id="{{ $instanceId }}"
        data-sp-templates="{{ json_encode($templatesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        data-sp-show-url="{{ locale_route('sprint-planner.show', ['instanceId' => $instanceId]) }}"
        data-sp-index-url="{{ locale_route('sprint-planner.index') }}"
        @include('components.sprint-planner.accounts-attrs')
    >
        @include('components.sprint-planner.bootstrap-json')
        <x-sprint-planner.subnav active="plans" />

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

        <div id="sp-settings-view" hidden>
            <h1 class="tools-page-title" data-i18n="sp.settingsTitle">Plan settings</h1>
            <p class="tools-page-lead" id="sp-settings-plan-title"></p>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.field.startDate">Start date</h2>
                <label class="sp-field">
                    <span data-i18n="sp.field.startDate">Start date</span>
                    <input type="date" id="sp-settings-start-date" class="tools-input" required>
                </label>
                <button type="button" class="tools-btn tools-btn--primary" id="sp-settings-start-date-save" data-i18n="sp.action.save">Save</button>
            </section>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.field.teams">Teams</h2>
                <fieldset class="sp-field">
                    <legend class="visually-hidden" data-i18n="sp.field.teams">Teams</legend>
                    <div id="sp-plan-teams" class="sp-checkbox-list"></div>
                </fieldset>
                <button type="button" class="tools-btn tools-btn--primary" id="sp-plan-teams-save" data-i18n="sp.action.save">Save</button>
            </section>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.section.sharing">Sharing</h2>
                <p class="sp-password-note" data-i18n="sp.sharing.note">Share this plan with users or teams on this instance (accounts mode).</p>
                <div id="sp-sharing-panel" class="sp-lock-form" hidden>
                    <fieldset class="sp-field">
                        <legend data-i18n="sp.sharing.users">Viewer users</legend>
                        <div id="sp-share-users" class="sp-checkbox-list"></div>
                    </fieldset>
                    <fieldset class="sp-field">
                        <legend data-i18n="sp.sharing.teams">Viewer teams</legend>
                        <div id="sp-share-teams" class="sp-checkbox-list"></div>
                    </fieldset>
                    <button type="button" class="tools-btn tools-btn--primary" id="sp-sharing-save" data-i18n="sp.sharing.save">Save sharing</button>
                </div>
                <p id="sp-sharing-local-only" class="sp-password-note" data-i18n="sp.sharing.localOnly">Sharing requires accounts mode.</p>
            </section>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.section.password">Password protection</h2>
                <p class="sp-password-note" data-i18n="sp.password.note">Local soft lock in this browser only — not real access control.</p>
                <p id="sp-password-status" class="sp-card__meta"></p>
                <form id="sp-password-form" class="sp-lock-form">
                    <label class="sp-field" id="sp-password-current-wrap" hidden>
                        <span data-i18n="sp.password.current">Current password</span>
                        <input type="password" id="sp-password-current" class="tools-input" autocomplete="current-password">
                    </label>
                    <label class="sp-field">
                        <span data-i18n="sp.password.new">New password</span>
                        <input type="password" id="sp-password-new" class="tools-input" autocomplete="new-password" minlength="4" required>
                    </label>
                    <label class="sp-field">
                        <span data-i18n="sp.password.confirm">Confirm password</span>
                        <input type="password" id="sp-password-confirm" class="tools-input" autocomplete="new-password" minlength="4" required>
                    </label>
                    <p id="sp-password-error" class="sp-field-error" hidden></p>
                    <div class="sp-action-row">
                        <button type="submit" class="tools-btn tools-btn--primary" id="sp-password-save" data-i18n="sp.password.set">Set password</button>
                        <button type="button" class="tools-btn tools-btn--secondary" id="sp-password-remove" hidden data-i18n="sp.password.remove">Remove password</button>
                        <button type="button" class="tools-btn tools-btn--secondary" id="sp-password-lock-session" hidden data-i18n="sp.password.lockSession">Lock again</button>
                    </div>
                </form>
            </section>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.section.template">Template</h2>
                <p class="sp-password-note" data-i18n="sp.template.switchNote">Switch to a compatible stack variant (same sprint structure). Progress is kept.</p>
                <label class="sp-field">
                    <span data-i18n="sp.field.templateVariant">Template variant</span>
                    <select id="sp-switch-template" class="tools-input"></select>
                </label>
                <div class="sp-action-row">
                    <button type="button" class="tools-btn tools-btn--primary" id="sp-switch-template-save" data-i18n="sp.action.switchTemplate">Switch template</button>
                </div>
            </section>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.section.planActions">Plan actions</h2>
                <div class="sp-action-row">
                    <button type="button" class="tools-btn tools-btn--secondary" id="sp-export-instance" data-i18n="sp.action.exportPlan">Export plan</button>
                    <button type="button" class="tools-btn tools-btn--secondary" id="sp-duplicate-plan" data-i18n="sp.action.duplicatePlan">Duplicate plan</button>
                    <button type="button" class="tools-btn tools-btn--secondary" id="sp-archive-plan" data-i18n="sp.action.archivePlan">Archive plan</button>
                    <button type="button" class="tools-btn tools-btn--secondary" id="sp-reset-plan" data-i18n="sp.action.resetPlan">Reset plan</button>
                    <button type="button" class="tools-btn tools-btn--danger" id="sp-delete-plan" data-i18n="sp.action.deletePlan">Delete plan</button>
                </div>
            </section>

            <section class="sp-section">
                <h2 class="sp-section__title" data-i18n="sp.section.workspaceActions">Workspace</h2>
                <div class="sp-action-row">
                    <button type="button" class="tools-btn tools-btn--secondary" id="sp-export-workspace" data-i18n="sp.action.exportWorkspace">Export workspace</button>
                    <button type="button" class="tools-btn tools-btn--secondary" id="sp-import-workspace" data-i18n="sp.action.import">Import</button>
                    <button type="button" class="tools-btn tools-btn--danger" id="sp-clear-workspace" data-i18n="sp.action.clearWorkspace">Clear local data</button>
                    <input type="file" id="sp-import-file" accept="application/json,.json" hidden>
                </div>
            </section>

            <p><a id="sp-back-to-plan" href="{{ locale_route('sprint-planner.show', ['instanceId' => $instanceId]) }}" class="tools-btn tools-btn--primary" data-i18n="sp.action.backToPlan">Back to plan</a></p>
        </div>

        <div id="sp-toast" class="sp-toast" role="status" aria-live="polite" hidden></div>
    </div>
@endsection
