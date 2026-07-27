import {
    readPlanLaunchContext,
    writeTransferPayload,
} from './plan-bridge.js';

/**
 * Show plan-only transfer chrome and wire “Apply to plan”.
 *
 * @param {object} options
 * @param {HTMLElement} options.root
 * @param {(key: string) => string} options.t
 * @param {() => { markdown?: string, columns?: Array<{id:string,label:string}>, rows?: Array<{id:string,cells:Record<string,string>}> }} options.getPayload
 * @param {() => void} options.markTransferred
 * @param {() => boolean} [options.hasContent]
 * @returns {import('./plan-bridge.js').PlanLaunchContext|null}
 */
export function bindPlanTransferUi(options) {
    const { root, t, getPayload, markTransferred, hasContent } = options;
    const ctx = readPlanLaunchContext();

    root.querySelectorAll('[data-plan-only]').forEach((el) => {
        if (el instanceof HTMLElement) {
            el.hidden = !ctx;
        }
    });

    if (!ctx) {
        return null;
    }

    // Prefer plan-specific copy when present.
    root.querySelectorAll('[data-i18n="discovery.warnTitle"]').forEach((el) => {
        el.setAttribute('data-i18n', 'discovery.planWarnTitle');
        el.textContent = t('discovery.planWarnTitle');
    });
    root.querySelectorAll('[data-i18n="discovery.warnBody"]').forEach((el) => {
        el.setAttribute('data-i18n', 'discovery.planWarnBody');
        el.textContent = t('discovery.planWarnBody');
    });
    root.querySelectorAll('[data-i18n="discovery.exportHint"]').forEach((el) => {
        el.setAttribute('data-i18n', 'discovery.planExportHint');
        el.textContent = t('discovery.planExportHint');
    });

    const btn = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-apply-to-plan]'));
    btn?.addEventListener('click', () => {
        if (hasContent && !hasContent()) {
            window.alert(t('discovery.applyEmpty'));
            return;
        }
        const payload = getPayload();
        const hasRows = Array.isArray(payload?.rows) && payload.rows.length > 0;
        const hasMd = Boolean(String(payload?.markdown || '').trim());
        if (!hasRows && !hasMd) {
            window.alert(t('discovery.applyEmpty'));
            return;
        }
        writeTransferPayload({
            v: 1,
            instanceId: ctx.instanceId,
            itemKey: ctx.itemKey,
            kind: ctx.kind,
            sprintId: ctx.sprintId,
            custom: ctx.custom,
            markdown: payload?.markdown || '',
            columns: payload?.columns || [],
            rows: payload?.rows || [],
            at: Date.now(),
        });
        markTransferred();
        window.location.assign(ctx.returnUrl);
    });

    return ctx;
}
