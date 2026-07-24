/* Playbooks offline service worker — caches only what the user explicitly downloads. */
const META_CACHE = 'binom-playbooks-meta-v1';
const SHELL_CACHE = 'binom-playbooks-shell-v1';
const STORY_PREFIX = 'binom-playbooks-story-';

const SW_DIR = self.location.pathname.replace(/\/[^/]*$/, '');
const FALLBACK_URL = `${SW_DIR}/playbooks-offline-fallback.html`;

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(META_CACHE).then((cache) => cache.add(FALLBACK_URL).catch(() => undefined)),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('message', (event) => {
    const data = event.data;
    if (!data || typeof data !== 'object') {
        return;
    }

    if (data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

/**
 * @param {string} pathname
 */
function isPlaybookNavigation(pathname) {
    return /(?:^|\/)(?:de\/)?playbooks(?:\/[a-z0-9-]+)?\/?$/.test(pathname);
}

/**
 * @param {string} cacheName
 * @param {Request} request
 * @param {URL} url
 */
async function matchInCache(cacheName, request, url) {
    const cache = await caches.open(cacheName);
    const hit = await cache.match(request, { ignoreSearch: true });
    if (hit) {
        return hit;
    }

    const byHref = await cache.match(url.href, { ignoreSearch: true });
    if (byHref) {
        return byHref;
    }

    return cache.match(url.pathname, { ignoreSearch: true });
}

/**
 * @param {Request} request
 * @param {URL} url
 */
async function handleNavigation(request, url) {
    const cacheNames = await caches.keys();
    const relevant = cacheNames.filter(
        (name) => name === SHELL_CACHE || name.startsWith(STORY_PREFIX) || name === META_CACHE,
    );

    for (const name of relevant) {
        const hit = await matchInCache(name, request, url);
        if (hit) {
            return hit;
        }
    }

    try {
        return await fetch(request);
    } catch {
        const fallback = await matchInCache(META_CACHE, new Request(FALLBACK_URL), new URL(FALLBACK_URL, self.location.origin));
        if (fallback) {
            return fallback;
        }

        return new Response(
            '<!DOCTYPE html><html><body><h1>Offline</h1><p>This story is not available offline.</p></body></html>',
            { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } },
        );
    }
}

/**
 * @param {Request} request
 * @param {URL} url
 */
async function handleAsset(request, url) {
    try {
        return await fetch(request);
    } catch {
        const cacheNames = await caches.keys();
        const relevant = cacheNames.filter(
            (name) => name === SHELL_CACHE || name.startsWith(STORY_PREFIX) || name === META_CACHE,
        );

        for (const name of relevant) {
            const hit = await matchInCache(name, request, url);
            if (hit) {
                return hit;
            }
        }

        return new Response('', { status: 503, statusText: 'Offline' });
    }
}

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    if (url.pathname.includes('/offline-manifest')) {
        return;
    }

    const accept = request.headers.get('accept') || '';
    const isNavigation = request.mode === 'navigate' || accept.includes('text/html');

    if (isNavigation && isPlaybookNavigation(url.pathname)) {
        event.respondWith(handleNavigation(request, url));
        return;
    }

    // Network-first for same-origin assets; fall back to cache when offline.
    if (
        url.pathname.includes('/build/')
        || url.pathname.includes('/images/playbooks/')
        || url.pathname.includes('/videos/playbooks/')
        || url.pathname.endsWith('/sw-playbooks.js')
        || url.pathname.endsWith('/playbooks-offline-fallback.html')
        || /favicon|apple-touch-icon/.test(url.pathname)
    ) {
        event.respondWith(handleAsset(request, url));
    }
});
