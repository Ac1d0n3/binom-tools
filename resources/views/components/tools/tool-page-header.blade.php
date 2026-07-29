@props([
    'toolId' => null,
    'titleKey' => null,
    'leadKey' => null,
    'titleDe' => null,
    'titleEn' => null,
    'leadDe' => null,
    'leadEn' => null,
    'eyebrowDe' => null,
    'eyebrowEn' => null,
    'optionTabs' => [],
    'workspaceLabelDe' => 'Workspace',
    'workspaceLabelEn' => 'Workspace',
    'id' => null,
])

@php
    $headerId = $id ?: ($toolId ?: 'tool');
    $hasFlow = is_string($toolId)
        && $toolId !== ''
        && filled(\App\Support\ToolWorkflow::flowchartStepsForToolId($toolId));
    $tabs = array_values(array_filter(
        is_array($optionTabs) ? $optionTabs : [],
        static fn ($tab): bool => is_string($tab) && $tab !== ''
    ));
    $hasOptions = count($tabs) > 0;
    $tabMeta = [
        'help' => [
            'icon' => 'fa-circle-question',
            'de' => 'Hilfe',
            'en' => 'Help',
        ],
        'overview' => [
            'icon' => 'fa-table-columns',
            'de' => 'Überblick',
            'en' => 'Overview',
        ],
        'structure' => [
            'icon' => 'fa-list-check',
            'de' => 'Struktur',
            'en' => 'Structure',
        ],
        'workspace' => [
            'icon' => 'fa-folder-tree',
            'de' => $workspaceLabelDe,
            'en' => $workspaceLabelEn,
        ],
    ];
    $titleText = $titleEn ?: ($titleDe ?: '');
    $leadText = $leadEn ?: ($leadDe ?: '');
    $eyebrowText = $eyebrowEn ?: ($eyebrowDe ?: '');
@endphp

<header
    {{ $attributes->class(['tools-tool-header']) }}
    data-tool-page-header
    @if ($toolId) data-tool-id="{{ $toolId }}" @endif
>
    <div class="tools-tool-header__main">
        @if ($eyebrowDe || $eyebrowEn || $eyebrowText !== '')
            <p
                class="governance-hub__eyebrow"
                @if ($eyebrowDe) data-text-de="{{ $eyebrowDe }}" @endif
                @if ($eyebrowEn) data-text-en="{{ $eyebrowEn }}" @endif
            >{{ $eyebrowText !== '' ? $eyebrowText : $eyebrowEn }}</p>
        @endif

        <h1
            class="tools-page-title"
            @if ($titleKey) data-i18n="{{ $titleKey }}" @endif
            @if ($titleDe) data-text-de="{{ $titleDe }}" @endif
            @if ($titleEn) data-text-en="{{ $titleEn }}" @endif
        >{{ $titleText }}</h1>

        @if ($leadKey || $leadDe || $leadEn || $leadText !== '')
            <p
                class="tools-page-lead"
                data-hub-lead
                @if ($leadKey) data-i18n="{{ $leadKey }}" @endif
                @if ($leadDe) data-text-de="{{ $leadDe }}" @endif
                @if ($leadEn) data-text-en="{{ $leadEn }}" @endif
            >{{ $leadText }}</p>
        @endif

        <div class="tools-tool-header__actions">
            @if ($hasFlow)
                <button
                    type="button"
                    class="governance-hub__button tools-tool-header__button tools-tool-header__button--neutral"
                    data-tool-header-flow-toggle
                    aria-controls="{{ $headerId }}-flow-panel"
                    aria-expanded="false"
                >
                    <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    <span data-text-de="Flow" data-text-en="Flow">Flow</span>
                </button>
            @endif

            @if ($hasOptions)
                <button
                    type="button"
                    class="governance-hub__button tools-tool-header__button tools-tool-header__button--neutral"
                    data-tool-header-options-toggle
                    aria-controls="{{ $headerId }}-options-drawer"
                    aria-expanded="false"
                >
                    <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                    <span data-text-de="Optionen" data-text-en="Options">Options</span>
                </button>
            @endif

            {{ $actions ?? '' }}
        </div>
    </div>

    @isset($question)
        <div class="tools-tool-header__question">
            {{ $question }}
        </div>
    @endisset

    @if ($hasFlow)
        <section
            class="tools-tool-header__flow"
            id="{{ $headerId }}-flow-panel"
            data-tool-header-flow-panel
            aria-label="Workflow"
            hidden
        >
            <x-tools.workflow-nav :tool-id="$toolId" />
        </section>
    @endif

    @if ($hasOptions)
        <section
            class="tools-tool-header__drawer"
            id="{{ $headerId }}-options-drawer"
            data-tool-header-options-drawer
            aria-label="Tool options"
            hidden
        >
            <nav class="governance-hub__panel-tabs tools-tool-header__drawer-tabs" aria-label="Tool options tabs" role="tablist">
                @foreach ($tabs as $index => $tab)
                    @php
                        $meta = $tabMeta[$tab] ?? [
                            'icon' => 'fa-circle',
                            'de' => $tab,
                            'en' => $tab,
                        ];
                        $panelId = $headerId.'-drawer-'.$tab;
                        $isFirst = $index === 0;
                    @endphp
                    <button
                        type="button"
                        class="governance-hub__panel-tab{{ $isFirst ? ' governance-hub__panel-tab--active' : '' }}"
                        id="{{ $headerId }}-drawer-tab-{{ $tab }}"
                        data-tool-header-panel-toggle="{{ $panelId }}"
                        role="tab"
                        aria-controls="{{ $panelId }}"
                        aria-selected="{{ $isFirst ? 'true' : 'false' }}"
                        @if (! $isFirst) tabindex="-1" @endif
                    >
                        <i class="fa-solid {{ $meta['icon'] }}" aria-hidden="true"></i>
                        <span data-text-de="{{ $meta['de'] }}" data-text-en="{{ $meta['en'] }}">{{ $meta['en'] }}</span>
                    </button>
                @endforeach
            </nav>

            <div class="tools-tool-header__drawer-stage">
                @foreach ($tabs as $index => $tab)
                    @php
                        $panelId = $headerId.'-drawer-'.$tab;
                        $isFirst = $index === 0;
                        $slotContent = match ($tab) {
                            'help' => $help ?? null,
                            'overview' => $overview ?? null,
                            'structure' => $structure ?? null,
                            'workspace' => $workspace ?? null,
                            default => null,
                        };
                    @endphp
                    <article
                        class="tools-tool-header__drawer-panel{{ $tab === 'help' ? ' governance-advisor__helpbox' : '' }}"
                        id="{{ $panelId }}"
                        aria-labelledby="{{ $headerId }}-drawer-tab-{{ $tab }}"
                        data-tool-header-panel
                        role="tabpanel"
                        @if ($tab === 'help') data-tool-help @endif
                        @if (! $isFirst) hidden @endif
                    >
                        @if ($slotContent)
                            {{ $slotContent }}
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</header>
