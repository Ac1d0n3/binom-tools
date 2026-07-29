/**
 * Layout workflow step strips for wrapping (--wrap only):
 * - equal-width items per row
 * - arrow between steps on the same row
 * - full-width continuation marker between rows
 *
 * Supports Tools (`tools-workflow-flowchart`) and Playbook (`playbook-flowchart`) prefixes.
 */

/** @typedef {{ item: string, rowStart: string, rowEnd: string, break: string, breakLine: string, breakLabel: string, host: string }} WrapClasses */

/** @type {WrapClasses} */
const TOOLS_CLASSES = {
    item: 'tools-workflow-flowchart__item',
    rowStart: 'tools-workflow-flowchart__item--row-start',
    rowEnd: 'tools-workflow-flowchart__item--row-end',
    break: 'tools-workflow-flowchart__break',
    breakLine: 'tools-workflow-flowchart__break-line',
    breakLabel: 'tools-workflow-flowchart__break-label',
    host: 'tools-workflow-flowchart',
};

/** @type {WrapClasses} */
const PLAYBOOK_CLASSES = {
    item: 'playbook-flowchart__item',
    rowStart: 'playbook-flowchart__item--row-start',
    rowEnd: 'playbook-flowchart__item--row-end',
    break: 'playbook-flowchart__break',
    breakLine: 'playbook-flowchart__break-line',
    breakLabel: 'playbook-flowchart__break-label',
    host: 'playbook-flowchart',
};

const WRAP_LIST_SELECTOR = [
    '.tools-workflow-flowchart--wrap .tools-workflow-flowchart__list',
    '.playbook-flowchart--wrap .playbook-flowchart__list',
].join(', ');

let initialized = false;

/**
 * @param {HTMLElement} list
 * @returns {WrapClasses}
 */
function classesForList(list) {
    return list.closest('.playbook-flowchart--wrap') ? PLAYBOOK_CLASSES : TOOLS_CLASSES;
}

/**
 * @param {HTMLElement} list
 * @param {WrapClasses} classes
 */
function clearBreaks(list, classes) {
    list.querySelectorAll(`.${classes.break}`).forEach((node) => node.remove());
}

/**
 * @param {HTMLElement} list
 * @param {WrapClasses} classes
 * @returns {HTMLElement[]}
 */
function stepItems(list, classes) {
    return Array.from(list.children).filter(
        (node) => node instanceof HTMLElement && node.classList.contains(classes.item),
    );
}

/**
 * @param {HTMLElement[]} items
 * @param {WrapClasses} classes
 */
function clearRowMarkers(items, classes) {
    for (const item of items) {
        item.classList.remove(classes.rowStart, classes.rowEnd);
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
 * @param {WrapClasses} classes
 */
function insertBreak(afterItem, classes) {
    const parent = afterItem.parentElement;
    const tagName = parent?.tagName === 'OL' || parent?.tagName === 'UL' ? 'li' : 'div';
    const breakEl = document.createElement(tagName);
    breakEl.className = classes.break;
    breakEl.setAttribute('aria-hidden', 'true');
    if (tagName === 'div') {
        breakEl.setAttribute('role', 'presentation');
    }
    breakEl.innerHTML = `<span class="${classes.breakLine}"></span><span class="${classes.breakLabel}">${continueLabel()}</span><span class="${classes.breakLine}"></span>`;
    afterItem.after(breakEl);
}

/**
 * @param {HTMLElement} list
 */
function layoutWorkflowList(list) {
    const classes = classesForList(list);
    clearBreaks(list, classes);
    const items = stepItems(list, classes);
    if (items.length === 0) {
        return;
    }

    clearRowMarkers(items, classes);

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
    clearBreaks(list, classes);
    rows = groupIntoRows(stepItems(list, classes));
    clearRowMarkers(stepItems(list, classes), classes);

    for (let index = 0; index < rows.length; index += 1) {
        const group = rows[index];
        equalizeRow(group);
        group[0]?.classList.add(classes.rowStart);
        group[group.length - 1]?.classList.add(classes.rowEnd);
        if (index < rows.length - 1 && group[group.length - 1]) {
            insertBreak(group[group.length - 1], classes);
        }
    }
}

/**
 * @param {ParentNode} [root]
 * @returns {HTMLElement[]}
 */
function wrapLists(root = document) {
    return [...root.querySelectorAll(WRAP_LIST_SELECTOR)].filter(
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
    window.addEventListener('binom-tools:playbook-locale', schedule);
    window.addEventListener('binom-tools:workflow-flowchart-relayout', schedule);

    if (typeof ResizeObserver !== 'undefined') {
        const observer = new ResizeObserver(schedule);
        for (const list of lists) {
            observer.observe(list);
            const host = list.closest(`.${classesForList(list).host}`);
            if (host) {
                observer.observe(host);
            }
        }
    }
}
