import { describe, expect, it } from 'vitest';
import { buildPureViewJson, buildPureViewMapping, buildPureViewRunbook, splitCsv } from './pureview-builder.js';

const baseState = {
    asset: 'sales.orders_curated',
    domain: 'sales-domain',
    platform: 'fabric',
    owner: 'data-owner-sales',
    steward: 'data-steward-sales',
    columns: ['order_id', 'customer_email', 'iban', 'updated_at'],
    sensitive: ['customer_email', 'iban'],
    frequency: 'daily',
    toolId: 'pureview-classification-generator',
};

describe('pureview builder', () => {
    it('splits comma separated values', () => {
        expect(splitCsv('a, b,,c')).toEqual(['a', 'b', 'c']);
    });

    it('builds classification JSON with sensitivity review markers', () => {
        const parsed = JSON.parse(buildPureViewJson(baseState));

        expect(parsed.assetName).toBe('sales.orders_curated');
        expect(parsed.classifications).toContainEqual({
            column: 'customer_email',
            classification: 'Email Address',
            sensitivity: 'Confidential',
            reviewRequired: true,
        });
    });

    it('builds dbt-compatible mapping YAML', () => {
        const yaml = buildPureViewMapping(baseState);

        expect(yaml).toContain('pureview_collection: sales-domain');
        expect(yaml).toContain('pureview_classification: Email Address');
        expect(yaml).toContain('review_required: true');
    });

    it('builds scan JSON for first setup examples', () => {
        const scan = JSON.parse(buildPureViewJson({ ...baseState, toolId: 'pureview-scan-generator' }));

        expect(scan.kind).toBe('FabricWarehouse');
        expect(scan.properties.scan.includeColumns).toEqual(baseState.columns);
        expect(scan.properties.scan.frequency).toBe('daily');
    });

    it('builds data product runbook with review gates', () => {
        const runbook = buildPureViewRunbook({ ...baseState, toolId: 'pureview-data-product-generator' });

        expect(runbook).toContain('PureView data product onboarding');
        expect(runbook).toContain('Owner accepts accountability');
        expect(runbook).toContain('customer_email, iban');
    });
});
