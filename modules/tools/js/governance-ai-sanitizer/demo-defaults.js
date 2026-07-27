/** @typedef {'de' | 'en'} ToolsLocale */

const LLM_RULES_DE = `- Alle [[BNM_...]]-Platzhalter-Tokens unverändert übernehmen (z. B. [[BNM_EMAIL_...]], [[BNM_PHONE_...]], [[BNM_URL_...]], [[BNM_CARD_...]], [[BNM_ADDRESS_...]], [[BNM_GEO_...]]).
- Sensible Werte in Antworten nur mit diesen exakten Tokens wiedergeben.
- Keine Platzhalter erfinden, ersetzen, decodieren oder entfernen.`;

const LLM_RULES_EN = `- Keep all [[BNM_...]] placeholder tokens exactly as provided (e.g. [[BNM_EMAIL_...]], [[BNM_PHONE_...]], [[BNM_URL_...]], [[BNM_CARD_...]], [[BNM_ADDRESS_...]], [[BNM_GEO_...]]).
- When mentioning sensitive values in replies, repeat those tokens verbatim.
- Do not invent, replace, decode, or remove placeholder tokens.`;

/** @type {Record<ToolsLocale, { userMessage: string; aiResponseTemplate: string; llmRules: string }>} */
export const demoDefaults = {
    de: {
        userMessage:
            'Bestellung #48291 verzögert. Kontakt alice@example.com oder +49 170 1234567. Bezahlt mit Karte 4111 1111 1111 1111 (IBAN DE89370400440532013000). Lieferung an Hauptstraße 12, 10115 Berlin. Tracking: https://portal.example.com/order/48291 von 10.0.0.1. Abhol-Pin geo:52.520008,13.404954. Pass AB1234567, Kennzeichen B-AB 1234, Geburtsdatum 12.03.1985.',
        aiResponseTemplate:
            'Danke für die Nachricht zu Bestellung #48291. Ich melde mich unter {{PLACEHOLDERS}}, sobald der Carrier den nächsten Scan bestätigt.',
        llmRules: LLM_RULES_DE,
    },
    en: {
        userMessage:
            'Order #48291 is delayed. Contact alice@example.com or +49 170 1234567. Paid with card 4111 1111 1111 1111 (IBAN DE89370400440532013000). Ship to Hauptstraße 12, 10115 Berlin. Tracking: https://portal.example.com/order/48291 from 10.0.0.1. Pickup pin geo:52.520008,13.404954. Passport AB1234567, plate B-AB 1234, DOB 12.03.1985.',
        aiResponseTemplate:
            'Thanks for the update on order #48291. I will follow up via {{PLACEHOLDERS}} once the carrier confirms the next scan.',
        llmRules: LLM_RULES_EN,
    },
};

/** @param {ToolsLocale} locale */
export function getDemoDefaults(locale) {
    return demoDefaults[locale] ?? demoDefaults.en;
}
