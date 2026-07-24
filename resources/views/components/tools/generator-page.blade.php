@props([
    'titleKey',
    'toolId' => null,
    'appId',
    'leadKey' => null,
    'titleBadge' => null,
])

<div class="tools-content tools-content--wide">
    <div class="tools-page-title-row">
        <h1 class="tools-page-title" data-i18n="{{ $titleKey }}"></h1>
        @if ($titleBadge)
            <span class="tools-page-title-badge">{{ $titleBadge }}</span>
        @endif
    </div>

    @if ($leadKey)
        <p class="tools-page-lead" data-i18n="{{ $leadKey }}"></p>
    @endif

    <div class="tools-page-stack">
        @if ($toolId)
            <x-tools.workflow-nav :tool-id="$toolId" />
        @endif

        <div class="pii-policy-generator" id="{{ $appId }}" @if ($toolId) data-tool-id="{{ $toolId }}" @endif>
            {{ $slot }}
        </div>
    </div>
</div>
