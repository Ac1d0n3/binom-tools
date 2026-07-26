@props([
    'products' => [],
    'limit' => 3,
])

@php
    /** @var list<string> $products */
    $products = \App\Playbooks\PlaybookProducts::sort(array_values(array_filter(
        is_array($products) ? $products : [],
        static fn (mixed $id): bool => is_string($id) && $id !== '',
    )));
    $limit = max(1, (int) $limit);
    $visible = array_slice($products, 0, $limit);
    $hidden = array_slice($products, $limit);
    $moreCount = count($hidden);
    $moreLabels = array_map(
        static fn (string $id): string => \App\Playbooks\PlaybookProducts::label($id),
        $hidden,
    );
    $moreTitle = implode(', ', $moreLabels);
@endphp

@if (count($products) > 0)
    <div {{ $attributes->class(['tools-card__story-products']) }} aria-label="Products">
        <span class="tools-card__platform-marks">
            @foreach ($visible as $productId)
                @php
                    $label = \App\Playbooks\PlaybookProducts::label($productId);
                    $asset = \App\Playbooks\PlaybookProducts::badgeAsset($productId);
                @endphp
                @if ($asset !== null)
                    <img
                        src="{{ asset($asset) }}"
                        alt="{{ $label }}"
                        title="{{ $label }}"
                        @class([
                            'tools-card__dbt-badge' => $productId === 'dbt',
                            'tools-card__platform-mark' => $productId !== 'dbt',
                        ])
                        loading="lazy"
                        decoding="async"
                    />
                @else
                    <span class="tools-card__product-chip" title="{{ $label }}">{{ $label }}</span>
                @endif
            @endforeach
            @if ($moreCount > 0)
                <span
                    class="tools-card__product-chip tools-card__product-chip--more"
                    title="{{ $moreTitle }}"
                    aria-label="{{ $moreTitle }}"
                    data-i18n="overview.productsMore"
                    data-i18n-count="{{ $moreCount }}"
                >+{{ $moreCount }} more</span>
            @endif
        </span>
    </div>
@endif
