import { expect, test } from '@playwright/test';

const MOBILE_VIEWPORTS = [
    { name: 'phone', width: 390, height: 844 },
    { name: 'narrow-tablet', width: 580, height: 900 },
];

/**
 * Hub shell + sticky toolbars on phone. Tools generators stay out (phone-hide-tools).
 */
const PAGES = [
    { path: '/', label: 'landing' },
    { path: '/about', label: 'about' },
    { path: '/impressum', label: 'impressum' },
    { path: '/sprint-planner', label: 'sprint-planner-index' },
    { path: '/sprint-planner/templates', label: 'sprint-planner-templates' },
    { path: '/governance', label: 'governance-hub' },
    { path: '/governance/radar', label: 'governance-radar' },
    { path: '/playbooks', label: 'stories' },
    { path: '/playbooks/help-hub-platform', label: 'playbook-story' },
    { path: '/roles', label: 'roles' },
    { path: '/roles/steward', label: 'role-detail' },
    { path: '/glossary', label: 'glossary' },
    { path: '/glossary/bingo', label: 'bingo' },
    { path: '/glossary/data-steward', label: 'glossary-detail' },
    { path: '/learning-paths', label: 'learning-paths' },
    { path: '/learning-paths/pii-in-five-steps', label: 'learning-path-detail' },
    { path: '/resources', label: 'resources' },
    { path: '/suppliers', label: 'suppliers' },
    { path: '/suppliers/salesforce', label: 'supplier-detail' },
    { path: '/compliance', label: 'compliance' },
    { path: '/compliance/gdpr', label: 'compliance-detail' },
    { path: '/compliance/roadmap', label: 'compliance-roadmap' },
    { path: '/search', label: 'search' },
    { path: '/search?q=PII', label: 'search-results' },
    { path: '/calendar', label: 'calendar' },
];

const OVERLAY_PAGES = [
    '/governance/radar',
    '/playbooks',
    '/glossary',
    '/roles',
    '/governance',
];

async function shellMetrics(page) {
    return page.evaluate(() => {
        const sidebar = document.querySelector('.tools-shell__sidebar');
        const main = document.querySelector('.tools-shell__main');
        const header = document.querySelector('.tools-shell__header');
        const actions = document.querySelector('.tools-header__actions');
        const mission = document.querySelector('.tools-header__mission');
        const searchForm = document.querySelector('.tools-header__search');
        const searchLink = document.querySelector('.tools-header__search-link');
        const wordmark = document.querySelector('.tools-header__wordmark');
        const signInLabel = document.querySelector('.tools-header__sign-in-label');

        const sidebarBox = sidebar?.getBoundingClientRect();
        const mainBox = main?.getBoundingClientRect();
        const headerBox = header?.getBoundingClientRect();
        const actionsBox = actions?.getBoundingClientRect();
        const sidebarStyle = sidebar ? getComputedStyle(sidebar) : null;
        const searchFormStyle = searchForm ? getComputedStyle(searchForm) : null;
        const searchLinkStyle = searchLink ? getComputedStyle(searchLink) : null;
        const wordmarkStyle = wordmark ? getComputedStyle(wordmark) : null;
        const signInLabelStyle = signInLabel ? getComputedStyle(signInLabel) : null;

        return {
            viewportWidth: window.innerWidth,
            pageScrollWidth: document.documentElement.scrollWidth,
            missionPresent: Boolean(mission),
            searchFormDisplay: searchFormStyle?.display ?? null,
            searchLinkDisplay: searchLinkStyle?.display ?? null,
            wordmarkDisplay: wordmarkStyle?.display ?? null,
            signInLabelDisplay: signInLabelStyle?.display ?? null,
            sidebar: sidebarBox
                ? {
                    left: sidebarBox.left,
                    right: sidebarBox.right,
                    width: sidebarBox.width,
                    position: sidebarStyle?.position ?? null,
                }
                : null,
            main: mainBox
                ? {
                    left: mainBox.left,
                    width: mainBox.width,
                    ratio: mainBox.width / window.innerWidth,
                }
                : null,
            header: headerBox
                ? {
                    height: headerBox.height,
                    width: headerBox.width,
                }
                : null,
            actions: actionsBox
                ? {
                    top: actionsBox.top,
                    bottom: actionsBox.bottom,
                    height: actionsBox.height,
                    right: actionsBox.right,
                }
                : null,
        };
    });
}

for (const viewport of MOBILE_VIEWPORTS) {
    test.describe(`mobile shell ${viewport.name} (${viewport.width}px)`, () => {
        test.use({ viewport: { width: viewport.width, height: viewport.height } });

        for (const pageDef of PAGES) {
            test(`${pageDef.label} (${pageDef.path}) keeps shell header + sidebar correct`, async ({ page }) => {
                const response = await page.goto(pageDef.path, { waitUntil: 'domcontentloaded' });
                expect(response, `no response for ${pageDef.path}`).not.toBeNull();
                expect(response.status(), `HTTP for ${pageDef.path}`).toBeLessThan(400);

                await page.waitForSelector('.tools-shell__header');

                const metrics = await shellMetrics(page);

                expect(metrics.missionPresent, 'header mission text must stay removed').toBe(false);
                expect(metrics.searchFormDisplay).toBe('none');
                expect(metrics.searchLinkDisplay).not.toBe('none');
                expect(metrics.wordmarkDisplay).toBe('none');
                expect(metrics.sidebar, 'sidebar element missing').not.toBeNull();
                expect(metrics.main, 'main element missing').not.toBeNull();
                expect(metrics.header, 'header element missing').not.toBeNull();

                expect(metrics.sidebar.position).toBe('fixed');
                expect(metrics.sidebar.right).toBeLessThanOrEqual(1);

                expect(metrics.main.left).toBeLessThan(24);
                expect(metrics.main.ratio).toBeGreaterThan(0.82);

                expect(metrics.header.height).toBeLessThanOrEqual(56);
                expect(metrics.actions).not.toBeNull();
                expect(metrics.actions.top).toBeGreaterThanOrEqual(0);
                expect(metrics.actions.bottom).toBeLessThanOrEqual(metrics.header.height + 4);
                expect(metrics.actions.right).toBeLessThanOrEqual(metrics.viewportWidth + 1);

                expect(metrics.pageScrollWidth).toBeLessThanOrEqual(metrics.viewportWidth + 2);

                if (metrics.signInLabelDisplay !== null) {
                    expect(metrics.signInLabelDisplay).toBe('none');
                }
            });
        }

        for (const path of OVERLAY_PAGES) {
            test(`open sidebar stays above sticky toolbar on ${path}`, async ({ page }) => {
                await page.goto(path, { waitUntil: 'domcontentloaded' });
                await page.waitForSelector('[data-tools-sidebar-toggle]');

                const cookieAll = page.locator('[data-cookie-consent-all]');
                if (await cookieAll.isVisible().catch(() => false)) {
                    await cookieAll.click();
                }

                await page.locator('[data-tools-sidebar-toggle]').click();
                await expect(page.locator('.tools-shell')).toHaveClass(/tools-shell--sidebar-open/);
                await expect
                    .poll(async () => page.locator('.tools-shell__sidebar').evaluate((el) => {
                        const transform = getComputedStyle(el).transform;
                        if (!transform || transform === 'none') {
                            return 0;
                        }
                        const match = transform.match(/matrix\(([^)]+)\)/);
                        if (!match) {
                            return Number.NaN;
                        }
                        const tx = Number.parseFloat(match[1].split(',')[4]);
                        return Number.isFinite(tx) ? Math.abs(tx) : Number.NaN;
                    }))
                    .toBeLessThan(1);

                const layer = await page.evaluate(() => {
                    const sidebar = document.querySelector('.tools-shell__sidebar');
                    const sticky = document.querySelector(
                        '.governance-radar-sticky, .tools-overview-sticky-header'
                    );
                    const backdrop = document.querySelector('.tools-sidebar-backdrop');
                    const header = document.querySelector('.tools-shell__header');

                    const z = (el) => {
                        if (!el) {
                            return null;
                        }
                        const value = Number.parseInt(getComputedStyle(el).zIndex, 10);
                        return Number.isFinite(value) ? value : 0;
                    };

                    const sidebarBox = sidebar?.getBoundingClientRect();
                    const sampleX = Math.min(48, Math.max(12, (sidebarBox?.width ?? 48) * 0.25));
                    const sampleY = (header?.getBoundingClientRect().bottom ?? 44) + 36;
                    const topEl = document.elementFromPoint(sampleX, sampleY);

                    return {
                        sidebarZ: z(sidebar),
                        stickyZ: sticky ? z(sticky) : 0,
                        backdropZ: z(backdrop),
                        headerZ: z(header),
                        hitSidebar: Boolean(topEl?.closest('.tools-shell__sidebar')),
                        hitSticky: Boolean(
                            topEl?.closest('.governance-radar-sticky, .tools-overview-sticky-header, .governance-radar__toolbar')
                        ),
                        topClass: (topEl?.className || topEl?.tagName || '').toString().slice(0, 80),
                    };
                });

                expect(layer.headerZ).toBeGreaterThanOrEqual(50);
                expect(layer.sidebarZ).toBeGreaterThan(layer.stickyZ ?? 0);
                expect(layer.sidebarZ).toBeGreaterThan(layer.backdropZ ?? 0);
                expect(layer.headerZ).toBeGreaterThan(layer.sidebarZ);
                expect(layer.hitSidebar, `sidebar under hit (${layer.topClass})`).toBe(true);
                expect(layer.hitSticky, 'sticky toolbar must not cover open sidebar').toBe(false);
            });
        }

        test('stories toolbar has no horizontal scroll and no clipped controls', async ({ page }) => {
            await page.goto('/playbooks', { waitUntil: 'domcontentloaded' });
            await page.waitForSelector('.tools-overview-toolbar__row--controls');

            const metrics = await page.evaluate(() => {
                const row = document.querySelector('.tools-overview-toolbar__row--controls');
                const vw = window.innerWidth;
                const kids = [...(row?.children ?? [])].map((el) => {
                    const box = el.getBoundingClientRect();
                    return box.right > vw + 1 || box.left < -1;
                });

                return {
                    scrollWidth: row?.scrollWidth ?? 0,
                    clientWidth: row?.clientWidth ?? 0,
                    overflowX: row ? getComputedStyle(row).overflowX : null,
                    anyClipped: kids.some(Boolean),
                };
            });

            expect(metrics.overflowX).not.toBe('auto');
            expect(metrics.overflowX).not.toBe('scroll');
            expect(metrics.scrollWidth).toBeLessThanOrEqual(metrics.clientWidth + 1);
            expect(metrics.anyClipped).toBe(false);
        });

        test('stories filter opens as overlay on phone, not under content', async ({ page }) => {
            await page.goto('/playbooks', { waitUntil: 'domcontentloaded' });
            await page.waitForSelector('[data-tag-sidebar-toggle]');

            await page.locator('.tools-overview-toolbar [data-tag-sidebar-toggle]').click();

            const metrics = await page.evaluate(() => {
                const sidebar = document.querySelector('[data-tag-sidebar]');
                const style = sidebar ? getComputedStyle(sidebar) : null;
                const box = sidebar?.getBoundingClientRect();

                return {
                    collapsed: sidebar?.dataset.collapsed ?? null,
                    position: style?.position ?? null,
                    sidebarTop: box?.top ?? null,
                    sidebarLeft: box?.left ?? null,
                    vw: window.innerWidth,
                    backdropHidden: document.querySelector('[data-tag-sidebar-backdrop]')?.hidden ?? null,
                };
            });

            expect(metrics.collapsed).toBe('false');
            expect(metrics.position).toBe('fixed');
            expect(metrics.backdropHidden).toBe(false);
            expect(metrics.sidebarTop).toBeLessThan(120);
            expect(metrics.sidebarLeft).toBeGreaterThan(metrics.vw * 0.2);
        });
    });
}
