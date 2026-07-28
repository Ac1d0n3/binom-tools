<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\AccountUser;
use App\Accounts\ContentAreas;
use App\Admin\Content\CatalogJsonWriter;
use App\Admin\Content\ContentOwnership;
use App\Admin\Content\MarkdownContentWriter;
use App\Catalog\CatalogJsonLoader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class AdvisorAdminController extends AdminController
{
    private const KINDS = ['story', 'supplier', 'vendor'];

    private CatalogJsonWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new CatalogJsonWriter(base_path('content/catalogs/advisor-recommendations'));
    }

    public function index(): View
    {
        $user = $this->assertAdvisorAccess();
        $doc = $this->safeRead();
        $items = array_values($doc['items'] ?? []);

        if (! $user->canManageContent) {
            $items = array_values(array_filter(
                $items,
                static fn (array $row): bool => ContentOwnership::ownerFromRow($row) === $user->id
            ));
        }

        return $this->adminView('admin::content.advisor-index', [
            'items' => $items,
            'storyOptions' => $this->storyOptions(),
            'supplierOptions' => $this->supplierOptions(),
            'vendorOptions' => $this->vendorOptions(),
            'canCreateStory' => $user->canAccessContentArea(ContentAreas::STORIES),
            'canCreateVendorSource' => $user->canAccessContentArea(ContentAreas::VENDORS_SOURCES),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->assertAdvisorAccess();
        $data = $this->validatedItem($request, requireId: true);
        $area = $this->areaForKind($data['kind']);
        $this->assertContentArea($area);

        try {
            $doc = $this->safeRead();
            $items = array_values($doc['items'] ?? []);
            foreach ($items as $item) {
                if (($item['id'] ?? '') === $data['id']) {
                    return back()->withErrors(['id' => 'Item id already exists.'])->withInput();
                }
            }
            if (! $this->refExists($data['kind'], $data['ref'])) {
                return back()->withErrors(['ref' => 'Unknown reference for this kind.'])->withInput();
            }

            $items[] = ContentOwnership::stampRow($this->itemPayload($data), $user->id);
            $doc['items'] = $items;
            $this->writer->write($doc);
            CatalogJsonLoader::clearCache();
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'advisor-item-saved');
    }

    public function update(Request $request, string $itemId): RedirectResponse
    {
        $doc = $this->safeRead();
        $existing = $this->findItem($doc, $itemId);
        abort_unless($existing !== null, 404);

        $kind = (string) ($existing['kind'] ?? 'story');
        $this->assertContentMutation($this->areaForKind($kind), ContentOwnership::ownerFromRow($existing));

        $data = $this->validatedItem($request, requireId: false);
        // Kind/ref stay editable but area must match mutation rights for the new kind.
        $this->assertContentArea($this->areaForKind($data['kind']));

        try {
            if (! $this->refExists($data['kind'], $data['ref'])) {
                return back()->withErrors(['ref' => 'Unknown reference for this kind.'])->withInput();
            }

            $items = array_values($doc['items'] ?? []);
            foreach ($items as $i => $item) {
                if (($item['id'] ?? '') !== $itemId) {
                    continue;
                }
                $items[$i] = array_merge($item, $this->itemPayload($data, $itemId));
                break;
            }
            $doc['items'] = $items;
            $this->writer->write($doc);
            CatalogJsonLoader::clearCache();
        } catch (RuntimeException $e) {
            return back()->withErrors(['title_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'advisor-item-saved');
    }

    public function destroy(string $itemId): RedirectResponse
    {
        $doc = $this->safeRead();
        $existing = $this->findItem($doc, $itemId);
        abort_unless($existing !== null, 404);

        $kind = (string) ($existing['kind'] ?? 'story');
        $this->assertContentMutation($this->areaForKind($kind), ContentOwnership::ownerFromRow($existing));

        try {
            $before = count($doc['items'] ?? []);
            $doc['items'] = array_values(array_filter(
                array_values($doc['items'] ?? []),
                static fn (array $item): bool => ($item['id'] ?? '') !== $itemId
            ));
            abort_unless(count($doc['items']) < $before, 404);
            $this->writer->write($doc);
            CatalogJsonLoader::clearCache();
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'advisor-item-deleted');
    }

    private function assertAdvisorAccess(): AccountUser
    {
        $user = $this->user();
        if (
            $user->canAccessContentArea(ContentAreas::STORIES)
            || $user->canAccessContentArea(ContentAreas::VENDORS_SOURCES)
        ) {
            return $user;
        }

        abort(403);
    }

    private function areaForKind(string $kind): string
    {
        return $kind === 'story' ? ContentAreas::STORIES : ContentAreas::VENDORS_SOURCES;
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedItem(Request $request, bool $requireId): array
    {
        $rules = [
            'kind' => ['required', 'in:story,supplier,vendor'],
            'ref' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'enabled' => ['nullable', 'boolean'],
            'group' => ['nullable', 'in:resources,suppliers,certs,gaps'],
            'icon' => ['nullable', 'string', 'max:80'],
            'score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tags' => ['nullable', 'string', 'max:500'],
            'title_de' => ['required', 'string', 'max:240'],
            'title_en' => ['required', 'string', 'max:240'],
            'reason_de' => ['required', 'string', 'max:2000'],
            'reason_en' => ['required', 'string', 'max:2000'],
            'when_goals' => ['nullable', 'string', 'max:500'],
            'when_scenarios' => ['nullable', 'string', 'max:500'],
            'when_domains' => ['nullable', 'string', 'max:500'],
            'when_platforms' => ['nullable', 'string', 'max:500'],
            'when_roles' => ['nullable', 'string', 'max:500'],
        ];
        if ($requireId) {
            $rules['id'] = ['nullable', 'regex:/^[a-z0-9-]+$/', 'max:80'];
        }

        $data = $request->validate($rules);
        $kind = (string) $data['kind'];
        $ref = (string) $data['ref'];
        $id = $requireId
            ? (trim((string) ($data['id'] ?? '')) !== '' ? (string) $data['id'] : $kind.'-'.$ref)
            : '';

        return [
            'id' => $id,
            'kind' => $kind,
            'ref' => $ref,
            'enabled' => $request->boolean('enabled'),
            'group' => ($data['group'] ?? '') !== '' ? (string) $data['group'] : ($kind === 'supplier' ? 'suppliers' : 'resources'),
            'icon' => trim((string) ($data['icon'] ?? '')) ?: $this->defaultIcon($kind),
            'score' => isset($data['score']) ? (int) $data['score'] : 70,
            'tags' => $this->csvList($data['tags'] ?? ''),
            'title' => ['de' => $data['title_de'], 'en' => $data['title_en']],
            'reason' => ['de' => $data['reason_de'], 'en' => $data['reason_en']],
            'when' => [
                'goals' => $this->csvList($data['when_goals'] ?? ''),
                'scenarios' => $this->csvList($data['when_scenarios'] ?? ''),
                'domains' => $this->csvList($data['when_domains'] ?? ''),
                'platforms' => $this->csvList($data['when_platforms'] ?? ''),
                'roles' => $this->csvList($data['when_roles'] ?? ''),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function itemPayload(array $data, ?string $forceId = null): array
    {
        return [
            'id' => $forceId ?? $data['id'],
            'kind' => $data['kind'],
            'ref' => $data['ref'],
            'enabled' => (bool) ($data['enabled'] ?? true),
            'group' => $data['group'],
            'icon' => $data['icon'],
            'score' => (int) $data['score'],
            'tags' => $data['tags'],
            'when' => $data['when'],
            'title' => $data['title'],
            'reason' => $data['reason'],
        ];
    }

    /**
     * @return list<string>
     */
    private function csvList(mixed $raw): array
    {
        $text = is_string($raw) ? $raw : '';
        if (trim($text) === '') {
            return [];
        }

        $parts = preg_split('/[,\s]+/', $text) ?: [];
        $out = [];
        foreach ($parts as $part) {
            $value = strtolower(trim((string) $part));
            if ($value !== '' && preg_match('/^[a-z0-9_-]+$/', $value)) {
                $out[] = $value;
            }
        }

        return array_values(array_unique($out));
    }

    private function defaultIcon(string $kind): string
    {
        return match ($kind) {
            'supplier' => 'fa-plug',
            'vendor' => 'fa-book-open',
            default => 'fa-book',
        };
    }

    private function refExists(string $kind, string $ref): bool
    {
        return match ($kind) {
            'story' => isset($this->storyOptions()[$ref]),
            'supplier' => isset($this->supplierOptions()[$ref]),
            'vendor' => isset($this->vendorOptions()[$ref]),
            default => false,
        };
    }

    /**
     * @return array<string, string>
     */
    private function storyOptions(): array
    {
        $writer = new MarkdownContentWriter(base_path('content/stories'));
        $options = [];
        foreach ($writer->listSlugs() as $row) {
            $slug = (string) ($row['slug'] ?? '');
            if ($slug !== '') {
                $options[$slug] = $slug;
            }
        }
        ksort($options);

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private function supplierOptions(): array
    {
        $options = [];
        foreach (config('suppliers.products', []) as $product) {
            if (! is_array($product)) {
                continue;
            }
            $id = (string) ($product['id'] ?? '');
            if ($id === '') {
                continue;
            }
            $label = is_array($product['label'] ?? null)
                ? (string) ($product['label']['en'] ?? $product['label']['de'] ?? $id)
                : $id;
            $options[$id] = $label !== '' ? "{$label} ({$id})" : $id;
        }
        asort($options);

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private function vendorOptions(): array
    {
        $options = [];
        $vendors = config('vendor-resources.vendors', []);
        if (! is_array($vendors)) {
            return $options;
        }
        foreach ($vendors as $id => $labels) {
            if (! is_string($id) || $id === '') {
                continue;
            }
            $label = is_array($labels)
                ? (string) ($labels['en'] ?? $labels['de'] ?? $id)
                : $id;
            $options[$id] = $label !== '' ? "{$label} ({$id})" : $id;
        }
        asort($options);

        return $options;
    }

    /**
     * @param  array<string, mixed>  $doc
     * @return array<string, mixed>|null
     */
    private function findItem(array $doc, string $itemId): ?array
    {
        foreach ($doc['items'] ?? [] as $item) {
            if (is_array($item) && ($item['id'] ?? '') === $itemId) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function safeRead(): array
    {
        try {
            $doc = $this->writer->read();

            return [
                'items' => array_values($doc['items'] ?? []),
            ];
        } catch (RuntimeException) {
            return ['items' => []];
        }
    }
}
