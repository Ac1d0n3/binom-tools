<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Admin\Content\CatalogJsonWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class RadarAdminController extends AdminController
{
    private CatalogJsonWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new CatalogJsonWriter(base_path('content/catalogs/governance-radar'));
    }

    public function index(): View
    {
        $this->assertCanManageUsers();
        $doc = $this->safeRead();

        return $this->adminView('admin::content.radar-index', [
            'sources' => array_values($doc['sources'] ?? []),
            'items' => array_values($doc['items'] ?? []),
        ]);
    }

    public function storeSource(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'id' => ['nullable', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'name' => ['required', 'string', 'max:160'],
            'url' => ['required', 'url', 'max:500'],
            'language' => ['required', 'in:de,en'],
            'type' => ['nullable', 'string', 'max:80'],
        ]);

        try {
            $doc = $this->safeRead();
            $sources = array_values($doc['sources'] ?? []);
            $id = $data['id'] ?: Str::slug($data['name']);
            foreach ($sources as $source) {
                if (($source['id'] ?? '') === $id) {
                    return back()->withErrors(['id' => 'Source id already exists.'])->withInput();
                }
            }
            $sources[] = [
                'id' => $id,
                'name' => $data['name'],
                'url' => $data['url'],
                'language' => $data['language'],
                'type' => $data['type'] ?: 'Governance News',
            ];
            $doc['sources'] = $sources;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['name' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'radar-source-saved');
    }

    public function updateSource(Request $request, string $sourceId): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'url' => ['required', 'url', 'max:500'],
            'language' => ['required', 'in:de,en'],
            'type' => ['nullable', 'string', 'max:80'],
        ]);

        try {
            $doc = $this->safeRead();
            $sources = array_values($doc['sources'] ?? []);
            $found = false;
            foreach ($sources as $i => $source) {
                if (($source['id'] ?? '') !== $sourceId) {
                    continue;
                }
                $sources[$i] = array_merge($source, [
                    'name' => $data['name'],
                    'url' => $data['url'],
                    'language' => $data['language'],
                    'type' => $data['type'] ?: ($source['type'] ?? 'Governance News'),
                ]);
                $found = true;
                break;
            }
            abort_unless($found, 404);
            $doc['sources'] = $sources;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['name' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'radar-source-saved');
    }

    public function storeItem(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'title_de' => ['required', 'string', 'max:240'],
            'title_en' => ['required', 'string', 'max:240'],
            'url' => ['required', 'url', 'max:500'],
            'language' => ['required', 'in:de,en'],
            'type' => ['nullable', 'string', 'max:80'],
            'summary_de' => ['nullable', 'string', 'max:2000'],
            'summary_en' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $doc = $this->safeRead();
            $items = array_values($doc['items'] ?? []);
            $id = 'manual-'.Str::slug(substr($data['title_en'], 0, 40)).'-'.bin2hex(random_bytes(3));
            $items[] = [
                'id' => $id,
                'title' => $data['language'] === 'de' ? $data['title_de'] : $data['title_en'],
                'title_i18n' => ['de' => $data['title_de'], 'en' => $data['title_en']],
                'summary' => $data['language'] === 'de' ? ($data['summary_de'] ?? '') : ($data['summary_en'] ?? ''),
                'summary_i18n' => [
                    'de' => $data['summary_de'] ?? '',
                    'en' => $data['summary_en'] ?? '',
                ],
                'url' => $data['url'],
                'language' => $data['language'],
                'type' => $data['type'] ?: 'Governance News',
                'published_at' => now()->toDateString(),
                'source_id' => 'manual',
            ];
            $doc['items'] = $items;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['title_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'radar-item-saved');
    }

    /**
     * @return array<string, mixed>
     */
    private function safeRead(): array
    {
        try {
            return $this->writer->read();
        } catch (RuntimeException) {
            return ['sources' => [], 'items' => []];
        }
    }
}
