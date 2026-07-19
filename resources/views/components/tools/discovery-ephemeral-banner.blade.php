{{-- Plan-only transfer warning — shown when opened from Sprint Planner with fromPlan=1 --}}
<aside class="discovery-ephemeral-banner" data-plan-only hidden role="status" aria-live="polite">
    <div class="discovery-ephemeral-banner__icon" aria-hidden="true">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <div class="discovery-ephemeral-banner__body">
        <p class="discovery-ephemeral-banner__title" data-i18n="discovery.warnTitle">
            Not saved — transfer the result now
        </p>
        <p class="discovery-ephemeral-banner__text" data-i18n="discovery.warnBody">
            Entries exist only on this page. Copy or download before you leave — notes, wiki, tickets, or Sprint Planner.
        </p>
        <div class="discovery-ephemeral-banner__actions">
            <button
                type="button"
                class="tools-btn tools-btn--primary"
                data-apply-to-plan
                data-i18n="discovery.applyToPlan"
            >Apply to current plan item</button>
        </div>
    </div>
</aside>
