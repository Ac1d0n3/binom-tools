# Phase E — Discussion (nur DB-Mode, zuletzt)

Stand: 2026-07-28  
Status: bewusst später  
Zurück: [Phase D](phase-d-reichweite.de.md) · Index: [index.de.md](index.de.md)

## Ziel

Optionaler Diskussionsbereich für eingeloggte Nutzer — nur sinnvoll mit geteiltem Server-State. **Kein Local-Storage-Mode.**

## Voraussetzung (Gate)

Phase E starten erst wenn:

- [ ] Phasen A–D „Done when“ weitgehend erfüllt
- [ ] Production läuft mit `BINOM_TOOLS_STORAGE_DRIVER=mysql` (oder klarer Cutover-Plan)
- [ ] Accounts/Login stabil (Registrierung, Approval, Sessions)
- [ ] Moderations-Kapazität geklärt (wer löscht/lockt)

Wenn File/Local-only: **Phase E nicht bauen.**

---

## Done when

- [ ] Discussion nur aktiv bei MySQL-Store
- [ ] Login Pflicht zum Schreiben
- [ ] Threads an bestehende Inhalte gebunden (Playbook / Tool / Topic)
- [ ] Moderation + Report-Flow vorhanden
- [ ] Öffentliche Indexierung klar geregelt (`noindex` oder stark kuratiert)
- [ ] Kein LocalStorage als Source of Truth für Posts

---

## E1 — Produktregeln

- [ ] Scope: Fragen zu Governance/BI an konkrete Seiten hängen — kein generisches Social Network
- [ ] Rollen: Leser (öffentlich oder login), Author (login), Moderator/Admin
- [ ] Inhalte: Text, optional Markdown-Subset, keine Datei-Uploads in v1
- [ ] Keine anonymen Posts
- [ ] Disclaimer: keine Rechtsberatung; Community ≠ offizielle Auskunft

---

## E2 — Technik (Skizze)

- [ ] Feature-Flag z. B. `BINOM_TOOLS_DISCUSSION_ENABLED` + Guard: nur wenn Storage = `mysql`
- [ ] Tabellen: threads, posts, reports (Namen finalisieren)
- [ ] Kein File-Store-Adapter für Discussion (explizit ablehnen / 404)
- [ ] API oder Controller unter Mega-Module (`modules/discussion/` o. ä.) — kein Scatter in `resources/*/domains/`
- [ ] Soft-delete / Lock für Moderation
- [ ] Rate limiting

Dual-Store-Hinweis:

- Rest der Plattform darf File bleiben; Discussion ist MySQL-only Feature.
- Oder: gesamter Runtime-Store muss MySQL sein — Entscheidung vor Implementierung treffen und hier dokumentieren:

_Entscheidung Storage:_

> …

---

## E3 — UX

- [ ] „Diskussion“-Tab oder Abschnitt auf Playbook/Tool-Show
- [ ] Liste Threads + Detail + Reply
- [ ] Login-CTA wenn Gast
- [ ] Leerzustand: Link zu Issues/GitHub bis Community wächst
- [ ] Kein neuer Sidebar-Hub ohne Absprache — bevorzugt kontextuell an Content

---

## E4 — SEO & Trust

- [ ] Default: Discussion-URLs `noindex,follow` oder ganz hinter Login
- [ ] Nicht in Sitemap, bis kuratierte „beste Antworten“ existieren
- [ ] Spam-/Abuse-Report
- [ ] Impressum/Disclaimer-Links in Discussion-UI

---

## E5 — Launch

- [ ] Soft-Launch (wenige Threads vorbefüllt / Seed-Fragen)
- [ ] Moderations-Checkliste
- [ ] Nach 2 Wochen: behalten, einschränken oder abschalten

---

## Explizit nicht

- LocalStorage / IndexedDB als Discussion-Backend
- Gast-Posting ohne Account
- Redesign der gesamten Hub-IA nur für Community

## Notizen

_Flag-Namen, Schema, Moderations-Owner:_
