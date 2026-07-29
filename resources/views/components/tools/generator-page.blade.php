@props([
    'titleKey',
    'toolId' => null,
    'appId',
    'leadKey' => null,
    'titleBadge' => null,
    'sharedHeader' => false,
    'optionTabs' => null,
    'eyebrowDe' => null,
    'eyebrowEn' => null,
])

@php
    $useSharedHeader = (bool) $sharedHeader;
    $resolvedOptionTabs = $optionTabs;
    if ($useSharedHeader && $resolvedOptionTabs === null) {
        $resolvedOptionTabs = isset($help) ? ['help'] : [];
    }
    if (! is_array($resolvedOptionTabs)) {
        $resolvedOptionTabs = [];
    }
    $resolvedEyebrowDe = $eyebrowDe;
    $resolvedEyebrowEn = $eyebrowEn;
    if ($useSharedHeader && $resolvedEyebrowDe === null && $resolvedEyebrowEn === null) {
        $resolvedEyebrowDe = 'Governance Tool';
        $resolvedEyebrowEn = 'Governance tool';
    }
@endphp

<div class="tools-content tools-content--wide">
    @if ($useSharedHeader)
        <x-tools.tool-page-header
            :tool-id="$toolId"
            :title-key="$titleKey"
            :lead-key="$leadKey"
            :option-tabs="$resolvedOptionTabs"
            :eyebrow-de="$resolvedEyebrowDe"
            :eyebrow-en="$resolvedEyebrowEn"
        >
            @isset($actions)
                <x-slot:actions>{{ $actions }}</x-slot:actions>
            @endisset

            @isset($help)
                <x-slot:help>{{ $help }}</x-slot:help>
            @endisset

            @isset($overview)
                <x-slot:overview>{{ $overview }}</x-slot:overview>
            @endisset

            @isset($structure)
                <x-slot:structure>{{ $structure }}</x-slot:structure>
            @endisset

            @isset($workspace)
                <x-slot:workspace>{{ $workspace }}</x-slot:workspace>
            @endisset
        </x-tools.tool-page-header>
    @else
        <div class="tools-page-title-row">
            <h1 class="tools-page-title" data-i18n="{{ $titleKey }}"></h1>
            @if ($titleBadge)
                <span class="tools-page-title-badge">{{ $titleBadge }}</span>
            @endif
        </div>

        @if ($leadKey)
            <p class="tools-page-lead" data-hub-lead data-i18n="{{ $leadKey }}"></p>
        @endif
    @endif

    <div class="tools-page-stack">
        @if (! $useSharedHeader && $toolId)
            <x-tools.workflow-nav :tool-id="$toolId" />
        @endif

        <div class="pii-policy-generator" id="{{ $appId }}" @if ($toolId) data-tool-id="{{ $toolId }}" @endif>
            {{ $slot }}
        </div>
    </div>
</div>
