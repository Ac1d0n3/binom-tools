/**
 * @typedef {{ id: string, label: string, children: Array<{ id: string, label: string }> }} TocGroup
 */

/**
 * @param {Array<{ id: string, text: string, level: number }>} entries
 * @returns {TocGroup[]}
 */
export function buildTocGroups(entries) {
    /** @type {TocGroup[]} */
    const groups = [];

    for (const entry of entries) {
        if (entry.level === 2) {
            groups.push({ id: entry.id, label: entry.text, children: [] });
        } else if (groups.length > 0) {
            groups[groups.length - 1].children.push({
                id: entry.id,
                label: entry.text,
            });
        }
    }

    return groups;
}

/**
 * @param {HTMLElement[]} headings
 * @param {Window | Element} scrollRoot
 * @param {number} offset
 * @returns {string | null}
 */
export function findActiveHeadingId(headings, scrollRoot, offset) {
    if (headings.length === 0) {
        return null;
    }

    const containerTop = scrollRoot === window
        ? 0
        : scrollRoot.getBoundingClientRect().top;
    const marker = containerTop + offset;

    let activeId = headings[0].id;

    for (const heading of headings) {
        if (heading.getBoundingClientRect().top <= marker) {
            activeId = heading.id;
        } else {
            break;
        }
    }

    return activeId;
}

/**
 * @param {TocGroup[]} groups
 * @param {string | null} activeId
 * @param {string | null} manualExpandedGroupId
 * @param {{ fromScroll?: boolean }} [options]
 * @returns {{ expandedGroupIds: Set<string>, manualExpandedGroupId: string | null }}
 */
export function syncExpandedGroups(groups, activeId, manualExpandedGroupId, options = {}) {
    const { fromScroll = false } = options;
    const expanded = new Set();

    if (!activeId || groups.length === 0) {
        return { expandedGroupIds: expanded, manualExpandedGroupId };
    }

    let activeGroupId = null;

    for (const group of groups) {
        if (group.id === activeId || group.children.some((child) => child.id === activeId)) {
            activeGroupId = group.id;
            break;
        }
    }

    const effectiveGroupId = activeGroupId ?? manualExpandedGroupId;

    if (
        effectiveGroupId
        && groups.some((group) => group.id === effectiveGroupId && group.children.length > 0)
    ) {
        expanded.add(effectiveGroupId);
    }

    let nextManual = manualExpandedGroupId;

    if (fromScroll && activeGroupId) {
        nextManual = activeGroupId;
    }

    return { expandedGroupIds: expanded, manualExpandedGroupId: nextManual };
}

/**
 * @param {TocGroup[]} groups
 * @param {string} groupId
 * @param {Set<string>} expandedGroupIds
 * @returns {{ expandedGroupIds: Set<string>, manualExpandedGroupId: string | null }}
 */
export function toggleExpandedGroup(groups, groupId, expandedGroupIds) {
    const expanded = new Set(expandedGroupIds);
    const isOpen = expanded.has(groupId);

    if (isOpen) {
        expanded.delete(groupId);
        return { expandedGroupIds: expanded, manualExpandedGroupId: null };
    }

    expanded.clear();
    expanded.add(groupId);

    return {
        expandedGroupIds: expanded,
        manualExpandedGroupId: groupId,
    };
}

/**
 * @param {Element} el
 * @param {{ root?: Element | Document, paddingTop?: number, paddingBottom?: number }} [opts]
 */
export function isInViewport(el, opts = {}) {
    const root = opts.root ?? document.documentElement;
    const padTop = opts.paddingTop ?? 0;
    const padBottom = opts.paddingBottom ?? 0;
    const rect = el.getBoundingClientRect();

    if (rect.width <= 0 || rect.height <= 0) {
        return false;
    }

    const rootRect = root === document.documentElement
        ? {
            top: padTop,
            bottom: window.innerHeight - padBottom,
            left: 0,
            right: window.innerWidth,
        }
        : root.getBoundingClientRect();

    return (
        rect.top >= rootRect.top
        && rect.bottom <= rootRect.bottom
        && rect.left >= rootRect.left
        && rect.right <= rootRect.right
    );
}

export function getHeaderOffset() {
    if (typeof document === 'undefined') {
        return 44;
    }

    const raw = getComputedStyle(document.documentElement)
        .getPropertyValue('--tools-header-height')
        .trim();

    const parsed = parseInt(raw, 10);

    return Number.isFinite(parsed) ? parsed : 44;
}
