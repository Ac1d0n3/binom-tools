@extends('admin::layouts.shell', ['mainClass' => 'tools-shell__main--admin-md'])

@section('title', ($isNew ? 'New template' : 'Edit template') . ' — Admin — ' . config('app.name'))

@section('admin_content')
    @php
        $railUid = 'admin-plan-rail-'.preg_replace('/[^a-z0-9-]+/i', '-', (string) ($slug ?: 'new'));
    @endphp
    <div
        class="tools-content tools-content--wide sp-app admin-hub admin-hub--md-editor"
        data-admin-md-editor
        data-admin-images-rail
    >
        <div class="admin-hub__md-top">
            <a class="tools-btn" href="{{ locale_route('admin.plan-templates.index') }}">Back</a>
            <button
                type="button"
                class="tools-btn"
                data-admin-images-toggle
                aria-expanded="true"
                aria-controls="{{ $railUid }}"
            >
                <span data-admin-images-toggle-hide>Hide side panel</span>
                <span data-admin-images-toggle-show hidden>Show side panel</span>
            </button>
        </div>

        <div class="admin-hub__md-workspace">
            <div class="admin-hub__md-main">
                <form
                    id="admin-template-editor"
                    method="post"
                    action="{{ $isNew ? locale_route('admin.plan-templates.store') : locale_route('admin.plan-templates.update', ['slug' => $slug]) }}"
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

                    <x-admin.locale-tabs name="template-body" active="en" class="admin-hub__locale-tabs--fill">
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

                    @if ($errors->any())
                        <p class="admin-hub__meta">{{ $errors->first() }}</p>
                    @endif
                </form>

                <div class="admin-hub__md-footer">
                    <button type="submit" class="tools-btn tools-btn--primary" form="admin-template-editor">Save</button>
                    @unless ($isNew)
                        <form
                            method="post"
                            action="{{ locale_route('admin.plan-templates.destroy', ['slug' => $slug]) }}"
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
                id="{{ $railUid }}"
                class="admin-hub__md-rail"
                data-admin-images-panel
                aria-label="Template help"
            >
                <div class="admin-hub__md-rail-head">
                    <h2 class="admin-hub__md-rail-title">Hilfe</h2>
                </div>
                <div class="admin-hub__md-rail-panel admin-hub__md-rail-panel--solo">
                    <div class="admin-hub__md-help">
                        <p class="admin-hub__md-help-lead">
                            Plan templates = YAML-Frontmatter + freier Intro-Text + ein oder mehrere
                            <code>```sprint</code>-Blöcke. Checkboxen entstehen in der UI aus Tasks/Deliverables —
                            nicht aus <code>- [ ]</code> im Markdown.
                        </p>

                        <h3>Front matter</h3>
                        <p class="admin-hub__md-help-note"><code>type: sprint-plan</code> ist Pflicht (sonst speichert der Admin nicht).</p>
                        <pre class="admin-hub__md-help-code">---
type: sprint-plan
title: "Learning path — Trusted metrics"
slug: learning-path-trusted-metrics
description: Short plan summary for the catalog.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 5
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - KPI
---</pre>
                        <ul class="admin-hub__md-help-list">
                            <li><code>slug</code> sollte zum Datei-Slug passen.</li>
                            <li><code>duration</code> ≈ Anzahl der <code>```sprint</code>-Blöcke (sonst Warning).</li>
                            <li><code>unit</code> z. B. <code>week</code> / <code>day</code>.</li>
                            <li><code>locale</code> = <code>de</code> bzw. <code>en</code> passend zur Datei.</li>
                            <li>Optional Roadmap: <code>roadmap_family</code>, <code>roadmap_title</code>, <code>roadmap_track</code>, <code>roadmap_phase</code>, …</li>
                        </ul>

                        <h3>Intro-Text</h3>
                        <p class="admin-hub__md-help-note">
                            Freier Markdown zwischen Frontmatter und dem ersten Fence ist ok (Kontext für Leser).
                            Ausführbare Struktur nur in <code>```sprint</code>-Blöcken.
                        </p>

                        <h3>Sprint-Fence</h3>
                        <p class="admin-hub__md-help-note">Pflichtfelder: <code>id</code>, <code>number</code>, <code>title</code>, <code>goal</code>.</p>
                        <pre class="admin-hub__md-help-code">```sprint
id: week-01
number: 1
title: Orientation and mandate
goal: Understand the brief and stakeholders.

description: Optional longer intro for this week.
estimated_effort: 6h
notes: true

stories:
  - slug: data-ownership-stewardship
    required: true
  - slug: eight-pillars
    required: false

links:
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator

tasks:
  - id: align-expectations
    label: Align expectations with leadership
    assigneeType: person
    assigneeId: null
    plannedMinutes: 90
    helpText: |
      Short, action-oriented guidance.
    helpLinks:
      - label: Data Ownership
        href: /playbooks/data-ownership-stewardship
    tableColumns: Term, Definition, Owner

deliverables:
  - id: stakeholder-list
    label: Stakeholder list created
    plannedMinutes: 45

fields:
  - id: management-expectations
    label: Leadership expectations
    type: textarea
```</pre>

                        <h3>Tasks</h3>
                        <ul class="admin-hub__md-help-list">
                            <li>Pflicht: <code>id</code>, <code>label</code>.</li>
                            <li><code>assigneeType</code>: <code>person</code> | <code>team</code>; <code>assigneeId</code> oft <code>null</code> (UI wählt).</li>
                            <li><code>plannedMinutes</code>, <code>dependsOn</code>, <code>helpText</code>, <code>helpLinks</code>.</li>
                            <li><code>stories</code> — Playbook-Slugs unter <code>content/stories/</code>.</li>
                            <li><code>tableColumns</code> — CSV → Eingabetabelle in der UI.</li>
                            <li><code>demoCode</code> — optionaler Demo-/Code-Hinweis.</li>
                        </ul>

                        <h3>Deliverables</h3>
                        <p class="admin-hub__md-help-note">Wie Tasks, ohne Assignee: <code>id</code>, <code>label</code>, optional Help/Minutes.</p>

                        <h3>Fields (Formulareingaben)</h3>
                        <pre class="admin-hub__md-help-code">fields:
  - id: notes-field
    label: Notes
    type: textarea
    placeholder: Optional hint
  - id: status
    label: Status
    type: select
    options:
      - Draft
      - Ready</pre>
                        <p class="admin-hub__md-help-note">
                            Typen: <code>text</code>, <code>textarea</code>, <code>number</code>, <code>date</code>,
                            <code>select</code>, <code>multiselect</code>, <code>url</code>, <code>checkbox</code>,
                            <code>person</code>, <code>team</code>.
                        </p>

                        <h3>Flow im Sprint (optional)</h3>
                        <pre class="admin-hub__md-help-code">flowVariant: linear
flowLayout: vertical
flowSteps:
  - Collect inputs
  - Align owners [active]
  - Publish plan</pre>
                        <p class="admin-hub__md-help-note">Analog zu Story-Flows: <code>chevron</code>/<code>linear</code>, <code>horizontal</code>/<code>vertical</code>, States <code>[active]</code>/<code>[done]</code>.</p>

                        <h3>DE / EN</h3>
                        <ul class="admin-hub__md-help-list">
                            <li>Beide Locales pflegen.</li>
                            <li><strong>Gleiche IDs</strong> für Sprints, Tasks, Deliverables und Fields (sonst Validator-Fehler).</li>
                            <li>Labels/Texte übersetzen, Struktur-IDs nicht.</li>
                        </ul>

                        <h3>Workflow-Tipps</h3>
                        <ul class="admin-hub__md-help-list">
                            <li>Nach Speichern unter <code>/sprint-planner/templates</code> prüfen (Parse-Errors sichtbar).</li>
                            <li>Story-Slugs müssen existieren.</li>
                            <li>Referenz-Hilfe: Story <code>help-hub-sprint-planner</code>.</li>
                            <li>Kein Markdown-<code>- [ ]</code> als Task — immer <code>tasks:</code> / <code>deliverables:</code>.</li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
