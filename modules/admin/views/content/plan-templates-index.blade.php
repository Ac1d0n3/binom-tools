@extends('admin::layouts.shell')

@section('title', 'Plan templates — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">Plan templates</h1>
        <x-admin.help id="plan-templates">
            <p data-text-de="Markdown unter content/sprint-plans mit type: sprint-plan — nie DB." data-text-en="Markdown under content/sprint-plans with type: sprint-plan — never DB.">Markdown under content/sprint-plans with type: sprint-plan — never DB.</p>
        </x-admin.help>
        <x-admin.page-header title="Templates" titleDe="Templates" titleEn="Templates">
            <x-slot:actions>
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('admin.plan-templates.create') }}">New template</a>
            </x-slot:actions>
        </x-admin.page-header>
        <div class="sp-list">
            @foreach ($templates as $tpl)
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong>{{ $tpl['slug'] }}</strong>
                        <span class="admin-hub__meta">DE {{ $tpl['de'] ? '✓' : '—' }} · EN {{ $tpl['en'] ? '✓' : '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.plan-templates.edit', ['slug' => $tpl['slug']]) }}">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
