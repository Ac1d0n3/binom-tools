@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/sprint-planner.css',
        'resources/js/sprint-planner/people.js',
    ],
])

@section('title', 'Sprint Planner People — ' . config('app.name'))

@php
    $accountsOn = ! empty($accountsEnabled);
    $canManageUsers = $accountsOn && ! empty($accountUser['canManageUsers']);
    $canManageTeams = $accountsOn && ! empty($accountUser['canManageTeams']);
@endphp

@section('content')
    <div
        class="tools-content tools-content--wide sp-app"
        id="sp-app"
        data-sp-page="people"
        data-sp-templates="{{ json_encode($templatesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        @if ($canManageUsers)
            data-user-edit-url="{{ locale_route('accounts.users.edit', ['userId' => '__ID__']) }}"
        @endif
        @if ($canManageTeams)
            data-team-edit-url="{{ locale_route('accounts.teams.edit', ['teamId' => '__ID__']) }}"
        @endif
        @include('components.sprint-planner.accounts-attrs')
    >
        @include('components.sprint-planner.bootstrap-json')

        <header class="sp-page-header">
            <h1 class="tools-page-title" data-i18n="sp.peopleTitle">Teams &amp; people</h1>
            <p class="tools-page-lead" data-i18n="{{ $accountsOn ? 'sp.peopleLeadAccounts' : 'sp.peopleLead' }}">
                @if ($accountsOn)
                    People and teams come from shared accounts. Manage them under Account when you have permission.
                @else
                    Local profiles only. Selecting an active person filters “My tasks” — it is not authentication.
                @endif
            </p>
            <x-sprint-planner.subnav active="people" />
        </header>

        <section class="sp-section sp-section--compact" aria-labelledby="sp-active-person-heading">
            <div class="sp-context-bar">
                <h2 id="sp-active-person-heading" class="sp-context-bar__title" data-i18n="sp.section.activePerson">Working as</h2>
                <div class="sp-context-bar__fields">
                    <label class="sp-field sp-field--inline">
                        <span class="visually-hidden" data-i18n="sp.field.activePerson">Active person</span>
                        <select id="sp-active-person" class="tools-input"></select>
                    </label>
                    <label class="sp-field sp-field--inline">
                        <span data-i18n="sp.field.defaultTeam">Default team</span>
                        <select id="sp-default-team" class="tools-input"></select>
                    </label>
                </div>
            </div>
        </section>

        <div class="sp-split">
            <section class="sp-section" aria-labelledby="sp-people-heading">
                <div class="sp-section__header">
                    <h2 id="sp-people-heading" class="sp-section__title" data-i18n="sp.section.people">People</h2>
                    <div class="sp-section__actions">
                        @if ($canManageUsers)
                            <a
                                href="{{ locale_route('accounts.users') }}"
                                class="tools-btn tools-btn--secondary tools-btn--small"
                                data-i18n="accounts.manageUsers"
                            >Manage users</a>
                        @elseif (! $accountsOn)
                            <button type="button" class="tools-btn tools-btn--primary tools-btn--small" id="sp-add-person" data-i18n="sp.action.addPerson">Add person</button>
                        @endif
                    </div>
                </div>
                <div id="sp-people-list" class="sp-list"></div>
            </section>

            <section class="sp-section" aria-labelledby="sp-teams-heading">
                <div class="sp-section__header">
                    <h2 id="sp-teams-heading" class="sp-section__title" data-i18n="sp.section.teams">Teams</h2>
                    <div class="sp-section__actions">
                        @if ($canManageTeams)
                            <a
                                href="{{ locale_route('accounts.teams') }}"
                                class="tools-btn tools-btn--secondary tools-btn--small"
                                data-i18n="accounts.manageTeams"
                            >Manage teams</a>
                        @elseif (! $accountsOn)
                            <button type="button" class="tools-btn tools-btn--primary tools-btn--small" id="sp-add-team" data-i18n="sp.action.addTeam">Add team</button>
                        @endif
                    </div>
                </div>
                <div id="sp-teams-list" class="sp-list"></div>
            </section>
        </div>

        @unless ($accountsOn)
            <div class="sp-toolbar sp-toolbar--start">
                <button type="button" class="tools-btn tools-btn--secondary" id="sp-export-workspace" data-i18n="sp.action.exportWorkspace">Export workspace</button>
                <button type="button" class="tools-btn tools-btn--secondary" id="sp-import-workspace" data-i18n="sp.action.import">Import</button>
                <input type="file" id="sp-import-file" accept="application/json,.json" hidden>
            </div>
        @endunless

        <dialog id="sp-person-dialog" class="sp-dialog">
            <form method="dialog" id="sp-person-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" id="sp-person-dialog-title" data-i18n="sp.dialog.personTitle">Person</h2>
                <input type="hidden" id="sp-person-id">
                <label class="sp-field"><span data-i18n="sp.field.displayName">Display name</span><input type="text" id="sp-person-display-name" class="tools-input" required></label>
                <label class="sp-field"><span data-i18n="sp.field.shortName">Trigram</span><input type="text" id="sp-person-short-name" class="tools-input" maxlength="3"></label>
                <label class="sp-field"><span data-i18n="sp.field.emailOptional">Email (optional)</span><input type="email" id="sp-person-email" class="tools-input"></label>
                <label class="sp-field"><span data-i18n="sp.field.role">Role</span><input type="text" id="sp-person-role" class="tools-input"></label>
                <fieldset class="sp-field">
                    <legend data-i18n="sp.field.color">Color</legend>
                    <div id="sp-person-color" class="sp-color-swatches" role="radiogroup"></div>
                </fieldset>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" data-i18n="sp.action.save">Save</button>
                </div>
            </form>
        </dialog>

        <dialog id="sp-team-dialog" class="sp-dialog">
            <form method="dialog" id="sp-team-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" id="sp-team-dialog-title" data-i18n="sp.dialog.teamTitle">Team</h2>
                <input type="hidden" id="sp-team-id">
                <label class="sp-field"><span data-i18n="sp.field.teamNameDe">Name (DE)</span><input type="text" id="sp-team-name-de" class="tools-input" required></label>
                <label class="sp-field"><span data-i18n="sp.field.teamNameEn">Name (EN)</span><input type="text" id="sp-team-name-en" class="tools-input"></label>
                <label class="sp-field"><span data-i18n="sp.field.shortName">Trigram</span><input type="text" id="sp-team-short-name" class="tools-input" maxlength="3"></label>
                <label class="sp-field"><span data-i18n="sp.field.teamDescDe">Description (DE)</span><textarea id="sp-team-desc-de" class="tools-input" rows="2"></textarea></label>
                <label class="sp-field"><span data-i18n="sp.field.teamDescEn">Description (EN)</span><textarea id="sp-team-desc-en" class="tools-input" rows="2"></textarea></label>
                <fieldset class="sp-field">
                    <legend data-i18n="sp.field.color">Color</legend>
                    <div id="sp-team-color" class="sp-color-swatches" role="radiogroup"></div>
                </fieldset>
                <fieldset class="sp-field">
                    <legend data-i18n="sp.field.members">Members</legend>
                    <div id="sp-team-members" class="sp-checkbox-list"></div>
                </fieldset>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" data-i18n="sp.action.save">Save</button>
                </div>
            </form>
        </dialog>

        <div id="sp-toast" class="sp-toast" role="status" aria-live="polite" hidden></div>
    </div>
@endsection
