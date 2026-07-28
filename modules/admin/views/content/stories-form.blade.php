@extends('admin::layouts.shell', ['mainClass' => 'tools-shell__main--admin-md'])

@section('title', ($isNew ? 'New story' : 'Edit story') . ' — Admin — ' . config('app.name'))

@section('admin_content')
    @php
        $imageCount = count($images ?? []);
        $railUid = 'admin-story-rail-'.preg_replace('/[^a-z0-9-]+/i', '-', (string) ($slug ?: 'new'));
    @endphp
    <div
        class="tools-content tools-content--wide sp-app admin-hub admin-hub--md-editor"
        data-admin-md-editor
        data-admin-images-rail
    >
        <div class="admin-hub__md-top">
            <a class="tools-btn" href="{{ locale_route('admin.stories.index') }}">Back</a>
            <button
                type="button"
                class="tools-btn"
                data-admin-images-toggle
                aria-expanded="true"
                aria-controls="admin-story-images-rail"
            >
                <span data-admin-images-toggle-hide>Hide side panel</span>
                <span data-admin-images-toggle-show hidden>Show side panel</span>
            </button>
            @if (session('flashDetail'))
                <p class="admin-hub__meta admin-hub__md-flash">{{ session('flashDetail') }}</p>
            @endif
        </div>

        <div class="admin-hub__md-workspace">
            <div class="admin-hub__md-main">
                @if ($isNew)
                    <div class="admin-hub__draft-bar" data-admin-story-draft>
                        <form
                            method="get"
                            action="{{ locale_route('admin.stories.create') }}"
                            class="admin-hub__draft-form"
                        >
                            <fieldset class="admin-hub__draft-fieldset">
                                <legend class="admin-hub__draft-legend">Template</legend>
                                <label class="admin-hub__draft-choice">
                                    <input
                                        type="radio"
                                        name="template"
                                        value="single"
                                        @checked(($draftTemplate ?? 'single') === 'single')
                                        data-admin-draft-template
                                    >
                                    Single story
                                </label>
                                <label class="admin-hub__draft-choice">
                                    <input
                                        type="radio"
                                        name="template"
                                        value="series"
                                        @checked(($draftTemplate ?? '') === 'series')
                                        data-admin-draft-template
                                    >
                                    Series episode
                                </label>
                            </fieldset>

                            <div
                                class="admin-hub__field admin-hub__field--inline admin-hub__draft-series"
                                data-admin-draft-series-wrap
                                @if (($draftTemplate ?? 'single') !== 'series') hidden @endif
                            >
                                <label for="draft-series">Series</label>
                                <select id="draft-series" name="series">
                                    <option value="">New series…</option>
                                    @foreach ($seriesOptions ?? [] as $series)
                                        <option
                                            value="{{ $series['id'] }}"
                                            @selected(($draftSeriesId ?? '') === $series['id'])
                                        >
                                            {{ $series['title'] }} ({{ $series['parts'] }} · next {{ $series['nextPart'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="tools-btn tools-btn--small">Apply template</button>
                        </form>

                        <p class="admin-hub__meta admin-hub__draft-hint">
                            @if (($draftTemplate ?? '') === 'series')
                                Prefill: {{ $draftSeriesLabel ?? 'New series' }} — fill title/description, then write the body.
                            @else
                                Prefill: single story header — fill title, description, category, tags.
                            @endif
                        </p>
                    </div>
                @endif

                <form
                    id="admin-story-editor"
                    method="post"
                    action="{{ $isNew ? locale_route('admin.stories.store') : locale_route('admin.stories.update', ['slug' => $slug]) }}"
                    class="admin-hub__md-editor"
                >
                    @csrf
                    @unless ($isNew) @method('PUT') @endunless

                    @if ($isNew)
                        <div class="admin-hub__field admin-hub__field--inline">
                            <label for="slug">Slug</label>
                            <input id="slug" name="slug" value="{{ old('slug', $slug) }}" required pattern="[a-z0-9\-]+">
                        </div>
                    @endif

                    <x-admin.locale-tabs name="story-body" active="en" class="admin-hub__locale-tabs--fill">
                        <x-slot:de>
                            <div class="admin-hub__field admin-hub__field--fill">
                                <label for="body_de">DE markdown</label>
                                <textarea id="body_de" name="body_de" class="admin-hub__textarea admin-hub__textarea--fill" spellcheck="false">{{ old('body_de', $bodyDe) }}</textarea>
                            </div>
                        </x-slot:de>
                        <x-slot:en>
                            <div class="admin-hub__field admin-hub__field--fill">
                                <label for="body_en">EN markdown</label>
                                <textarea id="body_en" name="body_en" class="admin-hub__textarea admin-hub__textarea--fill" spellcheck="false">{{ old('body_en', $bodyEn) }}</textarea>
                            </div>
                        </x-slot:en>
                    </x-admin.locale-tabs>
                </form>

                <div class="admin-hub__md-footer">
                    <button type="submit" class="tools-btn tools-btn--primary" form="admin-story-editor">Save</button>
                    @unless ($isNew)
                        <form
                            method="post"
                            action="{{ locale_route('admin.stories.destroy', ['slug' => $slug]) }}"
                            data-admin-confirm-delete data-confirm-message="Delete both locales?"
                            class="admin-hub__md-danger"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tools-btn tools-btn--danger">Delete</button>
                        </form>
                    @endunless
                </div>
            </div>

            <aside
                id="admin-story-images-rail"
                class="admin-hub__md-rail"
                data-admin-images-panel
                data-admin-tabs
                aria-label="Story side panel"
            >
                <div class="admin-hub__tablist admin-hub__md-rail-tabs" role="tablist" aria-label="Side panel">
                    <button
                        type="button"
                        class="admin-hub__tab is-active"
                        role="tab"
                        id="{{ $railUid }}-tab-images"
                        data-tab-id="{{ $railUid }}-panel-images"
                        aria-controls="{{ $railUid }}-panel-images"
                        aria-selected="true"
                        tabindex="0"
                    >Bilder</button>
                    <button
                        type="button"
                        class="admin-hub__tab"
                        role="tab"
                        id="{{ $railUid }}-tab-help"
                        data-tab-id="{{ $railUid }}-panel-help"
                        aria-controls="{{ $railUid }}-panel-help"
                        aria-selected="false"
                        tabindex="-1"
                    >Hilfe</button>
                </div>

                <div class="admin-hub__tab-panels admin-hub__md-rail-panels">
                    <div
                        class="admin-hub__tab-panel admin-hub__md-rail-panel"
                        role="tabpanel"
                        id="{{ $railUid }}-panel-images"
                        data-admin-tab-panel="{{ $railUid }}-panel-images"
                    >
                        <p class="admin-hub__meta">{{ $imageCount }} in markdown</p>

                        @unless ($isNew)
                            <form
                                method="post"
                                action="{{ locale_route('admin.stories.upload', ['slug' => $slug]) }}"
                                enctype="multipart/form-data"
                                class="admin-hub__md-upload"
                                data-admin-upload-auto
                            >
                                @csrf
                                <input type="hidden" name="slug" value="{{ $slug }}">
                                <label class="tools-btn admin-hub__upload-btn">
                                    Upload image
                                    <input
                                        type="file"
                                        name="image"
                                        accept="image/*"
                                        required
                                        class="bn-visually-hidden"
                                        data-admin-upload-input
                                    >
                                </label>
                            </form>
                        @else
                            <p class="admin-hub__meta">Save the story first, then upload images.</p>
                        @endunless

                        @if (! empty($images))
                            <ul class="admin-hub__md-rail-list">
                                @foreach ($images as $image)
                                    <li class="admin-hub__md-rail-item">
                                        <a href="{{ $image['url'] }}" target="_blank" rel="noopener" class="admin-hub__md-rail-thumb">
                                            <img src="{{ $image['previewUrl'] }}" alt="{{ $image['name'] }}" loading="lazy">
                                        </a>
                                        <button
                                            type="button"
                                            class="admin-hub__md-rail-path"
                                            data-admin-copy="{{ $image['markdownPath'] }}"
                                            title="Copy markdown path"
                                        >{{ $image['name'] }}</button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="admin-hub__meta">No images referenced in DE/EN yet.</p>
                        @endif
                    </div>

                    <div
                        class="admin-hub__tab-panel admin-hub__md-rail-panel"
                        role="tabpanel"
                        id="{{ $railUid }}-panel-help"
                        data-admin-tab-panel="{{ $railUid }}-panel-help"
                        hidden
                    >
                        <div class="admin-hub__md-help">
                            <p class="admin-hub__md-help-lead">
                                Stories = YAML-Frontmatter + Markdown-Body (GFM). Extra-Blöcke:
                                <code>flow</code>/<code>flowchart</code>, <code>video</code>, Code-Fences mit Titel/Highlight.
                                Kein Mermaid, keine Callouts, keine Tabs.
                            </p>

                            <h3>Front matter</h3>
                            <p class="admin-hub__md-help-note">Steht ganz oben, zwischen <code>---</code>. Pflicht für Karten, SEO und Filter.</p>
                            <pre class="admin-hub__md-help-code">---
title: "My story"
description: "Short summary for cards and SEO"
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - rbac
products:
  - snowflake
  - dbt
order: -1
publishedAt: 2026-07-28
hero: images/playbooks/example-hero.png
---</pre>
                            <ul class="admin-hub__md-help-list">
                                <li><code>title</code> / <code>description</code> — Karte, Browser-Titel, SEO.</li>
                                <li><code>author</code> — Anzeige auf der Story-Seite.</li>
                                <li><code>category</code> — Filter/Gruppierung im Überblick.</li>
                                <li><code>tags</code> — Liste (eine Zeile pro Tag).</li>
                                <li><code>products</code> — optionale Produkt-Badges (IDs wie in den Catalogs).</li>
                                <li><code>order</code> — Sortierung; <code>-1</code> = editorial/neu.</li>
                                <li><code>publishedAt</code> — Datum (<code>YYYY-MM-DD</code> oder mit Uhrzeit).</li>
                                <li><code>hero</code> — Pfad unter <code>images/playbooks/</code>.</li>
                            </ul>

                            <h3>Serie</h3>
                            <pre class="admin-hub__md-help-code">series: governance-pillars
seriesPart: 9
seriesTitle: The 8 Pillars of Data Governance</pre>
                            <ul class="admin-hub__md-help-list">
                                <li>Gleiche <code>series</code>-ID = eine Serie.</li>
                                <li><code>seriesPart</code> = Reihenfolge (1, 2, 3…).</li>
                                <li><code>seriesTitle</code> = Anzeigename der Serie (DE/EN getrennt pflegen).</li>
                                <li>Beim Anlegen: Template „Series episode“ übernimmt bestehende Werte.</li>
                            </ul>

                            <h3>Überschriften &amp; Text</h3>
                            <pre class="admin-hub__md-help-code">## Section
### Details
**bold** · *italic* · ~~strike~~
`inline code`
- bullet
1. numbered
- [ ] task open
- [x] task done</pre>
                            <p class="admin-hub__md-help-note">
                                Nur <code>##</code> und <code>###</code> landen im Inhaltsverzeichnis (mit Anker-IDs).
                                Ein führendes <code>#</code> im Body ist unnötig — der Titel kommt aus dem Frontmatter.
                            </p>

                            <h3>Links</h3>
                            <pre class="admin-hub__md-help-code">[External](https://example.com)
[Other story](/playbooks/eight-pillars)
[Series](/playbooks/series/governance-pillars)</pre>
                            <p class="admin-hub__md-help-note">Lokale Pfade ab <code>/</code> werden automatisch lokalisiert (DE/EN).</p>

                            <h3>Bilder</h3>
                            <pre class="admin-hub__md-help-code">![Alt text](images/playbooks/file.png)</pre>
                            <p class="admin-hub__md-help-note">Einfaches Bild. Upload im Tab <strong>Bilder</strong>, dann Pfad einfügen (Klick auf Dateiname kopiert).</p>

                            <h3>Diagramm-Figure (Lightbox)</h3>
                            <pre class="admin-hub__md-help-code">&lt;figure class="playbook-prose__figure"&gt;
  &lt;img
    src="images/playbooks/diagram-en.png"
    alt="Short description"
    class="playbook-prose__image playbook-prose__image--diagram"
  /&gt;
  &lt;figcaption class="playbook-prose__figure-caption"&gt;Caption&lt;/figcaption&gt;
&lt;/figure&gt;</pre>
                            <ul class="admin-hub__md-help-list">
                                <li><code>playbook-prose__image--diagram</code> aktiviert Lightbox.</li>
                                <li>Existiert ein WebP-Companion, wird automatisch <code>&lt;picture&gt;</code> genutzt.</li>
                                <li>DE/EN: getrennte Dateien (<code>-de</code> / <code>-en</code>) empfohlen.</li>
                            </ul>

                            <h3>Tabelle (GFM)</h3>
                            <pre class="admin-hub__md-help-code">| Step | Owner |
| --- | --- |
| Collect | Data team |
| Publish | Steward |</pre>

                            <h3>Zitat</h3>
                            <pre class="admin-hub__md-help-code">&gt; Governance is not a tool problem.
&gt; — Thomas Lindackers

&gt; **Merksatz ohne Autor.**</pre>
                            <p class="admin-hub__md-help-note">
                                Attribution nach <code>—</code>, <code>--</code> oder <code>- </code> wird als Quellenzeile gerendert.
                                Keine Callouts/Admonitions (<code>:::tip</code> usw. gibt es nicht).
                            </p>

                            <h3>Flow / Flowchart</h3>
                            <p class="admin-hub__md-help-note">
                                Eigene Fence-Syntax (kein Mermaid). Mindestens 2 Schritte, je Zeile ein Step.
                                Optional: <code>[active]</code>, <code>[done]</code> / <code>[completed]</code>.
                            </p>
                            <pre class="admin-hub__md-help-code">```flowchart
Governance policy
dbt project
Warehouse [active]
BI report
```

```flow linear
Collect metadata
Profile data
Publish catalog
```

```flow linear vertical
AI Foundations [done]
Language Models [done]
AI Agents [active]
Failure Modes
```</pre>
                            <ul class="admin-hub__md-help-list">
                                <li><code>flowchart</code> oder <code>flow</code> — Chevron-Kette (Default, Tools-Stil).</li>
                                <li><code>flow linear</code> / <code>box</code> / <code>boxes</code> — Boxen mit Pfeilen.</li>
                                <li><code>vertical</code> / <code>horizontal</code> — Layout (Default horizontal).</li>
                                <li>Bullet-/Nummern-Präfixe und reine Pfeilzeilen werden ignoriert.</li>
                            </ul>

                            <h3>Video</h3>
                            <pre class="admin-hub__md-help-code">```video Demo title
https://www.youtube.com/watch?v=VIDEO_ID
```

```video Intro
/videos/playbooks/intro.mp4
```</pre>
                            <ul class="admin-hub__md-help-list">
                                <li>Titel = Text nach <code>video</code> in der Info-Zeile.</li>
                                <li>Embeds: YouTube, Vimeo, Loom, Wistia (Consent-Button, kein Sofort-iframe).</li>
                                <li>Lokal nur <code>videos/playbooks/*.{mp4,webm,ogg}</code> (auch offline).</li>
                            </ul>

                            <h3>Code-Fence (Prism)</h3>
                            <pre class="admin-hub__md-help-code">```php title="Example.php" {2}
&lt;?php
echo 'hello';
```

```sql title="Metric check"
SELECT metric_id, value
FROM metrics
WHERE dt = CURRENT_DATE;
```

```bash
npm run build
```</pre>
                            <ul class="admin-hub__md-help-list">
                                <li>Sprache zuerst: <code>php</code>, <code>sql</code>, <code>bash</code>, <code>yaml</code>, <code>json</code>, …</li>
                                <li>Optional <code>title="…"</code> — Dateiname über dem Block.</li>
                                <li>Optional <code>{2}</code> oder <code>{3-6}</code> — Zeilen highlighten.</li>
                                <li>Aliases: <code>html</code>/<code>xml</code>→markup, <code>ts</code>→typescript, <code>sh</code>/<code>shell</code>→bash, <code>yml</code>→yaml, <code>md</code>→markdown, <code>env</code>→properties.</li>
                                <li>Highlight-Sprachen: markup, css, javascript, typescript, php, sql, json, yaml, bash, properties, python, markdown.</li>
                                <li>UI: Zeilennummern + Copy-Button (Prism).</li>
                            </ul>

                            <h3>Workflow-Tipps</h3>
                            <ul class="admin-hub__md-help-list">
                                <li>DE und EN strukturell synchron halten (Abschnitte, Bilder, Tags, Serie).</li>
                                <li>Erst speichern, dann Bilder hochladen; Pfad aus dem Bilder-Tab kopieren.</li>
                                <li>Nach Upload: WebP-Companion nutzen (leichter, automatisches <code>&lt;picture&gt;</code>).</li>
                                <li>Neue Story: Template wählen (Single / Series), dann nur Titel + Body schreiben.</li>
                                <li>Referenz-Story mit Live-Beispielen: <code>help-hub-platform</code>.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
