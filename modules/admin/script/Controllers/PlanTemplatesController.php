<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
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
        $this->assertCanManageUsers();

        return $this->adminView('admin::content.plan-templates-index', [
            'templates' => $this->writer->listSlugs(),
        ]);
    }

    public function create(): View
    {
        $this->assertCanManageUsers();
        $stub = "---\ntype: sprint-plan\ntitle: \"\"\n---\n\n```sprint\n# Sprint 1\n- [ ] Task\n```\n";

        return $this->adminView('admin::content.plan-templates-form', [
            'slug' => '',
            'bodyDe' => $stub,
            'bodyEn' => $stub,
            'isNew' => true,
        ]);
    }

    public function edit(string $slug): View
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);

        return $this->adminView('admin::content.plan-templates-form', [
            'slug' => $slug,
            'bodyDe' => $this->writer->read($slug, 'de') ?? '',
            'bodyEn' => $this->writer->read($slug, 'en') ?? '',
            'isNew' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'slug' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:120'],
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            $this->writePair($data['slug'], (string) ($data['body_de'] ?? ''), (string) ($data['body_en'] ?? ''));
        } catch (RuntimeException $e) {
            return back()->withErrors(['slug' => $e->getMessage()])->withInput();
        }

        return redirect()->to(locale_route('admin.plan-templates.edit', ['slug' => $data['slug']]))->with('status', 'template-saved');
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $data = $request->validate([
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            $this->writePair($slug, (string) ($data['body_de'] ?? ''), (string) ($data['body_en'] ?? ''));
        } catch (RuntimeException $e) {
            return back()->withErrors(['body_de' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'template-saved');
    }

    public function destroy(string $slug): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $this->writer->delete($slug);

        return redirect()->to(locale_route('admin.plan-templates.index'))->with('status', 'template-deleted');
    }

    private function writePair(string $slug, string $de, string $en): void
    {
        foreach (['de' => $de, 'en' => $en] as $locale => $body) {
            if (trim($body) === '') {
                continue;
            }
            if (! str_contains($body, 'type: sprint-plan') && ! str_contains($body, 'type:sprint-plan')) {
                throw new RuntimeException('Plan templates must include frontmatter type: sprint-plan.');
            }
            $this->writer->write($slug, $locale, $body);
        }
    }
}
