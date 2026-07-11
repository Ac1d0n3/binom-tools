import {
    buildTocGroups,
    findActiveHeadingId,
    getHeaderOffset,
    syncExpandedGroups,
    toggleExpandedGroup,
} from './toc.helpers.js';

const DESKTOP_TOC_QUERY = '(min-width: 1100px)';
const SCROLL_PADDING = 16;

let suppressHashUpdate = false;

function isDesktopToc() {
    return window.matchMedia(DESKTOP_TOC_QUERY).matches;
}

function getScrollRoot(panel) {
    return panel.querySelector('[data-playbook-scroll-root]')
        ?? document.querySelector('.tools-shell__main')
        ?? window;
}

function getScrollTop(scrollRoot) {
    return scrollRoot === window ? window.scrollY : scrollRoot.scrollTop;
}

function scrollToPosition(scrollRoot, top) {
    if (scrollRoot === window) {
        window.scrollTo({ top, behavior: 'smooth' });
    } else {
        scrollRoot.scrollTo({ top, behavior: 'smooth' });
    }
}

function scrollToAnchor(id, panel) {
    const scrollRoot = getScrollRoot(panel);
    const target = panel.querySelector(`#${CSS.escape(id)}`);

    if (!target) {
        return;
    }

    suppressHashUpdate = true;

    const containerTop = scrollRoot === window ? 0 : scrollRoot.getBoundingClientRect().top;
    const offset = getHeaderOffset() + SCROLL_PADDING;
    const targetTop = target.getBoundingClientRect().top - containerTop + getScrollTop(scrollRoot) - offset;

    scrollToPosition(scrollRoot, Math.max(0, targetTop));

    window.setTimeout(() => {
        suppressHashUpdate = false;
    }, 500);
}

function updateHash(id) {
    if (suppressHashUpdate || !id) {
        return;
    }

    const nextHash = `#${id}`;

    if (window.location.hash === nextHash) {
        return;
    }

    history.replaceState(null, '', `${window.location.pathname}${window.location.search}${nextHash}`);
}

function getProseHeadings(panel) {
    return [...panel.querySelectorAll('.playbook-prose h2[id], .playbook-prose h3[id]')];
}

function parseGroupsFromDom(tocRoot) {
    /** @type {Array<{ id: string, text: string, level: number }>} */
    const entries = [];

    tocRoot.querySelectorAll('[data-playbook-toc-group]').forEach((groupEl) => {
        const groupId = groupEl.dataset.groupId;

        if (!groupId) {
            return;
        }

        const headingLink = groupEl.querySelector('.playbook-toc__group-header [data-playbook-toc-link]')
            ?? groupEl.querySelector('.playbook-toc__link--leaf');
        const headingText = headingLink?.textContent?.trim() ?? groupId;

        entries.push({ id: groupId, text: headingText, level: 2 });

        groupEl.querySelectorAll('[data-playbook-toc-sublist] [data-playbook-toc-link]').forEach((childLink) => {
            const childId = childLink.dataset.targetId;

            if (!childId) {
                return;
            }

            entries.push({
                id: childId,
                text: childLink.textContent?.trim() ?? childId,
                level: 3,
            });
        });
    });

    return buildTocGroups(entries);
}

function applyExpandedState(tocRoot, expandedGroupIds) {
    tocRoot.querySelectorAll('[data-playbook-toc-branch]').forEach((groupEl) => {
        const groupId = groupEl.dataset.groupId;
        const expanded = groupId ? expandedGroupIds.has(groupId) : false;
        const sublist = groupEl.querySelector('[data-playbook-toc-sublist]');
        const toggle = groupEl.querySelector('[data-playbook-toc-group-toggle]');

        groupEl.classList.toggle('playbook-toc__group--expanded', expanded);
        sublist?.classList.toggle('playbook-toc__sublist--open', expanded);
        toggle?.setAttribute('aria-expanded', String(expanded));
    });
}

function applyActiveState(tocRoot, activeId) {
    tocRoot.querySelectorAll('[data-playbook-toc-link]').forEach((link) => {
        link.classList.toggle('playbook-toc__link--active', link.dataset.targetId === activeId);
    });

    tocRoot.querySelectorAll('[data-playbook-toc-group]').forEach((groupEl) => {
        const groupId = groupEl.dataset.groupId;
        const containsActive = groupId === activeId
            || Boolean(groupEl.querySelector(`[data-playbook-toc-link][data-target-id="${CSS.escape(activeId ?? '')}"]`));

        groupEl.classList.toggle('playbook-toc__group--active', containsActive);
    });
}

function scrollActiveLinkIntoView(nav, activeLink) {
    if (!nav || !activeLink || !isDesktopToc()) {
        return;
    }

    const navRect = nav.getBoundingClientRect();
    const linkRect = activeLink.getBoundingClientRect();

    if (linkRect.top < navRect.top) {
        nav.scrollTop += linkRect.top - navRect.top;
    } else if (linkRect.bottom > navRect.bottom) {
        nav.scrollTop += linkRect.bottom - navRect.bottom;
    }
}

function syncTocPanelVisibility(tocPanel, toggle) {
    if (!tocPanel) {
        return;
    }

    if (isDesktopToc()) {
        tocPanel.classList.add('playbook-toc__panel--open');
        toggle?.setAttribute('aria-expanded', 'true');
        return;
    }

    const expanded = toggle?.getAttribute('aria-expanded') === 'true';
    tocPanel.classList.toggle('playbook-toc__panel--open', expanded);
}

/**
 * @param {HTMLElement} panel
 * @returns {{ disconnect: () => void } | null}
 */
export function initPlaybookToc(panel) {
    const tocRoot = panel.querySelector('[data-playbook-toc]');

    if (!tocRoot) {
        return null;
    }

    const tocPanel = tocRoot.querySelector('[data-playbook-toc-panel]');
    const tocNav = tocRoot.querySelector('.playbook-toc__nav');
    const mobileToggle = tocRoot.querySelector('[data-playbook-toc-toggle]');
    const links = [...tocRoot.querySelectorAll('[data-playbook-toc-link]')];
    const headings = getProseHeadings(panel);
    const scrollRoot = getScrollRoot(panel);
    const groups = parseGroupsFromDom(tocRoot);
    const headingIds = headings.map((heading) => heading.id);

    let manualExpandedGroupId = null;
    let expandedGroupIds = new Set();
    let ticking = false;

    syncTocPanelVisibility(tocPanel, mobileToggle);

    function renderState(preferredActiveId, options = {}) {
        const offset = getHeaderOffset() + SCROLL_PADDING;
        const resolvedActiveId = preferredActiveId
            ?? findActiveHeadingId(headings, scrollRoot, offset)
            ?? headingIds[0]
            ?? null;

        const syncResult = syncExpandedGroups(
            groups,
            resolvedActiveId,
            manualExpandedGroupId,
            options,
        );

        expandedGroupIds = syncResult.expandedGroupIds;
        manualExpandedGroupId = syncResult.manualExpandedGroupId;

        if (options.fromScroll && manualExpandedGroupId) {
            expandedGroupIds.add(manualExpandedGroupId);
        }

        applyExpandedState(tocRoot, expandedGroupIds);
        applyActiveState(tocRoot, resolvedActiveId);

        const activeLink = links.find((link) => link.dataset.targetId === resolvedActiveId);
        scrollActiveLinkIntoView(tocNav, activeLink);

        if (resolvedActiveId) {
            updateHash(resolvedActiveId);
        }
    }

    mobileToggle?.addEventListener('click', () => {
        if (isDesktopToc()) {
            return;
        }

        const expanded = mobileToggle.getAttribute('aria-expanded') === 'true';
        const nextExpanded = !expanded;

        mobileToggle.setAttribute('aria-expanded', String(nextExpanded));
        tocPanel?.classList.toggle('playbook-toc__panel--open', nextExpanded);
    });

    window.matchMedia(DESKTOP_TOC_QUERY).addEventListener('change', () => {
        syncTocPanelVisibility(tocPanel, mobileToggle);
    });

    tocRoot.querySelectorAll('[data-playbook-toc-group-toggle]').forEach((groupToggle) => {
        groupToggle.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const groupEl = groupToggle.closest('[data-playbook-toc-branch]');
            const groupId = groupEl?.dataset.groupId;

            if (!groupId) {
                return;
            }

            const toggleResult = toggleExpandedGroup(groups, groupId, expandedGroupIds);
            expandedGroupIds = toggleResult.expandedGroupIds;
            manualExpandedGroupId = toggleResult.manualExpandedGroupId;

            applyExpandedState(tocRoot, expandedGroupIds);
        });
    });

    links.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();

            const id = link.dataset.targetId;

            if (!id) {
                return;
            }

            scrollToAnchor(id, panel);
            manualExpandedGroupId = groups.find(
                (group) => group.id === id || group.children.some((child) => child.id === id),
            )?.id ?? manualExpandedGroupId;
            renderState(id, { fromScroll: false });

            if (!isDesktopToc() && tocPanel && mobileToggle) {
                tocPanel.classList.remove('playbook-toc__panel--open');
                mobileToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    if (headings.length === 0) {
        return null;
    }

    const onScroll = () => {
        if (ticking) {
            return;
        }

        ticking = true;

        window.requestAnimationFrame(() => {
            renderState(null, { fromScroll: true });
            ticking = false;
        });
    };

    const scrollTarget = scrollRoot === window ? window : scrollRoot;
    scrollTarget.addEventListener('scroll', onScroll, { passive: true });

    const initialHash = window.location.hash.replace(/^#/, '');

    if (initialHash && headingIds.includes(initialHash)) {
        manualExpandedGroupId = groups.find(
            (group) => group.id === initialHash || group.children.some((child) => child.id === initialHash),
        )?.id ?? null;
        renderState(initialHash, { fromScroll: true });
        window.requestAnimationFrame(() => scrollToAnchor(initialHash, panel));
    } else {
        renderState(null, { fromScroll: true });
    }

    return {
        disconnect() {
            scrollTarget.removeEventListener('scroll', onScroll);
        },
    };
}

export {
    scrollToAnchor,
    getScrollTop,
    getScrollRoot as getScrollContainer,
    getHeaderOffset,
    SCROLL_PADDING,
};
