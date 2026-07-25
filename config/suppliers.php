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
        'analytics' => ['de' => 'Analytics', 'en' => 'Analytics'],
    ],

    'products' => require __DIR__.'/suppliers-catalog.php',
];
