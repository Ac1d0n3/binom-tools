<?php

/**
 * Wave 7 supplier library entries — Markets & Insurance (full template depth).
 *
 * Trades/positions and policy/claim data are high sensitivity — prefer aggregates
 * (notional, P&L, incurred, loss ratio) over row-level exposure. Do not load
 * cleartext trade tickets, claim adjuster narratives, or medical notes by default.
 *
 * @param  list<array<string, mixed>>  $crmDimensions
 * @param  list<string>  $crmTools
 * @param  list<string>  $relatedPlaybooks
 * @param  callable(array): list<array<string, mixed>>  $crmMeasures
 * @return list<array<string, mixed>>
 */
return static function (array $crmDimensions, array $crmTools, array $relatedPlaybooks, callable $crmMeasures): array {
    $marketsInsuranceTools = [
        'kpi-definition',
        'pii-recommend-generator',
        'pii-policy-generator',
        'schema-yml-editor',
    ];

    return [
        [
            'id' => 'murex',
            'domain' => 'banking',
            'order' => 250,
            'label' => ['de' => 'Murex', 'en' => 'Murex'],
            'shortPurpose' => [
                'de' => 'Capital Markets: Trade/Position/Risk — aggregierte Exposure- und P&L-Measures; keine Roh-Ticket-Freitexte.',
                'en' => 'Capital markets: trade/position/risk — aggregated exposure and P&L measures; no raw ticket free text.',
            ],
            'entities' => [
                [
                    'id' => 'trade',
                    'label' => ['de' => 'Trade', 'en' => 'Trade'],
                    'description' => [
                        'de' => 'Front-Office Trade Capture — Deal-Economics; Ticket-Freitext und Broker-Notes nicht laden.',
                        'en' => 'Front-office trade capture — deal economics; ticket free text and broker notes not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Trade (dealId)', 'en' => 'One trade (dealId)'],
                    'role' => ['de' => 'Fact-Anker (sensibel)', 'en' => 'Fact anchor (sensitive)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'position',
                    'label' => ['de' => 'Position', 'en' => 'Position'],
                    'description' => [
                        'de' => 'Aggregierte Position je Book/Instrument/Tag — MTM und Exposure; für Reporting bevorzugt.',
                        'en' => 'Aggregated position by book/instrument/date — MTM and exposure; preferred for reporting.',
                    ],
                    'grain' => ['de' => 'Eine Position (Book, Instrument, Positionsdatum)', 'en' => 'One position (book, instrument, position date)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'instrument',
                    'label' => ['de' => 'Instrument', 'en' => 'Instrument'],
                    'description' => [
                        'de' => 'Static Data — Produkttyp, ISIN/CUSIP, Maturity; Referenz-Dimension.',
                        'en' => 'Static data — product type, ISIN/CUSIP, maturity; reference dimension.',
                    ],
                    'grain' => ['de' => 'Ein Instrument (instrumentId / ISIN)', 'en' => 'One instrument (instrumentId / ISIN)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'counterparty',
                    'label' => ['de' => 'Counterparty', 'en' => 'Counterparty'],
                    'description' => [
                        'de' => 'Legal Entity / LEI — Handelsgegenpartei; Name und LEI sind geschäftssensibel.',
                        'en' => 'Legal entity / LEI — trading counterparty; name and LEI are business-sensitive.',
                    ],
                    'grain' => ['de' => 'Ein Counterparty (counterpartyId / LEI)', 'en' => 'One counterparty (counterpartyId / LEI)'],
                    'role' => ['de' => 'Dimension (sensibel)', 'en' => 'Dimension (sensitive)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'book',
                    'label' => ['de' => 'Book / Portfolio', 'en' => 'Book / portfolio'],
                    'description' => [
                        'de' => 'Trading-Book-Hierarchie — Desk-/Entity-Rollup.',
                        'en' => 'Trading book hierarchy — desk/entity rollup.',
                    ],
                    'grain' => ['de' => 'Ein Book (bookId)', 'en' => 'One book (bookId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'market_data',
                    'label' => ['de' => 'Market Data (Curve/Quote)', 'en' => 'Market data (curve/quote)'],
                    'description' => [
                        'de' => 'Rate Curves, FX Rates, Vol Surfaces für Valuation; volle Tick-History nicht laden.',
                        'en' => 'Rate curves, FX rates, vol surfaces for valuation; full tick history not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Market-Data-Punkt (Curve/Quote, As-of)', 'en' => 'One market data point (curve/quote, as-of)'],
                    'role' => ['de' => 'Referenz-Dimension', 'en' => 'Reference dimension'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'cash_flow',
                    'label' => ['de' => 'Cash Flow', 'en' => 'Cash flow'],
                    'description' => [
                        'de' => 'Generierte Cash Flows aus Trades — Settlement-Betrag und -Datum.',
                        'en' => 'Generated cash flows from trades — settlement amount and date.',
                    ],
                    'grain' => ['de' => 'Ein Cash Flow (tradeId, flowId)', 'en' => 'One cash flow (tradeId, flowId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'confirmation',
                    'label' => ['de' => 'Confirmation (Meta)', 'en' => 'Confirmation (meta)'],
                    'description' => [
                        'de' => 'SWIFT/FpML Confirmation-Status Meta — matched/unmatched; Roh-Payload nicht laden.',
                        'en' => 'SWIFT/FpML confirmation status meta — matched/unmatched; raw payload not loaded.',
                    ],
                    'grain' => ['de' => 'Eine Confirmation (confirmationId)', 'en' => 'One confirmation (confirmationId)'],
                    'role' => ['de' => 'Meta-Fact', 'en' => 'Meta fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'risk_result',
                    'label' => ['de' => 'Risk / P&L Result', 'en' => 'Risk / P&L result'],
                    'description' => [
                        'de' => 'Tages-P&L, Sensitivitäten (Greeks), VaR-Beitrag je Book/Trade.',
                        'en' => 'Daily P&L, sensitivities (Greeks), VaR contribution per book/trade.',
                    ],
                    'grain' => ['de' => 'Ein Risk Result (Book/Trade, As-of-Datum)', 'en' => 'One risk result (book/trade, as-of date)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'Trade', 'name' => 'dealId', 'role' => 'key', 'why' => ['de' => 'Trade-Join / Deal-Nummer', 'en' => 'Trade join / deal number']],
                ['entity' => 'Trade', 'name' => 'tradeDate', 'role' => 'measure', 'why' => ['de' => 'Handelstag', 'en' => 'Trade date']],
                ['entity' => 'Trade', 'name' => 'valueDate', 'role' => 'measure', 'why' => ['de' => 'Settlement-Datum', 'en' => 'Settlement date']],
                ['entity' => 'Trade', 'name' => 'productType', 'role' => 'dimension', 'why' => ['de' => 'Produkttyp (Swap, FX Fwd, Bond …)', 'en' => 'Product type (swap, FX fwd, bond …)']],
                ['entity' => 'Trade', 'name' => 'notionalAmount', 'role' => 'measure', 'why' => ['de' => 'Nominalbetrag — geschäftssensibel', 'en' => 'Notional amount — business-sensitive']],
                ['entity' => 'Trade', 'name' => 'currency', 'role' => 'dimension', 'why' => ['de' => 'Trade-Währung', 'en' => 'Trade currency']],
                ['entity' => 'Trade', 'name' => 'counterpartyId', 'role' => 'dimension', 'why' => ['de' => 'Counterparty-Join', 'en' => 'Counterparty join']],
                ['entity' => 'Trade', 'name' => 'bookId', 'role' => 'dimension', 'why' => ['de' => 'Book-Join', 'en' => 'Book join']],
                ['entity' => 'Trade', 'name' => 'traderId', 'role' => 'pii', 'why' => ['de' => 'Trader-Kennung / Workforce-PII', 'en' => 'Trader identifier / workforce PII']],
                ['entity' => 'Trade', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'live / matured / cancelled', 'en' => 'live / matured / cancelled']],
                ['entity' => 'Position', 'name' => 'positionId', 'role' => 'key', 'why' => ['de' => 'Position-Join', 'en' => 'Position join']],
                ['entity' => 'Position', 'name' => 'bookId', 'role' => 'dimension', 'why' => ['de' => 'Book-Join', 'en' => 'Book join']],
                ['entity' => 'Position', 'name' => 'instrumentId', 'role' => 'dimension', 'why' => ['de' => 'Instrument-Join', 'en' => 'Instrument join']],
                ['entity' => 'Position', 'name' => 'positionDate', 'role' => 'measure', 'why' => ['de' => 'Positions-Snapshot-Datum', 'en' => 'Position snapshot date']],
                ['entity' => 'Position', 'name' => 'quantity', 'role' => 'measure', 'why' => ['de' => 'Positionsgröße', 'en' => 'Position size']],
                ['entity' => 'Position', 'name' => 'marketValue', 'role' => 'measure', 'why' => ['de' => 'Mark-to-Market-Wert', 'en' => 'Mark-to-market value']],
                ['entity' => 'Instrument', 'name' => 'instrumentId', 'role' => 'key', 'why' => ['de' => 'Instrument-Join', 'en' => 'Instrument join']],
                ['entity' => 'Instrument', 'name' => 'isin', 'role' => 'dimension', 'why' => ['de' => 'ISIN/CUSIP', 'en' => 'ISIN/CUSIP']],
                ['entity' => 'Instrument', 'name' => 'instrumentType', 'role' => 'dimension', 'why' => ['de' => 'Produktklasse (Static Data)', 'en' => 'Product class (static data)']],
                ['entity' => 'Instrument', 'name' => 'maturityDate', 'role' => 'measure', 'why' => ['de' => 'Fälligkeit', 'en' => 'Maturity']],
                ['entity' => 'Counterparty', 'name' => 'counterpartyId', 'role' => 'key', 'why' => ['de' => 'Counterparty-Join', 'en' => 'Counterparty join']],
                ['entity' => 'Counterparty', 'name' => 'lei', 'role' => 'pii', 'why' => ['de' => 'Legal Entity Identifier — geschäftssensibel', 'en' => 'Legal entity identifier — business-sensitive']],
                ['entity' => 'Counterparty', 'name' => 'name', 'role' => 'pii', 'why' => ['de' => 'Legal-Entity-Name — geschäftssensibel', 'en' => 'Legal entity name — business-sensitive']],
                ['entity' => 'Counterparty', 'name' => 'counterpartyType', 'role' => 'dimension', 'why' => ['de' => 'Bank / Corporate / Sovereign', 'en' => 'Bank / corporate / sovereign']],
                ['entity' => 'Book', 'name' => 'bookId', 'role' => 'key', 'why' => ['de' => 'Book-Join', 'en' => 'Book join']],
                ['entity' => 'Book', 'name' => 'bookName', 'role' => 'dimension', 'why' => ['de' => 'Book-Label', 'en' => 'Book label']],
                ['entity' => 'Book', 'name' => 'deskId', 'role' => 'dimension', 'why' => ['de' => 'Desk-Rollup', 'en' => 'Desk rollup']],
                ['entity' => 'MarketData', 'name' => 'curveId', 'role' => 'key', 'why' => ['de' => 'Curve/Quote-Join', 'en' => 'Curve/quote join']],
                ['entity' => 'MarketData', 'name' => 'asOfDate', 'role' => 'measure', 'why' => ['de' => 'Stichtag', 'en' => 'As-of date']],
                ['entity' => 'MarketData', 'name' => 'quoteValue', 'role' => 'measure', 'why' => ['de' => 'Rate/Quote-Wert', 'en' => 'Rate/quote value']],
                ['entity' => 'CashFlow', 'name' => 'cashFlowId', 'role' => 'key', 'why' => ['de' => 'Cash-Flow-Join', 'en' => 'Cash flow join']],
                ['entity' => 'CashFlow', 'name' => 'tradeId', 'role' => 'dimension', 'why' => ['de' => 'Trade-Rückjoin', 'en' => 'Trade back-join']],
                ['entity' => 'CashFlow', 'name' => 'flowDate', 'role' => 'measure', 'why' => ['de' => 'Zahlungsdatum', 'en' => 'Flow date']],
                ['entity' => 'CashFlow', 'name' => 'flowAmount', 'role' => 'measure', 'why' => ['de' => 'Zahlungsbetrag', 'en' => 'Flow amount']],
                ['entity' => 'Confirmation', 'name' => 'confirmationId', 'role' => 'key', 'why' => ['de' => 'Confirmation-Join', 'en' => 'Confirmation join']],
                ['entity' => 'Confirmation', 'name' => 'tradeId', 'role' => 'dimension', 'why' => ['de' => 'Trade-Rückjoin', 'en' => 'Trade back-join']],
                ['entity' => 'Confirmation', 'name' => 'matchStatus', 'role' => 'dimension', 'why' => ['de' => 'matched / unmatched / disputed', 'en' => 'matched / unmatched / disputed']],
                ['entity' => 'RiskResult', 'name' => 'riskResultId', 'role' => 'key', 'why' => ['de' => 'Risk-Result-Join', 'en' => 'Risk result join']],
                ['entity' => 'RiskResult', 'name' => 'bookId', 'role' => 'dimension', 'why' => ['de' => 'Book-Rollup', 'en' => 'Book rollup']],
                ['entity' => 'RiskResult', 'name' => 'asOfDate', 'role' => 'measure', 'why' => ['de' => 'Stichtag', 'en' => 'As-of date']],
                ['entity' => 'RiskResult', 'name' => 'pnlAmount', 'role' => 'measure', 'why' => ['de' => 'P&L-Betrag', 'en' => 'P&L amount']],
                ['entity' => 'RiskResult', 'name' => 'varContribution', 'role' => 'measure', 'why' => ['de' => 'VaR-Beitrag', 'en' => 'VaR contribution']],
            ],
            'skipTables' => [
                [
                    'name' => 'Trade ticket free text / broker notes',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Ticket-Freitext und Broker-Notes — geschäftssensible Economics und oft Personenbezug.',
                        'en' => 'Ticket free text and broker notes — business-sensitive economics and often personal references.',
                    ],
                ],
                [
                    'name' => 'Raw FpML / SWIFT confirmation payloads',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Rohe Message-Payloads — Volumen und Redundanz zum strukturierten Match-Status.',
                        'en' => 'Raw message payloads — volume and redundant with the structured match status.',
                    ],
                ],
                [
                    'name' => 'Voice / chat trader communications (turret, IB chat)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Surveillance-Daten — nicht für Analytics-Warehouse; eigene Compliance-Systeme.',
                        'en' => 'Surveillance data — not for the analytics warehouse; belongs in dedicated compliance systems.',
                    ],
                ],
                [
                    'name' => 'Full intraday market data tick history',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Hohes Volumen — EOD-Snapshots reichen für Valuation-Reporting.',
                        'en' => 'High volume — EOD snapshots suffice for valuation reporting.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Trade ticket free text / broker notes', 'reason' => ['de' => 'Sensible Economics + Personenbezug', 'en' => 'Sensitive economics + personal references']],
                ['name' => 'Raw FpML/SWIFT confirmation payloads', 'reason' => ['de' => 'Volumen — Match-Status reicht', 'en' => 'Volume — match status suffices']],
                ['name' => 'Trader chat / voice recordings', 'reason' => ['de' => 'Surveillance, kein Analytics-Kern', 'en' => 'Surveillance, not analytics core']],
                ['name' => 'Intraday tick history (bulk)', 'reason' => ['de' => 'Volumen — EOD-Snapshots bevorzugen', 'en' => 'Volume — prefer EOD snapshots']],
            ],
            'dimensions' => [
                [
                    'id' => 'book',
                    'label' => ['de' => 'Book', 'en' => 'Book'],
                    'grain' => ['de' => 'trade.bookId / position.bookId', 'en' => 'trade.bookId / position.bookId'],
                    'notes' => [
                        'de' => 'Primärer Trading-Slice; Desk-Rollup über book.deskId.',
                        'en' => 'Primary trading slice; desk rollup via book.deskId.',
                    ],
                ],
                [
                    'id' => 'product_type',
                    'label' => ['de' => 'Product Type', 'en' => 'Product type'],
                    'grain' => ['de' => 'trade.productType / instrument.instrumentType', 'en' => 'trade.productType / instrument.instrumentType'],
                    'notes' => [
                        'de' => 'Asset-Class-Gruppierung (Rates, FX, Credit …) vor dem Mart festlegen.',
                        'en' => 'Lock asset-class grouping (rates, FX, credit …) before the mart.',
                    ],
                ],
                [
                    'id' => 'currency',
                    'label' => ['de' => 'Currency', 'en' => 'Currency'],
                    'grain' => ['de' => 'trade.currency', 'en' => 'trade.currency'],
                    'notes' => [
                        'de' => 'FX-Umrechnung für Cross-Currency-Aggregate klären.',
                        'en' => 'Clarify FX conversion for cross-currency aggregates.',
                    ],
                ],
                [
                    'id' => 'counterparty_type',
                    'label' => ['de' => 'Counterparty Type', 'en' => 'Counterparty type'],
                    'grain' => ['de' => 'counterparty.counterpartyType', 'en' => 'counterparty.counterpartyType'],
                    'notes' => [
                        'de' => 'Bank/Corporate/Sovereign-Schnitt statt Klartext-Namen.',
                        'en' => 'Bank/corporate/sovereign slice instead of cleartext names.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Counterparty',
                    'fields' => ['name', 'lei'],
                    'treatment' => [
                        'de' => 'Legal-Entity-Name/LEI — geschäftssensible Identifikatoren; counterpartyId als Join bevorzugen.',
                        'en' => 'Legal entity name/LEI — business-sensitive identifiers; prefer counterpartyId as join.',
                    ],
                ],
                [
                    'entity' => 'Trade',
                    'fields' => ['traderId'],
                    'treatment' => [
                        'de' => 'Trader-Kennung — Workforce-PII; Zugriff auf Front-Office-Rollen beschränken.',
                        'en' => 'Trader identifier — workforce PII; restrict access to front-office roles.',
                    ],
                ],
                [
                    'entity' => 'Trade',
                    'fields' => ['notionalAmount', 'counterpartyId'],
                    'treatment' => [
                        'de' => 'Deal-Economics sind geschäftssensibel — Zugriff wie PII behandeln; Aggregate bevorzugen.',
                        'en' => 'Deal economics are business-sensitive — treat access like PII; prefer aggregates.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'dealId, counterpartyId/LEI, bookId, instrumentId/ISIN.',
                        'en' => 'dealId, counterpartyId/LEI, bookId, instrumentId/ISIN.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Trade, Position, Counterparty, Book — ohne Roh-Tickets oder Broker-Notes.',
                        'en' => 'Trade, position, counterparty, book — no raw tickets or broker notes.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'notional-outstanding',
                    'example' => true,
                    'label' => ['de' => 'Notional Outstanding', 'en' => 'Notional outstanding'],
                    'question' => [
                        'de' => 'Wie hoch ist das offene Nominalvolumen je Book/Produkt?',
                        'en' => 'How much notional volume is outstanding by book/product?',
                    ],
                    'formula' => "SUM(notionalAmount) FROM trade WHERE status = 'live'",
                    'grain' => ['de' => 'Live Trade', 'en' => 'Live trade'],
                    'dimensions' => ['book', 'product_type', 'currency'],
                    'fieldsUsed' => ['Trade.notionalAmount', 'Trade.status', 'Trade.bookId', 'Trade.productType', 'Trade.currency'],
                    'sourceHints' => [
                        'de' => 'status=live; FX-Umrechnung für Multi-Currency-Summen festlegen.',
                        'en' => 'status=live; lock FX conversion for multi-currency sums.',
                    ],
                    'adapt' => [
                        'de' => 'Netting je Counterparty optional als separate Variante.',
                        'en' => 'Optional variant: netting per counterparty.',
                    ],
                ],
                [
                    'id' => 'trade-count',
                    'example' => true,
                    'label' => ['de' => 'Trade Count', 'en' => 'Trade count'],
                    'question' => [
                        'de' => 'Wie viele Trades wurden in der Periode gehandelt?',
                        'en' => 'How many trades were executed in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM trade WHERE tradeDate IN period',
                    'grain' => ['de' => 'Trade', 'en' => 'Trade'],
                    'dimensions' => ['book', 'product_type', 'counterparty_type'],
                    'fieldsUsed' => ['Trade.dealId', 'Trade.tradeDate', 'Trade.bookId', 'Trade.productType'],
                    'sourceHints' => [
                        'de' => 'Amendments/Cancels nicht als neue Trades doppelzählen.',
                        'en' => 'Do not double-count amendments/cancels as new trades.',
                    ],
                    'adapt' => [
                        'de' => 'Internal Book-to-Book Trades optional ausschließen.',
                        'en' => 'Optionally exclude internal book-to-book trades.',
                    ],
                ],
                [
                    'id' => 'pnl-daily',
                    'example' => false,
                    'label' => ['de' => 'Daily P&L', 'en' => 'Daily P&L'],
                    'question' => [
                        'de' => 'Wie hoch ist der tägliche P&L je Book?',
                        'en' => 'What is the daily P&L per book?',
                    ],
                    'formula' => 'SUM(pnlAmount) FROM risk_result WHERE asOfDate = :date',
                    'grain' => ['de' => 'Risk Result (Book, Tag)', 'en' => 'Risk result (book, day)'],
                    'dimensions' => ['book', 'product_type'],
                    'fieldsUsed' => ['RiskResult.pnlAmount', 'RiskResult.bookId', 'RiskResult.asOfDate'],
                    'sourceHints' => [
                        'de' => 'Realized vs. Unrealized P&L in der Definition trennen.',
                        'en' => 'Separate realized vs unrealized P&L in the definition.',
                    ],
                    'adapt' => [
                        'de' => 'MTD/YTD-Rollup als zusätzliche Variante.',
                        'en' => 'MTD/YTD rollup as an additional variant.',
                    ],
                ],
                [
                    'id' => 'var-exposure',
                    'example' => false,
                    'label' => ['de' => 'VaR Exposure', 'en' => 'VaR exposure'],
                    'question' => [
                        'de' => 'Wie hoch ist der aggregierte VaR-Beitrag je Book?',
                        'en' => 'What is the aggregated VaR contribution per book?',
                    ],
                    'formula' => 'SUM(varContribution) FROM risk_result WHERE asOfDate = :date',
                    'grain' => ['de' => 'Risk Result (Book, Tag)', 'en' => 'Risk result (book, day)'],
                    'dimensions' => ['book', 'counterparty_type'],
                    'fieldsUsed' => ['RiskResult.varContribution', 'RiskResult.bookId', 'RiskResult.asOfDate'],
                    'sourceHints' => [
                        'de' => 'VaR-Modell (Historical vs. Parametric) dokumentieren.',
                        'en' => 'Document the VaR model (historical vs parametric).',
                    ],
                    'adapt' => [
                        'de' => 'Diversification-Effekt bei Aggregation über Books beachten.',
                        'en' => 'Account for diversification effect when aggregating across books.',
                    ],
                ],
                [
                    'id' => 'unmatched-confirmations',
                    'example' => false,
                    'label' => ['de' => 'Unmatched Confirmations', 'en' => 'Unmatched confirmations'],
                    'question' => [
                        'de' => 'Wie viele Confirmations sind unmatched/disputed?',
                        'en' => 'How many confirmations are unmatched/disputed?',
                    ],
                    'formula' => "COUNT(*) FROM confirmation WHERE matchStatus <> 'matched'",
                    'grain' => ['de' => 'Confirmation', 'en' => 'Confirmation'],
                    'dimensions' => ['counterparty_type'],
                    'fieldsUsed' => ['Confirmation.matchStatus', 'Confirmation.tradeId'],
                    'sourceHints' => [
                        'de' => 'Settlement-Risiko-Proxy — Age-Bucket (>T+1) ergänzen.',
                        'en' => 'Settlement-risk proxy — add an age bucket (>T+1).',
                    ],
                    'adapt' => [
                        'de' => 'Disputed getrennt von schlicht Pending zählen.',
                        'en' => 'Count disputed separately from simply pending.',
                    ],
                ],
            ],
            'tools' => $marketsInsuranceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'fis',
            'domain' => 'banking',
            'order' => 260,
            'label' => ['de' => 'FIS', 'en' => 'FIS'],
            'shortPurpose' => [
                'de' => 'Banking & Payments: Account/Transaction/Payment — aggregierte Balance- und Volumen-Measures; kein PAN/KYC-Klartext.',
                'en' => 'Banking & payments: account/transaction/payment — aggregated balance and volume measures; no PAN/KYC cleartext.',
            ],
            'entities' => [
                [
                    'id' => 'customer',
                    'label' => ['de' => 'Customer', 'en' => 'Customer'],
                    'description' => [
                        'de' => 'Core-Banking-Party — Tax-ID, Name, Adresse; direkte PII.',
                        'en' => 'Core-banking party — tax ID, name, address; direct PII.',
                    ],
                    'grain' => ['de' => 'Ein Customer (customerId)', 'en' => 'One customer (customerId)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'account',
                    'label' => ['de' => 'Account', 'en' => 'Account'],
                    'description' => [
                        'de' => 'Deposit/Loan-Account — Fact-Anker für Balance- und Produkt-KPIs.',
                        'en' => 'Deposit/loan account — fact anchor for balance and product KPIs.',
                    ],
                    'grain' => ['de' => 'Ein Account (accountId)', 'en' => 'One account (accountId)'],
                    'role' => ['de' => 'Fact-Anker', 'en' => 'Fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'transaction',
                    'label' => ['de' => 'Transaction', 'en' => 'Transaction'],
                    'description' => [
                        'de' => 'Buchungssatz / Posting — High-Volume-Fact; Statement-Text nicht laden.',
                        'en' => 'Posting / booking entry — high-volume fact; statement text not loaded.',
                    ],
                    'grain' => ['de' => 'Eine Transaction (transactionId)', 'en' => 'One transaction (transactionId)'],
                    'role' => ['de' => 'Fact (High Volume)', 'en' => 'Fact (high volume)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'payment',
                    'label' => ['de' => 'Payment', 'en' => 'Payment'],
                    'description' => [
                        'de' => 'Zahlungsauftrag (Wire/ACH) — Beneficiary-Account ist PII.',
                        'en' => 'Payment instruction (wire/ACH) — beneficiary account is PII.',
                    ],
                    'grain' => ['de' => 'Ein Payment (paymentId)', 'en' => 'One payment (paymentId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'card',
                    'label' => ['de' => 'Card', 'en' => 'Card'],
                    'description' => [
                        'de' => 'Karten-Account-Link — nur maskierte PAN, nie Klartext-PAN/CVV.',
                        'en' => 'Card-to-account link — masked PAN only, never cleartext PAN/CVV.',
                    ],
                    'grain' => ['de' => 'Eine Card (cardId)', 'en' => 'One card (cardId)'],
                    'role' => ['de' => 'Dimension (PCI-Scope)', 'en' => 'Dimension (PCI scope)'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'loan',
                    'label' => ['de' => 'Loan', 'en' => 'Loan'],
                    'description' => [
                        'de' => 'Loan-Detail — Principal, Rate, Maturity je Account.',
                        'en' => 'Loan detail — principal, rate, maturity per account.',
                    ],
                    'grain' => ['de' => 'Ein Loan (loanId)', 'en' => 'One loan (loanId)'],
                    'role' => ['de' => 'Fact / Dimension', 'en' => 'Fact / dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product', 'en' => 'Product'],
                    'description' => [
                        'de' => 'Produkt-/Rate-Plan — Zins- und Gebührenstruktur.',
                        'en' => 'Product / rate plan — interest and fee structure.',
                    ],
                    'grain' => ['de' => 'Ein Product (productId)', 'en' => 'One product (productId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'branch',
                    'label' => ['de' => 'Branch / Channel', 'en' => 'Branch / channel'],
                    'description' => [
                        'de' => 'Filiale / Channel-Dimension für Volumen-Rollups.',
                        'en' => 'Branch / channel dimension for volume rollups.',
                    ],
                    'grain' => ['de' => 'Eine Branch (branchId)', 'en' => 'One branch (branchId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'gl_entry',
                    'label' => ['de' => 'GL Entry', 'en' => 'GL entry'],
                    'description' => [
                        'de' => 'General-Ledger-Buchung — Accounting-Fact für Abstimmung.',
                        'en' => 'General ledger posting — accounting fact for reconciliation.',
                    ],
                    'grain' => ['de' => 'Eine GL Entry (glEntryId)', 'en' => 'One GL entry (glEntryId)'],
                    'role' => ['de' => 'Accounting-Fact', 'en' => 'Accounting fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Customer', 'name' => 'customerId', 'role' => 'key', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'Customer', 'name' => 'taxId', 'role' => 'pii', 'why' => ['de' => 'Tax-ID/SSN — direkte PII', 'en' => 'Tax ID/SSN — direct PII']],
                ['entity' => 'Customer', 'name' => 'dateOfBirth', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'Customer', 'name' => 'fullName', 'role' => 'pii', 'why' => ['de' => 'Name / PII', 'en' => 'Name / PII']],
                ['entity' => 'Customer', 'name' => 'address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Customer', 'name' => 'segment', 'role' => 'dimension', 'why' => ['de' => 'Kundensegment', 'en' => 'Customer segment']],
                ['entity' => 'Account', 'name' => 'accountId', 'role' => 'key', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'customerId', 'role' => 'dimension', 'why' => ['de' => 'Customer-Join', 'en' => 'Customer join']],
                ['entity' => 'Account', 'name' => 'productId', 'role' => 'dimension', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'Account', 'name' => 'branchId', 'role' => 'dimension', 'why' => ['de' => 'Branch-Join', 'en' => 'Branch join']],
                ['entity' => 'Account', 'name' => 'openDate', 'role' => 'measure', 'why' => ['de' => 'Account-Eröffnung', 'en' => 'Account opened']],
                ['entity' => 'Account', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'active / closed / dormant', 'en' => 'active / closed / dormant']],
                ['entity' => 'Account', 'name' => 'balance', 'role' => 'measure', 'why' => ['de' => 'Kontostand (Snapshot)', 'en' => 'Account balance (snapshot)']],
                ['entity' => 'Transaction', 'name' => 'transactionId', 'role' => 'key', 'why' => ['de' => 'Transaction-Join', 'en' => 'Transaction join']],
                ['entity' => 'Transaction', 'name' => 'accountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Transaction', 'name' => 'postingDate', 'role' => 'measure', 'why' => ['de' => 'Buchungsdatum', 'en' => 'Posting date']],
                ['entity' => 'Transaction', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Buchungsbetrag', 'en' => 'Posting amount']],
                ['entity' => 'Transaction', 'name' => 'transactionType', 'role' => 'dimension', 'why' => ['de' => 'Typ (deposit, withdrawal, fee …)', 'en' => 'Type (deposit, withdrawal, fee …)']],
                ['entity' => 'Transaction', 'name' => 'channel', 'role' => 'dimension', 'why' => ['de' => 'Kanal (branch, ATM, online)', 'en' => 'Channel (branch, ATM, online)']],
                ['entity' => 'Payment', 'name' => 'paymentId', 'role' => 'key', 'why' => ['de' => 'Payment-Join', 'en' => 'Payment join']],
                ['entity' => 'Payment', 'name' => 'accountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Payment', 'name' => 'beneficiaryAccount', 'role' => 'pii', 'why' => ['de' => 'Begünstigten-IBAN — PII', 'en' => 'Beneficiary IBAN — PII']],
                ['entity' => 'Payment', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Zahlungsbetrag', 'en' => 'Payment amount']],
                ['entity' => 'Payment', 'name' => 'paymentType', 'role' => 'dimension', 'why' => ['de' => 'wire / ACH / SEPA', 'en' => 'wire / ACH / SEPA']],
                ['entity' => 'Payment', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'pending / completed / failed', 'en' => 'pending / completed / failed']],
                ['entity' => 'Payment', 'name' => 'valueDate', 'role' => 'measure', 'why' => ['de' => 'Wertstellungsdatum', 'en' => 'Value date']],
                ['entity' => 'Card', 'name' => 'cardId', 'role' => 'key', 'why' => ['de' => 'Card-Join', 'en' => 'Card join']],
                ['entity' => 'Card', 'name' => 'accountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Card', 'name' => 'maskedPan', 'role' => 'pii', 'why' => ['de' => 'Maskierte PAN — PCI-Scope', 'en' => 'Masked PAN — PCI scope']],
                ['entity' => 'Card', 'name' => 'cardStatus', 'role' => 'dimension', 'why' => ['de' => 'active / blocked / expired', 'en' => 'active / blocked / expired']],
                ['entity' => 'Loan', 'name' => 'loanId', 'role' => 'key', 'why' => ['de' => 'Loan-Join', 'en' => 'Loan join']],
                ['entity' => 'Loan', 'name' => 'accountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Loan', 'name' => 'principalAmount', 'role' => 'measure', 'why' => ['de' => 'Darlehensbetrag', 'en' => 'Principal amount']],
                ['entity' => 'Loan', 'name' => 'interestRate', 'role' => 'measure', 'why' => ['de' => 'Zinssatz', 'en' => 'Interest rate']],
                ['entity' => 'Loan', 'name' => 'maturityDate', 'role' => 'measure', 'why' => ['de' => 'Laufzeitende', 'en' => 'Maturity date']],
                ['entity' => 'Product', 'name' => 'productId', 'role' => 'key', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'Product', 'name' => 'productName', 'role' => 'dimension', 'why' => ['de' => 'Produkt-Label', 'en' => 'Product label']],
                ['entity' => 'Product', 'name' => 'productType', 'role' => 'dimension', 'why' => ['de' => 'deposit / loan / card', 'en' => 'deposit / loan / card']],
                ['entity' => 'Branch', 'name' => 'branchId', 'role' => 'key', 'why' => ['de' => 'Branch-Join', 'en' => 'Branch join']],
                ['entity' => 'Branch', 'name' => 'branchName', 'role' => 'dimension', 'why' => ['de' => 'Filial-Label', 'en' => 'Branch label']],
                ['entity' => 'Branch', 'name' => 'region', 'role' => 'dimension', 'why' => ['de' => 'Region-Rollup', 'en' => 'Region rollup']],
                ['entity' => 'GLEntry', 'name' => 'glEntryId', 'role' => 'key', 'why' => ['de' => 'GL-Entry-Join', 'en' => 'GL entry join']],
                ['entity' => 'GLEntry', 'name' => 'accountId', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'GLEntry', 'name' => 'glCode', 'role' => 'dimension', 'why' => ['de' => 'GL-Konto / Chart of Accounts', 'en' => 'GL account / chart of accounts']],
                ['entity' => 'GLEntry', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'GL-Betrag', 'en' => 'GL amount']],
            ],
            'skipTables' => [
                [
                    'name' => 'Full card PAN / CVV / magstripe data',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'PCI-DSS-Scope — nie Klartext-PAN/CVV im Warehouse landen lassen.',
                        'en' => 'PCI-DSS scope — never land cleartext PAN/CVV in the warehouse.',
                    ],
                ],
                [
                    'name' => 'KYC identity documents (scans, ID images)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Identitätsdokumente — hochsensibel, gehören ins KYC-System, nicht ins Warehouse.',
                        'en' => 'Identity documents — highly sensitive, belong in the KYC system, not the warehouse.',
                    ],
                ],
                [
                    'name' => 'Full account statement text/PDF exports',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Unstrukturiert und groß — strukturierte Postings reichen für Analytics.',
                        'en' => 'Unstructured and large — structured postings suffice for analytics.',
                    ],
                ],
                [
                    'name' => 'Raw core-banking audit trail / batch-job logs',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Technisches Rauschen und Volumen — kein Mart-Nutzen.',
                        'en' => 'Technical noise and volume — no mart value.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Full card PAN / CVV data', 'reason' => ['de' => 'PCI-DSS — nie Klartext', 'en' => 'PCI-DSS — never cleartext']],
                ['name' => 'KYC identity documents', 'reason' => ['de' => 'Hochsensibel — nicht im Warehouse', 'en' => 'Highly sensitive — not in the warehouse']],
                ['name' => 'Full account statement text/PDF exports', 'reason' => ['de' => 'Unstrukturiert, groß', 'en' => 'Unstructured, large']],
                ['name' => 'Raw core-banking audit trail dumps', 'reason' => ['de' => 'Volumen, technisches Rauschen', 'en' => 'Volume, technical noise']],
            ],
            'dimensions' => [
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product', 'en' => 'Product'],
                    'grain' => ['de' => 'account.productId', 'en' => 'account.productId'],
                    'notes' => [
                        'de' => 'Deposit vs. Loan vs. Card getrennt betrachten.',
                        'en' => 'Consider deposit vs loan vs card separately.',
                    ],
                ],
                [
                    'id' => 'branch',
                    'label' => ['de' => 'Branch', 'en' => 'Branch'],
                    'grain' => ['de' => 'account.branchId', 'en' => 'account.branchId'],
                    'notes' => [
                        'de' => 'Region-Rollup über branch.region.',
                        'en' => 'Region rollup via branch.region.',
                    ],
                ],
                [
                    'id' => 'channel',
                    'label' => ['de' => 'Channel', 'en' => 'Channel'],
                    'grain' => ['de' => 'transaction.channel', 'en' => 'transaction.channel'],
                    'notes' => [
                        'de' => 'Branch vs. ATM vs. Online-Kanal für Volumen-Trends.',
                        'en' => 'Branch vs ATM vs online channel for volume trends.',
                    ],
                ],
                [
                    'id' => 'customer_segment',
                    'label' => ['de' => 'Customer Segment', 'en' => 'Customer segment'],
                    'grain' => ['de' => 'customer.segment', 'en' => 'customer.segment'],
                    'notes' => [
                        'de' => 'Retail vs. Commercial vs. Private Banking konsistent pflegen.',
                        'en' => 'Keep retail vs commercial vs private banking consistent.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Customer',
                    'fields' => ['taxId', 'dateOfBirth', 'fullName', 'address'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — RAW einschränken, Curated nur customerId.',
                        'en' => 'Direct identifiers — restrict RAW, curated customerId only.',
                    ],
                ],
                [
                    'entity' => 'Payment',
                    'fields' => ['beneficiaryAccount'],
                    'treatment' => [
                        'de' => 'Begünstigten-IBAN ist PII — hashen/maskieren für Analytics.',
                        'en' => 'Beneficiary IBAN is PII — hash/mask for analytics.',
                    ],
                ],
                [
                    'entity' => 'Card',
                    'fields' => ['maskedPan'],
                    'treatment' => [
                        'de' => 'Auch maskierte PAN ist sensibel — nie unmaskieren, PCI-Scope beachten.',
                        'en' => 'Even masked PAN is sensitive — never unmask, respect PCI scope.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'customerId, taxId (gehasht), accountId, cardId.',
                        'en' => 'customerId, taxId (hashed), accountId, cardId.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Customer, Account, Card, Loan + Core-Banking-/Payments-Hub-Kopien.',
                        'en' => 'Customer, account, card, loan + core-banking/payments-hub copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'deposit-balance-outstanding',
                    'example' => true,
                    'label' => ['de' => 'Deposit Balance Outstanding', 'en' => 'Deposit balance outstanding'],
                    'question' => [
                        'de' => 'Wie hoch ist der offene Einlagenbestand?',
                        'en' => 'How much deposit balance is outstanding?',
                    ],
                    'formula' => "SUM(balance) FROM account WHERE productType = 'deposit' AND status = 'active'",
                    'grain' => ['de' => 'Deposit Account (Snapshot)', 'en' => 'Deposit account (snapshot)'],
                    'dimensions' => ['product', 'branch'],
                    'fieldsUsed' => ['Account.balance', 'Account.productId', 'Account.branchId', 'Account.status'],
                    'sourceHints' => [
                        'de' => 'Snapshot-Datum festlegen; Dormant-Accounts separat behandeln.',
                        'en' => 'Lock snapshot date; treat dormant accounts separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nach Product-Subtyp (savings vs checking) aufsplitten.',
                        'en' => 'Split by product subtype (savings vs checking).',
                    ],
                ],
                [
                    'id' => 'transaction-volume',
                    'example' => true,
                    'label' => ['de' => 'Transaction Volume', 'en' => 'Transaction volume'],
                    'question' => [
                        'de' => 'Wie viele Transactions gab es in der Periode?',
                        'en' => 'How many transactions occurred in the period?',
                    ],
                    'formula' => 'COUNT(*) FROM transaction WHERE postingDate IN period',
                    'grain' => ['de' => 'Transaction', 'en' => 'Transaction'],
                    'dimensions' => ['channel', 'branch'],
                    'fieldsUsed' => ['Transaction.transactionId', 'Transaction.postingDate', 'Transaction.channel', 'Transaction.accountId'],
                    'sourceHints' => [
                        'de' => 'Reversals/Storno-Buchungen in der Definition klären.',
                        'en' => 'Clarify reversals/storno postings in the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Nach transactionType (deposit/withdrawal/fee) aufsplitten.',
                        'en' => 'Split by transactionType (deposit/withdrawal/fee).',
                    ],
                ],
                [
                    'id' => 'payment-count',
                    'example' => false,
                    'label' => ['de' => 'Payment Count', 'en' => 'Payment count'],
                    'question' => [
                        'de' => 'Wie viele Payments wurden in der Periode abgeschlossen?',
                        'en' => 'How many payments were completed in the period?',
                    ],
                    'formula' => "COUNT(*) FROM payment WHERE valueDate IN period AND status = 'completed'",
                    'grain' => ['de' => 'Completed Payment', 'en' => 'Completed payment'],
                    'dimensions' => ['channel'],
                    'fieldsUsed' => ['Payment.paymentId', 'Payment.valueDate', 'Payment.status', 'Payment.paymentType'],
                    'sourceHints' => [
                        'de' => 'Failed/rejected Payments getrennt tracken.',
                        'en' => 'Track failed/rejected payments separately.',
                    ],
                    'adapt' => [
                        'de' => 'Nach paymentType (wire/ACH/SEPA) aufsplitten.',
                        'en' => 'Split by paymentType (wire/ACH/SEPA).',
                    ],
                ],
                [
                    'id' => 'loan-balance-outstanding',
                    'example' => false,
                    'label' => ['de' => 'Loan Balance Outstanding', 'en' => 'Loan balance outstanding'],
                    'question' => [
                        'de' => 'Wie hoch ist der offene Darlehensbestand?',
                        'en' => 'How much loan balance is outstanding?',
                    ],
                    'formula' => "SUM(principalAmount) FROM loan JOIN account ON loan.accountId = account.accountId WHERE account.status = 'active'",
                    'grain' => ['de' => 'Active Loan', 'en' => 'Active loan'],
                    'dimensions' => ['product', 'branch'],
                    'fieldsUsed' => ['Loan.principalAmount', 'Loan.accountId', 'Account.status', 'Account.productId'],
                    'sourceHints' => [
                        'de' => 'Amortisierten Principal vs. ursprünglichen Betrag klären.',
                        'en' => 'Clarify amortized principal vs original amount.',
                    ],
                    'adapt' => [
                        'de' => 'Delinquency-Buckets als separate Risk-Variante.',
                        'en' => 'Delinquency buckets as a separate risk variant.',
                    ],
                ],
                [
                    'id' => 'nsf-overdraft-count',
                    'example' => false,
                    'label' => ['de' => 'NSF / Overdraft Count', 'en' => 'NSF / overdraft count'],
                    'question' => [
                        'de' => 'Wie viele NSF-/Overdraft-Fees fielen in der Periode an?',
                        'en' => 'How many NSF/overdraft fees occurred in the period?',
                    ],
                    'formula' => "COUNT(*) FROM transaction WHERE transactionType = 'nsf_fee' AND postingDate IN period",
                    'grain' => ['de' => 'NSF-Fee-Transaction', 'en' => 'NSF fee transaction'],
                    'dimensions' => ['branch', 'customer_segment'],
                    'fieldsUsed' => ['Transaction.transactionType', 'Transaction.postingDate', 'Transaction.accountId'],
                    'sourceHints' => [
                        'de' => 'transactionType-Codes je Core-Banking-Instanz verifizieren.',
                        'en' => 'Verify transactionType codes per core-banking instance.',
                    ],
                    'adapt' => [
                        'de' => 'Distinct Accounts betroffen vs. Fee-Count unterscheiden.',
                        'en' => 'Distinguish distinct accounts affected vs fee count.',
                    ],
                ],
            ],
            'tools' => $marketsInsuranceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'guidewire',
            'domain' => 'insurance',
            'order' => 270,
            'label' => ['de' => 'Guidewire', 'en' => 'Guidewire'],
            'shortPurpose' => [
                'de' => 'Insurance Core: Policy/Claim — aggregierte Premium-/Loss-Ratio-Measures; keine Adjuster-Notizen/Medical Docs.',
                'en' => 'Insurance core: policy/claim — aggregated premium/loss-ratio measures; no adjuster notes/medical docs.',
            ],
            'entities' => [
                [
                    'id' => 'account',
                    'label' => ['de' => 'Account', 'en' => 'Account'],
                    'description' => [
                        'de' => 'Insurance Account / Party (PolicyCenter) — Insured-Stammdaten, direkte PII.',
                        'en' => 'Insurance account / party (PolicyCenter) — insured master data, direct PII.',
                    ],
                    'grain' => ['de' => 'Ein Account (accountNumber)', 'en' => 'One account (accountNumber)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'policy',
                    'label' => ['de' => 'Policy', 'en' => 'Policy'],
                    'description' => [
                        'de' => 'Policy-Kopf — LOB, Effective/Expiration, Premium; Fact-Anker.',
                        'en' => 'Policy header — LOB, effective/expiration, premium; fact anchor.',
                    ],
                    'grain' => ['de' => 'Eine Policy (policyNumber)', 'en' => 'One policy (policyNumber)'],
                    'role' => ['de' => 'Fact-Anker', 'en' => 'Fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'policy_period',
                    'label' => ['de' => 'Policy Period', 'en' => 'Policy period'],
                    'description' => [
                        'de' => 'Policy-Term je Renewal — Perioden-Grain für In-Force-Measures.',
                        'en' => 'Policy term per renewal — period grain for in-force measures.',
                    ],
                    'grain' => ['de' => 'Ein Policy Period (periodId)', 'en' => 'One policy period (periodId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'coverage',
                    'label' => ['de' => 'Coverage', 'en' => 'Coverage'],
                    'description' => [
                        'de' => 'Coverage/Line — Limit und Deductible je Policy.',
                        'en' => 'Coverage/line — limit and deductible per policy.',
                    ],
                    'grain' => ['de' => 'Eine Coverage (coverageId)', 'en' => 'One coverage (coverageId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'claim',
                    'label' => ['de' => 'Claim', 'en' => 'Claim'],
                    'description' => [
                        'de' => 'ClaimCenter-Claim — Loss/Reported-Datum, Status; Adjuster-Notizen nicht laden.',
                        'en' => 'ClaimCenter claim — loss/reported date, status; adjuster notes not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Claim (claimNumber)', 'en' => 'One claim (claimNumber)'],
                    'role' => ['de' => 'Fact (sensibel)', 'en' => 'Fact (sensitive)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'claim_transaction',
                    'label' => ['de' => 'Claim Transaction', 'en' => 'Claim transaction'],
                    'description' => [
                        'de' => 'Claim Financial Transaction — Reserve/Payment; Incurred-Grundlage.',
                        'en' => 'Claim financial transaction — reserve/payment; incurred basis.',
                    ],
                    'grain' => ['de' => 'Eine Claim Transaction (claimTransactionId)', 'en' => 'One claim transaction (claimTransactionId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'producer',
                    'label' => ['de' => 'Producer / Agency', 'en' => 'Producer / agency'],
                    'description' => [
                        'de' => 'Vermittler/Agentur — Distribution-Dimension.',
                        'en' => 'Broker/agency — distribution dimension.',
                    ],
                    'grain' => ['de' => 'Ein Producer (producerCode)', 'en' => 'One producer (producerCode)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'billing_invoice',
                    'label' => ['de' => 'Billing Invoice', 'en' => 'Billing invoice'],
                    'description' => [
                        'de' => 'BillingCenter-Invoice — Fälligkeit und Zahlungsstatus.',
                        'en' => 'BillingCenter invoice — due date and payment status.',
                    ],
                    'grain' => ['de' => 'Ein Invoice (invoiceId)', 'en' => 'One invoice (invoiceId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'policy_transaction',
                    'label' => ['de' => 'Policy Transaction', 'en' => 'Policy transaction'],
                    'description' => [
                        'de' => 'Endorsement/Renewal/Cancellation — Policy-Änderungs-Fact.',
                        'en' => 'Endorsement/renewal/cancellation — policy change fact.',
                    ],
                    'grain' => ['de' => 'Eine Policy Transaction (policyTransactionId)', 'en' => 'One policy transaction (policyTransactionId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
            ],
            'fields' => [
                ['entity' => 'Account', 'name' => 'accountNumber', 'role' => 'key', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'insuredName', 'role' => 'pii', 'why' => ['de' => 'Versicherter Name / PII', 'en' => 'Insured name / PII']],
                ['entity' => 'Account', 'name' => 'address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Account', 'name' => 'dateOfBirth', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'Account', 'name' => 'accountStatus', 'role' => 'dimension', 'why' => ['de' => 'active / closed', 'en' => 'active / closed']],
                ['entity' => 'Policy', 'name' => 'policyNumber', 'role' => 'key', 'why' => ['de' => 'Policy-Join', 'en' => 'Policy join']],
                ['entity' => 'Policy', 'name' => 'accountNumber', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'Policy', 'name' => 'lineOfBusiness', 'role' => 'dimension', 'why' => ['de' => 'LOB-Dimension', 'en' => 'LOB dimension']],
                ['entity' => 'Policy', 'name' => 'effectiveDate', 'role' => 'measure', 'why' => ['de' => 'Policy-Start', 'en' => 'Policy start']],
                ['entity' => 'Policy', 'name' => 'expirationDate', 'role' => 'measure', 'why' => ['de' => 'Policy-Ende', 'en' => 'Policy end']],
                ['entity' => 'Policy', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'in_force / expired / cancelled', 'en' => 'in_force / expired / cancelled']],
                ['entity' => 'Policy', 'name' => 'producerCode', 'role' => 'dimension', 'why' => ['de' => 'Producer-Join', 'en' => 'Producer join']],
                ['entity' => 'Policy', 'name' => 'writtenPremium', 'role' => 'measure', 'why' => ['de' => 'Gebuchte Prämie', 'en' => 'Written premium']],
                ['entity' => 'PolicyPeriod', 'name' => 'periodId', 'role' => 'key', 'why' => ['de' => 'Period-Join', 'en' => 'Period join']],
                ['entity' => 'PolicyPeriod', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'PolicyPeriod', 'name' => 'termNumber', 'role' => 'dimension', 'why' => ['de' => 'Term-Nummer', 'en' => 'Term number']],
                ['entity' => 'PolicyPeriod', 'name' => 'periodStart', 'role' => 'measure', 'why' => ['de' => 'Perioden-Start', 'en' => 'Period start']],
                ['entity' => 'PolicyPeriod', 'name' => 'periodEnd', 'role' => 'measure', 'why' => ['de' => 'Perioden-Ende', 'en' => 'Period end']],
                ['entity' => 'Coverage', 'name' => 'coverageId', 'role' => 'key', 'why' => ['de' => 'Coverage-Join', 'en' => 'Coverage join']],
                ['entity' => 'Coverage', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'Coverage', 'name' => 'coverageType', 'role' => 'dimension', 'why' => ['de' => 'Coverage-Typ', 'en' => 'Coverage type']],
                ['entity' => 'Coverage', 'name' => 'limitAmount', 'role' => 'measure', 'why' => ['de' => 'Deckungssumme', 'en' => 'Coverage limit']],
                ['entity' => 'Coverage', 'name' => 'deductibleAmount', 'role' => 'measure', 'why' => ['de' => 'Selbstbehalt', 'en' => 'Deductible']],
                ['entity' => 'Claim', 'name' => 'claimNumber', 'role' => 'key', 'why' => ['de' => 'Claim-Join', 'en' => 'Claim join']],
                ['entity' => 'Claim', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'Claim', 'name' => 'lossDate', 'role' => 'measure', 'why' => ['de' => 'Schadendatum', 'en' => 'Loss date']],
                ['entity' => 'Claim', 'name' => 'reportedDate', 'role' => 'measure', 'why' => ['de' => 'Meldedatum', 'en' => 'Reported date']],
                ['entity' => 'Claim', 'name' => 'claimStatus', 'role' => 'dimension', 'why' => ['de' => 'open / closed / reopened', 'en' => 'open / closed / reopened']],
                ['entity' => 'Claim', 'name' => 'lineOfBusiness', 'role' => 'dimension', 'why' => ['de' => 'LOB-Dimension', 'en' => 'LOB dimension']],
                ['entity' => 'Claim', 'name' => 'claimantName', 'role' => 'pii', 'why' => ['de' => 'Anspruchsteller-Name / PII', 'en' => 'Claimant name / PII']],
                ['entity' => 'ClaimTransaction', 'name' => 'claimTransactionId', 'role' => 'key', 'why' => ['de' => 'Transaction-Join', 'en' => 'Transaction join']],
                ['entity' => 'ClaimTransaction', 'name' => 'claimNumber', 'role' => 'dimension', 'why' => ['de' => 'Claim-Rückjoin', 'en' => 'Claim back-join']],
                ['entity' => 'ClaimTransaction', 'name' => 'transactionType', 'role' => 'dimension', 'why' => ['de' => 'reserve / payment / recovery', 'en' => 'reserve / payment / recovery']],
                ['entity' => 'ClaimTransaction', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Transaktionsbetrag', 'en' => 'Transaction amount']],
                ['entity' => 'ClaimTransaction', 'name' => 'transactionDate', 'role' => 'measure', 'why' => ['de' => 'Buchungsdatum', 'en' => 'Transaction date']],
                ['entity' => 'Producer', 'name' => 'producerCode', 'role' => 'key', 'why' => ['de' => 'Producer-Join', 'en' => 'Producer join']],
                ['entity' => 'Producer', 'name' => 'producerName', 'role' => 'dimension', 'why' => ['de' => 'Vermittler-Label', 'en' => 'Producer label']],
                ['entity' => 'Producer', 'name' => 'agencyId', 'role' => 'dimension', 'why' => ['de' => 'Agentur-Rollup', 'en' => 'Agency rollup']],
                ['entity' => 'BillingInvoice', 'name' => 'invoiceId', 'role' => 'key', 'why' => ['de' => 'Invoice-Join', 'en' => 'Invoice join']],
                ['entity' => 'BillingInvoice', 'name' => 'accountNumber', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'BillingInvoice', 'name' => 'dueDate', 'role' => 'measure', 'why' => ['de' => 'Fälligkeitsdatum', 'en' => 'Due date']],
                ['entity' => 'BillingInvoice', 'name' => 'amountDue', 'role' => 'measure', 'why' => ['de' => 'Fälliger Betrag', 'en' => 'Amount due']],
                ['entity' => 'BillingInvoice', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'paid / overdue / pending', 'en' => 'paid / overdue / pending']],
                ['entity' => 'PolicyTransaction', 'name' => 'policyTransactionId', 'role' => 'key', 'why' => ['de' => 'Transaction-Join', 'en' => 'Transaction join']],
                ['entity' => 'PolicyTransaction', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'PolicyTransaction', 'name' => 'transactionType', 'role' => 'dimension', 'why' => ['de' => 'endorsement / renewal / cancellation', 'en' => 'endorsement / renewal / cancellation']],
                ['entity' => 'PolicyTransaction', 'name' => 'transactionDate', 'role' => 'measure', 'why' => ['de' => 'Änderungsdatum', 'en' => 'Transaction date']],
            ],
            'skipTables' => [
                [
                    'name' => 'Claim adjuster notes / activity narrative text',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Adjuster-Notizen enthalten oft medizinische/rechtliche Details — hochsensibel.',
                        'en' => 'Adjuster notes often contain medical/legal detail — highly sensitive.',
                    ],
                ],
                [
                    'name' => 'Medical bills / injury documentation attachments',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Besondere Kategorie Gesundheitsdaten — nie ins Analytics-Warehouse.',
                        'en' => 'Special-category health data — never into the analytics warehouse.',
                    ],
                ],
                [
                    'name' => 'Policy document / binder PDF text',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Unstrukturiert und groß — strukturierte Policy-Felder reichen.',
                        'en' => 'Unstructured and large — structured policy fields suffice.',
                    ],
                ],
                [
                    'name' => 'SIU (fraud investigation) case notes',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Legal-Hold-sensibel — strikt getrennt von Analytics-Marts.',
                        'en' => 'Legal-hold sensitive — kept strictly separate from analytics marts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Claim adjuster / activity narrative text', 'reason' => ['de' => 'Medical/Legal-Detail — hochsensibel', 'en' => 'Medical/legal detail — highly sensitive']],
                ['name' => 'Medical bills / injury documentation', 'reason' => ['de' => 'Besondere Kategorie Gesundheitsdaten', 'en' => 'Special-category health data']],
                ['name' => 'Policy document / binder PDF text', 'reason' => ['de' => 'Unstrukturiert, groß', 'en' => 'Unstructured, large']],
                ['name' => 'SIU fraud investigation notes', 'reason' => ['de' => 'Legal-Hold — nicht in Analytics', 'en' => 'Legal hold — not in analytics']],
            ],
            'dimensions' => [
                [
                    'id' => 'line_of_business',
                    'label' => ['de' => 'Line of Business', 'en' => 'Line of business'],
                    'grain' => ['de' => 'policy.lineOfBusiness / claim.lineOfBusiness', 'en' => 'policy.lineOfBusiness / claim.lineOfBusiness'],
                    'notes' => [
                        'de' => 'Auto/Property/Casualty konsistent zwischen Policy und Claim halten.',
                        'en' => 'Keep auto/property/casualty consistent between policy and claim.',
                    ],
                ],
                [
                    'id' => 'jurisdiction',
                    'label' => ['de' => 'Jurisdiction', 'en' => 'Jurisdiction'],
                    'grain' => ['de' => 'account.address (state/country, aggregiert)', 'en' => 'account.address (state/country, aggregated)'],
                    'notes' => [
                        'de' => 'Nur State/Country-Ebene aggregieren — keine Straßenadresse in Marts.',
                        'en' => 'Aggregate to state/country level only — no street address in marts.',
                    ],
                ],
                [
                    'id' => 'producer',
                    'label' => ['de' => 'Producer / Agency', 'en' => 'Producer / agency'],
                    'grain' => ['de' => 'policy.producerCode / producer.agencyId', 'en' => 'policy.producerCode / producer.agencyId'],
                    'notes' => [
                        'de' => 'Distribution-Channel-Analysen je Agentur.',
                        'en' => 'Distribution channel analysis per agency.',
                    ],
                ],
                [
                    'id' => 'claim_status',
                    'label' => ['de' => 'Claim Status', 'en' => 'Claim status'],
                    'grain' => ['de' => 'claim.claimStatus', 'en' => 'claim.claimStatus'],
                    'notes' => [
                        'de' => 'Open/Closed/Reopened nicht mit Policy-Status verwechseln.',
                        'en' => 'Do not confuse open/closed/reopened with policy status.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Account',
                    'fields' => ['insuredName', 'address', 'dateOfBirth'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — RAW einschränken, Curated nur accountNumber.',
                        'en' => 'Direct identifiers — restrict RAW, curated accountNumber only.',
                    ],
                ],
                [
                    'entity' => 'Claim',
                    'fields' => ['claimantName'],
                    'treatment' => [
                        'de' => 'Anspruchsteller-Name — direkter Identifikator, oft mit Schadendetails verknüpft.',
                        'en' => 'Claimant name — direct identifier, often linked to loss detail.',
                    ],
                ],
                [
                    'entity' => 'Claim',
                    'fields' => ['lossDate', 'claimStatus'],
                    'treatment' => [
                        'de' => 'In Kombination mit Claimant-Identität potenziell sensibel (Verletzung/Krankheit) — Aggregate statt Row-Level bevorzugen.',
                        'en' => 'In combination with claimant identity potentially sensitive (injury/illness) — prefer aggregates over row-level.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'accountNumber, policyNumber, claimNumber, PublicID.',
                        'en' => 'accountNumber, policyNumber, claimNumber, PublicID.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Account, Policy, Claim + BillingCenter/ClaimCenter-Kopien — keine Narrative/Dokumente.',
                        'en' => 'Account, policy, claim + BillingCenter/ClaimCenter copies — no narratives/documents.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'policies-in-force',
                    'example' => true,
                    'label' => ['de' => 'Policies In Force', 'en' => 'Policies in force'],
                    'question' => [
                        'de' => 'Wie viele Policies sind zum Stichtag in Force?',
                        'en' => 'How many policies are in force as of the reporting date?',
                    ],
                    'formula' => "COUNT(*) FROM policy WHERE status = 'in_force' AND :as_of BETWEEN effectiveDate AND expirationDate",
                    'grain' => ['de' => 'In-Force Policy', 'en' => 'In-force policy'],
                    'dimensions' => ['line_of_business', 'jurisdiction'],
                    'fieldsUsed' => ['Policy.status', 'Policy.effectiveDate', 'Policy.expirationDate', 'Policy.lineOfBusiness'],
                    'sourceHints' => [
                        'de' => 'Cancelled-mid-term Policies aus dem Snapshot ausschließen.',
                        'en' => 'Exclude cancelled-mid-term policies from the snapshot.',
                    ],
                    'adapt' => [
                        'de' => 'Neubusiness vs. Renewal getrennt zählen.',
                        'en' => 'Count new business vs renewal separately.',
                    ],
                ],
                [
                    'id' => 'written-premium',
                    'example' => true,
                    'label' => ['de' => 'Written Premium', 'en' => 'Written premium'],
                    'question' => [
                        'de' => 'Wie viel Prämie wurde in der Periode gebucht?',
                        'en' => 'How much premium was written in the period?',
                    ],
                    'formula' => 'SUM(writtenPremium) FROM policy WHERE effectiveDate IN period',
                    'grain' => ['de' => 'Policy', 'en' => 'Policy'],
                    'dimensions' => ['line_of_business', 'producer'],
                    'fieldsUsed' => ['Policy.writtenPremium', 'Policy.effectiveDate', 'Policy.lineOfBusiness', 'Policy.producerCode'],
                    'sourceHints' => [
                        'de' => 'Endorsement-Prämienänderungen über PolicyTransaction mit einbeziehen.',
                        'en' => 'Include endorsement premium changes via PolicyTransaction.',
                    ],
                    'adapt' => [
                        'de' => 'Gross vs. Net-of-Commission Premium klären.',
                        'en' => 'Clarify gross vs net-of-commission premium.',
                    ],
                ],
                [
                    'id' => 'claims-incurred',
                    'example' => false,
                    'label' => ['de' => 'Claims Incurred', 'en' => 'Claims incurred'],
                    'question' => [
                        'de' => 'Wie hoch ist der Incurred-Betrag (Reserve + Payment) in der Periode?',
                        'en' => 'What is the incurred amount (reserve + payment) in the period?',
                    ],
                    'formula' => "SUM(amount) FROM claim_transaction WHERE transactionType IN ('reserve', 'payment') AND transactionDate IN period",
                    'grain' => ['de' => 'Claim Transaction', 'en' => 'Claim transaction'],
                    'dimensions' => ['line_of_business', 'claim_status'],
                    'fieldsUsed' => ['ClaimTransaction.amount', 'ClaimTransaction.transactionType', 'ClaimTransaction.transactionDate', 'Claim.lineOfBusiness'],
                    'sourceHints' => [
                        'de' => 'Recovery/Salvage separat als negative Komponente behandeln.',
                        'en' => 'Treat recovery/salvage separately as a negative component.',
                    ],
                    'adapt' => [
                        'de' => 'Reserve-Entwicklung (IBNR) als eigene Kennzahl.',
                        'en' => 'Reserve development (IBNR) as its own metric.',
                    ],
                ],
                [
                    'id' => 'loss-ratio',
                    'example' => false,
                    'label' => ['de' => 'Loss Ratio', 'en' => 'Loss ratio'],
                    'question' => [
                        'de' => 'Wie hoch ist das Verhältnis Incurred Losses zu Earned Premium?',
                        'en' => 'What is the ratio of incurred losses to earned premium?',
                    ],
                    'formula' => 'SUM(claims_incurred) / SUM(writtenPremium)',
                    'grain' => ['de' => 'Line of Business / Periode', 'en' => 'Line of business / period'],
                    'dimensions' => ['line_of_business'],
                    'fieldsUsed' => ['ClaimTransaction.amount', 'Policy.writtenPremium', 'Claim.lineOfBusiness', 'Policy.lineOfBusiness'],
                    'sourceHints' => [
                        'de' => 'Earned vs. Written Premium für exakte Loss Ratio unterscheiden.',
                        'en' => 'Distinguish earned vs written premium for an exact loss ratio.',
                    ],
                    'adapt' => [
                        'de' => 'Combined Ratio (inkl. Expenses) als erweiterte Variante.',
                        'en' => 'Combined ratio (incl. expenses) as an extended variant.',
                    ],
                ],
                [
                    'id' => 'claims-closed-count',
                    'example' => false,
                    'label' => ['de' => 'Claims Closed Count', 'en' => 'Claims closed count'],
                    'question' => [
                        'de' => 'Wie viele Claims wurden in der Periode geschlossen?',
                        'en' => 'How many claims were closed in the period?',
                    ],
                    'formula' => "COUNT(*) FROM claim WHERE claimStatus = 'closed' AND reportedDate IS NOT NULL",
                    'grain' => ['de' => 'Closed Claim', 'en' => 'Closed claim'],
                    'dimensions' => ['line_of_business', 'jurisdiction'],
                    'fieldsUsed' => ['Claim.claimStatus', 'Claim.lineOfBusiness', 'Claim.reportedDate'],
                    'sourceHints' => [
                        'de' => 'Close-Date separat tracken statt reportedDate zu missbrauchen.',
                        'en' => 'Track a dedicated close date instead of misusing reportedDate.',
                    ],
                    'adapt' => [
                        'de' => 'Reopened Claims aus dem Closed-Zähler ausschließen.',
                        'en' => 'Exclude reopened claims from the closed count.',
                    ],
                ],
            ],
            'tools' => $marketsInsuranceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],

        [
            'id' => 'duck-creek',
            'domain' => 'insurance',
            'order' => 280,
            'label' => ['de' => 'Duck Creek', 'en' => 'Duck Creek'],
            'shortPurpose' => [
                'de' => 'Insurance Platforms: Policy Transaction/Claim — aggregierte Premium-/Loss-Ratio-Measures; keine Adjuster-/Underwriting-Docs.',
                'en' => 'Insurance platforms: policy transaction/claim — aggregated premium/loss-ratio measures; no adjuster/underwriting docs.',
            ],
            'entities' => [
                [
                    'id' => 'account',
                    'label' => ['de' => 'Account', 'en' => 'Account'],
                    'description' => [
                        'de' => 'Insured/Account (Duck Creek Policy) — direkte PII.',
                        'en' => 'Insured/account (Duck Creek Policy) — direct PII.',
                    ],
                    'grain' => ['de' => 'Ein Account (accountNumber)', 'en' => 'One account (accountNumber)'],
                    'role' => ['de' => 'Dimension (PII)', 'en' => 'Dimension (PII)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'policy_transaction',
                    'label' => ['de' => 'Policy Transaction', 'en' => 'Policy transaction'],
                    'description' => [
                        'de' => 'Duck Creek Policy Transaction — new business/renewal/endorsement; Fact-Anker.',
                        'en' => 'Duck Creek Policy transaction — new business/renewal/endorsement; fact anchor.',
                    ],
                    'grain' => ['de' => 'Eine Policy Transaction (policyTransactionId)', 'en' => 'One policy transaction (policyTransactionId)'],
                    'role' => ['de' => 'Fact-Anker', 'en' => 'Fact anchor'],
                    'load' => 'required',
                ],
                [
                    'id' => 'coverage',
                    'label' => ['de' => 'Coverage', 'en' => 'Coverage'],
                    'description' => [
                        'de' => 'Coverage / Rating-Line — Limit je Policy Transaction.',
                        'en' => 'Coverage / rating line — limit per policy transaction.',
                    ],
                    'grain' => ['de' => 'Eine Coverage (coverageId)', 'en' => 'One coverage (coverageId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'rating_detail',
                    'label' => ['de' => 'Rating Detail', 'en' => 'Rating detail'],
                    'description' => [
                        'de' => 'Rating-Engine-Output — Rate-Faktor und berechnete Prämie.',
                        'en' => 'Rating engine output — rate factor and calculated premium.',
                    ],
                    'grain' => ['de' => 'Ein Rating Detail (ratingDetailId)', 'en' => 'One rating detail (ratingDetailId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'claim',
                    'label' => ['de' => 'Claim', 'en' => 'Claim'],
                    'description' => [
                        'de' => 'Duck Creek Claims — Loss/Reported-Datum, Status; Adjuster-Notizen nicht laden.',
                        'en' => 'Duck Creek Claims — loss/reported date, status; adjuster notes not loaded.',
                    ],
                    'grain' => ['de' => 'Ein Claim (claimNumber)', 'en' => 'One claim (claimNumber)'],
                    'role' => ['de' => 'Fact (sensibel)', 'en' => 'Fact (sensitive)'],
                    'load' => 'required',
                ],
                [
                    'id' => 'claim_activity',
                    'label' => ['de' => 'Claim Activity', 'en' => 'Claim activity'],
                    'description' => [
                        'de' => 'Reserve-/Payment-Transaction — Incurred-Grundlage.',
                        'en' => 'Reserve/payment transaction — incurred basis.',
                    ],
                    'grain' => ['de' => 'Eine Claim Activity (claimActivityId)', 'en' => 'One claim activity (claimActivityId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'required',
                ],
                [
                    'id' => 'producer',
                    'label' => ['de' => 'Producer / Agency', 'en' => 'Producer / agency'],
                    'description' => [
                        'de' => 'Vermittler/Agentur — Distribution-Dimension.',
                        'en' => 'Broker/agency — distribution dimension.',
                    ],
                    'grain' => ['de' => 'Ein Producer (producerCode)', 'en' => 'One producer (producerCode)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
                [
                    'id' => 'billing_transaction',
                    'label' => ['de' => 'Billing Transaction', 'en' => 'Billing transaction'],
                    'description' => [
                        'de' => 'Duck Creek Billing — Invoice-/Zahlungs-Fact.',
                        'en' => 'Duck Creek Billing — invoice/payment fact.',
                    ],
                    'grain' => ['de' => 'Eine Billing Transaction (billingTransactionId)', 'en' => 'One billing transaction (billingTransactionId)'],
                    'role' => ['de' => 'Fact', 'en' => 'Fact'],
                    'load' => 'optional',
                ],
                [
                    'id' => 'product',
                    'label' => ['de' => 'Product / Program', 'en' => 'Product / program'],
                    'description' => [
                        'de' => 'Product/Program-Definition — Rating- und Underwriting-Regelset-Dimension.',
                        'en' => 'Product/program definition — rating and underwriting ruleset dimension.',
                    ],
                    'grain' => ['de' => 'Ein Product (productId)', 'en' => 'One product (productId)'],
                    'role' => ['de' => 'Dimension', 'en' => 'Dimension'],
                    'load' => 'required',
                ],
            ],
            'fields' => [
                ['entity' => 'Account', 'name' => 'accountNumber', 'role' => 'key', 'why' => ['de' => 'Account-Join', 'en' => 'Account join']],
                ['entity' => 'Account', 'name' => 'insuredName', 'role' => 'pii', 'why' => ['de' => 'Versicherter Name / PII', 'en' => 'Insured name / PII']],
                ['entity' => 'Account', 'name' => 'address', 'role' => 'pii', 'why' => ['de' => 'Adresse / PII', 'en' => 'Address / PII']],
                ['entity' => 'Account', 'name' => 'dateOfBirth', 'role' => 'pii', 'why' => ['de' => 'Geburtsdatum / PII', 'en' => 'Date of birth / PII']],
                ['entity' => 'PolicyTransaction', 'name' => 'policyTransactionId', 'role' => 'key', 'why' => ['de' => 'Transaction-Join', 'en' => 'Transaction join']],
                ['entity' => 'PolicyTransaction', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Dimension', 'en' => 'Policy dimension']],
                ['entity' => 'PolicyTransaction', 'name' => 'transactionType', 'role' => 'dimension', 'why' => ['de' => 'new_business / renewal / endorsement', 'en' => 'new_business / renewal / endorsement']],
                ['entity' => 'PolicyTransaction', 'name' => 'effectiveDate', 'role' => 'measure', 'why' => ['de' => 'Wirksamkeitsdatum', 'en' => 'Effective date']],
                ['entity' => 'PolicyTransaction', 'name' => 'premiumAmount', 'role' => 'measure', 'why' => ['de' => 'Prämienbetrag', 'en' => 'Premium amount']],
                ['entity' => 'PolicyTransaction', 'name' => 'accountNumber', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'PolicyTransaction', 'name' => 'productId', 'role' => 'dimension', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'PolicyTransaction', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'active / cancelled / expired', 'en' => 'active / cancelled / expired']],
                ['entity' => 'Coverage', 'name' => 'coverageId', 'role' => 'key', 'why' => ['de' => 'Coverage-Join', 'en' => 'Coverage join']],
                ['entity' => 'Coverage', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'Coverage', 'name' => 'coverageCode', 'role' => 'dimension', 'why' => ['de' => 'Coverage-Code', 'en' => 'Coverage code']],
                ['entity' => 'Coverage', 'name' => 'limitAmount', 'role' => 'measure', 'why' => ['de' => 'Deckungssumme', 'en' => 'Coverage limit']],
                ['entity' => 'RatingDetail', 'name' => 'ratingDetailId', 'role' => 'key', 'why' => ['de' => 'Rating-Join', 'en' => 'Rating join']],
                ['entity' => 'RatingDetail', 'name' => 'policyTransactionId', 'role' => 'dimension', 'why' => ['de' => 'Transaction-Rückjoin', 'en' => 'Transaction back-join']],
                ['entity' => 'RatingDetail', 'name' => 'rateFactor', 'role' => 'measure', 'why' => ['de' => 'Rate-Faktor', 'en' => 'Rate factor']],
                ['entity' => 'RatingDetail', 'name' => 'calculatedPremium', 'role' => 'measure', 'why' => ['de' => 'Berechnete Prämie', 'en' => 'Calculated premium']],
                ['entity' => 'Claim', 'name' => 'claimNumber', 'role' => 'key', 'why' => ['de' => 'Claim-Join', 'en' => 'Claim join']],
                ['entity' => 'Claim', 'name' => 'policyNumber', 'role' => 'dimension', 'why' => ['de' => 'Policy-Rückjoin', 'en' => 'Policy back-join']],
                ['entity' => 'Claim', 'name' => 'lossDate', 'role' => 'measure', 'why' => ['de' => 'Schadendatum', 'en' => 'Loss date']],
                ['entity' => 'Claim', 'name' => 'reportedDate', 'role' => 'measure', 'why' => ['de' => 'Meldedatum', 'en' => 'Reported date']],
                ['entity' => 'Claim', 'name' => 'claimStatus', 'role' => 'dimension', 'why' => ['de' => 'open / closed / reopened', 'en' => 'open / closed / reopened']],
                ['entity' => 'Claim', 'name' => 'claimantName', 'role' => 'pii', 'why' => ['de' => 'Anspruchsteller-Name / PII', 'en' => 'Claimant name / PII']],
                ['entity' => 'Claim', 'name' => 'lineOfBusiness', 'role' => 'dimension', 'why' => ['de' => 'LOB-Dimension', 'en' => 'LOB dimension']],
                ['entity' => 'ClaimActivity', 'name' => 'claimActivityId', 'role' => 'key', 'why' => ['de' => 'Activity-Join', 'en' => 'Activity join']],
                ['entity' => 'ClaimActivity', 'name' => 'claimNumber', 'role' => 'dimension', 'why' => ['de' => 'Claim-Rückjoin', 'en' => 'Claim back-join']],
                ['entity' => 'ClaimActivity', 'name' => 'activityType', 'role' => 'dimension', 'why' => ['de' => 'reserve / payment / recovery', 'en' => 'reserve / payment / recovery']],
                ['entity' => 'ClaimActivity', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Aktivitätsbetrag', 'en' => 'Activity amount']],
                ['entity' => 'ClaimActivity', 'name' => 'activityDate', 'role' => 'measure', 'why' => ['de' => 'Buchungsdatum', 'en' => 'Activity date']],
                ['entity' => 'Producer', 'name' => 'producerCode', 'role' => 'key', 'why' => ['de' => 'Producer-Join', 'en' => 'Producer join']],
                ['entity' => 'Producer', 'name' => 'producerName', 'role' => 'dimension', 'why' => ['de' => 'Vermittler-Label', 'en' => 'Producer label']],
                ['entity' => 'Producer', 'name' => 'agencyId', 'role' => 'dimension', 'why' => ['de' => 'Agentur-Rollup', 'en' => 'Agency rollup']],
                ['entity' => 'BillingTransaction', 'name' => 'billingTransactionId', 'role' => 'key', 'why' => ['de' => 'Billing-Join', 'en' => 'Billing join']],
                ['entity' => 'BillingTransaction', 'name' => 'accountNumber', 'role' => 'dimension', 'why' => ['de' => 'Account-Rückjoin', 'en' => 'Account back-join']],
                ['entity' => 'BillingTransaction', 'name' => 'amount', 'role' => 'measure', 'why' => ['de' => 'Buchungsbetrag', 'en' => 'Billing amount']],
                ['entity' => 'BillingTransaction', 'name' => 'dueDate', 'role' => 'measure', 'why' => ['de' => 'Fälligkeitsdatum', 'en' => 'Due date']],
                ['entity' => 'BillingTransaction', 'name' => 'status', 'role' => 'dimension', 'why' => ['de' => 'paid / overdue / pending', 'en' => 'paid / overdue / pending']],
                ['entity' => 'Product', 'name' => 'productId', 'role' => 'key', 'why' => ['de' => 'Product-Join', 'en' => 'Product join']],
                ['entity' => 'Product', 'name' => 'productName', 'role' => 'dimension', 'why' => ['de' => 'Produkt-Label', 'en' => 'Product label']],
                ['entity' => 'Product', 'name' => 'programCode', 'role' => 'dimension', 'why' => ['de' => 'Program-Rollup', 'en' => 'Program rollup']],
            ],
            'skipTables' => [
                [
                    'name' => 'Claim adjuster notes / correspondence content',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Adjuster-Notizen und Korrespondenz — oft medizinische/rechtliche Details.',
                        'en' => 'Adjuster notes and correspondence — often medical/legal detail.',
                    ],
                ],
                [
                    'name' => 'Underwriting document attachments (applications, inspections)',
                    'category' => 'pii',
                    'reason' => [
                        'de' => 'Enthalten oft Identitäts- und Objektdetails — hochsensibel.',
                        'en' => 'Often contain identity and property detail — highly sensitive.',
                    ],
                ],
                [
                    'name' => 'Policy print / document template content (bound PDFs)',
                    'category' => 'system',
                    'reason' => [
                        'de' => 'Unstrukturiert und groß — strukturierte Policy-Felder reichen.',
                        'en' => 'Unstructured and large — structured policy fields suffice.',
                    ],
                ],
                [
                    'name' => 'Legal / SIU investigation notes',
                    'category' => 'security',
                    'reason' => [
                        'de' => 'Legal-Hold-sensibel — strikt getrennt von Analytics-Marts.',
                        'en' => 'Legal-hold sensitive — kept strictly separate from analytics marts.',
                    ],
                ],
            ],
            'skip' => [
                ['name' => 'Claim adjuster notes / correspondence', 'reason' => ['de' => 'Medical/Legal-Detail — hochsensibel', 'en' => 'Medical/legal detail — highly sensitive']],
                ['name' => 'Underwriting document attachments', 'reason' => ['de' => 'Identitäts-/Objektdetails', 'en' => 'Identity/property detail']],
                ['name' => 'Policy print / document templates (bound PDFs)', 'reason' => ['de' => 'Unstrukturiert, groß', 'en' => 'Unstructured, large']],
                ['name' => 'Legal / SIU investigation notes', 'reason' => ['de' => 'Legal Hold — nicht in Analytics', 'en' => 'Legal hold — not in analytics']],
            ],
            'dimensions' => [
                [
                    'id' => 'line_of_business',
                    'label' => ['de' => 'Line of Business', 'en' => 'Line of business'],
                    'grain' => ['de' => 'claim.lineOfBusiness / product.programCode', 'en' => 'claim.lineOfBusiness / product.programCode'],
                    'notes' => [
                        'de' => 'Program-Code als LOB-Proxy nutzen, wenn kein separates LOB-Feld existiert.',
                        'en' => 'Use program code as an LOB proxy when no separate LOB field exists.',
                    ],
                ],
                [
                    'id' => 'program',
                    'label' => ['de' => 'Program', 'en' => 'Program'],
                    'grain' => ['de' => 'product.programCode', 'en' => 'product.programCode'],
                    'notes' => [
                        'de' => 'Program-Ebene für Rating-/Underwriting-Regelset-Vergleiche.',
                        'en' => 'Program level for rating/underwriting ruleset comparisons.',
                    ],
                ],
                [
                    'id' => 'producer',
                    'label' => ['de' => 'Producer / Agency', 'en' => 'Producer / agency'],
                    'grain' => ['de' => 'producer.agencyId', 'en' => 'producer.agencyId'],
                    'notes' => [
                        'de' => 'Distribution-Channel-Analysen je Agentur.',
                        'en' => 'Distribution channel analysis per agency.',
                    ],
                ],
                [
                    'id' => 'transaction_type',
                    'label' => ['de' => 'Transaction Type', 'en' => 'Transaction type'],
                    'grain' => ['de' => 'policy_transaction.transactionType', 'en' => 'policy_transaction.transactionType'],
                    'notes' => [
                        'de' => 'New Business vs. Renewal vs. Endorsement nicht vermischen.',
                        'en' => 'Do not mix new business vs renewal vs endorsement.',
                    ],
                ],
            ],
            'pii' => [
                [
                    'entity' => 'Account',
                    'fields' => ['insuredName', 'address', 'dateOfBirth'],
                    'treatment' => [
                        'de' => 'Direkte Identifikatoren — RAW einschränken, Curated nur accountNumber.',
                        'en' => 'Direct identifiers — restrict RAW, curated accountNumber only.',
                    ],
                ],
                [
                    'entity' => 'Claim',
                    'fields' => ['claimantName'],
                    'treatment' => [
                        'de' => 'Anspruchsteller-Identität — potenziell mit Schaden-/Verletzungsdetails verknüpft.',
                        'en' => 'Claimant identity — potentially linked to loss/injury detail.',
                    ],
                ],
                [
                    'entity' => 'Claim',
                    'fields' => ['lossDate', 'claimStatus'],
                    'treatment' => [
                        'de' => 'Als sensibel behandeln — aggregierte Loss-Ratio-Reports statt Row-Level-Exposure bevorzugen.',
                        'en' => 'Treat as sensitive — prefer aggregated loss-ratio reporting over row-level exposure.',
                    ],
                ],
            ],
            'dsdr' => [
                [
                    'focus' => ['de' => 'Match-Keys', 'en' => 'Match keys'],
                    'notes' => [
                        'de' => 'accountNumber, policyNumber, claimNumber.',
                        'en' => 'accountNumber, policyNumber, claimNumber.',
                    ],
                ],
                [
                    'focus' => ['de' => 'Primärobjekte', 'en' => 'Primary objects'],
                    'notes' => [
                        'de' => 'Account, Policy Transaction, Claim + Insights-/Billing-Kopien.',
                        'en' => 'Account, policy transaction, claim + Insights/Billing copies.',
                    ],
                ],
            ],
            'measures' => [
                [
                    'id' => 'premium-written',
                    'example' => true,
                    'label' => ['de' => 'Premium Written', 'en' => 'Premium written'],
                    'question' => [
                        'de' => 'Wie viel Prämie wurde in der Periode gebucht (New Business + Renewal)?',
                        'en' => 'How much premium was written in the period (new business + renewal)?',
                    ],
                    'formula' => "SUM(premiumAmount) FROM policy_transaction WHERE transactionType IN ('new_business', 'renewal') AND effectiveDate IN period",
                    'grain' => ['de' => 'Policy Transaction', 'en' => 'Policy transaction'],
                    'dimensions' => ['line_of_business', 'program'],
                    'fieldsUsed' => ['PolicyTransaction.premiumAmount', 'PolicyTransaction.transactionType', 'PolicyTransaction.effectiveDate', 'PolicyTransaction.productId'],
                    'sourceHints' => [
                        'de' => 'Endorsement-Prämienänderungen separat oder additiv einbeziehen — Definition locken.',
                        'en' => 'Include endorsement premium changes separately or additively — lock the definition.',
                    ],
                    'adapt' => [
                        'de' => 'Gross vs. Net-of-Commission Premium klären.',
                        'en' => 'Clarify gross vs net-of-commission premium.',
                    ],
                ],
                [
                    'id' => 'policies-in-force',
                    'example' => true,
                    'label' => ['de' => 'Policies In Force', 'en' => 'Policies in force'],
                    'question' => [
                        'de' => 'Wie viele Policies sind aktuell aktiv?',
                        'en' => 'How many policies are currently active?',
                    ],
                    'formula' => "COUNT(DISTINCT policyNumber) FROM policy_transaction WHERE status = 'active'",
                    'grain' => ['de' => 'Active Policy', 'en' => 'Active policy'],
                    'dimensions' => ['line_of_business', 'producer'],
                    'fieldsUsed' => ['PolicyTransaction.policyNumber', 'PolicyTransaction.status', 'PolicyTransaction.productId'],
                    'sourceHints' => [
                        'de' => 'Neuste Transaction je policyNumber für den Status nutzen (Window Function).',
                        'en' => 'Use the latest transaction per policyNumber for status (window function).',
                    ],
                    'adapt' => [
                        'de' => 'Neubusiness vs. Renewal getrennt zählen.',
                        'en' => 'Count new business vs renewal separately.',
                    ],
                ],
                [
                    'id' => 'claims-incurred',
                    'example' => false,
                    'label' => ['de' => 'Claims Incurred', 'en' => 'Claims incurred'],
                    'question' => [
                        'de' => 'Wie hoch ist der Incurred-Betrag in der Periode?',
                        'en' => 'What is the incurred amount in the period?',
                    ],
                    'formula' => "SUM(amount) FROM claim_activity WHERE activityType IN ('reserve', 'payment') AND activityDate IN period",
                    'grain' => ['de' => 'Claim Activity', 'en' => 'Claim activity'],
                    'dimensions' => ['line_of_business', 'transaction_type'],
                    'fieldsUsed' => ['ClaimActivity.amount', 'ClaimActivity.activityType', 'ClaimActivity.activityDate', 'Claim.lineOfBusiness'],
                    'sourceHints' => [
                        'de' => 'Recovery/Salvage als negative Komponente behandeln.',
                        'en' => 'Treat recovery/salvage as a negative component.',
                    ],
                    'adapt' => [
                        'de' => 'Reserve-Entwicklung (IBNR) als eigene Kennzahl.',
                        'en' => 'Reserve development (IBNR) as its own metric.',
                    ],
                ],
                [
                    'id' => 'loss-ratio',
                    'example' => false,
                    'label' => ['de' => 'Loss Ratio', 'en' => 'Loss ratio'],
                    'question' => [
                        'de' => 'Wie hoch ist das Verhältnis Incurred Losses zu Written Premium?',
                        'en' => 'What is the ratio of incurred losses to written premium?',
                    ],
                    'formula' => 'SUM(claims_incurred) / SUM(premiumAmount)',
                    'grain' => ['de' => 'Line of Business / Periode', 'en' => 'Line of business / period'],
                    'dimensions' => ['line_of_business'],
                    'fieldsUsed' => ['ClaimActivity.amount', 'PolicyTransaction.premiumAmount', 'Claim.lineOfBusiness'],
                    'sourceHints' => [
                        'de' => 'Earned vs. Written Premium für exakte Loss Ratio unterscheiden.',
                        'en' => 'Distinguish earned vs written premium for an exact loss ratio.',
                    ],
                    'adapt' => [
                        'de' => 'Combined Ratio (inkl. Expenses) als erweiterte Variante.',
                        'en' => 'Combined ratio (incl. expenses) as an extended variant.',
                    ],
                ],
                [
                    'id' => 'claims-open-count',
                    'example' => false,
                    'label' => ['de' => 'Claims Open Count', 'en' => 'Claims open count'],
                    'question' => [
                        'de' => 'Wie viele Claims sind aktuell offen?',
                        'en' => 'How many claims are currently open?',
                    ],
                    'formula' => "COUNT(*) FROM claim WHERE claimStatus = 'open'",
                    'grain' => ['de' => 'Open Claim', 'en' => 'Open claim'],
                    'dimensions' => ['line_of_business', 'program'],
                    'fieldsUsed' => ['Claim.claimStatus', 'Claim.lineOfBusiness', 'Claim.policyNumber'],
                    'sourceHints' => [
                        'de' => 'Reopened Claims als eigenes Flag mitführen.',
                        'en' => 'Carry reopened claims as their own flag.',
                    ],
                    'adapt' => [
                        'de' => 'Aging-Buckets (0-30/31-90/90+) für Backlog-Analyse.',
                        'en' => 'Aging buckets (0-30/31-90/90+) for backlog analysis.',
                    ],
                ],
            ],
            'tools' => $marketsInsuranceTools,
            'relatedPlaybooks' => $relatedPlaybooks,
        ],
    ];
};
