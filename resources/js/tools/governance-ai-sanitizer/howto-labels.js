/** @typedef {'de' | 'en'} ToolsLocale */

/** @type {Record<ToolsLocale, Record<string, string>>} */
export const governanceHowtoLabels = {
    de: {
        'gov.howto.summary': 'So funktioniert\'s',

        'gov.howto.overview.intro':
            'Der Governance AI Sanitizer schützt sensible Daten, bevor sie an ein LLM gehen — und stellt sie danach wieder her.',
        'gov.howto.overview.step1':
            'Prompt mit echten Daten eingeben (Namen, E-Mails, Adressen …).',
        'gov.howto.overview.step2':
            'Sanitisieren: PII wird erkannt und durch Platzhalter-Tokens ersetzt.',
        'gov.howto.overview.step3':
            'Outbound-Block kopieren und als User-Message an ChatGPT oder eure LLM-API senden.',
        'gov.howto.overview.step4':
            'LLM-Antwort einfügen (Tokens unverändert lassen).',
        'gov.howto.overview.step5':
            'Wiederherstellen: Platzhalter werden durch die Originalwerte aus Schritt 1 ersetzt.',
        'gov.howto.overview.tip':
            'Der System-Prompt eurer App bleibt separat — nur der User-Content wird sanitiert.',

        'gov.howto.prompt.intro':
            'Hier startet der Workflow: Der Prompt enthält die Daten, die geschützt werden sollen.',
        'gov.howto.prompt.step1':
            'Echten Prompt-Text einfügen — Demo-Daten oder Produktions-Beispiele.',
        'gov.howto.prompt.step2':
            'Sanitisieren klicken — der SDK-Detektor findet PII und ersetzt sie durch Tokens.',
        'gov.howto.prompt.step3':
            'Funde-Tabelle prüfen: Typ, Position und Konfidenz pro erkanntem Wert.',
        'gov.howto.prompt.tip':
            'Ohne Sanitize gibt es keinen Outbound-Block und keine Wiederherstellung.',

        'gov.howto.outbound.intro':
            'Der Outbound-Block ist die sichere Version deines Prompts für das LLM.',
        'gov.howto.outbound.step1':
            'Erscheint nach Sanitize — enthält Platzhalter statt echter PII.',
        'gov.howto.outbound.step2':
            'Kopieren und als User-Message an das LLM senden.',
        'gov.howto.outbound.step3':
            'System-Prompt und App-Rollen bleiben außerhalb dieses Blocks.',
        'gov.howto.outbound.tip':
            'Das LLM arbeitet nur mit den Tokens — echte Werte verlassen eure Umgebung nicht.',

        'gov.howto.aiResponse.intro':
            'Hier fügst du die LLM-Antwort ein und stellst die Original-PII wieder her.',
        'gov.howto.aiResponse.step1':
            'LLM-Antwort einfügen — Platzhalter-Tokens müssen unverändert bleiben.',
        'gov.howto.aiResponse.step2':
            'Demo-Antwort einfügen: Schnelltest mit vorgefertigter Antwort.',
        'gov.howto.aiResponse.step3':
            'Wiederherstellen: Tokens werden durch die gespeicherten Originalwerte ersetzt.',
        'gov.howto.aiResponse.tip':
            'Wiederherstellen ist erst nach Sanitize verfügbar — das Mapping kommt aus Schritt 1.',

        'gov.howto.findings.intro':
            'Die Funde-Tabelle zeigt alle erkannten PII-Stellen im Prompt.',
        'gov.howto.findings.step1':
            'Typ: Art der erkannten Daten (E-Mail, Name, IP …).',
        'gov.howto.findings.step2':
            'Wert: Der erkannte Originalwert (nur lokal sichtbar, nicht ans LLM gesendet).',
        'gov.howto.findings.step3':
            'Position: Zeichen-Offset im Prompt für Nachvollziehbarkeit.',
        'gov.howto.findings.step4':
            'Konfidenz: Sicherheit der Erkennung — höher = zuverlässiger.',
    },
    en: {
        'gov.howto.summary': 'How it works',

        'gov.howto.overview.intro':
            'The Governance AI Sanitizer protects sensitive data before it goes to an LLM — and restores it afterward.',
        'gov.howto.overview.step1':
            'Enter a prompt with real data (names, emails, addresses …).',
        'gov.howto.overview.step2':
            'Sanitize: PII is detected and replaced with placeholder tokens.',
        'gov.howto.overview.step3':
            'Copy the outbound block and send it as the user message to ChatGPT or your LLM API.',
        'gov.howto.overview.step4':
            'Paste the LLM reply (keep tokens unchanged).',
        'gov.howto.overview.step5':
            'Restore: placeholders are replaced with the original values from step 1.',
        'gov.howto.overview.tip':
            'Your app system prompt stays separate — only user content is sanitized.',

        'gov.howto.prompt.intro':
            'This is where the workflow starts: the prompt contains data to protect.',
        'gov.howto.prompt.step1':
            'Paste real prompt text — demo or production examples.',
        'gov.howto.prompt.step2':
            'Click Sanitize — the SDK detector finds PII and replaces it with tokens.',
        'gov.howto.prompt.step3':
            'Check the findings table: type, position, and confidence per detected value.',
        'gov.howto.prompt.tip':
            'Without Sanitize there is no outbound block and no restore.',

        'gov.howto.outbound.intro':
            'The outbound block is the safe version of your prompt for the LLM.',
        'gov.howto.outbound.step1':
            'Appears after Sanitize — contains placeholders instead of real PII.',
        'gov.howto.outbound.step2':
            'Copy and send as the user message to the LLM.',
        'gov.howto.outbound.step3':
            'System prompt and app roles stay outside this block.',
        'gov.howto.outbound.tip':
            'The LLM only works with tokens — real values never leave your environment.',

        'gov.howto.aiResponse.intro':
            'Paste the LLM reply here and restore original PII.',
        'gov.howto.aiResponse.step1':
            'Paste LLM reply — placeholder tokens must remain unchanged.',
        'gov.howto.aiResponse.step2':
            'Simulate demo reply: quick test with a prefilled response.',
        'gov.howto.aiResponse.step3':
            'Restore: tokens are replaced with stored original values.',
        'gov.howto.aiResponse.tip':
            'Restore is only available after Sanitize — mapping comes from step 1.',

        'gov.howto.findings.intro':
            'The findings table shows all detected PII in the prompt.',
        'gov.howto.findings.step1':
            'Type: kind of detected data (email, name, IP …).',
        'gov.howto.findings.step2':
            'Value: detected original value (visible locally only, not sent to LLM).',
        'gov.howto.findings.step3':
            'Position: character offset in the prompt for traceability.',
        'gov.howto.findings.step4':
            'Confidence: detection certainty — higher = more reliable.',
    },
};
