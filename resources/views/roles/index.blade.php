@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Roles — '.config('app.name'))
@section('meta_description', 'Data governance roles — steward, owner, architect, custodian, and consumer — with links to glossary, stories, learning paths, and tools.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--roles" data-roles-hub>
        <div class="tools-overview-sticky-header roles-hub-sticky">
            <h1 class="tools-page-title" data-i18n="roles.indexTitle">Roles</h1>
            <p class="tools-page-lead" data-hub-lead data-i18n="roles.indexLead">
                Governance personas with shared vocabulary, stories, learning paths, and tools.
            </p>
        </div>

        <div class="tools-overview-scroll">
            <div class="roles-hub-grid" role="list">
                @foreach ($roles as $role)
                    @php
                        $id = (string) ($role['id'] ?? '');
                        $icon = (string) ($role['icon'] ?? 'fa-user');
                        $titleEn = (string) ($role['title']['en'] ?? $id);
                        $titleDe = (string) ($role['title']['de'] ?? $titleEn);
                        $leadEn = (string) ($role['lead']['en'] ?? '');
                        $leadDe = (string) ($role['lead']['de'] ?? $leadEn);
                    @endphp
                    <a
                        href="{{ locale_route('roles.show', ['slug' => $id]) }}"
                        class="roles-hub-card"
                        role="listitem"
                        data-roles-card
                    >
                        <span class="roles-hub-card__icon-wrap" aria-hidden="true">
                            <i class="fa-solid {{ $icon }} roles-hub-card__icon"></i>
                        </span>
                        <span class="roles-hub-card__body">
                            <span class="roles-hub-card__title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</span>
                            <span class="roles-hub-card__lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</span>
                        </span>
                        <span class="roles-hub-card__cta">
                            <span data-i18n="roles.cardCta">Explore role</span>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
