@php
    use App\Support\Locale;

    $routeBase = Locale::routeBaseName(request()->route()?->getName()) ?? '';
    $hideOn = [
        'governance.index',
        'governance.radar',
        'tools.landing',
        'tools.overview',
        'playbooks.index',
        'playbooks.show',
        'search.index',
        'about.show',
    ];
    $showPrefixes = [
        'tools.',
        'suppliers.',
        'resources.',
        'compliance.',
        'governance.sessions',
        'governance.advisor',
        'governance.stacks',
        'governance.kpi-requirements',
        'governance.supplier-discovery',
        'governance.discovery-canvas',
    ];
    $showHubBacklink = $routeBase !== ''
        && ! in_array($routeBase, $hideOn, true)
        && collect($showPrefixes)->contains(static fn (string $prefix): bool => str_starts_with($routeBase, $prefix));
@endphp

@if ($showHubBacklink)
    <nav class="governance-hub-backlink" aria-label="Governance Hub">
        <a class="governance-hub-backlink__link" href="{{ locale_route('governance.index') }}">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            <span data-text-de="Zurück zum Governance Hub" data-text-en="Back to Governance Hub">Back to Governance Hub</span>
        </a>
    </nav>
@endif
