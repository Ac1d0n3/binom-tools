@extends('layouts.tools')

@section('title', ($content['title'] ?? 'Privacy') . ' — ' . config('app.name'))

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title">{{ $content['title'] ?? 'Privacy' }}</h1>

        @if (! empty($content['summary']))
            <p class="tools-page-lead">{{ $content['summary'] }}</p>
        @endif

        @foreach ($content['sections'] ?? [] as $section)
            <section class="tools-section">
                <h2 class="tools-section__title">{{ $section['title'] ?? '' }}</h2>
                <p class="tools-impressum-body">{!! nl2br(e($section['body'] ?? '')) !!}</p>
            </section>
        @endforeach
    </div>
@endsection
