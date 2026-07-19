@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/sprint-planner.css',
        'resources/js/sprint-planner/index.js',
    ],
])

@section('title', 'Sprint Planner — ' . config('app.name'))

@php
    $isDe = current_locale() === 'de';
    $showDemoBanner = ! empty($accountsEnabled) && ! empty($demoMode);
    if ($showDemoBanner) {
        // Lead lives inside the guest demo banner (no separate lead + banner).
        $pageLeadText = null;
        $demoBannerLead = $isDe
            ? 'Vorlagen lokal in dieser Browser-Session ausprobieren. Zum Speichern und Teilen brauchst du lokale Login-Daten.'
            : 'Try templates locally in this browser session. Sign in with local login credentials to save and share plans.';
    } elseif (! empty($accountsEnabled)) {
        // Signed in: plain lead, no demo banner.
        $pageLeadText = $isDe
            ? 'Gespeicherte Pläne synchronisieren auf diesen Server.'
            : 'Saved plans sync to this server.';
        $demoBannerLead = null;
    } else {
        $pageLeadText = $isDe
            ? 'Lokale Sprint-Pläne aus Markdown-Vorlagen — Fortschritt bleibt im Browser. Optional mit Plan-Passwort schützen.'
            : 'Run local sprint plans from Markdown templates — progress stays in your browser. Optionally protect with a plan password.';
        $demoBannerLead = null;
    }
@endphp

@section('content')
    <div
        class="tools-content tools-content--wide sp-app"
        id="sp-app"
        data-sp-page="index"
        data-sp-templates="{{ json_encode($templatesJson ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}"
        data-sp-show-base="{{ locale_route('sprint-planner.index') }}"
        @include('components.sprint-planner.accounts-attrs')
    >
        @include('components.sprint-planner.bootstrap-json')
        <h1 class="tools-page-title">Sprint Planner</h1>
        @if ($pageLeadText)
            <p class="tools-page-lead">{{ $pageLeadText }}</p>
        @endif

        @if ($showDemoBanner)
            <aside class="sp-demo-banner" id="sp-demo-banner" aria-label="{{ $isDe ? 'Demo-Optionen' : 'Demo options' }}">
                <div class="sp-demo-banner__top">
                    <p class="sp-demo-banner__eyebrow">
                        <i class="fa-solid fa-flask" aria-hidden="true"></i>
                        <span>{{ $isDe ? 'Demo-Modus' : 'Demo mode' }}</span>
                    </p>
                    @if (! empty($loginUrl))
                        <a href="{{ $loginUrl }}" class="tools-btn tools-btn--primary tools-btn--small">{{ $isDe ? 'Anmelden' : 'Sign in' }}</a>
                    @endif
                </div>
                <p class="sp-demo-banner__lead">{{ $demoBannerLead }}</p>
                <ul class="sp-demo-banner__options">
                    <li>
                        <strong>{{ $isDe ? 'Session-Demo' : 'Session demo' }}</strong>
                        <span>{{ $isDe
                            ? 'Ohne Login starten — Fortschritt nur in dieser Browser-Session.'
                            : 'Start without login — progress stays in this browser session only.' }}</span>
                    </li>
                    <li>
                        <strong>{{ $isDe ? 'Plan-Passwort' : 'Plan password' }}</strong>
                        <span>{{ $isDe
                            ? 'Auch ohne Login: Soft-Lock in den Plan-Einstellungen (nur lokal, keine echte Zugriffskontrolle).'
                            : 'Also without login: soft-lock in plan settings (local only, not real access control).' }}</span>
                    </li>
                    <li class="sp-demo-banner__option--signin">
                        <strong>{{ $isDe ? 'Mit Login (nicht in dieser Demo)' : 'With sign-in (not in this demo)' }}</strong>
                        <span>{{ $isDe
                            ? 'So sähe der volle Modus aus: eigene User-Pläne und Team-Pläne speichern, teilen und serverseitig synchronisieren. Hier als Gast-Demo wird das nicht angeboten — dafür brauchst du lokale Login-Daten.'
                            : 'This is what the full mode looks like: save and share personal user plans and team plans, synced on the server. That is not offered in this guest demo — local login credentials are required.' }}</span>
                    </li>
                </ul>
                <p class="sp-demo-banner__note">
                    {{ $isDe
                        ? 'Kurz: In der Demo nur Session + optionales Plan-Passwort. Teams- & User-Pläne erst nach Anmeldung.'
                        : 'In short: this demo offers session + optional plan password only. Teams & user plans unlock after sign-in.' }}
                </p>
            </aside>
        @endif

        <x-sprint-planner.subnav active="plans" />

        <div class="sp-toolbar">
            <div class="sp-filters" role="group" aria-label="Plan filters">
                <label class="sp-filter">
                    <span class="visually-hidden" data-i18n="sp.filter.label">Filter</span>
                    <select id="sp-overview-filter" class="tools-input" data-i18n-aria="sp.filter.label">
                        <option value="all" data-i18n="sp.filter.all">All</option>
                        <option value="mine" data-i18n="sp.filter.mine">My plans</option>
                        <option value="team" data-i18n="sp.filter.team">Team plans</option>
                        <option value="completed" data-i18n="sp.filter.completed">Completed</option>
                        <option value="archived" data-i18n="sp.filter.archived">Archived</option>
                    </select>
                </label>
            </div>
            <div class="sp-toolbar__actions">
                <button type="button" class="tools-btn tools-btn--secondary" id="sp-export-workspace" data-i18n="sp.action.exportWorkspace">Export workspace</button>
                <button type="button" class="tools-btn tools-btn--secondary" id="sp-import-workspace" data-i18n="sp.action.import">Import</button>
                <input type="file" id="sp-import-file" accept="application/json,.json" hidden>
            </div>
        </div>

        <section class="sp-section" aria-labelledby="sp-active-heading">
            <h2 id="sp-active-heading" class="sp-section__title" data-i18n="sp.section.activePlans">Active plans</h2>
            <div id="sp-active-plans" class="sp-card-grid"></div>
            <p id="sp-active-empty" class="sp-empty" hidden data-i18n="sp.empty.activePlans">No active plans yet. Start one from a template.</p>
        </section>

        <section class="sp-section" aria-labelledby="sp-templates-heading">
            <h2 id="sp-templates-heading" class="sp-section__title" data-i18n="sp.section.templates">Templates</h2>
            <div id="sp-template-cards" class="sp-card-grid"></div>
        </section>

        <section class="sp-section" aria-labelledby="sp-archive-heading">
            <h2 id="sp-archive-heading" class="sp-section__title" data-i18n="sp.section.archive">Archive</h2>
            <div id="sp-archived-plans" class="sp-card-grid"></div>
            <p id="sp-archive-empty" class="sp-empty" hidden data-i18n="sp.empty.archive">No archived plans.</p>
        </section>

        <dialog id="sp-start-dialog" class="sp-dialog">
            <form method="dialog" id="sp-start-form" class="sp-dialog__form">
                <h2 class="sp-dialog__title" data-i18n="sp.dialog.startTitle">Start plan</h2>
                <input type="hidden" id="sp-start-slug" name="slug">
                <label class="sp-field">
                    <span data-i18n="sp.field.startDate">Start date</span>
                    <input type="date" id="sp-start-date" class="tools-input" required>
                </label>
                <fieldset class="sp-field">
                    <legend data-i18n="sp.field.teams">Teams</legend>
                    <div id="sp-start-teams" class="sp-checkbox-list"></div>
                </fieldset>
                <fieldset class="sp-field">
                    <legend data-i18n="sp.field.participants">Participants</legend>
                    <div id="sp-start-participants" class="sp-checkbox-list"></div>
                </fieldset>
                <div class="sp-dialog__actions">
                    <button type="submit" value="cancel" formnovalidate class="tools-btn tools-btn--secondary" data-i18n="sp.action.cancel">Cancel</button>
                    <button type="submit" value="demo" class="tools-btn tools-btn--secondary" id="sp-start-demo" data-i18n="sp.action.startDemo">Start demo (session only)</button>
                    <button type="submit" value="confirm" class="tools-btn tools-btn--primary" id="sp-start-save" data-i18n="sp.action.start">Start</button>
                </div>
            </form>
        </dialog>

        <div id="sp-toast" class="sp-toast" role="status" aria-live="polite" hidden></div>
    </div>
@endsection
