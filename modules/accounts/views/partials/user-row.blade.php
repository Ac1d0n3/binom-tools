@php
    use App\Support\AccentColors;
    $teamLabels = [];
    foreach ($teams as $team) {
        $teamLabels[$team['id']] = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
    }
    $chip = $user['shortName'] ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user['displayName'] ?? '') ?: 'U', 0, 3));
    if (strlen($chip) === 1) {
        $chip .= 'X';
    }
    $color = AccentColors::normalize($user['colorToken'] ?? null);
    $iconSvg = \App\Support\AvatarIcons::svgMarkup($user['avatarIcon'] ?? '');
    $userTeams = array_values(array_filter(array_map(
        static fn ($id) => $teamLabels[$id] ?? null,
        $user['teamIds'] ?? []
    )));
@endphp
<div class="sp-list__row">
    <div class="sp-list__identity">
        <span
            class="sp-avatar sp-avatar--{{ $color }} sp-avatar--person{{ $iconSvg !== '' ? ' sp-avatar--icon' : '' }}{{ $iconSvg === '' && strlen($chip) >= 3 ? ' sp-avatar--trigram-3' : '' }}"
            style="{{ AccentColors::chipStyle($color) }}"
            aria-hidden="true"
        >
            @if ($iconSvg !== '')
                {!! $iconSvg !!}
            @else
                {{ $chip }}
            @endif
        </span>
        <div>
            <strong>{{ $user['displayName'] }}</strong>
            <span class="sp-list__meta">
                {{ $user['email'] }}
                @if (! empty($user['mustChangePassword']))
                    · <span data-i18n="accounts.pendingPasswordChange">Password change pending</span>
                @endif
                @if (! empty($user['pendingApproval']))
                    · <span data-i18n="accounts.pendingApproval">Awaiting approval</span>
                @elseif (! ($user['active'] ?? true))
                    · <span data-i18n="accounts.inactive">Inactive</span>
                @endif
                @if ($userTeams !== [])
                    · {{ implode(', ', array_slice($userTeams, 0, 3)) }}{{ count($userTeams) > 3 ? '…' : '' }}
                @endif
            </span>
        </div>
    </div>
    <div class="sp-list__actions" style="display:flex;gap:.5rem;flex-wrap:wrap">
        @if (! empty($pending))
            <form method="post" action="{{ locale_route('accounts.users.approve', ['userId' => $user['id']]) }}">
                @csrf
                <button type="submit" class="tools-btn tools-btn--primary tools-btn--small" data-i18n="accounts.approve">Approve</button>
            </form>
            <form method="post" action="{{ locale_route('accounts.users.reject', ['userId' => $user['id']]) }}" onsubmit="return confirm('Reject and delete this registration?');">
                @csrf
                <button type="submit" class="tools-btn tools-btn--secondary tools-btn--small" data-i18n="accounts.reject">Reject</button>
            </form>
        @else
            <a
                href="{{ locale_route('admin.users.edit', ['userId' => $user['id']]) }}"
                class="tools-btn tools-btn--secondary tools-btn--small"
                data-i18n="accounts.edit"
            >Edit</a>
        @endif
    </div>
</div>
