@extends('layouts.tools')

@section('title', 'Disclaimer — ' . config('app.name'))
@section('meta_description', 'Hinweise zu Inhalt, Empfehlungen, Best Practices, Haftung, Vollständigkeit und Werbefreiheit von binom-tools.')

@section('content')
    <div class="tools-content tools-legal-page">
        <h1 class="tools-page-title" data-text-de="Disclaimer" data-text-en="Disclaimer">Disclaimer</h1>
        <p
            class="tools-page-lead"
            data-text-de="binom-tools ist eine Orientierungshilfe für Data Governance, Data Quality, PII/DSDR, BI, Analytics Engineering und Stack-Entscheidungen. Die Inhalte sollen helfen, Fragen besser zu strukturieren, ersetzen aber keine verbindliche Prüfung."
            data-text-en="binom-tools is an orientation aid for data governance, data quality, PII/DSDR, BI, analytics engineering, and stack decisions. The content helps structure questions, but does not replace binding review."
        >binom-tools is an orientation aid for data governance, data quality, PII/DSDR, BI, analytics engineering, and stack decisions. The content helps structure questions, but does not replace binding review.</p>

        <section class="tools-section tools-legal-page__section">
            <h2 class="tools-section__title" data-text-de="Keine verbindliche Beratung" data-text-en="No binding advice">No binding advice</h2>
            <p
                class="tools-impressum-body"
                data-text-de="Alle Inhalte, Tools, Checklisten, Reports, Links, Generatoren und Playbooks sind allgemeine Empfehlungen, Best Practices und Leitfäden. Sie sind keine Rechtsberatung, Steuerberatung, Wirtschaftsprüfung, Datenschutz-Folgenabschätzung, Security-Freigabe, Architekturfreigabe oder Projektfreigabe."
                data-text-en="All content, tools, checklists, reports, links, generators, and playbooks are general recommendations, best practices, and guides. They are not legal advice, tax advice, audit advice, a data protection impact assessment, security approval, architecture approval, or project approval."
            >All content, tools, checklists, reports, links, generators, and playbooks are general recommendations, best practices, and guides. They are not legal advice, tax advice, audit advice, a data protection impact assessment, security approval, architecture approval, or project approval.</p>
        </section>

        <section class="tools-section tools-legal-page__section">
            <h2 class="tools-section__title" data-text-de="Keine Gewähr für Vollständigkeit" data-text-en="No guarantee of completeness">No guarantee of completeness</h2>
            <p
                class="tools-impressum-body"
                data-text-de="Ich übernehme keine Verantwortung für Vollständigkeit, Aktualität, Richtigkeit, Eignung für einen bestimmten Zweck oder konkrete Projektergebnisse. Governance-, Compliance-, Datenschutz-, Security-, Tool- und Zertifizierungsanforderungen ändern sich und müssen im jeweiligen Kontext geprüft werden."
                data-text-en="I do not assume responsibility for completeness, timeliness, correctness, suitability for a specific purpose, or concrete project outcomes. Governance, compliance, privacy, security, tool, and certification requirements change and must be reviewed in the relevant context."
            >I do not assume responsibility for completeness, timeliness, correctness, suitability for a specific purpose, or concrete project outcomes. Governance, compliance, privacy, security, tool, and certification requirements change and must be reviewed in the relevant context.</p>
        </section>

        <section class="tools-section tools-legal-page__section">
            <h2 class="tools-section__title" data-text-de="Eigenverantwortliche Nutzung" data-text-en="Use with your own judgment">Use with your own judgment</h2>
            <p
                class="tools-impressum-body"
                data-text-de="Nutze Ergebnisse aus den Tools als Startpunkt für Diskussion, Dokumentation, Review und Umsetzung. Bevor Entscheidungen umgesetzt werden, sollten zuständige Owner, Datenschutz, Legal, Security, Architektur, Betriebsverantwortliche oder weitere Fachstellen die Ergebnisse prüfen."
                data-text-en="Use tool outputs as a starting point for discussion, documentation, review, and implementation. Before decisions are implemented, responsible owners, privacy, legal, security, architecture, operations, or other subject-matter teams should review the results."
            >Use tool outputs as a starting point for discussion, documentation, review, and implementation. Before decisions are implemented, responsible owners, privacy, legal, security, architecture, operations, or other subject-matter teams should review the results.</p>
        </section>

        <section class="tools-section tools-legal-page__section">
            <h2 class="tools-section__title" data-text-de="Keine Werbeplattform" data-text-en="Not an advertising platform">Not an advertising platform</h2>
            <p
                class="tools-impressum-body"
                data-text-de="Erwähnte Hersteller, Produkte, Frameworks, Zertifikate und externe Links dienen der Orientierung und Einordnung. Sie sind keine bezahlte Empfehlung, keine Werbung und keine Zusicherung, dass ein Produkt oder Anbieter für einen bestimmten Fall geeignet ist."
                data-text-en="Mentioned vendors, products, frameworks, certifications, and external links are included for orientation and context. They are not paid recommendations, advertising, or a promise that a product or vendor is suitable for a specific case."
            >Mentioned vendors, products, frameworks, certifications, and external links are included for orientation and context. They are not paid recommendations, advertising, or a promise that a product or vendor is suitable for a specific case.</p>
        </section>
    </div>
@endsection
