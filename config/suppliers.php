<?php

/**
 * Supplier Library Hub — meta labels and product catalogue.
 *
 * Templates for core fields, dimensions, PII/DSDR and standard measures.
 * Not a full schema dump; adapt grain, filters and custom fields per customer.
 */
return [
    'domains' => [
        'crm' => ['de' => 'CRM', 'en' => 'CRM'],
        'service' => ['de' => 'Service', 'en' => 'Service'],
        'commerce' => ['de' => 'Commerce', 'en' => 'Commerce'],
        'erp' => ['de' => 'ERP', 'en' => 'ERP'],
        'hcm' => ['de' => 'HCM', 'en' => 'HCM'],
        'collab' => ['de' => 'Collab', 'en' => 'Collab'],
        'finance' => ['de' => 'Finance', 'en' => 'Finance'],
        'workplace' => ['de' => 'Workplace', 'en' => 'Workplace'],
        'analytics' => ['de' => 'Analytics', 'en' => 'Analytics'],
        'banking' => ['de' => 'Banking', 'en' => 'Banking'],
        'insurance' => ['de' => 'Insurance', 'en' => 'Insurance'],
        'dms' => ['de' => 'DMS', 'en' => 'DMS'],
        'learning' => ['de' => 'Learning', 'en' => 'Learning'],
        'marketing' => ['de' => 'Marketing', 'en' => 'Marketing'],
        'bpm' => ['de' => 'BPM', 'en' => 'BPM'],
        'healthcare' => ['de' => 'Healthcare', 'en' => 'Healthcare'],
    ],

    'products' => require __DIR__.'/suppliers-catalog.php',
];
