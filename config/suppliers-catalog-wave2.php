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

        /* PLACEHOLDER_NETSUITE */
    ];
};
