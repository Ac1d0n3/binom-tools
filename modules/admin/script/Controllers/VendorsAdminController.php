<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\ContentAreas;
use App\Admin\Content\CatalogJsonWriter;
use App\Admin\Content\ContentOwnership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class VendorsAdminController extends AdminController
{
    private const LINK_GROUPS = ['help', 'governance', 'learning', 'certifications', 'compliance'];

    private CatalogJsonWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new CatalogJsonWriter(base_path('content/catalogs/vendor-resources'));
    }

    public function index(): View
    {
        $user = $this->assertContentArea(ContentAreas::VENDORS_SOURCES);
        $doc = $this->safeRead();
        $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
        $products = array_values(array_filter($doc['products'] ?? [], 'is_array'));

        if (! $user->canManageContent) {
            $vendors = array_filter(
                $vendors,
                static fn (mixed $value): bool => is_array($value) && ContentOwnership::ownerFromRow($value) === $user->id
            );
            $products = array_values(array_filter(
                $products,
                static fn (array $row): bool => ContentOwnership::ownerFromRow($row) === $user->id
            ));
        }

        $byVendor = [];
        foreach ($products as $product) {
            $vendorId = (string) ($product['vendor'] ?? '');
            if ($vendorId === '') {
                continue;
            }
            $byVendor[$vendorId][] = $product;
        }

        return $this->adminView('admin::catalogs.vendors-index', [
            'vendors' => $vendors,
            'productsByVendor' => $byVendor,
            'families' => is_array($doc['families'] ?? null) ? $doc['families'] : [],
            'productCount' => count($products),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::VENDORS_SOURCES);
        $data = $request->validate([
            'id' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['required', 'string', 'max:120'],
        ]);

        try {
            $doc = $this->safeRead();
            $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
            if (isset($vendors[$data['id']])) {
                return back()->withErrors(['id' => 'Vendor already exists.'])->withInput();
            }
            $vendors[$data['id']] = ContentOwnership::stampRow([
                'de' => $data['name_de'],
                'en' => $data['name_en'],
            ], $user->id);
            $doc['vendors'] = $vendors;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'vendor-saved');
    }

    public function update(Request $request, string $vendorId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $vendorId) === 1, 404);
        $doc = $this->safeRead();
        $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
        abort_unless(isset($vendors[$vendorId]), 404);
        $existing = is_array($vendors[$vendorId]) ? $vendors[$vendorId] : [];
        $this->assertContentMutation(ContentAreas::VENDORS_SOURCES, ContentOwnership::ownerFromRow($existing));
        $data = $request->validate([
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['required', 'string', 'max:120'],
        ]);

        try {
            $vendors[$vendorId] = array_merge($existing, [
                'de' => $data['name_de'],
                'en' => $data['name_en'],
            ]);
            $doc['vendors'] = $vendors;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['name_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'vendor-saved');
    }

    public function destroy(string $vendorId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $vendorId) === 1, 404);
        $doc = $this->safeRead();
        $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
        $existing = is_array($vendors[$vendorId] ?? null) ? $vendors[$vendorId] : [];
        $this->assertContentMutation(ContentAreas::VENDORS_SOURCES, ContentOwnership::ownerFromRow($existing));
        try {
            unset($vendors[$vendorId]);
            $doc['vendors'] = $vendors;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'vendor-deleted');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::VENDORS_SOURCES);
        $data = $this->validateProduct($request);

        try {
            $doc = $this->safeRead();
            $products = array_values(array_filter($doc['products'] ?? [], 'is_array'));
            foreach ($products as $product) {
                if (($product['id'] ?? '') === $data['id']) {
                    return back()->withErrors(['id' => 'Product already exists.'])->withInput();
                }
            }
            $products[] = ContentOwnership::stampRow($this->buildProduct($data, null), $user->id);
            $doc['products'] = $products;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'product-saved');
    }

    public function updateProduct(Request $request, string $productId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $productId) === 1, 404);
        $this->assertContentMutation(ContentAreas::VENDORS_SOURCES, $this->productOwner($productId));
        $data = $this->validateProduct($request, requireId: false);
        $data['id'] = $productId;

        try {
            $doc = $this->safeRead();
            $products = array_values(array_filter($doc['products'] ?? [], 'is_array'));
            $found = false;
            foreach ($products as $i => $product) {
                if (($product['id'] ?? '') !== $productId) {
                    continue;
                }
                $products[$i] = $this->buildProduct($data, $product);
                $found = true;
                break;
            }
            abort_unless($found, 404);
            $doc['products'] = $products;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'product-saved');
    }

    public function destroyProduct(string $productId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $productId) === 1, 404);
        $this->assertContentMutation(ContentAreas::VENDORS_SOURCES, $this->productOwner($productId));
        try {
            $doc = $this->safeRead();
            $doc['products'] = array_values(array_filter(
                array_filter($doc['products'] ?? [], 'is_array'),
                static fn (array $product): bool => ($product['id'] ?? '') !== $productId
            ));
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'product-deleted');
    }

    private function productOwner(string $productId): ?string
    {
        $doc = $this->safeRead();
        foreach (array_filter($doc['products'] ?? [], 'is_array') as $product) {
            if (($product['id'] ?? '') !== $productId) {
                continue;
            }

            return ContentOwnership::ownerFromRow($product);
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProduct(Request $request, bool $requireId = true): array
    {
        $rules = [
            'vendor' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'family' => ['required', 'string', 'max:80'],
            'label_de' => ['required', 'string', 'max:160'],
            'label_en' => ['required', 'string', 'max:160'],
            'purpose_de' => ['nullable', 'string', 'max:2000'],
            'purpose_en' => ['nullable', 'string', 'max:2000'],
            'brandColor' => ['nullable', 'string', 'max:40'],
            'logo' => ['nullable', 'string', 'max:160'],
            'models' => ['nullable', 'string', 'max:500'],
            'residency' => ['nullable', 'string', 'max:500'],
            'links' => ['nullable', 'array'],
        ];
        if ($requireId) {
            $rules['id'] = ['required', 'regex:/^[a-z0-9-]+$/', 'max:80'];
        }

        foreach (self::LINK_GROUPS as $group) {
            $rules["links.{$group}"] = ['nullable', 'array'];
            $rules["links.{$group}.*.href"] = ['nullable', 'string', 'max:500'];
            $rules["links.{$group}.*.label_de"] = ['nullable', 'string', 'max:160'];
            $rules["links.{$group}.*.label_en"] = ['nullable', 'string', 'max:160'];
            $rules["links.{$group}.*.description_de"] = ['nullable', 'string', 'max:500'];
            $rules["links.{$group}.*.description_en"] = ['nullable', 'string', 'max:500'];
            $rules["links.{$group}.*.id"] = ['nullable', 'string', 'max:80'];
        }

        return $request->validate($rules);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>|null  $existing
     * @return array<string, mixed>
     */
    private function buildProduct(array $data, ?array $existing): array
    {
        $product = is_array($existing) ? $existing : [];
        $product['id'] = (string) $data['id'];
        $product['vendor'] = (string) $data['vendor'];
        $product['family'] = (string) $data['family'];
        $product['label'] = ['de' => $data['label_de'], 'en' => $data['label_en']];
        $product['purpose'] = [
            'de' => (string) ($data['purpose_de'] ?? ''),
            'en' => (string) ($data['purpose_en'] ?? ''),
        ];
        $product['brandColor'] = (string) ($data['brandColor'] ?? $product['brandColor'] ?? '#64748b');
        $product['logo'] = (string) ($data['logo'] ?? $product['logo'] ?? '');
        $product['models'] = $this->csvList((string) ($data['models'] ?? ''));
        $product['residency'] = $this->csvList((string) ($data['residency'] ?? ''));

        $links = is_array($data['links'] ?? null) ? $data['links'] : [];
        foreach (self::LINK_GROUPS as $group) {
            $product[$group] = $this->normalizeLinks(is_array($links[$group] ?? null) ? $links[$group] : [], $group === 'compliance');
        }

        return $product;
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private function normalizeLinks(array $rows, bool $withId): array
    {
        $out = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $href = trim((string) ($row['href'] ?? ''));
            $labelDe = trim((string) ($row['label_de'] ?? ''));
            $labelEn = trim((string) ($row['label_en'] ?? ''));
            if ($href === '' && $labelDe === '' && $labelEn === '') {
                continue;
            }
            $item = [
                'label' => ['de' => $labelDe, 'en' => $labelEn !== '' ? $labelEn : $labelDe],
                'href' => $href,
                'description' => [
                    'de' => trim((string) ($row['description_de'] ?? '')),
                    'en' => trim((string) ($row['description_en'] ?? '')),
                ],
            ];
            if ($withId) {
                $id = trim((string) ($row['id'] ?? ''));
                if ($id !== '') {
                    $item['id'] = $id;
                }
            }
            $out[] = $item;
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    private function csvList(string $value): array
    {
        $parts = preg_split('/\s*,\s*/', trim($value)) ?: [];

        return array_values(array_filter(array_map('strval', $parts), static fn (string $part): bool => $part !== ''));
    }

    /**
     * @return array<string, mixed>
     */
    private function safeRead(): array
    {
        try {
            return $this->writer->read();
        } catch (RuntimeException) {
            return ['vendors' => [], 'products' => [], 'families' => []];
        }
    }
}
