@extends('profile::layouts.shell')

@section('title', 'Read stories — ' . config('app.name'))

@section('profile_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title" data-text-de="Gelesene Stories" data-text-en="Read stories">Read stories</h1>
        <x-admin.help id="reads">
            <p data-text-de="Dein persönlicher Lesestand aus dem Read-State-Store." data-text-en="Your personal read state from the read-state store.">Your personal read state from the read-state store.</p>
        </x-admin.help>
        <div class="sp-list">
            @forelse ($items as $item)
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong>{{ $item['slug'] }}</strong>
                        @if (!empty($item['readAt']))
                            <span class="admin-hub__meta">{{ date('Y-m-d H:i', $item['readAt']) }}</span>
                        @endif
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('playbooks.show', ['slug' => $item['slug']]) }}">Open</a>
                    </div>
                </div>
            @empty
                <p class="tools-page-lead">No read stories yet.</p>
            @endforelse
        </div>
    </div>
@endsection
