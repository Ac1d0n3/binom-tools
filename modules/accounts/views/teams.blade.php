@extends('admin::layouts.shell')

@php
    use App\Support\AccentColors;
@endphp

@section('title', 'Teams — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search teams…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <a href="{{ locale_route('admin.teams.create') }}" class="tools-btn tools-btn--primary" data-i18n="accounts.addTeam">Add team</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'team-created' => 'accounts.flash.teamCreated',
            'team-updated' => 'accounts.flash.teamUpdated',
            'team-deleted' => 'accounts.flash.teamDeleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-i18n="accounts.noTeams">No matches.</p>

        <section class="sp-section">
            <div class="sp-list">
                @forelse ($teams as $team)
                    @php
                        $label = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
                        $icon = \App\Support\AvatarIcons::normalize($team['avatarIcon'] ?? '');
                        $short = trim((string) ($team['shortName'] ?? ''));
                        if ($short === '' && $icon === '') {
                            $short = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $label) ?: 'TM', 0, 3));
                            if (strlen($short) === 1) {
                                $short = str_pad($short, 2, 'X');
                            }
                        }
                        $showLabel = $short !== '';
                        $iconOnly = $icon !== '' && ! $showLabel;
                        $iconLabel = $icon !== '' && $showLabel;
                        $color = AccentColors::normalize($team['colorToken'] ?? null);
                        $roles = is_array($team['memberRoles'] ?? null) ? $team['memberRoles'] : [];
                        $managerCount = count(array_filter($roles, static fn ($r) => $r === 'manager'));
                        $ceoCount = count(array_filter($roles, static fn ($r) => $r === 'ceo'));
                        $memberCount = count($team['memberIds'] ?? []);
                        $avatarClasses = [
                            'sp-avatar',
                            'sp-avatar--'.$color,
                            'sp-avatar--team',
                        ];
                        if ($icon !== '') {
                            $avatarClasses[] = 'sp-avatar--icon';
                            $avatarClasses[] = $iconOnly ? 'sp-avatar--icon-only' : 'sp-avatar--icon-label';
                        } elseif (strlen($short) >= 3) {
                            $avatarClasses[] = 'sp-avatar--trigram-3';
                        }
                        $searchText = strtolower(trim(implode(' ', array_filter([
                            $label,
                            $team['id'] ?? '',
                            $team['name']['de'] ?? '',
                            $team['name']['en'] ?? '',
                            $short,
                        ]))));
                    @endphp
                    <div class="sp-list__row" data-overview-item data-search-text="{{ $searchText }}">
                        <div class="sp-list__identity">
                            <span
                                class="{{ implode(' ', $avatarClasses) }}"
                                style="{{ AccentColors::chipStyle($color) }}"
                                aria-hidden="true"
                            >
                                @if ($icon !== '')
                                    <span
                                        class="sp-avatar-icon-mask"
                                        style="mask-image:url('{{ asset('icons/avatar/'.$icon.'.svg') }}');-webkit-mask-image:url('{{ asset('icons/avatar/'.$icon.'.svg') }}')"
                                    ></span>
                                @endif
                                @if ($showLabel)
                                    @if ($icon !== '')
                                        <span class="sp-avatar__label">{{ $short }}</span>
                                    @else
                                        {{ $short }}
                                    @endif
                                @endif
                            </span>
                            <div>
                                <strong>{{ $label }}</strong>
                                <span class="sp-list__meta">
                                    {{ $team['id'] }}
                                    ·
                                    <span data-i18n="accounts.memberCount" data-i18n-count="{{ $memberCount }}">{{ $memberCount }} members</span>
                                    @if ($managerCount > 0)
                                        · <span data-i18n="accounts.role.manager">Manager</span> ×{{ $managerCount }}
                                    @endif
                                    @if ($ceoCount > 0)
                                        · <span data-i18n="accounts.role.ceo">CEO</span> ×{{ $ceoCount }}
                                    @endif
                                    @if (! empty($team['archived']))
                                        · <span data-i18n="accounts.archived">Archived</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a
                            href="{{ locale_route('admin.teams.edit', ['teamId' => $team['id']]) }}"
                            class="tools-btn tools-btn--secondary tools-btn--small"
                            data-i18n="accounts.edit"
                        >Edit</a>
                    </div>
                @empty
                    <p class="tools-page-lead" data-i18n="accounts.noTeams">No teams yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
