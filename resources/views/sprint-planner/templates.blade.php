@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/sprint-planner.css',
        'resources/js/sprint-planner/templates.js',
    ],
])

@section('title', 'Sprint Planner Templates — ' . config('app.name'))

@section('content')
    <div
        class="tools-content tools-content--wide sp-app"
        id="sp-app"
        data-sp-page="templates"
        data-sp-templates="{{ json_encode($templatesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        data-sp-show-base="{{ locale_route('sprint-planner.index') }}"
        @include('components.sprint-planner.accounts-attrs')
    >
        @include('components.sprint-planner.bootstrap-json')
        <h1 class="tools-page-title" data-i18n="sp.templatesTitle">Plan templates</h1>
        <p class="tools-page-lead" data-i18n="sp.templatesLead">
            Markdown templates define reusable sprint structures. Starting a plan creates a local instance.
        </p>

        <x-sprint-planner.subnav active="templates" />

        <div class="sp-action-row sp-templates-toolbar">
            @if (empty($accountsEnabled) || ! empty($accountUser))
                <a href="{{ locale_route('sprint-planner.create') }}" class="tools-btn tools-btn--primary" data-i18n="sp.creator.new">New plan / template</a>
            @elseif (! empty($loginUrl))
                <a href="{{ $loginUrl }}" class="tools-btn tools-btn--secondary" data-i18n="sp.creator.loginToCreate">Sign in to create templates</a>
            @endif
        </div>

        <div id="sp-template-cards" class="sp-card-grid"></div>
        <p id="sp-templates-empty" class="sp-empty" hidden data-i18n="sp.empty.templates">No templates available.</p>

        <dialog id="sp-start-dialog" class="sp-dialog">
            <form method="dialog" id="sp-start-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" data-i18n="sp.dialog.startTitle">Start plan</h2>
                <input type="hidden" id="sp-start-slug" name="slug">
                <label class="sp-field">
                    <span data-i18n="sp.field.startDate">Start date</span>
                    <input type="date" id="sp-start-date" class="tools-input" required>
                </label>
                <label class="sp-field">
                    <span data-i18n="sp.field.team">Team</span>
                    <select id="sp-start-team" class="tools-input"></select>
                </label>
                <fieldset class="sp-field">
                    <legend data-i18n="sp.field.participants">Participants</legend>
                    <div id="sp-start-participants" class="sp-checkbox-list"></div>
                </fieldset>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="demo" class="tools-btn tools-btn--secondary" id="sp-start-demo" data-i18n="sp.action.startDemo">Start demo (session only)</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" id="sp-start-save" data-i18n="sp.action.start">Start</button>
                </div>
            </form>
        </dialog>

        <div id="sp-toast" class="sp-toast" role="status" aria-live="polite" hidden></div>
    </div>
@endsection
