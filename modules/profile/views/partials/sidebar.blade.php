<nav class="tools-sidenav admin-sidenav" aria-label="Profile navigation">
    <ul class="tools-sidenav__list tools-sidenav__list--home">
        <li>
            <a href="{{ locale_route('tools.landing') }}" class="tools-sidenav__link">
                <span data-text-de="Zurück zur App" data-text-en="Back to app">Back to app</span>
            </a>
        </li>
        <li>
            <a href="{{ locale_route('profile.index') }}" class="tools-sidenav__link {{ request()->routeIs('profile.index') ? 'tools-sidenav__link--active' : '' }}">
                <span data-text-de="Profil Hub" data-text-en="Profile Hub">Profile Hub</span>
            </a>
        </li>
    </ul>

    <div class="tools-sidenav__group admin-sidenav__section">
        <p class="admin-sidenav__label" data-text-de="Mein Bereich" data-text-en="My area">My area</p>
        <ul class="tools-sidenav__list">
            <li>
                <a href="{{ locale_route('profile.workspaces.index') }}" class="tools-sidenav__link {{ request()->routeIs('profile.workspaces.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="My Workspaces" data-text-en="My Workspaces">My Workspaces</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('profile.plans.index') }}" class="tools-sidenav__link {{ request()->routeIs('profile.plans.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="My Plans" data-text-en="My Plans">My Plans</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('governance.sessions.index') }}" class="tools-sidenav__link {{ request()->routeIs('governance.sessions.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Governance Sessions" data-text-en="Governance Sessions">Governance Sessions</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('profile.reads.index') }}" class="tools-sidenav__link {{ request()->routeIs('profile.reads.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Gelesene Stories" data-text-en="Read stories">Read stories</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('profile.quiz.index') }}" class="tools-sidenav__link {{ request()->routeIs('profile.quiz.*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Quiz-Ergebnisse" data-text-en="Quiz results">Quiz results</span>
                </a>
            </li>
            <li>
                <a href="{{ locale_route('profile.settings') }}" class="tools-sidenav__link {{ request()->routeIs('profile.settings*') || request()->routeIs('accounts.profile*') ? 'tools-sidenav__link--active' : '' }}">
                    <span data-text-de="Profil" data-text-en="Profile">Profile</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
