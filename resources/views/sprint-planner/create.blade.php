@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/sprint-planner.css',
        'resources/js/sprint-planner/create.js',
    ],
])

@section('title', 'Plan Creator — ' . config('app.name'))

@section('content')
    <div
        class="tools-content tools-content--wide sp-app"
        id="sp-app"
        data-sp-page="create"
        data-sp-templates="{{ json_encode($templatesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        data-sp-index-url="{{ locale_route('sprint-planner.index') }}"
        data-sp-templates-url="{{ locale_route('sprint-planner.templates') }}"
        @include('components.sprint-planner.accounts-attrs')
    >
        @include('components.sprint-planner.bootstrap-json')
        <x-sprint-planner.subnav active="templates" />

        <header class="sp-plan-header">
            <div class="sp-plan-header__main">
                <h1 class="tools-page-title" data-i18n="sp.creator.title">Plan Creator</h1>
                <p class="tools-page-lead" data-i18n="sp.creator.lead">
                    Build a plan with sprints, tasks, help text, external links and optional data tables.
                </p>
            </div>
            <div class="sp-plan-header__actions">
                <a href="{{ locale_route('sprint-planner.templates') }}" class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</a>
                <button type="button" class="tools-btn tools-btn--secondary" id="sp-creator-save-template" data-i18n="sp.creator.saveTemplate">Save as template</button>
                <button type="button" class="tools-btn tools-btn--primary" id="sp-creator-start" data-i18n="sp.creator.startPlan">Start plan</button>
            </div>
        </header>

        <form id="sp-creator-form" class="sp-creator">
            <section class="sp-creator__meta">
                <label class="sp-field"><span data-i18n="sp.field.titleDe">Title (DE)</span><input type="text" id="sp-creator-title-de" class="tools-input" required></label>
                <label class="sp-field"><span data-i18n="sp.field.titleEn">Title (EN)</span><input type="text" id="sp-creator-title-en" class="tools-input"></label>
                <label class="sp-field"><span data-i18n="sp.field.descriptionDe">Description (DE)</span><textarea id="sp-creator-desc-de" class="tools-input" rows="2"></textarea></label>
                <label class="sp-field"><span data-i18n="sp.field.descriptionEn">Description (EN)</span><textarea id="sp-creator-desc-en" class="tools-input" rows="2"></textarea></label>
                <label class="sp-field"><span data-i18n="sp.field.startDate">Start date</span><input type="date" id="sp-creator-start-date" class="tools-input" required></label>
            </section>

            <div class="sp-creator__sprints-head">
                <h2 class="sp-section__title" data-i18n="sp.creator.sprints">Sprints</h2>
                <button type="button" class="tools-btn tools-btn--primary tools-btn--small" id="sp-creator-add-sprint" data-i18n="sp.action.addSprint">Add sprint</button>
            </div>
            <div id="sp-creator-sprints" class="sp-creator__sprints"></div>
        </form>

        <div id="sp-toast" class="sp-toast" role="status" aria-live="polite" hidden></div>
    </div>
@endsection
