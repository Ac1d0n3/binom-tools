/**
 * Playbook view/like/share controls (file-backed stats API).
 */

/** @type {WeakSet<HTMLElement>} */
const wired = new WeakSet();

let viewPosted = false;

/** @type {HTMLElement | null} */
let openShareMenu = null;

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

/**
 * @param {string} url
 * @param {RequestInit} [init]
 */
async function jsonFetch(url, init = {}) {
    const headers = new Headers(init.headers ?? {});
    headers.set('Accept', 'application/json');
    headers.set('X-Requested-With', 'XMLHttpRequest');

    if (init.method && init.method.toUpperCase() !== 'GET') {
        headers.set('X-CSRF-TOKEN', csrfToken());
    }

    const response = await fetch(url, {
        credentials: 'same-origin',
        ...init,
        headers,
    });

    if (!response.ok) {
        throw new Error(`Stats request failed: ${response.status}`);
    }

    return response.json();
}

/**
 * @param {ParentNode} scope
 * @param {{ views?: number, likes?: number, liked?: boolean }} data
 */
function renderStats(scope, data) {
    scope.querySelectorAll('[data-playbook-engagement]').forEach((root) => {
        const viewsEl = root.querySelector('[data-playbook-views]');
        const likesEl = root.querySelector('[data-playbook-likes]');
        const likeBtn = root.querySelector('[data-playbook-like]');
        const likeIcon = root.querySelector('[data-like-icon]');

        if (viewsEl && typeof data.views === 'number') {
            viewsEl.textContent = String(data.views);
        }

        if (likesEl && typeof data.likes === 'number') {
            likesEl.textContent = String(data.likes);
        }

        if (likeBtn && typeof data.liked === 'boolean') {
            likeBtn.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
            likeBtn.classList.toggle('playbook-engagement__like--active', data.liked);
            if (likeIcon) {
                likeIcon.classList.toggle('fa-solid', data.liked);
                likeIcon.classList.toggle('fa-regular', !data.liked);
            }
        }
    });
}

function sharePayload() {
    const title = document.querySelector('[itemprop="headline"]')?.textContent?.trim()
        ?? document.title;
    const url = window.location.href;
    const text = title;

    return { title, url, text };
}

/**
 * @param {string} network
 * @param {{ title: string, url: string, text: string }} payload
 */
function shareUrlFor(network, payload) {
    const encodedUrl = encodeURIComponent(payload.url);
    const encodedText = encodeURIComponent(payload.text);

    switch (network) {
        case 'facebook':
            return `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        case 'linkedin':
            return `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
        case 'xing':
            return `https://www.xing.com/spi/shares/new?url=${encodedUrl}`;
        case 'whatsapp':
            return `https://wa.me/?text=${encodeURIComponent(`${payload.text} ${payload.url}`)}`;
        case 'x':
            return `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
        case 'email':
            return `mailto:?subject=${encodeURIComponent(payload.title)}&body=${encodeURIComponent(`${payload.text}\n\n${payload.url}`)}`;
        default:
            return null;
    }
}

/**
 * @param {HTMLElement} wrap
 * @param {boolean} open
 */
function setShareMenuOpen(wrap, open) {
    const btn = wrap.querySelector('[data-playbook-share]');
    const menu = wrap.querySelector('[data-playbook-share-menu]');

    if (!(btn instanceof HTMLElement) || !(menu instanceof HTMLElement)) {
        return;
    }

    if (open) {
        if (openShareMenu && openShareMenu !== menu) {
            const prevWrap = openShareMenu.closest('[data-playbook-share-wrap]');
            if (prevWrap instanceof HTMLElement) {
                setShareMenuOpen(prevWrap, false);
            }
        }

        const payload = sharePayload();
        menu.querySelectorAll('[data-share-network]').forEach((link) => {
            if (!(link instanceof HTMLAnchorElement)) {
                return;
            }
            const network = link.dataset.shareNetwork ?? '';
            const href = shareUrlFor(network, payload);
            if (href) {
                link.href = href;
            }
        });

        menu.hidden = false;
        btn.setAttribute('aria-expanded', 'true');
        openShareMenu = menu;
    } else {
        menu.hidden = true;
        btn.setAttribute('aria-expanded', 'false');
        if (openShareMenu === menu) {
            openShareMenu = null;
        }
    }
}

function closeOpenShareMenu() {
    if (!openShareMenu) {
        return;
    }
    const wrap = openShareMenu.closest('[data-playbook-share-wrap]');
    if (wrap instanceof HTMLElement) {
        setShareMenuOpen(wrap, false);
    }
}

/**
 * @param {HTMLElement} widget
 * @param {ParentNode} syncScope
 */
function wireWidget(widget, syncScope) {
    if (wired.has(widget)) {
        return;
    }

    wired.add(widget);

    const showUrl = widget.dataset.statsShowUrl ?? '';
    const viewUrl = widget.dataset.statsViewUrl ?? '';
    const likeUrl = widget.dataset.statsLikeUrl ?? '';
    const shareEnabled = widget.dataset.shareEnabled === '1';

    const likeBtn = widget.querySelector('[data-playbook-like]');
    const shareWrap = widget.querySelector('[data-playbook-share-wrap]');
    const shareBtn = widget.querySelector('[data-playbook-share]');
    const shareMenu = widget.querySelector('[data-playbook-share-menu]');
    const copyBtn = widget.querySelector('[data-share-copy]');

    jsonFetch(showUrl)
        .then((data) => renderStats(syncScope, data))
        .catch(() => {});

    if (!viewPosted) {
        viewPosted = true;
        jsonFetch(viewUrl, { method: 'POST' })
            .then((data) => renderStats(syncScope, data))
            .catch(() => {});
    }

    likeBtn?.addEventListener('click', () => {
        likeBtn.disabled = true;
        jsonFetch(likeUrl, { method: 'POST' })
            .then((data) => renderStats(syncScope, data))
            .catch(() => {})
            .finally(() => {
                likeBtn.disabled = false;
            });
    });

    if (shareEnabled && shareWrap instanceof HTMLElement && shareBtn instanceof HTMLElement) {
        shareBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = shareBtn.getAttribute('aria-expanded') === 'true';
            setShareMenuOpen(shareWrap, !isOpen);
        });

        shareMenu?.querySelectorAll('[data-share-network]').forEach((link) => {
            link.addEventListener('click', () => {
                closeOpenShareMenu();
            });
        });

        copyBtn?.addEventListener('click', async () => {
            const { url } = sharePayload();
            try {
                await navigator.clipboard.writeText(url);
                shareBtn.classList.add('playbook-engagement__share--copied');
                window.setTimeout(() => {
                    shareBtn.classList.remove('playbook-engagement__share--copied');
                }, 1600);
            } catch {
                // ignore
            }
            closeOpenShareMenu();
        });
    }
}

/**
 * @param {HTMLElement} root
 */
export function initPlaybookEngagement(root) {
    root.querySelectorAll('[data-playbook-engagement]').forEach((widget) => {
        if (widget instanceof HTMLElement) {
            wireWidget(widget, root);
        }
    });
}

if (typeof document !== 'undefined') {
    document.addEventListener('click', (event) => {
        if (!openShareMenu) {
            return;
        }
        const target = event.target;
        if (!(target instanceof Node)) {
            return;
        }
        const wrap = openShareMenu.closest('[data-playbook-share-wrap]');
        if (wrap && !wrap.contains(target)) {
            closeOpenShareMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeOpenShareMenu();
        }
    });
}
