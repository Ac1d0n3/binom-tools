<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} invitation</title>
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #111;">
    <p>Hi {{ $displayName }},</p>
    <p>An account was created for you on <strong>{{ config('app.name') }}</strong>.</p>
    <p>
        <strong>Login:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a><br>
        <strong>Email:</strong> {{ $emailAddress }}<br>
        <strong>Temporary password:</strong> <code style="font-size:1.05em">{{ $temporaryPassword }}</code>
    </p>
    @if ($mustChangePassword)
        <p>Please sign in and <strong>change this password immediately</strong> in your account profile.</p>
    @endif
    <p style="color:#555;font-size:0.9em">If you did not expect this email, contact your administrator.</p>
</body>
</html>
