@props([
    'id',
    'title' => '',
    'titleDe' => null,
    'titleEn' => null,
    'wide' => false,
])

<dialog
    id="{{ $id }}"
    {{ $attributes->class(['bn-shared-modal', 'admin-hub__modal', $wide ? 'admin-hub__modal--wide' : '']) }}
    data-shared-modal
    data-admin-modal
    aria-labelledby="{{ $id }}-title"
>
    <div class="admin-hub__modal-sheet">
        <header class="admin-hub__modal-header">
            <h2 id="{{ $id }}-title"
                @if ($titleDe && $titleEn)
                    data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}"
                @endif
            >{{ $title }}</h2>
            <button type="button" class="tools-btn tools-btn--ghost tools-btn--small" data-shared-modal-close data-text-de="Schließen" data-text-en="Close">Close</button>
        </header>
        <div class="admin-hub__modal-body">
            {{ $slot }}
        </div>
    </div>
</dialog>
