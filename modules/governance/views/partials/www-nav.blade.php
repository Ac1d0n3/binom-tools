@php
    use App\Support\Locale;

    $routeBase = Locale::routeBaseName(request()->route()?->getName());
    $items = [
        [
            'route' => 'governance.index',
            'active' => $routeBase === 'governance.index',
            'icon' => 'fa-house',
            'de' => 'Hub',
            'en' => 'Hub',
        ],
        [
            'route' => 'governance.advisor',
            'active' => $routeBase === 'governance.advisor',
            'icon' => 'fa-compass',
            'de' => 'Berater',
            'en' => 'Advisor',
        ],
        [
            'route' => 'governance.stacks',
            'active' => $routeBase === 'governance.stacks',
            'icon' => 'fa-layer-group',
            'de' => 'Stacks',
            'en' => 'Stacks',
        ],
        [
            'route' => 'governance.kpi-requirements',
            'active' => $routeBase === 'governance.kpi-requirements',
            'icon' => 'fa-gauge-high',
            'de' => 'KPI',
            'en' => 'KPI',
        ],
        [
            'route' => 'governance.supplier-discovery',
            'active' => $routeBase === 'governance.supplier-discovery',
            'icon' => 'fa-database',
            'de' => 'Supplier',
            'en' => 'Supplier',
        ],
        [
            'route' => 'governance.discovery-canvas',
            'active' => $routeBase === 'governance.discovery-canvas',
            'icon' => 'fa-clipboard-list',
            'de' => 'Workshop',
            'en' => 'Workshop',
        ],
    ];
@endphp

<nav class="governance-hub__tabs governance-www-tabs" aria-label="Governance WWW Bereiche" role="tablist">
    @foreach ($items as $item)
        <a
            href="{{ locale_route($item['route']) }}"
            class="governance-hub__tab {{ $item['active'] ? 'governance-hub__tab--active' : '' }}"
            role="tab"
            aria-selected="{{ $item['active'] ? 'true' : 'false' }}"
            @if ($item['active']) aria-current="page" @endif
        >
            <i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i>
            <span data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</span>
        </a>
    @endforeach
</nav>
