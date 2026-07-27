@php
    $exId = (string) ($example['id'] ?? '');
    $titleEn = $example['title']['en'] ?? $exId;
    $titleDe = $example['title']['de'] ?? $titleEn;
    $sql = (string) ($example['sql'] ?? '');
    $notesEn = $example['notes']['en'] ?? '';
    $notesDe = $example['notes']['de'] ?? $notesEn;
@endphp
<details class="supplier-sql-example">
    <summary class="supplier-sql-example__summary" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</summary>
    <div class="supplier-sql-example__body">
        @if ($notesEn !== '' || $notesDe !== '')
            <p class="supplier-sql-example__notes" data-text-de="{{ $notesDe }}" data-text-en="{{ $notesEn }}">{{ $notesEn }}</p>
        @endif
        <div class="supplier-sql-example__code-row">
            <pre class="supplier-sql-example__pre"><code data-supplier-copy-source>{{ $sql }}</code></pre>
            <button
                type="button"
                class="supplier-copy-btn"
                data-supplier-copy
                data-i18n="suppliers.copy"
                data-i18n-copied="suppliers.copied"
            >Copy</button>
        </div>
    </div>
</details>
