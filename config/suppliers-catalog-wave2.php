<?php

/**
 * Wave 2 supplier library entries — ERP/HCM (full template depth).
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $erpTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
        'powerbi-dax-generator',
        'qlik-set-analysis-generator',
    ];

    $hcmTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'sap-s4hana',
            'domain' => 'erp',
            'order' => 70,
            'label' => ['de' => 'SAP S/4HANA', 'en' => 'SAP S/4HANA'],
            'shortPurpose' => [
                'de' => 'ERP-Kern: Material, Kunden, Aufträge, FI — Load, PII und Finance/Logistics-Measures.',
                'en' => 'ERP core: material, customers, orders, FI — load, PII and finance/logistics measures.',
            ],
            'entities' => [
                [
                    'id' => 'material',
                    'label' => ['de' => 'Material (MARA)', 'en' => 'Material (MARA)'],
                    'description' => [
                        'de' => 'Materialstamm — Produkt-/SKU-Dimension für Sales und Inventory.',
                        'en' => 'Material master — product/SKU dimension for sales and inventory.',
                    ],
                    'grain' => ['de' => 'Ein Material', 'en' => 'One material'],
                    'role' => ['de' => 'Produkt-Dimension', 'en' => 'Product dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer (KNA1)', 'en' => 'Customer (KNA1)'],
                    'description' => [
                        'de' => 'Debitorenstamm — Dimension und PII für Kontakt-/Adressfelder.',
                        'en' => 'Customer master — dimension and PII for contact/address fields.',
                    ],
                    'grain' => ['de' => 'Ein Kunde', 'en' => 'One customer'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'vendor',
                    'label' => ['de' => 'Vendor (LFA1)', 'en' => 'Vendor (LFA1)'],
                    'description' => [
                        'de' => 'Kreditorenstamm — Beschaffungs-Dimension mit Kontakt-PII.',
                        'en' => 'Vendor master — procurement dimension with contact PII.',
                    ],
                    'grain' => ['de' => 'Ein Lieferant', 'en' => 'One vendor'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sales_order',
                    'label' => ['de' => 'Sales Order (VBAK)', 'en' => 'Sales order (VBAK)'],
                    'description' => [
                        'de' => 'Vertriebsauftrags-Kopf — Fact für Backlog, Pipeline und Order-KPIs.',
                        'en' => 'Sales order header — fact for backlog, pipeline and order KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Auftrag', 'en' => 'One order'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'sales_order_item',
                    'label' => ['de' => 'Sales Order Item (VBAP)', 'en' => 'Sales order item (VBAP)'],
                    'description' => [
                        'de' => 'Auftragspositionen — feiner Grain für Produktmix und Mengen.',
                        'en' => 'Order line items — finer grain for product mix and quantities.',
                    ],
                    'grain' => ['de' => 'Eine Position', 'en' => 'One line item'],
                    'role' => ['de' => 'Fact (fein)', 'en' => 'Fact (fine)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'fi_document',
                    'label' => ['de' => 'FI Document (BKPF/BSEG)', 'en' => 'FI document (BKPF/BSEG)'],
                    'description' => [
                        'de' => 'Buchungsbelege — Revenue, AR/AP und GL-Analytics.',
                        'en' => 'Accounting documents — revenue, AR/AP and GL analytics.',
                    ],
                    'grain' => ['de' => 'Ein Beleg / Belegzeile', 'en' => 'One document / line item'],
                    'role' => ['de' => 'Finance-Fact', 'en' => 'Finance fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Cost Center (CSKS)', 'en' => 'Cost center (CSKS)'],
                    'description' => [
                        'de' => 'Kostenstellen — Controlling-Dimension für Kosten und Allokation.',
                        'en' => 'Cost centers — controlling dimension for cost and allocation.',
                    ],
                    'grain' => ['de' => 'Eine Kostenstelle', 'en' => 'One cost center'],
                    'role' => ['de' => 'Controlling-Dimension', 'en' => 'Controlling dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'company_code',
                    'label' => ['de' => 'Company Code (T001)', 'en' => 'Company code (T001)'],
                    'description' => [
                        'de' => 'Buchungskreis — Legal-Entity- und Währungs-Wurzel.',
                        'en' => 'Company code — legal entity and currency root.',
                    ],
                    'grain' => ['de' => 'Ein Buchungskreis', 'en' => 'One company code'],
                    'role' => ['de' => 'Legal-Entity-Dimension', 'en' => 'Legal entity dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'profit_center',
                    'label' => ['de' => 'Profit Center (CEPC)', 'en' => 'Profit center (CEPC)'],
                    'description' => [
                        'de' => 'Profit Center — optionale Segment-Dimension für P&L.',
                        'en' => 'Profit center — optional segment dimension for P&L.',
                    ],
                    'grain' => ['de' => 'Ein Profit Center', 'en' => 'One profit center'],
                    'role' => ['de' => 'Segment-Dimension', 'en' => 'Segment dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Material', 'name' => 'matnr', 'role' => 'key', 'why' => ['de' => 'Material-Join', 'en' => 'Material join']],
                ['entity' => 'Material', 'name' => 'mtart', 'role' => 'dimension', 'why' => ['de' => 'Materialtyp', 'en' => 'Material type']],
                ['entity' => 'Material', 'name' => 'matkl', 'role' => 'dimension', 'why' => ['de' => 'Materialgruppe', 'en' => 'Material group']],
                ['entity' => 'Material', 'name' => 'meins', 'role' => 'dimension', 'why' => ['de' => 'Basismengeneinheit', 'en' => 'Base unit of measure']],
                ['entity' => 'Customer', 'name' => 'kunnr', 'role' => 'key', 'why' => ['de' => 'Kunden-Join', 'en' => 'Customer join']],
                ['entity' => 'Customer', 'name' => 'name1', 'role' => 'dimension', 'why' => ['de' => 'Kunden-Label', 'en' => 'Customer label']],
                ['entity' => 'Customer', 'name' => 'land1', 'role' => 'dimension', 'why' => ['de' => 'Land', 'en' => 'Country']],
                ['entity' => 'Customer', 'name' => 'smtp_addr', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Customer', 'name' => 'telf1', 'role' => 'pii', 'why' => ['de' => 'Telefon / PII', 'en' => 'Phone / PII']],
                ['entity' => 'Vendor', 'name' => 'lifnr', 'role' => 'key', 'why' => ['de' => 'Lieferanten-Join', 'en' => 'Vendor join']],
                ['entity' => 'Vendor', 'name' => 'name1', 'role' => 'dimension', 'why' => ['de' => 'Lieferanten-Label', 'en' => 'Vendor label']],
                ['entity' => 'Vendor', 'name' => 'smtp_addr', 'role' => 'pii', 'why' => ['de' => 'Kontakt-E-Mail / PII', 'en' => 'Contact email / PII']],
                ['entity' => 'Sales Order', 'name' => 'vbeln', 'role' => 'key', 'why' => ['de' => 'Auftrags-Join', 'en' => 'Order join']],
                ['entity' => 'Sales Order', 'name' => 'netwr', 'role' => 'measure', 'why' => ['de' => 'Auftragswert', 'en' => 'Order value']],
                ['entity' => 'Sales Order', 'name' => 'audat', 'role' => 'measure', 'why' => ['de' => 'Auftragsdatum / Perioden-Grain', 'en' => 'Order date / period grain']],
                ['entity' => 'Sales Order', 'name' => 'gbstk', 'role' => 'dimension', 'why' => ['de' => 'Gesamt-Status', 'en' => 'Overall status']],
                ['entity' => 'Sales Order', 'name' => 'kunnr', 'role' => 'dimension', 'why' => ['de' => 'Kunden-Join', 'en' => 'Customer join']],
                ['entity' => 'Sales Order Item', 'name' => 'vbeln', 'role' => 'key', 'why' => ['de' => 'Parent-Join', 'en' => 'Parent join']],
                ['entity' => 'Sales Order Item', 'name' => 'posnr', 'role' => 'key', 'why' => ['de' => 'Positions-Key', 'en' => 'Line key']],
                ['entity' => 'Sales Order Item', 'name' => 'matnr', 'role' => 'dimension', 'why' => ['de' => 'Material', 'en' => 'Material']],
                ['entity' => 'Sales Order Item', 'name' => 'kwmeng', 'role' => 'measure', 'why' => ['de' => 'Bestellmenge', 'en' => 'Order quantity']],
                ['entity' => 'Sales Order Item', 'name' => 'netwr', 'role' => 'measure', 'why' => ['de' => 'Positionswert', 'en' => 'Line value']],
                ['entity' => 'FI Document', 'name' => 'belnr', 'role' => 'key', 'why' => ['de' => 'Beleg-Join', 'en' => 'Document join']],
                ['entity' => 'FI Document', 'name' => 'dmbtr', 'role' => 'measure', 'why' => ['de' => 'Betrag in Hauswährung', 'en' => 'Amount in local currency']],
                ['entity' => 'FI Document', 'name' => 'budat', 'role' => 'measure', 'why' => ['de' => 'Buchungsdatum', 'en' => 'Posting date']],
                ['entity' => 'FI Document', 'name' => 'bukrs', 'role' => 'dimension', 'why' => ['de' => 'Buchungskreis', 'en' => 'Company code']],
                ['entity' => 'FI Document', 'name' => 'kostl', 'role' => 'dimension', 'why' => ['de' => 'Kostenstelle', 'en' => 'Cost center']],
                ['entity' => 'Cost Center', 'name' => 'kostl', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Cost Center', 'name' => 'kokrs', 'role' => 'dimension', 'why' => ['de' => 'Controlling Area', 'en' => 'Controlling area']],
                ['entity' => 'Company Code', 'name' => 'bukrs', 'role' => 'key', 'why' => ['de' => 'Legal-Entity-Join', 'en' => 'Legal entity join']],
                ['entity' => 'Company Code', 'name' => 'waers', 'role' => 'measure', 'why' => ['de' => 'Hauswährung', 'en' => 'Local currency']],
            ],
            'skipTables' => [
                [
                    'name' => 'Change documents (CDHDR/CDPOS)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Änderungsprotokolle — hohes Volumen, selten KPI-relevant.',
                        'en' => 'Change documents — high volume, rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'IDoc archives / EDIDC / EDID4',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Integrations-Payloads — technisches Rauschen, kein Mart-Kern.',
                        'en' => 'Integration payloads — technical noise, not mart core.',
                    ],
                ],
                [
                    'name' => 'Application logs (SLG1 / BAL)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Runtime- und Debug-Logs — nicht für Business-KPIs.',
                        'en' => 'Runtime and debug logs — not for business KPIs.',
                    ],
                ],
                [
                    'name' => 'Spool / print queue tables',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Druck-Spool — kein Analytics-Load.',
                        'en' => 'Print spool — not an analytics load.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Change document tables (bulk)', 'reason' => ['de' => 'Hohes Volumen ohne KPI-Nutzen', 'en' => 'High volume without KPI value']],
                ['name' => 'IDoc payload archives', 'reason' => ['de' => 'Technisches Rauschen', 'en' => 'Technical noise']],
                ['name' => 'Application / job logs', 'reason' => ['de' => 'Nicht analytisch', 'en' => 'Not analytical']],
                ['name' => 'Unused Z-fields (bulk sync all)', 'reason' => ['de' => 'Vergrößert DSDR-Fläche', 'en' => 'Expands DSDR surface']],
            ],
            'dimensions' => [
                [
                    'id' => 'company_code',
                    'label' => ['de' => 'Company Code', 'en' => 'Company code'],
                    'grain' => ['de' => 'Buchungskreis', 'en' => 'Company code'],
                    'notes' => [
                        'de' => 'Legal Entity und Hauswährung über bukrs verbinden.',
                        'en' => 'Link legal entity and local currency via bukrs.',
                    ],
                ],
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Cost Center', 'en' => 'Cost center'],
                    'grain' => ['de' => 'Kostenstelle', 'en' => 'Cost center'],
                    'notes' => [
                        'de' => 'Controlling Area (kokrs) vor dem Mart harmonisieren.',
                        'en' => 'Harmonize controlling area (kokrs) before the mart.',
                    ],
                ],
                [
                    'id' => 'profit_center',
                    'label' => ['de' => 'Profit Center', 'en' => 'Profit center'],
                    'grain' => ['de' => 'Profit Center', 'en' => 'Profit center'],
                    'notes' => [
                        'de' => 'Nur laden wenn Segment-P&L gebraucht wird.',
                        'en' => 'Load only when segment P&L is needed.',
                    ],
                ],
                [
                    'id' => 'material_group',
                    'label' => ['de' => 'Material Group', 'en' => 'Material group'],
                    'grain' => ['de' => 'matkl / Warengruppe', 'en' => 'matkl / material group'],
                    'notes' => [
                        'de' => 'Materialgruppen-Hierarchie aus T023/T023T ableiten.',
                        'en' => 'Derive material group hierarchy from T023/T023T.',
                    ],
                ],
                [
                    'id' => 'country',
                    'label' => ['de' => 'Country', 'en' => 'Country'],
                    'grain' => ['de' => 'land1 / Region', 'en' => 'land1 / region'],
                    'notes' => [
                        'de' => 'Kunden- vs. Liefer-Land bewusst wählen.',
                        'en' => 'Consciously choose customer vs ship-to country.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['smtp_addr', 'telf1', 'telf2', 'stras', 'name1'],
                    'treatment' => [
                        'de' => 'Debitoren-Kontakt-PII — taggen, RAW einschränken.',
                        'en' => 'Customer contact PII — tag, restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Vendor',
                    'fields' => ['smtp_addr', 'telf1', 'stras', 'name1'],
                    'treatment' => [
                        'de' => 'Lieferanten-Kontakt-PII — eigene Policy vs. Kunden-PII.',
                        'en' => 'Vendor contact PII — separate policy from customer PII.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'kunnr, lifnr, matnr, External Id (Z-Felder), E-Mail/Telefon.',
                        'en' => 'kunnr, lifnr, matnr, external id (Z-fields), email/phone.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Vendor + CRM-/Warehouse-Kopien und Aktivierungs-Exports.',
                        'en' => 'Customer, vendor + CRM/warehouse copies and activation exports.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'billing-revenue',
                    'example' => true,
                    'label' => ['de' => 'Billing Revenue', 'en' => 'Billing revenue'],
                    'question' => [
                        'de' => 'Wie viel Umsatz wurde in der Periode fakturiert?',
                        'en' => 'How much revenue was billed in the period?',
                    ],
                    'formula' => 'SUM(dmbtr) FROM fi_document WHERE account_type = revenue AND budat IN period',
                    'grain' => ['de' => 'FI-Belegzeile', 'en' => 'FI document line'],
                    'dimensions' => ['company_code', 'cost_center', 'profit_center', 'country'],
                    'fieldsUsed' => ['FI Document.dmbtr', 'FI Document.budat', 'FI Document.bukrs'],
                    'sourceHints' => [
                        'de' => 'Revenue-Konten über SKA1/SKB1 mappen — SD-Billing (VBRK/VBRP) als Alternative prüfen.',
                        'en' => 'Map revenue accounts via SKA1/SKB1 — check SD billing (VBRK/VBRP) as alternative.',
                    ],
                    'adapt' => [
                        'de' => 'Netto vs. Brutto, Währungsumrechnung und Storno-Belege klären.',
                        'en' => 'Clarify net vs gross, currency conversion and reversal documents.',
                    ],
                ],
                [
                    'id' => 'open-order-backlog',
                    'example' => true,
                    'label' => ['de' => 'Open Order Backlog', 'en' => 'Open order backlog'],
                    'question' => [
                        'de' => 'Wie viel offener Auftragswert steht noch aus?',
                        'en' => 'How much open order value is still outstanding?',
                    ],
                    'formula' => 'SUM(netwr) FROM sales_order_item WHERE open_qty > 0',
                    'grain' => ['de' => 'Auftragsposition', 'en' => 'Order line item'],
                    'dimensions' => ['company_code', 'material_group', 'country'],
                    'fieldsUsed' => ['Sales Order Item.netwr', 'Sales Order Item.kwmeng', 'Sales Order.gbstk'],
                    'sourceHints' => [
                        'de' => 'Offene Menge aus VBAP/VBEP ableiten — gelieferte vs. bestellte Menge.',
                        'en' => 'Derive open qty from VBAP/VBEP — delivered vs ordered quantity.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot vs. Flow und Währung festlegen.',
                        'en' => 'Lock snapshot vs flow and currency handling.',
                    ],
                ],
                [
                    'id' => 'order-intake',
                    'example' => false,
                    'label' => ['de' => 'Order Intake', 'en' => 'Order intake'],
                    'question' => [
                        'de' => 'Wie viel Auftragswert wurde in der Periode neu erfasst?',
                        'en' => 'How much order value was newly captured in the period?',
                    ],
                    'formula' => 'SUM(netwr) FROM sales_order WHERE audat IN period',
                    'grain' => ['de' => 'Auftrag', 'en' => 'Order'],
                    'dimensions' => ['company_code', 'country', 'material_group'],
                    'fieldsUsed' => ['Sales Order.netwr', 'Sales Order.audat'],
                    'sourceHints' => [
                        'de' => 'audat als Periodenfilter — Storno-Aufträge ausschließen.',
                        'en' => 'audat as period filter — exclude cancelled orders.',
                    ],
                    'adapt' => [
                        'de' => 'Intercompany-Aufträge und Test-Mandanten filtern.',
                        'en' => 'Filter intercompany orders and test clients.',
                    ],
                ],
                [
                    'id' => 'cost-center-spend',
                    'example' => false,
                    'label' => ['de' => 'Cost Center Spend', 'en' => 'Cost center spend'],
                    'question' => [
                        'de' => 'Wie viel Kosten wurden pro Kostenstelle gebucht?',
                        'en' => 'How much cost was posted per cost center?',
                    ],
                    'formula' => 'SUM(dmbtr) FROM fi_document WHERE kostl IS NOT NULL AND budat IN period',
                    'grain' => ['de' => 'FI-Belegzeile', 'en' => 'FI document line'],
                    'dimensions' => ['cost_center', 'company_code', 'profit_center'],
                    'fieldsUsed' => ['FI Document.dmbtr', 'FI Document.kostl', 'FI Document.budat'],
                    'sourceHints' => [
                        'de' => 'Expense-Konten über Kontenplan mappen.',
                        'en' => 'Map expense accounts via chart of accounts.',
                    ],
                    'adapt' => [
                        'de' => 'Plan vs. Actual und Allokationsregeln separat definieren.',
                        'en' => 'Define plan vs actual and allocation rules separately.',
                    ],
                ],
                [
                    'id' => 'units-ordered',
                    'example' => false,
                    'label' => ['de' => 'Units Ordered', 'en' => 'Units ordered'],
                    'question' => [
                        'de' => 'Wie viele Einheiten wurden bestellt?',
                        'en' => 'How many units were ordered?',
                    ],
                    'formula' => 'SUM(kwmeng) FROM sales_order_item JOIN sales_order WHERE audat IN period',
                    'grain' => ['de' => 'Auftragsposition', 'en' => 'Order line item'],
                    'dimensions' => ['material_group', 'company_code', 'country'],
                    'fieldsUsed' => ['Sales Order Item.kwmeng', 'Sales Order.audat'],
                    'sourceHints' => [
                        'de' => 'Mengeneinheit (meins) vor Aggregation harmonisieren.',
                        'en' => 'Harmonize unit of measure (meins) before aggregation.',
                    ],
                    'adapt' => [
                        'de' => 'Retouren und Storno-Positionen ausschließen.',
                        'en' => 'Exclude returns and cancelled line items.',
                    ],
                ],
            ],
            'tools' => $erpTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'netsuite',
            'domain' => 'erp',
            'order' => 80,
            'label' => ['de' => 'Oracle NetSuite', 'en' => 'Oracle NetSuite'],
            'shortPurpose' => [
                'de' => 'Cloud-ERP: Customer, Vendor, Item, SalesOrder, Invoice — SuiteQL/REST, PII und Finance-KPIs.',
                'en' => 'Cloud ERP: customer, vendor, item, sales order, invoice — SuiteQL/REST, PII and finance KPIs.',
            ],
            'entities' => [
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'Debitorenstamm (Customer) — entity/internalid als Join, entityid als Business-Key.',
                        'en' => 'Customer master — entity/internalid as join, entityid as business key.',
                    ],
                    'grain' => ['de' => 'Ein Customer-Datensatz', 'en' => 'One customer record'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'vendor',
                    'label' => ['de' => 'Vendor', 'en' => 'Vendor'],
                    'description' => [
                        'de' => 'Kreditorenstamm (Vendor) — entity, companyname und Kontakt-PII.',
                        'en' => 'Vendor master — entity, companyname and contact PII.',
                    ],
                    'grain' => ['de' => 'Ein Vendor', 'en' => 'One vendor'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'item',
                    'label' => ['de' => 'Item', 'en' => 'Item'],
                    'description' => [
                        'de' => 'Artikelstamm (Item) — itemid, itemtype und subsidiary für Produkt-Dimension.',
                        'en' => 'Item master — itemid, itemtype and subsidiary for product dimension.',
                    ],
                    'grain' => ['de' => 'Ein Item', 'en' => 'One item'],
                    'role' => ['de' => 'Produkt-Dimension', 'en' => 'Product dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'salesorder',
                    'label' => ['de' => 'Sales Order', 'en' => 'Sales order'],
                    'description' => [
                        'de' => 'Vertriebsauftrag (SalesOrder) — trandate, entity, status für Backlog und Intake.',
                        'en' => 'Sales order — trandate, entity, status for backlog and intake.',
                    ],
                    'grain' => ['de' => 'Ein SalesOrder', 'en' => 'One sales order'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'salesorder_line',
                    'label' => ['de' => 'Sales Order Line', 'en' => 'Sales order line'],
                    'description' => [
                        'de' => 'Auftragspositionen (transactionline) — quantity, rate, item für feinen Grain.',
                        'en' => 'Order lines (transactionline) — quantity, rate, item for fine grain.',
                    ],
                    'grain' => ['de' => 'Eine transactionline', 'en' => 'One transaction line'],
                    'role' => ['de' => 'Fact (fein)', 'en' => 'Fact (fine)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'invoice',
                    'label' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'description' => [
                        'de' => 'Rechnung (Invoice) — amount, trandate, status für Billed Revenue und AR.',
                        'en' => 'Invoice — amount, trandate, status for billed revenue and AR.',
                    ],
                    'grain' => ['de' => 'Eine Invoice / Zeile', 'en' => 'One invoice / line'],
                    'role' => ['de' => 'Finance-Fact', 'en' => 'Finance fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'employee',
                    'label' => ['de' => 'Employee', 'en' => 'Employee'],
                    'description' => [
                        'de' => 'Mitarbeiter (Employee) — salesrep-Join und interne Kontakt-PII.',
                        'en' => 'Employee — sales rep join and internal contact PII.',
                    ],
                    'grain' => ['de' => 'Ein Employee', 'en' => 'One employee'],
                    'role' => ['de' => 'Owner-Dimension (PII)', 'en' => 'Owner dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'subsidiary',
                    'label' => ['de' => 'Subsidiary', 'en' => 'Subsidiary'],
                    'description' => [
                        'de' => 'Legal Entity (Subsidiary) — subsidiary/internalid als Multi-Company-Wurzel.',
                        'en' => 'Legal entity (subsidiary) — subsidiary/internalid as multi-company root.',
                    ],
                    'grain' => ['de' => 'Eine Subsidiary', 'en' => 'One subsidiary'],
                    'role' => ['de' => 'Legal-Entity-Dimension', 'en' => 'Legal entity dimension'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'Customer', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'NetSuite-Join-Key', 'en' => 'NetSuite join key']],
                ['entity' => 'Customer', 'name' => 'entityid', 'role' => 'dimension', 'why' => ['de' => 'Business-Key / Label', 'en' => 'Business key / label']],
                ['entity' => 'Customer', 'name' => 'companyname', 'role' => 'dimension', 'why' => ['de' => 'Firmenname', 'en' => 'Company name']],
                ['entity' => 'Customer', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Kontakt-E-Mail / PII', 'en' => 'Contact email / PII']],
                ['entity' => 'Customer', 'name' => 'phone', 'role' => 'pii', 'why' => ['de' => 'Telefon / PII', 'en' => 'Phone / PII']],
                ['entity' => 'Customer', 'name' => 'subsidiary', 'role' => 'dimension', 'why' => ['de' => 'Subsidiary-Join', 'en' => 'Subsidiary join']],
                ['entity' => 'Vendor', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'Vendor-Join', 'en' => 'Vendor join']],
                ['entity' => 'Vendor', 'name' => 'entityid', 'role' => 'dimension', 'why' => ['de' => 'Vendor-Label', 'en' => 'Vendor label']],
                ['entity' => 'Vendor', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Kontakt-E-Mail / PII', 'en' => 'Contact email / PII']],
                ['entity' => 'Item', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'Item-Join', 'en' => 'Item join']],
                ['entity' => 'Item', 'name' => 'itemid', 'role' => 'dimension', 'why' => ['de' => 'SKU / Artikelnummer', 'en' => 'SKU / item number']],
                ['entity' => 'Item', 'name' => 'itemtype', 'role' => 'dimension', 'why' => ['de' => 'Inventar vs. Non-Inventory', 'en' => 'Inventory vs non-inventory']],
                ['entity' => 'Item', 'name' => 'subsidiary', 'role' => 'dimension', 'why' => ['de' => 'Subsidiary-Scope', 'en' => 'Subsidiary scope']],
                ['entity' => 'Sales Order', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'Transaction-Join', 'en' => 'Transaction join']],
                ['entity' => 'Sales Order', 'name' => 'tranid', 'role' => 'dimension', 'why' => ['de' => 'Dokumentnummer', 'en' => 'Document number']],
                ['entity' => 'Sales Order', 'name' => 'trandate', 'role' => 'measure', 'why' => ['de' => 'Auftragsdatum / Perioden-Grain', 'en' => 'Order date / period grain']],
                ['entity' => 'Sales Order', 'name' => 'entity', 'role' => 'dimension', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'Sales Order', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Fulfillment / Billing Status', 'en' => 'Fulfillment / billing status']],
                ['entity' => 'Sales Order', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Auftragswert', 'en' => 'Order value']],
                ['entity' => 'Sales Order Line', 'name' => 'transaction', 'role' => 'key', 'why' => ['de' => 'Parent-Join', 'en' => 'Parent join']],
                ['entity' => 'Sales Order Line', 'name' => 'item', 'role' => 'dimension', 'why' => ['de' => 'Item-Join', 'en' => 'Item join']],
                ['entity' => 'Sales Order Line', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Bestellmenge', 'en' => 'Order quantity']],
                ['entity' => 'Sales Order Line', 'name' => 'rate', 'role' => 'measure', 'why' => ['de' => 'Einzelpreis', 'en' => 'Unit rate']],
                ['entity' => 'Invoice', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'Invoice-Join', 'en' => 'Invoice join']],
                ['entity' => 'Invoice', 'name' => 'trandate', 'role' => 'measure', 'why' => ['de' => 'Rechnungsdatum', 'en' => 'Invoice date']],
                ['entity' => 'Invoice', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Rechnungsbetrag', 'en' => 'Invoice amount']],
                ['entity' => 'Invoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Open / Paid / Void', 'en' => 'Open / paid / void']],
                ['entity' => 'Employee', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'Sales-Rep-Join', 'en' => 'Sales rep join']],
                ['entity' => 'Employee', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'Mitarbeiter-E-Mail / PII', 'en' => 'Employee email / PII']],
                ['entity' => 'Subsidiary', 'name' => 'internalid', 'role' => 'key', 'why' => ['de' => 'Legal-Entity-Join', 'en' => 'Legal entity join']],
                ['entity' => 'Subsidiary', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Subsidiary-Label', 'en' => 'Subsidiary label']],
            ],
            'skipTables' => [
                [
                    'name' => 'SystemNote / System Notes',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'SystemNotes — hohes Volumen, selten KPI-relevant.',
                        'en' => 'System notes — high volume, rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'LoginAudit / access logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Login-Audit — Security-Telemetrie, kein Mart-Kern.',
                        'en' => 'Login audit — security telemetry, not mart core.',
                    ],
                ],
                [
                    'name' => 'File Cabinet binaries (MediaItemFolder)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Datei-Binaries — Speicher und PII-Risiko ohne Analytics-Nutzen.',
                        'en' => 'File binaries — storage and PII risk without analytics value.',
                    ],
                ],
                [
                    'name' => 'Unused custom record bulk dumps',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Alle Custom Records blind syncen — DSDR-Fläche ohne KPI.',
                        'en' => 'Blind sync of all custom records — DSDR surface without KPI.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'SystemNote tables (bulk)', 'reason' => ['de' => 'Hohes Volumen ohne KPI-Nutzen', 'en' => 'High volume without KPI value']],
                ['name' => 'Login audit / access logs', 'reason' => ['de' => 'Security-Telemetrie', 'en' => 'Security telemetry']],
                ['name' => 'File cabinet attachments', 'reason' => ['de' => 'Binaries und PII-Risiko', 'en' => 'Binaries and PII risk']],
                ['name' => 'Unused custom record types (bulk)', 'reason' => ['de' => 'Vergrößert DSDR-Fläche', 'en' => 'Expands DSDR surface']],
            ],
            'dimensions' => [
                [
                    'id' => 'subsidiary',
                    'label' => ['de' => 'Subsidiary', 'en' => 'Subsidiary'],
                    'grain' => ['de' => 'Legal Entity', 'en' => 'Legal entity'],
                    'notes' => [
                        'de' => 'Multi-Company über subsidiary/internalid verbinden — Währung pro Subsidiary.',
                        'en' => 'Link multi-company via subsidiary/internalid — currency per subsidiary.',
                    ],
                ],
                [
                    'id' => 'item_type',
                    'label' => ['de' => 'Item Type', 'en' => 'Item type'],
                    'grain' => ['de' => 'itemtype', 'en' => 'itemtype'],
                    'notes' => [
                        'de' => 'Inventar, Non-Inventory, Service, Assembly — Mix-KPIs trennen.',
                        'en' => 'Inventory, non-inventory, service, assembly — separate mix KPIs.',
                    ],
                ],
                [
                    'id' => 'location',
                    'label' => ['de' => 'Location', 'en' => 'Location'],
                    'grain' => ['de' => 'location / warehouse', 'en' => 'location / warehouse'],
                    'notes' => [
                        'de' => 'Lagerort aus transactionline.location — Fulfillment-Analytics.',
                        'en' => 'Warehouse from transactionline.location — fulfillment analytics.',
                    ],
                ],
                [
                    'id' => 'country',
                    'label' => ['de' => 'Country', 'en' => 'Country'],
                    'grain' => ['de' => 'billcountry / shipcountry', 'en' => 'billcountry / shipcountry'],
                    'notes' => [
                        'de' => 'Billing vs. Shipping Country bewusst wählen.',
                        'en' => 'Consciously choose billing vs shipping country.',
                    ],
                ],
                [
                    'id' => 'sales_rep',
                    'label' => ['de' => 'Sales Rep', 'en' => 'Sales rep'],
                    'grain' => ['de' => 'salesrep / Employee', 'en' => 'salesrep / employee'],
                    'notes' => [
                        'de' => 'salesrep auf Employee.internalid mappen — Team-Rollups optional.',
                        'en' => 'Map salesrep to employee.internalid — optional team rollups.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['email', 'phone', 'altphone', 'companyname', 'billaddress'],
                    'treatment' => [
                        'de' => 'Debitoren-Kontakt-PII — taggen, RAW einschränken.',
                        'en' => 'Customer contact PII — tag, restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Vendor',
                    'fields' => ['email', 'phone', 'companyname', 'billaddress'],
                    'treatment' => [
                        'de' => 'Lieferanten-Kontakt-PII — eigene Policy vs. Kunden-PII.',
                        'en' => 'Vendor contact PII — separate policy from customer PII.',
                    ],
                ],
                [
                    'entity' => 'Employee',
                    'fields' => ['email', 'phone', 'firstname', 'lastname'],
                    'treatment' => [
                        'de' => 'Interne Mitarbeiter-PII — HR/CRM-Overlap beachten.',
                        'en' => 'Internal employee PII — watch HR/CRM overlap.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'internalid, entityid, email, tranid, External Id (custentity_*).',
                        'en' => 'internalid, entityid, email, tranid, external id (custentity_*).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Vendor, Employee + CRM-/Warehouse-Kopien und SuiteAnalytics-Exports.',
                        'en' => 'Customer, vendor, employee + CRM/warehouse copies and SuiteAnalytics exports.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'billed-revenue',
                    'example' => true,
                    'label' => ['de' => 'Billed Revenue', 'en' => 'Billed revenue'],
                    'question' => [
                        'de' => 'Wie viel Umsatz wurde in der Periode fakturiert?',
                        'en' => 'How much revenue was billed in the period?',
                    ],
                    'formula' => 'SUM(amount) FROM invoice WHERE type = CustInvc AND trandate IN period AND status != Voided',
                    'grain' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'dimensions' => ['subsidiary', 'item_type', 'country', 'sales_rep'],
                    'fieldsUsed' => ['Invoice.amount', 'Invoice.trandate', 'Invoice.status'],
                    'sourceHints' => [
                        'de' => 'Credit Memos (CustCred) separat — Netto vs. Brutto und Multi-Currency klären.',
                        'en' => 'Credit memos (CustCred) separate — clarify net vs gross and multi-currency.',
                    ],
                    'adapt' => [
                        'de' => 'Consolidated Exchange Rate und Voided Transactions filtern.',
                        'en' => 'Filter consolidated exchange rate and voided transactions.',
                    ],
                ],
                [
                    'id' => 'open-so-backlog',
                    'example' => true,
                    'label' => ['de' => 'Open SO Backlog', 'en' => 'Open SO backlog'],
                    'question' => [
                        'de' => 'Wie viel offener Auftragswert steht noch aus?',
                        'en' => 'How much open sales order value is still outstanding?',
                    ],
                    'formula' => 'SUM(amount) FROM salesorder WHERE status NOT IN (Closed, Cancelled) AND quantityshiprecv < quantity',
                    'grain' => ['de' => 'Sales Order / Line', 'en' => 'Sales order / line'],
                    'dimensions' => ['subsidiary', 'item_type', 'sales_rep', 'country'],
                    'fieldsUsed' => ['Sales Order.amount', 'Sales Order.status', 'Sales Order Line.quantity'],
                    'sourceHints' => [
                        'de' => 'Offene Menge aus quantity - quantityshiprecv — Partial Fulfillment beachten.',
                        'en' => 'Open qty from quantity - quantityshiprecv — watch partial fulfillment.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot vs. Flow und Währung festlegen.',
                        'en' => 'Lock snapshot vs flow and currency handling.',
                    ],
                ],
                [
                    'id' => 'order-intake',
                    'example' => false,
                    'label' => ['de' => 'Order Intake', 'en' => 'Order intake'],
                    'question' => [
                        'de' => 'Wie viel Auftragswert wurde in der Periode neu erfasst?',
                        'en' => 'How much order value was newly captured in the period?',
                    ],
                    'formula' => 'SUM(amount) FROM salesorder WHERE trandate IN period',
                    'grain' => ['de' => 'Sales Order', 'en' => 'Sales order'],
                    'dimensions' => ['subsidiary', 'country', 'sales_rep', 'item_type'],
                    'fieldsUsed' => ['Sales Order.amount', 'Sales Order.trandate'],
                    'sourceHints' => [
                        'de' => 'trandate als Periodenfilter — Cancelled Sales Orders ausschließen.',
                        'en' => 'trandate as period filter — exclude cancelled sales orders.',
                    ],
                    'adapt' => [
                        'de' => 'Intercompany-Subsidiaries und Test-Accounts filtern.',
                        'en' => 'Filter intercompany subsidiaries and test accounts.',
                    ],
                ],
                [
                    'id' => 'ar-open-balance',
                    'example' => false,
                    'label' => ['de' => 'AR Open Balance', 'en' => 'AR open balance'],
                    'question' => [
                        'de' => 'Wie hoch ist der offene Debitorensaldo?',
                        'en' => 'What is the open accounts receivable balance?',
                    ],
                    'formula' => 'SUM(amountremaining) FROM invoice WHERE status = Open AND duedate <= snapshot_date',
                    'grain' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'dimensions' => ['subsidiary', 'country', 'sales_rep'],
                    'fieldsUsed' => ['Invoice.amountremaining', 'Invoice.status', 'Invoice.duedate'],
                    'sourceHints' => [
                        'de' => 'Aging Buckets über duedate vs. trandate — Payment Applications prüfen.',
                        'en' => 'Aging buckets via duedate vs trandate — check payment applications.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot-Datum und Währungsumrechnung definieren.',
                        'en' => 'Define snapshot date and currency conversion.',
                    ],
                ],
                [
                    'id' => 'units-ordered',
                    'example' => false,
                    'label' => ['de' => 'Units Ordered', 'en' => 'Units ordered'],
                    'question' => [
                        'de' => 'Wie viele Einheiten wurden bestellt?',
                        'en' => 'How many units were ordered?',
                    ],
                    'formula' => 'SUM(quantity) FROM salesorder_line JOIN salesorder WHERE trandate IN period',
                    'grain' => ['de' => 'Sales Order Line', 'en' => 'Sales order line'],
                    'dimensions' => ['item_type', 'subsidiary', 'location', 'country'],
                    'fieldsUsed' => ['Sales Order Line.quantity', 'Sales Order.trandate', 'Sales Order Line.item'],
                    'sourceHints' => [
                        'de' => 'Units of Measure vor Aggregation harmonisieren.',
                        'en' => 'Harmonize units of measure before aggregation.',
                    ],
                    'adapt' => [
                        'de' => 'Returns und Cancelled Lines ausschließen.',
                        'en' => 'Exclude returns and cancelled lines.',
                    ],
                ],
            ],
            'tools' => $erpTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'workday',
            'domain' => 'hcm',
            'order' => 90,
            'label' => ['de' => 'Workday', 'en' => 'Workday'],
            'shortPurpose' => [
                'de' => 'HCM-Kern: Worker, Position, Organization — RaaS/REST, PII und Workforce-KPIs.',
                'en' => 'HCM core: worker, position, organization — RaaS/REST, PII and workforce KPIs.',
            ],
            'entities' => [
                [
                    'id' => 'worker',
                    'label' => ['de' => 'Worker', 'en' => 'Worker'],
                    'description' => [
                        'de' => 'Arbeitskraft (Worker) — Worker_ID, Employee vs. Contingent_Worker, Hire/Termination.',
                        'en' => 'Worker — worker_id, employee vs contingent_worker, hire/termination.',
                    ],
                    'grain' => ['de' => 'Ein Worker', 'en' => 'One worker'],
                    'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'position',
                    'label' => ['de' => 'Position', 'en' => 'Position'],
                    'description' => [
                        'de' => 'Stelle (Position) — Position_ID, FTE, supervisory organization.',
                        'en' => 'Position — position_id, FTE, supervisory organization.',
                    ],
                    'grain' => ['de' => 'Eine Position', 'en' => 'One position'],
                    'role' => ['de' => 'Org-Dimension', 'en' => 'Org dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'organization',
                    'label' => ['de' => 'Organization', 'en' => 'Organization'],
                    'description' => [
                        'de' => 'Organisationseinheit — Hierarchie über superior organization.',
                        'en' => 'Organization unit — hierarchy via superior organization.',
                    ],
                    'grain' => ['de' => 'Eine Organization', 'en' => 'One organization'],
                    'role' => ['de' => 'Org-Dimension', 'en' => 'Org dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'job_profile',
                    'label' => ['de' => 'Job Profile', 'en' => 'Job profile'],
                    'description' => [
                        'de' => 'Job Profile — job family, job level für Rollen-Dimension.',
                        'en' => 'Job profile — job family, job level for role dimension.',
                    ],
                    'grain' => ['de' => 'Ein Job Profile', 'en' => 'One job profile'],
                    'role' => ['de' => 'Rollen-Dimension', 'en' => 'Role dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'time_entry',
                    'label' => ['de' => 'Time Entry', 'en' => 'Time entry'],
                    'description' => [
                        'de' => 'Zeiterfassung (Time Tracking) — Stunden, Datum, Worker für Produktivität.',
                        'en' => 'Time tracking — hours, date, worker for productivity.',
                    ],
                    'grain' => ['de' => 'Ein Time Entry', 'en' => 'One time entry'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'absence',
                    'label' => ['de' => 'Absence', 'en' => 'Absence'],
                    'description' => [
                        'de' => 'Abwesenheit (Time Off) — absence type, start/end date für Absence-KPIs.',
                        'en' => 'Time off — absence type, start/end date for absence KPIs.',
                    ],
                    'grain' => ['de' => 'Eine Absence', 'en' => 'One absence'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'payroll_result',
                    'label' => ['de' => 'Payroll Result', 'en' => 'Payroll result'],
                    'description' => [
                        'de' => 'Payroll Result — gross/net pay, pay period für Kosten-KPIs.',
                        'en' => 'Payroll result — gross/net pay, pay period for cost KPIs.',
                    ],
                    'grain' => ['de' => 'Eine Payroll-Zeile', 'en' => 'One payroll line'],
                    'role' => ['de' => 'Finance-Fact', 'en' => 'Finance fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'compensation',
                    'label' => ['de' => 'Compensation', 'en' => 'Compensation'],
                    'description' => [
                        'de' => 'Compensation — base pay, currency, effective date für Comp-Analytics.',
                        'en' => 'Compensation — base pay, currency, effective date for comp analytics.',
                    ],
                    'grain' => ['de' => 'Ein Compensation-Event', 'en' => 'One compensation event'],
                    'role' => ['de' => 'Comp-Fact', 'en' => 'Comp fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Worker', 'name' => 'Worker_ID', 'role' => 'key', 'why' => ['de' => 'Worker-Join', 'en' => 'Worker join']],
                ['entity' => 'Worker', 'name' => 'Employee_ID', 'role' => 'dimension', 'why' => ['de' => 'Business-Key', 'en' => 'Business key']],
                ['entity' => 'Worker', 'name' => 'Worker_Type', 'role' => 'dimension', 'why' => ['de' => 'Employee vs. Contingent', 'en' => 'Employee vs contingent']],
                ['entity' => 'Worker', 'name' => 'Hire_Date', 'role' => 'measure', 'why' => ['de' => 'Einstellungsdatum', 'en' => 'Hire date']],
                ['entity' => 'Worker', 'name' => 'Termination_Date', 'role' => 'measure', 'why' => ['de' => 'Austrittsdatum', 'en' => 'Termination date']],
                ['entity' => 'Worker', 'name' => 'Active_Status', 'role' => 'dimension', 'why' => ['de' => 'Aktiv / Inaktiv', 'en' => 'Active / inactive']],
                ['entity' => 'Worker', 'name' => 'Primary_Work_Email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Position', 'name' => 'Position_ID', 'role' => 'key', 'why' => ['de' => 'Position-Join', 'en' => 'Position join']],
                ['entity' => 'Position', 'name' => 'FTE', 'role' => 'measure', 'why' => ['de' => 'Vollzeitäquivalent', 'en' => 'Full-time equivalent']],
                ['entity' => 'Position', 'name' => 'Supervisory_Organization', 'role' => 'dimension', 'why' => ['de' => 'Org-Hierarchie', 'en' => 'Org hierarchy']],
                ['entity' => 'Position', 'name' => 'Job_Profile', 'role' => 'dimension', 'why' => ['de' => 'Rollen-Join', 'en' => 'Role join']],
                ['entity' => 'Position', 'name' => 'Location', 'role' => 'dimension', 'why' => ['de' => 'Standort', 'en' => 'Location']],
                ['entity' => 'Organization', 'name' => 'Organization_ID', 'role' => 'key', 'why' => ['de' => 'Org-Join', 'en' => 'Org join']],
                ['entity' => 'Organization', 'name' => 'Organization_Name', 'role' => 'dimension', 'why' => ['de' => 'Org-Label', 'en' => 'Org label']],
                ['entity' => 'Organization', 'name' => 'Superior_Organization', 'role' => 'dimension', 'why' => ['de' => 'Hierarchie', 'en' => 'Hierarchy']],
                ['entity' => 'Job Profile', 'name' => 'Job_Profile_ID', 'role' => 'key', 'why' => ['de' => 'Join', 'en' => 'Join']],
                ['entity' => 'Job Profile', 'name' => 'Job_Family', 'role' => 'dimension', 'why' => ['de' => 'Job Family', 'en' => 'Job family']],
                ['entity' => 'Job Profile', 'name' => 'Job_Level', 'role' => 'dimension', 'why' => ['de' => 'Level / Grade', 'en' => 'Level / grade']],
                ['entity' => 'Time Entry', 'name' => 'Hours', 'role' => 'measure', 'why' => ['de' => 'Gearbeitete Stunden', 'en' => 'Hours worked']],
                ['entity' => 'Time Entry', 'name' => 'Date', 'role' => 'measure', 'why' => ['de' => 'Perioden-Grain', 'en' => 'Period grain']],
                ['entity' => 'Absence', 'name' => 'Absence_Type', 'role' => 'dimension', 'why' => ['de' => 'Urlaub / Krank', 'en' => 'Leave / sick']],
                ['entity' => 'Absence', 'name' => 'Start_Date', 'role' => 'measure', 'why' => ['de' => 'Abwesenheitsbeginn', 'en' => 'Absence start']],
                ['entity' => 'Absence', 'name' => 'End_Date', 'role' => 'measure', 'why' => ['de' => 'Abwesenheitsende', 'en' => 'Absence end']],
                ['entity' => 'Payroll Result', 'name' => 'Gross_Pay', 'role' => 'measure', 'why' => ['de' => 'Bruttogehalt', 'en' => 'Gross pay']],
                ['entity' => 'Payroll Result', 'name' => 'Net_Pay', 'role' => 'measure', 'why' => ['de' => 'Nettogehalt', 'en' => 'Net pay']],
                ['entity' => 'Payroll Result', 'name' => 'Pay_Period_End_Date', 'role' => 'measure', 'why' => ['de' => 'Abrechnungsperiode', 'en' => 'Pay period']],
                ['entity' => 'Compensation', 'name' => 'Base_Pay', 'role' => 'measure', 'why' => ['de' => 'Grundgehalt', 'en' => 'Base pay']],
                ['entity' => 'Compensation', 'name' => 'Currency', 'role' => 'dimension', 'why' => ['de' => 'Währung', 'en' => 'Currency']],
                ['entity' => 'Compensation', 'name' => 'Effective_Date', 'role' => 'measure', 'why' => ['de' => 'Gültig ab', 'en' => 'Effective from']],
            ],
            'skipTables' => [
                [
                    'name' => 'Audit logs / Business Process Audit',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Audit-Trails — hohes Volumen, selten KPI-relevant.',
                        'en' => 'Audit trails — high volume, rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Document attachments (Worker Documents)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Anhänge — PII-Risiko und Binaries ohne Analytics-Nutzen.',
                        'en' => 'Attachments — PII risk and binaries without analytics value.',
                    ],
                ],
                [
                    'name' => 'Integration event dumps (ISU/Studio)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Integrations-Payloads — technisches Rauschen.',
                        'en' => 'Integration payloads — technical noise.',
                    ],
                ],
                [
                    'name' => 'Benefits free-text elections (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Benefits-Freitext — PII und unstrukturiertes Rauschen.',
                        'en' => 'Benefits free text — PII and unstructured noise.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Audit log tables (bulk)', 'reason' => ['de' => 'Hohes Volumen ohne KPI-Nutzen', 'en' => 'High volume without KPI value']],
                ['name' => 'Document attachments', 'reason' => ['de' => 'PII und Binaries', 'en' => 'PII and binaries']],
                ['name' => 'Integration event dumps', 'reason' => ['de' => 'Technisches Rauschen', 'en' => 'Technical noise']],
                ['name' => 'Benefits free-text elections (bulk)', 'reason' => ['de' => 'Unstrukturiertes PII', 'en' => 'Unstructured PII']],
            ],
            'dimensions' => [
                [
                    'id' => 'organization',
                    'label' => ['de' => 'Organization', 'en' => 'Organization'],
                    'grain' => ['de' => 'Organization_ID', 'en' => 'Organization_ID'],
                    'notes' => [
                        'de' => 'Org-Hierarchie über Superior_Organization rollen.',
                        'en' => 'Roll up org hierarchy via superior_organization.',
                    ],
                ],
                [
                    'id' => 'supervisory_org',
                    'label' => ['de' => 'Supervisory Org', 'en' => 'Supervisory org'],
                    'grain' => ['de' => 'Supervisory_Organization', 'en' => 'Supervisory_Organization'],
                    'notes' => [
                        'de' => 'Manager-Reporting-Linie — Headcount nach Team.',
                        'en' => 'Manager reporting line — headcount by team.',
                    ],
                ],
                [
                    'id' => 'job_family',
                    'label' => ['de' => 'Job Family', 'en' => 'Job family'],
                    'grain' => ['de' => 'Job_Family', 'en' => 'Job_Family'],
                    'notes' => [
                        'de' => 'Aus Job Profile ableiten — Rollen-Cluster für KPIs.',
                        'en' => 'Derive from job profile — role clusters for KPIs.',
                    ],
                ],
                [
                    'id' => 'location',
                    'label' => ['de' => 'Location', 'en' => 'Location'],
                    'grain' => ['de' => 'Location / Country', 'en' => 'Location / country'],
                    'notes' => [
                        'de' => 'Standort aus Position — Remote vs. Office trennen.',
                        'en' => 'Location from position — separate remote vs office.',
                    ],
                ],
                [
                    'id' => 'worker_type',
                    'label' => ['de' => 'Worker Type', 'en' => 'Worker type'],
                    'grain' => ['de' => 'Employee / Contingent_Worker', 'en' => 'Employee / contingent_worker'],
                    'notes' => [
                        'de' => 'Contingent vs. Employee für Headcount und FTE separat zählen.',
                        'en' => 'Count contingent vs employee separately for headcount and FTE.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Worker',
                    'fields' => ['National_ID', 'Date_of_Birth', 'Primary_Work_Email', 'Home_Address'],
                    'treatment' => [
                        'de' => 'Worker-PII — National ID, DOB, Adresse — streng taggen und RAW einschränken.',
                        'en' => 'Worker PII — national ID, DOB, address — strictly tag and restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'Payroll Result',
                    'fields' => ['Bank_Account', 'IBAN', 'Tax_ID'],
                    'treatment' => [
                        'de' => 'Bank- und Steuer-PII — separate Policy, kein offener Mart-Zugriff.',
                        'en' => 'Bank and tax PII — separate policy, no open mart access.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'Worker_ID, Employee_ID, Primary_Work_Email, National_ID (hashed).',
                        'en' => 'Worker_ID, employee_id, primary_work_email, national_id (hashed).',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Worker, Position + HRIS-/Payroll-Kopien und Identity-System-Exports.',
                        'en' => 'Worker, position + HRIS/payroll copies and identity system exports.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'headcount',
                    'example' => true,
                    'label' => ['de' => 'Headcount', 'en' => 'Headcount'],
                    'question' => [
                        'de' => 'Wie viele aktive Worker gibt es am Stichtag?',
                        'en' => 'How many active workers are there on the snapshot date?',
                    ],
                    'formula' => 'COUNT(DISTINCT Worker_ID) FROM worker WHERE Active_Status = Active AND snapshot_date BETWEEN Hire_Date AND COALESCE(Termination_Date, future)',
                    'grain' => ['de' => 'Worker', 'en' => 'Worker'],
                    'dimensions' => ['organization', 'supervisory_org', 'job_family', 'location', 'worker_type'],
                    'fieldsUsed' => ['Worker.Worker_ID', 'Worker.Active_Status', 'Worker.Hire_Date', 'Worker.Termination_Date'],
                    'sourceHints' => [
                        'de' => 'Point-in-Time vs. Average Headcount — Contingent Worker inkludieren oder nicht klären.',
                        'en' => 'Point-in-time vs average headcount — clarify contingent worker inclusion.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot-Datum und Worker_Type-Filter festlegen.',
                        'en' => 'Lock snapshot date and worker_type filter.',
                    ],
                ],
                [
                    'id' => 'fte-total',
                    'example' => true,
                    'label' => ['de' => 'FTE Total', 'en' => 'FTE total'],
                    'question' => [
                        'de' => 'Wie viele Vollzeitäquivalente sind aktiv?',
                        'en' => 'How many full-time equivalents are active?',
                    ],
                    'formula' => 'SUM(FTE) FROM position JOIN worker WHERE Active_Status = Active AND snapshot_date IN period',
                    'grain' => ['de' => 'Position', 'en' => 'Position'],
                    'dimensions' => ['organization', 'job_family', 'location', 'worker_type'],
                    'fieldsUsed' => ['Position.FTE', 'Worker.Active_Status'],
                    'sourceHints' => [
                        'de' => 'FTE aus Position — Teilzeit und Job-Sharing korrekt mappen.',
                        'en' => 'FTE from position — map part-time and job-sharing correctly.',
                    ],
                    'adapt' => [
                        'de' => 'Vacant Positions ein- oder ausschließen.',
                        'en' => 'Include or exclude vacant positions.',
                    ],
                ],
                [
                    'id' => 'turnover-rate',
                    'example' => false,
                    'label' => ['de' => 'Turnover Rate', 'en' => 'Turnover rate'],
                    'question' => [
                        'de' => 'Wie hoch ist die Fluktuationsrate in der Periode?',
                        'en' => 'What is the turnover rate in the period?',
                    ],
                    'formula' => 'COUNT(Termination_Date IN period) / AVG(headcount) * 100',
                    'grain' => ['de' => 'Worker', 'en' => 'Worker'],
                    'dimensions' => ['organization', 'job_family', 'location', 'worker_type'],
                    'fieldsUsed' => ['Worker.Termination_Date', 'Worker.Hire_Date'],
                    'sourceHints' => [
                        'de' => 'Voluntary vs. Involuntary Termination trennen — Regrettable Loss optional.',
                        'en' => 'Separate voluntary vs involuntary termination — optional regrettable loss.',
                    ],
                    'adapt' => [
                        'de' => 'Denominator (Average Headcount) und Periode definieren.',
                        'en' => 'Define denominator (average headcount) and period.',
                    ],
                ],
                [
                    'id' => 'absence-days',
                    'example' => false,
                    'label' => ['de' => 'Absence Days', 'en' => 'Absence days'],
                    'question' => [
                        'de' => 'Wie viele Abwesenheitstage wurden in der Periode erfasst?',
                        'en' => 'How many absence days were recorded in the period?',
                    ],
                    'formula' => 'SUM(DATEDIFF(End_Date, Start_Date) + 1) FROM absence WHERE Start_Date IN period',
                    'grain' => ['de' => 'Absence', 'en' => 'Absence'],
                    'dimensions' => ['organization', 'job_family', 'location', 'worker_type'],
                    'fieldsUsed' => ['Absence.Start_Date', 'Absence.End_Date', 'Absence.Absence_Type'],
                    'sourceHints' => [
                        'de' => 'Absence_Type filtern — Urlaub vs. Krank vs. Unpaid.',
                        'en' => 'Filter absence_type — leave vs sick vs unpaid.',
                    ],
                    'adapt' => [
                        'de' => 'Halbe Tage und Overlaps behandeln.',
                        'en' => 'Handle half days and overlaps.',
                    ],
                ],
                [
                    'id' => 'payroll-cost',
                    'example' => false,
                    'label' => ['de' => 'Payroll Cost', 'en' => 'Payroll cost'],
                    'question' => [
                        'de' => 'Wie hoch sind die Payroll-Kosten in der Periode?',
                        'en' => 'What are payroll costs in the period?',
                    ],
                    'formula' => 'SUM(Gross_Pay) FROM payroll_result WHERE Pay_Period_End_Date IN period',
                    'grain' => ['de' => 'Payroll Result', 'en' => 'Payroll result'],
                    'dimensions' => ['organization', 'job_family', 'location', 'worker_type'],
                    'fieldsUsed' => ['Payroll Result.Gross_Pay', 'Payroll Result.Pay_Period_End_Date'],
                    'sourceHints' => [
                        'de' => 'Gross vs. Net — Employer Contributions separat laden wenn nötig.',
                        'en' => 'Gross vs net — load employer contributions separately if needed.',
                    ],
                    'adapt' => [
                        'de' => 'Währungsumrechnung und Bonus-Zyklen klären.',
                        'en' => 'Clarify currency conversion and bonus cycles.',
                    ],
                ],
            ],
            'tools' => $hcmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'successfactors',
            'domain' => 'hcm',
            'order' => 100,
            'label' => ['de' => 'SAP SuccessFactors', 'en' => 'SAP SuccessFactors'],
            'shortPurpose' => [
                'de' => 'Employee Central: EmpEmployment, EmpJob, PerPerson — OData, PII und Workforce-KPIs.',
                'en' => 'Employee Central: EmpEmployment, EmpJob, PerPerson — OData, PII and workforce KPIs.',
            ],
            'entities' => [
                [
                    'id' => 'empemployment',
                    'label' => ['de' => 'EmpEmployment', 'en' => 'EmpEmployment'],
                    'description' => [
                        'de' => 'Beschäftigungsverhältnis (EmpEmployment) — userId, startDate, endDate, personIdExternal.',
                        'en' => 'Employment (EmpEmployment) — userId, startDate, endDate, personIdExternal.',
                    ],
                    'grain' => ['de' => 'Ein Employment', 'en' => 'One employment'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'empjob',
                    'label' => ['de' => 'EmpJob', 'en' => 'EmpJob'],
                    'description' => [
                        'de' => 'Stelleninformation (EmpJob) — department, costCenter, payGrade, eventReason.',
                        'en' => 'Job info (EmpJob) — department, costCenter, payGrade, eventReason.',
                    ],
                    'grain' => ['de' => 'Ein EmpJob-Event', 'en' => 'One EmpJob event'],
                    'role' => ['de' => 'Org/Comp-Fact', 'en' => 'Org/comp fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'perperson',
                    'label' => ['de' => 'PerPerson', 'en' => 'PerPerson'],
                    'description' => [
                        'de' => 'Person (PerPerson) — personIdExternal als Person-Key, PII-Wurzel.',
                        'en' => 'Person (PerPerson) — personIdExternal as person key, PII root.',
                    ],
                    'grain' => ['de' => 'Eine Person', 'en' => 'One person'],
                    'role' => ['de' => 'Personen-Dimension (PII)', 'en' => 'Person dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'fodepartment',
                    'label' => ['de' => 'FODepartment', 'en' => 'FODepartment'],
                    'description' => [
                        'de' => 'Abteilung (FODepartment) — externalCode, name für Org-Dimension.',
                        'en' => 'Department (FODepartment) — externalCode, name for org dimension.',
                    ],
                    'grain' => ['de' => 'Eine Abteilung', 'en' => 'One department'],
                    'role' => ['de' => 'Org-Dimension', 'en' => 'Org dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'focostcenter',
                    'label' => ['de' => 'FOCostCenter', 'en' => 'FOCostCenter'],
                    'description' => [
                        'de' => 'Kostenstelle (FOCostCenter) — externalCode für Controlling-Dimension.',
                        'en' => 'Cost center (FOCostCenter) — externalCode for controlling dimension.',
                    ],
                    'grain' => ['de' => 'Eine Kostenstelle', 'en' => 'One cost center'],
                    'role' => ['de' => 'Controlling-Dimension', 'en' => 'Controlling dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'empcompensation',
                    'label' => ['de' => 'EmpCompensation', 'en' => 'EmpCompensation'],
                    'description' => [
                        'de' => 'Vergütung (EmpCompensation) — payGrade, payComponent für Comp-KPIs.',
                        'en' => 'Compensation (EmpCompensation) — payGrade, payComponent for comp KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Compensation-Event', 'en' => 'One compensation event'],
                    'role' => ['de' => 'Comp-Fact', 'en' => 'Comp fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'folocation',
                    'label' => ['de' => 'FOLocation', 'en' => 'FOLocation'],
                    'description' => [
                        'de' => 'Standort (FOLocation) — externalCode, country für Location-Dimension.',
                        'en' => 'Location (FOLocation) — externalCode, country for location dimension.',
                    ],
                    'grain' => ['de' => 'Ein Standort', 'en' => 'One location'],
                    'role' => ['de' => 'Location-Dimension', 'en' => 'Location dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'perpersonal',
                    'label' => ['de' => 'PerPersonal', 'en' => 'PerPersonal'],
                    'description' => [
                        'de' => 'Persönliche Daten (PerPersonal) — firstName, lastName, nationalId, dateOfBirth.',
                        'en' => 'Personal data (PerPersonal) — firstName, lastName, nationalId, dateOfBirth.',
                    ],
                    'grain' => ['de' => 'Ein PerPersonal-Snapshot', 'en' => 'One PerPersonal snapshot'],
                    'role' => ['de' => 'PII-Dimension', 'en' => 'PII dimension'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'EmpEmployment', 'name' => 'userId', 'role' => 'key', 'why' => ['de' => 'SF-User-Join', 'en' => 'SF user join']],
                ['entity' => 'EmpEmployment', 'name' => 'personIdExternal', 'role' => 'key', 'why' => ['de' => 'Person-Join', 'en' => 'Person join']],
                ['entity' => 'EmpEmployment', 'name' => 'startDate', 'role' => 'measure', 'why' => ['de' => 'Einstellungsdatum', 'en' => 'Hire date']],
                ['entity' => 'EmpEmployment', 'name' => 'endDate', 'role' => 'measure', 'why' => ['de' => 'Austrittsdatum', 'en' => 'Termination date']],
                ['entity' => 'EmpEmployment', 'name' => 'employmentStatus', 'role' => 'dimension', 'why' => ['de' => 'Aktiv / Inaktiv', 'en' => 'Active / inactive']],
                ['entity' => 'EmpJob', 'name' => 'userId', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'EmpJob', 'name' => 'startDate', 'role' => 'measure', 'why' => ['de' => 'Job-Event-Datum', 'en' => 'Job event date']],
                ['entity' => 'EmpJob', 'name' => 'endDate', 'role' => 'measure', 'why' => ['de' => 'Job-Event-Ende', 'en' => 'Job event end']],
                ['entity' => 'EmpJob', 'name' => 'eventReason', 'role' => 'dimension', 'why' => ['de' => 'Hire / Transfer / Termination', 'en' => 'Hire / transfer / termination']],
                ['entity' => 'EmpJob', 'name' => 'department', 'role' => 'dimension', 'why' => ['de' => 'Abteilung', 'en' => 'Department']],
                ['entity' => 'EmpJob', 'name' => 'costCenter', 'role' => 'dimension', 'why' => ['de' => 'Kostenstelle', 'en' => 'Cost center']],
                ['entity' => 'EmpJob', 'name' => 'payGrade', 'role' => 'dimension', 'why' => ['de' => 'Gehaltsstufe', 'en' => 'Pay grade']],
                ['entity' => 'EmpJob', 'name' => 'location', 'role' => 'dimension', 'why' => ['de' => 'Standort', 'en' => 'Location']],
                ['entity' => 'EmpJob', 'name' => 'company', 'role' => 'dimension', 'why' => ['de' => 'Legal Entity', 'en' => 'Legal entity']],
                ['entity' => 'PerPerson', 'name' => 'personIdExternal', 'role' => 'key', 'why' => ['de' => 'Person-Join', 'en' => 'Person join']],
                ['entity' => 'PerPerson', 'name' => 'userId', 'role' => 'dimension', 'why' => ['de' => 'User-Mapping', 'en' => 'User mapping']],
                ['entity' => 'FODepartment', 'name' => 'externalCode', 'role' => 'key', 'why' => ['de' => 'Department-Join', 'en' => 'Department join']],
                ['entity' => 'FODepartment', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Abteilungs-Label', 'en' => 'Department label']],
                ['entity' => 'FOCostCenter', 'name' => 'externalCode', 'role' => 'key', 'why' => ['de' => 'Cost-Center-Join', 'en' => 'Cost center join']],
                ['entity' => 'FOCostCenter', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Kostenstellen-Label', 'en' => 'Cost center label']],
                ['entity' => 'EmpCompensation', 'name' => 'payComponent', 'role' => 'dimension', 'why' => ['de' => 'Gehaltskomponente', 'en' => 'Pay component']],
                ['entity' => 'EmpCompensation', 'name' => 'paycompvalue', 'role' => 'measure', 'why' => ['de' => 'Vergütungsbetrag', 'en' => 'Comp amount']],
                ['entity' => 'FOLocation', 'name' => 'externalCode', 'role' => 'key', 'why' => ['de' => 'Location-Join', 'en' => 'Location join']],
                ['entity' => 'FOLocation', 'name' => 'country', 'role' => 'dimension', 'why' => ['de' => 'Land', 'en' => 'Country']],
                ['entity' => 'PerPersonal', 'name' => 'firstName', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'PerPersonal', 'name' => 'lastName', 'role' => 'pii', 'why' => ['de' => 'PII', 'en' => 'PII']],
                ['entity' => 'PerPersonal', 'name' => 'nationalId', 'role' => 'pii', 'why' => ['de' => 'National ID / PII', 'en' => 'National ID / PII']],
                ['entity' => 'PerPersonal', 'name' => 'dateOfBirth', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'PerPersonal', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'PerPersonal', 'name' => 'phoneNumber', 'role' => 'pii', 'why' => ['de' => 'Telefon / PII', 'en' => 'Phone / PII']],
            ],
            'skipTables' => [
                [
                    'name' => 'Attachment / AttachmentContent',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Anhänge — PII-Risiko und Binaries ohne Analytics-Nutzen.',
                        'en' => 'Attachments — PII risk and binaries without analytics value.',
                    ],
                ],
                [
                    'name' => 'Audit trail / MDF audit logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Audit-Trails — hohes Volumen, selten KPI-relevant.',
                        'en' => 'Audit trails — high volume, rarely KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Background check free text',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Background-Check-Freitext — sensibles PII ohne KPI.',
                        'en' => 'Background check free text — sensitive PII without KPI.',
                    ],
                ],
                [
                    'name' => 'Learning completion dumps (bulk)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Learning-Completion-Bulk — LMS-Rauschen außerhalb EC-Kern.',
                        'en' => 'Learning completion bulk — LMS noise outside EC core.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Attachment entities (bulk)', 'reason' => ['de' => 'PII und Binaries', 'en' => 'PII and binaries']],
                ['name' => 'Audit trail / MDF audit logs', 'reason' => ['de' => 'Hohes Volumen ohne KPI-Nutzen', 'en' => 'High volume without KPI value']],
                ['name' => 'Background check free text', 'reason' => ['de' => 'Sensibles PII', 'en' => 'Sensitive PII']],
                ['name' => 'Learning completion dumps (bulk)', 'reason' => ['de' => 'LMS-Rauschen außerhalb EC', 'en' => 'LMS noise outside EC']],
            ],
            'dimensions' => [
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'FODepartment.externalCode', 'en' => 'FODepartment.externalCode'],
                    'notes' => [
                        'de' => 'Abteilung aus EmpJob.department — Hierarchie über FODepartment.',
                        'en' => 'Department from EmpJob.department — hierarchy via FODepartment.',
                    ],
                ],
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Cost Center', 'en' => 'Cost center'],
                    'grain' => ['de' => 'FOCostCenter.externalCode', 'en' => 'FOCostCenter.externalCode'],
                    'notes' => [
                        'de' => 'Kostenstelle aus EmpJob.costCenter mappen.',
                        'en' => 'Map cost center from EmpJob.costCenter.',
                    ],
                ],
                [
                    'id' => 'location',
                    'label' => ['de' => 'Location', 'en' => 'Location'],
                    'grain' => ['de' => 'FOLocation.externalCode', 'en' => 'FOLocation.externalCode'],
                    'notes' => [
                        'de' => 'Standort aus EmpJob.location — Country für Geo-KPIs.',
                        'en' => 'Location from EmpJob.location — country for geo KPIs.',
                    ],
                ],
                [
                    'id' => 'pay_grade',
                    'label' => ['de' => 'Pay Grade', 'en' => 'Pay grade'],
                    'grain' => ['de' => 'EmpJob.payGrade', 'en' => 'EmpJob.payGrade'],
                    'notes' => [
                        'de' => 'Pay Grade für Comp-Ratio und Band-Analytics.',
                        'en' => 'Pay grade for comp ratio and band analytics.',
                    ],
                ],
                [
                    'id' => 'company',
                    'label' => ['de' => 'Company', 'en' => 'Company'],
                    'grain' => ['de' => 'EmpJob.company', 'en' => 'EmpJob.company'],
                    'notes' => [
                        'de' => 'Legal Entity — Multi-Company Headcount trennen.',
                        'en' => 'Legal entity — separate multi-company headcount.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'PerPerson',
                    'fields' => ['personIdExternal', 'userId'],
                    'treatment' => [
                        'de' => 'Person-Keys — Pseudonymisierung im Mart, RAW eingeschränkt.',
                        'en' => 'Person keys — pseudonymize in mart, restrict RAW.',
                    ],
                ],
                [
                    'entity' => 'PerPersonal',
                    'fields' => ['nationalId', 'dateOfBirth', 'email', 'phoneNumber', 'firstName', 'lastName'],
                    'treatment' => [
                        'de' => 'Persönliche PII — National ID, DOB, Kontakt — streng taggen.',
                        'en' => 'Personal PII — national ID, DOB, contact — strictly tag.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'userId, personIdExternal, email, nationalId (hashed), externalCode.',
                        'en' => 'userId, personIdExternal, email, nationalId (hashed), externalCode.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'PerPerson, PerPersonal, EmpEmployment + HRIS-/Identity-Kopien und OData-Exports.',
                        'en' => 'PerPerson, PerPersonal, EmpEmployment + HRIS/identity copies and OData exports.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'headcount',
                    'example' => true,
                    'label' => ['de' => 'Headcount', 'en' => 'Headcount'],
                    'question' => [
                        'de' => 'Wie viele aktive Beschäftigungen gibt es am Stichtag?',
                        'en' => 'How many active employments are there on the snapshot date?',
                    ],
                    'formula' => 'COUNT(DISTINCT userId) FROM empemployment WHERE employmentStatus = Active AND snapshot_date BETWEEN startDate AND COALESCE(endDate, future)',
                    'grain' => ['de' => 'EmpEmployment', 'en' => 'EmpEmployment'],
                    'dimensions' => ['department', 'cost_center', 'location', 'pay_grade', 'company'],
                    'fieldsUsed' => ['EmpEmployment.userId', 'EmpEmployment.employmentStatus', 'EmpEmployment.startDate', 'EmpEmployment.endDate'],
                    'sourceHints' => [
                        'de' => 'Effective-dated EmpJob für Org-Snapshot joinen — Concurrent Employment beachten.',
                        'en' => 'Join effective-dated EmpJob for org snapshot — watch concurrent employment.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot-Datum und employmentStatus-Filter festlegen.',
                        'en' => 'Lock snapshot date and employmentStatus filter.',
                    ],
                ],
                [
                    'id' => 'hires-in-period',
                    'example' => true,
                    'label' => ['de' => 'Hires in Period', 'en' => 'Hires in period'],
                    'question' => [
                        'de' => 'Wie viele Einstellungen gab es in der Periode?',
                        'en' => 'How many hires occurred in the period?',
                    ],
                    'formula' => 'COUNT(userId) FROM empemployment WHERE startDate IN period AND eventReason = Hire',
                    'grain' => ['de' => 'EmpEmployment', 'en' => 'EmpEmployment'],
                    'dimensions' => ['department', 'location', 'company', 'pay_grade'],
                    'fieldsUsed' => ['EmpEmployment.startDate', 'EmpJob.eventReason'],
                    'sourceHints' => [
                        'de' => 'eventReason aus EmpJob für Hire-Events — Rehire separat zählen.',
                        'en' => 'eventReason from EmpJob for hire events — count rehire separately.',
                    ],
                    'adapt' => [
                        'de' => 'Rehire vs. New Hire und Periode definieren.',
                        'en' => 'Define rehire vs new hire and period.',
                    ],
                ],
                [
                    'id' => 'terminations-in-period',
                    'example' => false,
                    'label' => ['de' => 'Terminations in Period', 'en' => 'Terminations in period'],
                    'question' => [
                        'de' => 'Wie viele Austritte gab es in der Periode?',
                        'en' => 'How many terminations occurred in the period?',
                    ],
                    'formula' => 'COUNT(userId) FROM empemployment WHERE endDate IN period AND eventReason IN (Termination, Retirement)',
                    'grain' => ['de' => 'EmpEmployment', 'en' => 'EmpEmployment'],
                    'dimensions' => ['department', 'location', 'company', 'pay_grade'],
                    'fieldsUsed' => ['EmpEmployment.endDate', 'EmpJob.eventReason'],
                    'sourceHints' => [
                        'de' => 'Voluntary vs. Involuntary über eventReason trennen.',
                        'en' => 'Separate voluntary vs involuntary via eventReason.',
                    ],
                    'adapt' => [
                        'de' => 'Termination-Reason-Codes harmonisieren.',
                        'en' => 'Harmonize termination reason codes.',
                    ],
                ],
                [
                    'id' => 'avg-tenure-days',
                    'example' => false,
                    'label' => ['de' => 'Avg Tenure Days', 'en' => 'Avg tenure days'],
                    'question' => [
                        'de' => 'Wie hoch ist die durchschnittliche Betriebszugehörigkeit?',
                        'en' => 'What is average tenure in days?',
                    ],
                    'formula' => 'AVG(DATEDIFF(COALESCE(endDate, snapshot_date), startDate)) FROM empemployment WHERE employmentStatus = Active',
                    'grain' => ['de' => 'EmpEmployment', 'en' => 'EmpEmployment'],
                    'dimensions' => ['department', 'location', 'company', 'pay_grade'],
                    'fieldsUsed' => ['EmpEmployment.startDate', 'EmpEmployment.endDate'],
                    'sourceHints' => [
                        'de' => 'Tenure für aktive vs. ausgeschiedene Worker getrennt betrachten.',
                        'en' => 'Consider tenure separately for active vs terminated workers.',
                    ],
                    'adapt' => [
                        'de' => 'Rehire-Tenure-Reset-Regeln klären.',
                        'en' => 'Clarify rehire tenure reset rules.',
                    ],
                ],
                [
                    'id' => 'comp-ratio',
                    'example' => false,
                    'label' => ['de' => 'Comp Ratio', 'en' => 'Comp ratio'],
                    'question' => [
                        'de' => 'Wie liegt die Vergütung im Verhältnis zur Pay-Grade-Mitte?',
                        'en' => 'How does compensation compare to pay grade midpoint?',
                    ],
                    'formula' => 'AVG(paycompvalue / pay_grade_midpoint) FROM empcompensation JOIN empjob WHERE startDate <= snapshot_date',
                    'grain' => ['de' => 'EmpCompensation', 'en' => 'EmpCompensation'],
                    'dimensions' => ['department', 'pay_grade', 'location', 'company'],
                    'fieldsUsed' => ['EmpCompensation.paycompvalue', 'EmpJob.payGrade'],
                    'sourceHints' => [
                        'de' => 'Pay-Grade-Midpoint aus FO-PayGrade oder externer Tabelle laden.',
                        'en' => 'Load pay grade midpoint from FO-PayGrade or external table.',
                    ],
                    'adapt' => [
                        'de' => 'Base vs. Total Comp und Währung klären.',
                        'en' => 'Clarify base vs total comp and currency.',
                    ],
                ],
            ],
            'tools' => $hcmTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
