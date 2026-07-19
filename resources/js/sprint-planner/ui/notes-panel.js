/**
 * Task/deliverable notes drawer — separate from the help panel.
 */

import { createIconButton } from './icons.js';
import { spT } from './helpers.js';

/** @type {ReturnType<typeof setTimeout>|null} */
let noteSaveTimer = null;
/** @type {((note: string) => void)|null} */
let activeOnNoteSave = null;

/**
 * @param {object} payload
 * @param {string} payload.title
 * @param {string} [payload.note]
 * @param {(note: string) => void} [payload.onNoteSave]
 */
export function openNotesPanel(payload) {
    const panel = document.getElementById('sp-notes-panel');
    const backdrop = document.getElementById('sp-notes-backdrop');
    const titleEl = document.getElementById('sp-notes-panel-title');
    const body = document.getElementById('sp-notes-panel-body');
    if (!panel || !body) {
        return;
    }

    if (noteSaveTimer) {
        window.clearTimeout(noteSaveTimer);
        noteSaveTimer = null;
    }
    activeOnNoteSave = typeof payload.onNoteSave === 'function' ? payload.onNoteSave : null;

    if (titleEl) {
        titleEl.textContent = spT('sp.notes.title');
    }
    body.replaceChildren();

    const taskTitle = document.createElement('p');
    taskTitle.className = 'sp-notes-panel__task';
    taskTitle.textContent = payload.title || '';
    body.appendChild(taskTitle);

    const field = document.createElement('label');
    field.className = 'sp-notes-panel__field';
    const label = document.createElement('span');
    label.className = 'sp-notes-panel__label';
    label.textContent = spT('sp.notes.label');
    const area = document.createElement('textarea');
    area.id = 'sp-notes-panel-input';
    area.className = 'tools-input sp-notes-panel__textarea';
    area.rows = 12;
    area.maxLength = 4000;
    area.placeholder = spT('sp.notes.placeholder');
    area.value = payload.note || '';
    area.addEventListener('input', () => {
        if (!activeOnNoteSave) {
            return;
        }
        window.clearTimeout(noteSaveTimer);
        noteSaveTimer = window.setTimeout(() => {
            noteSaveTimer = null;
            activeOnNoteSave?.(area.value);
        }, 400);
    });
    field.append(label, area);
    body.appendChild(field);

    panel.hidden = false;
    panel.setAttribute('aria-hidden', 'false');
    if (backdrop) {
        backdrop.hidden = false;
    }
    queueMicrotask(() => area.focus());
}

export function closeNotesPanel() {
    if (noteSaveTimer && activeOnNoteSave) {
        const area = /** @type {HTMLTextAreaElement|null} */ (document.getElementById('sp-notes-panel-input'));
        window.clearTimeout(noteSaveTimer);
        noteSaveTimer = null;
        if (area) {
            activeOnNoteSave(area.value);
        }
    }
    activeOnNoteSave = null;

    const panel = document.getElementById('sp-notes-panel');
    const backdrop = document.getElementById('sp-notes-backdrop');
    if (panel && !panel.hidden) {
        if (panel.contains(document.activeElement) && document.activeElement instanceof HTMLElement) {
            document.activeElement.blur();
        }
        panel.hidden = true;
        panel.setAttribute('aria-hidden', 'true');
    }
    if (backdrop) {
        backdrop.hidden = true;
    }
}

/**
 * @returns {boolean}
 */
export function isNotesPanelOpen() {
    const panel = document.getElementById('sp-notes-panel');
    return Boolean(panel && !panel.hidden);
}

let notesBound = false;

export function bindNotesPanelChrome() {
    if (notesBound) {
        return;
    }
    notesBound = true;
    document.getElementById('sp-notes-close')?.addEventListener('click', closeNotesPanel);
    document.getElementById('sp-notes-backdrop')?.addEventListener('click', closeNotesPanel);
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isNotesPanelOpen()) {
            closeNotesPanel();
        }
    });
}

/**
 * @param {import('../progress.js').ResolvedItem} item
 * @param {(item: import('../progress.js').ResolvedItem) => void} openNotes
 */
export function renderNotesButton(item, openNotes) {
    const hasNote = Boolean(String(item.note || '').trim());
    const btn = createIconButton({
        icon: 'note',
        label: spT('sp.notes.open'),
        className: 'sp-notes-btn',
        onClick: () => openNotes(item),
    });
    if (hasNote) {
        const badge = document.createElement('span');
        badge.className = 'sp-icon-btn__badge';
        badge.textContent = '1';
        badge.title = spT('sp.notes.hasNote');
        btn.appendChild(badge);
    }
    return btn;
}
