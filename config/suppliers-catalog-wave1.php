<?php

/**
 * Wave 1 supplier library entries (full template depth).
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $serviceTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    $commerceTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'powerbi-dax-generator',
        'qlik-set-analysis-generator',
        'tableau-calculation-generator',
    ];

    return [
        [
            'id' => 'dynamics365',
            'domain' => 'crm',
            'order' => 30,
            'label' => ['de' => 'Microsoft Dynamics 365', 'en' => 'Microsoft Dynamics 365'],
            'shortPurpose' => [
                'de' => 'Dataverse Sales: account, contact, opportunity — Logical Names, statecode und PII für Warehouse-Loads.',
                'en' => 'Dataverse Sales: account, contact, opportunity — logical names, statecode and PII for warehouse loads.',
            ],
            'entities' => [
                [
                    'id' => 'account',
                    'label' => ['de' => 'account', 'en' => 'account'],
                    'description' => [
                        'de' => 'Dataverse-Firma (account) — Kundenstamm und Hierarchie über parentaccountid.',
                        'en' => 'Dataverse company (account) — customer master and hierarchy via parentaccountid.',
                    ],
                    'grain' => ['de' => 'Ein account-Datensatz', 'en' => 'One account record'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'contact',
                    'label' => ['de' => 'contact', 'en' => 'contact'],
                    'description' => [
                        'de' => 'Dataverse-Person (contact) — PII über emailaddress1/telephone1, Join über parentcustomerid.',
                        'en' => 'Dataverse person (contact) — PII via emailaddress1/telephone1, join via parentcustomerid.',
                    ],
                    'grain' => ['de' => 'Ein contact', 'en' => 'One contact'],
                    'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'lead',
                    'label' => ['de' => 'lead', 'en' => 'lead'],
                    'description' => [
                        'de' => 'Dataverse-Lead vor Qualifizierung — statuscode/statecode für Funnel.',
                        'en' => 'Dataverse lead before qualification — statuscode/statecode for funnel.',
                    ],
                    'grain' => ['de' => 'Ein lead', 'en' => 'One lead'],
                    'role' => ['de' => 'Funnel / PII', 'en' => 'Funnel / PII'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'opportunity',
                    'label' => ['de' => 'opportunity', 'en' => 'opportunity'],
                    'description' => [
                        'de' => 'Dataverse-Opportunity — actualvalue/estimatedvalue, Won über statecode=1.',
                        'en' => 'Dataverse opportunity — actualvalue/estimatedvalue, won via statecode=1.',
                    ],
                    'grain' => ['de' => 'Eine opportunity', 'en' => 'One opportunity'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'opportunityproduct',
                    'label' => ['de' => 'opportunityproduct', 'en' => 'opportunityproduct'],
                    'description' => [
                        'de' => 'Produktzeilen (opportunityproduct) — extendedamount/quantity für Produktmix.',
                        'en' => 'Product lines (opportunityproduct) — extendedamount/quantity for product mix.',
                    ],
                    'grain' => ['de' => 'Eine opportunityproduct-Zeile', 'en' => 'One opportunityproduct row'],
                    'role' => ['de' => 'Fact (fein)', 'en' => 'Fact (fine)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'systemuser',
                    'label' => ['de' => 'systemuser', 'en' => 'systemuser'],
                    'description' => [
                        'de' => 'Dataverse-User (systemuser) für ownerid → fullname / businessunitid.',
                        'en' => 'Dataverse user (systemuser) for ownerid → fullname / businessunitid.',
                    ],
                    'grain' => ['de' => 'Ein systemuser', 'en' => 'One systemuser'],
                    'role' => ['de' => 'Owner-Dimension', 'en' => 'Owner dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'campaign',
                    'label' => ['de' => 'campaign', 'en' => 'campaign'],
                    'description' => [
                        'de' => 'Marketing-Kampagne in Dataverse — Attribution optional.',
                        'en' => 'Marketing campaign in Dataverse — attribution optional.',
                    ],
                    'grain' => ['de' => 'Eine campaign', 'en' => 'One campaign'],
                    'role' => ['de' => 'Attribution', 'en' => 'Attribution'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'incident',
                    'label' => ['de' => 'incident', 'en' => 'incident'],
                    'description' => [
                        'de' => 'Case/Incident in Customer Service — nur bei Service-Analytics.',
                        'en' => 'Case/incident in Customer Service — only for service analytics.',
                    ],
                    'grain' => ['de' => 'Ein incident', 'en' => 'One incident'],
                    'role' => ['de' => 'Service-Fact', 'en' => 'Service fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'task',
                    'label' => ['de' => 'task', 'en' => 'task'],
                    'description' => [
                        'de' => 'Aktivität (task) — oft laut; nur mit klarem Konsumenten laden.',
                        'en' => 'Activity (task) — often noisy; load only with a clear consumer.',
                    ],
                    'grain' => ['de' => 'Eine task', 'en' => 'One task'],
                    'role' => ['de' => 'Engagement (optional)', 'en' => 'Engagement (optional)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Opportunity', 'name' => 'opportunityid', 'role' => 'key', 'why' => ['de' => 'Join / Lineage', 'en' => 'Join / lineage']],
                ['entity' => 'Opportunity', 'name' => 'actualvalue', 'role' => 'measure', 'why' => ['de' => 'Umsatz / Pipeline', 'en' => 'Revenue / pipeline']],
                ['entity' => 'Opportunity', 'name' => 'estimatedvalue', 'role' => 'measure', 'why' => ['de' => 'Pipeline-Schätzung', 'en' => 'Pipeline estimate']],
                ['entity' => 'Opportunity', 'name' => 'statecode', 'role' => 'measure', 'why' => ['de' => 'Open / Won / Lost', 'en' => 'Open / won / lost']],
                ['entity' => 'Opportunity', 'name' => 'statuscode', 'role' => 'dimension', 'why' => ['de' => 'Feinere Stage-Logik', 'en' => 'Finer stage logic']],
                ['entity' => 'Opportunity', 'name' => 'actualclosedate', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
                ['entity' => 'Opportunity', 'name' => 'closeprobability', 'role' => 'measure', 'why' => ['de' => 'Weighted Pipeline', 'en' => 'Weighted pipeline']],
                ['entity' => 'Opportunity', 'name' => 'stepname', 'role' => 'dimension', 'why' => ['de' => 'Stage-Dimension', 'en' => 'Stage dimension']],
                ['entity' => 'Opportunity', 'name' => 'ownerid', 'role' => 'dimension', 'why' => ['de' => 'Owner', 'en' => 'Owner']],
                ['entity' => 'Opportunity', 'name' => 'customerid', 'role' => 'dimension', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Opportunity', 'name' => 'createdon', 'role' => 'key', 'why' => ['de' => 'Cycle Time / CDC', 'en' => 'Cycle time / CDC']],
                ['entity' => 'Opportunity', 'name' => 'modifiedon', 'role' => 'key', 'why' => ['de' => 'Incremental Sync', 'en' => 'Incremental sync']],
                ['entity' => 'Opportunity', 'name' => 'transactioncurrencyid', 'role' => 'measure', 'why' => ['de' => 'Multi-Currency', 'en' => 'Multi-currency']],
                ['entity' => 'Opportunity', 'name' => 'leadsourcecode', 'role' => 'dimension', 'why' => ['de' => 'Channel', 'en' => 'Channel']],
                ['entity' => 'OpportunityProduct', 'name' => 'opportunityid', 'role' => 'key', 'why' => ['de' => 'Parent-Join', 'en' => 'Parent join']],
                ['entity' => 'OpportunityProduct', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Menge', 'en' => 'Quantity']],
                ['entity' => 'OpportunityProduct', 'name' => 'extendedamount', 'role' => 'measure', 'why' => ['de' => 'Zeilenumsatz', 'en' => 'Line revenue']],
                ['entity' => 'OpportunityProduct', 'name' => 'productid', 'role' => 'dimension', 'why' => ['de' => 'Produkt', 'en' => 'Product']],
                ['entity' => 'Account', 'name' => 'accountid', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Account', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Account-Label', 'en' => 'Account label']],
                ['entity' => 'Account', 'name' => 'industrycode', 'role' => 'dimension', 'why' => ['de' => 'Branche', 'en' => 'Industry']],
                ['entity' => 'Account', 'name' => 'address1_country', 'role' => 'dimension', 'why' => ['de' => 'Region', 'en' => 'Region']],
                ['entity' => 'Account', 'name' => 'parentaccountid', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
                ['entity' => 'Account', 'name' => 'ownerid', 'role' => 'dimension', 'why' => ['de' => 'Account-Owner', 'en' => 'Account owner']],
                ['entity' => 'Contact', 'name' => 'contactid', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Contact', 'name' => 'parentcustomerid', 'role' => 'dimension', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Contact', 'name' => 'emailaddress1', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
                ['entity' => 'Contact', 'name' => 'telephone1', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Contact', 'name' => 'mobilephone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Contact', 'name' => 'firstname', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Contact', 'name' => 'lastname', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Contact', 'name' => 'jobtitle', 'role' => 'dimension', 'why' => ['de' => 'Rolle', 'en' => 'Job title']],
                ['entity' => 'Lead', 'name' => 'emailaddress1', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
                ['entity' => 'Lead', 'name' => 'statuscode', 'role' => 'dimension', 'why' => ['de' => 'Lead-Status', 'en' => 'Lead status']],
                ['entity' => 'Lead', 'name' => 'statecode', 'role' => 'measure', 'why' => ['de' => 'Conversion', 'en' => 'Conversion']],
                ['entity' => 'SystemUser', 'name' => 'systemuserid', 'role' => 'key', 'why' => ['de' => 'Owner-Join', 'en' => 'Owner join']],
                ['entity' => 'SystemUser', 'name' => 'fullname', 'role' => 'dimension', 'why' => ['de' => 'Owner-Label', 'en' => 'Owner label']],
                ['entity' => 'SystemUser', 'name' => 'isdisabled', 'role' => 'dimension', 'why' => ['de' => 'Aktive Owner', 'en' => 'Active owners']],
                ['entity' => 'SystemUser', 'name' => 'businessunitid', 'role' => 'dimension', 'why' => ['de' => 'Team / BU', 'en' => 'Team / BU']],
                ['entity' => 'Campaign', 'name' => 'campaignid', 'role' => 'key', 'why' => ['de' => 'Attribution-Join', 'en' => 'Attribution join']],
                ['entity' => 'Campaign', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Kampagnen-Label', 'en' => 'Campaign label']],
                ['entity' => 'Campaign', 'name' => 'typecode', 'role' => 'dimension', 'why' => ['de' => 'Kampagnen-Typ', 'en' => 'Campaign type']],
                ['entity' => 'Incident', 'name' => 'incidentid', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Incident', 'name' => 'statuscode', 'role' => 'dimension', 'why' => ['de' => 'Case-Status', 'en' => 'Case status']],
                ['entity' => 'Incident', 'name' => 'caseorigincode', 'role' => 'dimension', 'why' => ['de' => 'Kanal', 'en' => 'Channel']],
            ],
            'skipTables' => [
                [
                    'name' => 'Annotation / Note (bodies)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Notiz-Text und Anhänge — unstrukturiertes PII und Rauschen.',
                        'en' => 'Note text and attachments — unstructured PII and noise.',
                    ],
                ],
                [
                    'name' => 'Email / ActivityPointer bodies',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'E-Mail-Bodies und Header — PII/Volumen; nur mit klarem Use Case.',
                        'en' => 'Email bodies and headers — PII/volume; only with a clear use case.',
                    ],
                ],
                [
                    'name' => 'Audit / PluginTraceLog',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Plattform-Audit und Debug — nicht für Business-KPIs.',
                        'en' => 'Platform audit and debug — not for business KPIs.',
                    ],
                ],
                [
                    'name' => 'Workflow / AsyncOperation logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Runtime-Logs — kein Analytics-Load.',
                        'en' => 'Runtime logs — not an analytics load.',
                    ],
                ],
                [
                    'name' => 'Attachment / Document bodies',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Binaries und Dateiinhalte — teuer, wenig Analytics-Nutzen.',
                        'en' => 'Binaries and file bodies — expensive, little analytics value.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Annotation / attachment bodies', 'reason' => ['de' => 'Kosten, wenig Analytics-Nutzen', 'en' => 'Cost, little analytics value']],
                ['name' => 'Email / activity bodies', 'reason' => ['de' => 'Rauschen und PII', 'en' => 'Noise and PII']],
                ['name' => 'Unused custom attributes (bulk sync all)', 'reason' => ['de' => 'Vergrößert DSDR-Fläche', 'en' => 'Expands DSDR surface']],
                ['name' => 'Solution / metadata tables', 'reason' => ['de' => 'Nicht analytisch', 'en' => 'Not analytical']],
            ],
            'dimensions' => $crmDimensions,
            'pii' => [
                [
                    'entity' => 'Contact',
                    'fields' => ['emailaddress1', 'telephone1', 'mobilephone', 'firstname', 'lastname', 'address1_line1'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — PII taggen, RAW einschränken.',
                        'en' => 'Direct identifiers — tag PII, restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Lead',
                    'fields' => ['emailaddress1', 'telephone1', 'firstname', 'lastname'],
                    'treatment' => [
                        'de' => 'Wie Contact behandeln.',
                        'en' => 'Treat like Contact.',
                    ],
                ],
                [
                    'entity' => 'SystemUser',
                    'fields' => ['internalemailaddress', 'fullname', 'mobilephone'],
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
                        'de' => 'Email, Phone, Dynamics Id, External Id Custom Fields.',
                        'en' => 'Email, phone, Dynamics id, external id custom fields.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Contact, Lead, Account + Warehouse-Kopien.',
                        'en' => 'Contact, Lead, Account + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'won-actualvalue',
                    'example' => true,
                    'label' => ['de' => 'Won Actual Value', 'en' => 'Won actual value'],
                    'question' => [
                        'de' => 'Wie hoch ist actualvalue der Opportunities mit statecode = Won in der Periode?',
                        'en' => 'What is actualvalue of opportunities with statecode = Won in the period?',
                    ],
                    'formula' => 'SUM(opportunity.actualvalue) WHERE opportunity.statecode = 1 AND opportunity.actualclosedate IN period',
                    'grain' => ['de' => 'opportunity', 'en' => 'opportunity'],
                    'dimensions' => ['owner', 'region', 'stage'],
                    'fieldsUsed' => ['opportunity.actualvalue', 'opportunity.statecode', 'opportunity.actualclosedate', 'opportunity.transactioncurrencyid'],
                    'sourceHints' => [
                        'de' => 'Dataverse: statecode 0=Open, 1=Won, 2=Lost. Währung über transactioncurrencyid.',
                        'en' => 'Dataverse: statecode 0=Open, 1=Won, 2=Lost. Currency via transactioncurrencyid.',
                    ],
                    'adapt' => [
                        'de' => 'statuscode für firmenspezifische Won-Gründe; Multi-Currency-Reporting festlegen.',
                        'en' => 'Use statuscode for firm-specific won reasons; lock multi-currency reporting.',
                    ],
                ],
                [
                    'id' => 'open-estimatedvalue',
                    'example' => true,
                    'label' => ['de' => 'Open Estimated Value', 'en' => 'Open estimated value'],
                    'question' => [
                        'de' => 'Wie viel estimatedvalue steckt in offenen Opportunities?',
                        'en' => 'How much estimatedvalue sits in open opportunities?',
                    ],
                    'formula' => 'SUM(opportunity.estimatedvalue) WHERE opportunity.statecode = 0',
                    'grain' => ['de' => 'opportunity', 'en' => 'opportunity'],
                    'dimensions' => ['owner', 'stage', 'source'],
                    'fieldsUsed' => ['opportunity.estimatedvalue', 'opportunity.statecode', 'opportunity.stepname', 'opportunity.closeprobability'],
                    'sourceHints' => [
                        'de' => 'Optional × closeprobability für Weighted Pipeline.',
                        'en' => 'Optionally × closeprobability for weighted pipeline.',
                    ],
                    'adapt' => [
                        'de' => 'estimatedvalue vs. actualvalue-Policy je Stage klären.',
                        'en' => 'Clarify estimatedvalue vs actualvalue policy by stage.',
                    ],
                ],
                [
                    'id' => 'win-rate-statecode',
                    'example' => false,
                    'label' => ['de' => 'Win Rate (statecode)', 'en' => 'Win rate (statecode)'],
                    'question' => [
                        'de' => 'Welcher Anteil geschlossener Opportunities ist Won (statecode=1)?',
                        'en' => 'What share of closed opportunities is Won (statecode=1)?',
                    ],
                    'formula' => 'COUNT(statecode=1) / COUNT(statecode IN (1,2))',
                    'grain' => ['de' => 'opportunity', 'en' => 'opportunity'],
                    'dimensions' => ['owner', 'source'],
                    'fieldsUsed' => ['opportunity.statecode', 'opportunity.statuscode', 'opportunity.actualclosedate'],
                    'sourceHints' => [
                        'de' => 'Nenner nur Closed (Won+Lost), nicht Open.',
                        'en' => 'Denominator closed only (won+lost), not open.',
                    ],
                    'adapt' => [
                        'de' => 'statuscode-Werte die „abgebrochen“ bedeuten aus dem Nenner nehmen.',
                        'en' => 'Exclude statuscode values that mean cancelled from the denominator.',
                    ],
                ],
                [
                    'id' => 'avg-won-actualvalue',
                    'example' => false,
                    'label' => ['de' => 'Avg Won Actual Value', 'en' => 'Avg won actual value'],
                    'question' => [
                        'de' => 'Was ist der typische actualvalue gewonnener Opportunities?',
                        'en' => 'What is the typical actualvalue of won opportunities?',
                    ],
                    'formula' => 'SUM(actualvalue WHERE statecode=1) / COUNT(statecode=1)',
                    'grain' => ['de' => 'opportunity', 'en' => 'opportunity'],
                    'dimensions' => ['owner', 'region'],
                    'fieldsUsed' => ['opportunity.actualvalue', 'opportunity.statecode'],
                    'sourceHints' => [
                        'de' => 'Nur statecode=1; Währung normalisieren.',
                        'en' => 'statecode=1 only; normalize currency.',
                    ],
                    'adapt' => [
                        'de' => 'Median vs. Mean und Base Currency festlegen.',
                        'en' => 'Lock median vs mean and base currency.',
                    ],
                ],
                [
                    'id' => 'line-extendedamount',
                    'example' => false,
                    'label' => ['de' => 'Line Extended Amount', 'en' => 'Line extended amount'],
                    'question' => [
                        'de' => 'Wie verteilt sich extendedamount auf Produkte in Won-Opportunities?',
                        'en' => 'How does extendedamount distribute across products on won opportunities?',
                    ],
                    'formula' => 'SUM(opportunityproduct.extendedamount) WHERE parent opportunity.statecode = 1',
                    'grain' => ['de' => 'opportunityproduct', 'en' => 'opportunityproduct'],
                    'dimensions' => ['owner', 'region'],
                    'fieldsUsed' => ['opportunityproduct.extendedamount', 'opportunityproduct.quantity', 'opportunityproduct.productid', 'opportunity.statecode'],
                    'sourceHints' => [
                        'de' => 'Nur wenn opportunityproduct geladen wird.',
                        'en' => 'Only when opportunityproduct is loaded.',
                    ],
                    'adapt' => [
                        'de' => 'Produktkatalog-/Price-List-Grain mit dem Fachbereich klären.',
                        'en' => 'Align product catalog / price list grain with the business.',
                    ],
                ],
            ],
            'tools' => $crmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'servicenow',
            'domain' => 'service',
            'order' => 40,
            'label' => ['de' => 'ServiceNow', 'en' => 'ServiceNow'],
            'shortPurpose' => [
                'de' => 'ITSM-Kern: Incidents, Changes, CMDB — Load, PII und Service-Measures.',
                'en' => 'ITSM core: Incidents, changes, CMDB — load, PII and service measures.',
            ],
            'entities' => [
                [
                    'id' => 'incident',
                    'label' => ['de' => 'Incident', 'en' => 'Incident'],
                    'description' => [
                        'de' => 'Störungs- und Service-Tickets — zentraler Fact für ITSM-KPIs.',
                        'en' => 'Disruption and service tickets — core fact for ITSM KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Incident', 'en' => 'One incident'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'problem',
                    'label' => ['de' => 'Problem', 'en' => 'Problem'],
                    'description' => [
                        'de' => 'Root-Cause-Records — für Problem-Management und MTTR-Kontext.',
                        'en' => 'Root-cause records — for problem management and MTTR context.',
                    ],
                    'grain' => ['de' => 'Ein Problem', 'en' => 'One problem'],
                    'role' => ['de' => 'Fact (Problem)', 'en' => 'Fact (problem)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'change_request',
                    'label' => ['de' => 'Change Request', 'en' => 'Change request'],
                    'description' => [
                        'de' => 'Change-Management — Change-Volumen und Erfolgsrate.',
                        'en' => 'Change management — change volume and success rate.',
                    ],
                    'grain' => ['de' => 'Ein Change', 'en' => 'One change'],
                    'role' => ['de' => 'Fact (Change)', 'en' => 'Fact (change)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'cmdb_ci',
                    'label' => ['de' => 'CMDB CI', 'en' => 'CMDB CI'],
                    'description' => [
                        'de' => 'Configuration Items — Asset-Dimension für Incident-/Change-Joins.',
                        'en' => 'Configuration items — asset dimension for incident/change joins.',
                    ],
                    'grain' => ['de' => 'Ein CI', 'en' => 'One CI'],
                    'role' => ['de' => 'Asset-Dimension', 'en' => 'Asset dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'sys_user',
                    'label' => ['de' => 'Sys User', 'en' => 'Sys user'],
                    'description' => [
                        'de' => 'ServiceNow-User für Assignee-/Caller-Dimension.',
                        'en' => 'ServiceNow users for assignee/caller dimension.',
                    ],
                    'grain' => ['de' => 'Ein ServiceNow-User', 'en' => 'One ServiceNow user'],
                    'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sc_req_item',
                    'label' => ['de' => 'Service Catalog Request Item', 'en' => 'Service catalog request item'],
                    'description' => [
                        'de' => 'Katalog-Anfragen — Request-Fulfillment-Analytics.',
                        'en' => 'Catalog requests — request fulfillment analytics.',
                    ],
                    'grain' => ['de' => 'Ein Request Item', 'en' => 'One request item'],
                    'role' => ['de' => 'Fact (Catalog)', 'en' => 'Fact (catalog)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'task',
                    'label' => ['de' => 'Task', 'en' => 'Task'],
                    'description' => [
                        'de' => 'Aufgaben an Incidents/Changes — optional für Workload-Analytics.',
                        'en' => 'Tasks on incidents/changes — optional for workload analytics.',
                    ],
                    'grain' => ['de' => 'Eine Aufgabe', 'en' => 'One task'],
                    'role' => ['de' => 'Workload (optional)', 'en' => 'Workload (optional)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'kb_knowledge',
                    'label' => ['de' => 'Knowledge Base', 'en' => 'Knowledge base'],
                    'description' => [
                        'de' => 'KB-Artikel — Deflection und Self-Service; Metadaten, nicht Volltext.',
                        'en' => 'KB articles — deflection and self-service; metadata, not full text.',
                    ],
                    'grain' => ['de' => 'Ein KB-Artikel', 'en' => 'One KB article'],
                    'role' => ['de' => 'Knowledge (optional)', 'en' => 'Knowledge (optional)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Incident', 'name' => 'sys_id', 'role' => 'key', 'why' => ['de' => 'Join / Lineage', 'en' => 'Join / lineage']],
                ['entity' => 'Incident', 'name' => 'number', 'role' => 'dimension', 'why' => ['de' => 'Ticket-Label', 'en' => 'Ticket label']],
                ['entity' => 'Incident', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'Open / Resolved / Closed', 'en' => 'Open / resolved / closed']],
                ['entity' => 'Incident', 'name' => 'priority', 'role' => 'dimension', 'why' => ['de' => 'Priorität', 'en' => 'Priority']],
                ['entity' => 'Incident', 'name' => 'assignment_group', 'role' => 'dimension', 'why' => ['de' => 'Team', 'en' => 'Team']],
                ['entity' => 'Incident', 'name' => 'assigned_to', 'role' => 'dimension', 'why' => ['de' => 'Assignee', 'en' => 'Assignee']],
                ['entity' => 'Incident', 'name' => 'category', 'role' => 'dimension', 'why' => ['de' => 'Kategorie', 'en' => 'Category']],
                ['entity' => 'Incident', 'name' => 'opened_at', 'role' => 'measure', 'why' => ['de' => 'Created / Perioden-Grain', 'en' => 'Created / period grain']],
                ['entity' => 'Incident', 'name' => 'resolved_at', 'role' => 'measure', 'why' => ['de' => 'MTTR-Berechnung', 'en' => 'MTTR calculation']],
                ['entity' => 'Incident', 'name' => 'closed_at', 'role' => 'measure', 'why' => ['de' => 'Close-Zeit', 'en' => 'Close time']],
                ['entity' => 'Incident', 'name' => 'caller_id', 'role' => 'dimension', 'why' => ['de' => 'Caller (PII-Link)', 'en' => 'Caller (PII link)']],
                ['entity' => 'Incident', 'name' => 'cmdb_ci', 'role' => 'dimension', 'why' => ['de' => 'Betroffenes CI', 'en' => 'Affected CI']],
                ['entity' => 'Incident', 'name' => 'sys_updated_on', 'role' => 'key', 'why' => ['de' => 'Incremental Sync', 'en' => 'Incremental sync']],
                ['entity' => 'Problem', 'name' => 'sys_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Problem', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'Problem-Status', 'en' => 'Problem status']],
                ['entity' => 'Problem', 'name' => 'priority', 'role' => 'dimension', 'why' => ['de' => 'Priorität', 'en' => 'Priority']],
                ['entity' => 'Change Request', 'name' => 'sys_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Change Request', 'name' => 'state', 'role' => 'dimension', 'why' => ['de' => 'Change-Status', 'en' => 'Change status']],
                ['entity' => 'Change Request', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'Normal / Standard / Emergency', 'en' => 'Normal / standard / emergency']],
                ['entity' => 'Change Request', 'name' => 'start_date', 'role' => 'measure', 'why' => ['de' => 'Change-Fenster', 'en' => 'Change window']],
                ['entity' => 'CMDB CI', 'name' => 'sys_id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'CMDB CI', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'CI-Label', 'en' => 'CI label']],
                ['entity' => 'CMDB CI', 'name' => 'sys_class_name', 'role' => 'dimension', 'why' => ['de' => 'CI-Typ', 'en' => 'CI type']],
                ['entity' => 'Sys User', 'name' => 'sys_id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'Sys User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
                ['entity' => 'Sys User', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Sys User', 'name' => 'department', 'role' => 'dimension', 'why' => ['de' => 'Abteilung', 'en' => 'Department']],
                ['entity' => 'Sys User', 'name' => 'active', 'role' => 'dimension', 'why' => ['de' => 'Aktive User', 'en' => 'Active users']],
            ],
            'skipTables' => [
                [
                    'name' => 'sys_audit / sys_audit_delete',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Audit-Trail — hohes Volumen, selten KPI-relevant.',
                        'en' => 'Audit trail — high volume, rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'syslog / syslog_transaction',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Plattform-Logs — kein Business-Mart-Kern.',
                        'en' => 'Platform logs — not business mart core.',
                    ],
                ],
                [
                    'name' => 'sysevent / sysevent_register',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Event-Engine-Telemetrie — nicht analytisch.',
                        'en' => 'Event engine telemetry — not analytical.',
                    ],
                ],
                [
                    'name' => 'sys_attachment (bodies)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Binaries und Anhänge — teuer, wenig Analytics-Nutzen.',
                        'en' => 'Binaries and attachments — expensive, little analytics value.',
                    ],
                ],
                [
                    'name' => 'sys_journal_field (work notes / comments)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Freitext-Kommentare — unstrukturiertes PII.',
                        'en' => 'Free-text comments — unstructured PII.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Work notes / comment bodies', 'reason' => ['de' => 'Rauschen und PII', 'en' => 'Noise and PII']],
                ['name' => 'Attachment binaries', 'reason' => ['de' => 'Kosten, wenig Analytics-Nutzen', 'en' => 'Cost, little analytics value']],
                ['name' => 'Audit tables (bulk sync)', 'reason' => ['de' => 'Hohes Volumen ohne KPI-Nutzen', 'en' => 'High volume without KPI value']],
                ['name' => 'Unused custom fields (bulk sync all)', 'reason' => ['de' => 'Vergrößert DSDR-Fläche', 'en' => 'Expands DSDR surface']],
            ],
            'dimensions' => [
                [
                    'id' => 'priority',
                    'label' => ['de' => 'Priority', 'en' => 'Priority'],
                    'grain' => ['de' => 'P1–P5 / Critical–Low', 'en' => 'P1–P5 / critical–low'],
                    'notes' => [
                        'de' => 'Impact × Urgency-Mapping firmenspezifisch harmonisieren.',
                        'en' => 'Harmonize impact × urgency mapping firm-specifically.',
                    ],
                ],
                [
                    'id' => 'assignment_group',
                    'label' => ['de' => 'Assignment Group', 'en' => 'Assignment group'],
                    'grain' => ['de' => 'Support-Team', 'en' => 'Support team'],
                    'notes' => [
                        'de' => 'Team-Hierarchie und Escalation-Pfade beachten.',
                        'en' => 'Respect team hierarchy and escalation paths.',
                    ],
                ],
                [
                    'id' => 'category',
                    'label' => ['de' => 'Category / Subcategory', 'en' => 'Category / subcategory'],
                    'grain' => ['de' => 'Ticket-Kategorie', 'en' => 'Ticket category'],
                    'notes' => [
                        'de' => 'Category-Werte vor dem Mart harmonisieren.',
                        'en' => 'Harmonize category values before the mart.',
                    ],
                ],
                [
                    'id' => 'state',
                    'label' => ['de' => 'State', 'en' => 'State'],
                    'grain' => ['de' => 'Open / Resolved / Closed', 'en' => 'Open / resolved / closed'],
                    'notes' => [
                        'de' => 'Open vs. Closed-Definition festlegen — Resolved zählt oft als offen.',
                        'en' => 'Lock open vs closed definition — resolved often counts as open.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Sys User',
                    'fields' => ['email', 'name', 'phone', 'mobile_phone'],
                    'treatment' => [
                        'de' => 'Workforce-PII — eigene Policy vs. Endkunden-PII.',
                        'en' => 'Workforce PII — separate policy from end-customer PII.',
                    ],
                ],
                [
                    'entity' => 'Incident (caller)',
                    'fields' => ['caller_id → Sys User.email / name'],
                    'treatment' => [
                        'de' => 'Caller über sys_user joinen — PII nicht denormalisieren ohne Bedarf.',
                        'en' => 'Join caller via sys_user — do not denormalize PII without need.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'Email, Employee Id, sys_id, External Ids.',
                        'en' => 'Email, employee id, sys_id, external ids.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Sys User, Caller-Referenzen + Warehouse-Kopien.',
                        'en' => 'Sys user, caller references + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'open-incidents',
                    'example' => true,
                    'label' => ['de' => 'Offene Incidents', 'en' => 'Open incidents'],
                    'question' => [
                        'de' => 'Wie viele Incidents sind aktuell offen?',
                        'en' => 'How many incidents are currently open?',
                    ],
                    'formula' => 'COUNT(*) WHERE state NOT IN (resolved, closed, canceled)',
                    'grain' => ['de' => 'Incident', 'en' => 'Incident'],
                    'dimensions' => ['priority', 'assignment_group', 'category'],
                    'fieldsUsed' => ['Incident.state', 'Incident.opened_at'],
                    'sourceHints' => [
                        'de' => 'Open-States listen — Resolved kann je nach Prozess offen zählen.',
                        'en' => 'List open states — resolved may count as open depending on process.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot vs. Flow-Metrik — einmal festlegen.',
                        'en' => 'Lock snapshot vs flow metric once.',
                    ],
                ],
                [
                    'id' => 'mttr',
                    'example' => true,
                    'label' => ['de' => 'MTTR', 'en' => 'MTTR'],
                    'question' => [
                        'de' => 'Wie lange dauert es im Schnitt, bis Incidents gelöst sind?',
                        'en' => 'How long on average until incidents are resolved?',
                    ],
                    'formula' => 'AVG(resolved_at - opened_at) WHERE resolved_at IS NOT NULL',
                    'grain' => ['de' => 'Incident', 'en' => 'Incident'],
                    'dimensions' => ['priority', 'assignment_group', 'category'],
                    'fieldsUsed' => ['Incident.opened_at', 'Incident.resolved_at'],
                    'sourceHints' => [
                        'de' => 'resolved_at vs. closed_at — welches Event zählt?',
                        'en' => 'resolved_at vs closed_at — which event counts?',
                    ],
                    'adapt' => [
                        'de' => 'Business Hours vs. Calendar Hours und Outlier-Filter klären.',
                        'en' => 'Clarify business hours vs calendar hours and outlier filtering.',
                    ],
                ],
                [
                    'id' => 'incidents-created',
                    'example' => false,
                    'label' => ['de' => 'Incidents erstellt', 'en' => 'Incidents created'],
                    'question' => [
                        'de' => 'Wie viele Incidents wurden in der Periode eröffnet?',
                        'en' => 'How many incidents were opened in the period?',
                    ],
                    'formula' => 'COUNT(*) WHERE opened_at IN period',
                    'grain' => ['de' => 'Incident', 'en' => 'Incident'],
                    'dimensions' => ['priority', 'category', 'state'],
                    'fieldsUsed' => ['Incident.opened_at'],
                    'sourceHints' => [
                        'de' => 'opened_at als Periodenfilter.',
                        'en' => 'opened_at as period filter.',
                    ],
                    'adapt' => [
                        'de' => 'Zeitzone der ServiceNow-Instanz übernehmen.',
                        'en' => 'Use the ServiceNow instance timezone.',
                    ],
                ],
                [
                    'id' => 'change-success-rate',
                    'example' => false,
                    'label' => ['de' => 'Change Success Rate', 'en' => 'Change success rate'],
                    'question' => [
                        'de' => 'Welcher Anteil der Changes wurde erfolgreich abgeschlossen?',
                        'en' => 'What share of changes completed successfully?',
                    ],
                    'formula' => 'COUNT(successful) / COUNT(closed)',
                    'grain' => ['de' => 'Change Request', 'en' => 'Change request'],
                    'dimensions' => ['state', 'category'],
                    'fieldsUsed' => ['Change Request.state', 'Change Request.type'],
                    'sourceHints' => [
                        'de' => 'Success-States definieren — Failed/Canceled ausschließen.',
                        'en' => 'Define success states — exclude failed/canceled.',
                    ],
                    'adapt' => [
                        'de' => 'Emergency vs. Standard Changes separat betrachten.',
                        'en' => 'Consider emergency vs standard changes separately.',
                    ],
                ],
                [
                    'id' => 'catalog-fulfillment-time',
                    'example' => false,
                    'label' => ['de' => 'Catalog Fulfillment Time', 'en' => 'Catalog fulfillment time'],
                    'question' => [
                        'de' => 'Wie lange dauert die Erfüllung von Katalog-Anfragen?',
                        'en' => 'How long does catalog request fulfillment take?',
                    ],
                    'formula' => 'AVG(closed_at - opened_at) WHERE state = closed',
                    'grain' => ['de' => 'Request Item', 'en' => 'Request item'],
                    'dimensions' => ['category', 'assignment_group'],
                    'fieldsUsed' => ['sc_req_item.opened_at', 'sc_req_item.closed_at'],
                    'sourceHints' => [
                        'de' => 'Nur wenn sc_req_item geladen wird.',
                        'en' => 'Only when sc_req_item is loaded.',
                    ],
                    'adapt' => [
                        'de' => 'SLA-Breach-Logik separat definieren.',
                        'en' => 'Define SLA breach logic separately.',
                    ],
                ],
            ],
            'tools' => $serviceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'zendesk',
            'domain' => 'service',
            'order' => 50,
            'label' => ['de' => 'Zendesk', 'en' => 'Zendesk'],
            'shortPurpose' => [
                'de' => 'Customer Service: Tickets, Users, CSAT — Load, PII und Support-Measures.',
                'en' => 'Customer service: tickets, users, CSAT — load, PII and support measures.',
            ],
            'entities' => [
                [
                    'id' => 'tickets',
                    'label' => ['de' => 'Tickets', 'en' => 'Tickets'],
                    'description' => [
                        'de' => 'Support-Tickets — zentraler Fact für Service-KPIs.',
                        'en' => 'Support tickets — core fact for service KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Ticket', 'en' => 'One ticket'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'users',
                    'label' => ['de' => 'Users', 'en' => 'Users'],
                    'description' => [
                        'de' => 'Endkunden und Agents — PII-Kern und DSDR-Einstieg.',
                        'en' => 'End customers and agents — PII core and DSDR entry point.',
                    ],
                    'grain' => ['de' => 'Ein User', 'en' => 'One user'],
                    'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'organizations',
                    'label' => ['de' => 'Organizations', 'en' => 'Organizations'],
                    'description' => [
                        'de' => 'Kundenorganisationen — Account-artige Dimension.',
                        'en' => 'Customer organizations — account-style dimension.',
                    ],
                    'grain' => ['de' => 'Eine Organisation', 'en' => 'One organization'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'groups',
                    'label' => ['de' => 'Groups', 'en' => 'Groups'],
                    'description' => [
                        'de' => 'Support-Gruppen für Team-Dimension.',
                        'en' => 'Support groups for team dimension.',
                    ],
                    'grain' => ['de' => 'Eine Gruppe', 'en' => 'One group'],
                    'role' => ['de' => 'Team-Dimension', 'en' => 'Team dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'ticket_metrics',
                    'label' => ['de' => 'Ticket Metrics', 'en' => 'Ticket metrics'],
                    'description' => [
                        'de' => 'Vorgeaggregierte SLA-Metriken — First Reply, Resolution Time.',
                        'en' => 'Pre-aggregated SLA metrics — first reply, resolution time.',
                    ],
                    'grain' => ['de' => 'Metriken pro Ticket', 'en' => 'Metrics per ticket'],
                    'role' => ['de' => 'SLA-Measures', 'en' => 'SLA measures'],
                    'load' => 'required',
                ],
                [
                    'id' => 'satisfaction_ratings',
                    'label' => ['de' => 'Satisfaction Ratings', 'en' => 'Satisfaction ratings'],
                    'description' => [
                        'de' => 'CSAT/NPS-Bewertungen — optional, aber zentral für Qualitäts-KPIs.',
                        'en' => 'CSAT/NPS ratings — optional but central for quality KPIs.',
                    ],
                    'grain' => ['de' => 'Eine Bewertung', 'en' => 'One rating'],
                    'role' => ['de' => 'Quality-Fact', 'en' => 'Quality fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'ticket_comments',
                    'label' => ['de' => 'Ticket Comments', 'en' => 'Ticket comments'],
                    'description' => [
                        'de' => 'Kommentare — Metadaten ja, Bodies nur mit Bedarf (PII).',
                        'en' => 'Comments — metadata yes, bodies only when needed (PII).',
                    ],
                    'grain' => ['de' => 'Ein Kommentar', 'en' => 'One comment'],
                    'role' => ['de' => 'Engagement (optional)', 'en' => 'Engagement (optional)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Tickets', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join / Lineage', 'en' => 'Join / lineage']],
                ['entity' => 'Tickets', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Open / Pending / Solved', 'en' => 'Open / pending / solved']],
                ['entity' => 'Tickets', 'name' => 'priority', 'role' => 'dimension', 'why' => ['de' => 'Priorität', 'en' => 'Priority']],
                ['entity' => 'Tickets', 'name' => 'group_id', 'role' => 'dimension', 'why' => ['de' => 'Team', 'en' => 'Team']],
                ['entity' => 'Tickets', 'name' => 'assignee_id', 'role' => 'dimension', 'why' => ['de' => 'Assignee', 'en' => 'Assignee']],
                ['entity' => 'Tickets', 'name' => 'requester_id', 'role' => 'dimension', 'why' => ['de' => 'Requester (PII-Link)', 'en' => 'Requester (PII link)']],
                ['entity' => 'Tickets', 'name' => 'organization_id', 'role' => 'dimension', 'why' => ['de' => 'Organisation', 'en' => 'Organization']],
                ['entity' => 'Tickets', 'name' => 'via.channel', 'role' => 'dimension', 'why' => ['de' => 'Kanal', 'en' => 'Channel']],
                ['entity' => 'Tickets', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Created / Perioden-Grain', 'en' => 'Created / period grain']],
                ['entity' => 'Tickets', 'name' => 'updated_at', 'role' => 'key', 'why' => ['de' => 'Incremental Sync', 'en' => 'Incremental sync']],
                ['entity' => 'Tickets', 'name' => 'solved_at', 'role' => 'measure', 'why' => ['de' => 'Resolution Time', 'en' => 'Resolution time']],
                ['entity' => 'Tickets', 'name' => 'type', 'role' => 'dimension', 'why' => ['de' => 'Ticket-Typ', 'en' => 'Ticket type']],
                ['entity' => 'Ticket Metrics', 'name' => 'ticket_id', 'role' => 'key', 'why' => ['de' => 'Ticket-Join', 'en' => 'Ticket join']],
                ['entity' => 'Ticket Metrics', 'name' => 'reply_time_in_minutes', 'role' => 'measure', 'why' => ['de' => 'First Reply Time', 'en' => 'First reply time']],
                ['entity' => 'Ticket Metrics', 'name' => 'full_resolution_time_in_minutes', 'role' => 'measure', 'why' => ['de' => 'Resolution Time', 'en' => 'Resolution time']],
                ['entity' => 'Satisfaction Ratings', 'name' => 'score', 'role' => 'measure', 'why' => ['de' => 'CSAT-Score', 'en' => 'CSAT score']],
                ['entity' => 'Satisfaction Ratings', 'name' => 'ticket_id', 'role' => 'key', 'why' => ['de' => 'Ticket-Join', 'en' => 'Ticket join']],
                ['entity' => 'Users', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'Users', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
                ['entity' => 'Users', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Users', 'name' => 'phone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Users', 'name' => 'role', 'role' => 'dimension', 'why' => ['de' => 'End User vs Agent', 'en' => 'End user vs agent']],
                ['entity' => 'Organizations', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Organizations', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Org-Label', 'en' => 'Org label']],
                ['entity' => 'Groups', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Group-Join', 'en' => 'Group join']],
                ['entity' => 'Groups', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Team-Label', 'en' => 'Team label']],
            ],
            'skipTables' => [
                [
                    'name' => 'Ticket comment bodies (plain_text / html_body)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Freitext-Kommentare — unstrukturiertes PII und hohes Volumen.',
                        'en' => 'Free-text comments — unstructured PII and high volume.',
                    ],
                ],
                [
                    'name' => 'Attachments / upload tokens',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Binaries und Dateiinhalte — teuer, wenig Analytics-Nutzen.',
                        'en' => 'Binaries and file bodies — expensive, little analytics value.',
                    ],
                ],
                [
                    'name' => 'Audit logs / events (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Plattform-Audit — selten KPI-relevant.',
                        'en' => 'Platform audit — rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Side conversations (bodies)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Interne Side-Conversations — PII und Rauschen.',
                        'en' => 'Internal side conversations — PII and noise.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Ticket comment bodies', 'reason' => ['de' => 'Rauschen und PII', 'en' => 'Noise and PII']],
                ['name' => 'Attachment binaries', 'reason' => ['de' => 'Kosten, wenig Analytics-Nutzen', 'en' => 'Cost, little analytics value']],
                ['name' => 'Full user profile dumps', 'reason' => ['de' => 'DSDR-Fläche vergrößern', 'en' => 'Expands DSDR surface']],
            ],
            'dimensions' => [
                [
                    'id' => 'status',
                    'label' => ['de' => 'Status', 'en' => 'Status'],
                    'grain' => ['de' => 'New / Open / Pending / Solved / Closed', 'en' => 'New / open / pending / solved / closed'],
                    'notes' => [
                        'de' => 'Open vs. Solved-Definition festlegen — Pending zählt oft als offen.',
                        'en' => 'Lock open vs solved definition — pending often counts as open.',
                    ],
                ],
                [
                    'id' => 'priority',
                    'label' => ['de' => 'Priority', 'en' => 'Priority'],
                    'grain' => ['de' => 'Low / Normal / High / Urgent', 'en' => 'Low / normal / high / urgent'],
                    'notes' => [
                        'de' => 'Priority-Werte vor dem Mart harmonisieren.',
                        'en' => 'Harmonize priority values before the mart.',
                    ],
                ],
                [
                    'id' => 'group',
                    'label' => ['de' => 'Group', 'en' => 'Group'],
                    'grain' => ['de' => 'Support-Team', 'en' => 'Support team'],
                    'notes' => [
                        'de' => 'Group-Hierarchie und Escalation-Pfade beachten.',
                        'en' => 'Respect group hierarchy and escalation paths.',
                    ],
                ],
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel', 'en' => 'Channel'],
                    'grain' => ['de' => 'Email / Chat / Phone / Web', 'en' => 'Email / chat / phone / web'],
                    'notes' => [
                        'de' => 'via.channel für Kanal-Dimension nutzen.',
                        'en' => 'Use via.channel for channel dimension.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Users',
                    'fields' => ['email', 'name', 'phone'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — PII taggen, RAW einschränken.',
                        'en' => 'Direct identifiers — tag PII, restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Tickets (requester)',
                    'fields' => ['requester_id → Users.email / name'],
                    'treatment' => [
                        'de' => 'Requester über users joinen — PII nicht denormalisieren ohne Bedarf.',
                        'en' => 'Join requester via users — do not denormalize PII without need.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'Email (primär), Phone, Zendesk User Id, External Ids.',
                        'en' => 'Email (primary), phone, Zendesk user id, external ids.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Users, Requester-Referenzen + Warehouse-Kopien.',
                        'en' => 'Users, requester references + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'tickets-created',
                    'example' => true,
                    'label' => ['de' => 'Tickets erstellt', 'en' => 'Tickets created'],
                    'question' => [
                        'de' => 'Wie viele Tickets wurden in der Periode erstellt?',
                        'en' => 'How many tickets were created in the period?',
                    ],
                    'formula' => 'COUNT(*) WHERE created_at IN period',
                    'grain' => ['de' => 'Ticket', 'en' => 'Ticket'],
                    'dimensions' => ['status', 'priority', 'group', 'channel'],
                    'fieldsUsed' => ['Tickets.created_at'],
                    'sourceHints' => [
                        'de' => 'created_at als Periodenfilter.',
                        'en' => 'created_at as period filter.',
                    ],
                    'adapt' => [
                        'de' => 'Zeitzone der Zendesk-Instanz übernehmen.',
                        'en' => 'Use the Zendesk instance timezone.',
                    ],
                ],
                [
                    'id' => 'tickets-solved',
                    'example' => true,
                    'label' => ['de' => 'Tickets gelöst', 'en' => 'Tickets solved'],
                    'question' => [
                        'de' => 'Wie viele Tickets wurden in der Periode gelöst?',
                        'en' => 'How many tickets were solved in the period?',
                    ],
                    'formula' => 'COUNT(*) WHERE solved_at IN period',
                    'grain' => ['de' => 'Ticket', 'en' => 'Ticket'],
                    'dimensions' => ['priority', 'group', 'channel'],
                    'fieldsUsed' => ['Tickets.solved_at', 'Tickets.status'],
                    'sourceHints' => [
                        'de' => 'solved_at vs. status=Solved — konsistent bleiben.',
                        'en' => 'solved_at vs status=Solved — stay consistent.',
                    ],
                    'adapt' => [
                        'de' => 'Reopened-Tickets und Backlog-Shift berücksichtigen.',
                        'en' => 'Account for reopened tickets and backlog shift.',
                    ],
                ],
                [
                    'id' => 'csat-score',
                    'example' => false,
                    'label' => ['de' => 'CSAT Score', 'en' => 'CSAT score'],
                    'question' => [
                        'de' => 'Wie zufrieden sind Kunden mit dem Support?',
                        'en' => 'How satisfied are customers with support?',
                    ],
                    'formula' => 'AVG(score) WHERE score IS NOT NULL',
                    'grain' => ['de' => 'Satisfaction Rating', 'en' => 'Satisfaction rating'],
                    'dimensions' => ['group', 'channel'],
                    'fieldsUsed' => ['Satisfaction Ratings.score', 'Satisfaction Ratings.ticket_id'],
                    'sourceHints' => [
                        'de' => 'Nur wenn satisfaction_ratings geladen wird.',
                        'en' => 'Only when satisfaction_ratings is loaded.',
                    ],
                    'adapt' => [
                        'de' => 'Good/Bad vs. Skala — Scoring-Modell festlegen.',
                        'en' => 'Lock good/bad vs scale scoring model.',
                    ],
                ],
                [
                    'id' => 'first-reply-time',
                    'example' => false,
                    'label' => ['de' => 'First Reply Time', 'en' => 'First reply time'],
                    'question' => [
                        'de' => 'Wie schnell antwortet der Support auf neue Tickets?',
                        'en' => 'How quickly does support respond to new tickets?',
                    ],
                    'formula' => 'AVG(reply_time_in_minutes) FROM ticket_metrics',
                    'grain' => ['de' => 'Ticket', 'en' => 'Ticket'],
                    'dimensions' => ['priority', 'group', 'channel'],
                    'fieldsUsed' => ['Ticket Metrics.reply_time_in_minutes'],
                    'sourceHints' => [
                        'de' => 'ticket_metrics bevorzugen — robuster als manuelle Berechnung.',
                        'en' => 'Prefer ticket_metrics — more robust than manual calculation.',
                    ],
                    'adapt' => [
                        'de' => 'Business Hours vs. Calendar Hours klären.',
                        'en' => 'Clarify business hours vs calendar hours.',
                    ],
                ],
                [
                    'id' => 'open-tickets',
                    'example' => false,
                    'label' => ['de' => 'Offene Tickets', 'en' => 'Open tickets'],
                    'question' => [
                        'de' => 'Wie viele Tickets sind aktuell offen?',
                        'en' => 'How many tickets are currently open?',
                    ],
                    'formula' => 'COUNT(*) WHERE status NOT IN (solved, closed)',
                    'grain' => ['de' => 'Ticket', 'en' => 'Ticket'],
                    'dimensions' => ['priority', 'group', 'channel'],
                    'fieldsUsed' => ['Tickets.status'],
                    'sourceHints' => [
                        'de' => 'Snapshot vs. Flow-Metrik — einmal festlegen.',
                        'en' => 'Lock snapshot vs flow metric once.',
                    ],
                    'adapt' => [
                        'de' => 'Pending-Status je nach Prozess als offen zählen.',
                        'en' => 'Count pending status as open depending on process.',
                    ],
                ],
            ],
            'tools' => $serviceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'shopify',
            'domain' => 'commerce',
            'order' => 60,
            'label' => ['de' => 'Shopify', 'en' => 'Shopify'],
            'shortPurpose' => [
                'de' => 'E-Commerce: Orders, Customers, Products — Load, PII und Commerce-Measures.',
                'en' => 'E-commerce: orders, customers, products — load, PII and commerce measures.',
            ],
            'entities' => [
                [
                    'id' => 'orders',
                    'label' => ['de' => 'Orders', 'en' => 'Orders'],
                    'description' => [
                        'de' => 'Bestellungen — zentraler Fact für GMV, AOV und Order-KPIs.',
                        'en' => 'Orders — core fact for GMV, AOV and order KPIs.',
                    ],
                    'grain' => ['de' => 'Eine Bestellung', 'en' => 'One order'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'order_line_items',
                    'label' => ['de' => 'Order Line Items', 'en' => 'Order line items'],
                    'description' => [
                        'de' => 'Produktzeilen pro Order — Produktmix und Item-Level-Revenue.',
                        'en' => 'Product lines per order — product mix and item-level revenue.',
                    ],
                    'grain' => ['de' => 'Eine Zeile', 'en' => 'One line item'],
                    'role' => ['de' => 'Fact (fein)', 'en' => 'Fact (fine)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'customers',
                    'label' => ['de' => 'Customers', 'en' => 'Customers'],
                    'description' => [
                        'de' => 'Kundenstammdaten — PII-Kern und DSDR-Einstieg.',
                        'en' => 'Customer master data — PII core and DSDR entry point.',
                    ],
                    'grain' => ['de' => 'Ein Kunde', 'en' => 'One customer'],
                    'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'products',
                    'label' => ['de' => 'Products', 'en' => 'Products'],
                    'description' => [
                        'de' => 'Produktstamm — Dimension für Produktmix und Katalog-Analytics.',
                        'en' => 'Product master — dimension for product mix and catalog analytics.',
                    ],
                    'grain' => ['de' => 'Ein Produkt', 'en' => 'One product'],
                    'role' => ['de' => 'Produkt-Dimension', 'en' => 'Product dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'variants',
                    'label' => ['de' => 'Variants', 'en' => 'Variants'],
                    'description' => [
                        'de' => 'SKU-Varianten — feinere Produkt-Grain für Inventory und Sales.',
                        'en' => 'SKU variants — finer product grain for inventory and sales.',
                    ],
                    'grain' => ['de' => 'Eine Variante', 'en' => 'One variant'],
                    'role' => ['de' => 'SKU-Dimension', 'en' => 'SKU dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'fulfillments',
                    'label' => ['de' => 'Fulfillments', 'en' => 'Fulfillments'],
                    'description' => [
                        'de' => 'Versand-/Fulfillment-Events — optional für Logistik-KPIs.',
                        'en' => 'Shipping/fulfillment events — optional for logistics KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Fulfillment', 'en' => 'One fulfillment'],
                    'role' => ['de' => 'Logistics-Fact', 'en' => 'Logistics fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'refunds',
                    'label' => ['de' => 'Refunds', 'en' => 'Refunds'],
                    'description' => [
                        'de' => 'Erstattungen — für Refund Rate und Net Revenue.',
                        'en' => 'Refunds — for refund rate and net revenue.',
                    ],
                    'grain' => ['de' => 'Eine Erstattung', 'en' => 'One refund'],
                    'role' => ['de' => 'Refund-Fact', 'en' => 'Refund fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'inventory_levels',
                    'label' => ['de' => 'Inventory Levels', 'en' => 'Inventory levels'],
                    'description' => [
                        'de' => 'Lagerbestände pro Variante/Location — optional für Inventory-Analytics.',
                        'en' => 'Stock levels per variant/location — optional for inventory analytics.',
                    ],
                    'grain' => ['de' => 'Bestand pro Variante/Location', 'en' => 'Stock per variant/location'],
                    'role' => ['de' => 'Inventory (optional)', 'en' => 'Inventory (optional)'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Orders', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join / Lineage', 'en' => 'Join / lineage']],
                ['entity' => 'Orders', 'name' => 'total_price', 'role' => 'measure', 'why' => ['de' => 'GMV / Order-Wert', 'en' => 'GMV / order value']],
                ['entity' => 'Orders', 'name' => 'subtotal_price', 'role' => 'measure', 'why' => ['de' => 'Netto vor Versand/Steuer', 'en' => 'Net before shipping/tax']],
                ['entity' => 'Orders', 'name' => 'currency', 'role' => 'measure', 'why' => ['de' => 'Währung', 'en' => 'Currency']],
                ['entity' => 'Orders', 'name' => 'financial_status', 'role' => 'dimension', 'why' => ['de' => 'Paid / Refunded / Pending', 'en' => 'Paid / refunded / pending']],
                ['entity' => 'Orders', 'name' => 'fulfillment_status', 'role' => 'dimension', 'why' => ['de' => 'Versand-Status', 'en' => 'Fulfillment status']],
                ['entity' => 'Orders', 'name' => 'source_name', 'role' => 'dimension', 'why' => ['de' => 'Kanal / Quelle', 'en' => 'Channel / source']],
                ['entity' => 'Orders', 'name' => 'customer_id', 'role' => 'dimension', 'why' => ['de' => 'Kunden-Join', 'en' => 'Customer join']],
                ['entity' => 'Orders', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Order-Datum / Perioden-Grain', 'en' => 'Order date / period grain']],
                ['entity' => 'Orders', 'name' => 'updated_at', 'role' => 'key', 'why' => ['de' => 'Incremental Sync', 'en' => 'Incremental sync']],
                ['entity' => 'Orders', 'name' => 'shipping_address.country', 'role' => 'dimension', 'why' => ['de' => 'Land', 'en' => 'Country']],
                ['entity' => 'Orders', 'name' => 'cancelled_at', 'role' => 'measure', 'why' => ['de' => 'Stornierung', 'en' => 'Cancellation']],
                ['entity' => 'Order Line Items', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Order Line Items', 'name' => 'order_id', 'role' => 'key', 'why' => ['de' => 'Order-Join', 'en' => 'Order join']],
                ['entity' => 'Order Line Items', 'name' => 'product_id', 'role' => 'dimension', 'why' => ['de' => 'Produkt', 'en' => 'Product']],
                ['entity' => 'Order Line Items', 'name' => 'variant_id', 'role' => 'dimension', 'why' => ['de' => 'SKU', 'en' => 'SKU']],
                ['entity' => 'Order Line Items', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Menge', 'en' => 'Quantity']],
                ['entity' => 'Order Line Items', 'name' => 'price', 'role' => 'measure', 'why' => ['de' => 'Zeilenpreis', 'en' => 'Line price']],
                ['entity' => 'Customers', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Customers', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Person-Key / PII', 'en' => 'Person key / PII']],
                ['entity' => 'Customers', 'name' => 'first_name', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Customers', 'name' => 'last_name', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Customers', 'name' => 'phone', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'Customers', 'name' => 'orders_count', 'role' => 'measure', 'why' => ['de' => 'Customer Segment', 'en' => 'Customer segment']],
                ['entity' => 'Customers', 'name' => 'total_spent', 'role' => 'measure', 'why' => ['de' => 'LTV-Kontext', 'en' => 'LTV context']],
                ['entity' => 'Products', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Products', 'name' => 'title', 'role' => 'dimension', 'why' => ['de' => 'Produkt-Label', 'en' => 'Product label']],
                ['entity' => 'Products', 'name' => 'product_type', 'role' => 'dimension', 'why' => ['de' => 'Produkt-Typ', 'en' => 'Product type']],
                ['entity' => 'Products', 'name' => 'vendor', 'role' => 'dimension', 'why' => ['de' => 'Vendor', 'en' => 'Vendor']],
                ['entity' => 'Variants', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Variants', 'name' => 'sku', 'role' => 'dimension', 'why' => ['de' => 'SKU-Key', 'en' => 'SKU key']],
                ['entity' => 'Variants', 'name' => 'price', 'role' => 'measure', 'why' => ['de' => 'Listenpreis', 'en' => 'List price']],
                ['entity' => 'Refunds', 'name' => 'order_id', 'role' => 'key', 'why' => ['de' => 'Order-Join', 'en' => 'Order join']],
                ['entity' => 'Refunds', 'name' => 'created_at', 'role' => 'measure', 'why' => ['de' => 'Refund-Datum', 'en' => 'Refund date']],
                ['entity' => 'Refunds', 'name' => 'transactions.amount', 'role' => 'measure', 'why' => ['de' => 'Refund-Betrag', 'en' => 'Refund amount']],
            ],
            'skipTables' => [
                [
                    'name' => 'Draft orders (noise)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Entwürfe ohne Conversion — verschmutzen Order-KPIs.',
                        'en' => 'Drafts without conversion — pollute order KPIs.',
                    ],
                ],
                [
                    'name' => 'Checkout abandoned (raw)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Abgebrochene Checkouts — nur laden wenn Funnel-Analytics gebraucht wird.',
                        'en' => 'Abandoned checkouts — load only when funnel analytics is needed.',
                    ],
                ],
                [
                    'name' => 'Product images / media binaries',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Binaries — teuer, wenig Analytics-Nutzen im Warehouse.',
                        'en' => 'Binaries — expensive, little warehouse analytics value.',
                    ],
                ],
                [
                    'name' => 'Webhook delivery logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Plattform-Telemetrie — nicht für Business-KPIs.',
                        'en' => 'Platform telemetry — not for business KPIs.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Draft orders noise', 'reason' => ['de' => 'Verschmutzt Order-KPIs', 'en' => 'Pollutes order KPIs']],
                ['name' => 'Checkout abandoned raw (if not needed)', 'reason' => ['de' => 'Volumen ohne KPI-Nutzen', 'en' => 'Volume without KPI value']],
                ['name' => 'Product image binaries', 'reason' => ['de' => 'Kosten, wenig Analytics-Nutzen', 'en' => 'Cost, little analytics value']],
                ['name' => 'Unused metafields (bulk sync all)', 'reason' => ['de' => 'Vergrößert DSDR-Fläche', 'en' => 'Expands DSDR surface']],
            ],
            'dimensions' => [
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel / Source', 'en' => 'Channel / source'],
                    'grain' => ['de' => 'source_name / sales_channel', 'en' => 'source_name / sales_channel'],
                    'notes' => [
                        'de' => 'Online Store vs. POS vs. Marketplace harmonisieren.',
                        'en' => 'Harmonize online store vs POS vs marketplace.',
                    ],
                ],
                [
                    'id' => 'country',
                    'label' => ['de' => 'Country', 'en' => 'Country'],
                    'grain' => ['de' => 'Shipping / Billing Country', 'en' => 'Shipping / billing country'],
                    'notes' => [
                        'de' => 'Shipping- vs. Billing-Land bewusst wählen.',
                        'en' => 'Consciously choose shipping vs billing country.',
                    ],
                ],
                [
                    'id' => 'product_type',
                    'label' => ['de' => 'Product Type', 'en' => 'Product type'],
                    'grain' => ['de' => 'Produkt-Kategorie', 'en' => 'Product category'],
                    'notes' => [
                        'de' => 'product_type vor dem Mart harmonisieren.',
                        'en' => 'Harmonize product_type before the mart.',
                    ],
                ],
                [
                    'id' => 'customer_segment',
                    'label' => ['de' => 'Customer Segment', 'en' => 'Customer segment'],
                    'grain' => ['de' => 'New / Returning / VIP (derived)', 'en' => 'New / returning / VIP (derived)'],
                    'notes' => [
                        'de' => 'Aus orders_count / total_spent ableiten — nicht blind übernehmen.',
                        'en' => 'Derive from orders_count / total_spent — do not copy blindly.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customers',
                    'fields' => ['email', 'first_name', 'last_name', 'phone', 'default_address.address1'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — PII taggen, RAW einschränken.',
                        'en' => 'Direct identifiers — tag PII, restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Orders (shipping address)',
                    'fields' => ['shipping_address.name', 'shipping_address.address1', 'shipping_address.phone'],
                    'treatment' => [
                        'de' => 'Versandadresse — PII, nur laden wenn Logistik-Analytics es braucht.',
                        'en' => 'Shipping address — PII, load only when logistics analytics needs it.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'Email (primär), Phone, Shopify Customer Id, External Ids.',
                        'en' => 'Email (primary), phone, Shopify customer id, external ids.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customers, Order-Adressen + Warehouse-/Activation-Kopien.',
                        'en' => 'Customers, order addresses + warehouse/activation copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'gmv',
                    'example' => true,
                    'label' => ['de' => 'GMV (Gross Merchandise Value)', 'en' => 'GMV (gross merchandise value)'],
                    'question' => [
                        'de' => 'Wie viel Bruttoumsatz wurde in der Periode generiert?',
                        'en' => 'How much gross merchandise value was generated in the period?',
                    ],
                    'formula' => 'SUM(total_price) WHERE financial_status IN (paid, partially_refunded) AND created_at IN period',
                    'grain' => ['de' => 'Order', 'en' => 'Order'],
                    'dimensions' => ['channel', 'country', 'product_type'],
                    'fieldsUsed' => ['Orders.total_price', 'Orders.financial_status', 'Orders.created_at'],
                    'sourceHints' => [
                        'de' => 'Draft/Cancelled ausschließen — financial_status prüfen.',
                        'en' => 'Exclude draft/cancelled — verify financial_status.',
                    ],
                    'adapt' => [
                        'de' => 'Steuer, Versand, Refunds und Multi-Currency klären.',
                        'en' => 'Clarify tax, shipping, refunds and multi-currency.',
                    ],
                ],
                [
                    'id' => 'aov',
                    'example' => true,
                    'label' => ['de' => 'AOV (Average Order Value)', 'en' => 'AOV (average order value)'],
                    'question' => [
                        'de' => 'Was ist der durchschnittliche Bestellwert?',
                        'en' => 'What is the average order value?',
                    ],
                    'formula' => 'SUM(total_price) / COUNT(orders) WHERE financial_status = paid',
                    'grain' => ['de' => 'Order', 'en' => 'Order'],
                    'dimensions' => ['channel', 'country', 'customer_segment'],
                    'fieldsUsed' => ['Orders.total_price', 'Orders.financial_status'],
                    'sourceHints' => [
                        'de' => 'Nur paid Orders — Refunds separat behandeln.',
                        'en' => 'Paid orders only — treat refunds separately.',
                    ],
                    'adapt' => [
                        'de' => 'Median vs. Mean und Währungsumrechnung festlegen.',
                        'en' => 'Lock median vs mean and currency conversion.',
                    ],
                ],
                [
                    'id' => 'orders-count',
                    'example' => false,
                    'label' => ['de' => 'Orders Count', 'en' => 'Orders count'],
                    'question' => [
                        'de' => 'Wie viele Bestellungen wurden in der Periode aufgegeben?',
                        'en' => 'How many orders were placed in the period?',
                    ],
                    'formula' => 'COUNT(*) WHERE created_at IN period AND cancelled_at IS NULL',
                    'grain' => ['de' => 'Order', 'en' => 'Order'],
                    'dimensions' => ['channel', 'country', 'product_type'],
                    'fieldsUsed' => ['Orders.created_at', 'Orders.cancelled_at'],
                    'sourceHints' => [
                        'de' => 'Stornierte Orders ausschließen.',
                        'en' => 'Exclude cancelled orders.',
                    ],
                    'adapt' => [
                        'de' => 'Test-Orders und interne Bestellungen filtern.',
                        'en' => 'Filter test orders and internal orders.',
                    ],
                ],
                [
                    'id' => 'refund-rate',
                    'example' => false,
                    'label' => ['de' => 'Refund Rate', 'en' => 'Refund rate'],
                    'question' => [
                        'de' => 'Welcher Anteil des Umsatzes wurde erstattet?',
                        'en' => 'What share of revenue was refunded?',
                    ],
                    'formula' => 'SUM(refund_amount) / SUM(order_total)',
                    'grain' => ['de' => 'Order / Refund', 'en' => 'Order / refund'],
                    'dimensions' => ['channel', 'product_type'],
                    'fieldsUsed' => ['Refunds.transactions.amount', 'Orders.total_price'],
                    'sourceHints' => [
                        'de' => 'Refunds-Tabelle mit Orders joinen.',
                        'en' => 'Join refunds table with orders.',
                    ],
                    'adapt' => [
                        'de' => 'Partial vs. Full Refunds und Zeitversatz klären.',
                        'en' => 'Clarify partial vs full refunds and time lag.',
                    ],
                ],
                [
                    'id' => 'units-sold',
                    'example' => false,
                    'label' => ['de' => 'Units Sold', 'en' => 'Units sold'],
                    'question' => [
                        'de' => 'Wie viele Einheiten wurden verkauft?',
                        'en' => 'How many units were sold?',
                    ],
                    'formula' => 'SUM(quantity) FROM order_line_items JOIN orders',
                    'grain' => ['de' => 'Line Item', 'en' => 'Line item'],
                    'dimensions' => ['product_type', 'channel', 'country'],
                    'fieldsUsed' => ['Order Line Items.quantity', 'Order Line Items.product_id'],
                    'sourceHints' => [
                        'de' => 'Nur paid/non-cancelled Orders einbeziehen.',
                        'en' => 'Include paid/non-cancelled orders only.',
                    ],
                    'adapt' => [
                        'de' => 'Bundles und Geschenkartikel separat behandeln.',
                        'en' => 'Treat bundles and gift items separately.',
                    ],
                ],
            ],
            'tools' => $commerceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
