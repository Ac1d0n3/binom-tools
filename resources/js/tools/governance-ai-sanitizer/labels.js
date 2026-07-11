/** @typedef {'de' | 'en'} ToolsLocale */

import { governanceHowtoLabels } from './howto-labels';

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const governanceLabels = {
    de: {
        ...governanceHowtoLabels.de,
        'governance.pageTitle': 'Governance AI Sanitizer',
        'governance.pageLead':
            'Schritt für Schritt: Prompt sanitisieren, Outbound-Nachricht ins LLM kopieren, gemappte PII aus der KI-Antwort wiederherstellen.',
        'outbound.heading': 'Für LLM kopieren',
        'outbound.hint':
            'Nach Sanitize: diesen Block in ChatGPT oder als API-User-Message einfügen. Der App-Rollen-Prompt bleibt separat.',
        'outbound.copy': 'Kopieren',
        'outbound.copied': 'Kopiert!',
        'panels.promptSanitizer.title': 'Prompt-Sanitizer',
        'panels.promptSanitizer.description':
            'Prompt eingeben, sensible Daten sanitisieren und die Outbound-Nachricht an das LLM kopieren.',
        'panels.promptSanitizer.inputPlaceholder': 'Prompt-Text eingeben...',
        'panels.promptSanitizer.sanitizeLabel': 'Sanitisieren',
        'panels.promptSanitizer.findingsLabel': 'Fund(e) erkannt.',
        'panels.aiResponse.title': 'KI-Antwort',
        'panels.aiResponse.description':
            'LLM-Antwort hier einfügen (Platzhalter-Tokens behalten), dann gemappte PII wiederherstellen.',
        'panels.aiResponse.inputPlaceholder': 'KI-Antwort mit Platzhalter-Tokens einfügen...',
        'panels.aiResponse.simulateLabel': 'Demo-Antwort einfügen',
        'panels.aiResponse.restoreLabel': 'Wiederherstellen',
        'panels.aiResponse.restoredHeading': 'Wiederhergestellte Antwort',
        'panels.aiResponse.restoreHint':
            'Wiederherstellen ersetzt Platzhalter-Tokens durch die Originalwerte aus Ihrem Prompt.',
        'panels.findings.title': 'Funde',
        'panels.findings.empty': 'Noch keine Funde.',
        'panels.findings.typeColumn': 'Typ',
        'panels.findings.valueColumn': 'Wert',
        'panels.findings.positionColumn': 'Position',
        'panels.findings.confidenceColumn': 'Konfidenz',
        'detectors.personName': 'Personenname',
        'detectors.email': 'E-Mail',
        'detectors.phone': 'Telefon',
        'detectors.postalAddress': 'Postadresse',
        'detectors.geoCoordinates': 'Geo-Koordinaten',
        'detectors.companyName': 'Firmenname',
        'detectors.url': 'URL',
        'detectors.ipv4': 'IPv4-Adresse',
        'detectors.ipv6': 'IPv6-Adresse',
        'detectors.creditCard': 'Kreditkarte',
        'detectors.iban': 'IBAN',
        'detectors.taxVatId': 'Steuer-/USt-ID',
        'detectors.passportId': 'Passnummer',
        'detectors.licensePlate': 'Kennzeichen',
        'detectors.dateOfBirth': 'Geburtsdatum',
        'detectors.customRegex': 'Eigenes Muster',
    },
    en: {
        ...governanceHowtoLabels.en,
        'governance.pageTitle': 'Governance AI Sanitizer',
        'governance.pageLead':
            'Walk through prompt sanitization, copy the outbound message to your LLM, then restore mapped PII from the AI reply.',
        'outbound.heading': 'Copy to LLM',
        'outbound.hint':
            'After Sanitize: copy this block into ChatGPT or your API user message. Your app role prompt stays separate.',
        'outbound.copy': 'Copy',
        'outbound.copied': 'Copied!',
        'panels.promptSanitizer.title': 'Prompt sanitizer',
        'panels.promptSanitizer.description':
            'Enter your prompt, sanitize sensitive data, then copy the outbound message to your LLM.',
        'panels.promptSanitizer.inputPlaceholder': 'Enter prompt text...',
        'panels.promptSanitizer.sanitizeLabel': 'Sanitize',
        'panels.promptSanitizer.findingsLabel': 'finding(s) detected.',
        'panels.aiResponse.title': 'AI response',
        'panels.aiResponse.description':
            'Paste the LLM reply here (keep placeholder tokens), then restore mapped PII.',
        'panels.aiResponse.inputPlaceholder': 'Paste an AI response with placeholder tokens...',
        'panels.aiResponse.simulateLabel': 'Simulate demo reply',
        'panels.aiResponse.restoreLabel': 'Restore',
        'panels.aiResponse.restoredHeading': 'Restored response',
        'panels.aiResponse.restoreHint':
            'Restore replaces placeholder tokens with the original values from your prompt.',
        'panels.findings.title': 'Findings',
        'panels.findings.empty': 'No findings yet.',
        'panels.findings.typeColumn': 'Type',
        'panels.findings.valueColumn': 'Value',
        'panels.findings.positionColumn': 'Position',
        'panels.findings.confidenceColumn': 'Confidence',
        'detectors.personName': 'Person name',
        'detectors.email': 'Email',
        'detectors.phone': 'Phone',
        'detectors.postalAddress': 'Postal address',
        'detectors.geoCoordinates': 'Geo coordinates',
        'detectors.companyName': 'Company name',
        'detectors.url': 'URL',
        'detectors.ipv4': 'IPv4 address',
        'detectors.ipv6': 'IPv6 address',
        'detectors.creditCard': 'Credit card',
        'detectors.iban': 'IBAN',
        'detectors.taxVatId': 'Tax / VAT ID',
        'detectors.passportId': 'Passport ID',
        'detectors.licensePlate': 'License plate',
        'detectors.dateOfBirth': 'Date of birth',
        'detectors.customRegex': 'Custom pattern',
    },
};

/** @param {ToolsLocale} locale @param {string} key */
export function t(locale, key) {
    return governanceLabels[locale][key] ?? governanceLabels.en[key] ?? key;
}

/** @param {ToolsLocale} locale */
export function applyGovernanceLabels(locale) {
    document.querySelectorAll('#governance-ai-sanitizer-app [data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (key) el.textContent = t(locale, key);
    });

    document.querySelectorAll('#governance-ai-sanitizer-app [data-i18n-placeholder]').forEach((el) => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (key && 'placeholder' in el) {
            el.placeholder = t(locale, key);
        }
    });
}
