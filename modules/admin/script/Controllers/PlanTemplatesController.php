<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\ContentAreas;
use App\Admin\Content\ContentOwnership;
use App\Admin\Content\MarkdownContentWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class PlanTemplatesController extends AdminController
{
    private MarkdownContentWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new MarkdownContentWriter((string) config('admin.sprint_plans_path', base_path('content/sprint-plans')));
    }

    public function index(): View
    {
        $user = $this->assertContentArea(ContentAreas::PLAN_TEMPLATES);
        $templates = $this->writer->listSlugs();
        if (! $user->canManageContent) {
            $templates = array_values(array_filter(
                $templates,
                fn (array $row): bool => $this->templateOwner((string) $row['slug']) === $user->id
            ));
        }

        return $this->adminView('admin::content.plan-templates-index', [
            'templates' => $templates,
        ]);
    }

    public function create(): View
    {
        $this->assertContentArea(ContentAreas::PLAN_TEMPLATES);

        return $this->adminView('admin::content.plan-templates-form', [
            'slug' => '',
            'bodyDe' => $this->draftStub('de'),
            'bodyEn' => $this->draftStub('en'),
            'isNew' => true,
        ]);
    }

    public function edit(string $slug): View
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $this->assertContentMutation(ContentAreas::PLAN_TEMPLATES, $this->templateOwner($slug));

        return $this->adminView('admin::content.plan-templates-form', [
            'slug' => $slug,
            'bodyDe' => $this->writer->read($slug, 'de') ?? '',
            'bodyEn' => $this->writer->read($slug, 'en') ?? '',
            'isNew' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::PLAN_TEMPLATES);
        $data = $request->validate([
            'slug' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:120'],
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            $this->writePair($data['slug'], (string) ($data['body_de'] ?? ''), (string) ($data['body_en'] ?? ''), $user->id);
        } catch (RuntimeException $e) {
            return back()->withErrors(['slug' => $e->getMessage()])->withInput();
        }

        return redirect()->to(locale_route('admin.plan-templates.edit', ['slug' => $data['slug']]))->with('status', 'template-saved');
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $user = $this->assertContentMutation(ContentAreas::PLAN_TEMPLATES, $this->templateOwner($slug));
        $data = $request->validate([
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            $this->writePair($slug, (string) ($data['body_de'] ?? ''), (string) ($data['body_en'] ?? ''), $user->id);
        } catch (RuntimeException $e) {
            return back()->withErrors(['body_de' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'template-saved');
    }

    public function destroy(string $slug): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $this->assertContentMutation(ContentAreas::PLAN_TEMPLATES, $this->templateOwner($slug));
        $this->writer->delete($slug);

        return redirect()->to(locale_route('admin.plan-templates.index'))->with('status', 'template-deleted');
    }

    private function writePair(string $slug, string $de, string $en, string $userId): void
    {
        foreach (['de' => $de, 'en' => $en] as $locale => $body) {
            if (trim($body) === '') {
                continue;
            }
            if (! str_contains($body, 'type: sprint-plan') && ! str_contains($body, 'type:sprint-plan')) {
                throw new RuntimeException('Plan templates must include frontmatter type: sprint-plan.');
            }
            $this->writer->write($slug, $locale, ContentOwnership::ensureMarkdownOwner($body, $userId));
        }
    }

    private function templateOwner(string $slug): ?string
    {
        foreach (['en', 'de'] as $locale) {
            $raw = $this->writer->read($slug, $locale);
            if ($raw === null || trim($raw) === '') {
                continue;
            }
            $owner = ContentOwnership::ownerFromMarkdown($raw);
            if ($owner !== null) {
                return $owner;
            }
        }

        return null;
    }

    private function draftStub(string $locale): string
    {
        $intro = $locale === 'de'
            ? 'Kurzer Intro-Text für Leser (optionales Markdown).'
            : 'Short intro for readers (optional markdown).';

        return <<<MD
---
type: sprint-plan
title: ""
slug: ""
description: ""
duration: 1
unit: week
category: ""
author: Thomas Lindackers
version: 1
locale: {$locale}
tags:
  - 
---

{$intro}

```sprint
id: week-01
number: 1
title: ""
goal: ""

tasks:
  - id: first-task
    label: ""
    assigneeType: person
    assigneeId: null

deliverables:
  - id: first-deliverable
    label: ""

notes: true
```
MD;
    }
}
