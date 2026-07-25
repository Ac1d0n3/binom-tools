@extends('layouts.tools')

@section('title', 'Login — ' . config('app.name'))

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="accounts.loginTitle">Sign in</h1>
        <p class="tools-page-lead" data-i18n="accounts.loginLead">Local accounts — passwords are stored hashed only.</p>

        @if (session('status') === 'registration-pending')
            <p class="sp-field-hint" role="status" data-i18n="accounts.flash.registrationPending">
                Registration received. An administrator must approve your account before you can sign in.
            </p>
        @endif

        @if ($errors->any())
            <div class="sp-field-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ locale_route('accounts.login.submit') }}" class="sp-lock-form" style="max-width:24rem">
            @csrf
            <label class="sp-field">
                <span data-i18n="accounts.email">Email</span>
                <input type="email" name="email" class="tools-input" value="{{ old('email') }}" required autocomplete="username">
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.password">Password</span>
                <input type="password" name="password" class="tools-input" required autocomplete="current-password">
            </label>
            <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.signIn">Sign in</button>
        </form>

        @if (! empty($registrationEnabled))
            <p class="tools-page-lead" style="margin-top:1.25rem">
                <a href="{{ locale_route('accounts.register') }}" data-i18n="accounts.createAccountLink">Create an account</a>
            </p>
        @endif
    </div>
@endsection
