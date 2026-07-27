/**
 * Shared icon buttons and SVG glyphs for sprint planner UI.
 */

import { spT } from './helpers.js';

/**
 * @param {'help'|'owner'|'edit'|'delete'|'open'|'check'|'close'|'required'|'list'|'note'|'chain'|'parallel'} name
 * @returns {string}
 */
export function iconSvg(name) {
    const common = 'width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';
    switch (name) {
        case 'help':
            return `<svg ${common}><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;
        case 'note':
            return `<svg ${common}><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>`;
        case 'owner':
            return `<svg ${common}><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`;
        case 'edit':
            return `<svg ${common}><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>`;
        case 'delete':
            return `<svg ${common}><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>`;
        case 'list':
            return `<svg ${common}><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>`;
        case 'open':
            return `<svg ${common}><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>`;
        case 'check':
            return `<svg ${common}><polyline points="20 6 9 17 4 12"/></svg>`;
        case 'close':
            return `<svg ${common}><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
        case 'required':
            return `<svg ${common}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;
        case 'chain':
            return `<svg ${common}><path d="M10 13a5 5 0 0 0 7.07 0l2.12-2.12a5 5 0 0 0-7.07-7.07L11 4.93"/><path d="M14 11a5 5 0 0 0-7.07 0L4.81 13.12a5 5 0 0 0 7.07 7.07L13 19.07"/></svg>`;
        case 'parallel':
            return `<svg ${common}><path d="M6 3v7a4 4 0 0 0 4 4h8"/><path d="M6 21v-7a4 4 0 0 1 4-4h8"/><path d="m15 7 3 3-3 3"/><path d="m15 11 3 3-3 3"/></svg>`;
        default:
            return '';
    }
}

/**
 * @param {{icon: string, label: string, title?: string, className?: string, href?: string, primary?: boolean, pending?: boolean, onClick?: (e: Event) => void}} opts
 * @returns {HTMLButtonElement|HTMLAnchorElement}
 */
export function createIconButton(opts) {
    const el = opts.href
        ? document.createElement('a')
        : document.createElement('button');
    if (opts.href) {
        /** @type {HTMLAnchorElement} */ (el).href = opts.href;
        /** @type {HTMLAnchorElement} */ (el).target = '_blank';
        /** @type {HTMLAnchorElement} */ (el).rel = 'noopener noreferrer';
    } else {
        /** @type {HTMLButtonElement} */ (el).type = 'button';
    }
    el.className = [
        'sp-icon-btn',
        opts.primary ? 'sp-icon-btn--primary' : '',
        opts.className || '',
    ].filter(Boolean).join(' ');
    el.innerHTML = iconSvg(opts.icon);
    el.title = opts.title || opts.label;
    el.setAttribute('aria-label', opts.label);
    // Pending indicator lives on the count badge (badge-on-badge), not on the button.
    if (opts.onClick) {
        el.addEventListener('click', opts.onClick);
    }
    return el;
}

/**
 * Required-story marker (calm text badge — not an alert icon).
 * @param {{read?: boolean}} [opts]
 * @returns {HTMLSpanElement}
 */
export function createRequiredIcon(opts = {}) {
    const span = document.createElement('span');
    const read = Boolean(opts.read);
    span.className = read ? 'sp-required-badge sp-required-badge--done' : 'sp-required-badge';
    span.textContent = read ? spT('sp.story.read') : spT('sp.help.required');
    span.title = read ? spT('sp.story.read') : spT('sp.help.required');
    return span;
}
