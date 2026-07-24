#!/usr/bin/env python3
"""Generate config/compliance-items.php (Compliance Hub detail content).

Loads the already prepared GDPR/BDSG items from /tmp/compliance_items.pkl,
appends the remaining eleven frameworks and renders one PHP array file.

Learning and orientation content only — not legal advice.
"""

from __future__ import annotations

import pickle
import sys
from pathlib import Path

PICKLE_PATH = Path('/tmp/compliance_items.pkl')
OUT_PATH = Path(__file__).resolve().parent.parent / 'config' / 'compliance-items.php'

# Data key -> PHP key for the four card blocks and the trailing lists.
CARD_KEYS = {
    'keyRules': 'keyRules',
    'platform': 'platformImplications',
    'checklist': 'checklist',
    'pitfalls': 'commonPitfalls',
}

REQUIRED_KEYS = (
    'id', 'category', 'region', 'type', 'depth', 'order',
    'label', 'shortPurpose', 'whyItMatters', 'appliesTo',
    'scopeNotes', 'keyRules', 'platform', 'checklist', 'pitfalls',
    'sources', 'playbooks',
)

IND = '    '


# --- string helpers ----------------------------------------------------------

def norm(value: str) -> str:
    """Turn literal backslash-n markers into real newlines."""
    return str(value).replace('\\n', '\n').replace('\r\n', '\n')


def esc(s: str) -> str:
    """Escape for a PHP single-quoted string."""
    return s.replace('\\', '\\\\').replace("'", "\\'")


def esc_dq(s: str) -> str:
    """Escape for a PHP double-quoted string, keeping newlines as \\n."""
    out = s.replace('\\', '\\\\').replace('"', '\\"').replace('$', '\\$')
    return out.replace('\n', '\\n')


def php_str(value: str) -> str:
    """Render a PHP string literal; paragraph text keeps real newlines."""
    text = norm(value)
    if '\n' in text:
        return '"' + esc_dq(text) + '"'
    return "'" + esc(text) + "'"


# --- content shorthands ------------------------------------------------------

def R(tde, ten, dde, den, rde=None, ren=None):
    """Card with bilingual title/detail and optional reference."""
    card = {'title': {'de': tde, 'en': ten}, 'detail': {'de': dde, 'en': den}}
    if rde is not None or ren is not None:
        card['ref'] = {'de': rde if rde is not None else ren, 'en': ren if ren is not None else rde}
    return card


def N(de, en):
    return {'de': de, 'en': en}


def S(de, en, href):
    return {'de': de, 'en': en, 'href': href}


# --- PHP block writers -------------------------------------------------------

def pair_block(key: str, pair: dict, indent: str) -> str:
    return (
        f"{indent}'{key}' => [\n"
        f"{indent}{IND}'de' => {php_str(pair['de'])},\n"
        f"{indent}{IND}'en' => {php_str(pair.get('en', pair['de']))},\n"
        f"{indent}],\n"
    )


def notes_block(key: str, notes: list, indent: str) -> str:
    out = [f"{indent}'{key}' => [\n"]
    for locale in ('de', 'en'):
        out.append(f"{indent}{IND}'{locale}' => [\n")
        for note in notes:
            out.append(f"{indent}{IND}{IND}{php_str(note[locale])},\n")
        out.append(f"{indent}{IND}],\n")
    out.append(f"{indent}],\n")
    return ''.join(out)


def cards_block(name: str, cards: list, indent: str) -> str:
    """Render parallel de/en card arrays under the mapped PHP key."""
    php_key = CARD_KEYS.get(name, name)
    out = [f"{indent}'{php_key}' => [\n"]
    for locale in ('de', 'en'):
        out.append(f"{indent}{IND}'{locale}' => [\n")
        for card in cards:
            out.append(f"{indent}{IND}{IND}[\n")
            out.append(f"{indent}{IND}{IND}{IND}'title' => {php_str(card['title'][locale])},\n")
            out.append(f"{indent}{IND}{IND}{IND}'detail' => {php_str(card['detail'][locale])},\n")
            if card.get('ref'):
                out.append(f"{indent}{IND}{IND}{IND}'ref' => {php_str(card['ref'][locale])},\n")
            out.append(f"{indent}{IND}{IND}],\n")
        out.append(f"{indent}{IND}],\n")
    out.append(f"{indent}],\n")
    return ''.join(out)


def sources_block(sources: list, indent: str) -> str:
    out = [f"{indent}'officialSources' => [\n"]
    for source in sources:
        out.append(f"{indent}{IND}[\n")
        out.append(f"{indent}{IND}{IND}'label' => [\n")
        out.append(f"{indent}{IND}{IND}{IND}'de' => {php_str(source['de'])},\n")
        out.append(f"{indent}{IND}{IND}{IND}'en' => {php_str(source['en'])},\n")
        out.append(f"{indent}{IND}{IND}],\n")
        out.append(f"{indent}{IND}{IND}'href' => {php_str(source['href'])},\n")
        out.append(f"{indent}{IND}],\n")
    out.append(f"{indent}],\n")
    return ''.join(out)


def playbooks_block(slugs: list, indent: str) -> str:
    out = [f"{indent}'relatedPlaybooks' => [\n"]
    for slug in slugs:
        out.append(f"{indent}{IND}{php_str(slug)},\n")
    out.append(f"{indent}],\n")
    return ''.join(out)


def item(data: dict) -> str:
    indent = IND * 2
    out = [f"{IND}[\n"]
    for key in ('id', 'category', 'region', 'type', 'depth'):
        out.append(f"{indent}'{key}' => {php_str(data[key])},\n")
    out.append(f"{indent}'order' => {int(data['order'])},\n")
    for key in ('label', 'shortPurpose', 'whyItMatters', 'appliesTo'):
        out.append(pair_block(key, data[key], indent))
    out.append(notes_block('scopeNotes', data['scopeNotes'], indent))
    for key in ('keyRules', 'platform', 'checklist', 'pitfalls'):
        out.append(cards_block(key, data[key], indent))
    out.append(sources_block(data['sources'], indent))
    out.append(playbooks_block(data['playbooks'], indent))
    out.append(f"{IND}],\n")
    return ''.join(out)


# --- items -------------------------------------------------------------------

INTERNATIONAL_TRANSFERS = {
    'id': 'international-transfers',
    'category': 'privacy',
    'region': 'eu',
    'type': 'regulation',
    'depth': 'full',
    'order': 30,
    'label': N('Internationale Transfers (Schrems II / SCC)', 'International transfers (Schrems II / SCCs)'),
    'shortPurpose': N(
        'Regeln für die Übermittlung personenbezogener Daten in Drittländer — Angemessenheit, Standardvertragsklauseln und Zusatzmaßnahmen.',
        'Rules for transferring personal data to third countries — adequacy, standard contractual clauses and supplementary measures.',
    ),
    'whyItMatters': N(
        "Moderne Datenplattformen sind selten rein europäisch: Warehouse in einer EU-Region, Support aus Asien, Monitoring-SaaS in den USA, Modell-API in Kalifornien.\n\nKapitel V der DSGVO verlangt für jede dieser Konstellationen einen Übermittlungsmechanismus — seit Schrems II zusätzlich die Bewertung, ob das Recht des Ziellands die vertraglichen Garantien praktisch aushebelt. Für Plattform-Teams ist das keine reine Vertragsfrage: Region, Verschlüsselung, Key-Management und Admin-Zugriff entscheiden mit.",
        "Modern data platforms are rarely purely European: warehouse in an EU region, support from Asia, monitoring SaaS in the US, model API in California.\n\nChapter V of the GDPR requires a transfer mechanism for each of these constellations — and since Schrems II also an assessment of whether the destination country’s law undermines the contractual safeguards in practice. For platform teams this is not only a contract question: region, encryption, key management and admin access matter just as much.",
    ),
    'appliesTo': N(
        "Jede Übermittlung personenbezogener Daten an Empfänger außerhalb EU/EWR — inklusive Fernzugriff, Backups, Ticket-Anhängen und Sub-Auftragsverarbeitern in Drittländern.\n\nEntscheidend ist der Zugriff, nicht nur der Speicherort: Ein EU-gehostetes System mit Support-Zugriff aus einem Drittland löst denselben Prüfbedarf aus wie eine Replikation in eine US-Region.",
        "Any transfer of personal data to recipients outside the EU/EEA — including remote access, backups, ticket attachments and sub-processors in third countries.\n\nAccess is decisive, not only storage location: an EU-hosted system with support access from a third country triggers the same assessment as replication into a US region.",
    ),
    'scopeNotes': [
        N(
            'Kapitel V betrifft personenbezogene Daten — echte Aggregate ohne Re-Identifikationsrisiko fallen nicht darunter.',
            'Chapter V covers personal data — genuine aggregates without re-identification risk are out of scope.',
        ),
        N(
            '„Übermittlung“ umfasst auch Fernzugriff, Wartung und Support, nicht nur Kopien in ein Drittland.',
            '“Transfer” also covers remote access, maintenance and support — not only copies into a third country.',
        ),
        N(
            'Angemessenheitsbeschlüsse (z. B. EU-US Data Privacy Framework) können sich ändern oder gerichtlich überprüft werden — Fallback einplanen.',
            'Adequacy decisions (e.g. the EU-US Data Privacy Framework) can change or be challenged in court — plan a fallback.',
        ),
        N(
            'Diese Seite ist Orientierung — Transfer Impact Assessment und Verträge gehören zu Legal/DSB.',
            'This page is orientation — transfer impact assessments and contracts belong with legal/DPO.',
        ),
    ],
    'keyRules': [
        R(
            'Kein Transfer ohne Mechanismus',
            'No transfer without a mechanism',
            'Übermittlungen in Drittländer brauchen eine der in Kapitel V genannten Grundlagen. „Der Vendor ist groß und seriös“ ist keine.',
            'Transfers to third countries need one of the mechanisms listed in Chapter V. “The vendor is large and reputable” is not one of them.',
            'Art. 44', 'Art. 44',
        ),
        R(
            'Angemessenheitsbeschluss prüfen',
            'Check the adequacy decision',
            'Für Länder mit Angemessenheitsbeschluss entfallen Zusatzinstrumente. Beim EU-US Data Privacy Framework gilt das nur für zertifizierte Empfänger — Zertifizierung und abgedeckte Datenkategorien wirklich nachsehen.',
            'For countries with an adequacy decision no additional instrument is needed. Under the EU-US Data Privacy Framework this only applies to certified recipients — actually verify certification status and covered data categories.',
            'Art. 45', 'Art. 45',
        ),
        R(
            'Standardvertragsklauseln im richtigen Modul',
            'Standard contractual clauses in the right module',
            'Die SCC von 2021 haben vier Module (C2C, C2P, P2P, P2C). Das falsche Modul ist ein häufiger Formfehler — die Rollenverteilung im Datenfluss muss zum Modul passen.',
            'The 2021 SCCs come in four modules (C2C, C2P, P2P, P2C). Picking the wrong module is a common formal error — the roles in the data flow must match the module.',
            'Art. 46 Abs. 2 lit. c', 'Art. 46(2)(c)',
        ),
        R(
            'Transfer Impact Assessment und Zusatzmaßnahmen',
            'Transfer impact assessment and supplementary measures',
            'Nach Schrems II reicht die Unterschrift nicht: Recht und Praxis im Zielland bewerten und — wo nötig — technische Zusatzmaßnahmen ergänzen (Verschlüsselung mit EU-Schlüsselhoheit, Pseudonymisierung, Split Processing).',
            'After Schrems II a signature is not enough: assess law and practice in the destination country and, where needed, add technical supplementary measures (encryption with EU key control, pseudonymisation, split processing).',
            'EDPB Empfehlungen 01/2020', 'EDPB Recommendations 01/2020',
        ),
        R(
            'Binding Corporate Rules für Konzernflüsse',
            'Binding corporate rules for group flows',
            'Für konzerninterne Übermittlungen können genehmigte BCR der stabilere Weg sein als SCC pro Gesellschaft — Genehmigungsaufwand einplanen.',
            'For intra-group transfers, approved BCRs can be more stable than SCCs per entity — plan for the approval effort.',
            'Art. 47', 'Art. 47',
        ),
        R(
            'Ausnahmen sind Einzelfälle',
            'Derogations are for individual cases',
            'Einwilligung oder Vertragserfüllung als Transfergrundlage sind eng auszulegen und nicht für regelmäßige, systematische Plattform-Flows gedacht.',
            'Consent or contract performance as a transfer basis must be read narrowly and are not meant for regular, systematic platform flows.',
            'Art. 49', 'Art. 49',
        ),
        R(
            'Behördenanfragen aus Drittländern',
            'Third-country authority requests',
            'Herausgabeverlangen ausländischer Behörden dürfen nicht ohne Rechtshilfeabkommen oder andere unionsrechtliche Grundlage erfüllt werden. Eskalationspfad und Meldewege vorab klären.',
            'Disclosure requests from foreign authorities must not be fulfilled without a mutual legal assistance treaty or another basis in Union law. Clarify escalation and notification paths in advance.',
            'Art. 48', 'Art. 48',
        ),
        R(
            'Sub-Prozessor-Kette kontrollieren',
            'Control the sub-processor chain',
            'Auftragsverarbeiter dürfen Subprozessoren nur mit Genehmigung einsetzen und müssen gleichwertige Pflichten weitergeben. Genau hier entstehen unbemerkte Transfers.',
            'Processors may only engage sub-processors with authorisation and must pass on equivalent obligations. This is exactly where unnoticed transfers appear.',
            'Art. 28 Abs. 2 und 4', 'Art. 28(2) and (4)',
        ),
    ],
    'platform': [
        R(
            'Region ist nicht gleich Zugriff',
            'Region is not the same as access',
            'Eine EU-Region sagt nichts über Follow-the-Sun-Support, Betriebsteams oder Telemetrie. Admin- und Support-Zugriffe pro Dienst dokumentieren und wo möglich auf EU-Personal begrenzen.',
            'An EU region says nothing about follow-the-sun support, operations teams or telemetry. Document admin and support access per service and restrict it to EU staff where possible.',
        ),
        R(
            'Verschlüsselung mit eigener Schlüsselhoheit',
            'Encryption with your own key control',
            'Zusatzmaßnahmen wirken nur, wenn der Anbieter die Schlüssel nicht selbst hält: BYOK/HYOK, EU-KMS, Tokenisierung vor dem Upload. Verschlüsselung „at rest“ beim Anbieter allein ist schwach.',
            'Supplementary measures only work if the provider does not hold the keys: BYOK/HYOK, EU-based KMS, tokenisation before upload. Provider-managed encryption at rest alone is weak.',
        ),
        R(
            'Transferregister je Dataset und Vendor',
            'Transfer register per dataset and vendor',
            'Verknüpft Datasets, Zwecke, Empfänger, Länder und Mechanismus. Ohne diese Liste ist keine TIA und kein Audit sauber führbar.',
            'Link datasets, purposes, recipients, countries and mechanism. Without that list no TIA and no audit can be run cleanly.',
        ),
        R(
            'Minimieren, bevor übermittelt wird',
            'Minimise before you transfer',
            'Feldauswahl, Pseudonymisierung und Aggregation reduzieren das Transfer-Risiko oft stärker als jede Vertragsklausel — besonders bei Support-Exports und Debug-Datensätzen.',
            'Field selection, pseudonymisation and aggregation often reduce transfer risk more than any contract clause — especially for support exports and debug datasets.',
        ),
        R(
            'AI-Endpunkte und Prompt-Logs',
            'AI endpoints and prompt logs',
            'Modell-APIs, Embeddings und Prompt-Logs verlassen die Region oft unbemerkt. Region, Retention und Trainingsnutzung vertraglich und technisch prüfen.',
            'Model APIs, embeddings and prompt logs often leave the region unnoticed. Check region, retention and training use both contractually and technically.',
        ),
        R(
            'Fallback- und Exit-Pfad',
            'Fallback and exit path',
            'Was passiert, wenn ein Angemessenheitsbeschluss fällt? Alternative Region, alternativer Anbieter und Migrationsaufwand grob vorplanen — nicht erst im Krisenmodus.',
            'What happens if an adequacy decision falls? Sketch an alternative region, alternative provider and migration effort in advance — not in crisis mode.',
        ),
    ],
    'checklist': [
        R(
            'Transferregister vollständig?',
            'Transfer register complete?',
            'Inklusive Monitoring, Ticketing, CI/CD, BI-Extracts und LLM-Endpunkten — nicht nur das Warehouse.',
            'Including monitoring, ticketing, CI/CD, BI extracts and LLM endpoints — not only the warehouse.',
        ),
        R(
            'Mechanismus je Transfer benannt?',
            'Mechanism named per transfer?',
            'Adequacy, SCC-Modul oder BCR pro Empfänger dokumentiert und aktuell.',
            'Adequacy, SCC module or BCR documented and current per recipient.',
        ),
        R(
            'TIA dokumentiert und datiert?',
            'TIA documented and dated?',
            'Mit Annahmen, Zusatzmaßnahmen und Review-Datum — nicht als einmaliges PDF ohne Owner.',
            'With assumptions, supplementary measures and a review date — not a one-off PDF without an owner.',
        ),
        R(
            'Zusatzmaßnahmen technisch wirksam?',
            'Supplementary measures technically effective?',
            'Schlüsselhoheit, Masking und Zugriffsgrenzen im Betrieb geprüft, nicht nur beschrieben.',
            'Key control, masking and access boundaries verified in operations, not only described.',
        ),
        R(
            'DPF-Zertifizierung geprüft?',
            'DPF certification verified?',
            'Empfänger im Data Privacy Framework gelistet, Status aktiv und passende Datenkategorien abgedeckt.',
            'Recipient listed under the Data Privacy Framework, status active and matching data categories covered.',
        ),
        R(
            'Fallback durchgespielt?',
            'Fallback rehearsed?',
            'Grober Plan, welche Flows bei Wegfall des Mechanismus zuerst umgestellt oder gestoppt werden.',
            'A rough plan for which flows get switched or stopped first if the mechanism disappears.',
        ),
    ],
    'pitfalls': [
        R(
            '„EU-Region reicht“',
            '“An EU region is enough”',
            'Speicherort ohne Zugriffskontrolle löst nichts: Remote-Support, Telemetrie und Konzern-Admins bleiben Transfers.',
            'Storage location without access control solves nothing: remote support, telemetry and group admins remain transfers.',
        ),
        R(
            'DPF pauschal angenommen',
            'DPF assumed blanket-wide',
            'Nicht jeder US-Anbieter ist zertifiziert, und Zertifizierungen deckten nicht immer alle Datenarten oder Konzerngesellschaften ab.',
            'Not every US provider is certified, and certifications do not always cover all data types or group entities.',
        ),
        R(
            'SCC unterschrieben, TIA fehlt',
            'SCCs signed, TIA missing',
            'Die Klauseln allein erfüllen Schrems II nicht — ohne Bewertung und Zusatzmaßnahmen bleibt eine Lücke.',
            'The clauses alone do not satisfy Schrems II — without assessment and supplementary measures a gap remains.',
        ),
        R(
            'Verschlüsselung ohne Schlüsselkontrolle',
            'Encryption without key control',
            'Wenn der Anbieter die Schlüssel verwaltet, hilft Verschlüsselung gegen Behördenzugriff kaum.',
            'If the provider manages the keys, encryption hardly helps against government access.',
        ),
        R(
            'Schatten-Transfers',
            'Shadow transfers',
            'Notebooks, Browser-Plugins, Screenshots in Support-Tickets und private LLM-Accounts erzeugen Übermittlungen, die in keinem Register stehen.',
            'Notebooks, browser plugins, screenshots in support tickets and personal LLM accounts create transfers that appear in no register.',
        ),
    ],
    'sources': [
        S(
            'EU-Kommission — Standardvertragsklauseln',
            'European Commission — standard contractual clauses',
            'https://commission.europa.eu/law/law-topic/data-protection/international-dimension-data-protection/standard-contractual-clauses-scc_en',
        ),
        S(
            'EuGH — Schrems II (C-311/18)',
            'CJEU — Schrems II (C-311/18)',
            'https://curia.europa.eu/juris/documents.jsf?num=C-311/18',
        ),
        S(
            'EDPB — Empfehlungen 01/2020 (Zusatzmaßnahmen)',
            'EDPB — Recommendations 01/2020 (supplementary measures)',
            'https://www.edpb.europa.eu/our-work-tools/our-documents/recommendations/recommendations-012020-measures-supplement-transfer_en',
        ),
        S(
            'Angemessenheitsbeschluss EU-US Data Privacy Framework — EUR-Lex',
            'EU-US Data Privacy Framework adequacy decision — EUR-Lex',
            'https://eur-lex.europa.eu/eli/dec_impl/2023/1795/oj',
        ),
    ],
    'playbooks': ['pii-privacy-governance', 'host-vs-cloud', 'dsdr-governance'],
}

ISO_27001 = {
    'id': 'iso-27001',
    'category': 'security',
    'region': 'intl',
    'type': 'standard',
    'depth': 'full',
    'order': 40,
    'label': N('ISO/IEC 27001', 'ISO/IEC 27001'),
    'shortPurpose': N(
        'Internationaler Standard für ein Informationssicherheits-Managementsystem (ISMS) — Risiken, Controls und kontinuierliche Verbesserung.',
        'International standard for an information security management system (ISMS) — risk, controls and continual improvement.',
    ),
    'whyItMatters': N(
        "ISO/IEC 27001 ist in Ausschreibungen und Vendor-Assessments die häufigste Eintrittskarte — und für Datenplattformen ein brauchbares Gerüst, um Zugriff, Betrieb und Lieferanten sauber zu ordnen.\n\nDer Kern ist nicht die Control-Liste, sondern der Managementzyklus: Kontext und Scope festlegen, Risiken bewerten, Maßnahmen auswählen und begründen, Wirksamkeit messen, nachbessern. Genau diese Denkweise fehlt vielen Plattform-Teams, die Sicherheit als Sammlung einzelner Einstellungen betreiben.",
        "ISO/IEC 27001 is the most common entry ticket in tenders and vendor assessments — and for data platforms a workable frame to order access, operations and suppliers.\n\nIts core is not the control list but the management cycle: define context and scope, assess risk, select and justify measures, measure effectiveness, improve. That way of thinking is what many platform teams miss when they treat security as a pile of individual settings.",
    ),
    'appliesTo': N(
        "Jede Organisation, die ein ISMS aufbauen oder zertifizieren will — unabhängig von Größe und Branche. Der Scope wird selbst definiert, und genau darin liegt die Kunst.\n\nFür Datenteams heißt das: Warehouse, Orchestrierung, BI und Betriebsprozesse gehören in den Scope, wenn schützenswerte Daten dort verarbeitet werden. Sonst zertifiziert man an der eigentlichen Plattform vorbei.",
        "Any organisation that wants to build or certify an ISMS — regardless of size or industry. The scope is self-defined, and that is where the craft lies.\n\nFor data teams that means warehouse, orchestration, BI and operational processes belong in scope if sensitive data is processed there. Otherwise you certify around the actual platform.",
    ),
    'scopeNotes': [
        N(
            'Zertifizierung gilt nur für den definierten Scope — ein Zertifikat sagt nichts über nicht erfasste Systeme.',
            'Certification only covers the defined scope — a certificate says nothing about systems left out.',
        ),
        N(
            'ISO 27001 ist Sicherheitsmanagement, kein Datenschutznachweis; dafür gibt es ISO 27701 und die DSGVO selbst.',
            'ISO 27001 is security management, not privacy proof; ISO 27701 and the GDPR itself cover that.',
        ),
        N(
            'Annex A liefert Controls, ISO 27002 die Umsetzungshinweise — beides ist Auswahlmenü, nicht Pflichtprogramm.',
            'Annex A provides controls, ISO 27002 the implementation guidance — both are a menu, not a mandatory checklist.',
        ),
        N(
            'Das Zertifikat eines Cloud-Anbieters ersetzt nicht eure eigenen Controls in der geteilten Verantwortung.',
            'A cloud provider’s certificate does not replace your own controls under shared responsibility.',
        ),
    ],
    'keyRules': [
        R(
            'Kontext und Scope bestimmen',
            'Determine context and scope',
            'Interessierte Parteien, Anforderungen und Grenzen des ISMS festlegen. Für Plattformen: welche Umgebungen, Datenklassen und Dienstleister drin sind — und was bewusst draußen bleibt.',
            'Define interested parties, requirements and ISMS boundaries. For platforms: which environments, data classes and service providers are in — and what is deliberately out.',
            'Kapitel 4', 'Clause 4',
        ),
        R(
            'Führung und Sicherheitspolitik',
            'Leadership and security policy',
            'Leitung muss Politik, Ziele und Rollen verbindlich setzen. Ohne Mandat bleiben Zugriffs- und Klassifizierungsregeln in Datenteams unverbindliche Empfehlungen.',
            'Top management must set policy, objectives and roles with authority. Without a mandate, access and classification rules stay optional advice inside data teams.',
            'Kapitel 5', 'Clause 5',
        ),
        R(
            'Risikobeurteilung und -behandlung',
            'Risk assessment and treatment',
            'Risiken systematisch bewerten, Optionen wählen und die Auswahl in der Erklärung zur Anwendbarkeit (SoA) begründen — inklusive bewusster Ausschlüsse.',
            'Assess risks systematically, choose options and justify the selection in the statement of applicability (SoA) — including deliberate exclusions.',
            'Kapitel 6.1', 'Clause 6.1',
        ),
        R(
            'Kompetenz, Awareness und Dokumentation',
            'Competence, awareness and documented information',
            'Menschen und Nachweise gehören zum System: Schulungen, Verantwortlichkeiten und versionierte Dokumente statt Wissen in Köpfen.',
            'People and evidence are part of the system: training, responsibilities and versioned documents instead of knowledge in heads.',
            'Kapitel 7', 'Clause 7',
        ),
        R(
            'Betrieb und Änderungssteuerung',
            'Operation and change control',
            'Prozesse planen, umsetzen und steuern — auch bei Änderungen. Für Datenplattformen sind Deployments, Schema-Änderungen und Zugriffsanträge die relevanten Betriebsprozesse.',
            'Plan, implement and control processes — including changes. For data platforms the relevant operational processes are deployments, schema changes and access requests.',
            'Kapitel 8', 'Clause 8',
        ),
        R(
            'Überwachung, internes Audit, Management Review',
            'Monitoring, internal audit, management review',
            'Wirksamkeit messen, intern prüfen und auf Leitungsebene bewerten. Kennzahlen wie offene Zugriffs-Findings oder Patch-Rückstand sind hier wertvoller als Prosa.',
            'Measure effectiveness, audit internally and review at management level. Metrics such as open access findings or patch backlog are more useful here than prose.',
            'Kapitel 9', 'Clause 9',
        ),
        R(
            'Abweichungen und Verbesserung',
            'Nonconformity and improvement',
            'Findings, Korrekturmaßnahmen und Wirksamkeitskontrolle dokumentieren. Der Zyklus ist der eigentliche Wert des Standards.',
            'Document findings, corrective actions and effectiveness checks. The cycle is the standard’s real value.',
            'Kapitel 10', 'Clause 10',
        ),
        R(
            'Annex-A-Controls gezielt einsetzen',
            'Use Annex A controls deliberately',
            'Die Controls sind in vier Themen gruppiert (organisatorisch, personenbezogen, physisch, technologisch). ISO 27002 liefert die Umsetzungshinweise, etwa zu Zugriffsrechten, Logging und Kryptografie.',
            'The controls are grouped into four themes (organisational, people, physical, technological). ISO 27002 provides implementation guidance, for example on access rights, logging and cryptography.',
            'Annex A, ISO/IEC 27002', 'Annex A, ISO/IEC 27002',
        ),
    ],
    'platform': [
        R(
            'Asset- und Dateninventar',
            'Asset and data inventory',
            'Datasets, Data Products, Service-Accounts und Integrationen als Assets führen — mit Owner und Klassifizierung. Das ist die Basis für fast jedes Control.',
            'Track datasets, data products, service accounts and integrations as assets — with owner and classification. That is the basis for nearly every control.',
        ),
        R(
            'Zugriffsmanagement als Prozess',
            'Access management as a process',
            'Rollenmodell, Antrag, Genehmigung, Entzug und regelmäßige Reviews — inklusive privilegierter Konten in Warehouse und BI.',
            'Role model, request, approval, revocation and periodic reviews — including privileged accounts in warehouse and BI.',
        ),
        R(
            'Logging und Monitoring',
            'Logging and monitoring',
            'Query-, Login- und Admin-Logs zentral sammeln, Aufbewahrung festlegen und Auswertungen definieren. Logs ohne Auswertung sind kein Control.',
            'Collect query, login and admin logs centrally, define retention and define evaluations. Logs without evaluation are not a control.',
        ),
        R(
            'Sichere Entwicklung für Pipelines',
            'Secure development for pipelines',
            'Code Review, getrennte Umgebungen, Secret-Handling und Test-Daten-Regeln gelten auch für dbt-Projekte, Notebooks und Orchestrierung.',
            'Code review, separated environments, secret handling and test-data rules also apply to dbt projects, notebooks and orchestration.',
        ),
        R(
            'Lieferantensteuerung',
            'Supplier management',
            'Cloud, SaaS und Beratungspartner bewerten, vertraglich binden und periodisch nachprüfen — mit Nachweisen statt Marketingseiten.',
            'Assess, contractually bind and periodically re-check cloud, SaaS and consulting partners — with evidence instead of marketing pages.',
        ),
        R(
            'Kryptografie und Schlüssel',
            'Cryptography and keys',
            'Verschlüsselung in Transit und at Rest, Schlüsselverwaltung und Rotation dokumentieren — inklusive Ausnahmen für Legacy-Schnittstellen.',
            'Document encryption in transit and at rest, key management and rotation — including exceptions for legacy interfaces.',
        ),
    ],
    'checklist': [
        R(
            'Deckt der Scope die Plattform ab?',
            'Does the scope cover the platform?',
            'Warehouse, Orchestrierung, BI und relevante Cloud-Konten sind ausdrücklich genannt.',
            'Warehouse, orchestration, BI and the relevant cloud accounts are named explicitly.',
        ),
        R(
            'SoA mit echten Begründungen?',
            'SoA with real justifications?',
            'Jedes ausgeschlossene Control hat eine nachvollziehbare Begründung, kein Standardtext.',
            'Every excluded control has a traceable justification, not boilerplate.',
        ),
        R(
            'Risikoregister mit Datenrisiken?',
            'Risk register with data risks?',
            'Enthält plattformtypische Risiken: überbreite Rechte, Kopien in Notebooks, unklare Ownership.',
            'Contains platform-typical risks: overly broad rights, copies in notebooks, unclear ownership.',
        ),
        R(
            'Access-Review-Evidence auffindbar?',
            'Access review evidence findable?',
            'Letzte Reviews mit Datum, Prüfer und Ergebnis liegen greifbar vor.',
            'Recent reviews with date, reviewer and outcome are readily available.',
        ),
        R(
            'Lieferantenbewertungen aktuell?',
            'Supplier assessments current?',
            'Kritische Anbieter haben eine dokumentierte Bewertung im laufenden Zyklus.',
            'Critical providers have a documented assessment within the current cycle.',
        ),
        R(
            'Internes Audit mit Plattform-Fokus?',
            'Internal audit with platform focus?',
            'Mindestens ein Audit hat Pipelines und Zugriffe konkret geprüft, nicht nur Policies gelesen.',
            'At least one audit actually examined pipelines and access, not only read policies.',
        ),
    ],
    'pitfalls': [
        R(
            'Scope schneidet die Plattform aus',
            'Scope cuts out the platform',
            'Ein Zertifikat für Rechenzentrum und HQ beeindruckt Kunden, deckt aber das Analytics-Setup nicht ab.',
            'A certificate for the data centre and HQ impresses customers but does not cover the analytics setup.',
        ),
        R(
            'SoA als Papierübung',
            'SoA as a paper exercise',
            'Controls „umgesetzt“ zu markieren, ohne Runtime-Nachweis, fällt im ersten Audit oder Incident auf.',
            'Marking controls “implemented” without runtime evidence surfaces at the first audit or incident.',
        ),
        R(
            'Policies ohne Durchsetzung',
            'Policies without enforcement',
            'Klassifizierungsvorgaben ohne Masking, Rollen ohne Grants und Reviews ohne Entzug bleiben wirkungslos.',
            'Classification rules without masking, roles without grants and reviews without revocation stay ineffective.',
        ),
        R(
            'ISO 27001 als DSGVO-Nachweis',
            'ISO 27001 as GDPR proof',
            'Sicherheitsmanagement liefert keine Rechtsgrundlage, keine Transparenz und keine Löschfähigkeit.',
            'Security management provides no legal basis, no transparency and no erasure capability.',
        ),
        R(
            'Vendor-Zertifikat als eigenes',
            'Vendor certificate treated as your own',
            'Der Anbieter deckt Infrastruktur ab; Konfiguration, Rechte und Daten bleiben eure Verantwortung.',
            'The provider covers infrastructure; configuration, permissions and data remain your responsibility.',
        ),
    ],
    'sources': [
        S('ISO — ISO/IEC 27001 (Produktseite)', 'ISO — ISO/IEC 27001 (product page)', 'https://www.iso.org/standard/27001'),
        S('ISO — ISO/IEC 27002 (Controls)', 'ISO — ISO/IEC 27002 (controls)', 'https://www.iso.org/standard/75652.html'),
    ],
    'playbooks': ['access-security-governance', 'host-vs-cloud'],
}

SOC_2 = {
    'id': 'soc-2',
    'category': 'security',
    'region': 'us',
    'type': 'framework',
    'depth': 'full',
    'order': 50,
    'label': N('SOC 2', 'SOC 2'),
    'shortPurpose': N(
        'AICPA-Prüfbericht zu Trust Services Criteria (Security, Availability, Confidentiality, Processing Integrity, Privacy) — typisch für SaaS-Vendoren.',
        'AICPA attestation report on Trust Services Criteria (security, availability, confidentiality, processing integrity, privacy) — common for SaaS vendors.',
    ),
    'whyItMatters': N(
        "Wer Cloud-Warehouses, BI-SaaS oder Reverse-ETL-Dienste einkauft, bekommt als Nachweis meist einen SOC-2-Bericht. Er ist die häufigste Währung im Vendor-Assessment.\n\nDer Wert liegt nicht im Logo, sondern im Kleingedruckten: Systembeschreibung, Prüfzeitraum, getestete Controls, gefundene Abweichungen und die Controls, die der Prüfer euch zuschreibt. Genau diese Teile werden am seltensten gelesen.",
        "If you buy cloud warehouses, BI SaaS or reverse-ETL services, the evidence you get is usually a SOC 2 report. It is the most common currency in vendor assessments.\n\nIts value is not the logo but the fine print: system description, examination period, tested controls, exceptions found and the controls the auditor assigns to you. Those parts are the ones least often read.",
    ),
    'appliesTo': N(
        "Dienstleister, die Kundendaten verarbeiten und ihren Kunden Sicherheitsnachweise liefern müssen — vor allem US-geprägte SaaS-Anbieter, zunehmend auch europäische.\n\nAls Kunde seid ihr indirekt betroffen: Ihr müsst Berichte lesen, Lücken bewerten und die euch zugewiesenen Controls tatsächlich umsetzen.",
        "Service providers that process customer data and need to give customers security assurance — primarily US-shaped SaaS vendors, increasingly European ones too.\n\nAs a customer you are indirectly affected: you have to read reports, assess gaps and actually implement the controls assigned to you.",
    ),
    'scopeNotes': [
        N(
            'SOC 2 ist eine Attestierung durch einen Prüfer, keine Zertifizierung mit Gütesiegel.',
            'SOC 2 is an attestation by an auditor, not a certification with a seal.',
        ),
        N(
            'Der Bericht gilt nur für das beschriebene System und den genannten Zeitraum.',
            'The report only covers the described system and the stated period.',
        ),
        N(
            'Die Kategorie Privacy in SOC 2 ist nicht deckungsgleich mit DSGVO-Pflichten.',
            'The SOC 2 privacy category is not congruent with GDPR obligations.',
        ),
        N(
            'Berichte sind meist vertraulich — plant Fristen für NDA und Beschaffung ein.',
            'Reports are usually confidential — plan lead time for NDA and procurement.',
        ),
    ],
    'keyRules': [
        R(
            'Trust Services Criteria wählen',
            'Trust Services Criteria selection',
            'Security ist immer dabei; Availability, Confidentiality, Processing Integrity und Privacy sind optional. Fehlt eine Kategorie, wurde sie nicht geprüft.',
            'Security is always included; availability, confidentiality, processing integrity and privacy are optional. If a category is missing, it was not examined.',
        ),
        R(
            'Type I und Type II unterscheiden',
            'Distinguish Type I and Type II',
            'Type I beurteilt nur die Ausgestaltung zu einem Stichtag, Type II die Wirksamkeit über einen Zeitraum. Für Vendor-Entscheidungen zählt praktisch nur Type II.',
            'Type I only assesses design at a point in time, Type II operating effectiveness over a period. For vendor decisions only Type II really counts.',
        ),
        R(
            'Systembeschreibung ist der Scope',
            'The system description is the scope',
            'Sie legt Dienste, Regionen, Komponenten und Grenzen fest. Wenn euer genutztes Produkt oder eure Region dort fehlt, hilft der Bericht wenig.',
            'It defines services, regions, components and boundaries. If the product you use or your region is missing, the report helps little.',
        ),
        R(
            'Abweichungen im Testteil lesen',
            'Read the exceptions in the testing section',
            'Der eigentliche Informationsgehalt steckt in den Testergebnissen und Ausnahmen — nicht im Prüfungsurteil auf Seite eins.',
            'The real information sits in the test results and exceptions — not in the opinion on page one.',
        ),
        R(
            'Complementary User Entity Controls',
            'Complementary user entity controls',
            'Der Bericht listet Controls, die ihr selbst umsetzen müsst (MFA, Rechtevergabe, Konfiguration). Ohne diese Hausaufgaben gilt die Zusicherung des Anbieters nicht.',
            'The report lists controls you must implement yourself (MFA, permission management, configuration). Without that homework the provider’s assurance does not hold.',
        ),
        R(
            'Subservice-Organisationen prüfen',
            'Check subservice organisations',
            'Unterauftragnehmer werden per Carve-out ausgeschlossen oder inklusiv geprüft. Bei Carve-out braucht ihr die Nachweise dieser Anbieter separat.',
            'Sub-service providers are either carved out or examined inclusively. With a carve-out you need those providers’ evidence separately.',
        ),
        R(
            'Prüfzeitraum und Lückenzeit',
            'Examination period and gap',
            'Deckt der Zeitraum eure Nutzung ab? Für die Zeit nach Berichtsende gibt es üblicherweise ein Bridge Letter.',
            'Does the period cover your usage? For the time after the report end date a bridge letter is the usual instrument.',
        ),
        R(
            'Bericht ist Momentaufnahme',
            'A report is a snapshot',
            'Jährliche Erneuerung nachhalten und Änderungen an Architektur oder Subprozessoren neu bewerten.',
            'Track annual renewal and re-assess changes in architecture or sub-processors.',
        ),
    ],
    'platform': [
        R(
            'Zugewiesene Controls umsetzen',
            'Implement the assigned controls',
            'CUEC-Liste in konkrete Aufgaben übersetzen: SSO/MFA erzwingen, Rollen begrenzen, Netzwerkregeln setzen, Logs aktivieren.',
            'Translate the CUEC list into concrete tasks: enforce SSO/MFA, limit roles, set network rules, enable logs.',
        ),
        R(
            'Vendor-Register mit Berichtsdaten',
            'Vendor register with report metadata',
            'Anbieter, Produkt, Berichtstyp, Zeitraum, Kategorien und offene Findings an einem Ort — sonst beginnt jede Prüfung von vorn.',
            'Provider, product, report type, period, categories and open findings in one place — otherwise every review starts from scratch.',
        ),
        R(
            'Kritikalität statt Gleichbehandlung',
            'Criticality instead of equal treatment',
            'Das Warehouse mit PII verdient eine tiefere Prüfung als ein Diagramm-Tool ohne Kundendaten.',
            'The warehouse holding PII deserves deeper review than a diagramming tool without customer data.',
        ),
        R(
            'Eigene Controls dagegen mappen',
            'Map your own controls against it',
            'Nutzt die Kriterien als Spiegel für eigene Access-, Logging- und Change-Prozesse, statt sie nur beim Anbieter zu prüfen.',
            'Use the criteria as a mirror for your own access, logging and change processes instead of only checking the provider.',
        ),
        R(
            'Cloud-Unterbau nicht vergessen',
            'Do not forget the cloud substrate',
            'Ein BI-SaaS läuft meist auf einem Hyperscaler; prüft, ob dessen Controls inklusiv oder per Carve-out behandelt sind.',
            'A BI SaaS usually runs on a hyperscaler; check whether its controls are inclusive or carved out.',
        ),
    ],
    'checklist': [
        R(
            'Aktueller Type-II-Bericht vorhanden?',
            'Current Type II report on file?',
            'Für jeden kritischen Datenanbieter, nicht nur für den größten.',
            'For every critical data provider, not only the biggest one.',
        ),
        R(
            'Zeitraum deckt die Nutzung ab?',
            'Period covers your usage?',
            'Inklusive Bridge Letter für die Lücke bis heute.',
            'Including a bridge letter for the gap up to today.',
        ),
        R(
            'Scope enthält Produkt und Region?',
            'Scope includes product and region?',
            'Systembeschreibung wirklich gelesen und mit dem eigenen Setup verglichen.',
            'System description actually read and compared with your own setup.',
        ),
        R(
            'Abweichungen bewertet?',
            'Exceptions assessed?',
            'Findings dokumentiert, Risiko akzeptiert oder kompensiert — mit Owner.',
            'Findings documented, risk accepted or compensated — with an owner.',
        ),
        R(
            'CUECs zugewiesen?',
            'CUECs assigned?',
            'Jede Kundenpflicht hat ein Team und einen Umsetzungsnachweis.',
            'Every customer obligation has a team and evidence of implementation.',
        ),
        R(
            'Subprozessoren abgedeckt?',
            'Sub-processors covered?',
            'Carve-out-Anbieter separat bewertet oder bewusst akzeptiert.',
            'Carved-out providers assessed separately or consciously accepted.',
        ),
    ],
    'pitfalls': [
        R(
            'SOC 2 als DSGVO-Nachweis',
            'SOC 2 as GDPR proof',
            'Security-Attestierung ersetzt keine Rechtsgrundlage, keine Transferprüfung und keine Löschfähigkeit.',
            'A security attestation replaces neither legal basis nor transfer assessment nor erasure capability.',
        ),
        R(
            'Type I für Type II gehalten',
            'Type I mistaken for Type II',
            'Ein Design-Nachweis zum Stichtag sagt nichts über den Betrieb über zwölf Monate.',
            'A point-in-time design assessment says nothing about operations over twelve months.',
        ),
        R(
            'Abgelaufener Bericht im Ordner',
            'Expired report in the folder',
            'Zwei Jahre alte Berichte ohne Bridge Letter sind im Audit wertlos.',
            'Two-year-old reports without a bridge letter are worthless in an audit.',
        ),
        R(
            'Carve-out ignoriert',
            'Carve-out ignored',
            'Der Bericht endet an der Anbietergrenze; die Infrastruktur darunter bleibt ungeprüft.',
            'The report stops at the provider boundary; the infrastructure beneath stays unexamined.',
        ),
        R(
            'CUECs nie gelesen',
            'CUECs never read',
            'Wenn der Anbieter MFA und Rechteverwaltung euch zuweist und niemand es tut, entsteht genau dort die Lücke.',
            'If the provider assigns MFA and permission management to you and nobody does it, that is exactly where the gap appears.',
        ),
    ],
    'sources': [
        S(
            'AICPA — SOC 2 (Audit & Assurance)',
            'AICPA — SOC 2 (audit & assurance)',
            'https://www.aicpa-cima.com/topic/audit-assurance/audit-and-assurance-greater-than-soc-2',
        ),
        S(
            'AICPA — SOC Suite of Services',
            'AICPA — SOC suite of services',
            'https://www.aicpa-cima.com/resources/landing/system-and-organization-controls-soc-suite-of-services',
        ),
    ],
    'playbooks': ['access-security-governance', 'host-vs-cloud'],
}

BSI_C5 = {
    'id': 'bsi-c5',
    'category': 'security',
    'region': 'de',
    'type': 'framework',
    'depth': 'full',
    'order': 60,
    'label': N('BSI C5', 'BSI C5'),
    'shortPurpose': N(
        'Cloud Computing Compliance Criteria Catalogue des BSI — Anforderungskatalog und Prüfstandard für Cloud-Anbieter, besonders relevant im DE-/Behördenkontext.',
        'BSI Cloud Computing Compliance Criteria Catalogue — requirements and attestation baseline for cloud providers, especially relevant in German/public-sector contexts.',
    ),
    'whyItMatters': N(
        "In deutschen Ausschreibungen und im öffentlichen Sektor ist C5 oft der entscheidende Nachweis für Cloud-Dienste — und für Datenplattformen der Bericht mit den konkretesten Aussagen zu Betrieb, Standort und Unterauftragnehmern.\n\nBesonders wertvoll sind die Umfeldparameter: Sie beschreiben Rechtsraum, Datenstandorte, Zugriffsmöglichkeiten und Subunternehmer. Wer eine Transferbewertung oder ein Hosting-Entscheidungspapier schreibt, findet hier belastbare Fakten statt Marketing.",
        "In German tenders and the public sector, C5 is often the decisive evidence for cloud services — and for data platforms the report with the most concrete statements about operations, location and sub-contractors.\n\nThe environment parameters are particularly valuable: they describe legal jurisdiction, data locations, access options and sub-contractors. Anyone writing a transfer assessment or a hosting decision paper finds solid facts here instead of marketing.",
    ),
    'appliesTo': N(
        "Cloud-Anbieter, die ihren Kunden einen belastbaren Sicherheitsnachweis liefern wollen — und Kunden im deutschen Markt, die ihn einfordern.\n\nFür Plattform-Teams ist C5 vor allem ein Einkaufs- und Architekturinstrument: Es hilft, Anbieter zu vergleichen und die eigenen Restpflichten aus der geteilten Verantwortung zu erkennen.",
        "Cloud providers that want to give customers solid security evidence — and customers in the German market who require it.\n\nFor platform teams C5 is mainly a procurement and architecture instrument: it helps compare providers and recognise your remaining duties under shared responsibility.",
    ),
    'scopeNotes': [
        N(
            'C5 ist ein Kriterienkatalog mit Prüfung nach ISAE 3000 — es gibt keine „C5-Zertifizierung“ mit Siegel.',
            'C5 is a criteria catalogue examined under ISAE 3000 — there is no “C5 certification” with a seal.',
        ),
        N(
            'Der Katalog richtet sich an Anbieter; Kundenpflichten stehen in den ergänzenden Kundenkriterien.',
            'The catalogue addresses providers; customer duties sit in the complementary customer criteria.',
        ),
        N(
            'C5 deckt Informationssicherheit ab, nicht den Datenschutz insgesamt.',
            'C5 covers information security, not data protection as a whole.',
        ),
        N(
            'Typ 1 betrachtet die Ausgestaltung, Typ 2 die Wirksamkeit über einen Zeitraum.',
            'Type 1 looks at design, Type 2 at effectiveness over a period.',
        ),
    ],
    'keyRules': [
        R(
            'Kriterienkatalog über Domänen',
            'Criteria catalogue across domains',
            'C5:2020 ordnet Anforderungen in Bereiche wie Organisation der Informationssicherheit, Identitäts- und Rechteverwaltung, Kryptografie, Betrieb, Beschaffung und Notfallmanagement.',
            'C5:2020 groups requirements into areas such as organisation of information security, identity and access management, cryptography, operations, procurement and business continuity.',
        ),
        R(
            'Basis- und Zusatzkriterien',
            'Basic and additional criteria',
            'Neben den Basiskriterien gibt es Zusatzkriterien für höheren Schutzbedarf. Prüfen, welche im Bericht wirklich adressiert sind.',
            'Alongside the basic criteria there are additional criteria for higher protection needs. Check which ones the report actually addresses.',
        ),
        R(
            'Prüfung nach ISAE 3000',
            'Examination under ISAE 3000',
            'Ein unabhängiger Wirtschaftsprüfer testet die Controls und dokumentiert Feststellungen — inhaltlich vergleichbar mit SOC 2, aber mit deutschem Anforderungsrahmen.',
            'An independent auditor tests the controls and documents findings — comparable to SOC 2 in nature, but with a German requirements frame.',
        ),
        R(
            'Umfeldparameter als Pflichtlektüre',
            'Environment parameters as required reading',
            'Rechtsraum, Gerichtsstand, Datenstandorte, Standorte des Betriebspersonals, Unterauftragnehmer und Offenlegungspflichten gegenüber Behörden werden offengelegt.',
            'Jurisdiction, place of venue, data locations, locations of operating staff, sub-contractors and disclosure duties towards authorities are disclosed.',
        ),
        R(
            'Ergänzende Kundenkriterien',
            'Complementary customer criteria',
            'Der Bericht benennt, was in der Verantwortung des Kunden bleibt: Konfiguration, Rechtevergabe, Verschlüsselung eigener Daten, Monitoring der eigenen Nutzung.',
            'The report states what remains the customer’s responsibility: configuration, permission management, encryption of your own data, monitoring of your own usage.',
        ),
        R(
            'Anschluss an ISO 27001 und Verwandte',
            'Alignment with ISO 27001 and relatives',
            'Viele Kriterien überschneiden sich mit ISO/IEC 27001, 27017 und 27018. Anbieter mit ISMS erreichen C5 leichter; Kunden können Nachweise mappen statt doppelt zu erheben.',
            'Many criteria overlap with ISO/IEC 27001, 27017 and 27018. Providers with an ISMS reach C5 more easily; customers can map evidence instead of collecting it twice.',
        ),
        R(
            'Scope pro Dienst und Region',
            'Scope per service and region',
            'Ein Bericht gilt für benannte Dienste in benannten Regionen. Neue Services eines Anbieters sind selten automatisch abgedeckt.',
            'A report covers named services in named regions. A provider’s new services are rarely covered automatically.',
        ),
        R(
            'Relevanz in regulierten Kontexten',
            'Relevance in regulated contexts',
            'Öffentliche Auftraggeber und regulierte Branchen nutzen C5 häufig als Mindestanforderung — auch als Baustein für NIS2- und DORA-Nachweise gegenüber Dritten.',
            'Public buyers and regulated industries often use C5 as a minimum requirement — also as a building block for NIS2 and DORA evidence about third parties.',
        ),
    ],
    'platform': [
        R(
            'Scope gegen die eigene Nutzung prüfen',
            'Check scope against your actual usage',
            'Ist der konkrete Warehouse-, Storage- oder BI-Dienst in der genutzten Region im Bericht enthalten?',
            'Is the specific warehouse, storage or BI service in the region you use included in the report?',
        ),
        R(
            'Umfeldparameter in Transferbewertungen',
            'Environment parameters in transfer assessments',
            'Angaben zu Betriebsstandorten, Support-Zugriff und Behördenanfragen direkt in TIA und Hosting-Entscheidung übernehmen.',
            'Feed statements on operating locations, support access and authority requests directly into your TIA and hosting decision.',
        ),
        R(
            'Kundenkriterien in Backlog überführen',
            'Turn customer criteria into backlog items',
            'Aus den ergänzenden Kundenkriterien konkrete Tickets machen: Rollenmodell, Netzwerkbeschränkungen, Key-Management, Log-Auswertung.',
            'Turn complementary customer criteria into concrete tickets: role model, network restrictions, key management, log evaluation.',
        ),
        R(
            'Anbietervergleich strukturieren',
            'Structure provider comparison',
            'C5-, ISO- und SOC-Nachweise in einer Matrix gegenüberstellen, statt einzelne Berichte isoliert zu lesen.',
            'Compare C5, ISO and SOC evidence in one matrix instead of reading individual reports in isolation.',
        ),
        R(
            'Evidence für deutsche Prüfer',
            'Evidence for German auditors',
            'C5-Berichte plus eigene Access- und Change-Nachweise sind im DE-Kontext eine gut akzeptierte Kombination.',
            'C5 reports plus your own access and change evidence are a well-accepted combination in the German context.',
        ),
    ],
    'checklist': [
        R(
            'Aktueller Typ-2-Bericht vorhanden?',
            'Current Type 2 report available?',
            'Mit Prüfzeitraum, der die eigene Nutzung abdeckt.',
            'With an examination period covering your usage.',
        ),
        R(
            'Umfeldparameter gelesen?',
            'Environment parameters read?',
            'Datenstandorte, Betriebsstandorte, Unterauftragnehmer und Rechtsraum notiert.',
            'Data locations, operating locations, sub-contractors and jurisdiction noted.',
        ),
        R(
            'Zusatzkriterien im Scope?',
            'Additional criteria in scope?',
            'Falls höherer Schutzbedarf besteht, prüfen, ob die Zusatzkriterien geprüft wurden.',
            'If protection needs are higher, check whether additional criteria were examined.',
        ),
        R(
            'Kundenkriterien zugewiesen?',
            'Customer criteria assigned?',
            'Jede Kundenpflicht hat ein Team, ein Ticket und einen Nachweis.',
            'Every customer duty has a team, a ticket and evidence.',
        ),
        R(
            'Feststellungen nachverfolgt?',
            'Findings tracked?',
            'Abweichungen im Bericht bewertet und mit Kompensationen oder Risikoakzeptanz dokumentiert.',
            'Exceptions in the report assessed and documented with compensations or risk acceptance.',
        ),
    ],
    'pitfalls': [
        R(
            '„C5-zertifiziert“ ohne Bericht',
            '“C5 certified” without a report',
            'Marketingaussagen ersetzen kein Testat — immer den Prüfbericht anfordern.',
            'Marketing claims do not replace an attestation — always request the actual report.',
        ),
        R(
            'Scope passt nicht zum Setup',
            'Scope does not match your setup',
            'Bericht für Dienst A in Region X, genutzt wird Dienst B in Region Y.',
            'Report for service A in region X while you use service B in region Y.',
        ),
        R(
            'Kundenkriterien übersehen',
            'Customer criteria overlooked',
            'Die geteilte Verantwortung wird stillschweigend beim Anbieter abgelegt — und bleibt real bei euch.',
            'Shared responsibility is quietly assigned to the provider — and in reality stays with you.',
        ),
        R(
            'C5 als Datenschutznachweis',
            'C5 as data protection proof',
            'Sicherheitsprüfung ist kein Ersatz für Rechtsgrundlage, Transferbewertung oder Löschkonzept.',
            'A security examination does not replace legal basis, transfer assessment or a deletion concept.',
        ),
    ],
    'sources': [
        S(
            'BSI — C5 (Kriterienkatalog Cloud Computing)',
            'BSI — C5 (Cloud Computing Compliance Criteria Catalogue)',
            'https://www.bsi.bund.de/EN/Themen/Unternehmen-und-Organisationen/Informationen-und-Empfehlungen/Empfehlungen-nach-Angriffszielen/Cloud-Computing/Kriterienkatalog-C5/kriterienkatalog-c5_node.html',
        ),
        S(
            'BSI — C5:2020 Kriterienkatalog (PDF)',
            'BSI — C5:2020 criteria catalogue (PDF)',
            'https://www.bsi.bund.de/SharedDocs/Downloads/EN/BSI/CloudComputing/ComplianceControlsCatalogue/2020/C5_2020.pdf?__blob=publicationFile&v=3',
        ),
    ],
    'playbooks': ['access-security-governance', 'host-vs-cloud', 'cloud-hosting'],
}

NIST_ZERO_TRUST = {
    'id': 'nist-zero-trust',
    'category': 'security',
    'region': 'us',
    'type': 'framework',
    'depth': 'full',
    'order': 70,
    'label': N('NIST Zero Trust (SP 800-207)', 'NIST Zero Trust (SP 800-207)'),
    'shortPurpose': N(
        'Architekturmodell: nie implizit vertrauen, immer verifizieren — Identität, Gerät, Session und Kontext statt Netzperimeter.',
        'Architecture model: never trust by default, always verify — identity, device, session and context instead of network perimeter.',
    ),
    'whyItMatters': N(
        "Datenplattformen haben keinen Perimeter mehr: BI aus dem Homeoffice, Notebooks auf Laptops, Pipelines in der Cloud, Service-Accounts überall.\n\nZero Trust liefert dafür eine nützliche Denkfigur — jeder Zugriff wird pro Session anhand von Identität, Kontext und Policy entschieden und protokolliert. Für Warehouses heißt das: feingranulare Policies, kurzlebige Credentials und Telemetrie, die tatsächlich ausgewertet wird.",
        "Data platforms no longer have a perimeter: BI from home, notebooks on laptops, pipelines in the cloud, service accounts everywhere.\n\nZero trust offers a useful mental model — every access is decided per session based on identity, context and policy, and it is logged. For warehouses that means fine-grained policies, short-lived credentials and telemetry that is actually evaluated.",
    ),
    'appliesTo': N(
        "Freiwilliges Architekturmodell ohne Zertifizierung — anwendbar auf jede Organisation, in US-Behördenkontexten faktisch Vorgabe.\n\nFür Datenteams ist es besonders anschlussfähig, weil Warehouses ohnehin Identitäten, Rollen, Policies und Query-Logs mitbringen. Der Schritt ist die konsequente Nutzung, nicht ein neues Produkt.",
        "A voluntary architecture model without certification — applicable to any organisation, and in US federal contexts effectively expected.\n\nIt fits data teams particularly well because warehouses already bring identities, roles, policies and query logs. The step is to use them consistently, not to buy a new product.",
    ),
    'scopeNotes': [
        N(
            'Zero Trust ist ein Zielbild und Weg, kein Produkt, das man einkauft.',
            'Zero trust is a target state and a journey, not a product you buy.',
        ),
        N(
            'SP 800-207 beschreibt Architekturprinzipien; SP 1800-35 liefert Umsetzungsbeispiele.',
            'SP 800-207 describes architecture principles; SP 1800-35 provides implementation examples.',
        ),
        N(
            'Es ersetzt keine Compliance-Pflichten — es hilft, deren Security-Anforderungen zu erfüllen.',
            'It does not replace compliance obligations — it helps meet their security requirements.',
        ),
        N(
            'Ohne Identitäts- und Asset-Inventar bleibt jede Zero-Trust-Initiative Theorie.',
            'Without an identity and asset inventory every zero-trust initiative stays theory.',
        ),
    ],
    'keyRules': [
        R(
            'Alle Daten und Dienste sind Ressourcen',
            'All data sources and services are resources',
            'Warehouse-Schemas, Dashboards, APIs, Orchestrator und Notebook-Umgebungen zählen einzeln — nicht als eine große „interne Zone“.',
            'Warehouse schemas, dashboards, APIs, orchestrator and notebook environments count individually — not as one large “internal zone”.',
            'SP 800-207, Tenet 1', 'SP 800-207, tenet 1',
        ),
        R(
            'Kommunikation immer absichern',
            'Secure all communication',
            'Verschlüsselung und Authentisierung gelten unabhängig vom Netzstandort. „Im internen Netz“ ist kein Sicherheitsattribut.',
            'Encryption and authentication apply regardless of network location. “On the internal network” is not a security attribute.',
            'Tenet 2', 'Tenet 2',
        ),
        R(
            'Zugriff pro Session',
            'Per-session access',
            'Rechte werden je Session gewährt, minimal und zeitlich begrenzt. Für Datenzugriffe heißt das kurzlebige Tokens statt Dauer-Credentials.',
            'Access is granted per session, minimally and time-bound. For data access that means short-lived tokens instead of permanent credentials.',
            'Tenet 3', 'Tenet 3',
        ),
        R(
            'Dynamische Policy',
            'Dynamic policy',
            'Entscheidungen berücksichtigen Identität, Gerätezustand, Sensitivität der Daten und Verhaltenssignale — nicht nur Gruppenmitgliedschaft.',
            'Decisions take identity, device posture, data sensitivity and behavioural signals into account — not just group membership.',
            'Tenet 4', 'Tenet 4',
        ),
        R(
            'Integrität der Assets überwachen',
            'Monitor asset integrity',
            'Kein Gerät und kein Service ist per se vertrauenswürdig; Zustand und Patchlevel fließen in die Entscheidung ein.',
            'No device and no service is inherently trusted; posture and patch level feed the decision.',
            'Tenet 5', 'Tenet 5',
        ),
        R(
            'Authentisierung und Autorisierung vor jedem Zugriff',
            'Authenticate and authorise before every access',
            'Dynamisch, wiederholt und protokolliert. Policy Decision Point und Policy Enforcement Point sind die tragenden Komponenten.',
            'Dynamically, repeatedly and logged. Policy decision point and policy enforcement point are the load-bearing components.',
            'Tenet 6', 'Tenet 6',
        ),
        R(
            'Telemetrie zur Verbesserung nutzen',
            'Use telemetry to improve',
            'Logs und Signale werden gesammelt, um Policies zu schärfen. Ohne Rückkopplung entsteht nur Datenhalde.',
            'Logs and signals are collected to sharpen policies. Without a feedback loop you only build a data dump.',
            'Tenet 7', 'Tenet 7',
        ),
        R(
            'Migration in Schritten',
            'Migrate in steps',
            'NIST beschreibt Zero Trust ausdrücklich als iterative Reise mit Koexistenz klassischer Perimeter — beginnt bei den sensibelsten Datenpfaden.',
            'NIST explicitly describes zero trust as an iterative journey coexisting with classic perimeters — start with the most sensitive data paths.',
            'SP 800-207, Kapitel 7', 'SP 800-207, section 7',
        ),
    ],
    'platform': [
        R(
            'Identitätsbasierter Datenzugriff',
            'Identity-based data access',
            'SSO/MFA für Warehouse, BI und Orchestrator; personenbezogene Identitäten statt geteilter technischer Nutzer.',
            'SSO/MFA for warehouse, BI and orchestrator; individual identities instead of shared technical users.',
        ),
        R(
            'Feingranulare Policies im Warehouse',
            'Fine-grained policies in the warehouse',
            'Row Access Policies, Column Masking und Objekt-Grants als Enforcement-Punkt — nicht Filter im Dashboard.',
            'Row access policies, column masking and object grants as the enforcement point — not filters in the dashboard.',
        ),
        R(
            'Kurzlebige Credentials',
            'Short-lived credentials',
            'Key-Pair-Rotation, OAuth-Token und Just-in-Time-Rechte statt dauerhafter Passwörter in Konfigurationsdateien.',
            'Key pair rotation, OAuth tokens and just-in-time rights instead of permanent passwords in config files.',
        ),
        R(
            'Segmentierung für Pipelines',
            'Segmentation for pipelines',
            'Orchestrator, Staging und Produktion getrennt, mit eigenen Rollen und Netzwerkregeln — ein kompromittierter Job soll nicht alles erreichen.',
            'Separate orchestrator, staging and production with their own roles and network rules — a compromised job should not reach everything.',
        ),
        R(
            'Telemetrie aus Query-Logs',
            'Telemetry from query logs',
            'Zugriffs- und Query-Historie in Monitoring speisen: ungewöhnliche Exports, neue Verbindungen, Rechteänderungen.',
            'Feed access and query history into monitoring: unusual exports, new connections, permission changes.',
        ),
        R(
            'Service-Accounts als erste Klasse',
            'Service accounts as first-class citizens',
            'Auch technische Identitäten brauchen Owner, Least Privilege, Rotation und Review — sie sind oft der größte blinde Fleck.',
            'Technical identities also need owners, least privilege, rotation and review — they are often the biggest blind spot.',
        ),
    ],
    'checklist': [
        R(
            'Inventar der Ressourcen und Identitäten?',
            'Inventory of resources and identities?',
            'Datasets, Dienste, Service-Accounts und Integrationen mit Owner erfasst.',
            'Datasets, services, service accounts and integrations captured with owners.',
        ),
        R(
            'MFA überall, auch für Admins?',
            'MFA everywhere, including admins?',
            'Keine Ausnahmen für Notfallzugänge ohne Break-Glass-Prozess.',
            'No exceptions for emergency access without a break-glass process.',
        ),
        R(
            'Standing Privileges reduziert?',
            'Standing privileges reduced?',
            'Dauerhafte Adminrechte durch befristete oder genehmigungspflichtige Rechte ersetzt.',
            'Permanent admin rights replaced by time-bound or approval-based rights.',
        ),
        R(
            'Policy-Entscheidungen protokolliert?',
            'Policy decisions logged?',
            'Zugriffsentscheidungen und Ablehnungen sind auswertbar, nicht nur erfolgreiche Logins.',
            'Access decisions and denials are analysable, not only successful logins.',
        ),
        R(
            'Telemetrie wird genutzt?',
            'Telemetry is used?',
            'Mindestens ein regelmäßiger Report oder Alert basiert auf Zugriffsdaten.',
            'At least one recurring report or alert is based on access data.',
        ),
    ],
    'pitfalls': [
        R(
            'Zero Trust als Produkt kaufen',
            'Buying zero trust as a product',
            'Kein Tool liefert das Modell; ohne Inventar, Policies und Prozesse bleibt es ein Label.',
            'No tool delivers the model; without inventory, policies and processes it stays a label.',
        ),
        R(
            'VPN mit neuem Namen',
            'VPN with a new name',
            'Netzzugang zu ersetzen ist ein Anfang, aber Datenzugriffe brauchen eigene, feingranulare Entscheidungen.',
            'Replacing network access is a start, but data access needs its own fine-grained decisions.',
        ),
        R(
            'Service-Accounts ausgenommen',
            'Service accounts exempted',
            'Technische Nutzer mit statischen Passwörtern und breiten Rechten heben das Modell praktisch auf.',
            'Technical users with static passwords and broad rights practically cancel the model.',
        ),
        R(
            'Telemetrie ohne Auswertung',
            'Telemetry without evaluation',
            'Logs zu sammeln erzeugt Kosten, aber keine Sicherheit, wenn niemand hinsieht.',
            'Collecting logs creates cost but no security if nobody looks at them.',
        ),
    ],
    'sources': [
        S(
            'NIST SP 800-207 — Zero Trust Architecture',
            'NIST SP 800-207 — Zero Trust Architecture',
            'https://csrc.nist.gov/pubs/sp/800/207/final',
        ),
        S(
            'NIST SP 1800-35 — Implementing a Zero Trust Architecture',
            'NIST SP 1800-35 — Implementing a Zero Trust Architecture',
            'https://csrc.nist.gov/pubs/sp/1800/35/final',
        ),
    ],
    'playbooks': ['access-security-governance', 'host-vs-cloud'],
}

EU_AI_ACT = {
    'id': 'eu-ai-act',
    'category': 'ai',
    'region': 'eu',
    'type': 'regulation',
    'depth': 'full',
    'order': 80,
    'label': N('EU AI Act', 'EU AI Act'),
    'shortPurpose': N(
        'EU-Verordnung für KI-Systeme — risikobasierte Pflichten von verbotenen Praktiken bis Hochrisiko- und Transparenzanforderungen.',
        'EU regulation for AI systems — risk-based duties from prohibited practices to high-risk and transparency obligations.',
    ),
    'whyItMatters': N(
        "Sobald Analytics-Teams Modelle für Scoring, Priorisierung, Textgenerierung oder Assistenten einsetzen, sind sie im Anwendungsbereich einer Produktregulierung — mit Dokumentations-, Daten- und Aufsichtspflichten.\n\nDer AI Act macht dabei genau das zum Thema, was in Datenplattformen ohnehin wehtut: Herkunft und Qualität der Trainingsdaten, Nachvollziehbarkeit, Logging, menschliche Aufsicht und klare Rollen. Wer Lineage und Data Quality im Griff hat, hat den halben Weg schon gemacht.",
        "As soon as analytics teams use models for scoring, prioritisation, text generation or assistants, they fall under a product regulation — with documentation, data and oversight duties.\n\nThe AI Act puts exactly those topics on the table that already hurt in data platforms: provenance and quality of training data, traceability, logging, human oversight and clear roles. If you have lineage and data quality under control, you are halfway there.",
    ),
    'appliesTo': N(
        "Anbieter, Betreiber, Importeure und Händler von KI-Systemen mit EU-Bezug — auch wenn das Modell aus einem Drittland stammt, sofern die Ausgabe in der EU genutzt wird.\n\nDie meisten Datenteams sind Betreiber (Deployer) fremder Systeme. Wer ein Modell jedoch wesentlich verändert, umbenennt oder unter eigenem Namen anbietet, kann selbst zum Anbieter werden — mit deutlich mehr Pflichten.",
        "Providers, deployers, importers and distributors of AI systems with an EU nexus — even if the model comes from a third country, as long as its output is used in the EU.\n\nMost data teams are deployers of someone else’s system. But if you substantially modify, rebrand or offer a model under your own name, you can become a provider yourself — with substantially more duties.",
    ),
    'scopeNotes': [
        N(
            'Die Pflichten gelten gestuft nach Risikoklasse — nicht jedes Modell im Warehouse ist Hochrisiko.',
            'Duties are tiered by risk class — not every model in the warehouse is high-risk.',
        ),
        N(
            'Der AI Act ersetzt die DSGVO nicht; für personenbezogene Trainings- und Eingabedaten gilt beides.',
            'The AI Act does not replace the GDPR; for personal training and input data both apply.',
        ),
        N(
            'Die Anwendung erfolgt zeitlich gestaffelt — Verbote und AI-Kompetenz früher, Hochrisikopflichten später.',
            'Application is staggered over time — prohibitions and AI literacy earlier, high-risk duties later.',
        ),
        N(
            'Rolle bestimmt Pflichtenumfang: Anbieter, Betreiber, Importeur oder Händler.',
            'Your role determines the extent of duties: provider, deployer, importer or distributor.',
        ),
    ],
    'keyRules': [
        R(
            'Risikobasierter Ansatz',
            'Risk-based approach',
            'Verbotene Praktiken, Hochrisiko-Systeme, Transparenzfälle und minimales Risiko werden unterschiedlich behandelt. Erste Aufgabe ist immer die Einordnung des Use-Cases.',
            'Prohibited practices, high-risk systems, transparency cases and minimal risk are treated differently. The first task is always classifying the use case.',
            'Art. 5, Art. 6, Anhang III', 'Art. 5, Art. 6, Annex III',
        ),
        R(
            'AI-Kompetenz im Team',
            'AI literacy in the team',
            'Organisationen müssen für ausreichende Kompetenz der Personen sorgen, die KI betreiben oder nutzen — inklusive Grenzen und typischer Fehlerbilder.',
            'Organisations must ensure sufficient competence of the people operating or using AI — including its limits and typical failure modes.',
            'Art. 4', 'Art. 4',
        ),
        R(
            'Risikomanagementsystem',
            'Risk management system',
            'Für Hochrisiko-Systeme ist ein kontinuierlicher Risikoprozess über den Lebenszyklus vorgesehen, nicht eine Prüfung vor dem Go-live.',
            'High-risk systems require a continuous risk process across the lifecycle, not a single check before go-live.',
            'Art. 9', 'Art. 9',
        ),
        R(
            'Daten und Data Governance',
            'Data and data governance',
            'Trainings-, Validierungs- und Testdaten müssen relevant, repräsentativ und so weit möglich fehlerfrei sein; Herkunft, Erhebung und Bias-Prüfung sind zu dokumentieren.',
            'Training, validation and test data must be relevant, representative and as far as possible error-free; provenance, collection and bias examination must be documented.',
            'Art. 10', 'Art. 10',
        ),
        R(
            'Technische Dokumentation',
            'Technical documentation',
            'Systembeschreibung, Architektur, Datenquellen, Metriken und Grenzen gehören in eine nachvollziehbare Dokumentation nach Anhang IV.',
            'System description, architecture, data sources, metrics and limitations belong in traceable documentation per Annex IV.',
            'Art. 11, Anhang IV', 'Art. 11, Annex IV',
        ),
        R(
            'Protokollierung',
            'Record-keeping',
            'Hochrisiko-Systeme müssen Ereignisse automatisch protokollieren, damit Betrieb und Vorfälle rückverfolgbar bleiben.',
            'High-risk systems must automatically log events so that operations and incidents stay traceable.',
            'Art. 12', 'Art. 12',
        ),
        R(
            'Menschliche Aufsicht',
            'Human oversight',
            'Die Aufsicht muss wirksam sein: Eingriffsmöglichkeit, verständliche Ausgaben und Bewusstsein für Automation Bias.',
            'Oversight must be effective: ability to intervene, intelligible outputs and awareness of automation bias.',
            'Art. 14', 'Art. 14',
        ),
        R(
            'Transparenz und GPAI-Pflichten',
            'Transparency and GPAI duties',
            'Chatbots, Emotionserkennung und synthetische Inhalte brauchen Kennzeichnung; Anbieter von Allzweckmodellen haben eigene Dokumentations- und Urheberrechtspflichten.',
            'Chatbots, emotion recognition and synthetic content need disclosure; providers of general-purpose models have their own documentation and copyright duties.',
            'Art. 50, Art. 53', 'Art. 50, Art. 53',
        ),
    ],
    'platform': [
        R(
            'Use-Case-Register mit Rolle und Risikoklasse',
            'Use-case register with role and risk class',
            'Jeder KI-Anwendungsfall bekommt Owner, Rolle (Anbieter/Betreiber), Risikoeinordnung und Datenquellen. Ohne Register ist keine Pflicht zuordenbar.',
            'Every AI use case gets an owner, role (provider/deployer), risk classification and data sources. Without a register no duty can be assigned.',
        ),
        R(
            'Trainings- und Eingabedaten mit Lineage',
            'Training and input data with lineage',
            'Herkunft, Filter, Stichproben und Qualitätsmetriken dokumentieren — idealerweise als Metadaten am Dataset, nicht in einer Präsentation.',
            'Document provenance, filters, sampling and quality metrics — ideally as metadata on the dataset, not in a slide deck.',
        ),
        R(
            'Protokollierung von Läufen und Prompts',
            'Logging of runs and prompts',
            'Eingaben, Ausgaben, Modellversion und Entscheidungskontext so protokollieren, dass Nachvollziehbarkeit möglich ist — und die Logs selbst datenschutzkonform bleiben.',
            'Log inputs, outputs, model version and decision context so that traceability is possible — while keeping the logs themselves privacy-compliant.',
        ),
        R(
            'PII vor dem Modell trennen',
            'Separate PII before the model',
            'Feature-Auswahl, Masking und Pseudonymisierung reduzieren Datenschutz- und Bias-Risiken gleichzeitig.',
            'Feature selection, masking and pseudonymisation reduce privacy and bias risk at the same time.',
        ),
        R(
            'Aufsicht und Eskalation im Prozess',
            'Oversight and escalation in the process',
            'Wer prüft Ausgaben, wer darf abschalten, wie wird eskaliert? Diese Rollen gehören in den Betriebsplan, nicht in ein Konzeptpapier.',
            'Who reviews outputs, who may switch it off, how is it escalated? These roles belong in the operating plan, not in a concept paper.',
        ),
        R(
            'Vendor- und GPAI-Dokumentation einsammeln',
            'Collect vendor and GPAI documentation',
            'Von Modell- und SaaS-Anbietern die Systemdokumentation, Nutzungsgrenzen und Trainingshinweise einfordern und aufbewahren.',
            'Request and retain system documentation, usage limits and training notes from model and SaaS providers.',
        ),
    ],
    'checklist': [
        R(
            'KI-Inventar vollständig?',
            'AI inventory complete?',
            'Inklusive Copilot-Funktionen in BI-Tools, Skripten mit Modell-API und Pilotprojekten.',
            'Including copilot features in BI tools, scripts calling model APIs and pilot projects.',
        ),
        R(
            'Rolle und Risikoklasse je Use-Case?',
            'Role and risk class per use case?',
            'Dokumentiert und mit Legal abgestimmt, besonders bei HR-, Kredit- und Zugangsentscheidungen.',
            'Documented and aligned with legal, especially for HR, credit and access decisions.',
        ),
        R(
            'Datenherkunft belegbar?',
            'Data provenance evidenced?',
            'Quellen, Rechte, Filter und Qualitätsprüfungen für Trainings- und Evaluierungsdaten nachvollziehbar.',
            'Sources, rights, filters and quality checks for training and evaluation data traceable.',
        ),
        R(
            'Logs vorhanden und aufbewahrt?',
            'Logs available and retained?',
            'Mit definierter Aufbewahrung und Zugriffsbeschränkung.',
            'With defined retention and restricted access.',
        ),
        R(
            'Menschliche Aufsicht definiert?',
            'Human oversight defined?',
            'Namentlich benannte Rollen, Eingriffswege und Abschaltkriterien.',
            'Named roles, intervention paths and shutdown criteria.',
        ),
        R(
            'Anbieterunterlagen eingesammelt?',
            'Provider documentation collected?',
            'Modelldokumentation, Nutzungsbedingungen und Änderungshinweise archiviert.',
            'Model documentation, terms of use and change notices archived.',
        ),
    ],
    'pitfalls': [
        R(
            '„Wir nutzen nur ein fremdes Modell“',
            '“We only use someone else’s model”',
            'Auch Betreiber haben Pflichten — und wer ein System umbenennt oder wesentlich ändert, wird schnell selbst Anbieter.',
            'Deployers have duties too — and rebranding or substantially modifying a system can quickly make you a provider.',
        ),
        R(
            'DPIA mit AI-Act-Konformität verwechselt',
            'DPIA confused with AI Act conformity',
            'Datenschutz-Folgenabschätzung und KI-Konformitätsanforderungen sind unterschiedliche Verfahren mit unterschiedlichen Inhalten.',
            'A data protection impact assessment and AI conformity requirements are different procedures with different content.',
        ),
        R(
            'Schatten-KI im BI-Tool',
            'Shadow AI in the BI tool',
            'Eingebaute Assistenzfunktionen und Browser-Plugins erzeugen KI-Nutzung, die in keinem Inventar auftaucht.',
            'Built-in assistant features and browser plugins create AI usage that appears in no inventory.',
        ),
        R(
            'Keine Protokollierung',
            'No logging',
            'Ohne Logs sind Vorfälle nicht rekonstruierbar und Aufsicht nicht belegbar.',
            'Without logs, incidents cannot be reconstructed and oversight cannot be evidenced.',
        ),
        R(
            'Trainingsdaten ohne Rechteklärung',
            'Training data without rights clarification',
            'Interne Dokumente, Kundendaten und Web-Inhalte haben unterschiedliche Nutzungsgrenzen — Herkunft klären, bevor trainiert wird.',
            'Internal documents, customer data and web content have different usage limits — clarify provenance before training.',
        ),
    ],
    'sources': [
        S(
            'Verordnung (EU) 2024/1689 — EUR-Lex',
            'Regulation (EU) 2024/1689 — EUR-Lex',
            'https://eur-lex.europa.eu/eli/reg/2024/1689/oj',
        ),
        S(
            'EU-Kommission — AI Act',
            'European Commission — AI Act',
            'https://digital-strategy.ec.europa.eu/en/policies/regulatory-framework-ai',
        ),
        S(
            'EU-Kommission — AI Office',
            'European Commission — AI Office',
            'https://digital-strategy.ec.europa.eu/en/policies/ai-office',
        ),
    ],
    'playbooks': ['ai-gov', 'ai-agents', 'pii-privacy-governance'],
}

NIST_AI_RMF = {
    'id': 'nist-ai-rmf',
    'category': 'ai',
    'region': 'us',
    'type': 'framework',
    'depth': 'full',
    'order': 90,
    'label': N('NIST AI RMF', 'NIST AI RMF'),
    'shortPurpose': N(
        'Freiwilliges Risikomanagement-Framework für KI: Govern, Map, Measure, Manage — praxisnah und international anschlussfähig.',
        'Voluntary AI risk management framework: Govern, Map, Measure, Manage — practical and internationally usable.',
    ),
    'whyItMatters': N(
        "Wer KI-Governance aufbauen will, braucht mehr als eine Richtlinie: einen Weg von der Absicht über die Messung bis zum Betrieb. Genau das liefert das AI RMF in vier Funktionen.\n\nFür Datenteams ist es das pragmatischere Gegenstück zum AI Act: freiwillig, ohne Zertifizierung, aber mit konkreten Fragen zu Kontext, Metriken, Tests und Überwachung. Es passt gut als Arbeitsstruktur, um AI-Act- oder ISO-42001-Anforderungen später zu bedienen.",
        "Building AI governance takes more than a policy: it needs a path from intent through measurement to operations. That is exactly what the AI RMF provides in four functions.\n\nFor data teams it is the more pragmatic counterpart to the AI Act: voluntary, without certification, but with concrete questions on context, metrics, testing and monitoring. It works well as a working structure to serve AI Act or ISO 42001 requirements later.",
    ),
    'appliesTo': N(
        "Freiwillig für alle Organisationen, die KI entwickeln, beschaffen oder betreiben — sektor- und technologieneutral formuliert.\n\nIn US-Kontexten wird es häufig als Referenz erwartet; international eignet es sich als gemeinsame Sprache zwischen Data-, Security- und Fachteams.",
        "Voluntary for any organisation that develops, procures or operates AI — written to be sector- and technology-neutral.\n\nIn US contexts it is frequently expected as a reference; internationally it works as a shared language between data, security and business teams.",
    ),
    'scopeNotes': [
        N(
            'Das Framework ist freiwillig und nicht zertifizierbar — es erzeugt keine Rechtskonformität.',
            'The framework is voluntary and not certifiable — it does not create legal compliance.',
        ),
        N(
            'Es adressiert sozio-technische Risiken, nicht nur Modellgenauigkeit.',
            'It addresses socio-technical risk, not only model accuracy.',
        ),
        N(
            'Der Generative-AI-Profile-Anhang ergänzt spezifische Risiken für Sprachmodelle.',
            'The generative AI profile supplements specific risks for language models.',
        ),
        N(
            'Ohne belastbare Datenqualität bleiben Messungen im Measure-Teil wenig aussagekräftig.',
            'Without solid data quality, measurements in the Measure function stay weak.',
        ),
    ],
    'keyRules': [
        R(
            'Govern — Rahmen und Verantwortung',
            'Govern — frame and accountability',
            'Richtlinien, Rollen, Ressourcen und Kultur festlegen. Diese Funktion wirkt in alle anderen hinein und ist der häufigste Schwachpunkt.',
            'Set policies, roles, resources and culture. This function feeds into all others and is the most common weak spot.',
            'AI RMF 1.0, Govern', 'AI RMF 1.0, Govern',
        ),
        R(
            'Map — Kontext und Zweck verstehen',
            'Map — understand context and purpose',
            'Einsatzzweck, Betroffene, Annahmen, Datenquellen und Grenzen erfassen. Viele KI-Fehler sind Kontextfehler, keine Modellfehler.',
            'Capture intended use, affected people, assumptions, data sources and limits. Many AI failures are context failures, not model failures.',
            'Map', 'Map',
        ),
        R(
            'Measure — Metriken und Tests',
            'Measure — metrics and testing',
            'Trustworthiness quantifizieren, wo möglich: Genauigkeit, Robustheit, Bias, Erklärbarkeit, Datenschutz. Test, Evaluation, Verification und Validation gehören dazu.',
            'Quantify trustworthiness where possible: accuracy, robustness, bias, explainability, privacy. Test, evaluation, verification and validation are part of it.',
            'Measure', 'Measure',
        ),
        R(
            'Manage — Priorisieren und betreiben',
            'Manage — prioritise and operate',
            'Risiken behandeln, Restrisiken akzeptieren, überwachen und auf Vorfälle reagieren — inklusive Abschalt- und Rückfallpfaden.',
            'Treat risks, accept residual risk, monitor and respond to incidents — including shutdown and fallback paths.',
            'Manage', 'Manage',
        ),
        R(
            'Merkmale vertrauenswürdiger KI',
            'Characteristics of trustworthy AI',
            'Valid und verlässlich, sicher, resilient, nachvollziehbar und transparent, erklärbar, datenschutzfördernd, fair mit gemanagtem Bias — als Prüfraster nutzbar.',
            'Valid and reliable, safe, secure and resilient, accountable and transparent, explainable, privacy-enhanced, fair with managed bias — usable as a review grid.',
            'AI RMF, Kapitel 3', 'AI RMF, section 3',
        ),
        R(
            'Lebenszyklus statt Projektphase',
            'Lifecycle instead of project phase',
            'Risiken verschieben sich von Design über Deployment zu Betrieb und Außerbetriebnahme. Bewertungen müssen wiederholt werden.',
            'Risks shift from design through deployment to operations and decommissioning. Assessments must be repeated.',
        ),
        R(
            'Playbook und Profile nutzen',
            'Use the playbook and profiles',
            'Das AI RMF Playbook liefert konkrete Vorschläge je Unterkategorie; das Generative AI Profile ergänzt GenAI-spezifische Risiken.',
            'The AI RMF Playbook offers concrete suggestions per subcategory; the Generative AI Profile adds GenAI-specific risks.',
            'NIST AI 600-1', 'NIST AI 600-1',
        ),
    ],
    'platform': [
        R(
            'Modell- und Use-Case-Register',
            'Model and use-case register',
            'Kontext, Zweck, Datenquellen, Owner und Version an einem Ort — die Map-Funktion braucht ein Inventar, kein Wiki-Fragment.',
            'Context, purpose, data sources, owner and version in one place — the Map function needs an inventory, not a wiki fragment.',
        ),
        R(
            'Evaluations-Harness statt Ad-hoc-Tests',
            'Evaluation harness instead of ad-hoc tests',
            'Wiederholbare Test-Sets, definierte Metriken und Schwellenwerte, die im Deployment-Prozess geprüft werden.',
            'Repeatable test sets, defined metrics and thresholds that are checked in the deployment process.',
        ),
        R(
            'Monitoring und Drift',
            'Monitoring and drift',
            'Eingabeverteilungen, Ausgabequalität und Nutzungsverhalten beobachten; Schwellenwerte für Nachtraining oder Abschaltung festlegen.',
            'Observe input distributions, output quality and usage behaviour; define thresholds for retraining or shutdown.',
        ),
        R(
            'Datenqualität als Voraussetzung',
            'Data quality as a precondition',
            'Tests auf Vollständigkeit, Aktualität und Konsistenz upstream sind Voraussetzung dafür, dass Modellmetriken überhaupt aussagekräftig sind.',
            'Upstream tests for completeness, timeliness and consistency are the precondition for model metrics to mean anything.',
        ),
        R(
            'Vorfall- und Rückfallpfade',
            'Incident and fallback paths',
            'Wer wird informiert, was wird abgeschaltet, welcher manuelle Prozess greift? Vorab beschreiben, nicht während des Vorfalls erfinden.',
            'Who gets informed, what gets switched off, which manual process takes over? Describe it in advance instead of inventing it during the incident.',
        ),
    ],
    'checklist': [
        R(
            'KI-Inventar mit Kontext?',
            'AI inventory with context?',
            'Zweck, Betroffene, Datenquellen und Annahmen je Use-Case dokumentiert.',
            'Purpose, affected people, data sources and assumptions documented per use case.',
        ),
        R(
            'Metriken und Schwellen definiert?',
            'Metrics and thresholds defined?',
            'Mit Zielwerten, die eine Freigabe blockieren können — nicht nur als Reporting.',
            'With target values that can block a release — not only for reporting.',
        ),
        R(
            'Bias- und Robustheitstests durchgeführt?',
            'Bias and robustness tests performed?',
            'Ergebnisse dokumentiert, inklusive Grenzen der Aussagekraft.',
            'Results documented, including the limits of what they show.',
        ),
        R(
            'Vorfallpfad geübt?',
            'Incident path rehearsed?',
            'Mindestens ein Durchlauf inklusive Abschaltung und Kommunikation.',
            'At least one dry run including shutdown and communication.',
        ),
        R(
            'Rollen und Eskalation benannt?',
            'Roles and escalation named?',
            'Owner, fachliche Aufsicht und Entscheidungsbefugnis eindeutig.',
            'Owner, business oversight and decision authority unambiguous.',
        ),
        R(
            'GenAI-Risiken betrachtet?',
            'GenAI risks considered?',
            'Halluzination, Datenabfluss, Prompt Injection und Urheberrechtsfragen bewertet.',
            'Hallucination, data leakage, prompt injection and copyright questions assessed.',
        ),
    ],
    'pitfalls': [
        R(
            'Framework als Checkliste',
            'Framework as a checklist',
            'Die Funktionen abzuhaken, ohne zu messen, erzeugt Dokumente statt Risikoreduktion.',
            'Ticking off the functions without measuring produces documents instead of risk reduction.',
        ),
        R(
            'Nur Genauigkeit gemessen',
            'Only accuracy measured',
            'Robustheit, Bias, Datenschutz und Erklärbarkeit entscheiden im Betrieb oft mehr als ein Prozentpunkt Accuracy.',
            'Robustness, bias, privacy and explainability often matter more in operations than a percentage point of accuracy.',
        ),
        R(
            'Kein Owner für Modelle',
            'No owner for models',
            'Ohne fachliche Verantwortung verwaisen Modelle und laufen weiter, obwohl der Kontext sich geändert hat.',
            'Without business ownership, models are orphaned and keep running although the context has changed.',
        ),
        R(
            'AI RMF als Rechtsnachweis',
            'AI RMF as legal evidence',
            'Es ist freiwillig — AI-Act-Pflichten oder DSGVO-Anforderungen werden damit nicht erfüllt.',
            'It is voluntary — it does not satisfy AI Act duties or GDPR requirements.',
        ),
    ],
    'sources': [
        S(
            'NIST — AI Risk Management Framework',
            'NIST — AI Risk Management Framework',
            'https://www.nist.gov/itl/ai-risk-management-framework',
        ),
        S(
            'NIST — AI RMF 1.0 (PDF)',
            'NIST — AI RMF 1.0 (PDF)',
            'https://nvlpubs.nist.gov/nistpubs/ai/NIST.AI.100-1.pdf',
        ),
        S(
            'NIST — Generative AI Profile (AI 600-1, PDF)',
            'NIST — Generative AI Profile (AI 600-1, PDF)',
            'https://nvlpubs.nist.gov/nistpubs/ai/NIST.AI.600-1.pdf',
        ),
    ],
    'playbooks': ['ai-gov', 'ai-agents'],
}

ISO_42001 = {
    'id': 'iso-42001',
    'category': 'ai',
    'region': 'intl',
    'type': 'standard',
    'depth': 'full',
    'order': 100,
    'label': N('ISO/IEC 42001', 'ISO/IEC 42001'),
    'shortPurpose': N(
        'Managementsystem-Standard für künstliche Intelligenz (AIMS) — analog zu ISO 27001, aber für AI-Governance.',
        'Management system standard for artificial intelligence (AIMS) — analogous to ISO 27001, but for AI governance.',
    ),
    'whyItMatters': N(
        "ISO/IEC 42001 überträgt die bekannte Managementsystem-Logik auf KI: Scope, Politik, Rollen, Risiko- und Auswirkungsbewertung, Lebenszyklus-Controls, Audits, Verbesserung.\n\nFür Organisationen mit ISO 27001 ist das die günstigste Route zu belastbarer AI-Governance: gleiche Struktur, gleiche Auditlogik, erweiterte Inhalte. Zertifizierbar zu sein hilft zusätzlich in Ausschreibungen und Vendor-Fragebögen.",
        "ISO/IEC 42001 transfers the familiar management system logic to AI: scope, policy, roles, risk and impact assessment, lifecycle controls, audits, improvement.\n\nFor organisations that already run ISO 27001, this is the cheapest route to durable AI governance: same structure, same audit logic, extended content. Being certifiable also helps in tenders and vendor questionnaires.",
    ),
    'appliesTo': N(
        "Organisationen, die KI-Systeme entwickeln, bereitstellen oder nutzen und dafür ein prüfbares Managementsystem wollen — unabhängig von Größe und Branche.\n\nBesonders sinnvoll, wenn KI-Nutzung über Experimente hinausgeht und mehrere Teams, Vendoren oder Kundenzusagen betroffen sind.",
        "Organisations that develop, provide or use AI systems and want an auditable management system for it — regardless of size or industry.\n\nParticularly useful once AI usage goes beyond experiments and involves several teams, vendors or customer commitments.",
    ),
    'scopeNotes': [
        N(
            '42001 ist ein Managementsystem, keine Prüfung einzelner Modelle.',
            '42001 is a management system, not an examination of individual models.',
        ),
        N(
            'Eine Zertifizierung belegt keine AI-Act-Konformität, kann aber viele Nachweise vorbereiten.',
            'Certification does not prove AI Act conformity but can prepare much of the evidence.',
        ),
        N(
            'Der Standard baut auf der harmonisierten Struktur auf und lässt sich mit ISO 27001 und 27701 integrieren.',
            'The standard uses the harmonised structure and integrates with ISO 27001 and 27701.',
        ),
        N(
            'Der Scope entscheidet: eingebettete KI in SaaS und BI-Tools gerne mitdenken.',
            'Scope decides: remember to include embedded AI in SaaS and BI tools.',
        ),
    ],
    'keyRules': [
        R(
            'AIMS mit klarem Scope',
            'AIMS with a clear scope',
            'Kontext, interessierte Parteien und Grenzen des KI-Managementsystems festlegen — inklusive der Frage, in welchen Rollen die Organisation auftritt.',
            'Define context, interested parties and the boundaries of the AI management system — including which roles the organisation takes on.',
            'Kapitel 4', 'Clause 4',
        ),
        R(
            'KI-Politik und Ziele',
            'AI policy and objectives',
            'Eine verbindliche Politik mit Zielen, Prinzipien und Verantwortlichkeiten — abgestimmt mit Sicherheits- und Datenschutzpolitik statt daneben.',
            'A binding policy with objectives, principles and responsibilities — aligned with security and privacy policy rather than parallel to it.',
            'Kapitel 5', 'Clause 5',
        ),
        R(
            'Risikobeurteilung und Behandlung',
            'Risk assessment and treatment',
            'KI-spezifische Risiken bewerten: Datenherkunft, Bias, Robustheit, Missbrauch, Automatisierungsgrad, Abhängigkeit von Anbietern.',
            'Assess AI-specific risks: data provenance, bias, robustness, misuse, degree of automation, provider dependency.',
            'Kapitel 6', 'Clause 6',
        ),
        R(
            'Auswirkungsbewertung für Betroffene',
            'Impact assessment for affected people',
            'Über klassische Organisationsrisiken hinaus verlangt der Standard die Betrachtung von Auswirkungen auf Personen und Gesellschaft.',
            'Beyond classic organisational risk, the standard requires considering impacts on individuals and society.',
        ),
        R(
            'Lebenszyklus-Controls',
            'Lifecycle controls',
            'Anforderungen für Design, Daten für KI, Verifikation und Validierung, Deployment, Betrieb und Außerbetriebnahme — inklusive Dokumentation.',
            'Requirements for design, data for AI, verification and validation, deployment, operation and decommissioning — including documentation.',
            'Annex A und B', 'Annexes A and B',
        ),
        R(
            'Daten für KI',
            'Data for AI',
            'Herkunft, Qualität, Repräsentativität, Kennzeichnung und Zugriff auf Trainings- und Evaluierungsdaten sind explizite Controls.',
            'Provenance, quality, representativeness, labelling and access to training and evaluation data are explicit controls.',
        ),
        R(
            'Dritte und Lieferanten',
            'Third parties and suppliers',
            'Modell-APIs, Datenlieferanten und Integratoren müssen bewertet und vertraglich gebunden werden — mit klarer Rollenverteilung.',
            'Model APIs, data suppliers and integrators must be assessed and contractually bound — with clear role allocation.',
        ),
        R(
            'Audit, Review, Verbesserung',
            'Audit, review, improvement',
            'Interne Audits, Managementbewertung und Korrekturmaßnahmen halten das System lebendig — dieselbe Mechanik wie in ISO 27001.',
            'Internal audits, management review and corrective actions keep the system alive — the same mechanics as in ISO 27001.',
            'Kapitel 9 und 10', 'Clauses 9 and 10',
        ),
    ],
    'platform': [
        R(
            'ISMS erweitern, nicht duplizieren',
            'Extend the ISMS, do not duplicate it',
            'Risikoprozess, Auditzyklus und Dokumentenlenkung mitnutzen und um KI-Themen ergänzen.',
            'Reuse the risk process, audit cycle and document control and extend them with AI topics.',
        ),
        R(
            'Modell- und Dataset-Inventar',
            'Model and dataset inventory',
            'Modelle, Versionen, Trainingsdaten, Metriken und Owner nachvollziehbar führen — die Grundlage für nahezu jedes Control.',
            'Track models, versions, training data, metrics and owners traceably — the basis for nearly every control.',
        ),
        R(
            'Daten-Controls in der Pipeline',
            'Data controls in the pipeline',
            'Provenance, Qualitätstests, Labeling-Regeln und Zugriffsbeschränkungen dort umsetzen, wo die Daten entstehen.',
            'Implement provenance, quality tests, labelling rules and access restrictions where the data is created.',
        ),
        R(
            'Change- und Release-Prozess für Modelle',
            'Change and release process for models',
            'Modellwechsel wie Softwarereleases behandeln: Test, Freigabe, Rollback, Kommunikation.',
            'Treat model changes like software releases: test, approval, rollback, communication.',
        ),
        R(
            'Monitoring mit Rückkopplung',
            'Monitoring with feedback',
            'Betriebsmetriken, Nutzerfeedback und Vorfälle fließen in die nächste Risikobewertung ein.',
            'Operating metrics, user feedback and incidents feed into the next risk assessment.',
        ),
        R(
            'Lieferantenbewertung für Modell-APIs',
            'Supplier assessment for model APIs',
            'Region, Retention, Trainingsnutzung, Änderungsankündigungen und Exit-Optionen bewerten und dokumentieren.',
            'Assess and document region, retention, training use, change notifications and exit options.',
        ),
    ],
    'checklist': [
        R(
            'Scope deckt reale KI-Nutzung ab?',
            'Does scope cover real AI usage?',
            'Inklusive eingebetteter Funktionen in BI, CRM und Support-Tools.',
            'Including embedded features in BI, CRM and support tools.',
        ),
        R(
            'Auswirkungsbewertungen durchgeführt?',
            'Impact assessments performed?',
            'Für Use-Cases mit Auswirkung auf Menschen, dokumentiert und datiert.',
            'For use cases affecting people, documented and dated.',
        ),
        R(
            'Datenherkunft dokumentiert?',
            'Data provenance documented?',
            'Quellen, Rechte, Labeling-Verfahren und Qualitätsprüfungen belegbar.',
            'Sources, rights, labelling procedures and quality checks evidenced.',
        ),
        R(
            'Modelländerungen kontrolliert?',
            'Model changes controlled?',
            'Versionierung, Freigabe und Rollback etabliert und benutzt.',
            'Versioning, approval and rollback established and used.',
        ),
        R(
            'Monitoring und Vorfallbehandlung aktiv?',
            'Monitoring and incident handling active?',
            'Mit Zuständigkeiten, Schwellenwerten und dokumentierten Fällen.',
            'With responsibilities, thresholds and documented cases.',
        ),
        R(
            'Lieferantenklauseln geprüft?',
            'Supplier clauses reviewed?',
            'KI-relevante Themen wie Trainingsnutzung und Änderungsankündigung enthalten.',
            'AI-relevant topics such as training use and change notification included.',
        ),
    ],
    'pitfalls': [
        R(
            'Zertifikat ohne Runtime-Controls',
            'Certificate without runtime controls',
            'Ein AIMS auf Papier ohne Inventar, Tests und Monitoring hält dem ersten Vorfall nicht stand.',
            'An AIMS on paper without inventory, tests and monitoring does not survive the first incident.',
        ),
        R(
            'Paralleles zweites Managementsystem',
            'A parallel second management system',
            'Getrennte Prozesse für Security und KI erzeugen doppelte Arbeit und widersprüchliche Aussagen.',
            'Separate processes for security and AI create duplicated work and contradictory statements.',
        ),
        R(
            'Eingebettete KI außerhalb des Scopes',
            'Embedded AI outside the scope',
            'Assistenzfunktionen in gekauften Tools sind KI-Nutzung — auch wenn niemand sie beschafft hat.',
            'Assistant features in purchased tools are AI usage — even if nobody procured them.',
        ),
        R(
            '42001 mit AI-Act-Konformität gleichgesetzt',
            '42001 equated with AI Act conformity',
            'Der Standard hilft strukturell, ersetzt aber keine gesetzliche Einordnung und keine Konformitätsbewertung.',
            'The standard helps structurally but replaces neither legal classification nor conformity assessment.',
        ),
    ],
    'sources': [
        S('ISO — ISO/IEC 42001 (Produktseite)', 'ISO — ISO/IEC 42001 (product page)', 'https://www.iso.org/standard/81230.html'),
        S(
            'ISO — ISO/IEC 23894 (KI-Risikomanagement)',
            'ISO — ISO/IEC 23894 (AI risk management)',
            'https://www.iso.org/standard/77304.html',
        ),
    ],
    'playbooks': ['ai-gov', 'ai-agents', 'access-security-governance'],
}

GOBD = {
    'id': 'gobd',
    'category': 'retention',
    'region': 'de',
    'type': 'regulation',
    'depth': 'full',
    'order': 110,
    'label': N('GoBD', 'GoBD'),
    'shortPurpose': N(
        'Grundsätze zur ordnungsmäßigen Führung und Aufbewahrung von Büchern, Aufzeichnungen und Unterlagen in elektronischer Form — DE-Steuer-/Handelskontext.',
        'Principles for proper keeping and retention of books, records and documents in electronic form — German tax/commercial context.',
    ),
    'whyItMatters': N(
        "Sobald steuerlich relevante Daten durch ELT-Pipelines, dbt-Modelle und BI-Extracts laufen, wird die Datenplattform Teil der Buchführungslandschaft — auch wenn niemand sie so genannt hat.\n\nDie GoBD verlangen Nachvollziehbarkeit, Unveränderbarkeit, maschinelle Auswertbarkeit und eine Verfahrensdokumentation. Für Plattform-Teams ist das die härteste Gegenkraft zur DSGVO-Löschpflicht: Beides gleichzeitig zu erfüllen erfordert bewusstes Lifecycle-Design statt Zufall.",
        "As soon as tax-relevant data flows through ELT pipelines, dbt models and BI extracts, the data platform becomes part of the accounting landscape — even if nobody called it that.\n\nGoBD require traceability, immutability, machine evaluability and process documentation. For platform teams this is the strongest counterforce to GDPR erasure duties: satisfying both at once requires deliberate lifecycle design instead of coincidence.",
    ),
    'appliesTo': N(
        "Buchführungs- und aufzeichnungspflichtige Unternehmen in Deutschland — unabhängig davon, ob die Systeme selbst betrieben oder als Cloud-Dienst genutzt werden.\n\nRelevant sind alle Systeme, in denen steuerlich relevante Daten entstehen, verarbeitet oder aufbewahrt werden: ERP, Kassensysteme, Rechnungseingang, aber eben auch Warehouse und Reporting, wenn dort steuerrelevante Auswertungen erzeugt werden.",
        "Businesses in Germany subject to bookkeeping and record-keeping duties — regardless of whether systems are self-operated or consumed as a cloud service.\n\nRelevant are all systems where tax-relevant data is created, processed or retained: ERP, point-of-sale, invoice intake — but also warehouse and reporting if tax-relevant evaluations are produced there.",
    ),
    'scopeNotes': [
        N(
            'Die GoBD sind eine Verwaltungsanweisung der Finanzverwaltung — verbindliche Fristen stehen in AO und HGB.',
            'GoBD is administrative guidance from the tax authorities — binding retention periods sit in the Fiscal Code and Commercial Code.',
        ),
        N(
            'Betroffen sind steuerlich relevante Daten, nicht jeder analytische Datensatz im Warehouse.',
            'Only tax-relevant data is in scope, not every analytical dataset in the warehouse.',
        ),
        N(
            'Aufbewahrungspflichten und DSGVO-Löschpflichten können kollidieren — das muss dokumentiert entschieden werden.',
            'Retention duties and GDPR erasure duties can collide — that must be decided in a documented way.',
        ),
        N(
            'Diese Seite ist Orientierung — steuerliche Bewertung gehört zu Steuerberatung und Fachabteilung.',
            'This page is orientation — tax assessment belongs with tax advisors and the finance function.',
        ),
    ],
    'keyRules': [
        R(
            'Nachvollziehbarkeit und Nachprüfbarkeit',
            'Traceability and verifiability',
            'Ein Dritter muss Geschäftsvorfälle und deren Verarbeitung nachvollziehen können — von der Quelle über Transformationen bis zur Auswertung.',
            'A third party must be able to follow business transactions and their processing — from source through transformations to the report.',
            'GoBD, Rz. 30 ff.', 'GoBD, para. 30 ff.',
        ),
        R(
            'Vollständigkeit, Richtigkeit, zeitgerechte Erfassung, Ordnung',
            'Completeness, accuracy, timely recording, order',
            'Die klassischen Ordnungsmäßigkeitsgrundsätze gelten auch für elektronische Prozesse — Lücken in Ladeläufen sind hier ein echtes Thema.',
            'The classic principles of proper accounting also apply to electronic processes — gaps in load runs are a genuine issue here.',
            '§ 146 AO, § 239 HGB', 'Section 146 AO, Section 239 HGB',
        ),
        R(
            'Unveränderbarkeit',
            'Immutability',
            'Buchungen und aufbewahrungspflichtige Daten dürfen nicht ohne Kennzeichnung verändert werden. Änderungen müssen protokolliert und der ursprüngliche Inhalt feststellbar bleiben.',
            'Postings and records subject to retention must not be changed without being marked. Changes must be logged and the original content must remain determinable.',
            '§ 146 Abs. 4 AO', 'Section 146(4) AO',
        ),
        R(
            'Verfahrensdokumentation',
            'Process documentation',
            'Beschreibung der Systeme, Datenflüsse, Kontrollen und Aufbewahrung — inklusive der eingesetzten Transformationen und Berechtigungen.',
            'Description of systems, data flows, controls and retention — including the transformations and permissions in use.',
            'GoBD, Rz. 151 ff.', 'GoBD, para. 151 ff.',
        ),
        R(
            'Aufbewahrungsfristen',
            'Retention periods',
            'Für Bücher, Aufzeichnungen und Buchungsbelege gelten regelmäßig zehn Jahre, für sonstige Unterlagen kürzere Fristen. Fristbeginn und Sonderfälle beachten.',
            'Books, records and posting documents are generally kept for ten years, other documents for shorter periods. Watch the start of the period and special cases.',
            '§ 147 AO', 'Section 147 AO',
        ),
        R(
            'Maschinelle Auswertbarkeit und Datenzugriff',
            'Machine evaluability and data access',
            'Die Finanzverwaltung kann unmittelbaren Zugriff, mittelbaren Zugriff oder Datenträgerüberlassung verlangen (Z1/Z2/Z3). Formate müssen auswertbar bleiben, nicht nur lesbar.',
            'Tax authorities may request direct access, indirect access or data transfer on a medium (Z1/Z2/Z3). Formats must remain evaluable, not merely readable.',
            '§ 147 Abs. 6 AO', 'Section 147(6) AO',
        ),
        R(
            'Format der eingehenden Belege',
            'Format of incoming documents',
            'Eingehende elektronische Belege sind grundsätzlich im Empfangsformat aufzubewahren; bei E-Rechnungen genügt regelmäßig der strukturierte Teil.',
            'Incoming electronic documents must generally be retained in the format received; for e-invoices the structured part is generally sufficient.',
            'GoBD i. d. F. vom 14.07.2025', 'GoBD as amended 14 July 2025',
        ),
        R(
            'Auslagerung und Cloud',
            'Outsourcing and cloud',
            'Elektronische Bücher dürfen unter Bedingungen im Ausland geführt werden; Verantwortung, Zugriff und Nachweisfähigkeit bleiben beim Steuerpflichtigen.',
            'Electronic books may be kept abroad under conditions; responsibility, access and the ability to provide evidence stay with the taxpayer.',
            '§ 146 Abs. 2a und 2b AO', 'Section 146(2a) and (2b) AO',
        ),
    ],
    'platform': [
        R(
            'Steuerrelevante Datasets kennzeichnen',
            'Label tax-relevant datasets',
            'Umsatz-, Rechnungs-, Kassen- und Stammdaten mit Metadaten markieren — nur so lassen sich Fristen und Sperren gezielt anwenden.',
            'Mark revenue, invoice, point-of-sale and master data with metadata — only then can periods and holds be applied selectively.',
        ),
        R(
            'Unveränderbarkeit technisch abbilden',
            'Implement immutability technically',
            'Append-only Layer, Time Travel, Snapshots und Änderungsprotokolle statt Überschreiben in Ladeprozessen.',
            'Append-only layers, time travel, snapshots and change logs instead of overwriting in load processes.',
        ),
        R(
            'Verfahrensdokumentation für Pipelines',
            'Process documentation for pipelines',
            'Quellsysteme, Ladelogik, dbt-Transformationen, Tests, Berechtigungen und Aufbewahrung beschreiben — versioniert im Repository, nicht als loses Word-Dokument.',
            'Describe source systems, load logic, dbt transformations, tests, permissions and retention — versioned in the repository, not as a loose Word file.',
        ),
        R(
            'Retention und Löschung gemeinsam denken',
            'Design retention and deletion together',
            'Aufbewahrungspflichten als Legal Hold modellieren, DSGVO-Löschung auf nicht-steuerrelevante Attribute begrenzen und Entscheidungen dokumentieren.',
            'Model retention duties as legal holds, restrict GDPR deletion to non-tax-relevant attributes and document the decisions.',
        ),
        R(
            'Reproduzierbare Auswertungen',
            'Reproducible reports',
            'Versionierte Logik, dokumentierte Kennzahldefinitionen und archivierte Ergebnisse, damit ein Bericht Jahre später erklärbar bleibt.',
            'Versioned logic, documented metric definitions and archived results so a report is still explainable years later.',
        ),
        R(
            'Exportfähigkeit für die Prüfung',
            'Export capability for audits',
            'Auswertbare Exporte in gängigen Formaten inklusive Struktur- und Feldbeschreibungen vorbereiten und testen.',
            'Prepare and test evaluable exports in common formats including structure and field descriptions.',
        ),
    ],
    'checklist': [
        R(
            'Steuerrelevante Daten inventarisiert?',
            'Tax-relevant data inventoried?',
            'Mit Owner, Quelle, Frist und Speicherort — inklusive Kopien in BI und Exports.',
            'With owner, source, retention period and storage location — including copies in BI and exports.',
        ),
        R(
            'Verfahrensdokumentation aktuell?',
            'Process documentation current?',
            'Enthält aktuelle Pipelines, Transformationen und Berechtigungen, nicht den Stand von vor drei Jahren.',
            'Contains current pipelines, transformations and permissions, not the state from three years ago.',
        ),
        R(
            'Änderungen protokolliert?',
            'Changes logged?',
            'Korrekturen, Reloads und Backfills sind nachvollziehbar und kennzeichnen den ursprünglichen Zustand.',
            'Corrections, reloads and backfills are traceable and identify the original state.',
        ),
        R(
            'Fristen im Lifecycle abgebildet?',
            'Retention periods implemented in the lifecycle?',
            'Archiv- und Löschjobs kennen die relevanten Fristen und Ausnahmen.',
            'Archive and deletion jobs know the relevant periods and exceptions.',
        ),
        R(
            'Löschprozess respektiert Legal Holds?',
            'Deletion process respects legal holds?',
            'DSGVO-Löschläufe greifen nicht in aufbewahrungspflichtige Bestände ein.',
            'GDPR deletion runs do not touch records under retention duties.',
        ),
        R(
            'Prüfungsexport getestet?',
            'Audit export tested?',
            'Mindestens ein Probeexport inklusive Beschreibung wurde erzeugt und geprüft.',
            'At least one sample export including documentation has been produced and reviewed.',
        ),
    ],
    'pitfalls': [
        R(
            'Warehouse als System of Record ohne Unveränderbarkeit',
            'Warehouse as system of record without immutability',
            'Overwrite-Ladepattern und truncate/insert zerstören Nachvollziehbarkeit, sobald steuerrelevante Daten betroffen sind.',
            'Overwrite load patterns and truncate/insert destroy traceability as soon as tax-relevant data is involved.',
        ),
        R(
            'Löschpflicht gegen Aufbewahrung ungeklärt',
            'Erasure duty versus retention unresolved',
            'Ohne dokumentierte Abwägung entstehen entweder unzulässige Löschungen oder unzulässige Speicherungen.',
            'Without a documented balancing you get either unlawful deletion or unlawful retention.',
        ),
        R(
            'Transformationen undokumentiert',
            'Transformations undocumented',
            'Wenn niemand erklären kann, wie eine Kennzahl entsteht, ist die Nachprüfbarkeit praktisch verloren.',
            'If nobody can explain how a metric is produced, verifiability is practically lost.',
        ),
        R(
            'Cloud-Standort ungeprüft',
            'Cloud location unchecked',
            'Aufbewahrung im Ausland ohne Prüfung der Voraussetzungen und ohne gesicherten Zugriff für die Prüfung.',
            'Retention abroad without checking the conditions and without secured access for audits.',
        ),
        R(
            'Nur Produktivsystem archiviert',
            'Only the production system archived',
            'Belege in Ticketsystemen, Mail-Anhängen und BI-Extracts fallen sonst aus der Aufbewahrung heraus.',
            'Otherwise documents in ticket systems, mail attachments and BI extracts drop out of retention.',
        ),
    ],
    'sources': [
        S(
            'BMF — GoBD, 2. Änderung vom 14.07.2025 (PDF)',
            'German Federal Ministry of Finance — GoBD, 2nd amendment of 14 July 2025 (PDF)',
            'https://www.bundesfinanzministerium.de/Content/DE/Downloads/BMF_Schreiben/Weitere_Steuerthemen/Abgabenordnung/2025-07-14-GoBD-2-aenderung.pdf?__blob=publicationFile&v=4',
        ),
        S(
            '§ 146 AO — Ordnungsvorschriften für Buchführung',
            'Section 146 AO — rules for bookkeeping',
            'https://www.gesetze-im-internet.de/ao_1977/__146.html',
        ),
        S(
            '§ 147 AO — Aufbewahrung und Datenzugriff',
            'Section 147 AO — retention and data access',
            'https://www.gesetze-im-internet.de/ao_1977/__147.html',
        ),
    ],
    'playbooks': ['data-lifecycle-retention', 'dsdr-governance', 'missing-pieces-data-lifecycle-retirement'],
}

NIS2 = {
    'id': 'nis2',
    'category': 'sector',
    'region': 'eu',
    'type': 'regulation',
    'depth': 'short',
    'order': 120,
    'label': N('NIS2', 'NIS2'),
    'shortPurpose': N(
        'EU-Richtlinie zur Cybersicherheit wesentlicher und wichtiger Einrichtungen — Risikomanagement, Meldepflichten, Lieferkette.',
        'EU directive on cybersecurity for essential and important entities — risk management, incident reporting, supply chain.',
    ),
    'whyItMatters': N(
        "NIS2 erweitert den Kreis der betroffenen Branchen deutlich und macht Cybersicherheit zur Leitungsaufgabe mit persönlicher Verantwortung.\n\nFür Datenplattformen sind vor allem drei Punkte relevant: Verfügbarkeit und Wiederherstellbarkeit, Sicherheit der Lieferkette inklusive Cloud-Diensten und ein Meldeprozess, der unter Zeitdruck funktioniert.",
        "NIS2 significantly widens the set of affected sectors and makes cybersecurity a management duty with personal accountability.\n\nFor data platforms three points matter most: availability and recoverability, supply chain security including cloud services, and a reporting process that works under time pressure.",
    ),
    'appliesTo': N(
        "Wesentliche und wichtige Einrichtungen in den Anhängen der Richtlinie — je nach Sektor, Größe und nationaler Umsetzung.\n\nDie Richtlinie wirkt über nationale Gesetze; die konkrete Betroffenheit und Registrierungspflicht ergibt sich aus dem jeweiligen Umsetzungsrecht.",
        "Essential and important entities listed in the directive’s annexes — depending on sector, size and national transposition.\n\nThe directive works through national law; actual applicability and registration duties follow from the respective transposition act.",
    ),
    'scopeNotes': [
        N(
            'NIS2 ist eine Richtlinie — maßgeblich ist das nationale Umsetzungsgesetz.',
            'NIS2 is a directive — the national transposition act is what governs.',
        ),
        N(
            'Betroffenheit hängt von Sektor und Größe ab und sollte dokumentiert festgestellt werden.',
            'Applicability depends on sector and size and should be determined in a documented way.',
        ),
        N(
            'Cybersicherheit nach NIS2 ersetzt keine Datenschutzpflichten aus der DSGVO.',
            'Cybersecurity under NIS2 does not replace data protection duties under the GDPR.',
        ),
    ],
    'keyRules': [
        R(
            'Risikomanagementmaßnahmen',
            'Risk management measures',
            'Gefordert ist ein Mindestkatalog: Risikoanalyse, Incident Handling, Business Continuity und Backup, Kryptografie, Zugriffskontrolle und Multi-Faktor-Authentisierung.',
            'A minimum catalogue is required: risk analysis, incident handling, business continuity and backup, cryptography, access control and multi-factor authentication.',
            'Art. 21', 'Art. 21',
        ),
        R(
            'Sicherheit der Lieferkette',
            'Supply chain security',
            'Sicherheit direkter Anbieter und Dienstleister ist Teil der eigenen Pflichten — für Datenteams also Cloud, SaaS und Managed Services.',
            'The security of direct suppliers and service providers is part of your own duties — for data teams that means cloud, SaaS and managed services.',
            'Art. 21 Abs. 2 lit. d', 'Art. 21(2)(d)',
        ),
        R(
            'Meldepflichten mit Fristen',
            'Reporting duties with deadlines',
            'Frühwarnung binnen 24 Stunden, Meldung binnen 72 Stunden und Abschlussbericht innerhalb eines Monats — gerechnet ab Kenntnis eines erheblichen Vorfalls.',
            'Early warning within 24 hours, notification within 72 hours and a final report within one month — counted from awareness of a significant incident.',
            'Art. 23', 'Art. 23',
        ),
        R(
            'Verantwortung der Leitung',
            'Management accountability',
            'Leitungsorgane müssen Maßnahmen genehmigen, überwachen und sich schulen lassen; Verstöße können persönliche Folgen haben.',
            'Management bodies must approve and oversee measures and undergo training; breaches can have personal consequences.',
            'Art. 20', 'Art. 20',
        ),
    ],
    'platform': [
        R(
            'Backup und Wiederherstellung beweisen',
            'Prove backup and restore',
            'Wiederherstellung von Warehouse, Metadaten und Orchestrierung testen — Restore-Zeit dokumentieren, nicht nur Backup-Jobs.',
            'Test restore of warehouse, metadata and orchestration — document restore time, not only backup jobs.',
        ),
        R(
            'Lieferantensicherheit bewerten',
            'Assess supplier security',
            'Cloud- und SaaS-Anbieter mit Nachweisen (ISO 27001, C5, SOC 2) und vertraglichen Pflichten einordnen.',
            'Classify cloud and SaaS providers with evidence (ISO 27001, C5, SOC 2) and contractual duties.',
        ),
        R(
            'Erkennung und Meldekette',
            'Detection and reporting chain',
            'Logging und Alerting so aufsetzen, dass ein Vorfall überhaupt innerhalb von 24 Stunden erkannt und eskaliert werden kann.',
            'Set up logging and alerting so an incident can actually be detected and escalated within 24 hours.',
        ),
    ],
    'checklist': [
        R(
            'Betroffenheit dokumentiert?',
            'Applicability documented?',
            'Sektor, Größe und nationale Umsetzung geprüft und schriftlich festgehalten.',
            'Sector, size and national transposition checked and recorded in writing.',
        ),
        R(
            'Meldeprozess geübt?',
            'Reporting process rehearsed?',
            'Rollen, Kontakte und Fristen sind bekannt und wurden mindestens einmal durchgespielt.',
            'Roles, contacts and deadlines are known and have been rehearsed at least once.',
        ),
        R(
            'Restore getestet?',
            'Restore tested?',
            'Mit dokumentiertem Ergebnis und realistischer Wiederherstellungszeit.',
            'With a documented result and a realistic recovery time.',
        ),
    ],
    'pitfalls': [
        R(
            '„Wir sind keine kritische Infrastruktur“',
            '“We are not critical infrastructure”',
            'NIS2 erfasst deutlich mehr Sektoren als die Vorgängerrichtlinie — Annahmen ohne Prüfung sind riskant.',
            'NIS2 covers far more sectors than its predecessor — assumptions without a check are risky.',
        ),
        R(
            'Lieferkette ausgeblendet',
            'Supply chain ignored',
            'Die Sicherheit von Cloud- und SaaS-Anbietern gehört zum eigenen Pflichtenkreis, nicht nur in deren AGB.',
            'The security of cloud and SaaS providers is part of your own duties, not just their terms.',
        ),
        R(
            'Meldefristen ungeübt',
            'Reporting deadlines unrehearsed',
            'Wer erst im Vorfall nach Zuständigkeiten sucht, verliert die ersten 24 Stunden.',
            'If you first look for responsibilities during the incident, you lose the first 24 hours.',
        ),
    ],
    'sources': [
        S(
            'Richtlinie (EU) 2022/2555 — EUR-Lex',
            'Directive (EU) 2022/2555 — EUR-Lex',
            'https://eur-lex.europa.eu/eli/dir/2022/2555/oj',
        ),
        S(
            'EU-Kommission — NIS2-Richtlinie',
            'European Commission — NIS2 directive',
            'https://digital-strategy.ec.europa.eu/en/policies/nis2-directive',
        ),
    ],
    'playbooks': ['access-security-governance', 'host-vs-cloud'],
}

DORA = {
    'id': 'dora',
    'category': 'sector',
    'region': 'eu',
    'type': 'regulation',
    'depth': 'short',
    'order': 130,
    'label': N('DORA', 'DORA'),
    'shortPurpose': N(
        'Digital Operational Resilience Act — EU-Regeln für IKT-Risiken im Finanzsektor (Banken, Versicherungen, kritische ICT-Provider).',
        'Digital Operational Resilience Act — EU rules for ICT risk in the financial sector (banks, insurers, critical ICT providers).',
    ),
    'whyItMatters': N(
        "DORA behandelt IT-Ausfälle als Aufsichtsthema: Wer im Finanzsektor Daten- und Reporting-Plattformen betreibt, muss Resilienz nachweisen — nicht nur Sicherheit.\n\nBesonders spürbar ist das beim Third-Party-Risiko: Cloud-Warehouse, BI-SaaS und Datenlieferanten landen im Informationsregister, brauchen Vertragsklauseln, Kritikalitätsbewertung und einen belastbaren Exit-Plan.",
        "DORA treats IT outages as a supervisory topic: if you run data and reporting platforms in the financial sector, you must evidence resilience — not only security.\n\nThird-party risk is where this bites hardest: cloud warehouse, BI SaaS and data suppliers end up in the register of information and need contractual clauses, criticality assessment and a credible exit plan.",
    ),
    'appliesTo': N(
        "Finanzunternehmen im Sinne der Verordnung — Banken, Versicherungen, Zahlungsdienstleister, Wertpapierfirmen und weitere — sowie benannte kritische IKT-Drittdienstleister.\n\nDienstleister ohne Finanzlizenz spüren DORA indirekt: über Verträge, Auskunftspflichten und Prüfrechte ihrer Kunden.",
        "Financial entities as defined by the regulation — banks, insurers, payment providers, investment firms and others — plus designated critical ICT third-party providers.\n\nService providers without a financial licence feel DORA indirectly: through contracts, information duties and audit rights of their customers.",
    ),
    'scopeNotes': [
        N(
            'DORA gilt unmittelbar als Verordnung und wird durch technische Standards konkretisiert.',
            'DORA applies directly as a regulation and is specified further by technical standards.',
        ),
        N(
            'Es geht um operationelle Resilienz insgesamt, nicht nur um Informationssicherheit.',
            'It is about operational resilience overall, not only information security.',
        ),
        N(
            'Auch interne Analytics-Plattformen können relevant sein, wenn sie regulatorische Berichte stützen.',
            'Internal analytics platforms can also be relevant if they support regulatory reporting.',
        ),
    ],
    'keyRules': [
        R(
            'IKT-Risikomanagementrahmen',
            'ICT risk management framework',
            'Governance, Strategie, Schutzmaßnahmen, Erkennung, Reaktion und Wiederherstellung sind als zusammenhängender Rahmen zu betreiben — mit Verantwortung im Leitungsorgan.',
            'Governance, strategy, protection, detection, response and recovery must run as one coherent framework — with accountability in the management body.',
            'Art. 5–15', 'Arts. 5–15',
        ),
        R(
            'Vorfallmanagement und Meldung',
            'Incident management and reporting',
            'IKT-Vorfälle müssen klassifiziert und schwerwiegende Vorfälle den Aufsichtsbehörden in gestuften Fristen gemeldet werden.',
            'ICT incidents must be classified, and major incidents reported to supervisors within staged deadlines.',
            'Art. 17–23', 'Arts. 17–23',
        ),
        R(
            'Resilienztests',
            'Resilience testing',
            'Regelmäßige Tests des Resilienzprogramms, für bestimmte Institute inklusive bedrohungsgeleiteter Penetrationstests.',
            'Regular testing of the resilience programme, for certain entities including threat-led penetration testing.',
            'Art. 24–27', 'Arts. 24–27',
        ),
        R(
            'Drittparteienrisiko und Informationsregister',
            'Third-party risk and register of information',
            'Alle IKT-Dienstleistungen sind zu registrieren, kritische Funktionen zu kennzeichnen und Verträge müssen Mindestinhalte wie Prüfrechte, Weiterverlagerung und Kündigungsrechte abdecken.',
            'All ICT services must be registered, critical functions flagged, and contracts must cover minimum content such as audit rights, sub-outsourcing and termination rights.',
            'Art. 28–30', 'Arts. 28–30',
        ),
    ],
    'platform': [
        R(
            'Informationsregister für Datendienste',
            'Register of information for data services',
            'Warehouse, ETL-SaaS, BI-Plattform, Datenlieferanten und deren Unterauftragnehmer erfassen — mit Funktion, Kritikalität und Standort.',
            'Capture warehouse, ETL SaaS, BI platform, data suppliers and their sub-contractors — with function, criticality and location.',
        ),
        R(
            'Exit- und Konzentrationsrisiko',
            'Exit and concentration risk',
            'Für kritische Dienste einen realistischen Ausstiegspfad beschreiben: Datenexport, Formate, Alternativanbieter, Aufwand und Zeitbedarf.',
            'Describe a realistic exit path for critical services: data export, formats, alternative providers, effort and time needed.',
        ),
        R(
            'Wiederherstellung von Reporting-Ketten',
            'Recovery of reporting chains',
            'Nicht nur Datenbanken, sondern die gesamte Kette bis zum regulatorischen Bericht testen — inklusive Metadaten und Berechtigungen.',
            'Test not only databases but the entire chain up to the regulatory report — including metadata and permissions.',
        ),
    ],
    'checklist': [
        R(
            'Register vollständig?',
            'Register complete?',
            'Alle IKT-Dienstleister der Datenplattform inklusive Weiterverlagerung erfasst.',
            'All ICT providers of the data platform captured, including sub-outsourcing.',
        ),
        R(
            'Kritikalität und Exit bewertet?',
            'Criticality and exit assessed?',
            'Für Dienste, die kritische oder wichtige Funktionen stützen, liegt ein dokumentierter Plan vor.',
            'For services supporting critical or important functions a documented plan exists.',
        ),
        R(
            'Vorfallklassifizierung einsatzbereit?',
            'Incident classification ready to use?',
            'Schwellenwerte, Rollen und Meldewege sind definiert und bekannt.',
            'Thresholds, roles and reporting channels are defined and known.',
        ),
    ],
    'pitfalls': [
        R(
            'DORA als reines Security-Thema',
            'DORA seen as a pure security topic',
            'Resilienz umfasst Verfügbarkeit, Wiederherstellung, Tests und Anbietersteuerung — nicht nur Schutzmaßnahmen.',
            'Resilience covers availability, recovery, testing and provider management — not only protective measures.',
        ),
        R(
            'Register ohne Unterauftragnehmer',
            'Register without sub-contractors',
            'Die Kette hinter dem BI-SaaS bleibt unsichtbar und damit unbewertet.',
            'The chain behind the BI SaaS stays invisible and therefore unassessed.',
        ),
        R(
            'Exit-Plan nur auf Papier',
            'Exit plan on paper only',
            'Ohne getesteten Datenexport und geklärte Formate ist ein Ausstieg im Ernstfall nicht durchführbar.',
            'Without tested data export and clarified formats, an exit is not feasible when it matters.',
        ),
    ],
    'sources': [
        S(
            'Verordnung (EU) 2022/2554 — EUR-Lex',
            'Regulation (EU) 2022/2554 — EUR-Lex',
            'https://eur-lex.europa.eu/eli/reg/2022/2554/oj',
        ),
        S(
            'ESMA — Digital Operational Resilience Act (DORA)',
            'ESMA — Digital Operational Resilience Act (DORA)',
            'https://www.esma.europa.eu/esmas-activities/digital-finance-and-innovation/digital-operational-resilience-act-dora',
        ),
    ],
    'playbooks': ['host-vs-cloud', 'access-security-governance', 'cloud-hosting'],
}

NEW_ITEMS = [
    INTERNATIONAL_TRANSFERS,
    ISO_27001,
    SOC_2,
    BSI_C5,
    NIST_ZERO_TRUST,
    EU_AI_ACT,
    NIST_AI_RMF,
    ISO_42001,
    GOBD,
    NIS2,
    DORA,
]


# --- validation --------------------------------------------------------------

def validate(items: list) -> list:
    errors = []
    seen_ids = set()
    seen_orders = set()

    for index, data in enumerate(items):
        label = data.get('id') or f'index {index}'

        for key in REQUIRED_KEYS:
            if key not in data:
                errors.append(f'{label}: missing key "{key}"')

        if data.get('id') in seen_ids:
            errors.append(f'{label}: duplicate id')
        seen_ids.add(data.get('id'))

        if data.get('order') in seen_orders:
            errors.append(f'{label}: duplicate order {data.get("order")}')
        seen_orders.add(data.get('order'))

        if data.get('depth') not in ('full', 'short'):
            errors.append(f'{label}: depth must be full or short')

        for key in ('label', 'shortPurpose', 'whyItMatters', 'appliesTo'):
            pair = data.get(key)
            if not isinstance(pair, dict) or not pair.get('de') or not pair.get('en'):
                errors.append(f'{label}: {key} needs non-empty de and en')

        for key in ('scopeNotes',):
            notes = data.get(key) or []
            if len(notes) < 3:
                errors.append(f'{label}: {key} needs at least 3 entries')
            for note in notes:
                if not note.get('de') or not note.get('en'):
                    errors.append(f'{label}: {key} entry missing de/en')

        for key in ('keyRules', 'platform', 'checklist', 'pitfalls'):
            cards = data.get(key) or []
            if len(cards) < 3:
                errors.append(f'{label}: {key} needs at least 3 cards')
            for card in cards:
                for part in ('title', 'detail'):
                    value = card.get(part) or {}
                    if not value.get('de') or not value.get('en'):
                        errors.append(f'{label}: {key} card missing {part} de/en')
                if 'ref' in card:
                    ref = card['ref'] or {}
                    if not ref.get('de') or not ref.get('en'):
                        errors.append(f'{label}: {key} card has incomplete ref')

        sources = data.get('sources') or []
        if not sources:
            errors.append(f'{label}: needs at least one source')
        for source in sources:
            if not source.get('de') or not source.get('en'):
                errors.append(f'{label}: source missing de/en label')
            href = source.get('href') or ''
            if not href.startswith('https://'):
                errors.append(f'{label}: source href must be https ({href})')

        playbooks = data.get('playbooks') or []
        if not playbooks:
            errors.append(f'{label}: needs at least one related playbook')
        for slug in playbooks:
            if not isinstance(slug, str) or not slug:
                errors.append(f'{label}: invalid playbook slug {slug!r}')

    return errors


# --- main --------------------------------------------------------------------

def main() -> int:
    with PICKLE_PATH.open('rb') as handle:
        items = list(pickle.load(handle))

    items.extend(NEW_ITEMS)
    items.sort(key=lambda data: int(data['order']))

    errors = validate(items)
    if errors:
        print('Validation failed:')
        for error in errors:
            print(f'  - {error}')
        return 1

    body = ''.join(item(data) for data in items)
    php = (
        "<?php\n\n"
        "/**\n"
        " * Compliance Hub framework items (detail content).\n"
        " * Learning and orientation only — not legal advice.\n"
        " */\n"
        "return [\n"
        f"{body}"
        "];\n"
    )

    OUT_PATH.write_text(php, encoding='utf-8')

    print(f'Wrote {OUT_PATH} with {len(items)} items')
    for data in items:
        print(
            f"  {data['order']:>4}  {data['id']:<26} {data['depth']:<5} "
            f"rules={len(data['keyRules'])} platform={len(data['platform'])} "
            f"checklist={len(data['checklist'])} pitfalls={len(data['pitfalls'])} "
            f"sources={len(data['sources'])} playbooks={len(data['playbooks'])}"
        )

    if len(items) != 13:
        print(f'Expected 13 items, got {len(items)}')
        return 1

    return 0


if __name__ == '__main__':
    sys.exit(main())
