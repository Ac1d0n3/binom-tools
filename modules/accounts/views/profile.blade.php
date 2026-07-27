@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/sprint-planner/css/sprint-planner.css'],
])

@section('title', 'Account — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <h1 class="tools-page-title" data-i18n="accounts.profileTitle">Account</h1>
        <p class="tools-page-lead">{{ $account['email'] }}</p>

        <x-accounts.flash :status-map="[
            'profile-updated' => 'accounts.saved',
            'must-change-password' => 'accounts.flash.mustChangePassword',
        ]" />

        @if (! empty($mustChangePassword))
            <p class="sp-password-note" role="status" data-i18n="accounts.mustChangePasswordLead">
                Please set a new password before using the tools.
            </p>
        @endif

        <form method="post" action="{{ locale_route('accounts.profile.update') }}" class="sp-lock-form" style="max-width:40rem">
            @csrf
            @method('PUT')

            <label class="sp-field">
                <span data-i18n="accounts.displayName">Display name</span>
                <input type="text" name="displayName" class="tools-input" value="{{ old('displayName', $account['displayName']) }}" required>
            </label>

            @if (! empty($profileAvatarEnabled))
                <label class="sp-field">
                    <span data-i18n="accounts.shortName">Trigram</span>
                    <input
                        type="text"
                        name="shortName"
                        class="tools-input"
                        minlength="2"
                        maxlength="3"
                        pattern="[A-Za-z]{2,3}"
                        title="2–3 letters"
                        value="{{ old('shortName', $account['shortName'] ?? '') }}"
                    >
                    <span class="sp-field-hint" data-i18n="accounts.shortNameHint">2–3 letters (A–Z), optional.</span>
                </label>

                <x-accounts.icon-picker :selected="old('avatarIcon', $account['avatarIcon'] ?? '')" />

                <x-accounts.color-swatches :selected="old('colorToken', $account['colorToken'] ?? 'accent-1')" />
            @endif

            <label class="sp-field">
                <span data-i18n="{{ ! empty($mustChangePassword) ? 'accounts.temporaryPassword' : 'accounts.currentPassword' }}">
                    {{ ! empty($mustChangePassword) ? 'Temporary password' : 'Current password (to change password)' }}
                </span>
                <input
                    type="password"
                    name="current_password"
                    class="tools-input"
                    autocomplete="current-password"
                    @if (! empty($mustChangePassword)) required @endif
                >
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.newPassword">New password</span>
                <input
                    type="password"
                    name="password"
                    class="tools-input"
                    autocomplete="new-password"
                    minlength="8"
                    @if (! empty($mustChangePassword)) required @endif
                >
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.confirmPassword">Confirm new password</span>
                <input
                    type="password"
                    name="password_confirmation"
                    class="tools-input"
                    autocomplete="new-password"
                    minlength="8"
                    @if (! empty($mustChangePassword)) required @endif
                >
            </label>

            <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.save">Save</button>
        </form>

        @php
            $quizResults = is_array($glossaryQuizResults ?? null) ? $glossaryQuizResults : null;
            $quizAttempts = (int) ($quizResults['attemptCount'] ?? 0);
        @endphp
        @if ($quizAttempts > 0)
            <section class="glossary-quiz-profile" aria-labelledby="glossary-quiz-profile-title">
                <h2 id="glossary-quiz-profile-title" class="glossary-quiz-profile__title" data-i18n="glossary.quiz.profileTitle">
                    Buzzword Quiz
                </h2>
                <ul class="glossary-quiz-profile__stats">
                    <li>
                        <span data-i18n="glossary.quiz.bestScore">Best score</span>:
                        <strong>{{ (int) ($quizResults['bestScore'] ?? 0) }} / {{ (int) ($quizResults['bestTotal'] ?? 0) }}</strong>
                    </li>
                    <li>
                        <span data-i18n="glossary.quiz.attemptCount">Attempts</span>:
                        <strong>{{ $quizAttempts }}</strong>
                    </li>
                    @php
                        $last = is_array($quizResults['attempts'] ?? null) && $quizResults['attempts'] !== []
                            ? $quizResults['attempts'][array_key_last($quizResults['attempts'])]
                            : null;
                    @endphp
                    @if (is_array($last))
                        <li>
                            <span data-i18n="glossary.quiz.lastAttempt">Last attempt</span>:
                            <strong>{{ (int) ($last['score'] ?? 0) }} / {{ (int) ($last['total'] ?? 0) }}</strong>
                            @if (! empty($last['at']))
                                <span class="glossary-quiz-profile__when">({{ $last['at'] }})</span>
                            @endif
                        </li>
                    @endif
                </ul>
                <p>
                    <a href="{{ locale_route('glossary.index', ['quiz' => 1]) }}" class="tools-btn" data-i18n="glossary.quiz.cta">Buzzword Quiz</a>
                </p>
            </section>
        @endif
    </div>
@endsection
