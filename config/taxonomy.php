<?php

/**
 * Shared vocabulary IDs (file-first, no DB).
 *
 * Modules reference these IDs where they match 1:1.
 */
return [
    'regions' => [
        'eu' => ['de' => 'EU', 'en' => 'EU'],
        'de' => ['de' => 'Deutschland', 'en' => 'Germany'],
        'us' => ['de' => 'USA', 'en' => 'US'],
        'intl' => ['de' => 'International', 'en' => 'International'],
    ],

    'audiences' => [
        'privacy' => ['de' => 'Privacy / DPO', 'en' => 'Privacy / DPO'],
        'engineering' => ['de' => 'Analytics Engineering', 'en' => 'Analytics engineering'],
        'platform' => ['de' => 'Platform / Warehouse', 'en' => 'Platform / warehouse'],
        'governance' => ['de' => 'Governance Lead', 'en' => 'Governance lead'],
        'metrics' => ['de' => 'KPI / Metrics', 'en' => 'KPI / metrics'],
        'ai' => ['de' => 'AI / ML', 'en' => 'AI / ML'],
        'certification' => ['de' => 'Zertifizierung', 'en' => 'Certification'],
    ],

    'orgContexts' => [
        'unknown' => ['de' => 'Noch offen / gemischt', 'en' => 'Open / mixed'],
        'startup' => ['de' => 'Startup', 'en' => 'Startup'],
        'midmarket' => ['de' => 'Midmarket / KMU', 'en' => 'Mid-market / SME'],
        'enterprise' => ['de' => 'Enterprise', 'en' => 'Enterprise'],
        'bank-finance' => ['de' => 'Bank / Finance (reguliert)', 'en' => 'Bank / finance (regulated)'],
        'public-sector' => ['de' => 'Behörde / öffentlicher Sektor', 'en' => 'Public sector'],
    ],

    // Legacy sessions may still store orgContext=sme — map to midmarket in Advisor JS.
    'orgContextAliases' => [
        'sme' => 'midmarket',
    ],

    'regulationPressures' => [
        'low' => ['de' => 'Niedrig', 'en' => 'Low'],
        'gdpr-heavy' => ['de' => 'DSGVO-stark', 'en' => 'GDPR-heavy'],
        'regulated' => ['de' => 'Reguliert (DORA/NIS2/…)', 'en' => 'Regulated (DORA/NIS2/…)'],
    ],

    'platforms' => [
        'unknown' => ['de' => 'Noch offen / mehrere', 'en' => 'Open / multiple'],
        'fabric' => ['de' => 'Microsoft Fabric', 'en' => 'Microsoft Fabric'],
        'databricks' => ['de' => 'Databricks', 'en' => 'Databricks'],
        'snowflake-dbt' => ['de' => 'Snowflake / dbt', 'en' => 'Snowflake / dbt'],
        'sap' => ['de' => 'SAP-zentriert', 'en' => 'SAP-centric'],
        'opensource' => ['de' => 'Open Source Stack', 'en' => 'Open-source stack'],
        'custom' => ['de' => 'Eigener Stack', 'en' => 'Custom stack'],
    ],
];
