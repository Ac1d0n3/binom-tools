@extends('layouts.tools')

@php
    $titleEn = $tool['title']['en'] ?? $toolId;
    $titleDe = $tool['title']['de'] ?? $titleEn;
    $leadEn = $tool['lead']['en'] ?? '';
    $leadDe = $tool['lead']['de'] ?? $leadEn;
    $questionEn = $tool['question']['en'] ?? '';
    $questionDe = $tool['question']['de'] ?? $questionEn;
@endphp

@section('title', $titleEn . ' - ' . config('app.name'))
@section('meta_description', $leadEn)

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    <div class="tools-content governance-advisory-tool">
        <header class="governance-advisory-tool__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Governance Tool" data-text-en="Governance tool">Governance tool</p>
                <h1 class="tools-page-title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h1>
                <p class="tools-page-lead" data-hub-lead data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
            </div>
            <div class="governance-advisory-tool__question">
                @if (! empty($tool['icon']))
                    <i class="fa-solid {{ $tool['icon'] }}" aria-hidden="true"></i>
                @endif
                <span data-text-de="Entscheidungsfrage" data-text-en="Decision question">Decision question</span>
                <strong data-text-de="{{ $questionDe }}" data-text-en="{{ $questionEn }}">{{ $questionEn }}</strong>
            </div>
        </header>

        <div class="governance-advisory-tool__grid">
            <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-helps">
                <h2 id="{{ $toolId }}-helps" data-text-de="Wobei hilft das Tool?" data-text-en="What does this tool help decide?">What does this tool help decide?</h2>
                <ul>
                    @foreach ($tool['helps'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-inputs">
                <h2 id="{{ $toolId }}-inputs" data-text-de="Inputs" data-text-en="Inputs">Inputs</h2>
                <div class="governance-advisory-tool__chips">
                    @foreach ($tool['inputs'] as $item)
                        <span>{{ $item }}</span>
                    @endforeach
                </div>
            </section>

            <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-outputs">
                <h2 id="{{ $toolId }}-outputs" data-text-de="Outputs" data-text-en="Outputs">Outputs</h2>
                <div class="governance-advisory-tool__chips governance-advisory-tool__chips--accent">
                    @foreach ($tool['outputs'] as $item)
                        <span>{{ $item }}</span>
                    @endforeach
                </div>
            </section>
        </div>

        <section class="governance-advisory-tool__panel governance-advisory-tool__template" aria-labelledby="{{ $toolId }}-template">
            <h2 id="{{ $toolId }}-template" data-text-de="Erfassungsstruktur" data-text-en="Capture structure">Capture structure</h2>
            <p data-text-de="Diese Felder sind der erste saubere Stand fuer Workshop, Export oder spaetere Generator-Logik." data-text-en="These fields are the first clean structure for a workshop, export, or later generator logic.">These fields are the first clean structure for a workshop, export, or later generator logic.</p>
            <ol>
                @foreach ($tool['template'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ol>
        </section>

        <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-next">
            <h2 id="{{ $toolId }}-next" data-text-de="Naechster Schritt" data-text-en="Next step">Next step</h2>
            <div class="governance-advisory-tool__links">
                @foreach ($tool['links'] as $link)
                    @if (\Illuminate\Support\Facades\Route::has($link['route']))
                        <a href="{{ locale_route($link['route']) }}">
                            <span>{{ $link['label'] }}</span>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    @endif
                @endforeach
                <a href="{{ locale_route('governance.index') }}">
                    <span data-text-de="Zurueck zum Governance Hub" data-text-en="Back to Governance Hub">Back to Governance Hub</span>
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>
    </div>
@endsection
