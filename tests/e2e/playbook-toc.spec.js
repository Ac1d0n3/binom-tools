import { test, expect } from '@playwright/test';
import { expectTocRailVisible, getVisibleTop, scrollContentTo } from './helpers/viewport.js';

const PLAYBOOK_URL = '/playbooks/help-hub-platform';
const VISIBLE_PANEL = '[data-playbook-locale-panel="de"]:not([hidden])';

test.describe('Playbook TOC', () => {
    test.beforeEach(async ({ page }) => {
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto(PLAYBOOK_URL);
        await page.waitForSelector(`${VISIBLE_PANEL} [data-playbook-toc]`);
    });

    test('toc rail stays in viewport while content scrolls', async ({ page }) => {
        const scrollRoot = page.locator(`${VISIBLE_PANEL} [data-playbook-scroll-root]`).first();

        await expectTocRailVisible(page, `${VISIBLE_PANEL} [data-playbook-toc]`);

        await scrollRoot.evaluate((el) => {
            el.scrollTop = 2000;
        });

        await page.waitForTimeout(200);

        await expectTocRailVisible(page, `${VISIBLE_PANEL} [data-playbook-toc]`);
        expect(await scrollRoot.evaluate((el) => el.scrollTop)).toBeGreaterThan(500);
    });

    test('toc does not move with content column', async ({ page }) => {
        const scrollRoot = page.locator(`${VISIBLE_PANEL} [data-playbook-scroll-root]`).first();
        const topBefore = await getVisibleTop(page, `${VISIBLE_PANEL} [data-playbook-toc]`);

        await scrollRoot.evaluate((el) => {
            el.scrollTop = 1800;
        });

        await page.waitForTimeout(200);

        const topAfter = await getVisibleTop(page, `${VISIBLE_PANEL} [data-playbook-toc]`);

        expect(Math.abs(topAfter - topBefore)).toBeLessThanOrEqual(2);
    });

    test('branch expands and shows h3 links', async ({ page }) => {
        const branch = page.locator(`${VISIBLE_PANEL} [data-playbook-toc-branch]`).filter({
            has: page.locator('[data-playbook-toc-link]', { hasText: 'Story hinzufügen' }),
        }).first();

        const toggle = branch.locator('[data-playbook-toc-group-toggle]');
        const sublist = branch.locator('[data-playbook-toc-sublist]');

        await expect(sublist).not.toHaveClass(/playbook-toc__sublist--open/);

        await toggle.click();

        await expect(sublist).toHaveClass(/playbook-toc__sublist--open/);
        await expect(sublist.locator('[data-target-id="de-frontmatter-felder"]')).toBeVisible();
    });

    test('leaf has no toggle button', async ({ page }) => {
        const leaf = page.locator(`${VISIBLE_PANEL} [data-playbook-toc-leaf]`).filter({
            has: page.locator('[data-playbook-toc-link]', { hasText: 'Überblick' }),
        }).first();

        await expect(leaf.locator('[data-playbook-toc-group-toggle]')).toHaveCount(0);
    });

    test('scroll spy marks active section', async ({ page }) => {
        await scrollContentTo(page, 'de-architektur');
        await page.waitForTimeout(250);

        const activeLink = page.locator(`${VISIBLE_PANEL} [data-playbook-toc-link][data-target-id="de-architektur"]`);

        await expect(activeLink).toHaveClass(/playbook-toc__link--active/);
    });

    test('anchor click scrolls content and keeps toc visible', async ({ page }) => {
        const link = page.locator(`${VISIBLE_PANEL} [data-playbook-toc-link][data-target-id="de-deployment"]`).first();

        await link.click();
        await page.waitForTimeout(500);

        await expect(page.locator(`${VISIBLE_PANEL} #de-deployment`)).toBeVisible();
        await expectTocRailVisible(page, `${VISIBLE_PANEL} [data-playbook-toc]`);
    });
});
