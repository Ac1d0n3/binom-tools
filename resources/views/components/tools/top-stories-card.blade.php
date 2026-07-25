@props([
    'stories' => [],
])

@php
    /** @var list<array<string, mixed>> $stories */
    $stories = array_values(array_filter($stories, 'is_array'));
@endphp

@if (count($stories) > 0)
    <aside class="tools-card tools-card--top-stories" aria-labelledby="landing-top-stories-title">
        <div class="tools-card__top-stories-header">
            <div class="tools-card__icon-wrap tools-card__icon-wrap--accent" aria-hidden="true">
                <i class="fa-solid fa-trophy tools-card__icon"></i>
            </div>
            <div>
                <h3
                    id="landing-top-stories-title"
                    class="tools-card__title"
                    data-i18n="home.topStories.title"
                >Top stories</h3>
                <p class="tools-card__meta" data-i18n="home.topStories.lead">Most liked right now</p>
            </div>
        </div>

        <ol class="tools-card__top-stories-list">
            @foreach ($stories as $index => $item)
                @php
                    $de = $item['locales']['de'] ?? null;
                    $en = $item['locales']['en'] ?? null;
                    $titleDe = $de['title'] ?? '';
                    $titleEn = $en['title'] ?? $titleDe;
                    $views = max(0, (int) ($item['stats']['views'] ?? 0));
                    $likes = max(0, (int) ($item['stats']['likes'] ?? 0));
                    $slug = (string) ($item['slug'] ?? '');
                @endphp
                <li class="tools-card__top-stories-item">
                    <span class="tools-card__top-stories-rank" aria-hidden="true">{{ $index + 1 }}</span>
                    <a
                        href="{{ locale_route('playbooks.show', ['slug' => $slug]) }}"
                        class="tools-card__top-stories-link"
                    >
                        <span
                            class="tools-card__top-stories-title"
                            data-text-de="{{ $titleDe }}"
                            data-text-en="{{ $titleEn }}"
                        >{{ $titleEn }}</span>
                        <span class="tools-card__top-stories-stats">
                            <span title="Views">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                {{ number_format($views) }}
                            </span>
                            <span title="Likes">
                                <i class="fa-solid fa-heart" aria-hidden="true"></i>
                                {{ number_format($likes) }}
                            </span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ol>
    </aside>
@endif
