@props([
    'id' => 'default',
    'titleDe' => 'Hilfe',
    'titleEn' => 'Help',
])

<div class="admin-hub__help-root" data-admin-help-root>
    <div class="admin-hub__toolbar">
        <button
            type="button"
            class="tools-btn tools-btn--ghost tools-btn--small admin-hub__help-toggle"
            data-admin-help-toggle
            aria-expanded="true"
            aria-controls="admin-help-{{ $id }}"
        >
            <span data-admin-help-hide data-text-de="Hilfe ausblenden" data-text-en="Hide help">Hide help</span>
            <span data-admin-help-show hidden data-text-de="Hilfe einblenden" data-text-en="Show help">Show help</span>
        </button>
    </div>
    <aside
        id="admin-help-{{ $id }}"
        class="admin-hub__help"
        data-admin-help="{{ $id }}"
        aria-label="{{ $titleEn }}"
    >
        <strong data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</strong>
        <div class="admin-hub__help-body">
            {{ $slot }}
        </div>
    </aside>
</div>
