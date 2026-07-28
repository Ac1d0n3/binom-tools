<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\ContentAreas;
use App\Admin\Content\CatalogJsonWriter;
use App\Admin\Content\ContentOwnership;
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
        $user = $this->assertContentArea(ContentAreas::NEWS);
        $doc = $this->safeRead();
        $sources = array_values($doc['sources'] ?? []);
        $items = array_values($doc['items'] ?? []);

        if (! $user->canManageContent) {
            $sources = array_values(array_filter(
                $sources,
                static fn (array $row): bool => ContentOwnership::ownerFromRow($row) === $user->id
            ));
            $items = array_values(array_filter(
                $items,
                static fn (array $row): bool => ContentOwnership::ownerFromRow($row) === $user->id
            ));
        }

        $sourceIds = [];
        foreach ($sources as $source) {
            $id = (string) ($source['id'] ?? '');
            if ($id !== '') {
                $sourceIds[$id] = true;
            }
        }

        $itemsBySource = [];
        foreach ($items as $item) {
            $sid = (string) ($item['source_id'] ?? 'manual');
            if ($sid === '' || ! isset($sourceIds[$sid])) {
                $sid = 'manual';
            }
            $itemsBySource[$sid][] = $item;
        }

        return $this->adminView('admin::content.radar-index', [
            'sources' => $sources,
            'items' => $items,
            'itemsBySource' => $itemsBySource,
            'orphanItems' => $itemsBySource['manual'] ?? [],
        ]);
    }

    public function storeSource(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::NEWS);
        $request->merge([
            'feed_url' => $request->filled('feed_url') ? $request->input('feed_url') : null,
        ]);
        $data = $request->validate([
            'id' => ['nullable', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'name' => ['required', 'string', 'max:160'],
            'short_name' => ['nullable', 'string', 'max:40'],
            'source_url' => ['required', 'url', 'max:500'],
            'feed_url' => ['nullable', 'url', 'max:500'],
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
            $sources[] = ContentOwnership::stampRow([
                'id' => $id,
                'name' => $data['name'],
                'short_name' => $data['short_name'] ?: Str::upper(Str::substr($id, 0, 8)),
                'type' => $data['type'] ?: 'Governance News',
                'language' => $data['language'],
                'source_url' => $data['source_url'],
                'feed_url' => $data['feed_url'] ?: null,
                'ingest' => false,
                'priority' => 'medium',
            ], $user->id);
            $doc['sources'] = $sources;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['name' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'radar-source-saved');
    }

    public function updateSource(Request $request, string $sourceId): RedirectResponse
    {
        $doc = $this->safeRead();
        $this->assertContentMutation(ContentAreas::NEWS, $this->sourceOwner($doc, $sourceId));
        $request->merge([
            'feed_url' => $request->filled('feed_url') ? $request->input('feed_url') : null,
        ]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'short_name' => ['nullable', 'string', 'max:40'],
            'source_url' => ['required', 'url', 'max:500'],
            'feed_url' => ['nullable', 'url', 'max:500'],
            'language' => ['required', 'in:de,en'],
            'type' => ['nullable', 'string', 'max:80'],
        ]);

        try {
            $sources = array_values($doc['sources'] ?? []);
            $found = false;
            foreach ($sources as $i => $source) {
                if (($source['id'] ?? '') !== $sourceId) {
                    continue;
                }
                $sources[$i] = array_merge($source, [
                    'name' => $data['name'],
                    'short_name' => $data['short_name'] ?: ($source['short_name'] ?? ''),
                    'source_url' => $data['source_url'],
                    'feed_url' => $data['feed_url'] ?? null,
                    'language' => $data['language'],
                    'type' => $data['type'] ?: ($source['type'] ?? 'Governance News'),
                ]);
                unset($sources[$i]['url']);
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

    public function destroySource(string $sourceId): RedirectResponse
    {
        $doc = $this->safeRead();
        $this->assertContentMutation(ContentAreas::NEWS, $this->sourceOwner($doc, $sourceId));

        try {
            $before = count($doc['sources'] ?? []);
            $doc['sources'] = array_values(array_filter(
                array_values($doc['sources'] ?? []),
                static fn (array $source): bool => ($source['id'] ?? '') !== $sourceId
            ));
            abort_unless(count($doc['sources']) < $before, 404);
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['name' => $e->getMessage()]);
        }

        return back()->with('status', 'radar-source-deleted');
    }

    public function storeItem(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::NEWS);
        $data = $request->validate([
            'title_de' => ['required', 'string', 'max:240'],
            'title_en' => ['required', 'string', 'max:240'],
            'url' => ['required', 'url', 'max:500'],
            'type' => ['nullable', 'string', 'max:80'],
            'summary_de' => ['nullable', 'string', 'max:2000'],
            'summary_en' => ['nullable', 'string', 'max:2000'],
            'source_id' => ['nullable', 'string', 'max:80'],
        ]);

        try {
            $doc = $this->safeRead();
            $items = array_values($doc['items'] ?? []);
            $id = 'manual-'.Str::slug(substr($data['title_en'], 0, 40)).'-'.bin2hex(random_bytes(3));
            $sourceId = $data['source_id'] ?: 'manual';
            $language = $this->resolveItemLanguage($doc, $sourceId);
            $items[] = ContentOwnership::stampRow([
                'id' => $id,
                'title' => $language === 'de' ? $data['title_de'] : $data['title_en'],
                'title_i18n' => ['de' => $data['title_de'], 'en' => $data['title_en']],
                'summary' => $language === 'de' ? ($data['summary_de'] ?? '') : ($data['summary_en'] ?? ''),
                'summary_i18n' => [
                    'de' => $data['summary_de'] ?? '',
                    'en' => $data['summary_en'] ?? '',
                ],
                'url' => $data['url'],
                'language' => $language,
                'type' => $data['type'] ?: 'Governance News',
                'published_at' => now()->toDateString(),
                'source_id' => $sourceId,
                'origin' => 'manual',
            ], $user->id);
            $doc['items'] = $items;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['title_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'radar-item-saved');
    }

    public function updateItem(Request $request, string $itemId): RedirectResponse
    {
        $doc = $this->safeRead();
        $this->assertContentMutation(ContentAreas::NEWS, $this->itemOwner($doc, $itemId));
        $data = $request->validate([
            'title_de' => ['required', 'string', 'max:240'],
            'title_en' => ['required', 'string', 'max:240'],
            'url' => ['required', 'url', 'max:500'],
            'type' => ['nullable', 'string', 'max:80'],
            'summary_de' => ['nullable', 'string', 'max:2000'],
            'summary_en' => ['nullable', 'string', 'max:2000'],
            'source_id' => ['nullable', 'string', 'max:80'],
        ]);

        try {
            $items = array_values($doc['items'] ?? []);
            $found = false;
            foreach ($items as $i => $item) {
                if (($item['id'] ?? '') !== $itemId) {
                    continue;
                }
                $sourceId = $data['source_id'] ?: ($item['source_id'] ?? 'manual');
                $language = $this->resolveItemLanguage($doc, $sourceId, is_string($item['language'] ?? null) ? (string) $item['language'] : null);
                $items[$i] = array_merge($item, [
                    'title' => $language === 'de' ? $data['title_de'] : $data['title_en'],
                    'title_i18n' => ['de' => $data['title_de'], 'en' => $data['title_en']],
                    'summary' => $language === 'de' ? ($data['summary_de'] ?? '') : ($data['summary_en'] ?? ''),
                    'summary_i18n' => [
                        'de' => $data['summary_de'] ?? '',
                        'en' => $data['summary_en'] ?? '',
                    ],
                    'url' => $data['url'],
                    'language' => $language,
                    'type' => $data['type'] ?: ($item['type'] ?? 'Governance News'),
                    'source_id' => $sourceId,
                ]);
                $found = true;
                break;
            }
            abort_unless($found, 404);
            $doc['items'] = $items;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['title_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'radar-item-saved');
    }

    public function destroyItem(string $itemId): RedirectResponse
    {
        $doc = $this->safeRead();
        $this->assertContentMutation(ContentAreas::NEWS, $this->itemOwner($doc, $itemId));

        try {
            $before = count($doc['items'] ?? []);
            $doc['items'] = array_values(array_filter(
                array_values($doc['items'] ?? []),
                static fn (array $item): bool => ($item['id'] ?? '') !== $itemId
            ));
            abort_unless(count($doc['items']) < $before, 404);
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['title_en' => $e->getMessage()]);
        }

        return back()->with('status', 'radar-item-deleted');
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

    /**
     * @param  array<string, mixed>  $doc
     */
    private function sourceOwner(array $doc, string $sourceId): ?string
    {
        foreach ($doc['sources'] ?? [] as $source) {
            if (! is_array($source) || ($source['id'] ?? '') !== $sourceId) {
                continue;
            }

            return ContentOwnership::ownerFromRow($source);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $doc
     */
    private function itemOwner(array $doc, string $itemId): ?string
    {
        foreach ($doc['items'] ?? [] as $item) {
            if (! is_array($item) || ($item['id'] ?? '') !== $itemId) {
                continue;
            }

            return ContentOwnership::ownerFromRow($item);
        }

        return null;
    }

    /**
     * Prefer the source language; bilingual copy lives in title_i18n / summary_i18n.
     *
     * @param  array<string, mixed>  $doc
     */
    private function resolveItemLanguage(array $doc, string $sourceId, ?string $fallback = null): string
    {
        foreach ($doc['sources'] ?? [] as $source) {
            if (($source['id'] ?? '') !== $sourceId) {
                continue;
            }
            $language = strtolower(trim((string) ($source['language'] ?? '')));
            if (in_array($language, ['de', 'en'], true)) {
                return $language;
            }
            break;
        }

        $fallback = is_string($fallback) ? strtolower(trim($fallback)) : '';

        return in_array($fallback, ['de', 'en'], true) ? $fallback : 'en';
    }
}
