<?php

/**
 * Supplier Library catalogue — lean core fields, dims, PII/DSDR, measures.
 *
 * Shared CRM measure templates are expanded per product with field mappings.
 */

$crmDimensions = [
    [
        'id' => 'owner',
        'label' => ['de' => 'Owner / Team', 'en' => 'Owner / team'],
        'grain' => ['de' => 'User → Rolle → Team', 'en' => 'User → role → team'],
        'notes' => [
            'de' => 'Owner-Mapping über CRMs hinweg angleichen, wenn beide Systeme laufen.',
            'en' => 'Align owner mapping across CRMs when both systems run.',
        ],
    ],
    [
        'id' => 'stage',
        'label' => ['de' => 'Stage / Pipeline', 'en' => 'Stage / pipeline'],
        'grain' => ['de' => 'Stage-Name + Pipeline', 'en' => 'Stage name + pipeline'],
        'notes' => [
            'de' => 'Stage-Labels sind firmenspezifisch — Won/Lost/Open festlegen.',
            'en' => 'Stage labels are firm-specific — lock won/lost/open meanings.',
        ],
    ],
    [
        'id' => 'region',
        'label' => ['de' => 'Region / Land', 'en' => 'Region / country'],
        'grain' => ['de' => 'Land / Region', 'en' => 'Country / region'],
        'notes' => [
            'de' => 'Billing- vs. Shipping-Land bewusst wählen.',
            'en' => 'Consciously choose billing vs shipping country.',
        ],
    ],
    [
        'id' => 'source',
        'label' => ['de' => 'Lead Source / Channel', 'en' => 'Lead source / channel'],
        'grain' => ['de' => 'Source-Wert', 'en' => 'Source value'],
        'notes' => [
            'de' => 'Picklist-Werte vor dem Mart harmonisieren.',
            'en' => 'Harmonize picklist values before the mart.',
        ],
    ],
];

$crmTools = [
    'kpi-definition',
    'pii-recommend-generator',
    'pii-policy-generator',
    'schema-yml-editor',
    'powerbi-dax-generator',
    'qlik-set-analysis-generator',
    'tableau-calculation-generator',
];

$relatedPlaybooks = [
    'define-kpi',
    'kpi-metric-governance',
    'pii-privacy-governance',
    'dsdr-governance',
];

/**
 * @param  array<string, array{fieldsUsed: list<string>, sourceHints?: array{de: string, en: string}}>  $fieldMaps
 * @return list<array<string, mixed>>
 */
$crmMeasures = static function (array $fieldMaps): array {
    $templates = [
        [
            'id' => 'revenue-won',
            'example' => true,
            'label' => ['de' => 'Umsatz (Won)', 'en' => 'Revenue (Won)'],
            'question' => [
                'de' => 'Wie viel Deal-Wert haben wir in der Periode gewonnen?',
                'en' => 'How much deal value did we win in the period?',
            ],
            'formula' => 'SUM(amount) WHERE is_won = true AND close_date IN period',
            'grain' => ['de' => 'Opportunity / Deal', 'en' => 'Opportunity / Deal'],
            'dimensions' => ['owner', 'region', 'stage'],
            'adapt' => [
                'de' => 'Stornos, Teilgewinne, Multi-Currency und Close- vs. Booking-Datum klären.',
                'en' => 'Clarify refunds, partial wins, multi-currency, and close vs booking date.',
            ],
        ],
        [
            'id' => 'arr',
            'example' => true,
            'label' => ['de' => 'ARR', 'en' => 'ARR'],
            'question' => [
                'de' => 'Wie hoch ist der annualisierte wiederkehrende Umsatz?',
                'en' => 'What is annualized recurring revenue?',
            ],
            'formula' => 'SUM(annualized_recurring_value) WHERE contract_active_in_period',
            'grain' => ['de' => 'Subscription / Contract-Zeile', 'en' => 'Subscription / contract line'],
            'dimensions' => ['owner', 'region'],
            'adapt' => [
                'de' => 'Nur wenn Recurring-/Contract-Felder existieren. Sonst aus Custom Fields oder Billing ableiten — nicht Amount als ARR missbrauchen.',
                'en' => 'Only when recurring/contract fields exist. Otherwise derive from custom or billing — do not misuse Amount as ARR.',
            ],
        ],
        [
            'id' => 'pipeline-amount',
            'example' => false,
            'label' => ['de' => 'Pipeline Amount', 'en' => 'Pipeline amount'],
            'question' => [
                'de' => 'Wie viel offener Opportunity-Wert steht im Funnel?',
                'en' => 'How much open opportunity value is in the funnel?',
            ],
            'formula' => 'SUM(amount) WHERE is_closed = false',
            'grain' => ['de' => 'Opportunity / Deal', 'en' => 'Opportunity / Deal'],
            'dimensions' => ['owner', 'stage', 'source'],
            'adapt' => [
                'de' => 'Weighted Pipeline (× Probability) separat definieren.',
                'en' => 'Define weighted pipeline (× probability) separately.',
            ],
        ],
        [
            'id' => 'win-rate',
            'example' => false,
            'label' => ['de' => 'Win Rate', 'en' => 'Win rate'],
            'question' => [
                'de' => 'Welcher Anteil der geschlossenen Deals ist gewonnen?',
                'en' => 'What share of closed deals was won?',
            ],
            'formula' => 'COUNT(won) / COUNT(closed)',
            'grain' => ['de' => 'Opportunity / Deal', 'en' => 'Opportunity / Deal'],
            'dimensions' => ['owner', 'source'],
            'adapt' => [
                'de' => 'Withdrawn/Duplicate-Stages aus dem Nenner nehmen, falls der Prozess sie nutzt.',
                'en' => 'Exclude withdrawn/duplicate stages from the denominator if the process uses them.',
            ],
        ],
        [
            'id' => 'avg-deal-size',
            'example' => false,
            'label' => ['de' => 'Average Deal Size', 'en' => 'Average deal size'],
            'question' => [
                'de' => 'Was ist der typische Won-Deal-Wert?',
                'en' => 'What is the typical won deal value?',
            ],
            'formula' => 'SUM(won_amount) / COUNT(won_deals)',
            'grain' => ['de' => 'Opportunity / Deal', 'en' => 'Opportunity / Deal'],
            'dimensions' => ['owner', 'region'],
            'adapt' => [
                'de' => 'Median vs. Mean und Währungsumrechnung festlegen.',
                'en' => 'Lock median vs mean and currency conversion.',
            ],
        ],
    ];

    $out = [];
    foreach ($templates as $template) {
        $id = $template['id'];
        $map = $fieldMaps[$id] ?? ['fieldsUsed' => [], 'sourceHints' => ['de' => '', 'en' => '']];
        $out[] = array_merge($template, [
            'fieldsUsed' => $map['fieldsUsed'],
            'sourceHints' => $map['sourceHints'] ?? ['de' => '', 'en' => ''],
        ]);
    }

    return $out;
};

$products = array_merge([
    [
        'id' => 'salesforce',
        'domain' => 'crm',
        'order' => 10,
        'label' => ['de' => 'Salesforce', 'en' => 'Salesforce'],
        'shortPurpose' => [
            'de' => 'CRM-Kern: Accounts, Contacts, Opportunities — Load, PII und Standard-Measures.',
            'en' => 'CRM core: Accounts, Contacts, Opportunities — load, PII and standard measures.',
        ],
        'entities' => [
            [
                'id' => 'account',
                'label' => ['de' => 'Account', 'en' => 'Account'],
                'description' => [
                    'de' => 'Firmen- oder Kundenstammdaten — Dimension und Hierarchie-Wurzel.',
                    'en' => 'Company or customer master data — dimension and hierarchy root.',
                ],
                'grain' => ['de' => 'Ein Firmen-/Kunden-Datensatz', 'en' => 'One company / customer record'],
                'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                'load' => 'required',
            ],
            [
                'id' => 'contact',
                'label' => ['de' => 'Contact', 'en' => 'Contact'],
                'description' => [
                    'de' => 'Personen am Account — PII-lastig, zentral für DSDR.',
                    'en' => 'People on an account — PII-heavy, central for DSDR.',
                ],
                'grain' => ['de' => 'Eine Person', 'en' => 'One person'],
                'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                'load' => 'required',
            ],
            [
                'id' => 'lead',
                'label' => ['de' => 'Lead', 'en' => 'Lead'],
                'description' => [
                    'de' => 'Vorqualifizierte Prospects vor Conversion — Funnel-Top.',
                    'en' => 'Pre-qualified prospects before conversion — funnel top.',
                ],
                'grain' => ['de' => 'Ein Lead', 'en' => 'One lead'],
                'role' => ['de' => 'Funnel / PII', 'en' => 'Funnel / PII'],
                'load' => 'optional',
            ],
            [
                'id' => 'opportunity',
                'label' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'description' => [
                    'de' => 'Deal-Kopf für Pipeline, Wins und Umsatz-KPIs.',
                    'en' => 'Deal header for pipeline, wins and revenue KPIs.',
                ],
                'grain' => ['de' => 'Ein Deal', 'en' => 'One deal'],
                'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                'load' => 'required',
            ],
            [
                'id' => 'opportunitylineitem',
                'label' => ['de' => 'OpportunityLineItem', 'en' => 'OpportunityLineItem'],
                'description' => [
                    'de' => 'Produktzeilen am Deal — nur wenn Produktmix zählt.',
                    'en' => 'Product lines on a deal — only when product mix matters.',
                ],
                'grain' => ['de' => 'Eine Produktzeile', 'en' => 'One product line'],
                'role' => ['de' => 'Fact (fein)', 'en' => 'Fact (fine)'],
                'load' => 'optional',
            ],
            [
                'id' => 'user',
                'label' => ['de' => 'User', 'en' => 'User'],
                'description' => [
                    'de' => 'Salesforce-User für Owner-/Team-Dimension (ohne volles Profil-Dump).',
                    'en' => 'Salesforce users for owner/team dimension (without full profile dump).',
                ],
                'grain' => ['de' => 'Ein Salesforce-User', 'en' => 'One Salesforce user'],
                'role' => ['de' => 'Owner-Dimension', 'en' => 'Owner dimension'],
                'load' => 'required',
            ],
            [
                'id' => 'campaign',
                'label' => ['de' => 'Campaign', 'en' => 'Campaign'],
                'description' => [
                    'de' => 'Marketing-Kampagnen für Attribution — optional laden.',
                    'en' => 'Marketing campaigns for attribution — load optionally.',
                ],
                'grain' => ['de' => 'Eine Kampagne', 'en' => 'One campaign'],
                'role' => ['de' => 'Attribution', 'en' => 'Attribution'],
                'load' => 'optional',
            ],
            [
                'id' => 'case',
                'label' => ['de' => 'Case', 'en' => 'Case'],
                'description' => [
                    'de' => 'Support-Tickets — nur bei Service-Analytics laden.',
                    'en' => 'Support tickets — load only for service analytics.',
                ],
                'grain' => ['de' => 'Ein Case', 'en' => 'One case'],
                'role' => ['de' => 'Service-Fact', 'en' => 'Service fact'],
                'load' => 'optional',
            ],
            [
                'id' => 'task',
                'label' => ['de' => 'Task', 'en' => 'Task'],
                'description' => [
                    'de' => 'Aktivitäten — oft laut und PII-haltig; nur mit klarem Konsumenten.',
                    'en' => 'Activities — often noisy and PII-heavy; only with a clear consumer.',
                ],
                'grain' => ['de' => 'Eine Aktivität', 'en' => 'One activity'],
                'role' => ['de' => 'Engagement (optional)', 'en' => 'Engagement (optional)'],
                'load' => 'optional',
            ],
        ],
        'fields' => [
            ['entity' => 'Opportunity', 'name' => 'Id', 'role' => 'key', 'why' => ['de' => 'Join / Lineage', 'en' => 'Join / lineage']],
            ['entity' => 'Opportunity', 'name' => 'Amount', 'role' => 'measure', 'why' => ['de' => 'Umsatz / Pipeline', 'en' => 'Revenue / pipeline']],
            ['entity' => 'Opportunity', 'name' => 'IsWon', 'role' => 'measure', 'why' => ['de' => 'Won-Filter', 'en' => 'Won filter']],
            ['entity' => 'Opportunity', 'name' => 'IsClosed', 'role' => 'measure', 'why' => ['de' => 'Open vs closed', 'en' => 'Open vs closed']],
            ['entity' => 'Opportunity', 'name' => 'CloseDate', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
            ['entity' => 'Opportunity', 'name' => 'Probability', 'role' => 'measure', 'why' => ['de' => 'Weighted Pipeline', 'en' => 'Weighted pipeline']],
            ['entity' => 'Opportunity', 'name' => 'Type', 'role' => 'dimension', 'why' => ['de' => 'Deal-Typ', 'en' => 'Deal type']],
            ['entity' => 'Opportunity', 'name' => 'CurrencyIsoCode', 'role' => 'measure', 'why' => ['de' => 'Multi-Currency', 'en' => 'Multi-currency']],
            ['entity' => 'Opportunity', 'name' => 'StageName', 'role' => 'dimension', 'why' => ['de' => 'Stage-Dimension', 'en' => 'Stage dimension']],
            ['entity' => 'Opportunity', 'name' => 'OwnerId', 'role' => 'dimension', 'why' => ['de' => 'Owner', 'en' => 'Owner']],
            ['entity' => 'Opportunity', 'name' => 'LeadSource', 'role' => 'dimension', 'why' => ['de' => 'Channel', 'en' => 'Channel']],
            ['entity' => 'Opportunity', 'name' => 'AccountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
            ['entity' => 'Opportunity', 'name' => 'CreatedDate', 'role' => 'key', 'why' => ['de' => 'Cycle Time / CDC', 'en' => 'Cycle time / CDC']],
            ['entity' => 'Opportunity', 'name' => 'LastModifiedDate', 'role' => 'key', 'why' => ['de' => 'Incremental Sync', 'en' => 'Incremental sync']],
            ['entity' => 'Opportunity', 'name' => 'SystemModstamp', 'role' => 'key', 'why' => ['de' => 'CDC / Watermark', 'en' => 'CDC / watermark']],
            ['entity' => 'Opportunity', 'name' => 'IsDeleted', 'role' => 'key', 'why' => ['de' => 'Soft delete / CDC', 'en' => 'Soft delete / CDC']],
            ['entity' => 'OpportunityLineItem', 'name' => 'OpportunityId', 'role' => 'key', 'why' => ['de' => 'Parent-Join', 'en' => 'Parent join']],
            ['entity' => 'OpportunityLineItem', 'name' => 'Quantity', 'role' => 'measure', 'why' => ['de' => 'Menge', 'en' => 'Quantity']],
            ['entity' => 'OpportunityLineItem', 'name' => 'TotalPrice', 'role' => 'measure', 'why' => ['de' => 'Zeilenumsatz', 'en' => 'Line revenue']],
            ['entity' => 'OpportunityLineItem', 'name' => 'Product2Id', 'role' => 'dimension', 'why' => ['de' => 'Produkt', 'en' => 'Product']],
            ['entity' => 'Account', 'name' => 'Id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Account', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Account-Label', 'en' => 'Account label']],
            ['entity' => 'Account', 'name' => 'Type', 'role' => 'dimension', 'why' => ['de' => 'Account-Typ', 'en' => 'Account type']],
            ['entity' => 'Account', 'name' => 'Industry', 'role' => 'dimension', 'why' => ['de' => 'Branche', 'en' => 'Industry']],
            ['entity' => 'Account', 'name' => 'BillingCountry', 'role' => 'dimension', 'why' => ['de' => 'Region', 'en' => 'Region']],
            ['entity' => 'Account', 'name' => 'ParentId', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
            ['entity' => 'Account', 'name' => 'OwnerId', 'role' => 'dimension', 'why' => ['de' => 'Account-Owner', 'en' => 'Account owner']],
            ['entity' => 'Contact', 'name' => 'Id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Contact', 'name' => 'AccountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
            ['entity' => 'Contact', 'name' => 'Email', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
            ['entity' => 'Contact', 'name' => 'Phone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contact', 'name' => 'MobilePhone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contact', 'name' => 'FirstName', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contact', 'name' => 'LastName', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contact', 'name' => 'Title', 'role' => 'dimension', 'why' => ['de' => 'Rolle', 'en' => 'Job title']],
            ['entity' => 'Lead', 'name' => 'Email', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
            ['entity' => 'Lead', 'name' => 'Status', 'role' => 'dimension', 'why' => ['de' => 'Lead-Status', 'en' => 'Lead status']],
            ['entity' => 'Lead', 'name' => 'IsConverted', 'role' => 'measure', 'why' => ['de' => 'Conversion', 'en' => 'Conversion']],
            ['entity' => 'User', 'name' => 'Id', 'role' => 'key', 'why' => ['de' => 'Owner-Join', 'en' => 'Owner join']],
            ['entity' => 'User', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Owner-Label', 'en' => 'Owner label']],
            ['entity' => 'User', 'name' => 'IsActive', 'role' => 'dimension', 'why' => ['de' => 'Aktive Owner', 'en' => 'Active owners']],
            ['entity' => 'User', 'name' => 'UserRoleId', 'role' => 'dimension', 'why' => ['de' => 'Team / Rolle', 'en' => 'Team / role']],
            ['entity' => 'Campaign', 'name' => 'Id', 'role' => 'key', 'why' => ['de' => 'Attribution-Join', 'en' => 'Attribution join']],
            ['entity' => 'Campaign', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Kampagnen-Label', 'en' => 'Campaign label']],
            ['entity' => 'Campaign', 'name' => 'Type', 'role' => 'dimension', 'why' => ['de' => 'Kampagnen-Typ', 'en' => 'Campaign type']],
            ['entity' => 'Case', 'name' => 'Id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Case', 'name' => 'Status', 'role' => 'dimension', 'why' => ['de' => 'Case-Status', 'en' => 'Case status']],
            ['entity' => 'Case', 'name' => 'Origin', 'role' => 'dimension', 'why' => ['de' => 'Kanal', 'en' => 'Channel']],
        ],
        'skipTables' => [
            [
                'name' => 'FeedItem / FeedComment (Chatter)',
                'category' => 'system',
                'reason' => [
                    'de' => 'Collaboration-Noise und unstrukturiertes PII — selten KPI-relevant.',
                    'en' => 'Collaboration noise and unstructured PII — rarely KPI-relevant.',
                ],
            ],
            [
                'name' => 'ContentVersion / Attachment (bodies)',
                'category' => 'system',
                'reason' => [
                    'de' => 'Binaries und Dateiinhalte — teuer, wenig Analytics-Nutzen im Warehouse.',
                    'en' => 'Binaries and file bodies — expensive, little warehouse analytics value.',
                ],
            ],
            [
                'name' => 'SetupAuditTrail',
                'category' => 'system',
                'reason' => [
                    'de' => 'Admin-/Setup-Audit — nicht für Business-KPIs.',
                    'en' => 'Admin/setup audit — not for business KPIs.',
                ],
            ],
            [
                'name' => 'LoginHistory',
                'category' => 'system',
                'reason' => [
                    'de' => 'Login-Telemetrie — Security-Thema, nicht CRM-Mart-Kern.',
                    'en' => 'Login telemetry — security topic, not CRM mart core.',
                ],
            ],
            [
                'name' => 'EmailMessage (ohne Bedarf)',
                'category' => 'system',
                'reason' => [
                    'de' => 'E-Mail-Bodies und Header — PII/Volumen; nur mit klarem Use Case.',
                    'en' => 'Email bodies and headers — PII/volume; only with a clear use case.',
                ],
            ],
            [
                'name' => '*History (vor SCD2-Bedarf)',
                'category' => 'system',
                'reason' => [
                    'de' => 'Field-History-Objekte erst laden, wenn Type-2 wirklich gebraucht wird.',
                    'en' => 'Load field-history objects only when Type-2 is truly needed.',
                ],
            ],
            [
                'name' => 'ApexLog / AsyncApexJob',
                'category' => 'system',
                'reason' => [
                    'de' => 'Plattform-Runtime-Logs — kein Analytics-Load.',
                    'en' => 'Platform runtime logs — not an analytics load.',
                ],
            ],
        ],
        'skip' => [
            ['name' => 'Attachment / ContentVersion bodies', 'reason' => ['de' => 'Kosten, wenig Analytics-Nutzen', 'en' => 'Cost, little analytics value']],
            ['name' => 'Chatter / Feed bodies', 'reason' => ['de' => 'Rauschen und PII', 'en' => 'Noise and PII']],
            ['name' => 'Unused custom fields (bulk sync all)', 'reason' => ['de' => 'Vergrößert DSDR-Fläche', 'en' => 'Expands DSDR surface']],
            ['name' => 'UI layout / page metadata', 'reason' => ['de' => 'Nicht analytisch', 'en' => 'Not analytical']],
        ],
        'dimensions' => $crmDimensions,
        'pii' => [
            [
                'entity' => 'Contact',
                'fields' => ['Email', 'Phone', 'MobilePhone', 'FirstName', 'LastName', 'MailingStreet'],
                'treatment' => [
                    'de' => 'Direkte Identifikatoren — PII taggen, RAW einschränken.',
                    'en' => 'Direct identifiers — tag PII, restrict RAW.',
                ],
            ],
            [
                'entity' => 'Lead',
                'fields' => ['Email', 'Phone', 'FirstName', 'LastName'],
                'treatment' => [
                    'de' => 'Wie Contact behandeln.',
                    'en' => 'Treat like Contact.',
                ],
            ],
            [
                'entity' => 'User',
                'fields' => ['Email', 'Name', 'Phone'],
                'treatment' => [
                    'de' => 'Workforce-PII — eigene Policy vs. Kunden-PII.',
                    'en' => 'Workforce PII — separate policy from customer PII.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => [
                    'de' => 'Email, Phone, Salesforce Id, External Id Custom Fields.',
                    'en' => 'Email, phone, Salesforce Id, external Id custom fields.',
                ],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => [
                    'de' => 'Contact, Lead, Person Account + Warehouse-Kopien.',
                    'en' => 'Contact, Lead, person Account + warehouse copies.',
                ],
            ],
        ],
        'measures' => $crmMeasures([
            'revenue-won' => [
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsWon', 'Opportunity.CloseDate'],
                'sourceHints' => [
                    'de' => 'Standard-Opportunity-Felder — CloseDate als Periodenfilter.',
                    'en' => 'Standard Opportunity fields — CloseDate as period filter.',
                ],
            ],
            'arr' => [
                'fieldsUsed' => ['Contract / custom ARR fields', 'OpportunityLineItem (if subscription)'],
                'sourceHints' => [
                    'de' => 'Kein Standard-ARR-Feld — Subscription/Contract-Modell prüfen.',
                    'en' => 'No standard ARR field — check subscription/contract model.',
                ],
            ],
            'pipeline-amount' => [
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsClosed'],
                'sourceHints' => ['de' => 'IsClosed = false', 'en' => 'IsClosed = false'],
            ],
            'win-rate' => [
                'fieldsUsed' => ['Opportunity.IsWon', 'Opportunity.IsClosed'],
                'sourceHints' => ['de' => 'Nur Closed im Nenner', 'en' => 'Closed only in denominator'],
            ],
            'avg-deal-size' => [
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsWon'],
                'sourceHints' => ['de' => 'Nur Won', 'en' => 'Won only'],
            ],
        ]),
        'tools' => $crmTools,
        'relatedPlaybooks' => $relatedPlaybooks,
    ],

    [
        'id' => 'hubspot',
        'domain' => 'crm',
        'order' => 20,
        'label' => ['de' => 'HubSpot', 'en' => 'HubSpot'],
        'shortPurpose' => [
            'de' => 'CRM + Marketing: Companies, Contacts, Deals — inkl. Formular-PII.',
            'en' => 'CRM + marketing: Companies, Contacts, Deals — including form PII.',
        ],
        'entities' => [
            [
                'id' => 'companies',
                'label' => ['de' => 'Companies', 'en' => 'Companies'],
                'description' => [
                    'de' => 'Organisationen — Account-artige Dimension.',
                    'en' => 'Organizations — account-style dimension.',
                ],
                'grain' => ['de' => 'Eine Organisation', 'en' => 'One organization'],
                'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                'load' => 'required',
            ],
            [
                'id' => 'contacts',
                'label' => ['de' => 'Contacts', 'en' => 'Contacts'],
                'description' => [
                    'de' => 'Personen — PII-Kern und DSDR-Einstieg.',
                    'en' => 'People — PII core and DSDR entry point.',
                ],
                'grain' => ['de' => 'Eine Person', 'en' => 'One person'],
                'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                'load' => 'required',
            ],
            [
                'id' => 'deals',
                'label' => ['de' => 'Deals', 'en' => 'Deals'],
                'description' => [
                    'de' => 'Kommerzielle Opportunities — Pipeline und Umsatz.',
                    'en' => 'Commercial opportunities — pipeline and revenue.',
                ],
                'grain' => ['de' => 'Ein Deal', 'en' => 'One deal'],
                'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                'load' => 'required',
            ],
            [
                'id' => 'line_items',
                'label' => ['de' => 'Line items', 'en' => 'Line items'],
                'description' => [
                    'de' => 'Produktzeilen am Deal — optional bei Produktmix.',
                    'en' => 'Product lines on a deal — optional for product mix.',
                ],
                'grain' => ['de' => 'Eine Produktzeile', 'en' => 'One product line'],
                'role' => ['de' => 'Fact (fein)', 'en' => 'Fact (fine)'],
                'load' => 'optional',
            ],
            [
                'id' => 'owners',
                'label' => ['de' => 'Owners', 'en' => 'Owners'],
                'description' => [
                    'de' => 'HubSpot-User für Owner-Dimension.',
                    'en' => 'HubSpot users for owner dimension.',
                ],
                'grain' => ['de' => 'Ein HubSpot-User', 'en' => 'One HubSpot user'],
                'role' => ['de' => 'Owner-Dimension', 'en' => 'Owner dimension'],
                'load' => 'required',
            ],
            [
                'id' => 'associations',
                'label' => ['de' => 'Associations', 'en' => 'Associations'],
                'description' => [
                    'de' => 'Join-Graph Contact↔Company↔Deal — ohne Associations brechen Marts.',
                    'en' => 'Join graph contact↔company↔deal — marts break without associations.',
                ],
                'grain' => ['de' => 'Eine Association', 'en' => 'One association'],
                'role' => ['de' => 'Join-Graph', 'en' => 'Join graph'],
                'load' => 'required',
            ],
            [
                'id' => 'tickets',
                'label' => ['de' => 'Tickets', 'en' => 'Tickets'],
                'description' => [
                    'de' => 'Service-/Support-Fälle — nur bei Service-Analytics.',
                    'en' => 'Service/support cases — only for service analytics.',
                ],
                'grain' => ['de' => 'Ein Ticket', 'en' => 'One ticket'],
                'role' => ['de' => 'Service-Fact', 'en' => 'Service fact'],
                'load' => 'optional',
            ],
            [
                'id' => 'forms',
                'label' => ['de' => 'Forms / submissions', 'en' => 'Forms / submissions'],
                'description' => [
                    'de' => 'Formular-Einreichungen — hohes PII; nur mit Bedarf laden.',
                    'en' => 'Form submissions — high PII; load only when needed.',
                ],
                'grain' => ['de' => 'Eine Einreichung', 'en' => 'One submission'],
                'role' => ['de' => 'Lead Capture (PII)', 'en' => 'Lead capture (PII)'],
                'load' => 'optional',
            ],
            [
                'id' => 'engagements',
                'label' => ['de' => 'Engagements', 'en' => 'Engagements'],
                'description' => [
                    'de' => 'E-Mails, Calls, Meetings — Volumen und PII; nicht pauschal syncen.',
                    'en' => 'Emails, calls, meetings — volume and PII; do not sync blindly.',
                ],
                'grain' => ['de' => 'Eine Interaktion', 'en' => 'One interaction'],
                'role' => ['de' => 'Engagement (optional)', 'en' => 'Engagement (optional)'],
                'load' => 'optional',
            ],
        ],
        'fields' => [
            ['entity' => 'Deals', 'name' => 'hs_object_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Deals', 'name' => 'dealname', 'role' => 'dimension', 'why' => ['de' => 'Deal-Label', 'en' => 'Deal label']],
            ['entity' => 'Deals', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Umsatz / Pipeline', 'en' => 'Revenue / pipeline']],
            ['entity' => 'Deals', 'name' => 'dealstage', 'role' => 'dimension', 'why' => ['de' => 'Stage', 'en' => 'Stage']],
            ['entity' => 'Deals', 'name' => 'pipeline', 'role' => 'dimension', 'why' => ['de' => 'Pipeline', 'en' => 'Pipeline']],
            ['entity' => 'Deals', 'name' => 'closedate', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
            ['entity' => 'Deals', 'name' => 'createdate', 'role' => 'key', 'why' => ['de' => 'Cycle Time', 'en' => 'Cycle time']],
            ['entity' => 'Deals', 'name' => 'hs_lastmodifieddate', 'role' => 'key', 'why' => ['de' => 'Incremental Sync', 'en' => 'Incremental sync']],
            ['entity' => 'Deals', 'name' => 'hubspot_owner_id', 'role' => 'dimension', 'why' => ['de' => 'Owner', 'en' => 'Owner']],
            ['entity' => 'Deals', 'name' => 'hs_is_closed_won', 'role' => 'measure', 'why' => ['de' => 'Won-Flag (Portal prüfen)', 'en' => 'Won flag (verify portal)']],
            ['entity' => 'Deals', 'name' => 'hs_is_closed', 'role' => 'measure', 'why' => ['de' => 'Closed-Filter', 'en' => 'Closed filter']],
            ['entity' => 'Deals', 'name' => 'hs_deal_stage_probability', 'role' => 'measure', 'why' => ['de' => 'Weighted Pipeline', 'en' => 'Weighted pipeline']],
            ['entity' => 'Deals', 'name' => 'deal_currency_code', 'role' => 'measure', 'why' => ['de' => 'Währung', 'en' => 'Currency']],
            ['entity' => 'Line items', 'name' => 'hs_object_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Line items', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Menge', 'en' => 'Quantity']],
            ['entity' => 'Line items', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Zeilenumsatz', 'en' => 'Line revenue']],
            ['entity' => 'Companies', 'name' => 'hs_object_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Companies', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Company-Label', 'en' => 'Company label']],
            ['entity' => 'Companies', 'name' => 'domain', 'role' => 'dimension', 'why' => ['de' => 'Domain-Match', 'en' => 'Domain match']],
            ['entity' => 'Companies', 'name' => 'country', 'role' => 'dimension', 'why' => ['de' => 'Region', 'en' => 'Region']],
            ['entity' => 'Companies', 'name' => 'industry', 'role' => 'dimension', 'why' => ['de' => 'Branche', 'en' => 'Industry']],
            ['entity' => 'Companies', 'name' => 'hubspot_owner_id', 'role' => 'dimension', 'why' => ['de' => 'Owner', 'en' => 'Owner']],
            ['entity' => 'Contacts', 'name' => 'hs_object_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
            ['entity' => 'Contacts', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
            ['entity' => 'Contacts', 'name' => 'phone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contacts', 'name' => 'mobilephone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contacts', 'name' => 'firstname', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contacts', 'name' => 'lastname', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
            ['entity' => 'Contacts', 'name' => 'lifecyclestage', 'role' => 'dimension', 'why' => ['de' => 'Funnel-Stufe', 'en' => 'Funnel stage']],
            ['entity' => 'Contacts', 'name' => 'associatedcompanyid', 'role' => 'dimension', 'why' => ['de' => 'Company-Join', 'en' => 'Company join']],
            ['entity' => 'Owners', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Owner-Join', 'en' => 'Owner join']],
            ['entity' => 'Owners', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Workforce-PII', 'en' => 'Workforce PII']],
            ['entity' => 'Owners', 'name' => 'firstName', 'role' => 'dimension', 'why' => ['de' => 'Owner-Label', 'en' => 'Owner label']],
            ['entity' => 'Associations', 'name' => 'from_object_id', 'role' => 'key', 'why' => ['de' => 'Join from', 'en' => 'Join from']],
            ['entity' => 'Associations', 'name' => 'to_object_id', 'role' => 'key', 'why' => ['de' => 'Join to', 'en' => 'Join to']],
            ['entity' => 'Associations', 'name' => 'association_type', 'role' => 'dimension', 'why' => ['de' => 'Beziehungstyp', 'en' => 'Relationship type']],
            ['entity' => 'Tickets', 'name' => 'hs_ticket_status', 'role' => 'dimension', 'why' => ['de' => 'Ticket-Status', 'en' => 'Ticket status']],
            ['entity' => 'Tickets', 'name' => 'hs_pipeline', 'role' => 'dimension', 'why' => ['de' => 'Pipeline', 'en' => 'Pipeline']],
        ],
        'skipTables' => [
            [
                'name' => 'Email HTML / marketing email bodies',
                'category' => 'system',
                'reason' => [
                    'de' => 'Speicher und PII — selten für Warehouse-KPIs nötig.',
                    'en' => 'Storage and PII — rarely needed for warehouse KPIs.',
                ],
            ],
            [
                'name' => 'Notes / engagement free-text dumps',
                'category' => 'system',
                'reason' => [
                    'de' => 'Unstrukturiertes PII und Rauschen.',
                    'en' => 'Unstructured PII and noise.',
                ],
            ],
            [
                'name' => 'All engagements “just in case”',
                'category' => 'system',
                'reason' => [
                    'de' => 'Volumen ohne Consumer — gezielt syncen.',
                    'en' => 'Volume without consumers — sync selectively.',
                ],
            ],
            [
                'name' => 'File / attachment binaries',
                'category' => 'system',
                'reason' => [
                    'de' => 'Warehouse-Anti-Pattern.',
                    'en' => 'Warehouse anti-pattern.',
                ],
            ],
            [
                'name' => 'Unused custom properties (bulk sync)',
                'category' => 'system',
                'reason' => [
                    'de' => 'Vergrößert DSDR- und Kostenfläche.',
                    'en' => 'Expands DSDR and cost surface.',
                ],
            ],
        ],
        'skip' => [
            ['name' => 'Full email HTML bodies', 'reason' => ['de' => 'Speicher und PII', 'en' => 'Storage and PII']],
            ['name' => 'Note / engagement free text dumps', 'reason' => ['de' => 'Unstrukturiertes PII', 'en' => 'Unstructured PII']],
            ['name' => 'Unused custom properties (bulk sync)', 'reason' => ['de' => 'DSDR- und Kostenfläche', 'en' => 'DSDR and cost surface']],
        ],
        'dimensions' => $crmDimensions,
        'pii' => [
            [
                'entity' => 'Contacts',
                'fields' => ['email', 'phone', 'mobilephone', 'firstname', 'lastname'],
                'treatment' => [
                    'de' => 'Direkte Identifikatoren — PII taggen.',
                    'en' => 'Direct identifiers — tag PII.',
                ],
            ],
            [
                'entity' => 'Form submissions',
                'fields' => ['(form field values)'],
                'treatment' => [
                    'de' => 'Bis zum Gegenbeweis als PII annehmen.',
                    'en' => 'Assume PII until proven otherwise.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => [
                    'de' => 'Email (primär), Phone, HubSpot Contact Id, External Ids.',
                    'en' => 'Email (primary), phone, HubSpot contact id, external ids.',
                ],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => [
                    'de' => 'Contacts, Form Submissions, Tickets + Warehouse-/Activation-Kopien.',
                    'en' => 'Contacts, form submissions, tickets + warehouse/activation copies.',
                ],
            ],
        ],
        'measures' => [
            [
                'id' => 'closed-won-amount',
                'example' => true,
                'label' => ['de' => 'Closed Won Amount', 'en' => 'Closed won amount'],
                'question' => [
                    'de' => 'Wie viel Deal-amount ist in der Periode closed-won?',
                    'en' => 'How much deal amount closed-won in the period?',
                ],
                'formula' => 'SUM(deals.amount) WHERE deals.hs_is_closed_won = true AND deals.closedate IN period',
                'grain' => ['de' => 'deal', 'en' => 'deal'],
                'dimensions' => ['owner', 'region', 'stage'],
                'fieldsUsed' => ['deals.amount', 'deals.hs_is_closed_won', 'deals.dealstage', 'deals.closedate', 'deals.hubspot_owner_id'],
                'sourceHints' => [
                    'de' => 'Portal: hs_is_closed_won und Pipeline-Stages prüfen — nicht jedes Portal nutzt die Property gleich.',
                    'en' => 'Portal: verify hs_is_closed_won and pipeline stages — not every portal uses the property the same way.',
                ],
                'adapt' => [
                    'de' => 'Multi-Currency (deal_currency_code) und Storno-Stages klären.',
                    'en' => 'Clarify multi-currency (deal_currency_code) and reverse stages.',
                ],
            ],
            [
                'id' => 'open-pipeline-amount',
                'example' => true,
                'label' => ['de' => 'Open Pipeline Amount', 'en' => 'Open pipeline amount'],
                'question' => [
                    'de' => 'Wie viel amount steckt in offenen Deal-Stages?',
                    'en' => 'How much amount sits in open deal stages?',
                ],
                'formula' => 'SUM(deals.amount) WHERE deals.hs_is_closed = false',
                'grain' => ['de' => 'deal', 'en' => 'deal'],
                'dimensions' => ['owner', 'stage', 'source'],
                'fieldsUsed' => ['deals.amount', 'deals.hs_is_closed', 'deals.dealstage', 'deals.pipeline'],
                'sourceHints' => [
                    'de' => 'Optional × hs_deal_stage_probability für Weighted Pipeline.',
                    'en' => 'Optionally × hs_deal_stage_probability for weighted pipeline.',
                ],
                'adapt' => [
                    'de' => 'Offene Stages je Pipeline listen; archived deals ausschließen.',
                    'en' => 'List open stages per pipeline; exclude archived deals.',
                ],
            ],
            [
                'id' => 'win-rate-deals',
                'example' => false,
                'label' => ['de' => 'Win Rate (Deals)', 'en' => 'Win rate (deals)'],
                'question' => [
                    'de' => 'Welcher Anteil closed Deals ist hs_is_closed_won?',
                    'en' => 'What share of closed deals is hs_is_closed_won?',
                ],
                'formula' => 'COUNT(hs_is_closed_won) / COUNT(hs_is_closed)',
                'grain' => ['de' => 'deal', 'en' => 'deal'],
                'dimensions' => ['owner', 'source'],
                'fieldsUsed' => ['deals.hs_is_closed_won', 'deals.hs_is_closed', 'deals.dealstage'],
                'sourceHints' => [
                    'de' => 'Closed-Lost Stages explizit halten.',
                    'en' => 'Keep closed-lost stages explicit.',
                ],
                'adapt' => [
                    'de' => 'Disqualified/Deleted Stages aus dem Nenner nehmen.',
                    'en' => 'Exclude disqualified/deleted stages from the denominator.',
                ],
            ],
            [
                'id' => 'avg-won-deal-amount',
                'example' => false,
                'label' => ['de' => 'Avg Won Deal Amount', 'en' => 'Avg won deal amount'],
                'question' => [
                    'de' => 'Was ist der typische amount gewonnener Deals?',
                    'en' => 'What is the typical amount of won deals?',
                ],
                'formula' => 'SUM(amount WHERE hs_is_closed_won) / COUNT(hs_is_closed_won)',
                'grain' => ['de' => 'deal', 'en' => 'deal'],
                'dimensions' => ['owner', 'region'],
                'fieldsUsed' => ['deals.amount', 'deals.hs_is_closed_won'],
                'sourceHints' => [
                    'de' => 'Nur closed-won; Währung normalisieren.',
                    'en' => 'Closed-won only; normalize currency.',
                ],
                'adapt' => [
                    'de' => 'Median vs. Mean festlegen.',
                    'en' => 'Lock median vs mean.',
                ],
            ],
            [
                'id' => 'mrr-custom',
                'example' => false,
                'label' => ['de' => 'MRR (Custom Property)', 'en' => 'MRR (custom property)'],
                'question' => [
                    'de' => 'Wie hoch ist monatlich wiederkehrender Umsatz aus Deal-/Line-Properties?',
                    'en' => 'What is monthly recurring revenue from deal/line properties?',
                ],
                'formula' => 'SUM(deals.hs_mrr OR custom_mrr_property) WHERE subscription_active',
                'grain' => ['de' => 'deal / line item', 'en' => 'deal / line item'],
                'dimensions' => ['owner', 'region'],
                'fieldsUsed' => ['deals.hs_mrr', 'line_items.quantity', 'line_items.price', 'custom subscription properties'],
                'sourceHints' => [
                    'de' => 'hs_mrr existiert nicht in jedem Portal — Custom Properties mappingen.',
                    'en' => 'hs_mrr does not exist in every portal — map custom properties.',
                ],
                'adapt' => [
                    'de' => 'Nicht deals.amount als MRR missbrauchen.',
                    'en' => 'Do not misuse deals.amount as MRR.',
                ],
            ],
        ],
        'tools' => $crmTools,
        'relatedPlaybooks' => $relatedPlaybooks,
    ],

    [
        'id' => 'ga4',
        'domain' => 'analytics',
        'order' => 30,
        'label' => ['de' => 'Google Analytics 4', 'en' => 'Google Analytics 4'],
        'shortPurpose' => [
            'de' => 'Event-Analytics: Sessions, Users, Conversions und Purchase Revenue.',
            'en' => 'Event analytics: sessions, users, conversions and purchase revenue.',
        ],
        'entities' => [
            [
                'id' => 'event',
                'label' => ['de' => 'Event', 'en' => 'Event'],
                'description' => [
                    'de' => 'Atomarer Hit mit event_name und Parametern — Kern-Fact.',
                    'en' => 'Atomic hit with event_name and parameters — core fact.',
                ],
                'grain' => ['de' => 'Ein Hit', 'en' => 'One hit'],
                'role' => ['de' => 'Atomarer Fact', 'en' => 'Atomic fact'],
                'load' => 'required',
            ],
            [
                'id' => 'session',
                'label' => ['de' => 'Session', 'en' => 'Session'],
                'description' => [
                    'de' => 'Abgeleiteter Besuch aus Session-Keys — Session-Measures.',
                    'en' => 'Derived visit from session keys — session measures.',
                ],
                'grain' => ['de' => 'Ein Besuch', 'en' => 'One visit'],
                'role' => ['de' => 'Session-Measures', 'en' => 'Session measures'],
                'load' => 'required',
            ],
            [
                'id' => 'user',
                'label' => ['de' => 'User', 'en' => 'User'],
                'description' => [
                    'de' => 'User-Grain über user_pseudo_id und/oder user_id — einmal festlegen.',
                    'en' => 'User grain via user_pseudo_id and/or user_id — lock once.',
                ],
                'grain' => ['de' => 'user_pseudo_id / user_id', 'en' => 'user_pseudo_id / user_id'],
                'role' => ['de' => 'User-Grain', 'en' => 'User grain'],
                'load' => 'required',
            ],
            [
                'id' => 'item',
                'label' => ['de' => 'Item (e-commerce)', 'en' => 'Item (e-commerce)'],
                'description' => [
                    'de' => 'Produktzeilen in E-Commerce-Events — bei Shop-Analytics laden.',
                    'en' => 'Product lines in e-commerce events — load for shop analytics.',
                ],
                'grain' => ['de' => 'Ein Item in einem Event', 'en' => 'One item in an event'],
                'role' => ['de' => 'Produkt-Fact', 'en' => 'Product fact'],
                'load' => 'optional',
            ],
        ],
        'fields' => [
            ['entity' => 'Event', 'name' => 'event_date', 'role' => 'key', 'why' => ['de' => 'Partition / Tag', 'en' => 'Partition / day']],
            ['entity' => 'Event', 'name' => 'event_name', 'role' => 'measure', 'why' => ['de' => 'Event-Allowlist', 'en' => 'Event allowlist']],
            ['entity' => 'Event', 'name' => 'event_timestamp', 'role' => 'key', 'why' => ['de' => 'Zeitachse', 'en' => 'Timeline']],
            ['entity' => 'Event', 'name' => 'ga_session_id', 'role' => 'measure', 'why' => ['de' => 'Session-Key', 'en' => 'Session key']],
            ['entity' => 'Event', 'name' => 'ga_session_number', 'role' => 'dimension', 'why' => ['de' => 'Session-Nr.', 'en' => 'Session number']],
            ['entity' => 'Event', 'name' => 'user_pseudo_id', 'role' => 'pii', 'why' => ['de' => 'User-Key (oft personenbezogen)', 'en' => 'User key (often personal)']],
            ['entity' => 'Event', 'name' => 'user_id', 'role' => 'pii', 'why' => ['de' => 'CRM-Link — steuern', 'en' => 'CRM link — govern']],
            ['entity' => 'Event', 'name' => 'traffic_source.source', 'role' => 'dimension', 'why' => ['de' => 'Acquisition', 'en' => 'Acquisition']],
            ['entity' => 'Event', 'name' => 'traffic_source.medium', 'role' => 'dimension', 'why' => ['de' => 'Acquisition', 'en' => 'Acquisition']],
            ['entity' => 'Event', 'name' => 'traffic_source.name', 'role' => 'dimension', 'why' => ['de' => 'Campaign', 'en' => 'Campaign']],
            ['entity' => 'Event', 'name' => 'device.category', 'role' => 'dimension', 'why' => ['de' => 'Device', 'en' => 'Device']],
            ['entity' => 'Event', 'name' => 'geo.country', 'role' => 'dimension', 'why' => ['de' => 'Region', 'en' => 'Region']],
            ['entity' => 'Event', 'name' => 'ecommerce.value', 'role' => 'measure', 'why' => ['de' => 'Purchase Revenue', 'en' => 'Purchase revenue']],
            ['entity' => 'Event', 'name' => 'ecommerce.transaction_id', 'role' => 'measure', 'why' => ['de' => 'AOV / Dedup', 'en' => 'AOV / dedup']],
            ['entity' => 'Event', 'name' => 'ecommerce.currency', 'role' => 'measure', 'why' => ['de' => 'Währung', 'en' => 'Currency']],
            ['entity' => 'Event', 'name' => 'event_params.key', 'role' => 'dimension', 'why' => ['de' => 'Param-Allowlist', 'en' => 'Param allowlist']],
            ['entity' => 'Item', 'name' => 'item_id', 'role' => 'dimension', 'why' => ['de' => 'Produkt-Id', 'en' => 'Product id']],
            ['entity' => 'Item', 'name' => 'item_name', 'role' => 'dimension', 'why' => ['de' => 'Produkt-Label', 'en' => 'Product label']],
            ['entity' => 'Item', 'name' => 'price', 'role' => 'measure', 'why' => ['de' => 'Item-Preis', 'en' => 'Item price']],
            ['entity' => 'Item', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Menge', 'en' => 'Quantity']],
        ],
        'skipTables' => [
            [
                'name' => 'Debug / test stream events',
                'category' => 'system',
                'reason' => [
                    'de' => 'Verschmutzt Prod-KPIs — aus Prod-Marts fernhalten.',
                    'en' => 'Pollutes prod KPIs — keep out of prod marts.',
                ],
            ],
            [
                'name' => 'Raw page_location with tokens/PII',
                'category' => 'system',
                'reason' => [
                    'de' => 'Secret- und PII-Leakage in Query-Strings.',
                    'en' => 'Secret and PII leakage in query strings.',
                ],
            ],
            [
                'name' => 'Unused custom events (no consumer)',
                'category' => 'system',
                'reason' => [
                    'de' => 'Kosten und Metrik-Chaos — Allowlist pflegen.',
                    'en' => 'Cost and metric chaos — maintain an allowlist.',
                ],
            ],
            [
                'name' => 'Full page_title dumps without need',
                'category' => 'system',
                'reason' => [
                    'de' => 'Rauschen; manchmal persönlicher Content.',
                    'en' => 'Noise; sometimes personal content.',
                ],
            ],
        ],
        'skip' => [
            ['name' => 'Raw page_location with tokens/PII', 'reason' => ['de' => 'Secret- und PII-Leakage', 'en' => 'Secret and PII leakage']],
            ['name' => 'Debug / test stream events in prod marts', 'reason' => ['de' => 'Verschmutzt KPIs', 'en' => 'Pollutes KPIs']],
            ['name' => 'Every custom event without consumer', 'reason' => ['de' => 'Kosten und Metrik-Chaos', 'en' => 'Cost and metric chaos']],
        ],
        'dimensions' => [
            [
                'id' => 'channel',
                'label' => ['de' => 'Channel / Source / Medium', 'en' => 'Channel / source / medium'],
                'grain' => ['de' => 'Traffic Source', 'en' => 'Traffic source'],
                'notes' => [
                    'de' => 'default_channel_group plus Raw Source/Medium.',
                    'en' => 'default_channel_group plus raw source/medium.',
                ],
            ],
            [
                'id' => 'device',
                'label' => ['de' => 'Device Category', 'en' => 'Device category'],
                'grain' => ['de' => 'desktop / mobile / tablet', 'en' => 'desktop / mobile / tablet'],
                'notes' => ['de' => '', 'en' => ''],
            ],
            [
                'id' => 'country',
                'label' => ['de' => 'Country', 'en' => 'Country'],
                'grain' => ['de' => 'Geo', 'en' => 'Geo'],
                'notes' => [
                    'de' => 'Consent- und Residenz-Filter beachten.',
                    'en' => 'Respect consent and residency filters.',
                ],
            ],
            [
                'id' => 'event_name',
                'label' => ['de' => 'Event Name', 'en' => 'Event name'],
                'grain' => ['de' => 'Event', 'en' => 'Event'],
                'notes' => [
                    'de' => 'Nur Allowlist-Events in Prod-Marts.',
                    'en' => 'Allowlisted events only in prod marts.',
                ],
            ],
        ],
        'pii' => [
            [
                'entity' => 'Event params / URLs',
                'fields' => ['email', 'phone', 'page_location query tokens'],
                'treatment' => [
                    'de' => 'Im Tagging blocken; in RAW quarantänen falls gefunden.',
                    'en' => 'Block in tagging; quarantine in RAW if found.',
                ],
            ],
            [
                'entity' => 'User',
                'fields' => ['user_id', 'user_pseudo_id'],
                'treatment' => [
                    'de' => 'Als personenbezogen steuern, wenn joinbar oder kombiniert.',
                    'en' => 'Govern as personal when joinable or combined.',
                ],
            ],
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => [
                    'de' => 'user_id, CRM-Id in Params, user_pseudo_id-Cluster.',
                    'en' => 'user_id, CRM id in params, user_pseudo_id clusters.',
                ],
            ],
            [
                'focus' => ['de' => 'Policy', 'en' => 'Policy'],
                'notes' => [
                    'de' => 'Event-Historie vs. Aggregate — Löschung ist selten eine Zeile.',
                    'en' => 'Event history vs aggregates — deletion is rarely one row.',
                ],
            ],
        ],
        'measures' => [
            [
                'id' => 'sessions',
                'example' => true,
                'label' => ['de' => 'Sessions', 'en' => 'Sessions'],
                'question' => [
                    'de' => 'Wie viele Besuche gab es?',
                    'en' => 'How many visits occurred?',
                ],
                'formula' => 'COUNT DISTINCT (user_key, ga_session_id)',
                'grain' => ['de' => 'Session', 'en' => 'Session'],
                'dimensions' => ['channel', 'device', 'country'],
                'fieldsUsed' => ['ga_session_id', 'user_pseudo_id / user_id'],
                'sourceHints' => [
                    'de' => 'User-Key einmal festlegen und nicht mischen.',
                    'en' => 'Lock one user key and do not mix.',
                ],
                'adapt' => [
                    'de' => 'Zeitzone der GA4-Property übernehmen.',
                    'en' => 'Use the GA4 property timezone.',
                ],
            ],
            [
                'id' => 'purchase-revenue',
                'example' => true,
                'label' => ['de' => 'Purchase Revenue', 'en' => 'Purchase revenue'],
                'question' => [
                    'de' => 'Wie viel Purchase-Wert wurde erfasst?',
                    'en' => 'How much purchase value was recorded?',
                ],
                'formula' => "SUM(value) WHERE event_name = 'purchase'",
                'grain' => ['de' => 'Event / Transaction', 'en' => 'Event / transaction'],
                'dimensions' => ['channel', 'country'],
                'fieldsUsed' => ['ecommerce.value', 'ecommerce.transaction_id', 'event_name'],
                'sourceHints' => [
                    'de' => 'transaction_id für Dedup nutzen.',
                    'en' => 'Use transaction_id for dedup.',
                ],
                'adapt' => [
                    'de' => 'Währung, Steuer, Refunds und Test-Orders klären.',
                    'en' => 'Clarify currency, tax, refunds and test orders.',
                ],
            ],
            [
                'id' => 'conversion-rate-session',
                'example' => false,
                'label' => ['de' => 'Conversion Rate (Session)', 'en' => 'Conversion rate (session)'],
                'question' => [
                    'de' => 'Welcher Anteil der Sessions konvertierte?',
                    'en' => 'What share of sessions converted?',
                ],
                'formula' => 'sessions_with_key_event / sessions',
                'grain' => ['de' => 'Session', 'en' => 'Session'],
                'dimensions' => ['channel', 'device'],
                'fieldsUsed' => ['ga_session_id', 'key event names'],
                'sourceHints' => [
                    'de' => 'Key Events / Conversions listen.',
                    'en' => 'List key events / conversions.',
                ],
                'adapt' => [
                    'de' => 'Ein oder mehrere Conversions pro Session festlegen.',
                    'en' => 'Decide one vs many conversions per session.',
                ],
            ],
            [
                'id' => 'users',
                'example' => false,
                'label' => ['de' => 'Users', 'en' => 'Users'],
                'question' => [
                    'de' => 'Wie viele distinct Users?',
                    'en' => 'How many distinct users?',
                ],
                'formula' => 'COUNT DISTINCT user_key',
                'grain' => ['de' => 'User', 'en' => 'User'],
                'dimensions' => ['country', 'device'],
                'fieldsUsed' => ['user_pseudo_id / user_id'],
                'sourceHints' => [
                    'de' => 'Nie beide Keys in einer KPI mischen ohne Label.',
                    'en' => 'Never mix both keys in one KPI without labeling.',
                ],
                'adapt' => [
                    'de' => 'GA4-UI kann vom Warehouse abweichen.',
                    'en' => 'GA4 UI can differ from the warehouse.',
                ],
            ],
        ],
        'tools' => [
            'kpi-definition',
            'pii-recommend-generator',
            'pii-policy-generator',
            'powerbi-dax-generator',
            'qlik-set-analysis-generator',
            'tableau-calculation-generator',
        ],
        'relatedPlaybooks' => $relatedPlaybooks,
    ],
], (require __DIR__.'/suppliers-catalog-wave1.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave2.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave3.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave4.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave5.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave6.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave7.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave8.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave9.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave10.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave11.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
), (require __DIR__.'/suppliers-catalog-wave12.php')(
    $crmDimensions,
    $crmTools,
    $relatedPlaybooks,
    $crmMeasures,
));

$governance = array_merge(
    require __DIR__.'/suppliers-governance.php',
    require __DIR__.'/suppliers-governance-wave2.php',
    require __DIR__.'/suppliers-governance-wave3.php',
    require __DIR__.'/suppliers-governance-wave4.php',
    require __DIR__.'/suppliers-governance-wave5.php',
    require __DIR__.'/suppliers-governance-wave6.php',
    require __DIR__.'/suppliers-governance-wave7.php',
    require __DIR__.'/suppliers-governance-wave8.php',
    require __DIR__.'/suppliers-governance-wave9.php',
    require __DIR__.'/suppliers-governance-wave10.php',
    require __DIR__.'/suppliers-governance-wave11.php',
    require __DIR__.'/suppliers-governance-wave12.php',
);
$quality = array_merge(
    require __DIR__.'/suppliers-quality.php',
    require __DIR__.'/suppliers-quality-wave2.php',
    require __DIR__.'/suppliers-quality-wave3.php',
    require __DIR__.'/suppliers-quality-wave4.php',
    require __DIR__.'/suppliers-quality-wave5.php',
    require __DIR__.'/suppliers-quality-wave6.php',
    require __DIR__.'/suppliers-quality-wave7.php',
    require __DIR__.'/suppliers-quality-wave8.php',
    require __DIR__.'/suppliers-quality-wave9.php',
    require __DIR__.'/suppliers-quality-wave10.php',
    require __DIR__.'/suppliers-quality-wave11.php',
    require __DIR__.'/suppliers-quality-wave12.php',
);
$sql = array_merge(
    require __DIR__.'/suppliers-sql.php',
    require __DIR__.'/suppliers-sql-wave2.php',
    require __DIR__.'/suppliers-sql-wave3.php',
    require __DIR__.'/suppliers-sql-wave4.php',
    require __DIR__.'/suppliers-sql-wave5.php',
    require __DIR__.'/suppliers-sql-wave6.php',
    require __DIR__.'/suppliers-sql-wave7.php',
    require __DIR__.'/suppliers-sql-wave8.php',
    require __DIR__.'/suppliers-sql-wave9.php',
    require __DIR__.'/suppliers-sql-wave10.php',
    require __DIR__.'/suppliers-sql-wave11.php',
    require __DIR__.'/suppliers-sql-wave12.php',
);

return array_map(static function (array $product) use ($governance, $quality, $sql): array {
    $id = (string) ($product['id'] ?? '');
    if ($id === '') {
        return $product;
    }

    if (isset($governance[$id]) && is_array($governance[$id])) {
        $product = array_merge($product, $governance[$id]);
    }

    if (isset($quality[$id]) && is_array($quality[$id])) {
        $overlay = $quality[$id];
        $appendTools = is_array($overlay['tools'] ?? null) ? $overlay['tools'] : [];
        unset($overlay['tools']);
        $product = array_merge($product, $overlay);

        $tools = is_array($product['tools'] ?? null) ? $product['tools'] : [];
        foreach ($appendTools as $toolId) {
            if (! is_string($toolId) || $toolId === '') {
                continue;
            }
            if (! in_array($toolId, $tools, true)) {
                $tools[] = $toolId;
            }
        }
        $product['tools'] = $tools;
    }

    if (isset($sql[$id]) && is_array($sql[$id])) {
        $product = array_merge($product, $sql[$id]);
    }

    return $product;
}, $products);
