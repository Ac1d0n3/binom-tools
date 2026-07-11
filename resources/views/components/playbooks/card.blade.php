@props(['item'])

@php
    $de = $item['locales']['de'] ?? null;
    $en = $item['locales']['en'] ?? null;
    $titleDe = $de['title'] ?? '';
    $titleEn = $en['title'] ?? $titleDe;
    $descDe = $de['description'] ?? '';
    $descEn = $en['description'] ?? $descDe;
    $metaDe = collect([$de['category'] ?? null, ($de['readingTimeMinutes'] ?? null) ? ($de['readingTimeMinutes'] . ' min') : null])->filter()->implode(' · ');
    $metaEn = collect([$en['category'] ?? null, ($en['readingTimeMinutes'] ?? null) ? ($en['readingTimeMinutes'] . ' min') : null])->filter()->implode(' · ');
    $tags = $item['tags'] ?? [];
    $searchText = strtolower(implode(' ', array_filter([
        $titleDe,
        $titleEn,
        $descDe,
        $descEn,
        $de['category'] ?? '',
        $en['category'] ?? '',
        $item['slug'] ?? '',
        implode(' ', $tags),
    ])));
@endphp

<a
    href="{{ route('playbooks.show', $item['slug']) }}"
    class="tools-card"
    data-playbook-index-card
    data-overview-item
    data-card-id="playbook-{{ $item['slug'] }}"
    data-search-text="{{ $searchText }}"
    @if (count($tags) > 0) data-tags="{{ implode(',', $tags) }}" @endif
>
    <div class="tools-card__main">
        <div class="tools-card__icon-wrap tools-card__icon-wrap--primary" aria-hidden="true">
            <i class="fa-solid fa-book-open tools-card__icon"></i>
        </div>
        <div class="tools-card__body">
            <div class="tools-card__title-row">
                <h3
                    class="tools-card__title"
                    data-playbook-card-title
                    data-text-de="{{ $titleDe }}"
                    data-text-en="{{ $titleEn }}"
                >{{ $titleDe }}</h3>
            </div>
            @if ($metaDe !== '' || $metaEn !== '')
                <p
                    class="tools-card__meta"
                    data-playbook-card-meta
                    data-text-de="{{ $metaDe }}"
                    data-text-en="{{ $metaEn }}"
                >{{ $metaDe }}</p>
            @endif
            <p
                class="tools-card__desc"
                data-playbook-card-description
                data-text-de="{{ $descDe }}"
                data-text-en="{{ $descEn }}"
            >{{ $descDe }}</p>
            @if (count($tags) > 0)
                <ul class="tools-card__tags" aria-label="Tags">
                    @foreach ($tags as $tag)
                        <li class="tools-card__tag">{{ $tag }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
    </div>
</a>
