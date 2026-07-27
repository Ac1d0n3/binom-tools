/** Progressive reveal for overview card grids (batch load on scroll). */

export const OVERVIEW_REVEAL_BATCH = 18;
export const OVERVIEW_ITEM_UNREVEALED_CLASS = 'overview-item--unrevealed';

/**
 * @param {HTMLElement} item
 * @returns {boolean}
 */
function isActivelyShown(item) {
    if (item.hidden) {
        return false;
    }

    // Ignore cards inside a hidden view panel (stories vs series toggle).
    return item.closest('[hidden]') === null;
}

/**
 * @param {ParentNode} root
 * @returns {HTMLElement[]}
 */
function collectRevealItems(root) {
    return Array.from(
        root.querySelectorAll('[data-overview-item], [data-overview-series-item]'),
    ).filter((node) => node instanceof HTMLElement);
}

/**
 * @param {ParentNode} root
 * @returns {HTMLElement | null}
 */
function findRevealHost(root) {
    const storiesPanel = root.querySelector('[data-overview-view-panel="stories"]');
    const seriesPanel = root.querySelector('[data-overview-view-panel="series"]');

    if (seriesPanel instanceof HTMLElement && !seriesPanel.hidden) {
        const seriesGrid = seriesPanel.querySelector('[data-overview-series-grid], .tools-card-grid');
        if (seriesGrid instanceof HTMLElement) {
            return seriesGrid;
        }
    }

    if (storiesPanel instanceof HTMLElement && !storiesPanel.hidden) {
        const storiesGrid = storiesPanel.querySelector('[data-overview-stories-grid], .tools-card-grid');
        if (storiesGrid instanceof HTMLElement) {
            return storiesGrid;
        }
    }

    const storiesGrid = root.querySelector('[data-overview-stories-grid]');
    if (storiesGrid instanceof HTMLElement && storiesGrid.closest('[hidden]') === null) {
        return storiesGrid;
    }

    const seriesGrid = root.querySelector('[data-overview-series-grid]');
    if (seriesGrid instanceof HTMLElement && seriesGrid.closest('[hidden]') === null) {
        return seriesGrid;
    }

    const scrollPane = root.querySelector('.tools-overview-scroll, .tools-card-grid');
    if (scrollPane instanceof HTMLElement) {
        const grid =
            scrollPane.classList.contains('tools-card-grid')
                ? scrollPane
                : scrollPane.querySelector(
                      '.tools-card-grid, .glossary-hub-grid, .learning-paths-hub-grid, .roles-hub-grid',
                  );
        if (grid instanceof HTMLElement && grid.closest('[hidden]') === null) {
            return grid;
        }
    }

    const anyGrid = root.querySelector(
        '.tools-card-grid, .glossary-hub-grid, .learning-paths-hub-grid, .roles-hub-grid',
    );
    return anyGrid instanceof HTMLElement ? anyGrid : null;
}

/**
 * Nested overview panes scroll inside `.tools-overview-scroll`, not the window.
 *
 * @param {ParentNode} root
 * @returns {Element | null}
 */
export function findOverviewScrollRoot(root) {
    const pane = root.querySelector('.tools-overview-scroll');
    if (!(pane instanceof HTMLElement)) {
        return null;
    }

    try {
        const { overflowY } = window.getComputedStyle(pane);
        if (overflowY === 'auto' || overflowY === 'scroll' || overflowY === 'overlay') {
            return pane;
        }
    } catch {
        // jsdom / older environments — still prefer the pane when present.
        return pane;
    }

    // Desktop overview layout uses overflow-y: auto; mobile may set overflow: visible.
    return null;
}

/**
 * @param {Element} target
 * @param {Element | null} scrollRoot
 * @returns {boolean}
 */
function isNearlyVisible(target, scrollRoot) {
    const targetRect = target.getBoundingClientRect();
    // jsdom / pre-layout: avoid eagerly revealing the entire list.
    if (
        targetRect.width === 0
        && targetRect.height === 0
        && targetRect.top === 0
        && targetRect.bottom === 0
    ) {
        return false;
    }

    if (scrollRoot instanceof Element) {
        const rootRect = scrollRoot.getBoundingClientRect();
        const margin = 240;
        return targetRect.top <= rootRect.bottom + margin && targetRect.bottom >= rootRect.top - margin;
    }

    const margin = 240;
    return targetRect.top <= window.innerHeight + margin && targetRect.bottom >= -margin;
}

/**
 * @param {HTMLElement} root
 * @param {{ getSearchQuery?: () => string }} [options]
 * @returns {{ refresh: () => void, destroy: () => void }}
 */
export function attachOverviewProgressiveReveal(root, options = {}) {
    let revealedLimit = OVERVIEW_REVEAL_BATCH;
    /** @type {IntersectionObserver | null} */
    let observer = null;
    /** @type {HTMLElement | null} */
    let sentinel = root.querySelector('[data-overview-reveal-sentinel]');
    /** @type {Element | null} */
    let scrollRoot = null;
    /** @type {(() => void) | null} */
    let onScroll = null;
    let loading = false;

    if (!(sentinel instanceof HTMLElement)) {
        sentinel = document.createElement('div');
        sentinel.className = 'overview-reveal-sentinel';
        sentinel.setAttribute('data-overview-reveal-sentinel', '');
        sentinel.setAttribute('aria-hidden', 'true');
    }

    const getSearchQuery = typeof options.getSearchQuery === 'function'
        ? options.getSearchQuery
        : () => '';

    const placeSentinel = () => {
        const host = findRevealHost(root);
        if (!(host instanceof HTMLElement) || !(sentinel instanceof HTMLElement)) {
            return;
        }

        if (sentinel.parentElement !== host) {
            host.appendChild(sentinel);
        }
    };

    const applyReveal = () => {
        const items = collectRevealItems(root);
        const active = items.filter((item) => isActivelyShown(item));
        const query = (getSearchQuery() ?? '').trim();
        const revealAll = query !== '' && active.length <= OVERVIEW_REVEAL_BATCH * 2;
        const limit = revealAll ? active.length : revealedLimit;

        items.forEach((item) => {
            item.classList.remove(OVERVIEW_ITEM_UNREVEALED_CLASS);
        });

        active.forEach((item, index) => {
            if (index >= limit) {
                item.classList.add(OVERVIEW_ITEM_UNREVEALED_CLASS);
            }
        });

        placeSentinel();

        if (sentinel instanceof HTMLElement) {
            const hasMore = !revealAll && active.length > limit;
            sentinel.hidden = !hasMore;
        }
    };

    /**
     * Keep loading while the sentinel stays in view (tall viewports / list rows).
     * IntersectionObserver alone often fires only once when isIntersecting stays true.
     */
    const fillWhileSentinelVisible = () => {
        if (loading || !(sentinel instanceof HTMLElement)) {
            return;
        }

        loading = true;
        try {
            let guard = 0;
            while (!sentinel.hidden && guard < 40) {
                if (!isNearlyVisible(sentinel, scrollRoot)) {
                    break;
                }

                const before = revealedLimit;
                revealedLimit += OVERVIEW_REVEAL_BATCH;
                applyReveal();

                if (revealedLimit === before || sentinel.hidden) {
                    break;
                }
                guard += 1;
            }
        } finally {
            loading = false;
        }
    };

    const reset = () => {
        revealedLimit = OVERVIEW_REVEAL_BATCH;
        applyReveal();
        // First paint may leave the sentinel on-screen (short list cards).
        fillWhileSentinelVisible();
    };

    const bindObserver = () => {
        if (observer) {
            observer.disconnect();
            observer = null;
        }
        if (scrollRoot instanceof Element && onScroll) {
            scrollRoot.removeEventListener('scroll', onScroll);
            onScroll = null;
        }

        scrollRoot = findOverviewScrollRoot(root);

        if (!(sentinel instanceof HTMLElement) || typeof IntersectionObserver !== 'function') {
            return;
        }

        placeSentinel();

        observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting && !sentinel.hidden) {
                        fillWhileSentinelVisible();
                    }
                }
            },
            {
                root: scrollRoot,
                rootMargin: '240px 0px',
                threshold: 0,
            },
        );
        observer.observe(sentinel);

        if (scrollRoot instanceof Element) {
            onScroll = () => {
                if (!sentinel.hidden) {
                    fillWhileSentinelVisible();
                }
            };
            scrollRoot.addEventListener('scroll', onScroll, { passive: true });
        }
    };

    bindObserver();
    reset();

    return {
        refresh: () => {
            bindObserver();
            reset();
        },
        destroy: () => {
            if (observer) {
                observer.disconnect();
                observer = null;
            }
            if (scrollRoot instanceof Element && onScroll) {
                scrollRoot.removeEventListener('scroll', onScroll);
                onScroll = null;
            }
        },
    };
}
