@extends('admin::layouts.shell')

@section('title', 'Story access — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($stories)">
            <x-slot:search>
                <input
                    type="search"
                    class="tools-input"
                    data-overview-search
                    placeholder="Filter stories…"
                    aria-label="Filter stories"
                    data-i18n-placeholder="accounts.filterStories"
                    autocomplete="off"
                >
            </x-slot:search>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'acl-updated' => 'accounts.flash.aclUpdated',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-i18n="accounts.noStories">No matches.</p>

        <section class="sp-section">
            <div class="sp-list">
                @php $isDe = current_locale() === 'de'; @endphp
                @forelse ($stories as $story)
                    @php
                        $isRestricted = ($story['acl']['visibility'] ?? 'public') === 'restricted';
                        $userCount = count($story['acl']['userIds'] ?? []);
                        $teamCount = count($story['acl']['teamIds'] ?? []);
                        $searchText = strtolower(($story['title'] ?? '').' '.($story['slug'] ?? ''));
                    @endphp
                    <div
                        class="sp-list__row"
                        data-overview-item
                        data-search-text="{{ $searchText }}"
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
                        <x-admin.icon-btn kind="edit" :href="locale_route('accounts.story-acl.edit', ['slug' => $story['slug']])" />
                    </div>
                @empty
                    <p class="tools-page-lead" data-i18n="accounts.noStories">No stories found.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
