@props([
    'name' => 'locale',
    'active' => 'en',
])

@php
    $uid = 'admin-locale-'.preg_replace('/[^a-z0-9-]+/i', '-', $name).'-'.uniqid();
@endphp

<div {{ $attributes->class(['admin-hub__locale-tabs']) }} data-admin-locale-tabs data-admin-tabs>
    <div class="admin-hub__tablist" role="tablist" aria-label="Locale">
        <button type="button" class="admin-hub__tab {{ $active === 'de' ? 'is-active' : '' }}" role="tab" id="{{ $uid }}-tab-de" data-tab-id="{{ $uid }}-panel-de" aria-controls="{{ $uid }}-panel-de" aria-selected="{{ $active === 'de' ? 'true' : 'false' }}" tabindex="{{ $active === 'de' ? '0' : '-1' }}">DE</button>
        <button type="button" class="admin-hub__tab {{ $active === 'en' ? 'is-active' : '' }}" role="tab" id="{{ $uid }}-tab-en" data-tab-id="{{ $uid }}-panel-en" aria-controls="{{ $uid }}-panel-en" aria-selected="{{ $active === 'en' ? 'true' : 'false' }}" tabindex="{{ $active === 'en' ? '0' : '-1' }}">EN</button>
    </div>
    <div class="admin-hub__tab-panels">
        <div class="admin-hub__tab-panel" role="tabpanel" id="{{ $uid }}-panel-de" data-admin-tab-panel="{{ $uid }}-panel-de" @if ($active !== 'de') hidden @endif>
            {{ $de ?? '' }}
        </div>
        <div class="admin-hub__tab-panel" role="tabpanel" id="{{ $uid }}-panel-en" data-admin-tab-panel="{{ $uid }}-panel-en" @if ($active !== 'en') hidden @endif>
            {{ $en ?? '' }}
        </div>
    </div>
</div>
