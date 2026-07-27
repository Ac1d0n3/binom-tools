/**
 * Glossary funny meeting bingo — mark cells + print.
 */

function initBingo(root) {
    const printBtn = root.querySelector('[data-bingo-print]');
    if (printBtn instanceof HTMLButtonElement) {
        printBtn.addEventListener('click', () => {
            window.print();
        });
    }

    root.querySelectorAll('[data-bingo-cell]:not([data-bingo-free])').forEach((cell) => {
        if (!(cell instanceof HTMLButtonElement)) {
            return;
        }
        cell.addEventListener('click', () => {
            const marked = cell.getAttribute('aria-pressed') === 'true';
            cell.setAttribute('aria-pressed', marked ? 'false' : 'true');
            cell.classList.toggle('glossary-bingo-cell--marked', !marked);
        });
    });
}

document.querySelectorAll('[data-glossary-bingo]').forEach((root) => {
    if (root instanceof HTMLElement) {
        initBingo(root);
    }
});
