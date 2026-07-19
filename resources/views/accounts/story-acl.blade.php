@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@section('title', 'Story access — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <h1 class="tools-page-title" data-i18n="accounts.storyAclTitle">Story access</h1>
        <p class="tools-page-lead" data-i18n="accounts.storyAclLead">
            Playbooks are public by default. Restrict individual stories to selected users or teams.
        </p>

        <x-accounts.flash :status-map="[
            'acl-updated' => 'accounts.flash.aclUpdated',
        ]" />

        <section class="sp-section" aria-labelledby="accounts-story-acl-heading">
            <div class="sp-section__header">
                <h2 id="accounts-story-acl-heading" class="sp-section__title" data-i18n="accounts.storyAclStories">Stories</h2>
            </div>

            <label class="sp-field sp-field--compact" style="max-width:20rem;margin-bottom:0.85rem">
                <span class="visually-hidden" data-i18n="accounts.filterStories">Filter stories…</span>
                <input
                    type="search"
                    class="tools-input"
                    id="accounts-story-filter"
                    placeholder="Filter stories…"
                    data-i18n-placeholder="accounts.filterStories"
                    autocomplete="off"
                >
            </label>

            <div class="sp-list" id="accounts-story-list">
                @php $isDe = current_locale() === 'de'; @endphp
                @forelse ($stories as $story)
                    @php
                        $isRestricted = ($story['acl']['visibility'] ?? 'public') === 'restricted';
                        $userCount = count($story['acl']['userIds'] ?? []);
                        $teamCount = count($story['acl']['teamIds'] ?? []);
                    @endphp
                    <div
                        class="sp-list__row"
                        data-accounts-story-row
                        data-filter-text="{{ strtolower(($story['title'] ?? '').' '.($story['slug'] ?? '')) }}"
                    >
                        <div class="sp-list__identity">
                            <div>
                                <strong>{{ $story['title'] }}</strong>
                                <span class="sp-list__meta">
                                    <code>{{ $story['slug'] }}</code>
                                    ·
                                    @if ($isRestricted)
                                        <span class="sp-status sp-status--restricted" data-i18n="accounts.visibility.restricted">Restricted</span>
                                        ·
                                        {{ $isDe
                                            ? $userCount.' Benutzer · '.$teamCount.' Teams'
                                            : $userCount.' users · '.$teamCount.' teams' }}
                                    @else
                                        <span class="sp-status sp-status--public" data-i18n="accounts.visibility.public">Public</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a
                            href="{{ locale_route('accounts.story-acl.edit', ['slug' => $story['slug']]) }}"
                            class="tools-btn tools-btn--secondary tools-btn--small"
                            data-i18n="accounts.edit"
                        >Edit</a>
                    </div>
                @empty
                    <p class="tools-page-lead" data-i18n="accounts.noStories">No stories found.</p>
                @endforelse
            </div>
        </section>
    </div>

    <script>
    (() => {
        const input = document.getElementById('accounts-story-filter');
        const list = document.getElementById('accounts-story-list');
        if (!input || !list) return;
        input.addEventListener('input', () => {
            const q = String(input.value || '').trim().toLowerCase();
            list.querySelectorAll('[data-accounts-story-row]').forEach((row) => {
                const text = row.getAttribute('data-filter-text') || '';
                row.hidden = q !== '' && !text.includes(q);
            });
        });
    })();
    </script>
@endsection
