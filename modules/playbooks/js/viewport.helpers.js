export { isInViewport } from './toc.helpers.js';

/**
 * @param {Element} el
 * @param {{ root?: Element | Document, paddingTop?: number, paddingBottom?: number }} [opts]
 */
export function isElementInViewport(el, opts = {}) {
    return isInViewport(el, opts);
}
