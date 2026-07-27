@php
    $accountUser = $accountUser ?? null;
    $canUsers = ! empty($accountUser['canManageUsers']);
    $canTeams = ! empty($accountUser['canManageTeams']);
@endphp

<nav class="tools-sidenav admin-sidenav" aria-label="Admin navigation">
    <ul class="tools-sidenav__list tools-sidenav__list--home">
        <li>
            <a href="{{ locale_route('admin.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.index') ? 'tools-sidenav__link--active' : '' }}">
                <span data-text-de="Admin Hub" data-text-en="Admin Hub">Admin Hub</span>
            </a>
        </li>
        <li>
            <a href="{{ locale_route('tools.landing') }}" class="tools-sidenav__link">
                <span data-text-de="Zurück zur App" data-text-en="Back to app">Back to app</span>
            </a>
        </li>
    </ul>

    <div class="tools-sidenav__group admin-sidenav__section">
        <p class="admin-sidenav__label" data-text-de="Mein Bereich" data-text-en="My area">My area</p>
        <ul class="tools-sidenav__list">
            <li>
                <a href="{{ locale_route('admin.workspaces.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.workspaces.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="My Workspaces" data-text-en="My Workspaces">My Workspaces</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('admin.plans.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.plans.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="My Plans" data-text-en="My Plans">My Plans</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('governance.sessions.index') }}" class="tools-sidenav__link {{ request()->routeIs('governance.sessions.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Governance Sessions" data-text-en="Governance Sessions">Governance Sessions</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('admin.reads.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.reads.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Gelesene Stories" data-text-en="Read stories">Read stories</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('admin.quiz.index') }}" class="tools-sidenav__link {{ request()->routeIs('admin.quiz.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Quiz-Ergebnisse" data-text-en="Quiz results">Quiz results</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('accounts.profile') }}" class="tools-sidenav__link {{ request()->routeIs('accounts.profile') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Profil" data-text-en="Profile">Profile</span>
                </a>
            </li>
        </ul>
    </div>

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
