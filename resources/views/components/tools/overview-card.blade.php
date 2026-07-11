@props([
    'href',
    'titleKey',
    'descriptionKey',
    'count',
    'icon' => 'fa-grid-2',
    'accent' => 'accent',
])

<a href="{{ $href }}" class="tools-card tools-card--overview">
    <div class="tools-card__main">
        <div class="tools-card__icon-wrap tools-card__icon-wrap--{{ $accent }}" aria-hidden="true">
            <i class="fa-solid {{ $icon }} tools-card__icon"></i>
        </div>
        <div class="tools-card__body">
            <div class="tools-card__title-row">
                <h3 class="tools-card__title" data-i18n="{{ $titleKey }}">View all</h3>
            </div>
            <p class="tools-card__meta">
                <span class="tools-card__count">{{ $count }}</span>
                <span data-i18n="home.viewAllTotal">total</span>
            </p>
            <p class="tools-card__desc" data-i18n="{{ $descriptionKey }}">Browse the full overview.</p>
        </div>
        <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
    </div>
</a>
