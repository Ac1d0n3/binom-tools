@props([
    'titleKey',
    'toolId' => null,
    'appId',
    'leadKey' => null,
])

<div class="tools-content tools-content--wide">
    <h1 class="tools-page-title" data-i18n="{{ $titleKey }}"></h1>

    @if ($leadKey)
        <p class="tools-page-lead" data-i18n="{{ $leadKey }}"></p>
    @endif

    @if ($toolId)
        <x-tools.workflow-nav :tool-id="$toolId" />
    @endif

    <div class="pii-policy-generator" id="{{ $appId }}">
        {{ $slot }}
    </div>
</div>
