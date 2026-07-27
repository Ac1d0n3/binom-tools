function currentLocale() {
    return document.documentElement.lang === 'de' ? 'de' : 'en';
}

function collectState(root) {
    return Array.from(root.querySelectorAll('[data-discovery-step]')).map((step, index) => {
        const titleEl =
            step.querySelector('[data-discovery-title]') ||
            step.querySelector('h2') ||
            step.querySelector('h3');
        const title =
            titleEl?.getAttribute(currentLocale() === 'de' ? 'data-text-de' : 'data-text-en') ||
            titleEl?.textContent?.trim() ||
            `Step ${index + 1}`;
        const note = step.querySelector('[data-discovery-note]')?.value?.trim() || '';
        const done = Boolean(step.querySelector('[data-discovery-done]')?.checked);

        return {
            id: step.getAttribute('data-discovery-step') || `step-${index + 1}`,
            title,
            note,
            done,
        };
    });
}

function toMarkdown(steps) {
    const lines = ['# Governance Discovery Canvas', '', `Generated: ${new Date().toISOString()}`, ''];
    steps.forEach((step, index) => {
        lines.push(`## ${index + 1}. ${step.title}`);
        lines.push(`- Status: ${step.done ? 'done' : 'open'}`);
        if (step.note) {
            lines.push(`- Note: ${step.note}`);
        }
        lines.push('');
    });

    return lines.join('\n');
}

async function copyText(text) {
    if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(text);

        return;
    }

    const area = document.createElement('textarea');
    area.value = text;
    document.body.appendChild(area);
    area.select();
    document.execCommand('copy');
    area.remove();
}

function downloadText(filename, text, type = 'text/markdown') {
    const blob = new Blob([text], { type: `${type};charset=utf-8` });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.click();
    URL.revokeObjectURL(url);
}

function setStatus(root, message) {
    const status = root.querySelector('[data-discovery-status]');
    if (!status) {
        return;
    }
    status.hidden = !message;
    status.textContent = message || '';
}

function refreshPreview(root) {
    const preview = root.querySelector('[data-discovery-preview]');
    if (!preview) {
        return;
    }
    preview.textContent = toMarkdown(collectState(root));
}

function initDiscoveryCanvas(root) {
    const sync = () => refreshPreview(root);
    root.addEventListener('input', sync);
    root.addEventListener('change', sync);

    const accordion = root.querySelector('[data-discovery-steps]');
    accordion?.addEventListener(
        'toggle',
        (event) => {
            const target = event.target;
            if (!(target instanceof HTMLDetailsElement) || !target.open) {
                return;
            }
            if (!target.classList.contains('governance-discovery-steps__details')) {
                return;
            }
            accordion.querySelectorAll('details.governance-discovery-steps__details[open]').forEach((details) => {
                if (details !== target) {
                    details.open = false;
                }
            });
        },
        true,
    );

    root.querySelector('[data-discovery-copy-md]')?.addEventListener('click', async () => {
        const md = toMarkdown(collectState(root));
        await copyText(md);
        setStatus(root, currentLocale() === 'de' ? 'Markdown kopiert.' : 'Markdown copied.');
    });

    root.querySelector('[data-discovery-download-md]')?.addEventListener('click', () => {
        downloadText('governance-discovery.md', toMarkdown(collectState(root)));
        setStatus(root, currentLocale() === 'de' ? 'Markdown geladen.' : 'Markdown downloaded.');
    });

    root.querySelector('[data-discovery-copy-json]')?.addEventListener('click', async () => {
        const json = JSON.stringify({ steps: collectState(root), exportedAt: new Date().toISOString() }, null, 2);
        await copyText(json);
        setStatus(root, currentLocale() === 'de' ? 'JSON kopiert.' : 'JSON copied.');
    });

    root.querySelector('[data-discovery-reset]')?.addEventListener('click', () => {
        root.querySelectorAll('[data-discovery-note]').forEach((el) => {
            el.value = '';
        });
        root.querySelectorAll('[data-discovery-done]').forEach((el) => {
            el.checked = false;
        });
        sync();
        setStatus(root, currentLocale() === 'de' ? 'Zurückgesetzt.' : 'Reset.');
    });

    sync();
}

document.querySelectorAll('[data-governance-discovery-canvas]').forEach((root) => {
    initDiscoveryCanvas(root);
});
