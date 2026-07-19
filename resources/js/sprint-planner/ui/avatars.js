/**
 * Avatar / trigram chips for people and teams.
 */

import { readAccountsBootstrap } from '../accounts-bridge.js';
import { localizedText } from '../people-teams.js';
import {
    applyAvatarColor,
    normalizeAvatarIcon,
    normalizeColorToken,
    normalizeTrigram,
    teamTrigram,
} from '../trigram.js';
import { withAppBasePath } from '../../locale.js';
import { spT } from './helpers.js';

/**
 * @param {string} colorToken
 * @param {string} text
 * @param {string} title
 * @param {'person'|'team'|'empty'} kind
 * @param {{icon?: string|null}} [options]
 * @returns {HTMLSpanElement}
 */
export function renderChip(colorToken, text, title, kind = 'person', options = {}) {
    const token = normalizeColorToken(colorToken);
    const icon = normalizeAvatarIcon(options.icon);
    const label = String(text || '');
    // Person: icon replaces trigram. Team: icon alone or icon + up to 3 letters.
    const showIcon = Boolean(icon);
    const showLabel = Boolean(label) && (kind === 'team' || !showIcon);
    const iconOnly = showIcon && !showLabel;
    const iconLabel = showIcon && showLabel;
    const trigram3 = showLabel && !showIcon && label.length >= 3;

    const chip = document.createElement('span');
    chip.className = [
        `sp-avatar sp-avatar--${token} sp-avatar--${kind}`,
        showIcon ? 'sp-avatar--icon' : '',
        iconOnly ? 'sp-avatar--icon-only' : '',
        iconLabel ? 'sp-avatar--icon-label' : '',
        trigram3 ? 'sp-avatar--trigram-3' : '',
    ].filter(Boolean).join(' ');
    applyAvatarColor(chip, token, kind);
    chip.title = title || text || '';
    chip.setAttribute('aria-label', title || text || spT('sp.report.unassigned'));

    if (showIcon) {
        const glyph = document.createElement('span');
        glyph.className = 'sp-avatar-icon-mask';
        glyph.setAttribute('aria-hidden', 'true');
        const url = `url("${withAppBasePath(`/icons/avatar/${icon}.svg`)}")`;
        glyph.style.maskImage = url;
        glyph.style.webkitMaskImage = url;
        chip.appendChild(glyph);
    }

    if (showLabel) {
        if (showIcon) {
            const tri = document.createElement('span');
            tri.className = 'sp-avatar__label';
            tri.textContent = label;
            chip.appendChild(tri);
        } else {
            chip.textContent = label;
        }
    } else if (!showIcon) {
        chip.textContent = '—';
    }

    return chip;
}

/**
 * @param {import('../storage.js').SpPerson|null|undefined} person
 * @returns {HTMLSpanElement}
 */
export function renderPersonChip(person) {
    if (!person) {
        return renderChip('accent-1', '—', spT('sp.report.unassigned'), 'empty');
    }
    const tri = normalizeTrigram(person.shortName, person.displayName);
    return renderChip(person.colorToken, tri, person.displayName, 'person', {
        icon: person.avatarIcon,
    });
}

/**
 * @param {import('../storage.js').SpTeam|null|undefined} team
 * @param {'de'|'en'} locale
 * @returns {HTMLSpanElement}
 */
export function renderTeamChip(team, locale = 'en') {
    if (!team) {
        return renderChip('accent-1', '—', '—', 'empty');
    }
    const name = localizedText(team.name, locale);
    const icon = normalizeAvatarIcon(team.avatarIcon);
    let tri = '';
    if (team.shortName) {
        tri = normalizeTrigram(team.shortName, name);
    } else if (!icon) {
        tri = teamTrigram(team, locale);
    }
    return renderChip(team.colorToken || 'accent-1', tri, name, 'team', { icon });
}

/**
 * @param {string|null|undefined} value
 */
function hasAssigneeId(value) {
    if (value === null || value === undefined) {
        return false;
    }
    const id = String(value).trim();
    return id !== '' && id !== 'null' && id !== 'undefined';
}

/**
 * Assignee chip for a resolved item. Returns null when unassigned (no empty blue circle).
 * @param {{assigneeType?: string|null, assigneeId?: string|null}} item
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 * @returns {HTMLSpanElement|null}
 */
export function renderAssigneeChip(item, workspace, locale) {
    if (!hasAssigneeId(item?.assigneeId)) {
        return null;
    }
    const id = String(item.assigneeId);
    if (item.assigneeType === 'team') {
        const team = workspace.teams?.[id];
        if (team) {
            return renderTeamChip(team, locale);
        }
        return renderChip('accent-1', normalizeTrigram('', id), id, 'team');
    }
    const person = workspace.people?.[id];
    if (person) {
        return renderPersonChip(person);
    }
    const account = readAccountsBootstrap().accountUser;
    if (account && String(account.id) === id) {
        const displayName = String(account.displayName || account.email || id);
        return renderChip(
            String(account.colorToken || 'accent-1'),
            normalizeTrigram(String(account.shortName || ''), displayName),
            displayName,
            'person',
            { icon: account.avatarIcon },
        );
    }
    return renderChip('accent-1', normalizeTrigram('', id), id, 'person');
}

/**
 * Row of participant chips.
 * @param {string[]} participantIds
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @returns {HTMLElement}
 */
export function renderParticipantChips(participantIds, workspace) {
    const wrap = document.createElement('div');
    wrap.className = 'sp-avatar-row';
    const ids = Array.isArray(participantIds) ? participantIds : [];
    if (!ids.length) {
        wrap.appendChild(document.createTextNode('—'));
        return wrap;
    }
    for (const id of ids) {
        const person = workspace.people?.[id];
        if (person) {
            wrap.appendChild(renderPersonChip(person));
            continue;
        }
        const account = readAccountsBootstrap().accountUser;
        if (account && String(account.id) === String(id)) {
            const displayName = String(account.displayName || account.email || id);
            wrap.appendChild(renderChip(
                String(account.colorToken || 'accent-1'),
                normalizeTrigram(String(account.shortName || ''), displayName),
                displayName,
                'person',
                { icon: account.avatarIcon },
            ));
            continue;
        }
        wrap.appendChild(renderChip('accent-1', normalizeTrigram('', String(id)), String(id), 'person'));
    }
    return wrap;
}

/**
 * Row of team chips for a plan.
 * @param {string[]} teamIds
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 * @returns {HTMLElement}
 */
export function renderTeamChips(teamIds, workspace, locale) {
    const wrap = document.createElement('div');
    wrap.className = 'sp-avatar-row';
    const ids = Array.isArray(teamIds) ? teamIds : [];
    if (!ids.length) {
        wrap.appendChild(document.createTextNode('—'));
        return wrap;
    }
    for (const id of ids) {
        wrap.appendChild(renderTeamChip(workspace.teams[id], locale));
    }
    return wrap;
}
