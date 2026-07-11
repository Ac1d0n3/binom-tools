import { expect } from '@playwright/test';

export async function expectTocRailVisible(page, selector = '[data-playbook-toc]') {
    const headerHeight = await page.evaluate(() => {
        const raw = getComputedStyle(document.documentElement)
            .getPropertyValue('--tools-header-height')
            .trim();

        const parsed = parseInt(raw, 10);

        return Number.isFinite(parsed) ? parsed : 44;
    });

    const box = await page.locator(selector).first().boundingBox();

    expect(box).not.toBeNull();

    const viewport = page.viewportSize();

    if (!viewport) {
        throw new Error('Viewport size is not set');
    }

    expect(box.y).toBeGreaterThanOrEqual(headerHeight - 2);
    expect(box.y).toBeLessThan(viewport.height);
    expect(box.height).toBeGreaterThan(0);
}

export async function getVisibleTop(page, selector) {
    return page.locator(selector).first().evaluate((el) => el.getBoundingClientRect().top);
}

export async function scrollContentTo(page, targetId) {
    await page.locator('[data-playbook-scroll-root]').first().evaluate((root, id) => {
        const target = root.querySelector(`#${id}`);

        if (!target) {
            return;
        }

        const top = target.getBoundingClientRect().top - root.getBoundingClientRect().top + root.scrollTop - 60;
        root.scrollTop = Math.max(0, top);
        root.dispatchEvent(new Event('scroll', { bubbles: true }));
    }, targetId);
}
