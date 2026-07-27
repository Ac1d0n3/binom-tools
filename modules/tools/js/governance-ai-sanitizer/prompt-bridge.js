import {
    loadPromptBridge,
    saveToStudio,
    hasFreshBridgePayload,
} from '../prompt-shared/prompt-bridge-storage.js';

/**
 * @param {(key: string) => string} t
 * @returns {{ loaded: boolean, bannerHtml: string }}
 */
export function initPromptBridgeFromStudio(t) {
    const loaded = loadPromptBridge();
    if (!loaded || !('payload' in loaded) || loaded.payload.direction !== 'to-sanitizer') {
        return { loaded: false, bannerHtml: '' };
    }

    if (!hasFreshBridgePayload()) {
        return { loaded: false, bannerHtml: '' };
    }

    const ctx = loaded.payload.studioContext ?? {};
    const role = ctx.roleId ?? '';
    const task = ctx.taskId ?? '';

    return {
        loaded: true,
        prompt: loaded.payload.prompt.compiled,
        bannerHtml: `<p class="gov-bridge-banner__text">${t('gov.bridge.fromStudio')} <strong>${role}</strong> / <strong>${task}</strong></p>`,
        studioContext: ctx,
    };
}

/**
 * @param {{
 *   outbound?: string,
 *   restored?: string,
 *   findingCount?: number,
 *   studioContext?: import('../prompt-shared/prompt-bridge-storage.js').PromptBridgePayload['studioContext'],
 * }} input
 */
export function sendBackToPromptStudio(input) {
    saveToStudio({
        outbound: input.outbound,
        restored: input.restored,
        findingCount: input.findingCount,
        studioContext: input.studioContext,
    });
}

/**
 * @returns {string}
 */
export function promptStudioUrl() {
    const base = document.documentElement.dataset.appBase || '';
    const path = window.location.pathname;
    if (path.includes('/de/') || path === '/de' || path.endsWith('/de')) {
        return `${base}/de/tools/prompt-studio`;
    }
    if (path.includes('/en/') || path === '/en' || path.endsWith('/en')) {
        return `${base}/en/tools/prompt-studio`;
    }
    return `${base}/tools/prompt-studio`;
}
