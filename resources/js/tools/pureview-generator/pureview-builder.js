/** @param {string} value */
export function splitCsv(value) {
    return value.split(',').map((item) => item.trim()).filter(Boolean);
}

/** @param {string} value */
function slug(value) {
    return value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'asset';
}

/** @param {string} column */
function classificationForColumn(column) {
    const name = column.toLowerCase();
    if (name.includes('email')) return 'Email Address';
    if (name.includes('iban') || name.includes('account')) return 'Bank Account Number';
    if (name.includes('phone') || name.includes('mobile')) return 'Phone Number';
    if (name.includes('name')) return 'Person Name';
    if (name.includes('birth') || name.includes('dob')) return 'Date of Birth';
    if (name.includes('customer') || name.includes('person')) return 'Customer Identifier';
    return 'Business Data';
}

/** @param {string} platform */
function platformKind(platform) {
    return {
        fabric: 'FabricWarehouse',
        databricks: 'AzureDatabricks',
        sqlserver: 'AzureSqlDatabase',
        adls: 'AdlsGen2',
    }[platform] || 'GenericDataSource';
}

/** @param {{ asset: string, domain: string, platform: string, owner: string, steward: string, columns: string[], sensitive: string[], frequency: string, toolId: string }} state */
export function buildPureViewJson(state) {
    if (state.toolId === 'pureview-classification-generator') {
        return JSON.stringify({
            assetName: state.asset,
            collection: state.domain,
            owner: state.owner,
            steward: state.steward,
            classifications: state.columns.map((column) => ({
                column,
                classification: classificationForColumn(column),
                sensitivity: state.sensitive.includes(column) ? 'Confidential' : 'Internal',
                reviewRequired: state.sensitive.includes(column),
            })),
        }, null, 2);
    }

    if (state.toolId === 'pureview-glossary-generator') {
        return JSON.stringify({
            name: state.asset,
            status: 'Draft',
            domain: state.domain,
            owner: state.owner,
            steward: state.steward,
            definition: `Business definition for ${state.asset}. Replace this with the approved KPI or term definition.`,
            acronyms: [slug(state.asset).toUpperCase().replaceAll('-', '_')],
            relatedAssets: state.columns.map((column) => ({
                asset: `${state.domain}.${slug(state.asset)}`,
                column,
            })),
        }, null, 2);
    }

    if (state.toolId === 'pureview-data-product-generator') {
        return JSON.stringify({
            name: state.asset,
            id: slug(state.asset),
            domain: state.domain,
            platform: state.platform,
            owner: state.owner,
            steward: state.steward,
            criticalDataElements: state.columns,
            sensitiveElements: state.sensitive,
            serviceLevel: {
                refresh: state.frequency,
                freshnessColumn: state.columns.find((column) => column.toLowerCase().includes('updated')) || 'updated_at',
            },
            gates: ['owner-approved', 'classification-reviewed', 'dq-rules-passed', 'consumer-readme-published'],
        }, null, 2);
    }

    return JSON.stringify({
        name: `${slug(state.asset)}-${state.frequency}-scan`,
        kind: platformKind(state.platform),
        properties: {
            dataSourceName: state.asset,
            collection: {
                referenceName: state.domain,
                type: 'CollectionReference',
            },
            scan: {
                frequency: state.frequency,
                includeColumns: state.columns,
                classifyColumns: true,
                owner: state.owner,
                steward: state.steward,
            },
        },
    }, null, 2);
}

/** @param {{ asset: string, domain: string, platform: string, owner: string, steward: string, columns: string[], sensitive: string[], frequency: string, toolId: string }} state */
export function buildPureViewMapping(state) {
    const columnLines = state.columns.map((column) => {
        const sensitive = state.sensitive.includes(column);
        return `      - name: ${column}
        meta:
          pureview_classification: ${classificationForColumn(column)}
          sensitivity: ${sensitive ? 'confidential' : 'internal'}
          review_required: ${sensitive ? 'true' : 'false'}`;
    }).join('\n');

    if (state.toolId === 'pureview-glossary-generator') {
        return `business_term:
  name: ${state.asset}
  domain: ${state.domain}
  owner: ${state.owner}
  steward: ${state.steward}
  status: draft
  synonyms:
    - ${slug(state.asset)}
    - ${state.asset.toUpperCase()}
  related_columns:
${state.columns.map((column) => `    - ${column}`).join('\n')}`;
    }

    if (state.toolId === 'pureview-data-product-generator') {
        return `data_product:
  name: ${state.asset}
  id: ${slug(state.asset)}
  domain: ${state.domain}
  owner: ${state.owner}
  steward: ${state.steward}
  refresh: ${state.frequency}
  critical_data_elements:
${state.columns.map((column) => `    - ${column}`).join('\n')}
  governance_gates:
    - owner-approved
    - classification-reviewed
    - dq-rules-passed
    - consumer-readme-published`;
    }

    return `version: 2
models:
  - name: ${slug(state.asset).replaceAll('-', '_')}
    meta:
      pureview_collection: ${state.domain}
      platform: ${state.platform}
      owner: ${state.owner}
      steward: ${state.steward}
      scan_frequency: ${state.frequency}
    columns:
${columnLines}`;
}

/** @param {{ asset: string, domain: string, platform: string, owner: string, steward: string, columns: string[], sensitive: string[], frequency: string, toolId: string }} state */
export function buildPureViewRunbook(state) {
    const title = {
        'pureview-scan-generator': 'PureView scan setup',
        'pureview-classification-generator': 'PureView classification review',
        'pureview-glossary-generator': 'PureView glossary review',
        'pureview-data-product-generator': 'PureView data product onboarding',
    }[state.toolId] || 'PureView setup';

    return `# ${title}

Asset: ${state.asset}
Domain / collection: ${state.domain}
Platform: ${state.platform}
Owner: ${state.owner}
Steward: ${state.steward}
Frequency: ${state.frequency}

## Copy-paste setup steps
1. Create or select the collection '${state.domain}'.
2. Register the source or asset '${state.asset}'.
3. Assign owner '${state.owner}' and steward '${state.steward}'.
4. Review sensitive fields: ${state.sensitive.join(', ') || 'none listed'}.
5. Add glossary or data product links for: ${state.columns.join(', ')}.
6. Run the first scan/review and attach the result to the setup ticket.

## Review gates
- Owner accepts accountability.
- Steward validates classifications and glossary terms.
- Sensitive fields have access and export review.
- DQ checks exist before broad consumer onboarding.
- Changes are documented in the platform backlog.`;
}

/** @param {{ asset: string, domain: string, platform: string, owner: string, steward: string, columns: string[], sensitive: string[], frequency: string, toolId: string }} state */
export function buildPureViewOutputs(state) {
    return {
        json: buildPureViewJson(state),
        mapping: buildPureViewMapping(state),
        runbook: buildPureViewRunbook(state),
    };
}
