@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/playbooks.css',
        'resources/js/playbooks/show.js',
    ],
    'mainClass' => 'tools-shell__main--playbook',
])

@section('title', $playbook->title() . ' — ' . config('app.name'))

@section('content')
    <article
        class="playbook-detail"
        data-playbook-slug="{{ $playbook->slug }}"
        data-playbook-root
        data-title-de="{{ $playbook->title('de') }}"
        data-title-en="{{ $playbook->title('en') }}"
        data-title-suffix=" — {{ config('app.name') }}"
    >
        @foreach ($playbook->variants as $locale => $variant)
            <div
                class="playbook-detail__locale"
                data-playbook-locale-panel="{{ $locale }}"
                @if ($locale !== 'de') hidden @endif
            >
                <div class="playbook-detail__layout">
                    <div class="playbook-detail__scroll" data-playbook-scroll-root>
                        @if ($playbook->heroUrl)
                            <div class="playbook-detail__hero">
                                <img
                                    src="{{ $playbook->heroUrl }}"
                                    alt=""
                                    class="playbook-detail__hero-image"
                                    loading="eager"
                                />
                            </div>
                        @endif

                        <div class="playbook-detail__main">
                            <header class="playbook-detail__header">
                                <h1 class="tools-page-title">{{ $variant->title }}</h1>

                                @if ($variant->description)
                                    <p class="tools-page-lead">{{ $variant->description }}</p>
                                @endif

                                <x-playbooks.meta :variant="$variant" :modified-at="$playbook->modifiedAt" />

                                @if ($playbook->slug === 'help-hub-platform' && config('tools.links.repository'))
                                    <p class="playbook-detail__download">
                                        <a
                                            href="{{ rtrim(config('tools.links.repository'), '/') }}/archive/refs/heads/main.zip"
                                            class="tools-btn tools-btn--primary"
                                            download
                                        >
                                            <i class="fa-solid fa-download" aria-hidden="true"></i>
                                            <span data-i18n="playbooks.downloadStarter">Starter-Template herunterladen (ZIP)</span>
                                        </a>
                                    </p>
                                @endif
                            </header>

                            <div class="playbook-prose">
                                {!! $variant->bodyHtml !!}
                            </div>

                            <x-playbooks.pager :playbook="$playbook" />
                        </div>
                    </div>

                    @if (count($variant->toc) > 0)
                        <aside class="playbook-detail__toc-rail">
                            <x-playbooks.toc :entries="$variant->toc" />
                        </aside>
                    @endif
                </div>
            </div>
        @endforeach
    </article>
@endsection
