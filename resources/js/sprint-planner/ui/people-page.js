import { getLocale } from '../../locale.js';
import { isAccountsMode } from '../accounts-bridge.js';
import {
    applyImport,
    buildWorkspaceExport,
    downloadJson,
    validateImportPayload,
} from '../export-import.js';
import {
    archivePerson,
    archiveTeam,
    listPeople,
    listTeams,
    localizedText,
    setActivePerson,
    setDefaultTeam,
    upsertPerson,
    upsertTeam,
} from '../people-teams.js';
import { loadWorkspace } from '../storage.js';
import { renderPersonChip, renderTeamChip } from './avatars.js';
import {
    applySpI18n,
    fillSelect,
    showToast,
    spT,
    storageErrorMessage,
} from './helpers.js';

import { applyAvatarColor, ACCENT_TOKENS, TEAM_ACCENT_TOKENS } from '../trigram.js';

/**
 * @param {string} hostId
 * @param {string} [selected]
 * @param {string[]} [tokens]
 */
function renderColorSwatches(hostId, selected = 'accent-1', tokens = ACCENT_TOKENS) {
    const host = document.getElementById(hostId);
    if (!host) {
        return;
    }
    host.innerHTML = '';
    for (const token of tokens) {
        const label = document.createElement('label');
        label.className = `sp-color-swatch sp-avatar--${token}`;
        applyAvatarColor(label, token, 'person');
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = hostId;
        input.value = token;
        input.checked = token === selected;
        label.appendChild(input);
        host.appendChild(label);
    }
}

/**
 * @param {string} hostId
 */
function selectedColorToken(hostId) {
    return document.querySelector(`#${hostId} input:checked`)?.value || 'accent-1';
}

export function initPeoplePage() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    const accountsOn = isAccountsMode();

    applySpI18n(root);
    window.addEventListener('binom-tools:locale', () => {
        applySpI18n(root);
        render();
    });

    document.getElementById('sp-add-person')?.addEventListener('click', () => openPersonDialog(null));
    document.getElementById('sp-add-team')?.addEventListener('click', () => openTeamDialog(null));

    document.getElementById('sp-active-person')?.addEventListener('change', (event) => {
        setActivePerson(event.target.value || null);
        showToast(spT('sp.toast.saved'));
    });
    document.getElementById('sp-default-team')?.addEventListener('change', (event) => {
        setDefaultTeam(event.target.value || null);
        showToast(spT('sp.toast.saved'));
    });

    document.getElementById('sp-person-dialog')?.addEventListener('close', (event) => {
        const dialog = event.target;
        if (dialog.returnValue !== 'confirm') {
            return;
        }
        const result = upsertPerson({
            id: document.getElementById('sp-person-id').value || undefined,
            displayName: document.getElementById('sp-person-display-name').value,
            shortName: document.getElementById('sp-person-short-name').value,
            email: document.getElementById('sp-person-email').value,
            role: document.getElementById('sp-person-role').value,
            colorToken: selectedColorToken('sp-person-color'),
        });
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.toast.saved'));
        render();
    });

    document.getElementById('sp-team-dialog')?.addEventListener('close', (event) => {
        const dialog = event.target;
        if (dialog.returnValue !== 'confirm') {
            return;
        }
        const memberIds = [...document.querySelectorAll('#sp-team-members input:checked')].map((el) => el.value);
        const result = upsertTeam({
            id: document.getElementById('sp-team-id').value || undefined,
            nameDe: document.getElementById('sp-team-name-de').value,
            nameEn: document.getElementById('sp-team-name-en').value,
            shortName: document.getElementById('sp-team-short-name').value,
            descriptionDe: document.getElementById('sp-team-desc-de').value,
            descriptionEn: document.getElementById('sp-team-desc-en').value,
            colorToken: selectedColorToken('sp-team-color'),
            memberIds,
        });
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.toast.saved'));
        render();
    });

    document.getElementById('sp-export-workspace')?.addEventListener('click', () => {
        downloadJson(
            buildWorkspaceExport(),
            `bn-tools-sprint-workspace-${new Date().toISOString().slice(0, 10)}.json`,
        );
        showToast(spT('sp.toast.exported'));
    });

    const importFile = document.getElementById('sp-import-file');
    document.getElementById('sp-import-workspace')?.addEventListener('click', () => importFile?.click());
    importFile?.addEventListener('change', async () => {
        const file = importFile.files?.[0];
        if (!file) {
            return;
        }
        try {
            const raw = JSON.parse(await file.text());
            const validated = validateImportPayload(raw);
            if (!validated.ok) {
                showToast(storageErrorMessage(validated.error));
                return;
            }
            const mode = window.prompt(spT('sp.import.modePrompt'), 'merge');
            if (!mode || mode === 'cancel') {
                return;
            }
            const result = applyImport(['replace', 'merge', 'new-ids'].includes(mode) ? mode : 'merge', raw);
            if (!result.ok) {
                showToast(storageErrorMessage(result.error));
                return;
            }
            showToast(spT('sp.toast.imported'));
            render();
        } catch {
            showToast(spT('sp.error.importInvalid'));
        } finally {
            importFile.value = '';
        }
    });

    render();
}

function render() {
    const root = document.getElementById('sp-app');
    const accountsOn = isAccountsMode();
    const locale = getLocale();
    const { data: workspace } = loadWorkspace();
    const people = listPeople(true);
    const teams = listTeams(true);

    fillSelect(
        document.getElementById('sp-active-person'),
        people.filter((p) => !p.archived).map((p) => ({ id: p.id, label: p.displayName })),
        workspace.workspace.activePersonId || '',
        true,
    );
    fillSelect(
        document.getElementById('sp-default-team'),
        teams.filter((t) => !t.archived).map((t) => ({
            id: t.id,
            label: localizedText(t.name, locale),
        })),
        workspace.workspace.defaultTeamId || '',
        true,
    );

    const peopleList = document.getElementById('sp-people-list');
    peopleList.innerHTML = '';
    for (const person of people) {
        const row = document.createElement('div');
        row.className = 'sp-list__row';
        const left = document.createElement('div');
        left.className = 'sp-list__identity';
        left.appendChild(renderPersonChip(person));
        const text = document.createElement('div');
        const strong = document.createElement('strong');
        strong.textContent = person.displayName;
        const meta = document.createElement('span');
        meta.className = 'sp-list__meta';
        meta.textContent = [
            person.shortName,
            person.role,
            person.archived ? spT('sp.status.archived') : '',
        ].filter(Boolean).join(' · ');
        text.append(strong, meta);
        left.appendChild(text);
        const actions = document.createElement('div');
        actions.className = 'sp-action-row';
        if (accountsOn) {
            const editUrl = editUrlFromTemplate(root.dataset.userEditUrl, person.id);
            if (editUrl) {
                actions.appendChild(linkButton(spT('sp.action.edit'), editUrl));
            }
        } else {
            const edit = button(spT('sp.action.edit'), () => openPersonDialog(person));
            const archive = button(
                person.archived ? spT('sp.action.save') : spT('sp.action.archive'),
                () => {
                    archivePerson(person.id, !person.archived);
                    render();
                },
            );
            actions.append(edit, archive);
        }
        row.append(left, actions);
        peopleList.appendChild(row);
    }

    const teamsList = document.getElementById('sp-teams-list');
    teamsList.innerHTML = '';
    for (const team of teams) {
        const row = document.createElement('div');
        row.className = 'sp-list__row';
        const left = document.createElement('div');
        left.className = 'sp-list__identity';
        left.appendChild(renderTeamChip(team, locale));
        const text = document.createElement('div');
        const strong = document.createElement('strong');
        strong.textContent = localizedText(team.name, locale);
        const meta = document.createElement('span');
        meta.className = 'sp-list__meta';
        meta.textContent = [
            team.shortName,
            `${team.memberIds.length} ${spT('sp.field.members').toLowerCase()}`,
            team.archived ? spT('sp.status.archived') : '',
        ].filter(Boolean).join(' · ');
        text.append(strong, meta);
        left.appendChild(text);
        const actions = document.createElement('div');
        actions.className = 'sp-action-row';
        if (accountsOn) {
            const editUrl = editUrlFromTemplate(root.dataset.teamEditUrl, team.id);
            if (editUrl) {
                actions.appendChild(linkButton(spT('sp.action.edit'), editUrl));
            }
        } else {
            actions.append(
                button(spT('sp.action.edit'), () => openTeamDialog(team)),
                button(
                    team.archived ? spT('sp.action.save') : spT('sp.action.archive'),
                    () => {
                        archiveTeam(team.id, !team.archived);
                        render();
                    },
                ),
            );
        }
        row.append(left, actions);
        teamsList.appendChild(row);
    }
}

/**
 * @param {string|undefined} template
 * @param {string} id
 * @returns {string|null}
 */
function editUrlFromTemplate(template, id) {
    if (!template || !id) {
        return null;
    }
    return template.replace('__ID__', encodeURIComponent(id));
}

function button(label, onClick) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tools-btn tools-btn--secondary tools-btn--small';
    btn.textContent = label;
    btn.addEventListener('click', onClick);
    return btn;
}

/**
 * @param {string} label
 * @param {string} href
 */
function linkButton(label, href) {
    const a = document.createElement('a');
    a.href = href;
    a.className = 'tools-btn tools-btn--secondary tools-btn--small';
    a.textContent = label;
    return a;
}

function openPersonDialog(person) {
    document.getElementById('sp-person-id').value = person?.id || '';
    document.getElementById('sp-person-display-name').value = person?.displayName || '';
    document.getElementById('sp-person-short-name').value = person?.shortName || '';
    document.getElementById('sp-person-email').value = person?.email || '';
    document.getElementById('sp-person-role').value = person?.role || '';
    renderColorSwatches('sp-person-color', person?.colorToken || 'accent-1', ACCENT_TOKENS);
    document.getElementById('sp-person-dialog').showModal();
}

function openTeamDialog(team) {
    document.getElementById('sp-team-id').value = team?.id || '';
    document.getElementById('sp-team-name-de').value = team?.name?.de || '';
    document.getElementById('sp-team-name-en').value = team?.name?.en || '';
    const shortEl = document.getElementById('sp-team-short-name');
    if (shortEl) {
        shortEl.value = team?.shortName || '';
    }
    document.getElementById('sp-team-desc-de').value = team?.description?.de || '';
    document.getElementById('sp-team-desc-en').value = team?.description?.en || '';
    renderColorSwatches('sp-team-color', team?.colorToken || 'accent-1', TEAM_ACCENT_TOKENS);
    const host = document.getElementById('sp-team-members');
    host.innerHTML = '';
    const selected = new Set(team?.memberIds || []);
    for (const person of listPeople()) {
        const label = document.createElement('label');
        label.className = 'sp-check';
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.value = person.id;
        input.checked = selected.has(person.id);
        label.append(input, document.createTextNode(` ${person.displayName}`));
        host.appendChild(label);
    }
    document.getElementById('sp-team-dialog').showModal();
}
