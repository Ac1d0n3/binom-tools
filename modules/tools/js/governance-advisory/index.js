import { copyTextToClipboard } from '../pii-shared/tool-utils.js';
import { downloadTextFile } from '../discovery-shared/download.js';
import { bindLeaveGuard } from '../discovery-shared/leave-guard.js';
import { bindPlanTransferUi } from '../discovery-shared/plan-transfer-ui.js';
import { deleteGovernanceToolRecord, recordsForTool, upsertGovernanceToolRecord } from '../governance-tool-workspace-store.js';
import { acceptKpiIntake, deleteKpiIntake, loadKpiWorkspace, upsertKpiIntake } from '../kpi-workspace-store.js';
import { preferredProductIds, stackBuilderContextBanner } from '../../../governance/js/advisor-guidance.js';
import { mountStackBuilder, normalizeSelection, preferredProductIdsForStartingPoint, readCustomStack, readSavedStacksLocal, readStartingPointProduct, saveNamedStackLocal, startingPointStackBanner, summarizeSelection, syncSelectionToToolFields, writeCustomStack, writeSavedStacksLocal } from '../../../governance/js/stack-builder.js';

const texts = {
    'discovery.applyEmpty': 'Bitte erst Eingaben erfassen.',
    'discovery.leaveConfirm': 'Es gibt ungespeicherte Governance-Eingaben.',
    'discovery.planWarnTitle': 'Plan-Kontext',
    'discovery.planWarnBody': 'Dieser Report kann in die Plan-Aufgabe übernommen werden.',
    'discovery.planExportHint': 'Im Plan speichern schreibt Report und strukturierte Felder zurück.',
    savedDemo: 'Gespeichert.',
    copied: 'Report kopiert.',
    downloaded: 'Markdown geladen.',
    kpiSaved: 'KPI-Intake gespeichert.',
    kpiAccepted: 'KPI-Intake ins Register übernommen.',
    kpiDeleted: 'KPI-Intake gelöscht.',
    kpiOpened: 'KPI-Intake geöffnet.',
    kpiNew: 'Neue KPI-Klärung gestartet.',
    kpiEmpty: 'Noch kein Intake gespeichert.',
    recordSaved: 'Arbeitsstand gespeichert.',
    recordDeleted: 'Arbeitsstand gelöscht.',
    recordOpened: 'Arbeitsstand geöffnet.',
    recordNew: 'Neuer Arbeitsstand gestartet.',
    recordEmpty: 'Noch nichts gespeichert.',
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
    const fields = Array.from(root.querySelectorAll('[data-governance-tool-field]')).map((input) => {
        const labelEn = input.dataset.fieldLabelEn || input.dataset.fieldLabel || input.name;
        const labelDe = input.dataset.fieldLabelDe || labelEn;
        return {
            label: currentLocale() === 'de' ? labelDe : labelEn,
            labelDe,
            labelEn,
            help: input.dataset.fieldHelp || '',
            value: input.value.trim(),
        };
    });
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

function fieldValueByLabel(state, labelEn) {
    return state.fields.find((field) => field.labelEn === labelEn)?.value || '';
}

function intakeTitle(state) {
    return fieldValueByLabel(state, 'KPI') || state.note || state.title || 'KPI Intake';
}

function recordTitle(state) {
    const filledField = state.fields.find((field) => field.value);
    return filledField?.value || state.note || state.title || 'Governance Arbeitsstand';
}

function formatDate(value) {
    if (!value) {
        return '';
    }
    try {
        return new Intl.DateTimeFormat(currentLocale() === 'de' ? 'de-DE' : 'en-US', {
            dateStyle: 'short',
            timeStyle: 'short',
        }).format(new Date(value));
    } catch {
        return String(value);
    }
}

function fillIntakeForm(root, intake) {
    const note = root.querySelector('[data-governance-tool-note]');
    if (note) {
        note.value = intake.note || '';
    }

    const fields = Array.isArray(intake.fields) ? intake.fields : [];
    root.querySelectorAll('[data-governance-tool-field]').forEach((input, index) => {
        const labelEn = input.dataset.fieldLabelEn || input.dataset.fieldLabel || input.name;
        const match = fields.find((field) => field.labelEn === labelEn) || fields[index];
        input.value = match?.value || '';
    });
}

function clearIntakeForm(root) {
    const note = root.querySelector('[data-governance-tool-note]');
    if (note) {
        note.value = '';
    }
    root.querySelectorAll('[data-governance-tool-field]').forEach((input) => {
        input.value = '';
    });
}

function initKpiIntakeManager(root, getState, render, setStatus, markPristine = () => {}) {
    const manager = root.querySelector('[data-kpi-intake-manager]');
    const list = root.querySelector('[data-kpi-intake-list]');
    const managerStatus = root.querySelector('[data-kpi-intake-status]');
    const btnNew = Array.from(root.querySelectorAll('[data-kpi-intake-new]'));
    const btnSave = Array.from(root.querySelectorAll('[data-kpi-intake-save]'));
    const btnAccept = Array.from(root.querySelectorAll('[data-kpi-intake-accept]'));
    if (!manager || !list) {
        return null;
    }

    let activeId = null;
    let lastSavedSignature = '';

    const currentSignature = () => {
        const state = getState();
        return JSON.stringify({
            note: state.note,
            fields: state.fields.map((field) => [field.labelEn || field.label, field.value]),
        });
    };

    const hasCurrentContent = () => {
        const state = getState();
        return state.note !== '' || state.filled.length > 0;
    };

    const syncActions = () => {
        const hasContent = hasCurrentContent();
        const hasUnsavedChanges = hasContent && currentSignature() !== lastSavedSignature;
        btnSave.forEach((button) => {
            const isHeaderAction = button.dataset.kpiSavePlacement === 'header';
            button.disabled = !hasUnsavedChanges;
            button.setAttribute('aria-disabled', String(!hasUnsavedChanges));
        });
        btnAccept.forEach((button) => {
            button.disabled = !hasContent;
            button.setAttribute('aria-disabled', String(!hasContent));
        });
    };

    const status = (message) => {
        if (managerStatus) {
            managerStatus.textContent = message;
        }
        setStatus(message);
    };

    const saveCurrent = () => {
        const state = getState();
        const saved = upsertKpiIntake({
            id: activeId || undefined,
            title: intakeTitle(state),
            note: state.note,
            fields: state.fields,
        });
        activeId = saved.id;
        lastSavedSignature = currentSignature();
        markPristine();
        renderList();
        syncActions();
        status(t('kpiSaved'));
        return saved;
    };

    const openIntake = (id) => {
        const intake = loadKpiWorkspace().intakes.find((item) => item.id === id);
        if (!intake) {
            return;
        }
        activeId = intake.id;
        fillIntakeForm(root, intake);
        render();
        lastSavedSignature = currentSignature();
        markPristine();
        renderList();
        syncActions();
        status(t('kpiOpened'));
    };

    const deleteIntake = (id) => {
        deleteKpiIntake(id);
        if (activeId === id) {
            activeId = null;
            lastSavedSignature = '';
        }
        renderList();
        syncActions();
        status(t('kpiDeleted'));
    };

    const acceptCurrent = () => {
        const saved = saveCurrent();
        acceptKpiIntake(saved);
        renderList();
        syncActions();
        status(t('kpiAccepted'));
    };

    function renderList() {
        const workspace = loadKpiWorkspace();
        list.innerHTML = '';
        if (workspace.intakes.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'governance-advisory-tool__kpi-empty';
            empty.textContent = t('kpiEmpty');
            list.appendChild(empty);
            syncActions();
            return;
        }

        workspace.intakes.slice(0, 6).forEach((intake) => {
            const title = intake.title || 'KPI Intake';
            const done = intake.acceptedAt ? 'Übernommen' : 'Entwurf';
            const card = document.createElement('article');
            card.className = 'governance-advisory-tool__kpi-card';
            card.classList.toggle('governance-advisory-tool__kpi-card--active', intake.id === activeId);
            const body = document.createElement('div');
            const heading = document.createElement('strong');
            heading.textContent = title;
            const meta = document.createElement('span');
            meta.textContent = `${done} · ${formatDate(intake.updatedAt)}`;
            body.append(heading, meta);

            const actions = document.createElement('div');
            actions.className = 'governance-advisory-tool__kpi-card-actions';
            [
                ['kpiOpen', 'Öffnen'],
                ['kpiAccept', 'Übernehmen'],
                ['kpiDelete', 'Löschen'],
            ].forEach(([key, label]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.dataset[key] = intake.id;
                button.textContent = label;
                actions.appendChild(button);
            });

            card.append(body, actions);
            list.appendChild(card);
        });
        syncActions();
    }

    btnNew.forEach((button) => button.addEventListener('click', () => {
        activeId = null;
        lastSavedSignature = '';
        clearIntakeForm(root);
        render();
        markPristine();
        renderList();
        syncActions();
        status(t('kpiNew'));
    }));

    btnSave.forEach((button) => button.addEventListener('click', saveCurrent));
    btnAccept.forEach((button) => button.addEventListener('click', acceptCurrent));
    list.addEventListener('click', (event) => {
        const button = event.target instanceof HTMLElement ? event.target.closest('button') : null;
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }
        if (button.dataset.kpiOpen) {
            openIntake(button.dataset.kpiOpen);
        }
        if (button.dataset.kpiAccept) {
            openIntake(button.dataset.kpiAccept);
            acceptCurrent();
        }
        if (button.dataset.kpiDelete && window.confirm('Diesen KPI-Intake löschen?')) {
            deleteIntake(button.dataset.kpiDelete);
        }
    });

    renderList();
    syncActions();
    return { renderList, syncActions };
}

function initGovernanceRecordManager(root, config, getState, render, setStatus, markPristine = () => {}) {
    const manager = root.querySelector('[data-governance-record-manager]');
    const list = root.querySelector('[data-governance-record-list]');
    const managerStatus = root.querySelector('[data-governance-record-status]');
    const btnNew = Array.from(root.querySelectorAll('[data-governance-record-new]'));
    const btnSave = Array.from(root.querySelectorAll('[data-governance-record-save]'));

    if (!manager || !list) {
        return null;
    }

    let activeId = null;
    let lastSavedSignature = '';

    const currentSignature = () => {
        const state = getState();
        return JSON.stringify({
            note: state.note,
            fields: state.fields.map((field) => [field.labelEn || field.label, field.value]),
        });
    };

    const hasCurrentContent = () => {
        const state = getState();
        return state.note !== '' || state.filled.length > 0;
    };

    const syncActions = () => {
        const state = getState();
        const hasContent = state.note !== '' || state.filled.length > 0;
        const hasUnsavedChanges = hasContent && currentSignature() !== lastSavedSignature;
        btnSave.forEach((button) => {
            button.disabled = !hasUnsavedChanges;
            button.setAttribute('aria-disabled', String(!hasUnsavedChanges));
        });
    };

    const status = (message) => {
        if (managerStatus) {
            managerStatus.textContent = message;
        }
        setStatus(message);
    };

    const saveCurrent = () => {
        const state = getState();
        if (!hasCurrentContent()) {
            syncActions();
            return null;
        }

        const saved = upsertGovernanceToolRecord({
            id: activeId || undefined,
            toolId: config.id || state.toolId,
            title: recordTitle(state),
            note: state.note,
            fields: state.fields,
            reportMarkdown: markdown(state),
            score: state.score,
        });
        activeId = saved.id;
        lastSavedSignature = currentSignature();
        markPristine();
        renderList();
        syncActions();
        status(t('recordSaved'));
        return saved;
    };

    const openRecord = (id) => {
        const record = recordsForTool(config.id || 'governance-tool').find((item) => item.id === id);
        if (!record) {
            return;
        }

        activeId = record.id;
        fillIntakeForm(root, record);
        render();
        lastSavedSignature = currentSignature();
        markPristine();
        renderList();
        syncActions();
        status(t('recordOpened'));
    };

    const deleteRecord = (id) => {
        deleteGovernanceToolRecord(config.id || 'governance-tool', id);
        if (activeId === id) {
            activeId = null;
            lastSavedSignature = '';
            clearIntakeForm(root);
            render();
            markPristine();
        }
        renderList();
        syncActions();
        status(t('recordDeleted'));
    };

    function renderList() {
        const records = recordsForTool(config.id || 'governance-tool');
        list.innerHTML = '';
        if (records.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'governance-advisory-tool__kpi-empty';
            empty.textContent = t('recordEmpty');
            list.appendChild(empty);
            syncActions();
            return;
        }

        records.slice(0, 8).forEach((record) => {
            const card = document.createElement('article');
            card.className = 'governance-advisory-tool__kpi-card';
            card.classList.toggle('governance-advisory-tool__kpi-card--active', record.id === activeId);

            const body = document.createElement('div');
            const heading = document.createElement('strong');
            heading.textContent = record.title || 'Governance Arbeitsstand';
            const meta = document.createElement('span');
            meta.textContent = `${record.score || 0}% · ${formatDate(record.updatedAt)}`;
            body.append(heading, meta);

            const actions = document.createElement('div');
            actions.className = 'governance-advisory-tool__kpi-card-actions';
            [
                ['governanceOpen', 'Öffnen'],
                ['governanceDelete', 'Löschen'],
            ].forEach(([key, label]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.dataset[key] = record.id;
                button.textContent = label;
                actions.appendChild(button);
            });

            card.append(body, actions);
            list.appendChild(card);
        });
        syncActions();
    }

    btnNew.forEach((button) => button.addEventListener('click', () => {
        activeId = null;
        lastSavedSignature = '';
        clearIntakeForm(root);
        render();
        markPristine();
        renderList();
        syncActions();
        status(t('recordNew'));
    }));

    btnSave.forEach((button) => button.addEventListener('click', () => {
        saveCurrent();
    }));

    list.addEventListener('click', (event) => {
        const button = event.target instanceof HTMLElement ? event.target.closest('button') : null;
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }
        if (button.dataset.governanceOpen) {
            openRecord(button.dataset.governanceOpen);
        }
        if (button.dataset.governanceDelete && window.confirm('Diesen Arbeitsstand löschen?')) {
            deleteRecord(button.dataset.governanceDelete);
        }
    });

    renderList();
    syncActions();
    return { renderList, syncActions };
}

/**
 * @param {{ note?: string, fields?: Array<{ label?: string, labelEn?: string, value?: string }> }} state
 */
function formSignature(state) {
    return JSON.stringify({
        note: state.note || '',
        fields: (state.fields || []).map((field) => [field.labelEn || field.label || '', field.value || '']),
    });
}

function mount(root) {
    const config = readConfig(root);
    const preview = root.querySelector('[data-governance-tool-preview]');
    const score = root.querySelector('[data-governance-tool-score]');
    const status = root.querySelector('[data-governance-tool-status]');
    const returnLink = root.querySelector('[data-return-to-plan]');

    applyPrefill(root, config);
    let current = collect(root, config);
    let baselineSignature = formSignature(current);

    function setStatus(message) {
        if (status) {
            status.textContent = message;
        }
    }

    function markPristine() {
        baselineSignature = formSignature(current);
    }

    function isDirty() {
        return formSignature(current) !== baselineSignature;
    }

    function render() {
        current = collect(root, config);
        if (preview) {
            preview.textContent = markdown(current);
        }
        if (score) {
            score.textContent = String(current.score);
        }
        kpiManager?.syncActions();
        standardSave?.syncActions();
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
            markPristine();
        },
        hasContent: () => current.note !== '' || current.filled.length > 0,
    });

    if (ctx && returnLink instanceof HTMLAnchorElement) {
        returnLink.href = ctx.returnUrl;
    }

    let kpiManager = config.id === 'kpi-requirements-intake'
        ? initKpiIntakeManager(root, () => current, render, setStatus, markPristine)
        : null;
    let standardSave = config.id !== 'kpi-requirements-intake'
        ? initGovernanceRecordManager(root, config, () => current, render, setStatus, markPristine)
        : null;

    root.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('input', render);
        input.addEventListener('change', render);
    });

    root.querySelectorAll('[data-governance-tool-copy]').forEach((button) => button.addEventListener('click', async () => {
        await copyTextToClipboard(markdown(current));
        markPristine();
        setStatus(t('copied'));
    }));

    root.querySelectorAll('[data-governance-tool-download]').forEach((button) => button.addEventListener('click', () => {
        downloadTextFile(`${safeFilename(current.title)}.md`, markdown(current), 'text/markdown;charset=utf-8');
        markPristine();
        setStatus(t('downloaded'));
    }));

    root.querySelectorAll('[data-governance-tool-print]').forEach((button) => button.addEventListener('click', () => {
        markPristine();
        window.print();
    }));

    bindLeaveGuard(
        () => isDirty(),
        () => t('discovery.leaveConfirm'),
    );

    render();
    markPristine();
    kpiManager?.renderList();

    if (config.id === 'custom-stack-builder') {
        const builderHost = root.querySelector('[data-stack-builder-root]');
        const loadSelect = root.querySelector('[data-stack-builder-load]');
        const saveAsButton = root.querySelector('[data-governance-stack-builder-save-as]');
        const statusEl = root.querySelector('[data-stack-builder-status]');
        const lang = () => (document.documentElement.lang === 'de' ? 'de' : 'en');
        let api = null;
        let savedStacks = readSavedStacksLocal();

        const setStatus = (message) => {
            if (!(statusEl instanceof HTMLElement)) {
                return;
            }
            statusEl.hidden = !message;
            statusEl.textContent = message || '';
        };

        const fillLoadSelect = () => {
            if (!(loadSelect instanceof HTMLSelectElement)) {
                return;
            }
            const keep = loadSelect.value;
            loadSelect.replaceChildren();
            const empty = document.createElement('option');
            empty.value = '';
            empty.textContent = lang() === 'de' ? '— Auswählen —' : '— Choose —';
            loadSelect.append(empty);
            savedStacks.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                loadSelect.append(option);
            });
            if (keep && savedStacks.some((item) => item.id === keep)) {
                loadSelect.value = keep;
            }
        };

        const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const refreshFromWorkspace = async () => {
            const activeUrl = config.workspace?.activeUrl;
            if (!activeUrl) {
                fillLoadSelect();
                return;
            }
            try {
                const response = await fetch(activeUrl, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!response.ok) {
                    fillLoadSelect();
                    return;
                }
                const payload = await response.json();
                const remote = Array.isArray(payload?.workspace?.savedStacks) ? payload.workspace.savedStacks : [];
                savedStacks = remote.map((item) => ({
                    id: String(item.id),
                    name: String(item.name || 'Stack'),
                    selection: normalizeSelection(item.selection),
                    updatedAt: String(item.updatedAt || ''),
                }));
                writeSavedStacksLocal(savedStacks);
                if (payload?.workspace?.stack === 'custom' && payload.workspace.customStack) {
                    const selection = normalizeSelection(payload.workspace.customStack);
                    writeCustomStack(selection);
                    api?.setSelection?.(selection);
                    syncSelectionToToolFields(root, selection);
                }
            } catch {
                // keep local
            }
            fillLoadSelect();
        };

        if (builderHost) {
            const initial = readCustomStack();
            let hubContext = {};
            try {
                const raw = sessionStorage.getItem('binom-governance-hub-context');
                hubContext = raw ? JSON.parse(raw) : {};
            } catch {
                hubContext = {};
            }
            const preferred = preferredProductIds({
                orgContext: hubContext.orgContext,
                regulationPressure: hubContext.regulationPressure,
            });
            const startProduct = readStartingPointProduct();
            const fromStart = preferredProductIdsForStartingPoint(startProduct);
            const mergedPreferred = [...new Set([...fromStart, ...preferred])];
            const hubBanner = stackBuilderContextBanner({
                orgContext: hubContext.orgContext,
                regulationPressure: hubContext.regulationPressure,
            }, lang());
            const startBanner = startingPointStackBanner(startProduct, lang());
            const banner = [startBanner, hubBanner].filter(Boolean).join(' ');
            api = mountStackBuilder(builderHost, {
                selection: initial,
                preferredProductIds: mergedPreferred,
                contextBanner: banner,
                onChange: (selection) => {
                    writeCustomStack(selection);
                    syncSelectionToToolFields(root, selection);
                    const syncUrl = config.workspace?.syncStackUrl;
                    if (syncUrl) {
                        fetch(syncUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': csrf(),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ stack: 'custom', customStack: selection }),
                        }).catch(() => {});
                    }
                },
            });
            syncSelectionToToolFields(root, initial);
        }

        fillLoadSelect();
        refreshFromWorkspace();

        saveAsButton?.addEventListener('click', async () => {
            const selection = api?.getSelection?.() || readCustomStack();
            const hasProducts = Object.values(selection).some((items) => Array.isArray(items) && items.length > 0);
            if (!hasProducts) {
                setStatus(lang() === 'de' ? 'Bitte zuerst Produkte wählen.' : 'Choose products first.');
                return;
            }
            const suggested = summarizeSelection(selection, lang()).replace(/^Eigener Stack · |^Custom stack · /, '');
            const name = window.prompt(
                lang() === 'de' ? 'Name für diesen Stack:' : 'Name for this stack:',
                suggested || (lang() === 'de' ? 'Mein Stack' : 'My stack'),
            );
            if (!name || !String(name).trim()) {
                return;
            }

            const savedStacksUrl = config.workspace?.savedStacksUrl;
            if (savedStacksUrl) {
                try {
                    const response = await fetch(savedStacksUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrf(),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ name: String(name).trim(), selection }),
                    });
                    if (response.ok) {
                        const payload = await response.json();
                        savedStacks = Array.isArray(payload.savedStacks)
                            ? payload.savedStacks.map((item) => ({
                                id: String(item.id),
                                name: String(item.name || 'Stack'),
                                selection: normalizeSelection(item.selection),
                                updatedAt: String(item.updatedAt || ''),
                            }))
                            : savedStacks;
                        writeSavedStacksLocal(savedStacks);
                        fillLoadSelect();
                        setStatus(lang() === 'de'
                            ? `Gespeichert im Workspace: ${payload.savedStack?.name || name}`
                            : `Saved to workspace: ${payload.savedStack?.name || name}`);
                        return;
                    }
                    if (response.status === 422) {
                        setStatus(lang() === 'de'
                            ? 'Kein aktiver Workspace — bitte unter Profil Hub anlegen/aktivieren.'
                            : 'No active workspace — create/activate one in Profile Hub.');
                        return;
                    }
                } catch {
                    // fall through
                }
            }

            const local = saveNamedStackLocal(String(name).trim(), selection);
            savedStacks = readSavedStacksLocal();
            fillLoadSelect();
            setStatus(lang() === 'de'
                ? `Lokal gespeichert: ${local?.name || name}`
                : `Saved locally: ${local?.name || name}`);
        });

        loadSelect?.addEventListener('change', () => {
            const id = loadSelect.value;
            const match = savedStacks.find((item) => item.id === id);
            if (!match) {
                return;
            }
            const selection = normalizeSelection(match.selection);
            writeCustomStack(selection);
            api?.setSelection?.(selection);
            syncSelectionToToolFields(root, selection);
            setStatus(lang() === 'de' ? `Geladen: ${match.name}` : `Loaded: ${match.name}`);
        });
    }
}

document.querySelectorAll('[data-governance-tool-root]').forEach(mount);
