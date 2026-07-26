@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $id = (string) ($item['id'] ?? '');
    $titleEn = (string) ($item['title']['en'] ?? $id);
    $titleDe = (string) ($item['title']['de'] ?? $titleEn);
    $leadEn = (string) ($item['lead']['en'] ?? '');
    $leadDe = (string) ($item['lead']['de'] ?? $leadEn);
    $persona = (string) ($item['persona'] ?? $id);
    $personaLabel = $personas[$persona] ?? ['de' => $persona, 'en' => $persona];
    $storyLinks = is_array($item['storyLinks'] ?? null) ? $item['storyLinks'] : [];
    $hubLinks = is_array($item['hubLinks'] ?? null) ? $item['hubLinks'] : [];
    $pending = is_array($item['pendingStories'] ?? null) ? $item['pendingStories'] : [];
@endphp

@section('title', $titleEn.' — Roles — '.config('app.name'))
@section('meta_description', $leadEn !== '' ? $leadEn : 'Governance role: '.$titleEn)

@section('content')
    <div class="tools-content tools-content--roles-detail" data-roles-hub data-roles-detail-persona="{{ $persona }}">
        <nav class="roles-detail__nav" aria-label="Breadcrumb">
            <a href="{{ locale_route('roles.index') }}" data-i18n="roles.backToIndex">← Roles</a>
        </nav>

        <header class="roles-detail__header">
            <p class="roles-detail__persona" data-text-de="{{ $personaLabel['de'] ?? $persona }}" data-text-en="{{ $personaLabel['en'] ?? $persona }}">
                {{ $personaLabel['en'] ?? $persona }}
            </p>
            <h1 class="tools-page-title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h1>
            <p class="tools-page-lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
        </header>

        @if (count($hubLinks) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-hub-links">
                <h2 id="roles-hub-links" class="roles-detail__section-title" data-i18n="roles.hubLinksTitle">Start here</h2>
                <ul class="roles-detail__links">
                    @foreach ($hubLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                <span class="roles-detail__kind" data-i18n="search.type.{{ $link['kind'] }}">{{ $link['kind'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if (count($storyLinks) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-story-links">
                <h2 id="roles-story-links" class="roles-detail__section-title" data-i18n="roles.storiesTitle">Stories</h2>
                <ul class="roles-detail__links">
                    @foreach ($storyLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                <span class="roles-detail__kind" data-i18n="search.type.story">story</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @if (count($pending) > 0)
                    <p class="roles-detail__pending" data-i18n="roles.pendingStoriesHint">
                        Dedicated role stories are still planned — see docs/story-gaps-roles.md.
                    </p>
                @endif
            </section>
        @endif
    </div>
@endsection
