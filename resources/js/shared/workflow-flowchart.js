/**
 * Layout workflow step strips for wrapping (--wrap only):
 * - equal-width items per row
 * - arrow between steps on the same row
 * - full-width continuation marker between rows
 */

const ROW_START = 'tools-workflow-flowchart__item--row-start';
const ROW_END = 'tools-workflow-flowchart__item--row-end';
const BREAK_CLASS = 'tools-workflow-flowchart__break';

let initialized = false;

/**
 * @param {HTMLElement} list
 */
function clearBreaks(list) {
    list.querySelectorAll(`.${BREAK_CLASS}`).forEach((node) => node.remove());
}

/**
 * @param {HTMLElement} list
 * @returns {HTMLElement[]}
 */
function stepItems(list) {
    return Array.from(list.children).filter(
        (node) => node instanceof HTMLElement && node.classList.contains('tools-workflow-flowchart__item'),
    );
}

/**
 * @param {HTMLElement[]} items
 */
function clearRowMarkers(items) {
    for (const item of items) {
        item.classList.remove(ROW_START, ROW_END);
    }
}

/**
 * @param {HTMLElement[]} items
 * @returns {HTMLElement[][]}
 */
function groupIntoRows(items) {
    /** @type {HTMLElement[][]} */
    const rows = [];
    /** @type {HTMLElement[]} */
    let row = [];
    let rowTop = null;

    for (const item of items) {
        const top = Math.round(item.getBoundingClientRect().top);
        if (rowTop === null || Math.abs(top - rowTop) <= 3) {
            row.push(item);
            rowTop = rowTop === null ? top : rowTop;
            continue;
        }
        rows.push(row);
        row = [item];
        rowTop = top;
    }
    if (row.length > 0) {
        rows.push(row);
    }
    return rows;
}

/**
 * @param {HTMLElement[]} group
 */
function equalizeRow(group) {
    const n = Math.max(group.length, 1);
    const basis = `calc(${(100 / n).toFixed(4)}% - 0.45rem)`;
    for (const item of group) {
        item.style.flex = `1 1 ${basis}`;
        item.style.minWidth = '0';
        item.style.maxWidth = '100%';
    }
}

/**
 * @returns {string}
 */
function continueLabel() {
    return document.documentElement.lang === 'de' ? 'Weiter' : 'Continues';
}

/**
 * @param {HTMLElement} afterItem
 */
function insertBreak(afterItem) {
    const breakEl = document.createElement('li');
    breakEl.className = BREAK_CLASS;
    breakEl.setAttribute('aria-hidden', 'true');
    breakEl.innerHTML = `<span class="tools-workflow-flowchart__break-line"></span><span class="tools-workflow-flowchart__break-label">${continueLabel()}</span><span class="tools-workflow-flowchart__break-line"></span>`;
    afterItem.after(breakEl);
}

/**
 * @param {HTMLElement} list
 */
function layoutWorkflowList(list) {
    clearBreaks(list);
    const items = stepItems(list);
    if (items.length === 0) {
        return;
    }

    clearRowMarkers(items);

    // Natural content sizing for first wrap detection
    for (const item of items) {
        item.style.flex = '0 1 auto';
        item.style.minWidth = 'min(100%, 11rem)';
        item.style.maxWidth = '100%';
    }
    void list.offsetHeight;

    let rows = groupIntoRows(items);
    for (const group of rows) {
        equalizeRow(group);
    }
    void list.offsetHeight;

    // Re-detect after equalization (widths can change wrapping)
    clearBreaks(list);
    rows = groupIntoRows(stepItems(list));
    clearRowMarkers(stepItems(list));

    for (let index = 0; index < rows.length; index += 1) {
        const group = rows[index];
        equalizeRow(group);
        group[0]?.classList.add(ROW_START);
        group[group.length - 1]?.classList.add(ROW_END);
        if (index < rows.length - 1 && group[group.length - 1]) {
            insertBreak(group[group.length - 1]);
        }
    }
}

/**
 * @param {ParentNode} [root]
 * @returns {HTMLElement[]}
 */
function wrapLists(root = document) {
    return [...root.querySelectorAll('.tools-workflow-flowchart--wrap .tools-workflow-flowchart__list')].filter(
        (node) => node instanceof HTMLElement,
    );
}

/**
 * @param {ParentNode} [root]
 */
export function initWorkflowFlowcharts(root = document) {
    const lists = wrapLists(root);
    if (lists.length === 0) {
        return;
    }

    const run = () => {
        for (const list of wrapLists(root)) {
            layoutWorkflowList(list);
        }
    };

    const schedule = () => {
        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(run);
        });
    };

    schedule();

    if (initialized) {
        return;
    }
    initialized = true;

    if (document.fonts?.ready) {
        void document.fonts.ready.then(schedule);
    }

    window.addEventListener('load', schedule);
    window.addEventListener('resize', schedule);
    window.addEventListener('binom-tools:locale', schedule);

    if (typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(schedule);
        for (const list of lists) {
            observer.observe(list);
            const host = list.closest('.tools-workflow-flowchart');
            if (host) {
                observer.observe(host);
            }
        }
    }
}
