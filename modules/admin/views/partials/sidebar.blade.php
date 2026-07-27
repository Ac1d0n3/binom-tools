@php
    $accountUser = $accountUser ?? null;
    $canUsers = ! empty($accountUser['canManageUsers']);
    $canTeams = ! empty($accountUser['canManageTeams']);
@endphp

<nav class="tools-sidenav admin-sidenav" aria-label="Admin navigation">
    <ul class="tools-sidenav__list tools-sidenav__list--home">
        <li>
            <a href="{{ locale_route('tools.landing') }}" class="tools-sidenav__link">
                <span data-text-de="Zurück zur App" data-text-en="Back to app">Back to app</span>
            </a>
        </li>
        <li>
            <a href="{{ locale_route('admin.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.index') ? 'tools-sidenav__link--active' : '' }}">
                <span data-text-de="Admin Hub" data-text-en="Admin Hub">Admin Hub</span>
            </a>
        </li>
    </ul>

    @if ($canUsers || $canTeams)
        <div class="tools-sidenav__group admin-sidenav__section">
            <p class="admin-sidenav__label" data-text-de="Administration" data-text-en="Administration">Administration</p>
            <ul class="tools-sidenav__list">
                @if ($canUsers)
                    <li>
                        <a href="{{ locale_route('admin.users.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.users.*') || request()->routeIs('accounts.users*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Users" data-text-en="Users">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.story-acl.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.story-acl.*') || request()->routeIs('accounts.story-acl*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Story access" data-text-en="Story access">Story access</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.link-check.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.link-check.*') || request()->routeIs('accounts.link-check*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Link-Checker" data-text-en="Link checker">Link checker</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.stories.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.stories.*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Stories" data-text-en="Stories">Stories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.plan-templates.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.plan-templates.*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Plan-Templates" data-text-en="Plan templates">Plan templates</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.radar.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.radar.*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Radar" data-text-en="Radar">Radar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.vendors.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.vendors.*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Vendors" data-text-en="Vendors">Vendors</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ locale_route('admin.glossary.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.glossary.*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Glossary" data-text-en="Glossary">Glossary</span>
                        </a>
                    </li>
                @endif
                @if ($canTeams)
                    <li>
                        <a href="{{ locale_route('admin.teams.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.teams.*') || request()->routeIs('accounts.teams*') ? 'tools-sidenav__link--active' : '' }}">
                            <span data-text-de="Teams" data-text-en="Teams">Teams</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @endif
</nav>
