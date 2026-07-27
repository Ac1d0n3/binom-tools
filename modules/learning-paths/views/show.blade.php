@extends('foundations.layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $id = (string) ($item['id'] ?? '');
    $titleEn = (string) ($item['title']['en'] ?? $id);
    $titleDe = (string) ($item['title']['de'] ?? $titleEn);
    $leadEn = (string) ($item['lead']['en'] ?? '');
    $leadDe = (string) ($item['lead']['de'] ?? $leadEn);
    $audienceId = is_string($item['audienceId'] ?? null) ? $item['audienceId'] : '';
    $audienceEn = (string) ($item['audience']['en'] ?? ($audiences[$audienceId]['en'] ?? $audienceId));
    $audienceDe = (string) ($item['audience']['de'] ?? ($audiences[$audienceId]['de'] ?? $audienceEn));
    $durationEn = (string) ($item['duration']['en'] ?? '');
    $durationDe = (string) ($item['duration']['de'] ?? $durationEn);
@endphp

@section('title', $titleEn.' — Learning Paths — '.config('app.name'))
@section('meta_description', $leadEn !== '' ? $leadEn : 'Guided learning path: '.$titleEn)

@section('content')
    <div class="tools-content tools-content--learning-path-detail">
        <nav class="learning-path-detail__nav" aria-label="Breadcrumb">
            <a href="{{ locale_route('learning-paths.index') }}" data-i18n="learningPaths.backToIndex">← Learning Paths</a>
        </nav>

        <header class="learning-path-detail__header">
            <p class="learning-path-detail__meta">
                <span data-text-de="{{ $audienceDe }}" data-text-en="{{ $audienceEn }}">{{ $audienceEn }}</span>
                @if ($durationEn !== '')
                    <span aria-hidden="true">·</span>
                    <span data-text-de="{{ $durationDe }}" data-text-en="{{ $durationEn }}">{{ $durationEn }}</span>
                @endif
            </p>
            <h1 class="tools-page-title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h1>
            <p class="tools-page-lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
            @if (! empty($sprintPlanHref))
                <p class="learning-path-detail__sprint-cta">
                    <a href="{{ $sprintPlanHref }}" class="tools-button tools-button--primary" data-i18n="learningPaths.startSprintPlan">
                        Start as sprint plan
                    </a>
                    <span class="learning-path-detail__sprint-hint" data-i18n="learningPaths.startSprintPlanHint">
                        Opens the Sprint Planner with a matching template.
                    </span>
                </p>
            @endif
        </header>

        @if (count($relatedRoles ?? []) > 0)
            <section class="learning-path-detail__roles" aria-labelledby="learning-path-related-roles">
                <h2 id="learning-path-related-roles" class="learning-path-detail__section-title" data-i18n="learningPaths.relatedRolesTitle">
                    Related roles
                </h2>
                <ul class="learning-path-detail__links">
                    @foreach ($relatedRoles as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                <span class="learning-path-detail__link-kind" data-i18n="search.type.role">role</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        <ol class="learning-path-detail__steps">
            @foreach ($steps as $index => $step)
                <li class="learning-path-detail__step">
                    <h2 class="learning-path-detail__step-title" data-text-de="{{ $step['title']['de'] }}" data-text-en="{{ $step['title']['en'] }}">
                        {{ $step['title']['en'] }}
                    </h2>
                    @if (($step['lead']['en'] ?? '') !== '' || ($step['lead']['de'] ?? '') !== '')
                        <p class="learning-path-detail__step-lead" data-text-de="{{ $step['lead']['de'] }}" data-text-en="{{ $step['lead']['en'] }}">
                            {{ $step['lead']['en'] }}
                        </p>
                    @endif
                    @if (count($step['links']) > 0)
                        <ul class="learning-path-detail__links">
                            @foreach ($step['links'] as $link)
                                <li>
                                    <a
                                        href="{{ $link['href'] }}"
                                        @if (! empty($link['external'])) target="_blank" rel="noopener noreferrer" @endif
                                    >
                                        <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                        <span class="learning-path-detail__link-kind" data-i18n="search.type.{{ $link['kind'] }}">{{ $link['kind'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
@endsection
