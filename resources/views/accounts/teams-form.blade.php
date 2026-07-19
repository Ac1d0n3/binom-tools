@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@php
    use App\Accounts\AccountTeam;

    $isEdit = is_array($team);
    $titleKey = $isEdit ? 'accounts.editTeam' : 'accounts.addTeam';
    $action = $isEdit
        ? locale_route('accounts.teams.update', ['teamId' => $team['id']])
        : locale_route('accounts.teams.store');
    $selectedMembers = array_map('strval', old('memberIds', $team['memberIds'] ?? []));
    $memberRoles = old('memberRoles', $team['memberRoles'] ?? []);
    if (! is_array($memberRoles)) {
        $memberRoles = [];
    }
@endphp

@section('title', ($isEdit ? 'Edit team' : 'Add team') . ' — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <p class="sp-action-row">
            <a href="{{ locale_route('accounts.teams') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.backToTeams">Back to teams</a>
        </p>

        <h1 class="tools-page-title" data-i18n="{{ $titleKey }}">{{ $isEdit ? 'Edit team' : 'Add team' }}</h1>
        @if ($isEdit)
            <p class="tools-page-lead"><code>{{ $team['id'] }}</code></p>
        @endif

        <x-accounts.flash />

        <form method="post" action="{{ $action }}" class="sp-lock-form" style="max-width:40rem">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="sp-split">
                <label class="sp-field">
                    <span data-i18n="accounts.nameDe">Name (DE)</span>
                    <input type="text" name="name_de" class="tools-input" value="{{ old('name_de', $team['name']['de'] ?? '') }}" required>
                </label>
                <label class="sp-field">
                    <span data-i18n="accounts.nameEn">Name (EN)</span>
                    <input type="text" name="name_en" class="tools-input" value="{{ old('name_en', $team['name']['en'] ?? '') }}">
                </label>
            </div>

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
                    value="{{ old('shortName', $team['shortName'] ?? '') }}"
                >
            </label>

            <label class="sp-field">
                <span data-i18n="accounts.descriptionDe">Description (DE)</span>
                <textarea name="description_de" class="tools-input" rows="2">{{ old('description_de', $team['description']['de'] ?? '') }}</textarea>
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.descriptionEn">Description (EN)</span>
                <textarea name="description_en" class="tools-input" rows="2">{{ old('description_en', $team['description']['en'] ?? '') }}</textarea>
            </label>

            <x-accounts.color-swatches
                :selected="old('colorToken', $team['colorToken'] ?? ($defaultColorToken ?? 'accent-1'))"
                :tokens="\App\Support\AccentColors::TEAM_TOKENS"
            />

            <fieldset class="sp-field" data-accounts-checkbox-filter>
                <legend data-i18n="accounts.members">Members</legend>
                <p class="sp-field-hint" data-i18n="accounts.roleHint">
                    Managers often own team plans — anyone can still create their own plan.
                </p>
                <label class="sp-field">
                    <span class="visually-hidden" data-i18n="accounts.filterMembers">Filter</span>
                    <input
                        type="search"
                        class="tools-input"
                        data-accounts-filter-input
                        placeholder="Filter…"
                        data-i18n-placeholder="accounts.filterMembers"
                        autocomplete="off"
                    >
                </label>
                <div class="sp-checkbox-list">
                    @forelse ($users as $user)
                        @php
                            $uid = (string) ($user['id'] ?? '');
                            $label = (string) ($user['displayName'] ?? $user['email'] ?? $uid);
                            $checked = in_array($uid, $selectedMembers, true);
                            $role = AccountTeam::normalizeRole($memberRoles[$uid] ?? AccountTeam::ROLE_MEMBER);
                        @endphp
                        <div
                            class="sp-member-role-row"
                            data-accounts-filter-row
                            data-filter-text="{{ strtolower($label.' '.$uid) }}"
                        >
                            <label class="sp-check">
                                <input type="checkbox" name="memberIds[]" value="{{ $uid }}" @checked($checked)>
                                {{ $label }}
                            </label>
                            <label class="sp-field sp-field--compact">
                                <span class="visually-hidden" data-i18n="accounts.role">Role</span>
                                <select name="memberRoles[{{ $uid }}]" class="tools-input">
                                    <option value="member" @selected($role === 'member') data-i18n="accounts.role.member">Member</option>
                                    <option value="manager" @selected($role === 'manager') data-i18n="accounts.role.manager">Manager</option>
                                    <option value="ceo" @selected($role === 'ceo') data-i18n="accounts.role.ceo">CEO</option>
                                </select>
                            </label>
                        </div>
                    @empty
                        <p class="tools-page-lead" data-i18n="accounts.noItems">No items yet.</p>
                    @endforelse
                </div>
            </fieldset>

            @if ($isEdit)
                <label class="sp-check">
                    <input type="checkbox" name="archived" value="1" @checked(old('archived', $team['archived'] ?? false))>
                    <span data-i18n="accounts.archived">Archived</span>
                </label>
            @endif

            <div class="sp-action-row">
                <a href="{{ locale_route('accounts.teams') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.cancel">Cancel</a>
                <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.save">Save</button>
            </div>
        </form>

        @if ($isEdit)
            <form
                method="post"
                action="{{ locale_route('accounts.teams.destroy', ['teamId' => $team['id']]) }}"
                class="sp-lock-form"
                style="max-width:40rem;margin-top:1.5rem"
                onsubmit="return confirm(@json(__('Delete this team?')));"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="tools-btn tools-btn--danger" data-i18n="accounts.deleteTeam">Delete team</button>
            </form>
        @endif
    </div>

    <script>
    (() => {
        document.querySelectorAll('[data-accounts-checkbox-filter]').forEach((root) => {
            const input = root.querySelector('[data-accounts-filter-input]');
            if (!input || input.dataset.bound === '1') return;
            input.dataset.bound = '1';
            input.addEventListener('input', () => {
                const q = String(input.value || '').trim().toLowerCase();
                root.querySelectorAll('[data-accounts-filter-row]').forEach((row) => {
                    const text = row.getAttribute('data-filter-text') || '';
                    row.hidden = q !== '' && !text.includes(q);
                });
            });
        });
    })();
    </script>
@endsection
