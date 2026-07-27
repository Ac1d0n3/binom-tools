@props([
    'items' => [],
])

@php
    /** @var list<array{label: string, href: string}> $items */
@endphp

@if (count($items) > 0)
    <nav class="governance-seo-breadcrumbs" aria-label="Breadcrumb">
        <ol class="governance-seo-breadcrumbs__list">
            @foreach ($items as $index => $item)
                <li class="governance-seo-breadcrumbs__item">
                    @if ($index < count($items) - 1)
                        <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                    @else
                        <span aria-current="page">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
