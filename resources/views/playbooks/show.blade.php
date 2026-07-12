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
        itemscope
        itemtype="https://schema.org/Article"
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
                @if ($locale !== current_locale()) hidden @endif
            >
                <div class="playbook-detail__layout">
                    <div class="playbook-detail__main-column">
                        <div class="playbook-detail__scroll" data-playbook-scroll-root>
                            @if ($variant->heroUrl)
                                <div class="playbook-detail__hero">
                                    <img
                                        src="{{ $variant->heroUrl }}"
                                        alt="{{ $variant->title }}"
                                        class="playbook-detail__hero-image"
                                        loading="eager"
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
