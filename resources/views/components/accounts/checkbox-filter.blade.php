@props([
    'items' => [],
    'name' => 'memberIds',
    'selected' => [],
    'labelKey' => 'displayName',
    'legendKey' => 'accounts.members',
    'searchPlaceholderKey' => 'accounts.filterMembers',
])

@php
    $selected = array_map('strval', $selected);
    $uid = 'accounts-filter-'.uniqid();
@endphp

<fieldset class="sp-field" data-accounts-checkbox-filter>
    <legend data-i18n="{{ $legendKey }}">Items</legend>
    <label class="sp-field">
        <span class="visually-hidden" data-i18n="{{ $searchPlaceholderKey }}">Filter</span>
        <input
            type="search"
            class="tools-input"
            data-accounts-filter-input
            placeholder="Filter…"
            data-i18n-placeholder="{{ $searchPlaceholderKey }}"
            autocomplete="off"
        >
    </label>
    <div class="sp-checkbox-list" id="{{ $uid }}">
        @forelse ($items as $item)
            @php
                $id = (string) ($item['id'] ?? '');
                $label = (string) ($item[$labelKey] ?? $item['email'] ?? $id);
                if (isset($item['name']) && is_array($item['name'])) {
                    $label = (string) ($item['name']['en'] ?? $item['name']['de'] ?? $id);
                }
            @endphp
            <label class="sp-check" data-accounts-filter-row data-filter-text="{{ strtolower($label.' '.$id) }}">
                <input type="checkbox" name="{{ $name }}[]" value="{{ $id }}" @checked(in_array($id, $selected, true))>
                {{ $label }}
            </label>
        @empty
            <p class="tools-page-lead" data-i18n="accounts.noItems">No items yet.</p>
        @endforelse
    </div>
</fieldset>

<script>
(() => {
    document.querySelectorAll('[data-accounts-checkbox-filter]').forEach((root) => {
        const input = root.querySelector('[data-accounts-filter-input]');
        if (!input || input.dataset.bound === '1') return;
        input.dataset.bound = '1';
        input.addEventListener('input', () => {
            const q = String(input.value || '').trim().toLowerCase();
            root.querySelectorAll('[data-accounts-filter-row]').forEach((row) => {
                const text = row.getAttribute('data-filter-text') || '';
                row.hidden = q !== '' && !text.includes(q);
            });
        });
    });
})();
</script>
