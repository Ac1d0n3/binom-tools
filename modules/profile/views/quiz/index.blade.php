@extends('profile::layouts.shell')

@section('title', 'Quiz results — ' . config('app.name'))

@section('profile_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title" data-text-de="Quiz-Ergebnisse" data-text-en="Quiz results">Quiz results</h1>
        <x-admin.help id="quiz">
            <p data-text-de="Deine Glossary-Quiz-Versuche." data-text-en="Your glossary quiz attempts.">Your glossary quiz attempts.</p>
        </x-admin.help>
        <ul class="sp-list" style="list-style:none;padding:0">
            <li class="sp-list__row"><div class="sp-list__identity">Best score: <strong>{{ (int) ($results['bestScore'] ?? 0) }} / {{ (int) ($results['bestTotal'] ?? 0) }}</strong></div></li>
            <li class="sp-list__row"><div class="sp-list__identity">Attempts: <strong>{{ (int) ($results['attemptCount'] ?? 0) }}</strong></div></li>
        </ul>
        <div class="sp-list">
            @foreach (array_reverse($results['attempts'] ?? []) as $attempt)
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong>{{ $attempt['score'] ?? 0 }} / {{ $attempt['total'] ?? 0 }}</strong>
                        <span class="admin-hub__meta">{{ $attempt['at'] ?? '' }} · {{ $attempt['mode'] ?? 'mixed' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <p style="margin-top:1rem"><a class="tools-btn" href="{{ locale_route('glossary.index', ['quiz' => 1]) }}">Open quiz</a></p>
    </div>
@endsection
