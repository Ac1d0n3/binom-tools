import { getLocale, withAppBasePath } from '../../locale.js';
import { isAccountsMode, readAccountsBootstrap } from '../accounts-bridge.js';
import {
    buildPlanToolHref,
    isExternalHelpHref,
    isAppToolHref,
    resolveHelpHref as resolveAllowedHelpHref,
} from '../external-links.js';
import { isPlaybookRead, markPlaybookRead } from '../../playbooks/read-state.js';
import { itemHasHelp, requiredStoryProgress } from '../progress.js';
import { createIconButton, createRequiredIcon } from './icons.js';
import { spT } from './helpers.js';

/** @type {Record<string, {titleDe?: string|null, titleEn?: string|null, title?: string}>} */
let storyTitleIndex = {};

/**
 * @param {Array<{slug: string, titleDe?: string|null, titleEn?: string|null, title?: string}>} stories
 */
export function hydrateStoryTitles(stories) {
    storyTitleIndex = {};
    for (const story of stories || []) {
        if (!story?.slug) {
            continue;
        }
        storyTitleIndex[story.slug] = story;
    }
}

export function readStoryTitlesFromDom(root = document.getElementById('sp-app')) {
    const fromScript = document.getElementById('sp-bootstrap-stories');
    if (fromScript?.textContent?.trim()) {
        try {
            hydrateStoryTitles(JSON.parse(fromScript.textContent));
            return;
        } catch {
            // fall through
        }
    }
    if (!root?.dataset?.spStories) {
        return;
    }
    try {
        hydrateStoryTitles(JSON.parse(root.dataset.spStories));
    } catch {
        // ignore invalid JSON
    }
}

/**
 * @param {string} slug
 */
export function storyTitle(slug) {
    const locale = getLocale();
    const entry = storyTitleIndex[slug];
    if (!entry) {
        return slug;
    }
    if (locale === 'de' && entry.titleDe) {
        return entry.titleDe;
    }
    if (locale === 'en' && entry.titleEn) {
        return entry.titleEn;
    }
    return entry.title || entry.titleEn || entry.titleDe || slug;
}

function isStoryRead(slug) {
    const readSet = new Set(readAccountsBootstrap().readSlugs || []);
    return isAccountsMode()
        ? readSet.has(slug) || isPlaybookRead(slug)
        : isPlaybookRead(slug);
}

/**
 * Resolve playbook story URLs (internal). Help/community links use resolveExternalHelpHref.
 * @param {string} href
 */
export function resolveHelpHref(href) {
    const value = String(href || '').trim();
    if (!value) {
        return '#';
    }
    if (/^https?:\/\//i.test(value) || value.startsWith('mailto:')) {
        return value;
    }
    if (value.startsWith('/')) {
        return withAppBasePath(value);
    }
    if (/^[a-z0-9-]+$/i.test(value)) {
        return withAppBasePath(`/playbooks/${encodeURIComponent(value)}`);
    }
    return value;
}

/**
 * @param {string} href
 */
export { resolveHelpHref as resolveExternalHelpHref } from '../external-links.js';

/**
 * @param {import('../progress.js').SpStoryRef} story
 * @param {() => void} [onChanged]
 */
export function renderStoryCard(story, onChanged) {
    const card = document.createElement('article');
    card.className = 'sp-story-card';
    card.dataset.required = story.required ? '1' : '0';
    const read = isStoryRead(story.slug);
    card.dataset.read = read ? '1' : '0';

    const head = document.createElement('div');
    head.className = 'sp-story-card__head';
    if (story.required) {
        head.appendChild(createRequiredIcon({ read }));
    }
    const title = document.createElement('strong');
    title.className = 'sp-story-card__title';
    title.textContent = storyTitle(story.slug);
    head.appendChild(title);

    const meta = document.createElement('p');
    meta.className = 'sp-story-card__meta';
    meta.textContent = story.slug;

    const actions = document.createElement('div');
    actions.className = 'sp-story-card__actions';
    actions.appendChild(createIconButton({
        icon: 'open',
        label: spT('sp.help.openStory'),
        href: resolveHelpHref(`/playbooks/${story.slug}`),
    }));

    // Optional stories can be marked read in-panel; required ones only via opening the playbook.
    if (!read && !story.required) {
        actions.appendChild(createIconButton({
            icon: 'check',
            label: spT('sp.help.markRead'),
            primary: true,
            onClick: () => {
                markPlaybookRead(story.slug);
                const bootstrap = readAccountsBootstrap();
                if (Array.isArray(bootstrap.readSlugs) && !bootstrap.readSlugs.includes(story.slug)) {
                    bootstrap.readSlugs = [...bootstrap.readSlugs, story.slug];
                }
                onChanged?.();
            },
        }));
    }

    card.append(head, meta, actions);
    return card;
}

/**
 * @param {import('../progress.js').SpHelpLink} link
 * @param {import('../external-links.js').PlanToolContext|null|undefined} planContext
 */
function renderHelpLinkCard(link, planContext) {
    const locale = getLocale();
    const isTool = isAppToolHref(link.href);
    const isPlaybook = /^\/(?:de\/|en\/)?playbooks\//i.test(String(link.href || ''));
    const isExternal = isExternalHelpHref(link.href);
    let href = resolveAllowedHelpHref(link.href, locale);
    if (href === '#') {
        return null;
    }
    if (isTool && planContext) {
        href = buildPlanToolHref(link.href, planContext, locale);
    }

    const card = document.createElement('article');
    card.className = 'sp-help-link-card';
    card.dataset.kind = isTool || isPlaybook ? 'internal' : (isExternal ? 'external' : 'link');

    const head = document.createElement('div');
    head.className = 'sp-help-link-card__head';

    const title = document.createElement('a');
    title.className = 'sp-help-link-card__title';
    title.href = href;
    title.textContent = link.label || link.href;
    title.target = '_blank';
    title.rel = 'noopener noreferrer';

    head.append(title);

    const description = document.createElement('p');
    description.className = 'sp-help-link-card__description';
    if (link.description) {
        description.textContent = String(link.description);
    } else {
        description.hidden = true;
    }

    const actions = document.createElement('div');
    actions.className = 'sp-help-link-card__actions';
    actions.appendChild(createIconButton({
        icon: 'open',
        label: spT('sp.action.open'),
        href,
    }));

    card.append(head, description, actions);
    return card;
}

/**
 * @param {import('../progress.js').SpFlow} flow
 */
export function renderFlow(flow) {
    if (!flow?.steps?.length) {
        return null;
    }
    const wrap = document.createElement('div');
    const variant = flow.variant === 'chevron' ? 'chevron' : 'linear';
    const layout = flow.layout === 'horizontal' ? 'horizontal' : 'vertical';
    wrap.className = `playbook-flowchart playbook-flowchart--${variant} playbook-flowchart--${layout} sp-flow`;
    const list = document.createElement('ol');
    list.className = 'playbook-flowchart__list';
    list.setAttribute('role', 'list');
    flow.steps.forEach((label, index) => {
        const item = document.createElement('li');
        item.className = 'playbook-flowchart__item';
        item.setAttribute('role', 'listitem');
        const step = document.createElement('div');
        step.className = variant === 'chevron' ? 'playbook-flowchart__chevron' : 'playbook-flowchart__step';
        const num = document.createElement('span');
        num.className = 'playbook-flowchart__num';
        num.textContent = String(index + 1);
        const text = document.createElement('span');
        text.className = 'playbook-flowchart__label';
        text.textContent = label;
        step.append(num, text);
        item.appendChild(step);
        if (index < flow.steps.length - 1) {
            const connector = document.createElement('span');
            connector.className = 'playbook-flowchart__connector';
            connector.setAttribute('aria-hidden', 'true');
            item.appendChild(connector);
        }
        list.appendChild(item);
    });
    wrap.appendChild(list);
    return wrap;
}

/**
 * @param {import('../progress.js').ResolvedItem} item
 * @param {(item: import('../progress.js').ResolvedItem) => void} openHelp
 */
export function renderHelpButton(item, openHelp) {
    if (!itemHasHelp(item)) {
        return null;
    }
    const progress = requiredStoryProgress(item.stories || [], isStoryRead);
    const pending = progress.total > 0 && progress.done < progress.total;
    const label = progress.total > 0
        ? `${spT('sp.help.open')} (${progress.done}/${progress.total})`
        : spT('sp.help.open');
    const btn = createIconButton({
        icon: 'help',
        label,
        className: 'sp-help-btn',
        pending,
        onClick: () => openHelp(item),
    });
    if (progress.total > 0) {
        const badge = document.createElement('span');
        badge.className = 'sp-icon-btn__badge';
        badge.textContent = `${progress.done}/${progress.total}`;
        if (pending) {
            badge.dataset.pending = '1';
        }
        btn.appendChild(badge);
    }
    return btn;
}

/**
 * @param {object} payload
 * @param {string} payload.title
 * @param {string|null} [payload.helpText]
 * @param {import('../progress.js').SpStoryRef[]} [payload.stories]
 * @param {import('../progress.js').SpHelpLink[]} [payload.helpLinks]
 * @param {string|null} [payload.demoCode]
 * @param {import('../progress.js').SpFlow|null} [payload.flow]
 * @param {import('../external-links.js').PlanToolContext|null} [payload.planContext]
 * @param {() => void} onChanged
 */
export function fillHelpPanel(payload, onChanged) {
    const panel = document.getElementById('sp-help-panel');
    const backdrop = document.getElementById('sp-help-backdrop');
    const titleEl = document.getElementById('sp-help-panel-title');
    const body = document.getElementById('sp-help-panel-body');
    if (!panel || !body) {
        return;
    }
    if (titleEl) {
        titleEl.textContent = spT('sp.help.title');
    }
    body.replaceChildren();

    const taskTitle = document.createElement('p');
    taskTitle.className = 'sp-help-panel__task';
    taskTitle.textContent = payload.title;
    body.appendChild(taskTitle);

    if (payload.flow) {
        const flowEl = renderFlow(payload.flow);
        if (flowEl) {
            const flowHead = document.createElement('h3');
            flowHead.className = 'sp-help-panel__section';
            flowHead.textContent = spT('sp.help.flow');
            body.append(flowHead, flowEl);
        }
    }

    if (payload.helpText) {
        const text = document.createElement('p');
        text.className = 'sp-help-panel__text';
        text.textContent = payload.helpText;
        body.appendChild(text);
    }

    const stories = payload.stories || [];
    const required = stories.filter((s) => s.required);
    const optional = stories.filter((s) => !s.required);
    const refresh = () => fillHelpPanel(payload, onChanged);

    if (required.length) {
        const head = document.createElement('h3');
        head.className = 'sp-help-panel__section';
        head.textContent = spT('sp.help.requiredStories');
        body.appendChild(head);
        for (const story of required) {
            body.appendChild(renderStoryCard(story, () => {
                refresh();
                onChanged();
            }));
        }
    }
    if (optional.length) {
        const head = document.createElement('h3');
        head.className = 'sp-help-panel__section';
        head.textContent = spT('sp.help.optionalStories');
        body.appendChild(head);
        for (const story of optional) {
            body.appendChild(renderStoryCard(story, () => {
                refresh();
                onChanged();
            }));
        }
    }

    if (payload.helpLinks?.length) {
        const head = document.createElement('h3');
        head.className = 'sp-help-panel__section';
        head.textContent = spT('sp.help.links');
        const list = document.createElement('div');
        list.className = 'sp-help-panel__links';
        for (const link of payload.helpLinks) {
            const card = renderHelpLinkCard(link, payload.planContext);
            if (!card) {
                continue;
            }
            list.appendChild(card);
        }
        if (list.childNodes.length) {
            body.append(head, list);
        }
    }

    if (payload.demoCode) {
        const head = document.createElement('h3');
        head.className = 'sp-help-panel__section';
        head.textContent = spT('sp.help.demo');
        const pre = document.createElement('pre');
        pre.className = 'sp-help-panel__code';
        pre.textContent = payload.demoCode;
        body.append(head, pre);
    }

    if (!payload.helpText && !stories.length && !payload.helpLinks?.length && !payload.demoCode && !payload.flow) {
        const empty = document.createElement('p');
        empty.className = 'sp-help-panel__empty';
        empty.textContent = spT('sp.help.empty');
        body.appendChild(empty);
    }

    panel.hidden = false;
    panel.setAttribute('aria-hidden', 'false');
    if (backdrop) {
        backdrop.hidden = false;
    }
}

export function closeHelpPanel() {
    const panel = document.getElementById('sp-help-panel');
    const backdrop = document.getElementById('sp-help-backdrop');
    if (panel && !panel.hidden) {
        if (panel.contains(document.activeElement) && document.activeElement instanceof HTMLElement) {
            document.activeElement.blur();
        }
        panel.hidden = true;
        panel.setAttribute('aria-hidden', 'true');
    }
    if (backdrop) {
        backdrop.hidden = true;
    }
}

/**
 * @returns {boolean}
 */
export function isHelpPanelOpen() {
    const panel = document.getElementById('sp-help-panel');
    return Boolean(panel && !panel.hidden);
}

let helpBound = false;
export function bindHelpPanelChrome() {
    if (helpBound) {
        return;
    }
    helpBound = true;
    document.getElementById('sp-help-close')?.addEventListener('click', closeHelpPanel);
    document.getElementById('sp-help-backdrop')?.addEventListener('click', closeHelpPanel);
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isHelpPanelOpen()) {
            closeHelpPanel();
        }
    });
}
