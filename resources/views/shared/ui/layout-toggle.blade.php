{{-- Shared Grid/List toggle — same DOM contract as before (1:1). --}}
<div
    {{ $attributes->class(['tools-overview-layout-toggle']) }}
    role="group"
>
    <button
        type="button"
        class="tools-overview-layout-toggle__button tools-overview-layout-toggle__button--active"
        data-overview-layout-toggle="grid"
        aria-pressed="true"
        data-i18n-aria="overview.layoutGrid"
        aria-label="Grid view"
        title="Grid view"
    >
        <i class="fa-solid fa-grip" aria-hidden="true"></i>
        <span class="sr-only" data-i18n="overview.layoutGrid">Grid view</span>
    </button>
    <button
        type="button"
        class="tools-overview-layout-toggle__button"
        data-overview-layout-toggle="list"
        aria-pressed="false"
        data-i18n-aria="overview.layoutList"
        aria-label="List view"
        title="List view"
    >
        <i class="fa-solid fa-list" aria-hidden="true"></i>
        <span class="sr-only" data-i18n="overview.layoutList">List view</span>
    </button>
</div>
