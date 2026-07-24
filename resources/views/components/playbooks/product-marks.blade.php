@props([
    'products' => [],
])

@php
    /** @var list<string> $products */
    $products = array_values(array_filter(
        is_array($products) ? $products : [],
        static fn (mixed $id): bool => is_string($id) && $id !== '',
    ));
@endphp

@if (count($products) > 0)
    <div {{ $attributes->class(['tools-card__purpose tools-card__purpose--story-products']) }} aria-label="Products">
        <span class="tools-card__platform-marks">
            @foreach ($products as $productId)
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
        </span>
    </div>
@endif
