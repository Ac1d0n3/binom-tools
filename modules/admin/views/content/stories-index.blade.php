@extends('admin::layouts.shell')

@section('title', 'Stories — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">Stories</h1>
        <x-admin.help id="stories-admin">
            <p data-text-de="Markdown unter content/stories — Speichern ohne Git. Bilder landen in public/images/playbooks (+ WebP)." data-text-en="Markdown under content/stories — save without git. Images go to public/images/playbooks (+ WebP).">Markdown under content/stories — save without git. Images go to public/images/playbooks (+ WebP).</p>
        </x-admin.help>
        <x-admin.page-header title="Stories" titleDe="Stories" titleEn="Stories">
            <x-slot:actions>
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('admin.stories.create') }}">New story</a>
            </x-slot:actions>
        </x-admin.page-header>
        <div class="sp-list">
            @foreach ($stories as $story)
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong>{{ $story['slug'] }}</strong>
                        <span class="admin-hub__meta">DE {{ $story['de'] ? '✓' : '—' }} · EN {{ $story['en'] ? '✓' : '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.stories.edit', ['slug' => $story['slug']]) }}">Edit</a>
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('playbooks.show', ['slug' => $story['slug']]) }}">View</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
