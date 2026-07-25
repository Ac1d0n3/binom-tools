<?php

/**
 * Wave 4 supplier library entries — Finance / Spend (full template depth).
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $financeTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
        'powerbi-dax-generator',
    ];

    return [
        [
            'id' => 'stripe',
            'domain' => 'finance',
            'order' => 150,
            'label' => ['de' => 'Stripe', 'en' => 'Stripe'],
            'shortPurpose' => [
                'de' => 'Payments: Charge/PaymentIntent, Invoice, Subscription — API-Load, PII und GMV/MRR-Measures.',
                'en' => 'Payments: charge/PaymentIntent, invoice, subscription — API load, PII and GMV/MRR measures.',
            ],
            'entities' => [
                [
                    'id' => 'payment_intent',
                    'label' => ['de' => 'PaymentIntent', 'en' => 'PaymentIntent'],
                    'description' => [
                        'de' => 'Stripe PaymentIntent — Fact-Kern für erfolgreiche Payments und GMV (amount in Minor Units).',
                        'en' => 'Stripe PaymentIntent — fact core for successful payments and GMV (amount in minor units).',
                    ],
                    'grain' => ['de' => 'Ein PaymentIntent (pi_…)', 'en' => 'One PaymentIntent (pi_…)'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'charge',
                    'label' => ['de' => 'Charge', 'en' => 'Charge'],
                    'description' => [
                        'de' => 'Stripe Charge — Capture/Refund-Join; oft über PaymentIntent.latest_charge.',
                        'en' => 'Stripe Charge — capture/refund join; often via PaymentIntent.latest_charge.',
                    ],
                    'grain' => ['de' => 'Ein Charge (ch_…)', 'en' => 'One charge (ch_…)'],
                    'role' => ['de' => 'Fact / Join', 'en' => 'Fact / join'],
                    'load' => 'required',
                ],
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'Stripe Customer — Dimension; email, name, shipping, tax_id sind PII.',
                        'en' => 'Stripe Customer — dimension; email, name, shipping, tax_id are PII.',
                    ],
                    'grain' => ['de' => 'Ein Customer (cus_…)', 'en' => 'One customer (cus_…)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'invoice',
                    'label' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'description' => [
                        'de' => 'Stripe Invoice — Billing-Fact für Subscription- und One-off-Rechnungen.',
                        'en' => 'Stripe Invoice — billing fact for subscription and one-off invoices.',
                    ],
                    'grain' => ['de' => 'Eine Invoice (in_…)', 'en' => 'One invoice (in_…)'],
                    'role' => ['de' => 'Billing-Fact', 'en' => 'Billing fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'subscription',
                    'label' => ['de' => 'Subscription', 'en' => 'Subscription'],
                    'description' => [
                        'de' => 'Stripe Subscription — MRR/ARR-Basis über items und status.',
                        'en' => 'Stripe Subscription — MRR/ARR basis via items and status.',
                    ],
                    'grain' => ['de' => 'Eine Subscription (sub_…)', 'en' => 'One subscription (sub_…)'],
                    'role' => ['de' => 'Recurring-Fact', 'en' => 'Recurring fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'balance_transaction',
                    'label' => ['de' => 'Balance Transaction', 'en' => 'Balance transaction'],
                    'description' => [
                        'de' => 'Ledger-Zeile — net/fee/amount für Cash- und Fee-Analytics.',
                        'en' => 'Ledger row — net/fee/amount for cash and fee analytics.',
                    ],
                    'grain' => ['de' => 'Eine Balance Transaction (txn_…)', 'en' => 'One balance transaction (txn_…)'],
                    'role' => ['de' => 'Ledger-Fact', 'en' => 'Ledger fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'refund',
                    'label' => ['de' => 'Refund', 'en' => 'Refund'],
                    'description' => [
                        'de' => 'Refund auf Charge/PaymentIntent — Refund-Amount und Rate.',
                        'en' => 'Refund on charge/PaymentIntent — refund amount and rate.',
                    ],
                    'grain' => ['de' => 'Ein Refund (re_…)', 'en' => 'One refund (re_…)'],
                    'role' => ['de' => 'Contra-Fact', 'en' => 'Contra fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'dispute',
                    'label' => ['de' => 'Dispute', 'en' => 'Dispute'],
                    'description' => [
                        'de' => 'Chargeback/Dispute — Dispute-Rate; Evidence-Dateien nicht laden.',
                        'en' => 'Chargeback/dispute — dispute rate; do not load evidence files.',
                    ],
                    'grain' => ['de' => 'Ein Dispute (dp_…)', 'en' => 'One dispute (dp_…)'],
                    'role' => ['de' => 'Risk-Fact', 'en' => 'Risk fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'PaymentIntent', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'API-Join-Key (pi_…)', 'en' => 'API join key (pi_…)']],
                ['entity' => 'PaymentIntent', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Betrag in Minor Units (Cents)', 'en' => 'Amount in minor units (cents)']],
                ['entity' => 'PaymentIntent', 'name' => 'currency', 'role' => 'dimension', 'why' => ['de' => 'ISO-Währung (lowercase)', 'en' => 'ISO currency (lowercase)']],
                ['entity' => 'PaymentIntent', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'succeeded / requires_… / canceled', 'en' => 'succeeded / requires_… / canceled']],
                ['entity' => 'PaymentIntent', 'name' => 'customer', 'role' => 'dimension', 'why' => ['de' => 'Customer-Join (cus_…)', 'en' => 'Customer join (cus_…)']],
                ['entity' => 'PaymentIntent', 'name' => 'created', 'role' => 'measure', 'why' => ['de' => 'Unix-Timestamp / Perioden-Grain', 'en' => 'Unix timestamp / period grain']],
                ['entity' => 'PaymentIntent', 'name' => 'latest_charge', 'role' => 'dimension', 'why' => ['de' => 'Charge-Join (ch_…)', 'en' => 'Charge join (ch_…)']],
                ['entity' => 'PaymentIntent', 'name' => 'payment_method_types', 'role' => 'dimension', 'why' => ['de' => 'card / sepa_debit / …', 'en' => 'card / sepa_debit / …']],
                ['entity' => 'Charge', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Charge-Join (ch_…)', 'en' => 'Charge join (ch_…)']],
                ['entity' => 'Charge', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Captured Amount (Minor Units)', 'en' => 'Captured amount (minor units)']],
                ['entity' => 'Charge', 'name' => 'currency', 'role' => 'dimension', 'why' => ['de' => 'Charge-Währung', 'en' => 'Charge currency']],
                ['entity' => 'Charge', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'succeeded / pending / failed', 'en' => 'succeeded / pending / failed']],
                ['entity' => 'Charge', 'name' => 'payment_intent', 'role' => 'dimension', 'why' => ['de' => 'PaymentIntent-Rückjoin', 'en' => 'PaymentIntent back-join']],
                ['entity' => 'Charge', 'name' => 'customer', 'role' => 'dimension', 'why' => ['de' => 'Customer auf Charge', 'en' => 'Customer on charge']],
                ['entity' => 'Customer', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Customer-Join (cus_…)', 'en' => 'Customer join (cus_…)']],
                ['entity' => 'Customer', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'Customer', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Customer', 'name' => 'address.country', 'role' => 'dimension', 'why' => ['de' => 'Kundenland / Country-Dim', 'en' => 'Customer country / country dim']],
                ['entity' => 'Invoice', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Invoice-Join (in_…)', 'en' => 'Invoice join (in_…)']],
                ['entity' => 'Invoice', 'name' => 'amount_paid', 'role' => 'measure', 'why' => ['de' => 'Bezahlt (Minor Units)', 'en' => 'Paid (minor units)']],
                ['entity' => 'Invoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'paid / open / void / uncollectible', 'en' => 'paid / open / void / uncollectible']],
                ['entity' => 'Invoice', 'name' => 'subscription', 'role' => 'dimension', 'why' => ['de' => 'Subscription-Join', 'en' => 'Subscription join']],
                ['entity' => 'Subscription', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Subscription-Join (sub_…)', 'en' => 'Subscription join (sub_…)']],
                ['entity' => 'Subscription', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'active / past_due / canceled', 'en' => 'active / past_due / canceled']],
                ['entity' => 'Subscription', 'name' => 'items.data.price.unit_amount', 'role' => 'measure', 'why' => ['de' => 'MRR-Baustein (Minor Units)', 'en' => 'MRR building block (minor units)']],
                ['entity' => 'Subscription', 'name' => 'items.data.price.product', 'role' => 'dimension', 'why' => ['de' => 'Product-Join (prod_…)', 'en' => 'Product join (prod_…)']],
                ['entity' => 'BalanceTransaction', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Ledger-Join (txn_…)', 'en' => 'Ledger join (txn_…)']],
                ['entity' => 'BalanceTransaction', 'name' => 'net', 'role' => 'measure', 'why' => ['de' => 'Netto nach Fees (Minor Units)', 'en' => 'Net after fees (minor units)']],
                ['entity' => 'BalanceTransaction', 'name' => 'fee', 'role' => 'measure', 'why' => ['de' => 'Stripe Fee (Minor Units)', 'en' => 'Stripe fee (minor units)']],
                ['entity' => 'Refund', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Refund-Join (re_…)', 'en' => 'Refund join (re_…)']],
                ['entity' => 'Refund', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Refund-Betrag (Minor Units)', 'en' => 'Refund amount (minor units)']],
                ['entity' => 'Refund', 'name' => 'charge', 'role' => 'dimension', 'why' => ['de' => 'Charge-Join', 'en' => 'Charge join']],
                ['entity' => 'Refund', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'succeeded / pending / failed', 'en' => 'succeeded / pending / failed']],
                ['entity' => 'Dispute', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Dispute-Join (dp_…)', 'en' => 'Dispute join (dp_…)']],
                ['entity' => 'Dispute', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Dispute-Betrag (Minor Units)', 'en' => 'Dispute amount (minor units)']],
                ['entity' => 'Dispute', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'needs_response / won / lost', 'en' => 'needs_response / won / lost']],
                ['entity' => 'Dispute', 'name' => 'charge', 'role' => 'dimension', 'why' => ['de' => 'Charge-Join für Dispute-Rate', 'en' => 'Charge join for dispute rate']],
            ],
            'skipTables' => [
                [
                    'name' => 'Raw card PAN / payment method secrets',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Kartennummern nie laden — nur Tokens (pm_…) und last4 wenn nötig.',
                        'en' => 'Never load card PANs — tokens (pm_…) and last4 only if needed.',
                    ],
                ],
                [
                    'name' => 'Webhook event dumps bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Rohe Event-Payloads — hohes Volumen, Facts besser aus Objekten ableiten.',
                        'en' => 'Raw event payloads — high volume; derive facts from objects instead.',
                    ],
                ],
                [
                    'name' => 'Dispute evidence files',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Evidence-Binaries/PDFs — nicht für Warehouse-Analytics.',
                        'en' => 'Evidence binaries/PDFs — not for warehouse analytics.',
                    ],
                ],
                [
                    'name' => 'Payout destination bank details full',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Vollständige Bankdaten — Token/IDs reichen für Payout-KPIs.',
                        'en' => 'Full bank details — token/IDs suffice for payout KPIs.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Raw card PANs (tokens only)', 'reason' => ['de' => 'PCI — niemals PAN in RAW/Mart', 'en' => 'PCI — never PAN in RAW/mart']],
                ['name' => 'Webhook event dumps bulk', 'reason' => ['de' => 'Technisches Rauschen, Volumen', 'en' => 'Technical noise, volume']],
                ['name' => 'Dispute evidence file binaries', 'reason' => ['de' => 'Kein Mart-Nutzen, Speicheraufwand', 'en' => 'No mart value, storage cost']],
                ['name' => 'PaymentMethod billing_details free-text bulk', 'reason' => ['de' => 'PII-lastig; selektiv oder hashen', 'en' => 'PII-heavy; selective or hash']],
            ],
            'dimensions' => [
                [
                    'id' => 'currency',
                    'label' => ['de' => 'Currency', 'en' => 'Currency'],
                    'grain' => ['de' => 'currency (ISO lowercase)', 'en' => 'currency (ISO lowercase)'],
                    'notes' => [
                        'de' => 'Minor-Unit-Division pro Währung (JPY=0, USD=2) vor SUM beachten.',
                        'en' => 'Apply minor-unit division per currency (JPY=0, USD=2) before SUM.',
                    ],
                ],
                [
                    'id' => 'country',
                    'label' => ['de' => 'Country', 'en' => 'Country'],
                    'grain' => ['de' => 'customer.address.country / charge.billing_details', 'en' => 'customer.address.country / charge.billing_details'],
                    'notes' => [
                        'de' => 'Billing- vs. Shipping-Country bewusst wählen.',
                        'en' => 'Consciously choose billing vs shipping country.',
                    ],
                ],
                [
                    'id' => 'payment_method_type',
                    'label' => ['de' => 'Payment Method Type', 'en' => 'Payment method type'],
                    'grain' => ['de' => 'payment_method_types / type', 'en' => 'payment_method_types / type'],
                    'notes' => [
                        'de' => 'card vs. sepa_debit vs. wallet für Conversion und Fees trennen.',
                        'en' => 'Separate card vs sepa_debit vs wallet for conversion and fees.',
                    ],
                ],
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product', 'en' => 'Product'],
                    'grain' => ['de' => 'price.product (prod_…)', 'en' => 'price.product (prod_…)'],
                    'notes' => [
                        'de' => 'Über Subscription items oder Invoice line_items joinen.',
                        'en' => 'Join via subscription items or invoice line_items.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['email', 'name'],
                    'treatment' => [
                        'de' => 'Customer-E-Mail und Name — taggen, RAW einschränken; cus_… als Join bevorzugen.',
                        'en' => 'Customer email and name — tag, restrict RAW; prefer cus_… as join.',
                    ],
                ],
                [
                    'entity' => 'Customer',
                    'fields' => ['shipping.address', 'address'],
                    'treatment' => [
                        'de' => 'Shipping-/Billing-Adresse — PII; Country für Dim behalten, Straße hashen/redigieren.',
                        'en' => 'Shipping/billing address — PII; keep country for dim, hash/redact street.',
                    ],
                ],
                [
                    'entity' => 'Customer',
                    'fields' => ['tax_ids', 'tax_id'],
                    'treatment' => [
                        'de' => 'Steuer-IDs — sensible Identifikatoren; Zugriff und Retention strikt.',
                        'en' => 'Tax IDs — sensitive identifiers; strict access and retention.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'pi_/ch_/cus_/in_/sub_/re_/dp_ IDs, email, tax_id.',
                        'en' => 'pi_/ch_/cus_/in_/sub_/re_/dp_ IDs, email, tax_id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'PaymentIntent, Charge, Customer, Invoice, Subscription + Warehouse-Kopien.',
                        'en' => 'PaymentIntent, charge, customer, invoice, subscription + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'gmv-gross',
                    'example' => true,
                    'label' => ['de' => 'GMV (Gross)', 'en' => 'GMV (gross)'],
                    'question' => [
                        'de' => 'Wie hoch ist der Brutto-Zahlungsvolumen in der Periode?',
                        'en' => 'What is gross payment volume in the period?',
                    ],
                    'formula' => 'SUM(amount) / power(10, currency_exponent) FROM payment_intent WHERE status = succeeded AND created IN period',
                    'grain' => ['de' => 'Succeeded PaymentIntent', 'en' => 'Succeeded PaymentIntent'],
                    'dimensions' => ['currency', 'country', 'payment_method_type', 'product'],
                    'fieldsUsed' => ['PaymentIntent.amount', 'PaymentIntent.currency', 'PaymentIntent.status', 'PaymentIntent.created'],
                    'sourceHints' => [
                        'de' => 'amount ist in Minor Units — vor Aggregation durch 10^exponent teilen (Stripe currency exponents).',
                        'en' => 'amount is in minor units — divide by 10^exponent before aggregation (Stripe currency exponents).',
                    ],
                    'adapt' => [
                        'de' => 'Refunds und Disputes in Net-GMV-Variante abziehen oder separat zeigen.',
                        'en' => 'Subtract refunds/disputes in a net-GMV variant or show separately.',
                    ],
                ],
                [
                    'id' => 'refund-amount',
                    'example' => true,
                    'label' => ['de' => 'Refund Amount', 'en' => 'Refund amount'],
                    'question' => [
                        'de' => 'Wie viel wurde in der Periode erstattet?',
                        'en' => 'How much was refunded in the period?',
                    ],
                    'formula' => 'SUM(amount) / power(10, currency_exponent) FROM refund WHERE status = succeeded AND created IN period',
                    'grain' => ['de' => 'Succeeded Refund', 'en' => 'Succeeded refund'],
                    'dimensions' => ['currency', 'country', 'payment_method_type'],
                    'fieldsUsed' => ['Refund.amount', 'Refund.status', 'Refund.charge', 'Refund.created'],
                    'sourceHints' => [
                        'de' => 'Refund.amount in Minor Units; Charge/PaymentIntent für Dim-Joins nutzen.',
                        'en' => 'Refund.amount in minor units; use charge/PaymentIntent for dimension joins.',
                    ],
                    'adapt' => [
                        'de' => 'Partial vs. full refunds und reason-Codes (duplicate/fraudulent) trennen.',
                        'en' => 'Separate partial vs full refunds and reason codes (duplicate/fraudulent).',
                    ],
                ],
                [
                    'id' => 'successful-payments',
                    'example' => false,
                    'label' => ['de' => 'Successful Payments', 'en' => 'Successful payments'],
                    'question' => [
                        'de' => 'Wie viele Payments sind in der Periode succeeded?',
                        'en' => 'How many payments succeeded in the period?',
                    ],
                    'formula' => "COUNT(*) FROM payment_intent WHERE status = 'succeeded' AND created IN period",
                    'grain' => ['de' => 'PaymentIntent', 'en' => 'PaymentIntent'],
                    'dimensions' => ['currency', 'country', 'payment_method_type', 'product'],
                    'fieldsUsed' => ['PaymentIntent.status', 'PaymentIntent.created', 'PaymentIntent.currency'],
                    'sourceHints' => [
                        'de' => 'status=succeeded auf PaymentIntent; nicht nur Charge ohne PI in neueren Integrationen.',
                        'en' => 'Use PaymentIntent status=succeeded; not only Charge without PI on newer integrations.',
                    ],
                    'adapt' => [
                        'de' => 'Test-Mode (livemode=false) und Zero-Amount-Auths ausschließen.',
                        'en' => 'Exclude test mode (livemode=false) and zero-amount auths.',
                    ],
                ],
                [
                    'id' => 'dispute-rate',
                    'example' => false,
                    'label' => ['de' => 'Dispute Rate', 'en' => 'Dispute rate'],
                    'question' => [
                        'de' => 'Welcher Anteil der Charges hat einen Dispute?',
                        'en' => 'What share of charges has a dispute?',
                    ],
                    'formula' => 'COUNT(DISTINCT dispute.charge) / COUNT(DISTINCT charge.id) WHERE charge.created IN period',
                    'grain' => ['de' => 'Charge in Periode', 'en' => 'Charge in period'],
                    'dimensions' => ['currency', 'country', 'payment_method_type'],
                    'fieldsUsed' => ['Dispute.charge', 'Dispute.status', 'Charge.id', 'Charge.created'],
                    'sourceHints' => [
                        'de' => 'Dispute.charge joinen; Evidence-Objekte nicht laden — nur id/status/amount.',
                        'en' => 'Join Dispute.charge; do not load evidence objects — id/status/amount only.',
                    ],
                    'adapt' => [
                        'de' => 'Won/lost/needs_response und Betrags- vs. Count-Rate festlegen.',
                        'en' => 'Lock won/lost/needs_response and amount vs count rate.',
                    ],
                ],
                [
                    'id' => 'mrr-subscriptions',
                    'example' => false,
                    'label' => ['de' => 'MRR (Subscriptions)', 'en' => 'MRR (subscriptions)'],
                    'question' => [
                        'de' => 'Wie hoch ist der Monthly Recurring Revenue aktiver Subscriptions?',
                        'en' => 'What is monthly recurring revenue of active subscriptions?',
                    ],
                    'formula' => 'SUM(normalized_monthly_unit_amount * quantity) / power(10, currency_exponent) FROM subscription_item WHERE subscription.status IN (active, past_due)',
                    'grain' => ['de' => 'Active Subscription Item', 'en' => 'Active subscription item'],
                    'dimensions' => ['currency', 'product', 'country'],
                    'fieldsUsed' => ['Subscription.status', 'Subscription.items.data.price.unit_amount', 'Subscription.items.data.price.product'],
                    'sourceHints' => [
                        'de' => 'unit_amount in Minor Units; interval month/year auf Monatsäquivalent normalisieren.',
                        'en' => 'unit_amount in minor units; normalize month/year interval to monthly equivalent.',
                    ],
                    'adapt' => [
                        'de' => 'Trialing, discounted coupons und usage-based Prices in der Definition klären.',
                        'en' => 'Clarify trialing, discounted coupons and usage-based prices in the definition.',
                    ],
                ],
            ],
            'tools' => $financeTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'sap-concur',
            'domain' => 'finance',
            'order' => 160,
            'label' => ['de' => 'SAP Concur', 'en' => 'SAP Concur'],
            'shortPurpose' => [
                'de' => 'Expense: Reports/Entries, Travel — Concur-API-Load, PII und Spend-Measures.',
                'en' => 'Expense: reports/entries, travel — Concur API load, PII and spend measures.',
            ],
            'entities' => [
                [
                    'id' => 'expense_report',
                    'label' => ['de' => 'Expense Report', 'en' => 'Expense report'],
                    'description' => [
                        'de' => 'Concur Expense Report — Fact-Kern für Submitted/Approved und Spend.',
                        'en' => 'Concur expense report — fact core for submitted/approved and spend.',
                    ],
                    'grain' => ['de' => 'Ein Expense Report', 'en' => 'One expense report'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'expense_entry',
                    'label' => ['de' => 'Expense Entry', 'en' => 'Expense entry'],
                    'description' => [
                        'de' => 'Zeile im Report — Expense Type, Amount, Allocation-Join.',
                        'en' => 'Line on report — expense type, amount, allocation join.',
                    ],
                    'grain' => ['de' => 'Eine Expense Entry', 'en' => 'One expense entry'],
                    'role' => ['de' => 'Line-Fact', 'en' => 'Line fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User / Employee', 'en' => 'User / employee'],
                    'description' => [
                        'de' => 'Concur User — Employee-Dimension; Name/E-Mail sind PII.',
                        'en' => 'Concur user — employee dimension; name/email are PII.',
                    ],
                    'grain' => ['de' => 'Ein User (employeeId / UUID)', 'en' => 'One user (employeeId / UUID)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'receipt_meta',
                    'label' => ['de' => 'Receipt Meta', 'en' => 'Receipt meta'],
                    'description' => [
                        'de' => 'Beleg-Metadaten — Merchant, Datum, Betrag; Images nicht laden.',
                        'en' => 'Receipt metadata — merchant, date, amount; do not load images.',
                    ],
                    'grain' => ['de' => 'Ein Receipt (Meta)', 'en' => 'One receipt (meta)'],
                    'role' => ['de' => 'Beleg-Dimension / Fact', 'en' => 'Receipt dimension / fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'travel_request',
                    'label' => ['de' => 'Travel Request', 'en' => 'Travel request'],
                    'description' => [
                        'de' => 'Reiseantrag — Pre-Trip-Spend und Genehmigungsstatus.',
                        'en' => 'Travel request — pre-trip spend and approval status.',
                    ],
                    'grain' => ['de' => 'Ein Travel Request', 'en' => 'One travel request'],
                    'role' => ['de' => 'Pre-Trip-Fact', 'en' => 'Pre-trip fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'invoice_capture',
                    'label' => ['de' => 'Invoice Capture', 'en' => 'Invoice capture'],
                    'description' => [
                        'de' => 'Concur Invoice / Capture — Lieferantenrechnungen neben Expense.',
                        'en' => 'Concur Invoice / Capture — supplier invoices alongside expense.',
                    ],
                    'grain' => ['de' => 'Eine erfasste Invoice', 'en' => 'One captured invoice'],
                    'role' => ['de' => 'AP-Fact', 'en' => 'AP fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'allocation',
                    'label' => ['de' => 'Allocation', 'en' => 'Allocation'],
                    'description' => [
                        'de' => 'Kostenverteilung — Cost Center / Percentage auf Entry.',
                        'en' => 'Cost split — cost center / percentage on entry.',
                    ],
                    'grain' => ['de' => 'Eine Allocation-Zeile', 'en' => 'One allocation row'],
                    'role' => ['de' => 'Allokations-Fact', 'en' => 'Allocation fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'vendor',
                    'label' => ['de' => 'Vendor', 'en' => 'Vendor'],
                    'description' => [
                        'de' => 'Händler/Lieferant — Merchant-Name oft Quasi-PII auf Receipts.',
                        'en' => 'Merchant/vendor — merchant name often quasi-PII on receipts.',
                    ],
                    'grain' => ['de' => 'Ein Vendor / Merchant', 'en' => 'One vendor / merchant'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'ExpenseReport', 'name' => 'ID', 'role' => 'key', 'why' => ['de' => 'Report-Join (Concur Report ID)', 'en' => 'Report join (Concur report ID)']],
                ['entity' => 'ExpenseReport', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Report-Titel / Label', 'en' => 'Report title / label']],
                ['entity' => 'ExpenseReport', 'name' => 'OwnerLoginID', 'role' => 'dimension', 'why' => ['de' => 'Employee-Join', 'en' => 'Employee join']],
                ['entity' => 'ExpenseReport', 'name' => 'Total', 'role' => 'measure', 'why' => ['de' => 'Report-Gesamtbetrag', 'en' => 'Report total amount']],
                ['entity' => 'ExpenseReport', 'name' => 'CurrencyCode', 'role' => 'dimension', 'why' => ['de' => 'Report-Währung', 'en' => 'Report currency']],
                ['entity' => 'ExpenseReport', 'name' => 'PaymentStatusName', 'role' => 'dimension', 'why' => ['de' => 'Payment Status (Paid/…)', 'en' => 'Payment status (Paid/…)']],
                ['entity' => 'ExpenseReport', 'name' => 'ApprovalStatusName', 'role' => 'dimension', 'why' => ['de' => 'Submitted / Approved / …', 'en' => 'Submitted / Approved / …']],
                ['entity' => 'ExpenseReport', 'name' => 'SubmitDate', 'role' => 'measure', 'why' => ['de' => 'Einreichdatum / Perioden-Grain', 'en' => 'Submit date / period grain']],
                ['entity' => 'ExpenseReport', 'name' => 'Country', 'role' => 'dimension', 'why' => ['de' => 'Report-Land', 'en' => 'Report country']],
                ['entity' => 'ExpenseEntry', 'name' => 'EntryID', 'role' => 'key', 'why' => ['de' => 'Entry-Join', 'en' => 'Entry join']],
                ['entity' => 'ExpenseEntry', 'name' => 'ReportID', 'role' => 'dimension', 'why' => ['de' => 'Report-Rückjoin', 'en' => 'Report back-join']],
                ['entity' => 'ExpenseEntry', 'name' => 'ExpenseTypeName', 'role' => 'dimension', 'why' => ['de' => 'Expense Type (Meals/Mileage/…)', 'en' => 'Expense type (Meals/Mileage/…)']],
                ['entity' => 'ExpenseEntry', 'name' => 'TransactionAmount', 'role' => 'measure', 'why' => ['de' => 'Entry-Betrag', 'en' => 'Entry amount']],
                ['entity' => 'ExpenseEntry', 'name' => 'TransactionCurrency', 'role' => 'dimension', 'why' => ['de' => 'Transaktionswährung', 'en' => 'Transaction currency']],
                ['entity' => 'ExpenseEntry', 'name' => 'TransactionDate', 'role' => 'measure', 'why' => ['de' => 'Belegdatum', 'en' => 'Receipt / transaction date']],
                ['entity' => 'ExpenseEntry', 'name' => 'VendorDescription', 'role' => 'pii', 'why' => ['de' => 'Merchant — oft Quasi-PII', 'en' => 'Merchant — often quasi-PII']],
                ['entity' => 'ExpenseEntry', 'name' => 'IsPersonal', 'role' => 'dimension', 'why' => ['de' => 'Privatanteil filtern', 'en' => 'Filter personal share']],
                ['entity' => 'User', 'name' => 'EmployeeID', 'role' => 'key', 'why' => ['de' => 'HR-/Employee-Join', 'en' => 'HR / employee join']],
                ['entity' => 'User', 'name' => 'LoginID', 'role' => 'key', 'why' => ['de' => 'Concur Login / Join', 'en' => 'Concur login / join']],
                ['entity' => 'User', 'name' => 'PrimaryEmail', 'role' => 'pii', 'why' => ['de' => 'E-Mail / PII', 'en' => 'Email / PII']],
                ['entity' => 'User', 'name' => 'FirstName', 'role' => 'pii', 'why' => ['de' => 'Vorname / PII', 'en' => 'First name / PII']],
                ['entity' => 'User', 'name' => 'LastName', 'role' => 'pii', 'why' => ['de' => 'Nachname / PII', 'en' => 'Last name / PII']],
                ['entity' => 'User', 'name' => 'OrgUnit', 'role' => 'dimension', 'why' => ['de' => 'Cost Center / Org', 'en' => 'Cost center / org']],
                ['entity' => 'ReceiptMeta', 'name' => 'ReceiptID', 'role' => 'key', 'why' => ['de' => 'Receipt-Meta-Join', 'en' => 'Receipt meta join']],
                ['entity' => 'ReceiptMeta', 'name' => 'MerchantName', 'role' => 'pii', 'why' => ['de' => 'Händler — Quasi-PII', 'en' => 'Merchant — quasi-PII']],
                ['entity' => 'ReceiptMeta', 'name' => 'CardLastFour', 'role' => 'pii', 'why' => ['de' => 'Kreditkarte last4', 'en' => 'Credit card last4']],
                ['entity' => 'TravelRequest', 'name' => 'RequestID', 'role' => 'key', 'why' => ['de' => 'Travel-Request-Join', 'en' => 'Travel request join']],
                ['entity' => 'TravelRequest', 'name' => 'TotalPostApprovalAmount', 'role' => 'measure', 'why' => ['de' => 'Genehmigter Reisebetrag', 'en' => 'Approved travel amount']],
                ['entity' => 'Allocation', 'name' => 'AllocationID', 'role' => 'key', 'why' => ['de' => 'Allocation-Join', 'en' => 'Allocation join']],
                ['entity' => 'Allocation', 'name' => 'AccountCode1', 'role' => 'dimension', 'why' => ['de' => 'Cost Center / Kontierung', 'en' => 'Cost center / account']],
                ['entity' => 'Allocation', 'name' => 'Percentage', 'role' => 'measure', 'why' => ['de' => 'Allokationsanteil', 'en' => 'Allocation share']],
                ['entity' => 'Vendor', 'name' => 'VendorCode', 'role' => 'key', 'why' => ['de' => 'Vendor-Join', 'en' => 'Vendor join']],
                ['entity' => 'Vendor', 'name' => 'VendorName', 'role' => 'dimension', 'why' => ['de' => 'Lieferanten-/Händlername', 'en' => 'Vendor / merchant name']],
                ['entity' => 'InvoiceCapture', 'name' => 'InvoiceId', 'role' => 'key', 'why' => ['de' => 'Invoice-Capture-Join', 'en' => 'Invoice capture join']],
                ['entity' => 'InvoiceCapture', 'name' => 'InvoiceAmount', 'role' => 'measure', 'why' => ['de' => 'Rechnungsbetrag', 'en' => 'Invoice amount']],
            ],
            'skipTables' => [
                [
                    'name' => 'Receipt image binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Beleg-Bilder — nicht für Warehouse-Analytics laden.',
                        'en' => 'Receipt images — do not load for warehouse analytics.',
                    ],
                ],
                [
                    'name' => 'OCR free-text dumps',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'OCR-Volltext — PII und Volumen; Meta-Felder reichen.',
                        'en' => 'OCR full text — PII and volume; meta fields suffice.',
                    ],
                ],
                [
                    'name' => 'Audit log bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Audit-Historie bulk — selten Spend-KPI-relevant.',
                        'en' => 'Audit history bulk — rarely spend-KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Mobile receipt camera originals',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Original-Fotos — Speicher und PII; nur Receipt-IDs behalten.',
                        'en' => 'Original photos — storage and PII; keep receipt IDs only.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Receipt image binaries', 'reason' => ['de' => 'Kein Mart-Nutzen, Speicheraufwand', 'en' => 'No mart value, storage cost']],
                ['name' => 'OCR free-text dumps', 'reason' => ['de' => 'Freitext-PII und Volumen', 'en' => 'Free-text PII and volume']],
                ['name' => 'Audit log bulk', 'reason' => ['de' => 'Nicht analytisch für Spend-KPIs', 'en' => 'Not analytical for spend KPIs']],
                ['name' => 'Personal card full PAN (if ever present)', 'reason' => ['de' => 'Nur last4 — nie PAN', 'en' => 'last4 only — never PAN']],
            ],
            'dimensions' => [
                [
                    'id' => 'expense_type',
                    'label' => ['de' => 'Expense Type', 'en' => 'Expense type'],
                    'grain' => ['de' => 'ExpenseTypeName / Code', 'en' => 'ExpenseTypeName / code'],
                    'notes' => [
                        'de' => 'Policy-Codes normalisieren (Meals vs. Client Meal).',
                        'en' => 'Normalize policy codes (Meals vs Client Meal).',
                    ],
                ],
                [
                    'id' => 'cost_center',
                    'label' => ['de' => 'Cost Center', 'en' => 'Cost center'],
                    'grain' => ['de' => 'Allocation AccountCode / OrgUnit', 'en' => 'Allocation AccountCode / OrgUnit'],
                    'notes' => [
                        'de' => 'Über Allocation-Prozente auf Entry-Beträge verteilen.',
                        'en' => 'Allocate entry amounts via allocation percentages.',
                    ],
                ],
                [
                    'id' => 'employee',
                    'label' => ['de' => 'Employee', 'en' => 'Employee'],
                    'grain' => ['de' => 'EmployeeID / LoginID', 'en' => 'EmployeeID / LoginID'],
                    'notes' => [
                        'de' => 'PII (Name/E-Mail) nicht als Label in Self-Service-BI ohne Need-to-know.',
                        'en' => 'Do not expose PII (name/email) as labels in self-service BI without need-to-know.',
                    ],
                ],
                [
                    'id' => 'country',
                    'label' => ['de' => 'Country', 'en' => 'Country'],
                    'grain' => ['de' => 'Report.Country / Entry country', 'en' => 'Report.Country / entry country'],
                    'notes' => [
                        'de' => 'Policy-Land vs. Transaktionsland unterscheiden.',
                        'en' => 'Distinguish policy country vs transaction country.',
                    ],
                ],
                [
                    'id' => 'payment_status',
                    'label' => ['de' => 'Payment Status', 'en' => 'Payment status'],
                    'grain' => ['de' => 'PaymentStatusName', 'en' => 'PaymentStatusName'],
                    'notes' => [
                        'de' => 'Paid vs. Not Paid für Cash-out vs. Accrual trennen.',
                        'en' => 'Separate Paid vs Not Paid for cash-out vs accrual.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['PrimaryEmail', 'FirstName', 'LastName'],
                    'treatment' => [
                        'de' => 'Employee Name/E-Mail — taggen, RAW einschränken; EmployeeID als Join bevorzugen.',
                        'en' => 'Employee name/email — tag, restrict RAW; prefer EmployeeID as join.',
                    ],
                ],
                [
                    'entity' => 'ReceiptMeta',
                    'fields' => ['MerchantName'],
                    'treatment' => [
                        'de' => 'Receipt Merchant — Quasi-PII; oft mit Personenbezug auf kleinen Belegen.',
                        'en' => 'Receipt merchant — quasi-PII; often person-linked on small receipts.',
                    ],
                ],
                [
                    'entity' => 'ReceiptMeta',
                    'fields' => ['CardLastFour'],
                    'treatment' => [
                        'de' => 'Kreditkarte last4 — eingeschränkt speichern; nie Voll-PAN.',
                        'en' => 'Credit card last4 — store restricted; never full PAN.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'Report ID, EntryID, EmployeeID, LoginID, PrimaryEmail, ReceiptID.',
                        'en' => 'Report ID, EntryID, EmployeeID, LoginID, PrimaryEmail, ReceiptID.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Expense Report, Entry, User, Allocation + Export-Kopien in Warehouse/BI.',
                        'en' => 'Expense report, entry, user, allocation + export copies in warehouse/BI.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'expense-spend',
                    'example' => true,
                    'label' => ['de' => 'Expense Spend', 'en' => 'Expense spend'],
                    'question' => [
                        'de' => 'Wie hoch sind die Spesenausgaben in der Periode?',
                        'en' => 'What is expense spend in the period?',
                    ],
                    'formula' => 'SUM(TransactionAmount) FROM expense_entry WHERE Report.SubmitDate IN period AND IsPersonal = false',
                    'grain' => ['de' => 'Expense Entry', 'en' => 'Expense entry'],
                    'dimensions' => ['expense_type', 'cost_center', 'employee', 'country', 'payment_status'],
                    'fieldsUsed' => ['ExpenseEntry.TransactionAmount', 'ExpenseEntry.ExpenseTypeName', 'ExpenseReport.SubmitDate'],
                    'sourceHints' => [
                        'de' => 'Concur v3/v4 Expense APIs — TransactionAmount + CurrencyCode; Allocation für Cost Center.',
                        'en' => 'Concur v3/v4 Expense APIs — TransactionAmount + CurrencyCode; allocation for cost center.',
                    ],
                    'adapt' => [
                        'de' => 'Submit- vs. Payment-Datum und Firmen- vs. Privatanteil festlegen.',
                        'en' => 'Lock submit vs payment date and company vs personal share.',
                    ],
                ],
                [
                    'id' => 'reports-submitted',
                    'example' => true,
                    'label' => ['de' => 'Reports Submitted', 'en' => 'Reports submitted'],
                    'question' => [
                        'de' => 'Wie viele Expense Reports wurden in der Periode eingereicht?',
                        'en' => 'How many expense reports were submitted in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM expense_report WHERE SubmitDate IN period',
                    'grain' => ['de' => 'Expense Report', 'en' => 'Expense report'],
                    'dimensions' => ['employee', 'country', 'payment_status'],
                    'fieldsUsed' => ['ExpenseReport.SubmitDate', 'ExpenseReport.OwnerLoginID', 'ExpenseReport.ID'],
                    'sourceHints' => [
                        'de' => 'SubmitDate aus Report Header; ApprovalStatusName separat von PaymentStatus.',
                        'en' => 'SubmitDate from report header; ApprovalStatusName separate from PaymentStatus.',
                    ],
                    'adapt' => [
                        'de' => 'Recalls und Resubmits einmal oder mehrfach zählen klären.',
                        'en' => 'Clarify counting recalls and resubmits once vs multiple times.',
                    ],
                ],
                [
                    'id' => 'reports-approved',
                    'example' => false,
                    'label' => ['de' => 'Reports Approved', 'en' => 'Reports approved'],
                    'question' => [
                        'de' => 'Wie viele Reports wurden in der Periode genehmigt?',
                        'en' => 'How many reports were approved in the period?',
                    ],
                    'formula' => "COUNT(*) FROM expense_report WHERE ApprovalStatusName = 'Approved' AND ApprovalDate IN period",
                    'grain' => ['de' => 'Expense Report', 'en' => 'Expense report'],
                    'dimensions' => ['employee', 'cost_center', 'country'],
                    'fieldsUsed' => ['ExpenseReport.ApprovalStatusName', 'ExpenseReport.ID', 'ExpenseReport.Total'],
                    'sourceHints' => [
                        'de' => 'ApprovalStatusName lokal mappen (Approved / Approved & Closed).',
                        'en' => 'Map ApprovalStatusName locally (Approved / Approved & Closed).',
                    ],
                    'adapt' => [
                        'de' => 'First-approval vs. final-approval und Workflow-Stufen festlegen.',
                        'en' => 'Lock first-approval vs final-approval and workflow stages.',
                    ],
                ],
                [
                    'id' => 'avg-report-amount',
                    'example' => false,
                    'label' => ['de' => 'Avg Report Amount', 'en' => 'Avg report amount'],
                    'question' => [
                        'de' => 'Wie hoch ist der durchschnittliche Report-Betrag?',
                        'en' => 'What is the average report amount?',
                    ],
                    'formula' => 'AVG(Total) FROM expense_report WHERE SubmitDate IN period',
                    'grain' => ['de' => 'Expense Report', 'en' => 'Expense report'],
                    'dimensions' => ['expense_type', 'country', 'employee'],
                    'fieldsUsed' => ['ExpenseReport.Total', 'ExpenseReport.CurrencyCode', 'ExpenseReport.SubmitDate'],
                    'sourceHints' => [
                        'de' => 'Total in Report-Währung; FX auf Konzernwährung vor AVG wenn multi-currency.',
                        'en' => 'Total in report currency; FX to group currency before AVG if multi-currency.',
                    ],
                    'adapt' => [
                        'de' => 'Median vs. Average und Outlier-Caps (Executive Travel) festlegen.',
                        'en' => 'Lock median vs average and outlier caps (executive travel).',
                    ],
                ],
                [
                    'id' => 'mileage-amount',
                    'example' => false,
                    'label' => ['de' => 'Mileage Amount', 'en' => 'Mileage amount'],
                    'question' => [
                        'de' => 'Wie hoch sind die Mileage-/Kilometerpauschalen in der Periode?',
                        'en' => 'What is mileage reimbursement amount in the period?',
                    ],
                    'formula' => "SUM(TransactionAmount) FROM expense_entry WHERE ExpenseTypeName IN ('Mileage','Personal Car Mileage') AND SubmitDate IN period",
                    'grain' => ['de' => 'Mileage Entry', 'en' => 'Mileage entry'],
                    'dimensions' => ['employee', 'cost_center', 'country'],
                    'fieldsUsed' => ['ExpenseEntry.ExpenseTypeName', 'ExpenseEntry.TransactionAmount', 'ExpenseReport.SubmitDate'],
                    'sourceHints' => [
                        'de' => 'ExpenseType Codes/Names tenant-spezifisch mappen; Distanzfelder optional zusätzlich.',
                        'en' => 'Map expense type codes/names per tenant; optionally add distance fields.',
                    ],
                    'adapt' => [
                        'de' => 'Firmenwagen vs. Privatfahrzeug und Ländersatz (km vs. mile) klären.',
                        'en' => 'Clarify company car vs personal vehicle and country rate (km vs mile).',
                    ],
                ],
            ],
            'tools' => $financeTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'sap-ariba',
            'domain' => 'finance',
            'order' => 170,
            'label' => ['de' => 'SAP Ariba', 'en' => 'SAP Ariba'],
            'shortPurpose' => [
                'de' => 'Procurement: PO, Invoice, Supplier — Ariba-Load, PII und Spend-/Cycle-Measures.',
                'en' => 'Procurement: PO, invoice, supplier — Ariba load, PII and spend/cycle measures.',
            ],
            'entities' => [
                [
                    'id' => 'purchase_order',
                    'label' => ['de' => 'Purchase Order', 'en' => 'Purchase order'],
                    'description' => [
                        'de' => 'Ariba PO Header — Fact-Kern für PO-Spend und Open-Backlog.',
                        'en' => 'Ariba PO header — fact core for PO spend and open backlog.',
                    ],
                    'grain' => ['de' => 'Eine Purchase Order', 'en' => 'One purchase order'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'po_line',
                    'label' => ['de' => 'PO Line', 'en' => 'PO line'],
                    'description' => [
                        'de' => 'PO-Position — Menge, Preis, Commodity, Supplier-Join.',
                        'en' => 'PO line — quantity, price, commodity, supplier join.',
                    ],
                    'grain' => ['de' => 'Eine PO-Position', 'en' => 'One PO line'],
                    'role' => ['de' => 'Line-Fact', 'en' => 'Line fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'invoice',
                    'label' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'description' => [
                        'de' => 'Ariba Invoice — Invoice-Spend und PO-Match für Cycle Time.',
                        'en' => 'Ariba invoice — invoice spend and PO match for cycle time.',
                    ],
                    'grain' => ['de' => 'Eine Invoice', 'en' => 'One invoice'],
                    'role' => ['de' => 'AP-Fact', 'en' => 'AP fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'supplier',
                    'label' => ['de' => 'Supplier', 'en' => 'Supplier'],
                    'description' => [
                        'de' => 'Ariba Supplier / Vendor — Dimension; Kontakte sind PII.',
                        'en' => 'Ariba supplier / vendor — dimension; contacts are PII.',
                    ],
                    'grain' => ['de' => 'Ein Supplier', 'en' => 'One supplier'],
                    'role' => ['de' => 'Dimension (PII-Kontakte)', 'en' => 'Dimension (PII contacts)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'requisition',
                    'label' => ['de' => 'Requisition', 'en' => 'Requisition'],
                    'description' => [
                        'de' => 'Bedarfsanforderung — Upstream vor PO; optional für Cycle-Analysen.',
                        'en' => 'Requisition — upstream of PO; optional for cycle analysis.',
                    ],
                    'grain' => ['de' => 'Eine Requisition', 'en' => 'One requisition'],
                    'role' => ['de' => 'Upstream-Fact', 'en' => 'Upstream fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'contract',
                    'label' => ['de' => 'Contract', 'en' => 'Contract'],
                    'description' => [
                        'de' => 'Ariba Contract — Compliance und Contracted-Spend-Kontext.',
                        'en' => 'Ariba contract — compliance and contracted-spend context.',
                    ],
                    'grain' => ['de' => 'Ein Contract', 'en' => 'One contract'],
                    'role' => ['de' => 'Vertrags-Dimension', 'en' => 'Contract dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'receipt',
                    'label' => ['de' => 'Receipt / Goods Receipt', 'en' => 'Receipt / goods receipt'],
                    'description' => [
                        'de' => 'Wareneingang — 3-Way-Match mit PO und Invoice.',
                        'en' => 'Goods receipt — three-way match with PO and invoice.',
                    ],
                    'grain' => ['de' => 'Ein Goods Receipt', 'en' => 'One goods receipt'],
                    'role' => ['de' => 'Match-Fact', 'en' => 'Match fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'commodity',
                    'label' => ['de' => 'Commodity', 'en' => 'Commodity'],
                    'description' => [
                        'de' => 'Warengruppe / UNSPSC — Spend-Kategorie-Dimension.',
                        'en' => 'Commodity / UNSPSC — spend category dimension.',
                    ],
                    'grain' => ['de' => 'Ein Commodity Code', 'en' => 'One commodity code'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'PurchaseOrder', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'PO Document Number / Join', 'en' => 'PO document number / join']],
                ['entity' => 'PurchaseOrder', 'name' => 'OrderID', 'role' => 'key', 'why' => ['de' => 'Ariba Order ID', 'en' => 'Ariba order ID']],
                ['entity' => 'PurchaseOrder', 'name' => 'StatusString', 'role' => 'dimension', 'why' => ['de' => 'Ordered / Receiving / Closed / …', 'en' => 'Ordered / Receiving / Closed / …']],
                ['entity' => 'PurchaseOrder', 'name' => 'TotalCost.Amount', 'role' => 'measure', 'why' => ['de' => 'PO-Gesamtbetrag', 'en' => 'PO total amount']],
                ['entity' => 'PurchaseOrder', 'name' => 'TotalCost.Currency', 'role' => 'dimension', 'why' => ['de' => 'PO-Währung', 'en' => 'PO currency']],
                ['entity' => 'PurchaseOrder', 'name' => 'Supplier.UniqueName', 'role' => 'dimension', 'why' => ['de' => 'Supplier-Join', 'en' => 'Supplier join']],
                ['entity' => 'PurchaseOrder', 'name' => 'CompanyCode', 'role' => 'dimension', 'why' => ['de' => 'Company Code', 'en' => 'Company code']],
                ['entity' => 'PurchaseOrder', 'name' => 'PurchaseOrg', 'role' => 'dimension', 'why' => ['de' => 'Purchasing Organization', 'en' => 'Purchasing organization']],
                ['entity' => 'PurchaseOrder', 'name' => 'OrderedDate', 'role' => 'measure', 'why' => ['de' => 'PO-Datum / Cycle-Start', 'en' => 'PO date / cycle start']],
                ['entity' => 'POLine', 'name' => 'NumberInCollection', 'role' => 'key', 'why' => ['de' => 'Line Number + PO-Join', 'en' => 'Line number + PO join']],
                ['entity' => 'POLine', 'name' => 'Quantity', 'role' => 'measure', 'why' => ['de' => 'Bestellmenge', 'en' => 'Order quantity']],
                ['entity' => 'POLine', 'name' => 'Amount.Amount', 'role' => 'measure', 'why' => ['de' => 'Line Amount', 'en' => 'Line amount']],
                ['entity' => 'POLine', 'name' => 'CommodityCode', 'role' => 'dimension', 'why' => ['de' => 'Commodity / UNSPSC', 'en' => 'Commodity / UNSPSC']],
                ['entity' => 'POLine', 'name' => 'Description.Description', 'role' => 'dimension', 'why' => ['de' => 'Positionsbeschreibung', 'en' => 'Line description']],
                ['entity' => 'Invoice', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'Invoice Document Number', 'en' => 'Invoice document number']],
                ['entity' => 'Invoice', 'name' => 'InvoiceDate', 'role' => 'measure', 'why' => ['de' => 'Invoice-Datum / Cycle-Ende', 'en' => 'Invoice date / cycle end']],
                ['entity' => 'Invoice', 'name' => 'TotalCost.Amount', 'role' => 'measure', 'why' => ['de' => 'Invoice-Spend', 'en' => 'Invoice spend']],
                ['entity' => 'Invoice', 'name' => 'StatusString', 'role' => 'dimension', 'why' => ['de' => 'Invoice Status', 'en' => 'Invoice status']],
                ['entity' => 'Invoice', 'name' => 'Order.UniqueName', 'role' => 'dimension', 'why' => ['de' => 'PO-Match-Join', 'en' => 'PO match join']],
                ['entity' => 'Invoice', 'name' => 'Supplier.UniqueName', 'role' => 'dimension', 'why' => ['de' => 'Supplier auf Invoice', 'en' => 'Supplier on invoice']],
                ['entity' => 'Supplier', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'Supplier ID / Join', 'en' => 'Supplier ID / join']],
                ['entity' => 'Supplier', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Supplier-Name', 'en' => 'Supplier name']],
                ['entity' => 'Supplier', 'name' => 'CorporateEmailAddress', 'role' => 'pii', 'why' => ['de' => 'Supplier Contact E-Mail', 'en' => 'Supplier contact email']],
                ['entity' => 'Requisition', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'Requisition Number', 'en' => 'Requisition number']],
                ['entity' => 'Requisition', 'name' => 'Requester.UniqueName', 'role' => 'pii', 'why' => ['de' => 'Buyer Employee — PII-Join', 'en' => 'Buyer employee — PII join']],
                ['entity' => 'Requisition', 'name' => 'TotalCost.Amount', 'role' => 'measure', 'why' => ['de' => 'Requisition Amount', 'en' => 'Requisition amount']],
                ['entity' => 'Contract', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'Contract ID', 'en' => 'Contract ID']],
                ['entity' => 'Contract', 'name' => 'StatusString', 'role' => 'dimension', 'why' => ['de' => 'Contract Status', 'en' => 'Contract status']],
                ['entity' => 'Receipt', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'Receipt Number', 'en' => 'Receipt number']],
                ['entity' => 'Receipt', 'name' => 'ReceiptDate', 'role' => 'measure', 'why' => ['de' => 'Wareneingangsdatum', 'en' => 'Goods receipt date']],
                ['entity' => 'Receipt', 'name' => 'Order.UniqueName', 'role' => 'dimension', 'why' => ['de' => 'PO-Join für 3-Way-Match', 'en' => 'PO join for three-way match']],
                ['entity' => 'Commodity', 'name' => 'UniqueName', 'role' => 'key', 'why' => ['de' => 'Commodity Code', 'en' => 'Commodity code']],
                ['entity' => 'Commodity', 'name' => 'Name', 'role' => 'dimension', 'why' => ['de' => 'Commodity Label', 'en' => 'Commodity label']],
                ['entity' => 'PurchaseOrder', 'name' => 'Preparer.UniqueName', 'role' => 'pii', 'why' => ['de' => 'Buyer Employee', 'en' => 'Buyer employee']],
            ],
            'skipTables' => [
                [
                    'name' => 'Attachment binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'PO/Invoice-Anhänge — nicht für Warehouse-Analytics laden.',
                        'en' => 'PO/invoice attachments — do not load for warehouse analytics.',
                    ],
                ],
                [
                    'name' => 'Sourcing event chat',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Sourcing-Chat — Freitext/PII, selten Spend-KPI-relevant.',
                        'en' => 'Sourcing chat — free text/PII, rarely spend-KPI-relevant.',
                    ],
                ],
                [
                    'name' => 'Unused custom fields bulk',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Leere/ungenutzte Custom Fields — Schema-Rauschen.',
                        'en' => 'Empty/unused custom fields — schema noise.',
                    ],
                ],
                [
                    'name' => 'Supplier questionnaire free-text archives',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Fragebogen-Freitext — Retention und PII prüfen; nicht default-load.',
                        'en' => 'Questionnaire free text — check retention and PII; do not default-load.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Attachment binaries', 'reason' => ['de' => 'Kein Mart-Nutzen, Speicheraufwand', 'en' => 'No mart value, storage cost']],
                ['name' => 'Sourcing event chat', 'reason' => ['de' => 'Freitext-PII, nicht analytisch', 'en' => 'Free-text PII, not analytical']],
                ['name' => 'Unused custom fields bulk', 'reason' => ['de' => 'Schema-Noise', 'en' => 'Schema noise']],
                ['name' => 'Full supplier master duplicates across realms', 'reason' => ['de' => 'Deduplizieren statt Vollkopie', 'en' => 'Deduplicate instead of full copy']],
            ],
            'dimensions' => [
                [
                    'id' => 'supplier',
                    'label' => ['de' => 'Supplier', 'en' => 'Supplier'],
                    'grain' => ['de' => 'Supplier.UniqueName', 'en' => 'Supplier.UniqueName'],
                    'notes' => [
                        'de' => 'Parent/Child-Supplier und Inactive filtern.',
                        'en' => 'Filter parent/child suppliers and inactive ones.',
                    ],
                ],
                [
                    'id' => 'commodity',
                    'label' => ['de' => 'Commodity', 'en' => 'Commodity'],
                    'grain' => ['de' => 'CommodityCode / UNSPSC', 'en' => 'CommodityCode / UNSPSC'],
                    'notes' => [
                        'de' => 'UNSPSC-Level (Segment vs. Class) für Reporting festlegen.',
                        'en' => 'Lock UNSPSC level (segment vs class) for reporting.',
                    ],
                ],
                [
                    'id' => 'company_code',
                    'label' => ['de' => 'Company Code', 'en' => 'Company code'],
                    'grain' => ['de' => 'CompanyCode', 'en' => 'CompanyCode'],
                    'notes' => [
                        'de' => 'ERP-Company-Code mit FI-Organisation abstimmen.',
                        'en' => 'Align ERP company code with FI organization.',
                    ],
                ],
                [
                    'id' => 'purchasing_org',
                    'label' => ['de' => 'Purchasing Org', 'en' => 'Purchasing org'],
                    'grain' => ['de' => 'PurchaseOrg', 'en' => 'PurchaseOrg'],
                    'notes' => [
                        'de' => 'Einkaufsorganisation vs. Plant nicht vermischen.',
                        'en' => 'Do not mix purchasing organization vs plant.',
                    ],
                ],
                [
                    'id' => 'status',
                    'label' => ['de' => 'Status', 'en' => 'Status'],
                    'grain' => ['de' => 'StatusString (PO / Invoice)', 'en' => 'StatusString (PO / Invoice)'],
                    'notes' => [
                        'de' => 'Open-Backlog-Definition über Status-Whitelist festziehen.',
                        'en' => 'Lock open-backlog definition via status whitelist.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Supplier',
                    'fields' => ['CorporateEmailAddress', 'ContactName'],
                    'treatment' => [
                        'de' => 'Supplier Contacts — taggen, RAW einschränken; UniqueName als Join bevorzugen.',
                        'en' => 'Supplier contacts — tag, restrict RAW; prefer UniqueName as join.',
                    ],
                ],
                [
                    'entity' => 'Requisition',
                    'fields' => ['Requester.UniqueName', 'Requester.EmailAddress'],
                    'treatment' => [
                        'de' => 'Buyer Employee — Workforce-PII; Pseudonym oder ID in Marts.',
                        'en' => 'Buyer employee — workforce PII; pseudonym or ID in marts.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'PO/Invoice UniqueName, Supplier.UniqueName, Requester, CommodityCode.',
                        'en' => 'PO/invoice UniqueName, Supplier.UniqueName, requester, CommodityCode.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Purchase Order, Invoice, Supplier, Requisition + Warehouse-Kopien.',
                        'en' => 'Purchase order, invoice, supplier, requisition + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'po-spend',
                    'example' => true,
                    'label' => ['de' => 'PO Spend', 'en' => 'PO spend'],
                    'question' => [
                        'de' => 'Wie hoch ist das Bestellvolumen (PO) in der Periode?',
                        'en' => 'What is purchase order spend volume in the period?',
                    ],
                    'formula' => 'SUM(TotalCost.Amount) FROM purchase_order WHERE OrderedDate IN period',
                    'grain' => ['de' => 'Purchase Order', 'en' => 'Purchase order'],
                    'dimensions' => ['supplier', 'commodity', 'company_code', 'purchasing_org', 'status'],
                    'fieldsUsed' => ['PurchaseOrder.TotalCost.Amount', 'PurchaseOrder.OrderedDate', 'PurchaseOrder.Supplier.UniqueName'],
                    'sourceHints' => [
                        'de' => 'Ariba Procurement/Reporting APIs — TotalCost.Amount + Currency; Line-Level für Commodity.',
                        'en' => 'Ariba Procurement/Reporting APIs — TotalCost.Amount + Currency; line-level for commodity.',
                    ],
                    'adapt' => [
                        'de' => 'Change Orders und Canceled POs in/out der Spend-Definition klären.',
                        'en' => 'Clarify change orders and canceled POs in/out of the spend definition.',
                    ],
                ],
                [
                    'id' => 'invoice-spend',
                    'example' => true,
                    'label' => ['de' => 'Invoice Spend', 'en' => 'Invoice spend'],
                    'question' => [
                        'de' => 'Wie hoch ist der Invoice-Spend in der Periode?',
                        'en' => 'What is invoice spend in the period?',
                    ],
                    'formula' => 'SUM(TotalCost.Amount) FROM invoice WHERE InvoiceDate IN period AND StatusString NOT IN (Rejected, Canceled)',
                    'grain' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'dimensions' => ['supplier', 'commodity', 'company_code', 'status'],
                    'fieldsUsed' => ['Invoice.TotalCost.Amount', 'Invoice.InvoiceDate', 'Invoice.StatusString', 'Invoice.Supplier.UniqueName'],
                    'sourceHints' => [
                        'de' => 'InvoiceDate vs. Posted/Paid Date bewusst wählen; PO-Match über Order.UniqueName.',
                        'en' => 'Consciously choose InvoiceDate vs posted/paid date; PO match via Order.UniqueName.',
                    ],
                    'adapt' => [
                        'de' => 'Non-PO invoices und Credit Memos separat ausweisen.',
                        'en' => 'Report non-PO invoices and credit memos separately.',
                    ],
                ],
                [
                    'id' => 'open-po-backlog',
                    'example' => false,
                    'label' => ['de' => 'Open PO Backlog', 'en' => 'Open PO backlog'],
                    'question' => [
                        'de' => 'Wie hoch ist der offene PO-Bestand (Backlog)?',
                        'en' => 'What is the open PO backlog?',
                    ],
                    'formula' => "SUM(open_amount) FROM purchase_order WHERE StatusString IN ('Ordered','Receiving')",
                    'grain' => ['de' => 'Open Purchase Order', 'en' => 'Open purchase order'],
                    'dimensions' => ['supplier', 'commodity', 'purchasing_org', 'status'],
                    'fieldsUsed' => ['PurchaseOrder.StatusString', 'PurchaseOrder.TotalCost.Amount', 'PurchaseOrder.UniqueName'],
                    'sourceHints' => [
                        'de' => 'Open Amount = Ordered − Received/Invoiced je Tenant-Regel; StatusString whitelisten.',
                        'en' => 'Open amount = ordered − received/invoiced per tenant rule; whitelist StatusString.',
                    ],
                    'adapt' => [
                        'de' => 'Snapshot-Datum und Alters-Buckets (0–30/31–60) festlegen.',
                        'en' => 'Lock snapshot date and aging buckets (0–30/31–60).',
                    ],
                ],
                [
                    'id' => 'suppliers-active',
                    'example' => false,
                    'label' => ['de' => 'Suppliers Active', 'en' => 'Suppliers active'],
                    'question' => [
                        'de' => 'Wie viele Supplier hatten in der Periode PO- oder Invoice-Aktivität?',
                        'en' => 'How many suppliers had PO or invoice activity in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT Supplier.UniqueName) FROM purchase_order OR invoice WHERE date IN period',
                    'grain' => ['de' => 'Supplier mit Aktivität', 'en' => 'Supplier with activity'],
                    'dimensions' => ['commodity', 'company_code', 'purchasing_org'],
                    'fieldsUsed' => ['PurchaseOrder.Supplier.UniqueName', 'Invoice.Supplier.UniqueName', 'PurchaseOrder.OrderedDate'],
                    'sourceHints' => [
                        'de' => 'Active = mind. ein PO oder Invoice in Periode — nicht nur Master-Status Approved.',
                        'en' => 'Active = at least one PO or invoice in period — not only master status Approved.',
                    ],
                    'adapt' => [
                        'de' => 'One-time vendors und Intercompany aus Active-Set ggf. ausschließen.',
                        'en' => 'Optionally exclude one-time vendors and intercompany from the active set.',
                    ],
                ],
                [
                    'id' => 'cycle-time-po-to-invoice-days',
                    'example' => false,
                    'label' => ['de' => 'Cycle Time PO→Invoice (Days)', 'en' => 'Cycle time PO→invoice (days)'],
                    'question' => [
                        'de' => 'Wie lange dauert es von PO Ordered bis Invoice?',
                        'en' => 'How long from PO ordered to invoice?',
                    ],
                    'formula' => 'AVG(DATEDIFF(day, OrderedDate, InvoiceDate)) FROM invoice JOIN purchase_order ON Order.UniqueName',
                    'grain' => ['de' => 'Matched Invoice↔PO', 'en' => 'Matched invoice↔PO'],
                    'dimensions' => ['supplier', 'commodity', 'purchasing_org', 'company_code'],
                    'fieldsUsed' => ['PurchaseOrder.OrderedDate', 'Invoice.InvoiceDate', 'Invoice.Order.UniqueName'],
                    'sourceHints' => [
                        'de' => 'Nur matched Invoices; Non-PO und Multi-PO-Invoices separat behandeln.',
                        'en' => 'Matched invoices only; treat non-PO and multi-PO invoices separately.',
                    ],
                    'adapt' => [
                        'de' => 'Median vs. Average und Outlier-Caps (Jahresverträge) festlegen.',
                        'en' => 'Lock median vs average and outlier caps (annual contracts).',
                    ],
                ],
            ],
            'tools' => $financeTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'coupa',
            'domain' => 'finance',
            'order' => 180,
            'label' => ['de' => 'Coupa', 'en' => 'Coupa'],
            'shortPurpose' => [
                'de' => 'Spend: PO, Invoice, Expense — Coupa-API-Load, PII und Maverick-/Spend-Measures.',
                'en' => 'Spend: PO, invoice, expense — Coupa API load, PII and maverick/spend measures.',
            ],
            'entities' => [
                [
                    'id' => 'purchase_order',
                    'label' => ['de' => 'Purchase Order', 'en' => 'Purchase order'],
                    'description' => [
                        'de' => 'Coupa PO — Fact-Kern für PO-Spend und Compliance-Kontext.',
                        'en' => 'Coupa PO — fact core for PO spend and compliance context.',
                    ],
                    'grain' => ['de' => 'Eine Purchase Order', 'en' => 'One purchase order'],
                    'role' => ['de' => 'Fact-Kandidat', 'en' => 'Fact candidate'],
                    'load' => 'required',
                ],
                [
                    'id' => 'invoice',
                    'label' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'description' => [
                        'de' => 'Coupa Invoice — Invoice-Spend und Payment-Status.',
                        'en' => 'Coupa invoice — invoice spend and payment status.',
                    ],
                    'grain' => ['de' => 'Eine Invoice', 'en' => 'One invoice'],
                    'role' => ['de' => 'AP-Fact', 'en' => 'AP fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'requisition',
                    'label' => ['de' => 'Requisition', 'en' => 'Requisition'],
                    'description' => [
                        'de' => 'Coupa Requisition — Upstream-Bedarf vor PO.',
                        'en' => 'Coupa requisition — upstream demand before PO.',
                    ],
                    'grain' => ['de' => 'Eine Requisition', 'en' => 'One requisition'],
                    'role' => ['de' => 'Upstream-Fact', 'en' => 'Upstream fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'supplier',
                    'label' => ['de' => 'Supplier', 'en' => 'Supplier'],
                    'description' => [
                        'de' => 'Coupa Supplier — Dimension; Contact-Felder sind PII.',
                        'en' => 'Coupa supplier — dimension; contact fields are PII.',
                    ],
                    'grain' => ['de' => 'Ein Supplier', 'en' => 'One supplier'],
                    'role' => ['de' => 'Dimension (PII-Kontakte)', 'en' => 'Dimension (PII contacts)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'expense',
                    'label' => ['de' => 'Expense', 'en' => 'Expense'],
                    'description' => [
                        'de' => 'Coupa Expense Report/Line — Employee-Spend neben Procurement.',
                        'en' => 'Coupa expense report/line — employee spend beside procurement.',
                    ],
                    'grain' => ['de' => 'Eine Expense Line / Report', 'en' => 'One expense line / report'],
                    'role' => ['de' => 'Expense-Fact', 'en' => 'Expense fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'contract',
                    'label' => ['de' => 'Contract', 'en' => 'Contract'],
                    'description' => [
                        'de' => 'Coupa Contract — Contracted vs. Maverick-Spend-Kontext.',
                        'en' => 'Coupa contract — contracted vs maverick spend context.',
                    ],
                    'grain' => ['de' => 'Ein Contract', 'en' => 'One contract'],
                    'role' => ['de' => 'Vertrags-Dimension', 'en' => 'Contract dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'inventory_transaction',
                    'label' => ['de' => 'Inventory Transaction', 'en' => 'Inventory transaction'],
                    'description' => [
                        'de' => 'Bestandsbewegung — optional für Inventory-/Receiving-Analytics.',
                        'en' => 'Inventory movement — optional for inventory/receiving analytics.',
                    ],
                    'grain' => ['de' => 'Eine Inventory Transaction', 'en' => 'One inventory transaction'],
                    'role' => ['de' => 'Inventory-Fact', 'en' => 'Inventory fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'user',
                    'label' => ['de' => 'User', 'en' => 'User'],
                    'description' => [
                        'de' => 'Coupa User — Requester/Approver; E-Mail ist PII.',
                        'en' => 'Coupa user — requester/approver; email is PII.',
                    ],
                    'grain' => ['de' => 'Ein User', 'en' => 'One user'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'PurchaseOrder', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Coupa PO id', 'en' => 'Coupa PO id']],
                ['entity' => 'PurchaseOrder', 'name' => 'po-number', 'role' => 'key', 'why' => ['de' => 'Business Document Number', 'en' => 'Business document number']],
                ['entity' => 'PurchaseOrder', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'PO Status', 'en' => 'PO status']],
                ['entity' => 'PurchaseOrder', 'name' => 'total', 'role' => 'measure', 'why' => ['de' => 'PO Total', 'en' => 'PO total']],
                ['entity' => 'PurchaseOrder', 'name' => 'currency.code', 'role' => 'dimension', 'why' => ['de' => 'Währung', 'en' => 'Currency']],
                ['entity' => 'PurchaseOrder', 'name' => 'supplier_id', 'role' => 'dimension', 'why' => ['de' => 'Supplier-Join', 'en' => 'Supplier join']],
                ['entity' => 'PurchaseOrder', 'name' => 'chart-of-account_id', 'role' => 'dimension', 'why' => ['de' => 'Chart of Accounts', 'en' => 'Chart of accounts']],
                ['entity' => 'PurchaseOrder', 'name' => 'department_id', 'role' => 'dimension', 'why' => ['de' => 'Department', 'en' => 'Department']],
                ['entity' => 'PurchaseOrder', 'name' => 'created-at', 'role' => 'measure', 'why' => ['de' => 'PO-Erstellzeit / Periode', 'en' => 'PO created time / period']],
                ['entity' => 'Invoice', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Coupa Invoice id', 'en' => 'Coupa invoice id']],
                ['entity' => 'Invoice', 'name' => 'invoice-number', 'role' => 'key', 'why' => ['de' => 'Invoice Document Number', 'en' => 'Invoice document number']],
                ['entity' => 'Invoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Invoice Status', 'en' => 'Invoice status']],
                ['entity' => 'Invoice', 'name' => 'total-with-taxes', 'role' => 'measure', 'why' => ['de' => 'Invoice Total', 'en' => 'Invoice total']],
                ['entity' => 'Invoice', 'name' => 'supplier_id', 'role' => 'dimension', 'why' => ['de' => 'Supplier auf Invoice', 'en' => 'Supplier on invoice']],
                ['entity' => 'Invoice', 'name' => 'invoice-date', 'role' => 'measure', 'why' => ['de' => 'Invoice-Datum', 'en' => 'Invoice date']],
                ['entity' => 'Requisition', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Requisition id', 'en' => 'Requisition id']],
                ['entity' => 'Requisition', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Requisition Status', 'en' => 'Requisition status']],
                ['entity' => 'Requisition', 'name' => 'total', 'role' => 'measure', 'why' => ['de' => 'Requisition Total', 'en' => 'Requisition total']],
                ['entity' => 'Requisition', 'name' => 'requested-by_id', 'role' => 'dimension', 'why' => ['de' => 'Requester User-Join', 'en' => 'Requester user join']],
                ['entity' => 'Supplier', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Supplier id', 'en' => 'Supplier id']],
                ['entity' => 'Supplier', 'name' => 'name', 'role' => 'dimension', 'why' => ['de' => 'Supplier-Name', 'en' => 'Supplier name']],
                ['entity' => 'Supplier', 'name' => 'primary-contact.email', 'role' => 'pii', 'why' => ['de' => 'Supplier Contact E-Mail', 'en' => 'Supplier contact email']],
                ['entity' => 'Expense', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Expense id', 'en' => 'Expense id']],
                ['entity' => 'Expense', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Expense Status', 'en' => 'Expense status']],
                ['entity' => 'Expense', 'name' => 'total', 'role' => 'measure', 'why' => ['de' => 'Expense Total', 'en' => 'Expense total']],
                ['entity' => 'Expense', 'name' => 'expense-category_id', 'role' => 'dimension', 'why' => ['de' => 'Expense Category / Commodity', 'en' => 'Expense category / commodity']],
                ['entity' => 'Expense', 'name' => 'user_id', 'role' => 'dimension', 'why' => ['de' => 'Employee User-Join', 'en' => 'Employee user join']],
                ['entity' => 'Contract', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Contract id', 'en' => 'Contract id']],
                ['entity' => 'Contract', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'Contract Status', 'en' => 'Contract status']],
                ['entity' => 'Contract', 'name' => 'supplier_id', 'role' => 'dimension', 'why' => ['de' => 'Contracted Supplier', 'en' => 'Contracted supplier']],
                ['entity' => 'InventoryTransaction', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'Inventory Transaction id', 'en' => 'Inventory transaction id']],
                ['entity' => 'InventoryTransaction', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Bewegungsmenge', 'en' => 'Movement quantity']],
                ['entity' => 'User', 'name' => 'id', 'role' => 'key', 'why' => ['de' => 'User-Join', 'en' => 'User join']],
                ['entity' => 'User', 'name' => 'email', 'role' => 'pii', 'why' => ['de' => 'User-E-Mail / PII', 'en' => 'User email / PII']],
                ['entity' => 'User', 'name' => 'department_id', 'role' => 'dimension', 'why' => ['de' => 'User Department', 'en' => 'User department']],
                ['entity' => 'PurchaseOrder', 'name' => 'commodity_id', 'role' => 'dimension', 'why' => ['de' => 'Commodity auf PO/Lines', 'en' => 'Commodity on PO/lines']],
            ],
            'skipTables' => [
                [
                    'name' => 'Attachment binaries',
                    'category' => 'blob',
                    'reason' => [
                        'de' => 'Anhänge an PO/Invoice/Expense — nicht für Warehouse-Analytics laden.',
                        'en' => 'Attachments on PO/invoice/expense — do not load for warehouse analytics.',
                    ],
                ],
                [
                    'name' => 'Approval comment free text bulk',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Approval-Kommentare — Freitext/PII; Status-Events reichen für KPIs.',
                        'en' => 'Approval comments — free text/PII; status events suffice for KPIs.',
                    ],
                ],
                [
                    'name' => 'SSO debug logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'SSO-Debug — technisches Rauschen, oft Identifiers.',
                        'en' => 'SSO debug — technical noise, often identifiers.',
                    ],
                ],
                [
                    'name' => 'Unused integration payload archives',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Rohe Integrations-Payloads — Volumen ohne Mart-Nutzen.',
                        'en' => 'Raw integration payloads — volume without mart value.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Attachment binaries', 'reason' => ['de' => 'Kein Mart-Nutzen, Speicheraufwand', 'en' => 'No mart value, storage cost']],
                ['name' => 'Approval comment free text bulk', 'reason' => ['de' => 'Freitext-PII', 'en' => 'Free-text PII']],
                ['name' => 'SSO debug logs', 'reason' => ['de' => 'Nicht analytisch', 'en' => 'Not analytical']],
                ['name' => 'Full expense receipt image store', 'reason' => ['de' => 'Binaries — Meta behalten', 'en' => 'Binaries — keep meta']],
            ],
            'dimensions' => [
                [
                    'id' => 'supplier',
                    'label' => ['de' => 'Supplier', 'en' => 'Supplier'],
                    'grain' => ['de' => 'supplier_id', 'en' => 'supplier_id'],
                    'notes' => [
                        'de' => 'Active/Inactive und Preferred Supplier Flags nutzen.',
                        'en' => 'Use active/inactive and preferred supplier flags.',
                    ],
                ],
                [
                    'id' => 'commodity',
                    'label' => ['de' => 'Commodity', 'en' => 'Commodity'],
                    'grain' => ['de' => 'commodity_id / expense-category', 'en' => 'commodity_id / expense-category'],
                    'notes' => [
                        'de' => 'Coupa Commodity Hierarchy für Rollups festlegen.',
                        'en' => 'Lock Coupa commodity hierarchy for rollups.',
                    ],
                ],
                [
                    'id' => 'chart_of_account',
                    'label' => ['de' => 'Chart of Account', 'en' => 'Chart of account'],
                    'grain' => ['de' => 'chart-of-account_id', 'en' => 'chart-of-account_id'],
                    'notes' => [
                        'de' => 'COA-Segmente mit FI-Kontenplan abstimmen.',
                        'en' => 'Align COA segments with the FI chart of accounts.',
                    ],
                ],
                [
                    'id' => 'department',
                    'label' => ['de' => 'Department', 'en' => 'Department'],
                    'grain' => ['de' => 'department_id', 'en' => 'department_id'],
                    'notes' => [
                        'de' => 'Department vs. Cost Center Mapping dokumentieren.',
                        'en' => 'Document department vs cost center mapping.',
                    ],
                ],
                [
                    'id' => 'status',
                    'label' => ['de' => 'Status', 'en' => 'Status'],
                    'grain' => ['de' => 'status (PO / Invoice / Expense)', 'en' => 'status (PO / Invoice / Expense)'],
                    'notes' => [
                        'de' => 'Objekt-spezifische Status-Listen nicht vermischen.',
                        'en' => 'Do not mix object-specific status lists.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'User',
                    'fields' => ['email', 'fullname'],
                    'treatment' => [
                        'de' => 'User-E-Mail — taggen, RAW einschränken; user id als Join bevorzugen.',
                        'en' => 'User email — tag, restrict RAW; prefer user id as join.',
                    ],
                ],
                [
                    'entity' => 'Supplier',
                    'fields' => ['primary-contact.email', 'primary-contact.name'],
                    'treatment' => [
                        'de' => 'Supplier Contact — PII; supplier id für Analytics-Joins.',
                        'en' => 'Supplier contact — PII; supplier id for analytics joins.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'PO/Invoice/Expense id, po-number, supplier_id, user email/id.',
                        'en' => 'PO/invoice/expense id, po-number, supplier_id, user email/id.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Purchase Order, Invoice, Expense, Supplier, User + Warehouse-Kopien.',
                        'en' => 'Purchase order, invoice, expense, supplier, user + warehouse copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'po-spend',
                    'example' => true,
                    'label' => ['de' => 'PO Spend', 'en' => 'PO spend'],
                    'question' => [
                        'de' => 'Wie hoch ist das Coupa-PO-Volumen in der Periode?',
                        'en' => 'What is Coupa PO volume in the period?',
                    ],
                    'formula' => 'SUM(total) FROM purchase_order WHERE created-at IN period',
                    'grain' => ['de' => 'Purchase Order', 'en' => 'Purchase order'],
                    'dimensions' => ['supplier', 'commodity', 'chart_of_account', 'department', 'status'],
                    'fieldsUsed' => ['PurchaseOrder.total', 'PurchaseOrder.created-at', 'PurchaseOrder.supplier_id', 'PurchaseOrder.currency.code'],
                    'sourceHints' => [
                        'de' => 'Coupa REST — purchase_orders.json; total + currency.code; hyphenated API fields.',
                        'en' => 'Coupa REST — purchase_orders.json; total + currency.code; hyphenated API fields.',
                    ],
                    'adapt' => [
                        'de' => 'Canceled/draft POs und Change Orders in der Definition klären.',
                        'en' => 'Clarify canceled/draft POs and change orders in the definition.',
                    ],
                ],
                [
                    'id' => 'invoice-spend',
                    'example' => true,
                    'label' => ['de' => 'Invoice Spend', 'en' => 'Invoice spend'],
                    'question' => [
                        'de' => 'Wie hoch ist der Coupa-Invoice-Spend in der Periode?',
                        'en' => 'What is Coupa invoice spend in the period?',
                    ],
                    'formula' => 'SUM(total-with-taxes) FROM invoice WHERE invoice-date IN period AND status NOT IN (voided, draft)',
                    'grain' => ['de' => 'Invoice', 'en' => 'Invoice'],
                    'dimensions' => ['supplier', 'commodity', 'chart_of_account', 'department', 'status'],
                    'fieldsUsed' => ['Invoice.total-with-taxes', 'Invoice.invoice-date', 'Invoice.status', 'Invoice.supplier_id'],
                    'sourceHints' => [
                        'de' => 'invoices.json — total-with-taxes; invoice-date vs. created-at für Periodenwahl.',
                        'en' => 'invoices.json — total-with-taxes; invoice-date vs created-at for period choice.',
                    ],
                    'adapt' => [
                        'de' => 'Credit notes und tax-inclusive vs. exclusive festlegen.',
                        'en' => 'Lock credit notes and tax-inclusive vs exclusive.',
                    ],
                ],
                [
                    'id' => 'expense-spend',
                    'example' => false,
                    'label' => ['de' => 'Expense Spend', 'en' => 'Expense spend'],
                    'question' => [
                        'de' => 'Wie hoch sind die Coupa-Expense-Ausgaben in der Periode?',
                        'en' => 'What is Coupa expense spend in the period?',
                    ],
                    'formula' => 'SUM(total) FROM expense WHERE created-at IN period AND status = approved_for_payment',
                    'grain' => ['de' => 'Expense', 'en' => 'Expense'],
                    'dimensions' => ['commodity', 'department', 'chart_of_account', 'status'],
                    'fieldsUsed' => ['Expense.total', 'Expense.status', 'Expense.user_id', 'Expense.expense-category_id'],
                    'sourceHints' => [
                        'de' => 'expense_reports / expense_lines — Status-Whitelist tenant-spezifisch mappen.',
                        'en' => 'expense_reports / expense_lines — map status whitelist per tenant.',
                    ],
                    'adapt' => [
                        'de' => 'Submitted vs. paid und persönliche Karte vs. Corporate Card klären.',
                        'en' => 'Clarify submitted vs paid and personal vs corporate card.',
                    ],
                ],
                [
                    'id' => 'maverick-spend-rate',
                    'example' => false,
                    'label' => ['de' => 'Maverick Spend Rate', 'en' => 'Maverick spend rate'],
                    'question' => [
                        'de' => 'Welcher Anteil des Spends läuft außerhalb der Policy/Contracts?',
                        'en' => 'What share of spend runs outside policy/contracts?',
                    ],
                    'formula' => 'SUM(spend WHERE maverick) / SUM(spend) — requires policy definition',
                    'grain' => ['de' => 'Spend-Zeile (PO/Invoice/Expense)', 'en' => 'Spend line (PO/Invoice/Expense)'],
                    'dimensions' => ['supplier', 'commodity', 'department', 'chart_of_account'],
                    'fieldsUsed' => ['PurchaseOrder.total', 'Invoice.total-with-taxes', 'Contract.id', 'PurchaseOrder.supplier_id'],
                    'sourceHints' => [
                        'de' => 'Maverick erfordert Policy-Definition (kein Contract, Off-Catalog, Non-PO) — Felder tenant-spezifisch.',
                        'en' => 'Maverick requires a policy definition (no contract, off-catalog, non-PO) — fields are tenant-specific.',
                    ],
                    'adapt' => [
                        'de' => 'Policy mit Procurement Governance locken bevor die Rate reportet wird.',
                        'en' => 'Lock policy with procurement governance before reporting the rate.',
                    ],
                ],
                [
                    'id' => 'suppliers-active',
                    'example' => false,
                    'label' => ['de' => 'Suppliers Active', 'en' => 'Suppliers active'],
                    'question' => [
                        'de' => 'Wie viele Supplier hatten in der Periode Spend-Aktivität?',
                        'en' => 'How many suppliers had spend activity in the period?',
                    ],
                    'formula' => 'COUNT(DISTINCT supplier_id) FROM purchase_order OR invoice WHERE date IN period',
                    'grain' => ['de' => 'Supplier mit Aktivität', 'en' => 'Supplier with activity'],
                    'dimensions' => ['commodity', 'department', 'chart_of_account'],
                    'fieldsUsed' => ['PurchaseOrder.supplier_id', 'Invoice.supplier_id', 'PurchaseOrder.created-at'],
                    'sourceHints' => [
                        'de' => 'Active über Transaktionen — nicht nur suppliers.status=active.',
                        'en' => 'Active via transactions — not only suppliers.status=active.',
                    ],
                    'adapt' => [
                        'de' => 'Schwellenwert (min. Spend) und One-time Supplier Regeln festlegen.',
                        'en' => 'Lock threshold (min spend) and one-time supplier rules.',
                    ],
                ],
            ],
            'tools' => $financeTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
