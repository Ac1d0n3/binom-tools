import { renderTemplate } from './prompt-builder.js';

/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */

/**
 * @typedef {Object} ConversationTurn
 * @property {string} id
 * @property {'prompt' | 'response'} role
 * @property {string} content
 * @property {string} createdAt
 */

/**
 * Conversation history manager for Continue flow.
 */
export class ContinueManager {
    constructor() {
        /** @type {ConversationTurn[]} */
        this.timeline = [];
        this.pendingResponse = '';
        this.isOpen = false;
    }

    toggleOpen() {
        this.isOpen = !this.isOpen;
    }

    /**
     * @param {string} prompt
     * @param {string} [response]
     */
    addTurn(prompt, response = '') {
        this.continueConversation(prompt, response);
    }

    /** @returns {string} */
    getHistoryText() {
        return this.buildConversationHistory();
    }

    /** @param {string} prompt */
    appendPrompt(prompt) {
        this.timeline.push({
            id: `turn_${Date.now().toString(36)}`,
            role: 'prompt',
            content: prompt,
            createdAt: new Date().toISOString(),
        });
    }

    /** @param {string} response */
    appendResponse(response) {
        this.pendingResponse = '';
        this.timeline.push({
            id: `turn_${Date.now().toString(36)}`,
            role: 'response',
            content: response,
            createdAt: new Date().toISOString(),
        });
    }

    /** @param {string} value */
    setPendingResponse(value) {
        this.pendingResponse = value;
    }

    /** @returns {string} */
    buildConversationHistory() {
        return this.timeline
            .map((turn) => {
                const label = turn.role === 'prompt' ? 'User' : 'Assistant';
                return `${label}:\n${turn.content}`;
            })
            .join('\n\n');
    }

    /**
     * @param {PromptDraftState} draft
     * @param {string} nextPromptTemplate
     * @returns {Record<string, unknown>}
     */
    buildContinueContext(draft, nextPromptTemplate = '') {
        return {
            ...draft.parameterValues,
            conversationHistory: this.buildConversationHistory(),
            previousResponse: [...this.timeline].reverse().find((turn) => turn.role === 'response')?.content ?? '',
            nextPrompt: nextPromptTemplate,
        };
    }

    /**
     * @param {(key: string, params?: Record<string, string | number>) => string} tr
     * @returns {string}
     */
    renderTimelineHtml(tr) {
        if (this.timeline.length === 0) {
            return `<p class="prompt-studio__continue-empty">${tr('promptStudio.continue.empty')}</p>`;
        }

        return this.timeline
            .map(
                (turn) => `<article class="prompt-studio__continue-turn prompt-studio__continue-turn--${turn.role}">
                <header>${turn.role === 'prompt' ? tr('promptStudio.continue.prompt') : tr('promptStudio.continue.response')}</header>
                <pre>${escapeHtml(turn.content)}</pre>
            </article>`,
            )
            .join('');
    }

    /**
     * @param {string} compiledPrompt
     * @param {string} response
     * @param {string} [followUpTemplate]
     * @returns {{ history: string, followUpContext: Record<string, unknown> }}
     */
    continueConversation(compiledPrompt, response, followUpTemplate = '') {
        this.appendPrompt(compiledPrompt);
        if (response.trim()) {
            this.appendResponse(response.trim());
        }

        const history = this.buildConversationHistory();
        const followUpContext = {
            conversationHistory: history,
            previousResponse: response.trim(),
            nextPrompt: followUpTemplate,
        };

        return { history, followUpContext };
    }

    reset() {
        this.timeline = [];
        this.pendingResponse = '';
    }
}

/**
 * @param {string} template
 * @param {Record<string, unknown>} context
 * @returns {string}
 */
export function renderContinuePrompt(template, context) {
    return renderTemplate(template, context);
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

export function createContinueManager() {
    return new ContinueManager();
}
