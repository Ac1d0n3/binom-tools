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
                        $focusEn = is_array($role['focus']['en'] ?? null) ? array_values(array_filter($role['focus']['en'], 'is_string')) : [];
                        $focusDe = is_array($role['focus']['de'] ?? null) ? array_values(array_filter($role['focus']['de'], 'is_string')) : $focusEn;
                        $leadEn = (string) ($role['lead']['en'] ?? '');
                        $leadDe = (string) ($role['lead']['de'] ?? $leadEn);
                        $focusCount = max(count($focusEn), count($focusDe));
                    @endphp
                    <a
                        href="{{ locale_route('roles.show', ['slug' => $id]) }}"
                        class="roles-hub-card"
                        role="listitem"
                        data-roles-card
                    >
                        <span class="roles-hub-card__header">
                            <span class="roles-hub-card__icon-wrap" aria-hidden="true">
                                <i class="fa-solid {{ $icon }} roles-hub-card__icon"></i>
                            </span>
                            <span class="roles-hub-card__title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</span>
                        </span>
                        @if ($focusCount > 0)
                            <span class="roles-hub-card__purpose">
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
                            </span>
                        @endif
                        <span class="roles-hub-card__lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</span>
                        <span class="roles-hub-card__cta">
                            <span data-i18n="roles.cardCta">Explore role</span>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </span>
                    </a>
                @endforeach
            </div>

            @php
                $bridges = is_array($bridges ?? null) ? $bridges : [];
            @endphp
            @if (count($bridges) > 0)
                <section class="roles-bridges" id="roles-bridges" aria-labelledby="roles-bridges-title">
                    <header class="roles-bridges__header">
                        <h2 id="roles-bridges-title" class="roles-bridges__title" data-i18n="roles.bridgesTitle">Bridge profiles</h2>
                        <p class="roles-bridges__lead" data-i18n="roles.bridgesLead">
                            The six roles are decision rights. Bridges are typical multi-hat patterns — not extra governance personas.
                        </p>
                    </header>
                    <div class="roles-bridges__grid">
                        @foreach ($bridges as $bridge)
                            @include('roles.partials.bridge-card', ['bridge' => $bridge, 'compact' => false])
                        @endforeach
                        @if (! empty($roleQuote))
                            <div class="roles-quote-slot roles-quote-slot--bridges">
                                <x-tools.quote-card
                                    :quote="$roleQuote['quote']"
                                    :attribution="$roleQuote['attribution']"
                                />
                            </div>
                        @endif
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
