import { copyTextToClipboard } from '../pii-shared/tool-utils.js';
import { downloadTextFile } from '../discovery-shared/download.js';
import { bindPlanTransferUi } from '../discovery-shared/plan-transfer-ui.js';

const demoKey = 'bn-tools:governance-tool-demos:v1';

const texts = {
    'discovery.applyEmpty': 'Bitte erst Eingaben erfassen.',
    'discovery.leaveConfirm': 'Es gibt ungespeicherte Governance-Eingaben.',
    'discovery.planWarnTitle': 'Plan-Kontext',
    'discovery.planWarnBody': 'Dieser Report kann in die Plan-Aufgabe übernommen werden.',
    'discovery.planExportHint': 'Im Plan speichern schreibt Report und strukturierte Felder zurück.',
    savedDemo: 'Demo gespeichert.',
    copied: 'Report kopiert.',
    downloaded: 'Markdown geladen.',
};

function t(key) {
    return texts[key] || key;
}

function readConfig(root) {
    try {
        return JSON.parse(root.dataset.toolConfig || '{}');
    } catch {
        return {};
    }
}

function currentLocale() {
    return document.documentElement.lang === 'de' ? 'de' : 'en';
}

function localizedLabel(key, config) {
    const labels = config.fieldLabels && typeof config.fieldLabels === 'object' ? config.fieldLabels : {};
    const entry = labels[key];
    if (entry && typeof entry === 'object') {
        return entry[currentLocale()] || entry.en || entry.de || key;
    }

    return key;
}

function safeFilename(value) {
    return String(value || 'governance-tool-report')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '') || 'governance-tool-report';
}

function collect(root, config) {
    const note = root.querySelector('[data-governance-tool-note]')?.value?.trim() || '';
    const fields = Array.from(root.querySelectorAll('[data-governance-tool-field]')).map((input) => ({
        label: input.dataset[currentLocale() === 'de' ? 'fieldLabelDe' : 'fieldLabelEn'] || input.dataset.fieldLabel || input.name,
        help: input.dataset.fieldHelp || '',
        value: input.value.trim(),
    }));
    const filled = fields.filter((field) => field.value !== '');
    const open = fields.filter((field) => field.value === '');
    const score = fields.length === 0 ? 0 : Math.round((filled.length / fields.length) * 100);

    return {
        toolId: config.id || 'governance-tool',
        title: currentLocale() === 'de' ? (config.titleDe || config.title || 'Governance Tool') : (config.title || 'Governance Tool'),
        note,
        fields,
        filled,
        open,
        outputs: Array.isArray(config.outputs) ? config.outputs.map((output) => localizedLabel(output, config)) : [],
        reportSummary: String(config.reportSummary || '').trim(),
        score,
    };
}

function markdown(state) {
    const lines = [`# ${state.title}`, ''];
    if (state.reportSummary) {
        lines.push('## Report Zusammenfassung', '');
        lines.push(state.reportSummary, '');
    }
    if (state.note) {
        lines.push(`Kurznotiz: ${state.note}`, '');
    }
    lines.push('## Eingaben', '');
    for (const field of state.fields) {
        lines.push(`- ${field.label}: ${field.value || '(offen)'}`);
    }
    lines.push('', '## Erwartete Outputs', '');
    for (const output of state.outputs) {
        lines.push(`- ${output}`);
    }
    lines.push('', '## Validierung', '');
    lines.push(`- Vollständigkeit: ${state.score}%`);
    if (state.open.length) {
        lines.push(`- Offene Felder: ${state.open.map((field) => field.label).join(', ')}`);
        lines.push('- Noch zu klären:');
        state.open.forEach((field) => {
            lines.push(`  - ${field.label}: ${field.help || 'konkreten Stand, Owner und Quelle erfassen'}`);
        });
    } else {
        lines.push('- Offene Felder: keine');
    }
    lines.push('', '## Entscheidungshilfe', '');
    if (state.score >= 80) {
        lines.push('- Der Report ist als Entscheidungsgrundlage nutzbar. Nächster Schritt: Review mit Ownern und Übernahme in Plan oder Governance Session.');
    } else if (state.score >= 45) {
        lines.push('- Der Report ist ein Review-Entwurf. Nächster Schritt: offene Felder klären, bevor daraus Umsetzung oder Change Request entsteht.');
    } else {
        lines.push('- Der Report ist noch Discovery. Nächster Schritt: fehlende Basisinformationen mit Stakeholdern, Suppliern oder vorhandener Doku sammeln.');
    }

    return `${lines.join('\n')}\n`;
}

function planRows(state) {
    const rows = [];
    if (state.note) {
        rows.push({
            id: `${state.toolId}_note`,
            cells: {
                topic: 'Kurznotiz',
                value: state.note,
                status: 'captured',
            },
        });
    }
    state.fields
        .filter((field) => field.value !== '')
        .forEach((field, index) => {
            rows.push({
                id: `${state.toolId}_${index}`,
                cells: {
                    topic: field.label,
                    value: field.value,
                    status: 'captured',
                },
            });
        });
    return rows;
}

function saveDemo(state) {
    const raw = sessionStorage.getItem(demoKey);
    let entries = [];
    try {
        entries = raw ? JSON.parse(raw) : [];
    } catch {
        entries = [];
    }
    entries.unshift({
        ...state,
        savedAt: new Date().toISOString(),
    });
    sessionStorage.setItem(demoKey, JSON.stringify(entries.slice(0, 20)));
}

function applyPrefill(root, config) {
    const prefill = config.demoPrefill;
    if (!prefill || typeof prefill !== 'object') {
        return;
    }

    const note = root.querySelector('[data-governance-tool-note]');
    if (note && !note.value) {
        note.value = String(prefill.note || '');
    }

    const values = Array.isArray(prefill.fields) ? prefill.fields : [];
    root.querySelectorAll('[data-governance-tool-field]').forEach((input, index) => {
        if (!input.value && values[index]) {
            input.value = String(values[index]);
        }
    });
}

function initHeaderDrawer(root) {
    const drawer = root.querySelector('[data-governance-tool-header-drawer]');
    const drawerToggle = root.querySelector('[data-governance-tool-drawer-toggle]');
    const panels = Array.from(root.querySelectorAll('[data-governance-tool-panel]'));
    const tabs = Array.from(root.querySelectorAll('[data-governance-tool-panel-toggle]'));

    if (!drawer || !drawerToggle || panels.length === 0 || tabs.length === 0) {
        return;
    }

    let scrollAnchor = null;

    const rememberScroll = () => {
        scrollAnchor = { x: window.scrollX, y: window.scrollY };
    };

    const activatePanel = (targetId) => {
        panels.forEach((panel) => {
            panel.hidden = panel.id !== targetId;
        });
        tabs.forEach((tab) => {
            const isActive = tab.dataset.governanceToolPanelToggle === targetId;
            tab.classList.toggle('governance-hub__panel-tab--active', isActive);
            tab.setAttribute('aria-selected', String(isActive));
            tab.tabIndex = isActive ? 0 : -1;
        });
    };

    const keepScrollPosition = (callback) => {
        const scrollX = scrollAnchor?.x ?? window.scrollX;
        const scrollY = scrollAnchor?.y ?? window.scrollY;
        callback();
        window.requestAnimationFrame(() => {
            window.scrollTo(scrollX, scrollY);
            window.setTimeout(() => {
                window.scrollTo(scrollX, scrollY);
                scrollAnchor = null;
            }, 40);
        });
    };

    const sync = () => {
        drawerToggle.setAttribute('aria-expanded', String(!drawer.hidden));
    };

    drawerToggle.addEventListener('pointerdown', rememberScroll);
    drawerToggle.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            rememberScroll();
        }
    });
    drawerToggle.addEventListener('click', () => {
        keepScrollPosition(() => {
            drawer.hidden = !drawer.hidden;
            if (!drawer.hidden && !panels.some((panel) => !panel.hidden)) {
                activatePanel(tabs[0]?.dataset.governanceToolPanelToggle || panels[0]?.id || '');
            }
            drawerToggle.blur();
            sync();
        });
    });

    tabs.forEach((tab) => {
        tab.addEventListener('pointerdown', rememberScroll);
        tab.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                rememberScroll();
            }
        });
        tab.addEventListener('click', () => {
            const target = root.querySelector(`#${tab.dataset.governanceToolPanelToggle}`);
            if (!(target instanceof HTMLElement)) {
                return;
            }
            keepScrollPosition(() => {
                drawer.hidden = false;
                activatePanel(target.id);
                tab.blur();
                sync();
            });
        });
    });

    activatePanel(tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.governanceToolPanelToggle || tabs[0].dataset.governanceToolPanelToggle || panels[0].id);
    drawer.hidden = true;
    sync();
}

function mount(root) {
    const config = readConfig(root);
    const preview = root.querySelector('[data-governance-tool-preview]');
    const score = root.querySelector('[data-governance-tool-score]');
    const status = root.querySelector('[data-governance-tool-status]');
    const returnLink = root.querySelector('[data-return-to-plan]');

    let transferred = false;
    initHeaderDrawer(root);
    applyPrefill(root, config);
    let current = collect(root, config);

    function setStatus(message) {
        if (status) {
            status.textContent = message;
        }
    }

    function render() {
        current = collect(root, config);
        if (preview) {
            preview.textContent = markdown(current);
        }
        if (score) {
            score.textContent = String(current.score);
        }
        transferred = false;
    }

    const ctx = bindPlanTransferUi({
        root,
        t,
        getPayload: () => ({
            markdown: markdown(current),
            columns: [
                { id: 'topic', label: 'Topic' },
                { id: 'value', label: 'Value' },
                { id: 'status', label: 'Status' },
            ],
            rows: planRows(current),
        }),
        markTransferred: () => {
            transferred = true;
        },
        hasContent: () => current.note !== '' || current.filled.length > 0,
    });

    if (ctx && returnLink instanceof HTMLAnchorElement) {
        returnLink.href = ctx.returnUrl;
    }

    root.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('input', render);
        input.addEventListener('change', render);
    });

    root.querySelectorAll('[data-governance-tool-copy]').forEach((button) => button.addEventListener('click', async () => {
        await copyTextToClipboard(markdown(current));
        transferred = true;
        setStatus(t('copied'));
    }));

    root.querySelectorAll('[data-governance-tool-download]').forEach((button) => button.addEventListener('click', () => {
        downloadTextFile(`${safeFilename(current.title)}.md`, markdown(current), 'text/markdown;charset=utf-8');
        transferred = true;
        setStatus(t('downloaded'));
    }));

    root.querySelectorAll('[data-governance-tool-print]').forEach((button) => button.addEventListener('click', () => {
        transferred = true;
        window.print();
    }));

    root.querySelectorAll('[data-governance-tool-save-demo]').forEach((button) => button.addEventListener('click', () => {
        saveDemo(current);
        transferred = true;
        setStatus(t('savedDemo'));
    }));

    window.addEventListener('beforeunload', (event) => {
        if (transferred || (current.note === '' && current.filled.length === 0)) {
            return;
        }
        event.preventDefault();
        event.returnValue = '';
    });

    render();
}

document.querySelectorAll('[data-governance-tool-root]').forEach(mount);
