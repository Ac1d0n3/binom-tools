import { describe, expect, it } from 'vitest';
import {
    buildPlanToolHref,
    isAllowedHelpHref,
    isAppToolHref,
    localizeToolHref,
    parseExternalLinksTextarea,
    resolveHelpHref,
} from './external-links.js';

describe('sprint-planner external / tool help links', () => {
    it('accepts /tools/ paths', () => {
        expect(isAppToolHref('/tools/report-inventory')).toBe(true);
        expect(isAppToolHref('/de/tools/report-inventory')).toBe(true);
        expect(isAppToolHref('/playbooks/bi-tools')).toBe(false);
        expect(isAllowedHelpHref('/tools/kpi-definition')).toBe(true);
        expect(isAllowedHelpHref('/playbooks/bi-tools')).toBe(false);
    });

    it('localizes and resolves tool hrefs', () => {
        expect(localizeToolHref('/tools/report-inventory', 'de')).toBe('/de/tools/report-inventory');
        expect(resolveHelpHref('/tools/report-inventory', 'en')).toBe('/en/tools/report-inventory');
        expect(resolveHelpHref('https://example.com/x', 'de')).toBe('https://example.com/x');
        expect(resolveHelpHref('/playbooks/bi-tools', 'de')).toBe('#');
    });

    it('builds plan context query on tool links', () => {
        const href = buildPlanToolHref('/tools/report-inventory', {
            instanceId: 'plan_1',
            itemKey: 'tpl:week-02:task:inventory-reports',
            kind: 'task',
            sprintId: 'week-02',
            custom: false,
            returnUrl: '/de/sprint-planner/plan_1',
        }, 'de');
        expect(href.startsWith('/de/tools/report-inventory?')).toBe(true);
        expect(href).toContain('fromPlan=1');
        expect(href).toContain('instanceId=plan_1');
        expect(href).toContain('itemKey=tpl%3Aweek-02%3Atask%3Ainventory-reports');
        expect(href).toContain('return=%2Fde%2Fsprint-planner%2Fplan_1');
    });

    it('parses tool lines in the links textarea', () => {
        const { links, rejected } = parseExternalLinksTextarea(
            'Report Inventory | /tools/report-inventory\nStory | /playbooks/bi-tools',
        );
        expect(links).toEqual([
            { label: 'Report Inventory', href: '/tools/report-inventory' },
        ]);
        expect(rejected).toEqual(['Story | /playbooks/bi-tools']);
    });
});
