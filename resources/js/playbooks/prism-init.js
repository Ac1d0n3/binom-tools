import Prism from 'prismjs';

import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-typescript';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-sql';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-yaml';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-python';
import 'prismjs/components/prism-markdown';

/** @type {WeakMap<HTMLElement, string>} */
const originalCodeCache = new WeakMap();

/**
 * @param {string} highlightedCode
 * @returns {string}
 */
function appendLineNumbersMarkup(highlightedCode) {
    const linesCount = (highlightedCode.match(/\n(?!$)/g)?.length ?? 0) + 1;
    const lineSpans = '<span></span>'.repeat(linesCount);

    return `${highlightedCode}<span aria-hidden="true" class="line-numbers-rows">${lineSpans}</span>`;
}

/**
 * @param {HTMLElement} block
 */
function enhanceCodeBlock(block) {
    if (block.dataset.playbookEnhanced === 'true') return;

    const pre = block.querySelector('pre');
    const code = block.querySelector('code');

    if (!pre || !code) return;

    const language = block.dataset.language || 'text';
    const title = block.dataset.title || '';
    const highlight = block.dataset.highlight || '';

    if (highlight) {
        pre.dataset.line = highlight;
    }

    const header = document.createElement('div');
    header.className = 'playbook-code__header';

    const titleEl = document.createElement('div');
    titleEl.className = 'playbook-code__title';
    titleEl.textContent = title;

    const toolbar = document.createElement('div');
    toolbar.className = 'playbook-code__toolbar';

    const languageEl = document.createElement('span');
    languageEl.className = 'playbook-code__language';
    languageEl.textContent = language;

    const copyButton = document.createElement('button');
    copyButton.type = 'button';
    copyButton.className = 'playbook-code__copy';
    copyButton.setAttribute('aria-label', 'Copy code');
    copyButton.innerHTML = '<i class="fa-solid fa-copy" aria-hidden="true"></i>';

    copyButton.addEventListener('click', async () => {
        const text = originalCodeCache.get(code) ?? code.textContent ?? '';

        try {
            await navigator.clipboard.writeText(text);
        } catch {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            textArea.remove();
        }
    });

    toolbar.appendChild(languageEl);
    toolbar.appendChild(copyButton);

    if (title) {
        header.appendChild(titleEl);
    }

    header.appendChild(toolbar);
    block.insertBefore(header, pre);

    if (!originalCodeCache.has(code)) {
        originalCodeCache.set(code, code.textContent ?? '');
    }

    block.dataset.playbookEnhanced = 'true';
}

/**
 * @param {HTMLElement} block
 */
function highlightCodeBlock(block) {
    enhanceCodeBlock(block);

    const pre = block.querySelector('pre');
    const code = block.querySelector('code');

    if (!pre || !code) return;

    if (!originalCodeCache.has(code)) {
        originalCodeCache.set(code, code.textContent ?? '');
    }

    const language = block.dataset.language || 'text';
    const raw = originalCodeCache.get(code) ?? '';
    const grammar = Prism.languages[language];

    pre.classList.add('line-numbers', `language-${language}`);
    code.className = `language-${language}`;

    if (!grammar) {
        code.textContent = raw;
        return;
    }

    let highlighted = Prism.highlight(raw, grammar, language);
    highlighted = appendLineNumbersMarkup(highlighted);
    code.innerHTML = highlighted;
}

/**
 * @param {ParentNode} root
 */
function highlightPlaybookCode(root) {
    root.querySelectorAll('.playbook-code').forEach((block) => {
        try {
            highlightCodeBlock(block);
        } catch (error) {
            console.warn('Failed to highlight playbook code block.', error);
        }
    });
}

/**
 * @param {ParentNode} root
 */
export function initPlaybookPrism(root) {
    highlightPlaybookCode(root);

    window.addEventListener('binom-tools:color-scheme', () => {
        highlightPlaybookCode(root);
    });
}
