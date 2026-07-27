/**
 * Glossary buzzword quiz — modal + single/multi select, optional profile save.
 */

import { openSharedModal, closeSharedModal, ensureDialogOnBody } from './shared/modal.js';
import { getShellLabel } from './locale';

const LOCAL_STORAGE_KEY = 'binom-tools-glossary-quiz-results';

function csrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta instanceof HTMLMetaElement ? meta.content : '';
}

function setQuestionsJson(root, questions) {
    let node = root.querySelector('[data-glossary-quiz-questions]');
    if (!(node instanceof HTMLScriptElement)) {
        node = document.createElement('script');
        node.type = 'application/json';
        node.setAttribute('data-glossary-quiz-questions', '');
        root.appendChild(node);
    }
    node.textContent = JSON.stringify(Array.isArray(questions) ? questions : []);
}

function parseQuestions(root) {
    const node = root.querySelector('[data-glossary-quiz-questions]');
    if (!(node instanceof HTMLScriptElement)) {
        return [];
    }
    try {
        const data = JSON.parse(node.textContent || '[]');
        return Array.isArray(data) ? data : [];
    } catch {
        return [];
    }
}

function saveLocalResults(score, total) {
    try {
        const raw = localStorage.getItem(LOCAL_STORAGE_KEY);
        const current = raw ? JSON.parse(raw) : {};
        const attempts = Array.isArray(current.attempts) ? current.attempts : [];
        attempts.push({
            at: new Date().toISOString(),
            score,
            total,
            mode: 'mixed',
        });
        let bestScore = Number(current.bestScore) || 0;
        let bestTotal = Number(current.bestTotal) || 0;
        if (score > bestScore || (score === bestScore && total >= bestTotal) || bestTotal === 0) {
            bestScore = score;
            bestTotal = total;
        }
        localStorage.setItem(
            LOCAL_STORAGE_KEY,
            JSON.stringify({
                attempts: attempts.slice(-50),
                bestScore,
                bestTotal,
                attemptCount: (Number(current.attemptCount) || 0) + 1,
            }),
        );
    } catch {
        // ignore quota / private mode
    }
}

function setsEqual(a, b) {
    const left = [...new Set(a)].map(String).sort();
    const right = [...new Set(b)].map(String).sort();
    if (left.length !== right.length) {
        return false;
    }
    return left.every((value, index) => value === right[index]);
}

function t(key, fallback) {
    const label = getShellLabel(key);
    return label && label !== key ? label : fallback;
}

/**
 * @param {HTMLElement} root
 */
function initQuizRoot(root) {
    const canSave = root.dataset.canSave === '1';
    const saveUrl = root.dataset.quizSaveUrl || '';
    const dataUrl = root.dataset.quizDataUrl || '';

    const setupForm = root.querySelector('[data-quiz-setup-form]');
    const countSelect = root.querySelector('[data-quiz-count-select]');
    const startBtn = root.querySelector('[data-quiz-start]');
    const progressEl = root.querySelector('[data-quiz-progress]');
    const stemEl = root.querySelector('[data-quiz-stem]');
    const promptEl = root.querySelector('[data-quiz-prompt]');
    const hintEl = root.querySelector('[data-quiz-hint]');
    const choicesEl = root.querySelector('[data-quiz-choices]');
    const feedbackEl = root.querySelector('[data-quiz-feedback]');
    const checkBtn = root.querySelector('[data-quiz-check]');
    const nextBtn = root.querySelector('[data-quiz-next]');
    const resultEl = root.querySelector('[data-quiz-result]');
    const scoreEl = root.querySelector('[data-quiz-score]');
    const saveBtn = root.querySelector('[data-quiz-save]');
    const saveStatusEl = root.querySelector('[data-quiz-save-status]');
    const retryBtn = root.querySelector('[data-quiz-retry]');
    const ui = root.querySelector('[data-glossary-quiz-ui]');

    if (
        !(progressEl instanceof HTMLElement) ||
        !(stemEl instanceof HTMLElement) ||
        !(promptEl instanceof HTMLElement) ||
        !(hintEl instanceof HTMLElement) ||
        !(choicesEl instanceof HTMLElement) ||
        !(feedbackEl instanceof HTMLElement) ||
        !(checkBtn instanceof HTMLButtonElement) ||
        !(nextBtn instanceof HTMLButtonElement) ||
        !(resultEl instanceof HTMLElement) ||
        !(scoreEl instanceof HTMLElement) ||
        !(saveBtn instanceof HTMLButtonElement) ||
        !(saveStatusEl instanceof HTMLElement) ||
        !(ui instanceof HTMLElement)
    ) {
        return;
    }

    /** @type {any[]} */
    let questions = parseQuestions(root);
    let index = 0;
    let score = 0;
    let answered = false;
    let finalScore = 0;
    let finalTotal = 0;
    let started = false;

    function resetPlayState() {
        index = 0;
        score = 0;
        answered = false;
        finalScore = 0;
        finalTotal = questions.length;
        feedbackEl.hidden = true;
        feedbackEl.textContent = '';
        resultEl.hidden = true;
        checkBtn.hidden = true;
        nextBtn.hidden = true;
        saveStatusEl.hidden = true;
        saveStatusEl.textContent = '';
    }

    function showSetup() {
        started = false;
        if (setupForm instanceof HTMLElement) {
            setupForm.hidden = false;
        }
        ui.hidden = true;
        resetPlayState();
        choicesEl.innerHTML = '';
        stemEl.textContent = '';
        promptEl.textContent = '';
        promptEl.hidden = true;
        progressEl.textContent = '';
        hintEl.hidden = true;
    }

    function renderQuestion() {
        const question = questions[index];
        if (!question) {
            showResult();
            return;
        }

        answered = false;
        feedbackEl.hidden = true;
        feedbackEl.textContent = '';
        resultEl.hidden = true;
        nextBtn.hidden = true;
        checkBtn.hidden = question.type !== 'multi';
        checkBtn.disabled = false;

        progressEl.textContent = t('glossary.quiz.progress', 'Question {{n}} of {{total}}')
            .replace('{{n}}', String(index + 1))
            .replace('{{total}}', String(questions.length));

        const stem = String(question.stem || '').trim();
        const body = String(question.prompt || '').trim();
        const isMulti = question.type === 'multi';

        if (stem !== '') {
            stemEl.textContent = stem;
        } else if (isMulti) {
            stemEl.textContent = body;
        } else if (question.promptKind === 'def_to_term') {
            stemEl.textContent = t(
                'glossary.quiz.stemDefToTerm',
                'Which term matches this definition?',
            );
        } else {
            stemEl.textContent = t(
                'glossary.quiz.stemTermToDef',
                'Which definition matches this term?',
            );
        }

        if (body !== '' && !(isMulti && stem === '')) {
            // For def→term: show definition under the question stem.
            // For term→def: stem already includes the term; skip duplicate body.
            if (question.promptKind === 'term_to_def' && stem !== '') {
                promptEl.hidden = true;
                promptEl.textContent = '';
            } else if (isMulti && stem !== '') {
                promptEl.hidden = true;
                promptEl.textContent = '';
            } else {
                promptEl.hidden = false;
                promptEl.textContent = body;
            }
        } else {
            promptEl.hidden = true;
            promptEl.textContent = '';
        }

        hintEl.hidden = !isMulti;
        hintEl.textContent = isMulti ? t('glossary.quiz.multiHint', 'Select all that apply.') : '';

        choicesEl.innerHTML = '';
        const choices = Array.isArray(question.choices) ? question.choices : [];
        choices.forEach((choice) => {
            const id = String(choice.id || '');
            const label = String(choice.label || '');
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'glossary-quiz__choice';
            btn.dataset.choiceId = id;
            btn.setAttribute('aria-pressed', 'false');
            btn.textContent = label;
            btn.addEventListener('click', () => {
                if (answered) {
                    return;
                }
                if (isMulti) {
                    const pressed = btn.getAttribute('aria-pressed') === 'true';
                    btn.setAttribute('aria-pressed', pressed ? 'false' : 'true');
                    btn.classList.toggle('is-selected', !pressed);
                } else {
                    evaluate([id]);
                }
            });
            choicesEl.appendChild(btn);
        });
    }

    function selectedIds() {
        return [...choicesEl.querySelectorAll('.glossary-quiz__choice[aria-pressed="true"]')].map(
            (el) => String(el.dataset.choiceId || ''),
        );
    }

    function evaluate(selected) {
        const question = questions[index];
        if (!question || answered) {
            return;
        }
        answered = true;
        checkBtn.hidden = true;

        const correctIds = Array.isArray(question.correctIds) ? question.correctIds.map(String) : [];
        const ok = setsEqual(selected, correctIds);
        if (ok) {
            score += 1;
        }

        [...choicesEl.querySelectorAll('.glossary-quiz__choice')].forEach((el) => {
            const id = String(el.dataset.choiceId || '');
            const isCorrect = correctIds.includes(id);
            const isSelected = selected.includes(id);
            el.disabled = true;
            el.classList.toggle('is-correct', isCorrect);
            el.classList.toggle('is-wrong', isSelected && !isCorrect);
            el.classList.toggle('is-selected', isSelected);
        });

        feedbackEl.hidden = false;
        feedbackEl.textContent = ok
            ? t('glossary.quiz.correct', 'Correct!')
            : t('glossary.quiz.incorrect', 'Not quite.');
        feedbackEl.classList.toggle('is-correct', ok);
        feedbackEl.classList.toggle('is-wrong', !ok);

        nextBtn.textContent =
            index >= questions.length - 1
                ? t('glossary.quiz.finish', 'See results')
                : t('glossary.quiz.next', 'Next');
        nextBtn.hidden = false;
    }

    function showResult() {
        finalScore = score;
        finalTotal = questions.length;
        progressEl.textContent = '';
        stemEl.textContent = '';
        promptEl.textContent = '';
        promptEl.hidden = true;
        hintEl.hidden = true;
        choicesEl.innerHTML = '';
        feedbackEl.hidden = true;
        checkBtn.hidden = true;
        nextBtn.hidden = true;
        resultEl.hidden = false;
        scoreEl.textContent = t('glossary.quiz.score', 'You scored {{score}} / {{total}}')
            .replace('{{score}}', String(finalScore))
            .replace('{{total}}', String(finalTotal));

        saveLocalResults(finalScore, finalTotal);

        if (canSave && saveUrl) {
            saveBtn.hidden = false;
            saveBtn.disabled = false;
        } else {
            saveBtn.hidden = true;
        }
        saveStatusEl.hidden = true;
        saveStatusEl.textContent = '';
    }

    function beginWithQuestions(nextQuestions) {
        questions = Array.isArray(nextQuestions) ? nextQuestions : [];
        setQuestionsJson(root, questions);
        if (setupForm instanceof HTMLElement) {
            setupForm.hidden = true;
        }
        ui.hidden = false;
        started = true;
        resetPlayState();

        if (questions.length === 0) {
            progressEl.textContent = t('glossary.quiz.empty', 'Not enough glossary terms to build a quiz.');
            return;
        }
        renderQuestion();
    }

    async function fetchAndStart() {
        const count =
            countSelect instanceof HTMLSelectElement ? Number(countSelect.value) || 10 : 10;
        const selectedCategories = Array.from(
            root.querySelectorAll('input[data-quiz-category]:checked'),
        )
            .map((input) => (input instanceof HTMLInputElement ? input.value : ''))
            .filter(Boolean);
        if (startBtn instanceof HTMLButtonElement) {
            startBtn.disabled = true;
        }
        try {
            if (!dataUrl) {
                beginWithQuestions(parseQuestions(root));
                return;
            }
            const url = new URL(dataUrl, window.location.href);
            url.searchParams.set('count', String(count));
            url.searchParams.delete('categories');
            url.searchParams.delete('categories[]');
            for (const categoryId of selectedCategories) {
                url.searchParams.append('categories[]', categoryId);
            }
            const response = await fetch(url.toString(), {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!response.ok) {
                throw new Error('quiz fetch failed');
            }
            const payload = await response.json();
            beginWithQuestions(payload.questions || []);
        } catch {
            progressEl.textContent = t('glossary.quiz.loadFailed', 'Could not load quiz. Try again.');
            ui.hidden = false;
            if (setupForm instanceof HTMLElement) {
                setupForm.hidden = false;
            }
        } finally {
            if (startBtn instanceof HTMLButtonElement) {
                startBtn.disabled = false;
            }
        }
    }

    checkBtn.addEventListener('click', () => {
        evaluate(selectedIds());
    });

    nextBtn.addEventListener('click', () => {
        index += 1;
        renderQuestion();
    });

    saveBtn.addEventListener('click', async () => {
        if (!saveUrl) {
            return;
        }
        saveBtn.disabled = true;
        saveStatusEl.hidden = false;
        saveStatusEl.textContent = t('glossary.quiz.saving', 'Saving…');
        try {
            const response = await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    score: finalScore,
                    total: finalTotal,
                    mode: 'mixed',
                }),
            });
            if (!response.ok) {
                throw new Error('save failed');
            }
            saveStatusEl.textContent = t('glossary.quiz.saved', 'Saved to your profile.');
        } catch {
            saveBtn.disabled = false;
            saveStatusEl.textContent = t('glossary.quiz.saveFailed', 'Could not save. Try again.');
        }
    });

    if (setupForm instanceof HTMLFormElement) {
        setupForm.addEventListener('submit', (event) => {
            event.preventDefault();
            void fetchAndStart();
        });
    }

    if (retryBtn instanceof HTMLButtonElement) {
        retryBtn.addEventListener('click', () => {
            showSetup();
        });
    }

    // Standalone page with preloaded questions (legacy) auto-starts.
    if (!dataUrl && questions.length > 0) {
        beginWithQuestions(questions);
    } else {
        showSetup();
    }

    root.__glossaryQuizReset = showSetup;
}

function initQuizModal() {
    const dialog = document.querySelector('[data-glossary-quiz-modal]');
    if (!(dialog instanceof HTMLDialogElement)) {
        return;
    }

    ensureDialogOnBody(dialog);

    const openBtn = document.querySelector('[data-glossary-quiz-open]');
    const closeBtn = dialog.querySelector('[data-glossary-quiz-close]');
    const panel = dialog.querySelector('[data-glossary-quiz]');

    if (panel instanceof HTMLElement) {
        initQuizRoot(panel);
    }

    const open = () => {
        if (typeof panel?.__glossaryQuizReset === 'function') {
            panel.__glossaryQuizReset();
        }
        openSharedModal(dialog);
    };

    const close = () => {
        closeSharedModal(dialog);
        if (typeof panel?.__glossaryQuizReset === 'function') {
            panel.__glossaryQuizReset();
        }
    };

    if (openBtn instanceof HTMLElement) {
        openBtn.addEventListener('click', (event) => {
            event.preventDefault();
            open();
        });
    }
    if (closeBtn instanceof HTMLElement) {
        closeBtn.addEventListener('click', (event) => {
            event.preventDefault();
            close();
        });
    }
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            close();
        }
    });
    dialog.addEventListener('cancel', (event) => {
        event.preventDefault();
        close();
    });

    if (dialog.dataset.glossaryQuizAutopen === '1') {
        open();
    }
}

document.querySelectorAll('[data-glossary-quiz]').forEach((root) => {
    if (!(root instanceof HTMLElement)) {
        return;
    }
    // Modal panel is initialized via initQuizModal.
    if (root.closest('[data-glossary-quiz-modal]')) {
        return;
    }
    initQuizRoot(root);
});

initQuizModal();
