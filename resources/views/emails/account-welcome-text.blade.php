Hi {{ $displayName }},

An account was created for you on {{ config('app.name') }}.

Login: {{ $loginUrl }}
Email: {{ $emailAddress }}
Temporary password: {{ $temporaryPassword }}
@if ($mustChangePassword)

Please sign in and change this password immediately in your account profile.
@endif

If you did not expect this email, contact your administrator.
