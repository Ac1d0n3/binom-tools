import {
    archiveInstance,
    clearPlanPassword,
    deleteInstance,
    duplicateInstance,
    resetInstanceProgress,
    setPlanPassword,
    updateInstance,
} from '../instance-manager.js';
import { getLocale } from '../../locale.js';
import { isAccountsMode, readAccountsBootstrap } from '../accounts-bridge.js';
import {
    isPasswordProtected,
    isPlanUnlocked,
    lockPlanSession,
    unlockPlan,
    verifyPassword,
} from '../plan-password.js';
import { loadWorkspace } from '../storage.js';
import { listPeople, listTeams, localizedText } from '../people-teams.js';
import {
    applySpI18n,
    showToast,
    spT,
    storageErrorMessage,
} from './helpers.js';
import {
    applyImport,
    buildInstanceExport,
    buildWorkspaceExport,
    downloadJson,
    resetToEmptyWorkspace,
    validateImportPayload,
} from '../export-import.js';

export function initSettingsPage() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }
    const instanceId = root.dataset.spInstanceId;
    applySpI18n(root);
    window.addEventListener('binom-tools:locale', () => {
        applySpI18n(root);
        render();
    });

    const render = () => {
        const { data } = loadWorkspace();
        const instance = data.instances[instanceId];
        const missing = document.getElementById('sp-plan-missing');
        const locked = document.getElementById('sp-plan-locked');
        const view = document.getElementById('sp-settings-view');
        if (!instance) {
            missing.hidden = false;
            if (locked) {
                locked.hidden = true;
            }
            view.hidden = true;
            return;
        }
        missing.hidden = true;

        if (isPasswordProtected(instance) && !isPlanUnlocked(instanceId)) {
            if (locked) {
                locked.hidden = false;
            }
            view.hidden = true;
            return;
        }
        if (locked) {
            locked.hidden = true;
        }
        view.hidden = false;
        const locale = getLocale();
        document.getElementById('sp-settings-plan-title').textContent =
            instance.translations?.[locale]?.title
            || instance.translations?.en?.title
            || instance.templateSlug;

        const protectedPlan = isPasswordProtected(instance);
        document.getElementById('sp-password-status').textContent = protectedPlan
            ? spT('sp.password.statusOn')
            : spT('sp.password.statusOff');
        document.getElementById('sp-password-current-wrap').hidden = !protectedPlan;
        document.getElementById('sp-password-remove').hidden = !protectedPlan;
        document.getElementById('sp-password-lock-session').hidden = !(protectedPlan && isPlanUnlocked(instanceId));
        document.getElementById('sp-password-save').textContent = protectedPlan
            ? spT('sp.password.change')
            : spT('sp.password.set');

        renderSharing(instance, locale);
    };

    document.getElementById('sp-sharing-save')?.addEventListener('click', () => {
        const viewerUserIds = [...document.querySelectorAll('#sp-share-users input:checked')].map((el) => el.value);
        const viewerTeamIds = [...document.querySelectorAll('#sp-share-teams input:checked')].map((el) => el.value);
        const result = updateInstance(instanceId, (instance) => {
            instance.viewerUserIds = viewerUserIds;
            instance.viewerTeamIds = viewerTeamIds;
        });
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.sharing.saved'));
        render();
    });

    document.getElementById('sp-unlock-form')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const { data } = loadWorkspace();
        const instance = data.instances[instanceId];
        const password = document.getElementById('sp-unlock-password')?.value || '';
        const errorEl = document.getElementById('sp-unlock-error');
        const ok = await verifyPassword(password, instance || {});
        if (!ok) {
            if (errorEl) {
                errorEl.hidden = false;
                errorEl.textContent = spT('sp.password.wrong');
            }
            return;
        }
        unlockPlan(instanceId);
        if (errorEl) {
            errorEl.hidden = true;
        }
        render();
    });

    document.getElementById('sp-password-form')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const { data } = loadWorkspace();
        const instance = data.instances[instanceId];
        if (!instance) {
            return;
        }
        const errorEl = document.getElementById('sp-password-error');
        const showError = (key) => {
            errorEl.hidden = false;
            errorEl.textContent = spT(key);
        };
        errorEl.hidden = true;

        const next = document.getElementById('sp-password-new').value;
        const confirm = document.getElementById('sp-password-confirm').value;
        if (next !== confirm) {
            showError('sp.password.mismatch');
            return;
        }
        if (next.length < 4) {
            showError('sp.password.tooShort');
            return;
        }
        if (isPasswordProtected(instance)) {
            const current = document.getElementById('sp-password-current').value;
            const ok = await verifyPassword(current, instance);
            if (!ok) {
                showError('sp.password.wrong');
                return;
            }
        }
        const result = await setPlanPassword(instanceId, next);
        if (!result.ok) {
            showError(result.error === 'password-too-short' ? 'sp.password.tooShort' : 'sp.save.error');
            return;
        }
        unlockPlan(instanceId);
        document.getElementById('sp-password-form').reset();
        showToast(spT('sp.password.saved'));
        render();
    });

    document.getElementById('sp-password-remove')?.addEventListener('click', async () => {
        const { data } = loadWorkspace();
        const instance = data.instances[instanceId];
        if (!instance || !isPasswordProtected(instance)) {
            return;
        }
        const current = document.getElementById('sp-password-current').value
            || window.prompt(spT('sp.password.current'), '')
            || '';
        const ok = await verifyPassword(current, instance);
        if (!ok) {
            const errorEl = document.getElementById('sp-password-error');
            errorEl.hidden = false;
            errorEl.textContent = spT('sp.password.wrong');
            return;
        }
        clearPlanPassword(instanceId);
        lockPlanSession(instanceId);
        showToast(spT('sp.password.removed'));
        render();
    });

    document.getElementById('sp-password-lock-session')?.addEventListener('click', () => {
        lockPlanSession(instanceId);
        showToast(spT('sp.toast.saved'));
        window.location.href = root.dataset.spShowUrl || root.dataset.spIndexUrl;
    });

    document.getElementById('sp-export-instance')?.addEventListener('click', () => {
        const { data } = loadWorkspace();
        const instance = data.instances[instanceId];
        if (!instance) {
            return;
        }
        downloadJson(
            buildInstanceExport(instance, data),
            `sprint-plan-${instance.templateSlug}-${instance.startedAt}.json`,
        );
        showToast(spT('sp.toast.exported'));
    });

    document.getElementById('sp-duplicate-plan')?.addEventListener('click', () => {
        const result = duplicateInstance(instanceId);
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        window.location.href = (() => {
            const base = root.dataset.spIndexUrl || '/sprint-planner';
            let path = base;
            try {
                if (/^https?:\/\//i.test(base)) {
                    path = new URL(base).pathname;
                }
            } catch {
                path = base;
            }
            return `${path.replace(/\/$/, '')}/${result.instance.id}`;
        })();
    });

    document.getElementById('sp-archive-plan')?.addEventListener('click', () => {
        archiveInstance(instanceId, true);
        showToast(spT('sp.toast.saved'));
        window.location.href = root.dataset.spIndexUrl;
    });

    document.getElementById('sp-reset-plan')?.addEventListener('click', () => {
        if (!window.confirm(spT('sp.confirm.resetPlan'))) {
            return;
        }
        resetInstanceProgress(instanceId);
        showToast(spT('sp.toast.saved'));
    });

    document.getElementById('sp-delete-plan')?.addEventListener('click', () => {
        if (!window.confirm(spT('sp.confirm.deletePlan'))) {
            return;
        }
        deleteInstance(instanceId);
        showToast(spT('sp.toast.deleted'));
        window.location.href = root.dataset.spIndexUrl;
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

    document.getElementById('sp-clear-workspace')?.addEventListener('click', () => {
        if (!window.confirm(spT('sp.confirm.clearWorkspace'))) {
            return;
        }
        resetToEmptyWorkspace();
        showToast(spT('sp.toast.deleted'));
        window.location.href = root.dataset.spIndexUrl;
    });

    render();
}

/**
 * @param {import('../storage.js').SpPlanInstance} instance
 * @param {'de'|'en'} locale
 */
function renderSharing(instance, locale) {
    const panel = document.getElementById('sp-sharing-panel');
    const localOnly = document.getElementById('sp-sharing-local-only');
    if (!panel || !localOnly) {
        return;
    }

    if (!isAccountsMode()) {
        panel.hidden = true;
        localOnly.hidden = false;
        return;
    }

    panel.hidden = false;
    localOnly.hidden = true;

    const usersHost = document.getElementById('sp-share-users');
    const teamsHost = document.getElementById('sp-share-teams');
    if (!usersHost || !teamsHost) {
        return;
    }

    const bootstrap = readAccountsBootstrap();
    const ownerId = instance.ownerUserId || bootstrap.accountUser?.id;
    const selectedUsers = new Set(instance.viewerUserIds || []);
    const selectedTeams = new Set(instance.viewerTeamIds || []);

    usersHost.innerHTML = '';
    for (const person of listPeople()) {
        if (person.id === ownerId) {
            continue;
        }
        const label = document.createElement('label');
        label.className = 'sp-check';
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.value = person.id;
        input.checked = selectedUsers.has(person.id);
        label.append(input, document.createTextNode(` ${person.displayName}`));
        usersHost.appendChild(label);
    }

    teamsHost.innerHTML = '';
    for (const team of listTeams()) {
        const label = document.createElement('label');
        label.className = 'sp-check';
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.value = team.id;
        input.checked = selectedTeams.has(team.id);
        label.append(input, document.createTextNode(` ${localizedText(team.name, locale)}`));
        teamsHost.appendChild(label);
    }
}

