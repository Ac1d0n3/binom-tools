{{-- DQ packs + workspace save — tool-page-header save slot only. --}}
@props([
    'dqWorkspaceConfig' => null,
])
@php
    $configJson = $dqWorkspaceConfig
        ? json_encode($dqWorkspaceConfig, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP)
        : '{"enabled":false}';
    $regions = [
        'DE' => ['de' => 'Deutschland', 'en' => 'Germany'],
        'AT' => ['de' => 'Österreich', 'en' => 'Austria'],
        'CH' => ['de' => 'Schweiz', 'en' => 'Switzerland'],
        'NL' => ['de' => 'Niederlande', 'en' => 'Netherlands'],
        'GB' => ['de' => 'Vereinigtes Königreich', 'en' => 'United Kingdom'],
        'US' => ['de' => 'USA', 'en' => 'United States'],
        'FR' => ['de' => 'Frankreich', 'en' => 'France'],
    ];
    $packs = [
        'pii-detection' => ['de' => 'PII Detection', 'en' => 'PII Detection'],
        'address-format' => ['de' => 'Adressformat', 'en' => 'Address format'],
        'status-enum' => ['de' => 'Status-Enum', 'en' => 'Status enum'],
        'amount-range' => ['de' => 'Betrags-Range', 'en' => 'Amount range'],
        'freshness-volume' => ['de' => 'Freshness & Volumen', 'en' => 'Freshness & volume'],
        'unique-business-key' => ['de' => 'Unique Business Key', 'en' => 'Unique business key'],
    ];
@endphp
<div data-dq-workspace-config='{!! $configJson !!}' hidden></div>
<div data-dq-packs-panel class="dq-packs-panel qlik-set-appbar">
    <p class="governance-hub__eyebrow" data-i18n="dqPacks.title">Vorlagen &amp; Workspace</p>

    <label class="pii-policy-field dq-packs-panel__field">
        <span data-i18n="dqPacks.region">Region</span>
        <select class="pii-policy-input" data-dq-region>
            @foreach ($regions as $id => $labels)
                <option value="{{ $id }}" @selected($id === 'DE') data-text-de="{{ $labels['de'] }}" data-text-en="{{ $labels['en'] }}">{{ $labels['en'] }}</option>
            @endforeach
        </select>
    </label>

    <p class="dq-packs-panel__lead" data-i18n="dqPacks.packsLead">Starter-Pack für die gewählte Region anwenden (merge, kein Reset).</p>
    <div class="dq-packs-panel__packs" data-dq-packs>
        @foreach ($packs as $id => $labels)
            <button
                type="button"
                class="tools-btn tools-btn--ghost tools-btn--sm"
                data-dq-apply-pack="{{ $id }}"
                data-text-de="{{ $labels['de'] }}"
                data-text-en="{{ $labels['en'] }}"
            >{{ $labels['en'] }}</button>
        @endforeach
    </div>

    <div class="dq-packs-panel__workspace">
        <p class="dq-packs-panel__status" data-dq-workspace-status data-i18n="dqPacks.workspace.checking">Workspace wird geprüft…</p>
        <label class="pii-policy-field dq-packs-panel__field">
            <span data-i18n="dqPacks.workspace.name">Name speichern</span>
            <input type="text" class="pii-policy-input" data-dq-save-name maxlength="120" />
        </label>
        <div class="dq-packs-panel__actions">
            <button type="button" class="tools-btn tools-btn--primary" data-dq-save-btn data-i18n="dqPacks.workspace.save">Im Workspace speichern</button>
        </div>
        <div class="dq-packs-panel__saved" data-dq-saved-list></div>
    </div>
</div>
