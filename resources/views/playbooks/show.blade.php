@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/playbooks.css',
        'resources/js/playbooks/show.js',
    ],
    'mainClass' => 'tools-shell__main--playbook',
])

@section('title', $playbook->title() . ' — ' . config('app.name'))

@php
    $activeLocale = current_locale();
    $activeVariant = $playbook->variants[$activeLocale] ?? null;
    $activeHeroUrl = $activeVariant?->heroUrl;
    $activeHeroSources = filled($activeHeroUrl)
        ? \App\Support\PlaybookImagePath::pictureSources($activeHeroUrl)
        : null;
    $metaDescription = trim((string) ($activeVariant?->description ?? ''));
@endphp

@push('head')
    @if ($metaDescription !== '')
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if ($activeHeroSources)
        <link
            rel="preload"
            as="image"
            href="{{ $activeHeroSources['webp'] }}"
            type="image/webp"
            fetchpriority="high"
        >
    @elseif (filled($activeHeroUrl))
        <link rel="preload" as="image" href="{{ $activeHeroUrl }}" fetchpriority="high">
    @endif
@endpush

@section('content')
    <div
        class="playbook-offline-banner"
        data-playbook-offline-banner
        hidden
        role="status"
    ></div>
    <article
        class="playbook-detail"
        itemscope
        itemtype="https://schema.org/Article"
        data-playbook-slug="{{ $playbook->slug }}"
        data-playbook-root
        data-title-de="{{ $playbook->title('de') }}"
        data-title-en="{{ $playbook->title('en') }}"
        data-title-suffix=" — {{ config('app.name') }}"
    >
        @foreach ($playbook->variants as $locale => $variant)
            @php
                $isActiveLocale = $locale === $activeLocale;
            @endphp
            <div
                class="playbook-detail__locale"
                data-playbook-locale-panel="{{ $locale }}"
                @if (! $isActiveLocale) hidden @endif
            >
                <div class="playbook-detail__layout">
                    <div class="playbook-detail__main-column">
                        <div class="playbook-detail__scroll" data-playbook-scroll-root>
                            @if ($variant->heroUrl)
                                <div class="playbook-detail__hero">
                                    <x-playbooks.responsive-image
                                        :src="$variant->heroUrl"
                                        :alt="$variant->title"
                                        class="playbook-detail__hero-image"
                                        :loading="$isActiveLocale ? 'eager' : 'lazy'"
                                        :fetchpriority="$isActiveLocale ? 'high' : null"
                                        decoding="async"
                                    />
                                </div>
                            @endif

                            <div class="playbook-detail__main">
                                <header class="playbook-detail__header" id="{{ $locale }}-playbook-start">
                                <h1 class="tools-page-title" itemprop="headline">{{ $variant->title }}</h1>

                                @if ($variant->description)
                                    <p class="tools-page-lead">{{ $variant->description }}</p>
                                @endif

                                <x-playbooks.meta :variant="$variant" :modified-at="$playbook->modifiedAt" />

                                <x-playbooks.engagement
                                    :slug="$playbook->slug"
                                    :views="(int) ($engagementStats['views'] ?? 0)"
                                    :likes="(int) ($engagementStats['likes'] ?? 0)"
                                    :share-enabled="(bool) config('playbooks.share_enabled', true)"
                                />

                                <div class="playbook-detail__actions playbook-offline" data-playbook-offline>
                                    <button
                                        type="button"
                                        class="tools-btn tools-btn--secondary"
                                        data-playbook-offline-save
                                        data-i18n="playbooks.offline.save"
                                    >Save offline</button>
                                    <button
                                        type="button"
                                        class="tools-btn tools-btn--ghost"
                                        data-playbook-offline-remove
                                        hidden
                                        data-i18n="playbooks.offline.remove"
                                    >Remove offline</button>
                                    <p
                                        class="playbook-offline__status"
                                        data-playbook-offline-status
                                        hidden
                                        role="status"
                                        aria-live="polite"
                                    ></p>
                                </div>

                                @if ($playbook->slug === 'help-hub-platform' && config('tools.links.repository'))
                                    <div class="playbook-detail__actions">
                                        <x-tools.repo-clone-link variant="primary">
                                            {{ $locale === 'de' ? 'Git-Repo klonen' : 'Clone Git repo' }}
                                        </x-tools.repo-clone-link>
                                    </div>
                                @endif
                            </header>

                            <div class="playbook-prose">
                                {!! $variant->bodyHtml !!}
                            </div>

                            <x-playbooks.series-nav :playbook="$playbook" />
                            <x-playbooks.pager :playbook="$playbook" />
                            </div>
                        </div>

                        <div class="playbook-detail__toolbar">
                            @if (count($variant->toc) > 0)
                                <button
                                    type="button"
                                    class="playbook-toc-rail-toggle tools-btn tools-btn--ghost"
                                    data-playbook-toc-rail-button
                                    aria-pressed="false"
                                    data-i18n-aria="playbooks.tocShow"
                                    title="Show table of contents"
                                >
                                    <i class="fa-solid fa-list" aria-hidden="true"></i>
                                    <span class="sr-only" data-playbook-toc-rail-label data-i18n="playbooks.tocShow">Show table of contents</span>
                                </button>
                            @endif
                            <button
                                type="button"
                                class="playbook-focus-toggle tools-btn tools-btn--ghost"
                                data-playbook-focus-button
                                aria-pressed="false"
                                data-i18n-aria="playbooks.focusExpand"
                                title="Hide sidebars"
                            >
                                <i class="fa-solid fa-arrows-left-right-to-line" aria-hidden="true"></i>
                                <span class="sr-only" data-playbook-focus-label data-i18n="playbooks.focusExpand">Hide sidebars</span>
                            </button>
                        </div>
                    </div>

                    @if (count($variant->toc) > 0)
                        <aside class="playbook-detail__toc-rail">
                            <x-playbooks.toc
                                :entries="$variant->toc"
                                :start-id="$locale . '-playbook-start'"
                            />
                        </aside>
                    @endif
                </div>
            </div>
        @endforeach
    </article>
@endsection
