/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */

const MAX_STEPS = 50;

/**
 * Undo/redo stack for prompt draft snapshots.
 */
export class HistoryStack {
    constructor() {
        /** @type {PromptDraftState[]} */
        this.undoStack = [];
        /** @type {PromptDraftState[]} */
        this.redoStack = [];
    }

    /**
     * @param {PromptDraftState} snapshot
     * @param {{ skipDedupe?: boolean }} [options]
     */
    push(snapshot, options = {}) {
        const normalized = structuredClone(snapshot);
        const last = this.undoStack[this.undoStack.length - 1];

        if (!options.skipDedupe && last && JSON.stringify(last) === JSON.stringify(normalized)) {
            return;
        }

        this.undoStack.push(normalized);
        if (this.undoStack.length > MAX_STEPS) {
            this.undoStack.shift();
        }
        this.redoStack = [];
    }

    /** @returns {PromptDraftState | null} */
    undo() {
        if (this.undoStack.length <= 1) return null;
        const current = this.undoStack.pop();
        if (current) this.redoStack.push(current);
        return structuredClone(this.undoStack[this.undoStack.length - 1] ?? null);
    }

    /** @returns {PromptDraftState | null} */
    redo() {
        if (this.redoStack.length === 0) return null;
        const next = this.redoStack.pop();
        if (!next) return null;
        this.undoStack.push(next);
        return structuredClone(next);
    }

    canUndo() {
        return this.undoStack.length > 1;
    }

    canRedo() {
        return this.redoStack.length > 0;
    }

    reset(/** @type {PromptDraftState} */ initial) {
        this.undoStack = [structuredClone(initial)];
        this.redoStack = [];
    }
}
