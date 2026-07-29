{{-- DQ packs + workspace save — for tool-page-header save slot (no nested panel card). --}}
@props([
    'dqWorkspaceConfig' => null,
])
@php
    $configJson = $dqWorkspaceConfig
        ? json_encode($dqWorkspaceConfig, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP)
        : '{"enabled":false}';
@endphp
<div data-dq-workspace-config='{!! $configJson !!}' hidden></div>
<div data-dq-packs-panel class="dq-packs-panel">
    <p class="governance-hub__eyebrow" data-i18n="dqPacks.title">Templates &amp; workspace</p>

    <div class="dq-packs-panel__grid">
        <x-tools.field label-key="dqPacks.region">
            <select class="pii-policy-input" data-dq-region></select>
        </x-tools.field>
    </div>

    <p class="tools-panel-meta dq-packs-panel__lead" data-i18n="dqPacks.packsLead">Apply a starter pack for the selected region.</p>
    <div class="dq-packs-panel__packs" data-dq-packs></div>

    <div class="dq-packs-panel__section">
        <p class="tools-panel-meta" data-dq-workspace-status data-i18n="dqPacks.workspace.checking">Checking workspace…</p>
        <x-tools.field label-key="dqPacks.workspace.name">
            <input type="text" class="pii-policy-input" data-dq-save-name maxlength="120" placeholder="" />
        </x-tools.field>
        <div class="dq-packs-panel__actions">
            <button type="button" class="tools-btn tools-btn--primary" data-dq-save-btn data-i18n="dqPacks.workspace.save">Save to workspace</button>
        </div>
        <div class="dq-packs-panel__saved" data-dq-saved-list></div>
    </div>
</div>
