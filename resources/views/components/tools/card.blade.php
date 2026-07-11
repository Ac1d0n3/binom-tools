@props([
    'href',
    'title',
    'description',
    'meta' => null,
    'icon' => '🔧',
    'accent' => 'primary',
    'featured' => false,
    'external' => false,
    'cardId' => null,
    'example' => false,
    'overviewItem' => false,
    'searchText' => null,
    'tags' => [],
])

<a
    href="{{ $href }}"
    class="tools-card {{ $featured ? 'tools-card--featured' : '' }}"
    @if ($cardId) data-card-id="{{ $cardId }}" @endif
    @if ($overviewItem) data-overview-item @endif
    @if ($searchText) data-search-text="{{ $searchText }}" @endif
    @if (count($tags) > 0) data-tags="{{ implode(',', $tags) }}" @endif
    @if ($external) target="_blank" rel="noopener noreferrer" @endif
>
    <div class="tools-card__icon-wrap tools-card__icon-wrap--{{ $accent }}" aria-hidden="true">
        @if (str_starts_with($icon, 'fa-'))
            <i class="fa-solid {{ $icon }} tools-card__icon"></i>
        @else
            <span class="tools-card__icon">{{ $icon }}</span>
        @endif
    </div>
    <div class="tools-card__body">
        <div class="tools-card__title-row">
            <h3 class="tools-card__title">{{ $title }}</h3>
            @if ($example)
                <span class="tools-card__badge" data-i18n="card.exampleBadge">Example</span>
            @endif
        </div>
        @if ($meta)
            <p class="tools-card__meta">{{ $meta }}</p>
        @endif
        <p class="tools-card__desc">{{ $description }}</p>
    </div>
    @if ($external)
        <i class="fa-solid fa-arrow-up-right-from-square tools-card__arrow" aria-hidden="true"></i>
    @else
        <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
    @endif
</a>
