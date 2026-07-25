<?php

/**
 * Deep governance overlays (+ Salesforce measures/sqlExamples) for Supplier Library.
 * Merged onto catalog products by id — source-native PII/DSDR/skip content.
 *
 * @return array<string, array<string, mixed>>
 */

return [
    'salesforce' => [
        'pii' => [
            [
                'entity' => 'Contact',
                'fields' => ['Email', 'Phone', 'MobilePhone', 'FirstName', 'LastName', 'MailingStreet', 'MailingCity', 'MailingPostalCode', 'Birthdate'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Direkte Identifikatoren — PII taggen, Match-Keys getrennt halten.', 'en' => 'Direct identifiers — tag PII, keep match keys separate.'],
            ],
            [
                'entity' => 'Lead',
                'fields' => ['Email', 'Phone', 'MobilePhone', 'FirstName', 'LastName', 'Street', 'City', 'PostalCode', 'Company'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Wie Contact; Web-to-Lead-Felder mit inventarisieren.', 'en' => 'Treat like Contact; inventory web-to-lead fields too.'],
            ],
            [
                'entity' => 'Account',
                'fields' => ['BillingStreet', 'BillingCity', 'BillingPostalCode', 'ShippingStreet', 'ShippingCity', 'PersonEmail', 'PersonMobilePhone'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: taggen; Curated: generalisieren (Region statt PLZ); Mart: aggregiert', 'en' => 'RAW: tag; Curated: generalize (region vs postal); Mart: aggregated'],
                'treatment' => ['de' => 'B2B-Adressen oft Quasi-ID; Person Accounts = direkte PII.', 'en' => 'B2B addresses often quasi-ID; person accounts = direct PII.'],
            ],
            [
                'entity' => 'User',
                'fields' => ['Email', 'Name', 'Phone', 'MobilePhone', 'Username', 'FederationIdentifier'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Workforce-PII — eigene Retention und Zugriff vs. Kunden.', 'en' => 'Workforce PII — separate retention and access vs customers.'],
            ],
            [
                'entity' => 'Case',
                'fields' => ['SuppliedEmail', 'SuppliedName', 'SuppliedPhone', 'Description', 'Subject'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext und Web-Case-Felder — oft ungeprüfte PII.', 'en' => 'Free text and web-case fields — often unchecked PII.'],
            ],
            [
                'entity' => 'Task',
                'fields' => ['Subject', 'Description', 'WhoId'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Activity-Bodies und polymorphic WhoId — PII-Pfad.', 'en' => 'Activity bodies and polymorphic WhoId — PII path.'],
            ],
            [
                'entity' => 'Event',
                'fields' => ['Subject', 'Description', 'WhoId', 'Location'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Meeting-Notizen und Location können Personenbezug haben.', 'en' => 'Meeting notes and location can be personal.'],
            ],
            [
                'entity' => 'Opportunity',
                'fields' => ['Description', 'NextStep'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext-Risiko — nicht ungeprüft in Marts.', 'en' => 'Free-text risk — do not push unchecked into marts.'],
            ],
            [
                'entity' => 'CampaignMember',
                'fields' => ['Email', 'FirstName', 'LastName', 'ContactId', 'LeadId'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Marketing-Join zu Contact/Lead — zweite PII-Fläche.', 'en' => 'Marketing join to Contact/Lead — second PII surface.'],
            ],
            [
                'entity' => 'Custom / External Id',
                'fields' => ['*Email* custom fields', 'External Id custom fields', 'National ID customs'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Alle Custom Fields mit Email/External Id inventarisieren.', 'en' => 'Inventory all custom fields with email/external id.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'Email, Phone, Salesforce Id, External Id Customs, MobilePhone.', 'en' => 'Email, phone, Salesforce Id, external Id customs, MobilePhone.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Contact, Lead, Person Account — plus jedes Objekt mit Email-/Phone-Feldern.', 'en' => 'Contact, Lead, person Account — plus any object with email/phone fields.'],
            ],
            [
                'focus' => ['de' => 'Warehouse-Kopien', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'Landing/Curated-Tabellen, Activation/Reverse-ETL, BI-Extracts, Shared Stages.', 'en' => 'Landing/curated tables, activation/reverse-ETL, BI extracts, shared stages.'],
            ],
            [
                'focus' => ['de' => 'History-Objekte', 'en' => 'History objects'],
                'notes' => ['de' => 'ContactHistory, LeadHistory, AccountHistory — alte Werte bleiben PII.', 'en' => 'ContactHistory, LeadHistory, AccountHistory — old values remain PII.'],
            ],
            [
                'focus' => ['de' => 'Email & Content Bodies', 'en' => 'Email & content bodies'],
                'notes' => ['de' => 'EmailMessage, ContentNote, ContentVersion — Bodies und Header.', 'en' => 'EmailMessage, ContentNote, ContentVersion — bodies and headers.'],
            ],
            [
                'focus' => ['de' => 'Web-to-Lead / Forms', 'en' => 'Web-to-lead / forms'],
                'notes' => ['de' => 'Lead-Source-Formulare und Custom Form Fields ohne Schema-Review.', 'en' => 'Lead-source forms and custom form fields without schema review.'],
            ],
            [
                'focus' => ['de' => 'Integration vs Enduser', 'en' => 'Integration vs end user'],
                'notes' => ['de' => 'Integration-User sieht mehr Felder — Field-Level Security ≠ Warehouse-Policy.', 'en' => 'Integration users see more fields — FLS ≠ warehouse policy.'],
            ],
            [
                'focus' => ['de' => 'Shared Activities', 'en' => 'Shared activities'],
                'notes' => ['de' => 'Task/Event WhoId polymorphic — Contact und Lead in einer Spalte.', 'en' => 'Task/Event WhoId polymorphic — Contact and Lead in one column.'],
            ],
            [
                'focus' => ['de' => 'Sandbox & Refresh', 'en' => 'Sandbox & refresh'],
                'notes' => ['de' => 'Sandbox-Kopien und Partial Copies nicht mit Prod-Marts vermischen.', 'en' => 'Do not mix sandbox/partial copies with prod marts.'],
            ],
            [
                'focus' => ['de' => 'Managed Packages', 'en' => 'Managed packages'],
                'notes' => ['de' => 'Package-Objekte mit versteckten PII-Feldern — Describe + Sample prüfen.', 'en' => 'Package objects with hidden PII fields — describe + sample.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'FeedItem / FeedComment (Chatter)',
                'category' => 'system',
                'reason' => ['de' => 'Collaboration-Noise und unstrukturiertes PII.', 'en' => 'Collaboration noise and unstructured PII.'],
            ],
            [
                'name' => 'ContentVersion / Attachment (bodies)',
                'category' => 'system',
                'reason' => ['de' => 'Binaries — teuer, wenig Analytics-Nutzen.', 'en' => 'Binaries — expensive, little analytics value.'],
            ],
            [
                'name' => 'ContentNote',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-Notizen mit PII — nur mit Use Case.', 'en' => 'Free-text notes with PII — only with a use case.'],
            ],
            [
                'name' => 'SetupAuditTrail',
                'category' => 'system',
                'reason' => ['de' => 'Admin-Audit — nicht für Business-KPIs.', 'en' => 'Admin audit — not for business KPIs.'],
            ],
            [
                'name' => 'LoginHistory / LoginGeo',
                'category' => 'system',
                'reason' => ['de' => 'Login-Telemetrie — Security-Thema.', 'en' => 'Login telemetry — security topic.'],
            ],
            [
                'name' => 'EmailMessage (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'E-Mail-Bodies/Header — PII und Volumen.', 'en' => 'Email bodies/headers — PII and volume.'],
            ],
            [
                'name' => '*History (vor SCD2-Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Field-History erst bei echtem Type-2-Bedarf.', 'en' => 'Field history only when Type-2 is needed.'],
            ],
            [
                'name' => 'ApexLog / AsyncApexJob',
                'category' => 'system',
                'reason' => ['de' => 'Plattform-Runtime — kein Analytics-Load.', 'en' => 'Platform runtime — not an analytics load.'],
            ],
            [
                'name' => 'VoiceCall (bodies/recordings)',
                'category' => 'system',
                'reason' => ['de' => 'Aufnahmen und Transcripts — hochsensibel.', 'en' => 'Recordings and transcripts — highly sensitive.'],
            ],
            [
                'name' => 'EventRelation (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Teilnehmerlisten — oft überflüssig für Marts.', 'en' => 'Attendee lists — often unnecessary for marts.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'Description / Body free-text (bulk)',
                'reason' => ['de' => 'PII und Rauschen — gezielt oder gar nicht.', 'en' => 'PII and noise — selectively or not at all.'],
            ],
            [
                'name' => 'Unused custom fields (bulk sync all)',
                'reason' => ['de' => 'Vergrößert DSDR-Fläche und Kosten.', 'en' => 'Expands DSDR surface and cost.'],
            ],
            [
                'name' => 'UI layout / page metadata',
                'reason' => ['de' => 'Nicht analytisch.', 'en' => 'Not analytical.'],
            ],
            [
                'name' => 'Formula-only fields without consumer',
                'reason' => ['de' => 'Abgeleitet — in Curated neu berechnen.', 'en' => 'Derived — recompute in Curated.'],
            ],
            [
                'name' => 'Chatter feed bodies',
                'reason' => ['de' => 'Unstrukturiertes PII.', 'en' => 'Unstructured PII.'],
            ],
            [
                'name' => 'Attachment binary columns',
                'reason' => ['de' => 'Kosten ohne KPI-Nutzen.', 'en' => 'Cost without KPI value.'],
            ],
            [
                'name' => 'Debug / unused managed package fields',
                'reason' => ['de' => 'Schema-Ballast.', 'en' => 'Schema ballast.'],
            ]
        ],

        'measures' => [
            [
                'id' => 'won-revenue',
                'example' => true,
                'label' => ['de' => 'Won Revenue', 'en' => 'Won revenue'],
                'question' => ['de' => 'Wie viel Opportunity.Amount wurde in der Periode gewonnen?', 'en' => 'How much Opportunity.Amount was won in the period?'],
                'formula' => 'SUM(Opportunity.Amount) WHERE Opportunity.IsWon = true AND Opportunity.CloseDate IN period',
                'grain' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'dimensions' => ['owner', 'region', 'stage'],
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsWon', 'Opportunity.CloseDate', 'Opportunity.CurrencyIsoCode'],
                'sourceHints' => ['de' => 'CloseDate als Periodenfilter; Multi-Currency beachten.', 'en' => 'CloseDate as period filter; watch multi-currency.'],
                'adapt' => ['de' => 'Stornos, Teilgewinne und Booking- vs. Close-Datum klären.', 'en' => 'Clarify refunds, partial wins, booking vs close date.'],
            ],
            [
                'id' => 'open-pipeline',
                'example' => true,
                'label' => ['de' => 'Open Pipeline Amount', 'en' => 'Open pipeline amount'],
                'question' => ['de' => 'Wie viel offener Opportunity-Wert steht im Funnel?', 'en' => 'How much open opportunity value is in the funnel?'],
                'formula' => 'SUM(Opportunity.Amount) WHERE Opportunity.IsClosed = false',
                'grain' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'dimensions' => ['owner', 'stage', 'source'],
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsClosed', 'Opportunity.StageName'],
                'sourceHints' => ['de' => 'IsClosed = false; Stage-Whitelist pflegen.', 'en' => 'IsClosed = false; maintain stage allowlist.'],
                'adapt' => ['de' => 'Forecast Category optional zusätzlich filtern.', 'en' => 'Optionally also filter ForecastCategory.'],
            ],
            [
                'id' => 'weighted-pipeline',
                'example' => false,
                'label' => ['de' => 'Weighted Pipeline', 'en' => 'Weighted pipeline'],
                'question' => ['de' => 'Wie hoch ist der wahrscheinliche Pipeline-Wert?', 'en' => 'What is the probability-weighted pipeline value?'],
                'formula' => 'SUM(Opportunity.Amount * Opportunity.Probability / 100) WHERE Opportunity.IsClosed = false',
                'grain' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'dimensions' => ['owner', 'stage'],
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.Probability', 'Opportunity.IsClosed'],
                'sourceHints' => ['de' => 'Probability kommt aus Stage — Custom Overrides prüfen.', 'en' => 'Probability comes from stage — check custom overrides.'],
                'adapt' => ['de' => 'Firmeneigene Probability-Tabelle bevorzugen, falls vorhanden.', 'en' => 'Prefer firm probability table if it exists.'],
            ],
            [
                'id' => 'win-rate',
                'example' => false,
                'label' => ['de' => 'Win Rate', 'en' => 'Win rate'],
                'question' => ['de' => 'Welcher Anteil geschlossener Opps ist IsWon?', 'en' => 'What share of closed opps is IsWon?'],
                'formula' => 'COUNT(Opportunity WHERE IsWon) / COUNT(Opportunity WHERE IsClosed)',
                'grain' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'dimensions' => ['owner', 'source'],
                'fieldsUsed' => ['Opportunity.IsWon', 'Opportunity.IsClosed', 'Opportunity.StageName'],
                'sourceHints' => ['de' => 'Nur Closed im Nenner; Withdrawn Stages listen.', 'en' => 'Closed only in denominator; list withdrawn stages.'],
                'adapt' => ['de' => 'Duplicate/Withdrawn aus Nenner nehmen.', 'en' => 'Exclude duplicate/withdrawn from denominator.'],
            ],
            [
                'id' => 'avg-won-amount',
                'example' => false,
                'label' => ['de' => 'Avg Won Amount', 'en' => 'Avg won amount'],
                'question' => ['de' => 'Was ist der typische Amount gewonnener Opps?', 'en' => 'What is the typical amount of won opps?'],
                'formula' => 'SUM(Amount WHERE IsWon) / COUNT(IsWon)',
                'grain' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'dimensions' => ['owner', 'region'],
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsWon'],
                'sourceHints' => ['de' => 'Nur Won; Währung normalisieren.', 'en' => 'Won only; normalize currency.'],
                'adapt' => ['de' => 'Median vs. Mean festlegen.', 'en' => 'Lock median vs mean.'],
            ],
            [
                'id' => 'customers-with-won',
                'example' => true,
                'label' => ['de' => 'Customers with Won Opp', 'en' => 'Customers with won opp'],
                'question' => ['de' => 'Wie viele Accounts haben mindestens eine Won-Opp in der Periode?', 'en' => 'How many accounts have at least one won opp in the period?'],
                'formula' => 'COUNT(DISTINCT Opportunity.AccountId) WHERE Opportunity.IsWon = true AND Opportunity.CloseDate IN period',
                'grain' => ['de' => 'Account', 'en' => 'Account'],
                'dimensions' => ['region', 'owner'],
                'fieldsUsed' => ['Opportunity.AccountId', 'Opportunity.IsWon', 'Opportunity.CloseDate'],
                'sourceHints' => ['de' => 'AccountId Pflicht; Person Accounts klären.', 'en' => 'AccountId required; clarify person accounts.'],
                'adapt' => ['de' => 'Neue vs. Bestandskunden separat schneiden.', 'en' => 'Slice new vs existing customers separately.'],
            ],
            [
                'id' => 'new-accounts',
                'example' => false,
                'label' => ['de' => 'New Accounts', 'en' => 'New accounts'],
                'question' => ['de' => 'Wie viele Accounts wurden in der Periode angelegt?', 'en' => 'How many accounts were created in the period?'],
                'formula' => 'COUNT(*) WHERE Account.CreatedDate IN period',
                'grain' => ['de' => 'Account', 'en' => 'Account'],
                'dimensions' => ['region', 'owner'],
                'fieldsUsed' => ['Account.Id', 'Account.CreatedDate', 'Account.OwnerId'],
                'sourceHints' => ['de' => 'CreatedDate ≠ erste Won-Opp.', 'en' => 'CreatedDate ≠ first won opp.'],
                'adapt' => ['de' => 'Lead-Convert-Accounts ggf. separat.', 'en' => 'Optionally separate lead-converted accounts.'],
            ],
            [
                'id' => 'won-revenue-by-owner',
                'example' => true,
                'label' => ['de' => 'Won Revenue by Owner', 'en' => 'Won revenue by owner'],
                'question' => ['de' => 'Wie viel Won Amount je Salesperson (Owner)?', 'en' => 'How much won amount per salesperson (owner)?'],
                'formula' => 'SUM(Opportunity.Amount) WHERE IsWon GROUP BY Opportunity.OwnerId',
                'grain' => ['de' => 'Opportunity × Owner', 'en' => 'Opportunity × owner'],
                'dimensions' => ['owner', 'region'],
                'fieldsUsed' => ['Opportunity.Amount', 'Opportunity.IsWon', 'Opportunity.OwnerId', 'User.Name'],
                'sourceHints' => ['de' => 'OwnerId → User; Team-Rollen separat modellieren.', 'en' => 'OwnerId → User; model team roles separately.'],
                'adapt' => ['de' => 'Quota/Attainment braucht Custom/Quota-Objekt.', 'en' => 'Quota/attainment needs custom/quota object.'],
            ],
            [
                'id' => 'open-opps-per-owner',
                'example' => false,
                'label' => ['de' => 'Open Opps per Owner', 'en' => 'Open opps per owner'],
                'question' => ['de' => 'Wie viele offene Opportunities hat jeder Owner?', 'en' => 'How many open opportunities does each owner have?'],
                'formula' => 'COUNT(*) WHERE IsClosed = false GROUP BY OwnerId',
                'grain' => ['de' => 'Opportunity × Owner', 'en' => 'Opportunity × owner'],
                'dimensions' => ['owner', 'stage'],
                'fieldsUsed' => ['Opportunity.IsClosed', 'Opportunity.OwnerId', 'Opportunity.StageName'],
                'sourceHints' => ['de' => 'Workload-Sicht — nicht Revenue.', 'en' => 'Workload view — not revenue.'],
                'adapt' => ['de' => 'Optional nach Amount banden.', 'en' => 'Optionally band by amount.'],
            ],
            [
                'id' => 'lead-conversion-rate',
                'example' => false,
                'label' => ['de' => 'Lead Conversion Rate', 'en' => 'Lead conversion rate'],
                'question' => ['de' => 'Welcher Anteil Leads ist IsConverted?', 'en' => 'What share of leads is IsConverted?'],
                'formula' => 'COUNT(Lead WHERE IsConverted) / COUNT(Lead)',
                'grain' => ['de' => 'Lead', 'en' => 'Lead'],
                'dimensions' => ['source', 'owner'],
                'fieldsUsed' => ['Lead.IsConverted', 'Lead.ConvertedDate', 'Lead.LeadSource'],
                'sourceHints' => ['de' => 'ConvertedDate für Periodenfilter nutzen.', 'en' => 'Use ConvertedDate for period filters.'],
                'adapt' => ['de' => 'Disqualified Leads aus Nenner nehmen.', 'en' => 'Exclude disqualified leads from denominator.'],
            ],
            [
                'id' => 'sales-cycle-days',
                'example' => false,
                'label' => ['de' => 'Sales Cycle Days (Won)', 'en' => 'Sales cycle days (won)'],
                'question' => ['de' => 'Wie lange dauert CreatedDate → CloseDate bei Won?', 'en' => 'How long is CreatedDate → CloseDate for won?'],
                'formula' => 'AVG(Opportunity.CloseDate - Opportunity.CreatedDate) WHERE Opportunity.IsWon = true',
                'grain' => ['de' => 'Opportunity', 'en' => 'Opportunity'],
                'dimensions' => ['owner', 'stage'],
                'fieldsUsed' => ['Opportunity.CreatedDate', 'Opportunity.CloseDate', 'Opportunity.IsWon'],
                'sourceHints' => ['de' => 'Datumsdiff in Warehouse-Tagen; TZ klären.', 'en' => 'Date diff in warehouse days; clarify TZ.'],
                'adapt' => ['de' => 'Median oft robuster als AVG.', 'en' => 'Median often more robust than AVG.'],
            ],
            [
                'id' => 'won-line-total',
                'example' => false,
                'label' => ['de' => 'Won Line Total', 'en' => 'Won line total'],
                'question' => ['de' => 'Wie hoch ist die Summe OpportunityLineItem.TotalPrice für Won-Opps?', 'en' => 'What is the sum of OpportunityLineItem.TotalPrice for won opps?'],
                'formula' => 'SUM(OpportunityLineItem.TotalPrice) WHERE parent Opportunity.IsWon = true AND CloseDate IN period',
                'grain' => ['de' => 'OpportunityLineItem', 'en' => 'OpportunityLineItem'],
                'dimensions' => ['owner', 'region'],
                'fieldsUsed' => ['OpportunityLineItem.TotalPrice', 'OpportunityLineItem.OpportunityId', 'Opportunity.IsWon', 'Opportunity.CloseDate'],
                'sourceHints' => ['de' => 'Line vs. Header Amount können abweichen.', 'en' => 'Line vs header Amount can diverge.'],
                'adapt' => ['de' => 'Product2-Dimension für Mix-Analysen.', 'en' => 'Product2 dimension for mix analysis.'],
            ],
            [
                'id' => 'arr-custom',
                'example' => false,
                'label' => ['de' => 'ARR (Custom / Contract)', 'en' => 'ARR (custom / contract)'],
                'question' => ['de' => 'Wie hoch ist annualisierter Recurring Value?', 'en' => 'What is annualized recurring value?'],
                'formula' => 'SUM(annualized_recurring_value) WHERE contract_active_in_period',
                'grain' => ['de' => 'Contract / Subscription line', 'en' => 'Contract / subscription line'],
                'dimensions' => ['owner', 'region'],
                'fieldsUsed' => ['Contract / custom ARR fields', 'OpportunityLineItem (if subscription)'],
                'sourceHints' => ['de' => 'Kein Standard-ARR — Custom/CPQ/Contract prüfen.', 'en' => 'No standard ARR — check custom/CPQ/contract.'],
                'adapt' => ['de' => 'Nicht Opportunity.Amount als ARR missbrauchen.', 'en' => 'Do not misuse Opportunity.Amount as ARR.'],
            ],
        ],
    ],

    'hubspot' => [
        'pii' => [
            [
                'entity' => 'Contacts',
                'fields' => ['email', 'phone', 'mobilephone', 'firstname', 'lastname', 'address', 'city', 'zip', 'date_of_birth'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Primäre Personen-PII — Portal-Properties inventarisieren.', 'en' => 'Primary person PII — inventory portal properties.'],
            ],
            [
                'entity' => 'Companies',
                'fields' => ['address', 'city', 'zip', 'phone', 'domain'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: taggen; Curated: generalisieren (Region statt PLZ); Mart: aggregiert', 'en' => 'RAW: tag; Curated: generalize (region vs postal); Mart: aggregated'],
                'treatment' => ['de' => 'Firma oft Quasi-ID; Phone kann Personenbezug haben.', 'en' => 'Company often quasi-ID; phone may be personal.'],
            ],
            [
                'entity' => 'Deals',
                'fields' => ['dealname', 'description', 'closed_lost_reason'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext und Dealnamen können Kundenbezug haben.', 'en' => 'Free text and deal names can identify customers.'],
            ],
            [
                'entity' => 'Tickets',
                'fields' => ['subject', 'content', 'hs_ticket_priority'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Support-Freitext — ungeprüfte PII.', 'en' => 'Support free text — unchecked PII.'],
            ],
            [
                'entity' => 'Form submissions',
                'fields' => ['alle submitted fields', 'email', 'phone'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Form-Felder ≠ CRM-Schema — voll inventarisieren.', 'en' => 'Form fields ≠ CRM schema — fully inventory.'],
            ],
            [
                'entity' => 'Owners',
                'fields' => ['email', 'firstName', 'lastName'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Workforce getrennt von Kunden-Contacts.', 'en' => 'Workforce separate from customer contacts.'],
            ],
            [
                'entity' => 'Engagements / Notes',
                'fields' => ['body', 'hs_attachment_ids'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Note/Email-Bodies — Default skip oder redact.', 'en' => 'Note/email bodies — skip by default or redact.'],
            ],
            [
                'entity' => 'Custom properties',
                'fields' => ['*email*', '*phone*', 'national_id customs'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Property-Library nach PII-Mustern scannen.', 'en' => 'Scan property library for PII patterns.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'email, phone, HubSpot Contact Id, external ids, utk cookies.', 'en' => 'email, phone, HubSpot contact id, external ids, utk cookies.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'Contacts, Form Submissions, Tickets + Associations.', 'en' => 'Contacts, form submissions, tickets + associations.'],
            ],
            [
                'focus' => ['de' => 'Warehouse-Kopien', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'Sync-Targets, CDP/Activation, Marketing Extracts.', 'en' => 'Sync targets, CDP/activation, marketing extracts.'],
            ],
            [
                'focus' => ['de' => 'Marketing email bodies', 'en' => 'Marketing email bodies'],
                'notes' => ['de' => 'HTML/Bodies in Marketing-Tools — oft doppelte PII.', 'en' => 'HTML/bodies in marketing tools — often duplicate PII.'],
            ],
            [
                'focus' => ['de' => 'Engagement dumps', 'en' => 'Engagement dumps'],
                'notes' => ['de' => 'Calls/Notes/Emails „just in case“ — Volumen + PII.', 'en' => "Calls/notes/emails 'just in case' — volume + PII."],
            ],
            [
                'focus' => ['de' => 'Associations', 'en' => 'Associations'],
                'notes' => ['de' => 'Contact↔Company↔Deal — PII folgt Joins in Curated.', 'en' => 'Contact↔Company↔Deal — PII follows joins in Curated.'],
            ],
            [
                'focus' => ['de' => 'Portal custom properties', 'en' => 'Portal custom properties'],
                'notes' => ['de' => 'Jedes Portal anders — Property API inventarisieren.', 'en' => 'Every portal differs — inventory via property API.'],
            ],
            [
                'focus' => ['de' => 'Lifecycle / GDPR objects', 'en' => 'Lifecycle / GDPR objects'],
                'notes' => ['de' => 'Subscriptions, legal basis, deletion requests mitführen.', 'en' => 'Track subscriptions, legal basis, deletion requests.'],
            ],
            [
                'focus' => ['de' => 'Sandbox / test portals', 'en' => 'Sandbox / test portals'],
                'notes' => ['de' => 'Test-Portale nicht mit Prod-Marts mischen.', 'en' => 'Do not mix test portals with prod marts.'],
            ],
            [
                'focus' => ['de' => 'Integration users', 'en' => 'Integration users'],
                'notes' => ['de' => 'Private Apps sehen mehr Properties als UI-Rollen.', 'en' => 'Private apps see more properties than UI roles.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'Email HTML / marketing email bodies',
                'category' => 'system',
                'reason' => ['de' => 'Speicher und PII — selten für Warehouse-KPIs.', 'en' => 'Storage and PII — rarely needed for warehouse KPIs.'],
            ],
            [
                'name' => 'Notes / engagement free-text dumps',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturiertes PII und Rauschen.', 'en' => 'Unstructured PII and noise.'],
            ],
            [
                'name' => 'All engagements “just in case”',
                'category' => 'system',
                'reason' => ['de' => 'Volumen ohne Consumer — gezielt syncen.', 'en' => 'Volume without consumers — sync selectively.'],
            ],
            [
                'name' => 'File / attachment binaries',
                'category' => 'system',
                'reason' => ['de' => 'Binaries teuer und oft sensibel.', 'en' => 'Binaries expensive and often sensitive.'],
            ],
            [
                'name' => 'Unused marketing lists dumps',
                'category' => 'system',
                'reason' => ['de' => 'Listen-Snapshots ohne KPI-Nutzen.', 'en' => 'List snapshots without KPI value.'],
            ],
            [
                'name' => 'Workflow / automation logs (bulk)',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Noise — nicht Mart-Kern.', 'en' => 'Ops noise — not mart core.'],
            ],
            [
                'name' => 'Form raw HTML definitions',
                'category' => 'system',
                'reason' => ['de' => 'UI-Metadaten, nicht Analytics.', 'en' => 'UI metadata, not analytics.'],
            ],
            [
                'name' => 'Chat transcript archives (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensibler Freitext.', 'en' => 'Highly sensitive free text.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'Unused custom properties (bulk)',
                'reason' => ['de' => 'DSDR-Fläche und Sync-Kosten.', 'en' => 'DSDR surface and sync cost.'],
            ],
            [
                'name' => 'HTML email bodies',
                'reason' => ['de' => 'PII und Speicher.', 'en' => 'PII and storage.'],
            ],
            [
                'name' => 'Note/body free-text (default)',
                'reason' => ['de' => 'Nur mit klarem Use Case.', 'en' => 'Only with a clear use case.'],
            ],
            [
                'name' => 'Internal owner personal notes',
                'reason' => ['de' => 'Workforce-PII.', 'en' => 'Workforce PII.'],
            ],
            [
                'name' => 'Debug/test contact properties',
                'reason' => ['de' => 'Verschmutzt Prod-Marts.', 'en' => 'Pollutes prod marts.'],
            ],
            [
                'name' => 'Formula/calculated props without consumer',
                'reason' => ['de' => 'In Curated neu ableiten.', 'en' => 'Derive again in Curated.'],
            ]
        ],
    ],

    'ga4' => [
        'pii' => [
            [
                'entity' => 'Event',
                'fields' => ['user_pseudo_id', 'user_id'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'User-Keys — oft personenbezogen; Hash in Curated.', 'en' => 'User keys — often personal; hash in Curated.'],
            ],
            [
                'entity' => 'Event params',
                'fields' => ['page_location', 'page_referrer', 'page_title'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Query-Tokens, Reset-Links, Email in URLs strippen.', 'en' => 'Strip query tokens, reset links, email in URLs.'],
            ],
            [
                'entity' => 'User properties',
                'fields' => ['user_properties.* mit Email/Name', 'customer_email customs'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Custom User Properties inventarisieren.', 'en' => 'Inventory custom user properties.'],
            ],
            [
                'entity' => 'Device / Geo',
                'fields' => ['device.advertising_id', 'geo.city', 'geo.region', 'ip_overlay if present'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: taggen; Curated: generalisieren (Region statt PLZ); Mart: aggregiert', 'en' => 'RAW: tag; Curated: generalize (region vs postal); Mart: aggregated'],
                'treatment' => ['de' => 'Advertising IDs und feine Geo — Policy prüfen.', 'en' => 'Advertising IDs and fine geo — check policy.'],
            ],
            [
                'entity' => 'Ecommerce items',
                'fields' => ['item params mit PII', 'coupon codes mit Namen'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: taggen; Curated: generalisieren (Region statt PLZ); Mart: aggregiert', 'en' => 'RAW: tag; Curated: generalize (region vs postal); Mart: aggregated'],
                'treatment' => ['de' => 'Item-Params können Personenbezug tragen.', 'en' => 'Item params can carry personal reference.'],
            ],
            [
                'entity' => 'Consent / identity',
                'fields' => ['consent.*', 'non_personalized_ads'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Consent-Status steuert erlaubte Identifiers.', 'en' => 'Consent status governs allowed identifiers.'],
            ],
            [
                'entity' => 'Debug / test',
                'fields' => ['debug_mode events', 'test stream user ids'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Debug-Streams aus Prod-Marts fernhalten.', 'en' => 'Keep debug streams out of prod marts.'],
            ],
            [
                'entity' => 'Collected PII mistakes',
                'fields' => ['email in event_name/params', 'phone in params'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Implementierungsfehler — Scans + Drop-Regeln.', 'en' => 'Implementation mistakes — scans + drop rules.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'user_pseudo_id, user_id, client_id exports, CRM join keys.', 'en' => 'user_pseudo_id, user_id, client_id exports, CRM join keys.'],
            ],
            [
                'focus' => ['de' => 'Event parameter sprawl', 'en' => 'Event parameter sprawl'],
                'notes' => ['de' => 'Jedes Custom Param kann PII sein — Allowlist pflegen.', 'en' => 'Every custom param can be PII — maintain allowlists.'],
            ],
            [
                'focus' => ['de' => 'page_location leakage', 'en' => 'page_location leakage'],
                'notes' => ['de' => 'Tokens, Email, Session-IDs in Query-Strings.', 'en' => 'Tokens, email, session ids in query strings.'],
            ],
            [
                'focus' => ['de' => 'BigQuery export copies', 'en' => 'BigQuery export copies'],
                'notes' => ['de' => 'Export-Datasets, Scheduled Queries, Downstream Views.', 'en' => 'Export datasets, scheduled queries, downstream views.'],
            ],
            [
                'focus' => ['de' => 'Measurement Protocol', 'en' => 'Measurement Protocol'],
                'notes' => ['de' => 'Server-side Events können Klartext-PII injizieren.', 'en' => 'Server-side events can inject cleartext PII.'],
            ],
            [
                'focus' => ['de' => 'Cross-property joins', 'en' => 'Cross-property joins'],
                'notes' => ['de' => 'Mehrere Properties → User-Graph vergrößert Fläche.', 'en' => 'Multiple properties → user graph expands surface.'],
            ],
            [
                'focus' => ['de' => 'Audience / remarketing exports', 'en' => 'Audience / remarketing exports'],
                'notes' => ['de' => 'Audience-Snapshots außerhalb Analytics speichern PII-Keys.', 'en' => 'Audience snapshots outside analytics store PII keys.'],
            ],
            [
                'focus' => ['de' => 'DebugView / test streams', 'en' => 'DebugView / test streams'],
                'notes' => ['de' => 'Test-Traffic und echte User-IDs vermischen.', 'en' => 'Test traffic mixed with real user ids.'],
            ],
            [
                'focus' => ['de' => 'Consent mode gaps', 'en' => 'Consent mode gaps'],
                'notes' => ['de' => 'Ohne Consent trotzdem geladene Identifier droppen.', 'en' => 'Drop identifiers loaded without consent.'],
            ],
            [
                'focus' => ['de' => 'Warehouse identity resolution', 'en' => 'Warehouse identity resolution'],
                'notes' => ['de' => 'Curated-ID-Graphen verdoppeln DSDR — Policy zuerst.', 'en' => 'Curated ID graphs double DSDR — policy first.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'Debug / test stream events',
                'category' => 'system',
                'reason' => ['de' => 'Verschmutzt Prod-KPIs.', 'en' => 'Pollutes prod KPIs.'],
            ],
            [
                'name' => 'Raw page_location with tokens/PII',
                'category' => 'system',
                'reason' => ['de' => 'Secret- und PII-Leakage.', 'en' => 'Secret and PII leakage.'],
            ],
            [
                'name' => 'Unused custom events (no consumer)',
                'category' => 'system',
                'reason' => ['de' => 'Kosten und Metrik-Chaos.', 'en' => 'Cost and metric chaos.'],
            ],
            [
                'name' => 'Full event_params STRUCT dumps',
                'category' => 'system',
                'reason' => ['de' => 'Nur Allowlist-Params materialisieren.', 'en' => 'Materialize allowlisted params only.'],
            ],
            [
                'name' => 'Advertising ID exports (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Device-IDs — hohe Sensibilität.', 'en' => 'Device IDs — high sensitivity.'],
            ],
            [
                'name' => 'Raw IP / precise geo overlays',
                'category' => 'system',
                'reason' => ['de' => 'Oft unnötig und riskant.', 'en' => 'Often unnecessary and risky.'],
            ],
            [
                'name' => 'Duplicate export datasets',
                'category' => 'system',
                'reason' => ['de' => 'Mehrere Kopien derselben Events.', 'en' => 'Multiple copies of the same events.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'PII in event parameters',
                'reason' => ['de' => 'Drop/Redact vor Curated.', 'en' => 'Drop/redact before curated.'],
            ],
            [
                'name' => 'Query string tokens in page_location',
                'reason' => ['de' => 'Secrets und Reset-Links.', 'en' => 'Secrets and reset links.'],
            ],
            [
                'name' => 'Unused user_properties',
                'reason' => ['de' => 'DSDR ohne Consumer.', 'en' => 'DSDR without consumers.'],
            ],
            [
                'name' => 'Debug_mode flag rows in prod marts',
                'reason' => ['de' => 'Test-Noise.', 'en' => 'Test noise.'],
            ],
            [
                'name' => 'Raw nested STRUCT without typing',
                'reason' => ['de' => 'Schwer zu regieren — flatten allowlist.', 'en' => 'Hard to govern — flatten allowlist.'],
            ],
            [
                'name' => 'Client-side email/phone captures',
                'reason' => ['de' => 'Implementierungsfehler — blockieren.', 'en' => 'Implementation mistakes — block.'],
            ]
        ],
    ],

    'dynamics365' => [
        'pii' => [
            [
                'entity' => 'contact',
                'fields' => ['emailaddress1', 'telephone1', 'mobilephone', 'firstname', 'lastname', 'address1_line1', 'address1_city', 'address1_postalcode', 'birthdate'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Dataverse Contact — logical names taggen.', 'en' => 'Dataverse contact — tag logical names.'],
            ],
            [
                'entity' => 'lead',
                'fields' => ['emailaddress1', 'telephone1', 'firstname', 'lastname', 'address1_composite', 'companyname'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Wie Contact; Qualify-Pfad zu Contact beachten.', 'en' => 'Like contact; watch qualify path to contact.'],
            ],
            [
                'entity' => 'account',
                'fields' => ['address1_line1', 'address1_city', 'address1_postalcode', 'telephone1', 'emailaddress1'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: taggen; Curated: generalisieren (Region statt PLZ); Mart: aggregiert', 'en' => 'RAW: tag; Curated: generalize (region vs postal); Mart: aggregated'],
                'treatment' => ['de' => 'Account-Adressen Quasi-ID; Email kann Personenbezug haben.', 'en' => 'Account addresses quasi-ID; email may be personal.'],
            ],
            [
                'entity' => 'systemuser',
                'fields' => ['internalemailaddress', 'fullname', 'mobilephone', 'domainname'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Workforce getrennt von Kunden-Contact.', 'en' => 'Workforce separate from customer contact.'],
            ],
            [
                'entity' => 'incident',
                'fields' => ['title', 'description', 'emailaddress', 'customerid'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Case-Freitext und Customer-Join.', 'en' => 'Case free text and customer join.'],
            ],
            [
                'entity' => 'annotation',
                'fields' => ['subject', 'notetext', 'filename'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Notes/Attachments — Default skip Bodies.', 'en' => 'Notes/attachments — skip bodies by default.'],
            ],
            [
                'entity' => 'opportunity',
                'fields' => ['description', 'name'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Freitext und Namen können Kundenbezug haben.', 'en' => 'Free text and names can identify customers.'],
            ],
            [
                'entity' => 'activitypointer / email',
                'fields' => ['subject', 'description', 'regardingobjectid'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Activity-Bodies und Regarding-Joins.', 'en' => 'Activity bodies and regarding joins.'],
            ],
            [
                'entity' => 'Custom columns',
                'fields' => ['new_* email/phone', 'alternate keys'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Publisher-Prefix Custom Columns inventarisieren.', 'en' => 'Inventory publisher-prefix custom columns.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'emailaddress1, telephone1, contactid, alternate keys, Azure AD oid.', 'en' => 'emailaddress1, telephone1, contactid, alternate keys, Azure AD oid.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'contact, lead, account, incident + activity parties.', 'en' => 'contact, lead, account, incident + activity parties.'],
            ],
            [
                'focus' => ['de' => 'Warehouse copies', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'Data Lake/Synapselink, Export to Data Lake, Fabric mirrors.', 'en' => 'Data Lake/Synapselink, export to data lake, Fabric mirrors.'],
            ],
            [
                'focus' => ['de' => 'Audit & plugin logs', 'en' => 'Audit & plugin logs'],
                'notes' => ['de' => 'audittable, plugintracelog — alte PII-Werte.', 'en' => 'audittable, plugintracelog — old PII values.'],
            ],
            [
                'focus' => ['de' => 'Annotations & files', 'en' => 'Annotations & files'],
                'notes' => ['de' => 'annotation notetext und file attachments.', 'en' => 'annotation notetext and file attachments.'],
            ],
            [
                'focus' => ['de' => 'Polymorphic customerid', 'en' => 'Polymorphic customerid'],
                'notes' => ['de' => 'account vs contact in einer Lookup-Spalte.', 'en' => 'account vs contact in one lookup column.'],
            ],
            [
                'focus' => ['de' => 'Solutions / managed fields', 'en' => 'Solutions / managed fields'],
                'notes' => ['de' => 'Managed Attributes mit versteckter Sensibilität.', 'en' => 'Managed attributes with hidden sensitivity.'],
            ],
            [
                'focus' => ['de' => 'Integration users', 'en' => 'Integration users'],
                'notes' => ['de' => 'Application users bypass UI field security.', 'en' => 'Application users bypass UI field security.'],
            ],
            [
                'focus' => ['de' => 'Environments', 'en' => 'Environments'],
                'notes' => ['de' => 'Dev/Test/Prod Environments nicht mischen.', 'en' => 'Do not mix dev/test/prod environments.'],
            ],
            [
                'focus' => ['de' => 'Virtual tables / external', 'en' => 'Virtual tables / external'],
                'notes' => ['de' => 'Externe Quellen verdoppeln DSDR-Fläche.', 'en' => 'External sources double DSDR surface.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'annotation (bodies) / attachments',
                'category' => 'system',
                'reason' => ['de' => 'Freitext und Files — teuer und sensibel.', 'en' => 'Free text and files — expensive and sensitive.'],
            ],
            [
                'name' => 'plugintracelog / trace log',
                'category' => 'system',
                'reason' => ['de' => 'Runtime-Noise, kann PII enthalten.', 'en' => 'Runtime noise, may contain PII.'],
            ],
            [
                'name' => 'audit (vor Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Audit erst mit Retention-Policy laden.', 'en' => 'Load audit only with a retention policy.'],
            ],
            [
                'name' => 'systemform / ui metadata',
                'category' => 'system',
                'reason' => ['de' => 'Nicht analytisch.', 'en' => 'Not analytical.'],
            ],
            [
                'name' => 'asyncoperation bulk',
                'category' => 'system',
                'reason' => ['de' => 'Job-Noise.', 'en' => 'Job noise.'],
            ],
            [
                'name' => 'post / postcomment (Yammer-like)',
                'category' => 'system',
                'reason' => ['de' => 'Collaboration-PII.', 'en' => 'Collaboration PII.'],
            ],
            [
                'name' => 'mailbox / email server sync dumps',
                'category' => 'system',
                'reason' => ['de' => 'Mail-Bodies.', 'en' => 'Mail bodies.'],
            ],
            [
                'name' => 'Unused managed solution entities',
                'category' => 'system',
                'reason' => ['de' => 'Schema-Ballast.', 'en' => 'Schema ballast.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'notetext / description free-text (bulk)',
                'reason' => ['de' => 'PII — gezielt oder redact.', 'en' => 'PII — selectively or redact.'],
            ],
            [
                'name' => 'Unused custom columns (bulk sync)',
                'reason' => ['de' => 'DSDR-Fläche.', 'en' => 'DSDR surface.'],
            ],
            [
                'name' => 'UI / form XML',
                'reason' => ['de' => 'Nicht analytisch.', 'en' => 'Not analytical.'],
            ],
            [
                'name' => 'Binary file columns',
                'reason' => ['de' => 'Kosten.', 'en' => 'Cost.'],
            ],
            [
                'name' => 'Plugin debug payloads',
                'reason' => ['de' => 'Secrets/PII.', 'en' => 'Secrets/PII.'],
            ],
            [
                'name' => 'Calculated fields without consumer',
                'reason' => ['de' => 'In Curated neu ableiten.', 'en' => 'Derive again in Curated.'],
            ]
        ],
    ],

    'servicenow' => [
        'pii' => [
            [
                'entity' => 'sys_user',
                'fields' => ['email', 'name', 'phone', 'mobile_phone', 'user_name'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Mitarbeiter-PII — oft gleichzeitig Caller.', 'en' => 'Employee PII — often also caller.'],
            ],
            [
                'entity' => 'incident',
                'fields' => ['caller_id', 'short_description', 'description', 'opened_by'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Caller-Join + Freitext.', 'en' => 'Caller join + free text.'],
            ],
            [
                'entity' => 'sc_req_item / request',
                'fields' => ['requested_for', 'description', 'variables'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Request Variables können PII enthalten.', 'en' => 'Request variables can contain PII.'],
            ],
            [
                'entity' => 'sys_user_group members',
                'fields' => ['user', 'group'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Gruppenmitgliedschaften — Workforce-Graph.', 'en' => 'Group memberships — workforce graph.'],
            ],
            [
                'entity' => 'cmdb_ci related contacts',
                'fields' => ['u_contact', 'email customs'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'CI-Kontakte inventarisieren.', 'en' => 'Inventory CI contacts.'],
            ],
            [
                'entity' => 'sys_journal_field',
                'fields' => ['value', 'element_id'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Work notes / comments — Default skip bodies.', 'en' => 'Work notes/comments — skip bodies by default.'],
            ],
            [
                'entity' => 'sys_attachment',
                'fields' => ['file_name', 'content_type'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Attachments — Bodies nie default laden.', 'en' => 'Attachments — never load bodies by default.'],
            ],
            [
                'entity' => 'Custom tables',
                'fields' => ['u_* email/phone columns'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Custom Tables + Dictionary scannen.', 'en' => 'Scan custom tables + dictionary.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'email, user_name, sys_id, employee number customs, SSO name_id.', 'en' => 'email, user_name, sys_id, employee number customs, SSO name_id.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'sys_user, incident caller, requested_for, HR-Profile falls lizenziert.', 'en' => 'sys_user, incident caller, requested_for, HR profile if licensed.'],
            ],
            [
                'focus' => ['de' => 'Journal & activity', 'en' => 'Journal & activity'],
                'notes' => ['de' => 'sys_journal_field, sys_audit — historische PII.', 'en' => 'sys_journal_field, sys_audit — historical PII.'],
            ],
            [
                'focus' => ['de' => 'Attachments', 'en' => 'Attachments'],
                'notes' => ['de' => 'sys_attachment + attachment documents.', 'en' => 'sys_attachment + attachment documents.'],
            ],
            [
                'focus' => ['de' => 'Warehouse copies', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'Export Sets, Integration Hub, ETL Landing Zones.', 'en' => 'Export sets, Integration Hub, ETL landing zones.'],
            ],
            [
                'focus' => ['de' => 'Variables / catalog', 'en' => 'Variables / catalog'],
                'notes' => ['de' => 'Item Option New / Variable values — freie PII.', 'en' => 'Item option / variable values — free-form PII.'],
            ],
            [
                'focus' => ['de' => 'Domain separation', 'en' => 'Domain separation'],
                'notes' => ['de' => 'MSP-Domains — Daten nicht cross-tenant mischen.', 'en' => 'MSP domains — do not mix cross-tenant data.'],
            ],
            [
                'focus' => ['de' => 'Integration accounts', 'en' => 'Integration accounts'],
                'notes' => ['de' => 'Integration user ACLs ≠ Warehouse-Policy.', 'en' => 'Integration user ACLs ≠ warehouse policy.'],
            ],
            [
                'focus' => ['de' => 'Clone / subprod', 'en' => 'Clone / subprod'],
                'notes' => ['de' => 'Cloned instances mit Prod-PII — isolieren.', 'en' => 'Cloned instances with prod PII — isolate.'],
            ],
            [
                'focus' => ['de' => 'HR / sensitive scoped apps', 'en' => 'HR / sensitive scoped apps'],
                'notes' => ['de' => 'HRSD-Tabellen extra Policy.', 'en' => 'HRSD tables need extra policy.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'sys_attachment / attachment documents',
                'category' => 'system',
                'reason' => ['de' => 'Binaries und sensible Dateien.', 'en' => 'Binaries and sensitive files.'],
            ],
            [
                'name' => 'sys_journal_field (bodies)',
                'category' => 'system',
                'reason' => ['de' => 'Work notes — PII und Volumen.', 'en' => 'Work notes — PII and volume.'],
            ],
            [
                'name' => 'sys_audit (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Audit erst mit Retention laden.', 'en' => 'Load audit only with retention.'],
            ],
            [
                'name' => 'syslog / syslog_transaction',
                'category' => 'system',
                'reason' => ['de' => 'Platform logs.', 'en' => 'Platform logs.'],
            ],
            [
                'name' => 'sys_email (bodies)',
                'category' => 'system',
                'reason' => ['de' => 'Mail-Bodies.', 'en' => 'Mail bodies.'],
            ],
            [
                'name' => 'wf_context dumps',
                'category' => 'system',
                'reason' => ['de' => 'Workflow-Noise.', 'en' => 'Workflow noise.'],
            ],
            [
                'name' => 'Unused custom scoped tables',
                'category' => 'system',
                'reason' => ['de' => 'Schema-Ballast.', 'en' => 'Schema ballast.'],
            ],
            [
                'name' => 'Discovery / credential tables',
                'category' => 'system',
                'reason' => ['de' => 'Secrets — nie in Analytics.', 'en' => 'Secrets — never into analytics.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'Work notes / comments free-text (bulk)',
                'reason' => ['de' => 'PII — redact oder skip.', 'en' => 'PII — redact or skip.'],
            ],
            [
                'name' => 'Attachment binary content',
                'reason' => ['de' => 'Kosten und Sensibilität.', 'en' => 'Cost and sensitivity.'],
            ],
            [
                'name' => 'Unused custom dictionary columns',
                'reason' => ['de' => 'DSDR-Fläche.', 'en' => 'DSDR surface.'],
            ],
            [
                'name' => 'Credential / password fields',
                'reason' => ['de' => 'Secrets — blockieren.', 'en' => 'Secrets — block.'],
            ],
            [
                'name' => 'Email body HTML',
                'reason' => ['de' => 'PII.', 'en' => 'PII.'],
            ],
            [
                'name' => 'Debug / temp import tables',
                'reason' => ['de' => 'Prod-Marts sauber halten.', 'en' => 'Keep prod marts clean.'],
            ]
        ],
    ],

    'zendesk' => [
        'pii' => [
            [
                'entity' => 'users (end-users)',
                'fields' => ['email', 'name', 'phone', 'details', 'notes'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'End-User PII — primäre Fläche.', 'en' => 'End-user PII — primary surface.'],
            ],
            [
                'entity' => 'users (agents)',
                'fields' => ['email', 'name', 'phone'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Agenten getrennt von End-Users.', 'en' => 'Agents separate from end users.'],
            ],
            [
                'entity' => 'tickets',
                'fields' => ['subject', 'description', 'requester_id', 'submitter_id'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Ticket-Freitext + Requester-Join.', 'en' => 'Ticket free text + requester join.'],
            ],
            [
                'entity' => 'ticket comments',
                'fields' => ['body', 'html_body', 'plain_body', 'author_id'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Comments Default skip oder redact.', 'en' => 'Skip or redact comments by default.'],
            ],
            [
                'entity' => 'ticket attachments',
                'fields' => ['file_name', 'content_url'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Attachments nicht default laden.', 'en' => 'Do not load attachments by default.'],
            ],
            [
                'entity' => 'organizations',
                'fields' => ['name', 'details', 'domain_names'],
                'classification' => 'quasi',
                'stage' => ['de' => 'RAW: taggen; Curated: generalisieren (Region statt PLZ); Mart: aggregiert', 'en' => 'RAW: tag; Curated: generalize (region vs postal); Mart: aggregated'],
                'treatment' => ['de' => 'Org-Details können Personenbezug haben.', 'en' => 'Org details can be personal.'],
            ],
            [
                'entity' => 'side conversations',
                'fields' => ['subject', 'participants', 'messages'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Side Conversations — zweite PII-Fläche.', 'en' => 'Side conversations — second PII surface.'],
            ],
            [
                'entity' => 'custom fields',
                'fields' => ['ticket/user custom fields mit Email/Phone'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Custom Field Definitions inventarisieren.', 'en' => 'Inventory custom field definitions.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'email, external_id, user id, phone, social identities.', 'en' => 'email, external_id, user id, phone, social identities.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'users, tickets, comments, organizations.', 'en' => 'users, tickets, comments, organizations.'],
            ],
            [
                'focus' => ['de' => 'Comments & attachments', 'en' => 'Comments & attachments'],
                'notes' => ['de' => 'Größte Freitext-/File-Fläche.', 'en' => 'Largest free-text/file surface.'],
            ],
            [
                'focus' => ['de' => 'Warehouse copies', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'Export API dumps, Sunshine, BI connectors.', 'en' => 'Export API dumps, Sunshine, BI connectors.'],
            ],
            [
                'focus' => ['de' => 'Side conversations', 'en' => 'Side conversations'],
                'notes' => ['de' => 'Externe Teilnehmer und Mail-Threads.', 'en' => 'External participants and mail threads.'],
            ],
            [
                'focus' => ['de' => 'Help Center identities', 'en' => 'Help Center identities'],
                'notes' => ['de' => 'End-user accounts über HC und Messaging.', 'en' => 'End-user accounts via HC and messaging.'],
            ],
            [
                'focus' => ['de' => 'Deleted / redacted tickets', 'en' => 'Deleted / redacted tickets'],
                'notes' => ['de' => 'Redaction muss Warehouse mitziehen.', 'en' => 'Redaction must propagate to warehouse.'],
            ],
            [
                'focus' => ['de' => 'Integration tokens', 'en' => 'Integration tokens'],
                'notes' => ['de' => 'Apps speichern oft Email in eigenen Stores.', 'en' => 'Apps often store email in their own stores.'],
            ],
            [
                'focus' => ['de' => 'Sandbox', 'en' => 'Sandbox'],
                'notes' => ['de' => 'Sandbox-Tickets nicht in Prod-Marts.', 'en' => 'Sandbox tickets not in prod marts.'],
            ],
            [
                'focus' => ['de' => 'Satisfaction comments', 'en' => 'Satisfaction comments'],
                'notes' => ['de' => 'CSAT-Freitext mit PII.', 'en' => 'CSAT free text with PII.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'ticket comment bodies (default)',
                'category' => 'system',
                'reason' => ['de' => 'Freitext-PII — nur mit Use Case.', 'en' => 'Free-text PII — only with a use case.'],
            ],
            [
                'name' => 'ticket attachments / binaries',
                'category' => 'system',
                'reason' => ['de' => 'Files — teuer und sensibel.', 'en' => 'Files — expensive and sensitive.'],
            ],
            [
                'name' => 'side conversation message bodies',
                'category' => 'system',
                'reason' => ['de' => 'Zusätzliche Mail-PII.', 'en' => 'Additional mail PII.'],
            ],
            [
                'name' => 'audit events bulk (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Noise — gezielt.', 'en' => 'Noise — selectively.'],
            ],
            [
                'name' => 'chat / messaging transcripts (ohne Bedarf)',
                'category' => 'system',
                'reason' => ['de' => 'Hochsensibel.', 'en' => 'Highly sensitive.'],
            ],
            [
                'name' => 'unused custom object records',
                'category' => 'system',
                'reason' => ['de' => 'Schema-Ballast.', 'en' => 'Schema ballast.'],
            ],
            [
                'name' => 'macro / trigger definition dumps',
                'category' => 'system',
                'reason' => ['de' => 'Ops-Metadaten.', 'en' => 'Ops metadata.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'html_body / plain_body comments',
                'reason' => ['de' => 'PII — redact oder skip.', 'en' => 'PII — redact or skip.'],
            ],
            [
                'name' => 'Attachment content',
                'reason' => ['de' => 'Binaries.', 'en' => 'Binaries.'],
            ],
            [
                'name' => 'Unused user/ticket custom fields',
                'reason' => ['de' => 'DSDR-Fläche.', 'en' => 'DSDR surface.'],
            ],
            [
                'name' => 'Agent private notes (ohne Bedarf)',
                'reason' => ['de' => 'Workforce-Inhalte.', 'en' => 'Workforce content.'],
            ],
            [
                'name' => 'Raw satisfaction comment text',
                'reason' => ['de' => 'PII in Feedback.', 'en' => 'PII in feedback.'],
            ],
            [
                'name' => 'Debug/test end users',
                'reason' => ['de' => 'Prod-Marts sauber halten.', 'en' => 'Keep prod marts clean.'],
            ]
        ],
    ],

    'shopify' => [
        'pii' => [
            [
                'entity' => 'customers',
                'fields' => ['email', 'first_name', 'last_name', 'phone', 'note'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Kundenstamm — primäre PII.', 'en' => 'Customer master — primary PII.'],
            ],
            [
                'entity' => 'customer_address',
                'fields' => ['address1', 'address2', 'city', 'zip', 'phone', 'name'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Adressen = direkte Identifikatoren.', 'en' => 'Addresses = direct identifiers.'],
            ],
            [
                'entity' => 'orders',
                'fields' => ['email', 'phone', 'note', 'note_attributes', 'billing_address', 'shipping_address'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Order trägt Customer-PII und Notes.', 'en' => 'Order carries customer PII and notes.'],
            ],
            [
                'entity' => 'draft_orders',
                'fields' => ['email', 'note', 'shipping_address', 'billing_address'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Drafts oft vergessen in DSDR.', 'en' => 'Drafts often missed in DSDR.'],
            ],
            [
                'entity' => 'checkouts / abandoned',
                'fields' => ['email', 'phone', 'shipping_address', 'token'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Checkout Tokens und PII — Retention kurz halten.', 'en' => 'Checkout tokens and PII — keep retention short.'],
            ],
            [
                'entity' => 'metafields',
                'fields' => ['customer/order metafields mit PII'],
                'classification' => 'sensitive',
                'stage' => ['de' => 'RAW: selten laden; Curated: drop/redact; Mart: nie', 'en' => 'RAW: rarely load; Curated: drop/redact; Mart: never'],
                'treatment' => ['de' => 'Metafields scannen — beliebige Payloads.', 'en' => 'Scan metafields — arbitrary payloads.'],
            ],
            [
                'entity' => 'staff / users',
                'fields' => ['email', 'first_name', 'last_name'],
                'classification' => 'workforce',
                'stage' => ['de' => 'RAW: Workforce-Policy; Curated: getrennt von Kunden-PII; Mart: aggregates only', 'en' => 'RAW: workforce policy; Curated: separate from customer PII; Mart: aggregates only'],
                'treatment' => ['de' => 'Shop-Staff getrennt von Kunden.', 'en' => 'Shop staff separate from customers.'],
            ],
            [
                'entity' => 'gift_cards / recipients',
                'fields' => ['recipient email/name customs'],
                'classification' => 'direct',
                'stage' => ['de' => 'RAW: Zugriff einschränken; Curated: hashen/tokenisieren; Mart: kein Klartext', 'en' => 'RAW: restrict access; Curated: hash/tokenize; Mart: no cleartext'],
                'treatment' => ['de' => 'Gift-Card-Empfänger inventarisieren.', 'en' => 'Inventory gift-card recipients.'],
            ]
        ],
        'dsdr' => [
            [
                'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                'notes' => ['de' => 'email, phone, customer id, checkout token, order name.', 'en' => 'email, phone, customer id, checkout token, order name.'],
            ],
            [
                'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                'notes' => ['de' => 'customers, addresses, orders, draft orders, checkouts.', 'en' => 'customers, addresses, orders, draft orders, checkouts.'],
            ],
            [
                'focus' => ['de' => 'Note attributes & metafields', 'en' => 'Note attributes & metafields'],
                'notes' => ['de' => 'Freie Key/Value-PII außerhalb Standardfeldern.', 'en' => 'Free key/value PII outside standard fields.'],
            ],
            [
                'focus' => ['de' => 'Warehouse copies', 'en' => 'Warehouse copies'],
                'notes' => ['de' => 'ShopifyQL exports, ETL, CDP, marketing syncs.', 'en' => 'ShopifyQL exports, ETL, CDP, marketing syncs.'],
            ],
            [
                'focus' => ['de' => 'Apps / private apps', 'en' => 'Apps / private apps'],
                'notes' => ['de' => 'Apps speichern Order/Customer-Kopien.', 'en' => 'Apps store order/customer copies.'],
            ],
            [
                'focus' => ['de' => 'POS / offline', 'en' => 'POS / offline'],
                'notes' => ['de' => 'POS-Kundenprofile und Receipts.', 'en' => 'POS customer profiles and receipts.'],
            ],
            [
                'focus' => ['de' => 'Markets / multi-store', 'en' => 'Markets / multi-store'],
                'notes' => ['de' => 'Mehrere Shops → doppelte Customer-Fläche.', 'en' => 'Multiple shops → duplicate customer surface.'],
            ],
            [
                'focus' => ['de' => 'Deleted customers', 'en' => 'Deleted customers'],
                'notes' => ['de' => 'GDPR deletion muss Landing/Curated mitziehen.', 'en' => 'GDPR deletion must hit landing/curated.'],
            ],
            [
                'focus' => ['de' => 'Checkout tokens', 'en' => 'Checkout tokens'],
                'notes' => ['de' => 'Abandoned checkouts mit Email — kurze Retention.', 'en' => 'Abandoned checkouts with email — short retention.'],
            ],
            [
                'focus' => ['de' => 'Fulfillment partners', 'en' => 'Fulfillment partners'],
                'notes' => ['de' => 'Shipping-Exports an 3PLs verdoppeln Adressen.', 'en' => 'Shipping exports to 3PLs duplicate addresses.'],
            ]
        ],
        'skipTables' => [
            [
                'name' => 'checkout / abandoned checkout dumps (lang)',
                'category' => 'system',
                'reason' => ['de' => 'PII + Tokens — kurze Retention oder skip.', 'en' => 'PII + tokens — short retention or skip.'],
            ],
            [
                'name' => 'draft_orders ohne Bedarf',
                'category' => 'system',
                'reason' => ['de' => 'Oft doppelte Customer-PII.', 'en' => 'Often duplicate customer PII.'],
            ],
            [
                'name' => 'order note_attributes bulk',
                'category' => 'system',
                'reason' => ['de' => 'Unstrukturierte PII.', 'en' => 'Unstructured PII.'],
            ],
            [
                'name' => 'metafields without allowlist',
                'category' => 'system',
                'reason' => ['de' => 'Beliebige Payloads.', 'en' => 'Arbitrary payloads.'],
            ],
            [
                'name' => 'gift card full codes',
                'category' => 'system',
                'reason' => ['de' => 'Secrets — nie in Analytics.', 'en' => 'Secrets — never into analytics.'],
            ],
            [
                'name' => 'payment instrument details',
                'category' => 'system',
                'reason' => ['de' => 'PCI — out of scope.', 'en' => 'PCI — out of scope.'],
            ],
            [
                'name' => 'theme / asset files',
                'category' => 'system',
                'reason' => ['de' => 'Nicht analytisch.', 'en' => 'Not analytical.'],
            ],
            [
                'name' => 'app install / webhook secret logs',
                'category' => 'system',
                'reason' => ['de' => 'Secrets.', 'en' => 'Secrets.'],
            ]
        ],
        'skip' => [
            [
                'name' => 'Customer/order notes free-text (bulk)',
                'reason' => ['de' => 'PII — gezielt.', 'en' => 'PII — selectively.'],
            ],
            [
                'name' => 'Metafields without classification',
                'reason' => ['de' => 'Allowlist oder skip.', 'en' => 'Allowlist or skip.'],
            ],
            [
                'name' => 'Checkout tokens long retention',
                'reason' => ['de' => 'Identity leakage.', 'en' => 'Identity leakage.'],
            ],
            [
                'name' => 'Gift card codes',
                'reason' => ['de' => 'Secrets.', 'en' => 'Secrets.'],
            ],
            [
                'name' => 'Payment PANs / CVV (never)',
                'reason' => ['de' => 'PCI — blockieren.', 'en' => 'PCI — block.'],
            ],
            [
                'name' => 'Unused custom attributes',
                'reason' => ['de' => 'DSDR-Fläche.', 'en' => 'DSDR surface.'],
            ]
        ],
    ],

];
