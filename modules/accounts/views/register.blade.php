@extends('foundations.layouts.tools')

@section('title', 'Register — ' . config('app.name'))

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="accounts.registerTitle">Create account</h1>
        <p class="tools-page-lead" data-i18n="accounts.registerLead">
            After registering, an administrator must approve your account before you can sign in.
        </p>

        @if ($errors->any())
            <div class="sp-field-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ locale_route('accounts.register.submit') }}" class="sp-lock-form" style="max-width:24rem">
            @csrf
            <label class="sp-field">
                <span data-i18n="accounts.email">Email</span>
                <input type="email" name="email" class="tools-input" value="{{ old('email') }}" required autocomplete="username">
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.displayName">Display name</span>
                <input type="text" name="displayName" class="tools-input" value="{{ old('displayName') }}" required autocomplete="name">
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.password">Password</span>
                <input type="password" name="password" class="tools-input" required autocomplete="new-password" minlength="8">
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.passwordConfirm">Confirm password</span>
                <input type="password" name="password_confirmation" class="tools-input" required autocomplete="new-password" minlength="8">
            </label>
            <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.registerSubmit">Register</button>
        </form>

        <p class="tools-page-lead" style="margin-top:1.25rem">
            <a href="{{ locale_route('accounts.login') }}" data-i18n="accounts.backToLogin">Back to sign in</a>
        </p>
    </div>
@endsection
