{{-- Admin overview layout: Cards | Table (icon-only; no data-text-* — locale.js would wipe icons) --}}
<div
    {{ $attributes->class(['admin-hub__layout-toggle']) }}
    role="group"
    aria-label="Layout"
    data-admin-layout-toggle-group
>
    <button
        type="button"
        class="admin-hub__layout-toggle-btn"
        data-admin-layout-toggle="cards"
        aria-pressed="false"
        aria-label="Cards"
        title="Cards"
        data-i18n-aria="overview.layoutGrid"
    >
        <i class="fa-solid fa-grip" aria-hidden="true"></i>
        <span class="sr-only">Cards</span>
    </button>
    <button
        type="button"
        class="admin-hub__layout-toggle-btn is-active"
        data-admin-layout-toggle="table"
        aria-pressed="true"
        aria-label="Table"
        title="Table"
    >
        <i class="fa-solid fa-table" aria-hidden="true"></i>
        <span class="sr-only">Table</span>
    </button>
</div>
