@extends('foundations.layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $id = (string) ($item['id'] ?? '');
    $icon = (string) ($item['icon'] ?? 'fa-user');
    $titleEn = (string) ($item['title']['en'] ?? $id);
    $titleDe = (string) ($item['title']['de'] ?? $titleEn);
    $focusEn = is_array($item['focus']['en'] ?? null) ? array_values(array_filter($item['focus']['en'], 'is_string')) : [];
    $focusDe = is_array($item['focus']['de'] ?? null) ? array_values(array_filter($item['focus']['de'], 'is_string')) : $focusEn;
    $focusCount = max(count($focusEn), count($focusDe));
    $leadEn = (string) ($item['lead']['en'] ?? '');
    $leadDe = (string) ($item['lead']['de'] ?? $leadEn);
    $persona = (string) ($item['persona'] ?? $id);
    $personaLabel = $personas[$persona] ?? ['de' => $persona, 'en' => $persona];
    $ownsEn = is_array($item['owns']['en'] ?? null) ? array_values(array_filter($item['owns']['en'], 'is_string')) : [];
    $ownsDe = is_array($item['owns']['de'] ?? null) ? array_values(array_filter($item['owns']['de'], 'is_string')) : $ownsEn;
    $doesNotEn = is_array($item['doesNot']['en'] ?? null) ? array_values(array_filter($item['doesNot']['en'], 'is_string')) : [];
    $doesNotDe = is_array($item['doesNot']['de'] ?? null) ? array_values(array_filter($item['doesNot']['de'], 'is_string')) : $doesNotEn;
    $tasks = is_array($item['tasks'] ?? null) ? $item['tasks'] : [];
    $worksWithLinks = is_array($item['worksWithLinks'] ?? null) ? $item['worksWithLinks'] : [];
    $pathLinks = is_array($item['pathLinks'] ?? null) ? $item['pathLinks'] : [];
    $toolLinks = is_array($item['toolLinks'] ?? null) ? $item['toolLinks'] : [];
    $storyLinks = is_array($item['storyLinks'] ?? null) ? $item['storyLinks'] : [];
    $glossaryLink = is_array($item['glossaryLink'] ?? null) ? $item['glossaryLink'] : null;
    $pending = is_array($item['pendingStories'] ?? null) ? $item['pendingStories'] : [];
    $relatedBridges = is_array($relatedBridges ?? null) ? $relatedBridges : [];
@endphp

@section('title', $titleEn.' — Roles — '.config('app.name'))
@section('meta_description', $leadEn !== '' ? $leadEn : 'Governance role: '.$titleEn)

@section('content')
    <div class="tools-content tools-content--roles-detail" data-roles-hub data-roles-detail-persona="{{ $persona }}">
        <nav class="roles-detail__nav" aria-label="Breadcrumb">
            <a href="{{ locale_route('roles.index') }}" data-i18n="roles.backToIndex">← Roles</a>
        </nav>

        <header class="roles-detail__header">
            <div class="roles-detail__header-main">
                <div class="roles-detail__header-top">
                    <span class="roles-hub-card__icon-wrap roles-detail__icon-wrap" aria-hidden="true">
                        <i class="fa-solid {{ $icon }} roles-hub-card__icon"></i>
                    </span>
                    <div class="roles-detail__header-copy">
                        <p class="roles-detail__persona" data-text-de="{{ $personaLabel['de'] ?? $persona }}" data-text-en="{{ $personaLabel['en'] ?? $persona }}">
                            {{ $personaLabel['en'] ?? $persona }}
                        </p>
                        @if ($focusCount > 0)
                            <div class="roles-hub-card__purpose roles-detail__purpose">
                                <span class="roles-hub-card__purpose-label" data-i18n="roles.focusLabel">Focus</span>
                                <span class="roles-hub-card__tags">
                                    @for ($i = 0; $i < $focusCount; $i++)
                                        @php
                                            $tagEn = (string) ($focusEn[$i] ?? $focusDe[$i] ?? '');
                                            $tagDe = (string) ($focusDe[$i] ?? $tagEn);
                                        @endphp
                                        @if ($tagEn !== '')
                                            <span
                                                class="roles-hub-card__tag"
                                                data-text-de="{{ $tagDe }}"
                                                data-text-en="{{ $tagEn }}"
                                            >{{ $tagEn }}</span>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <h1 class="tools-page-title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h1>
                <p class="tools-page-lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
            </div>
            @if (! empty($roleQuote))
                <div class="roles-quote-slot roles-quote-slot--header">
                    <x-tools.quote-card
                        :quote="$roleQuote['quote']"
                        :attribution="$roleQuote['attribution']"
                    />
                </div>
            @endif
        </header>

        @if (count($ownsEn) > 0 || count($doesNotEn) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-boundaries">
                <h2 id="roles-boundaries" class="roles-detail__section-title" data-i18n="roles.boundariesTitle">Decision boundaries</h2>
                <div class="roles-detail__boundaries">
                    @if (count($ownsEn) > 0)
                        <div class="roles-detail__boundary">
                            <h3 class="roles-detail__boundary-title" data-i18n="roles.ownsTitle">Owns</h3>
                            <ul class="roles-detail__bullets">
                                @foreach ($ownsEn as $i => $lineEn)
                                    @php $lineDe = (string) ($ownsDe[$i] ?? $lineEn); @endphp
                                    <li data-text-de="{{ $lineDe }}" data-text-en="{{ $lineEn }}">{{ $lineEn }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (count($doesNotEn) > 0)
                        <div class="roles-detail__boundary">
                            <h3 class="roles-detail__boundary-title" data-i18n="roles.doesNotTitle">Does not</h3>
                            <ul class="roles-detail__bullets">
                                @foreach ($doesNotEn as $i => $lineEn)
                                    @php $lineDe = (string) ($doesNotDe[$i] ?? $lineEn); @endphp
                                    <li data-text-de="{{ $lineDe }}" data-text-en="{{ $lineEn }}">{{ $lineEn }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if (count($tasks) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-tasks">
                <h2 id="roles-tasks" class="roles-detail__section-title" data-i18n="roles.tasksTitle">Typical tasks</h2>
                <ul class="roles-detail__tasks">
                    @foreach ($tasks as $task)
                        @if (! is_array($task))
                            @continue
                        @endif
                        @php
                            $taskTitleEn = (string) ($task['title']['en'] ?? '');
                            $taskTitleDe = (string) ($task['title']['de'] ?? $taskTitleEn);
                            $taskWhereEn = (string) ($task['where']['en'] ?? '');
                            $taskWhereDe = (string) ($task['where']['de'] ?? $taskWhereEn);
                        @endphp
                        @if ($taskTitleEn === '' && $taskTitleDe === '')
                            @continue
                        @endif
                        <li class="roles-detail__task">
                            <span
                                class="roles-detail__task-title"
                                data-text-de="{{ $taskTitleDe }}"
                                data-text-en="{{ $taskTitleEn }}"
                            >{{ $taskTitleEn !== '' ? $taskTitleEn : $taskTitleDe }}</span>
                            @if ($taskWhereEn !== '' || $taskWhereDe !== '')
                                <span
                                    class="roles-detail__task-where"
                                    data-text-de="{{ $taskWhereDe }}"
                                    data-text-en="{{ $taskWhereEn }}"
                                >{{ $taskWhereEn !== '' ? $taskWhereEn : $taskWhereDe }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if (count($worksWithLinks) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-works-with">
                <h2 id="roles-works-with" class="roles-detail__section-title" data-i18n="roles.worksWithTitle">Works with</h2>
                <ul class="roles-detail__links">
                    @foreach ($worksWithLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                <span class="roles-detail__kind" data-i18n="search.type.role">role</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if (count($relatedBridges) > 0)
            <section class="roles-detail__section roles-detail__section--bridges" aria-labelledby="roles-related-bridges">
                <h2 id="roles-related-bridges" class="roles-detail__section-title" data-i18n="roles.relatedBridgesTitle">Bridge patterns</h2>
                <p class="roles-detail__bridges-lead">
                    <a href="{{ locale_route('roles.index') }}#roles-bridges" data-i18n="roles.relatedBridgesLead">
                        See all bridge profiles on the Roles hub
                    </a>
                </p>
                <div class="roles-bridges__grid roles-bridges__grid--detail">
                    @foreach ($relatedBridges as $bridge)
                        @include('roles::partials.bridge-card', ['bridge' => $bridge, 'compact' => true])
                    @endforeach
                </div>
            </section>
        @endif

        @if (count($pathLinks) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-paths">
                <h2 id="roles-paths" class="roles-detail__section-title" data-i18n="roles.pathsTitle">Recommended learning paths</h2>
                <ul class="roles-detail__links">
                    @foreach ($pathLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span class="roles-detail__link-main">
                                    <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                    @if (($link['why']['en'] ?? '') !== '' || ($link['why']['de'] ?? '') !== '')
                                        <span
                                            class="roles-detail__why"
                                            data-text-de="{{ $link['why']['de'] }}"
                                            data-text-en="{{ $link['why']['en'] }}"
                                        >{{ $link['why']['en'] !== '' ? $link['why']['en'] : $link['why']['de'] }}</span>
                                    @endif
                                </span>
                                <span class="roles-detail__kind" data-i18n="search.type.path">path</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if (count($toolLinks) > 0)
            <section class="roles-detail__section" aria-labelledby="roles-tools">
                <h2 id="roles-tools" class="roles-detail__section-title" data-i18n="roles.toolsTitle">Recommended tools</h2>
                <ul class="roles-detail__links">
                    @foreach ($toolLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span class="roles-detail__link-main">
                                    <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                    @if (($link['why']['en'] ?? '') !== '' || ($link['why']['de'] ?? '') !== '')
                                        <span
                                            class="roles-detail__why"
                                            data-text-de="{{ $link['why']['de'] }}"
                                            data-text-en="{{ $link['why']['en'] }}"
                                        >{{ $link['why']['en'] !== '' ? $link['why']['en'] : $link['why']['de'] }}</span>
                                    @endif
                                </span>
                                <span class="roles-detail__kind" data-i18n="search.type.tool">tool</span>
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

        @if ($glossaryLink !== null)
            <section class="roles-detail__section roles-detail__section--glossary" aria-labelledby="roles-glossary">
                <h2 id="roles-glossary" class="roles-detail__section-title" data-i18n="roles.glossaryTitle">Glossary</h2>
                <p class="roles-detail__glossary-lead" data-i18n="roles.glossaryLead">
                    Short definition for shared language — not the starting point for this role.
                </p>
                <ul class="roles-detail__links">
                    <li>
                        <a href="{{ $glossaryLink['href'] }}">
                            <span data-text-de="{{ $glossaryLink['label']['de'] }}" data-text-en="{{ $glossaryLink['label']['en'] }}">{{ $glossaryLink['label']['en'] }}</span>
                            <span class="roles-detail__kind" data-i18n="search.type.glossary">glossary</span>
                        </a>
                    </li>
                </ul>
            </section>
        @endif
    </div>
@endsection
