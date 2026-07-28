<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\ContentAreas;
use App\Admin\Content\CatalogJsonWriter;
use App\Admin\Content\ContentOwnership;
use App\Catalog\CatalogJsonLoader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class SuppliersAdminController extends AdminController
{
    private CatalogJsonWriter $productsWriter;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->productsWriter = new CatalogJsonWriter(
            base_path('content/catalogs/suppliers'),
            'products.json'
        );
    }

    public function index(): View
    {
        $user = $this->assertContentArea(ContentAreas::VENDORS_SOURCES);
        $products = $this->readProducts();
        if (! $user->canManageContent) {
            $products = array_values(array_filter(
                $products,
                static fn (array $row): bool => ContentOwnership::ownerFromRow($row) === $user->id
            ));
        }
        usort($products, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        return $this->adminView('admin::catalogs.suppliers-index', [
            'products' => $products,
            'domains' => config('suppliers.domains', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::VENDORS_SOURCES);
        $data = $this->validateProduct($request, true);

        try {
            $products = $this->readProducts();
            foreach ($products as $product) {
                if (($product['id'] ?? '') === $data['id']) {
                    return back()->withErrors(['id' => 'Source id already exists.'])->withInput();
                }
            }
            $products[] = ContentOwnership::stampRow($this->buildProduct($data, null), $user->id);
            $this->writeProducts($products);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'supplier-saved');
    }

    public function update(Request $request, string $supplierId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $supplierId) === 1, 404);
        $this->assertContentMutation(ContentAreas::VENDORS_SOURCES, $this->productOwner($supplierId));
        $data = $this->validateProduct($request, false);

        try {
            $products = $this->readProducts();
            $found = false;
            foreach ($products as $i => $product) {
                if (($product['id'] ?? '') !== $supplierId) {
                    continue;
                }
                $products[$i] = $this->buildProduct($data, $product);
                $found = true;
                break;
            }
            abort_unless($found, 404);
            $this->writeProducts($products);
        } catch (RuntimeException $e) {
            return back()->withErrors(['label_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'supplier-saved');
    }

    public function destroy(string $supplierId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $supplierId) === 1, 404);
        $this->assertContentMutation(ContentAreas::VENDORS_SOURCES, $this->productOwner($supplierId));

        try {
            $products = $this->readProducts();
            $before = count($products);
            $products = array_values(array_filter(
                $products,
                static fn (array $product): bool => ($product['id'] ?? '') !== $supplierId
            ));
            abort_unless(count($products) < $before, 404);
            $this->writeProducts($products);
            $this->removeOverlayKey('governance.json', $supplierId);
            $this->removeOverlayKey('quality.json', $supplierId);
            $this->removeOverlayKey('sql.json', $supplierId);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'supplier-deleted');
    }

    private function productOwner(string $supplierId): ?string
    {
        foreach ($this->readProducts() as $product) {
            if (($product['id'] ?? '') !== $supplierId) {
                continue;
            }

            return ContentOwnership::ownerFromRow($product);
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProduct(Request $request, bool $requireId): array
    {
        $domains = config('suppliers.domains', []);
        $rules = [
            'domain' => ['required', 'string', 'max:40', Rule::in(array_keys($domains))],
            'order' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'label_de' => ['required', 'string', 'max:160'],
            'label_en' => ['required', 'string', 'max:160'],
            'purpose_de' => ['nullable', 'string', 'max:500'],
            'purpose_en' => ['nullable', 'string', 'max:500'],
        ];
        if ($requireId) {
            $rules['id'] = ['required', 'regex:/^[a-z0-9-]+$/', 'max:80'];
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
        $base = is_array($existing) ? $existing : [
            'entities' => [],
            'fields' => [],
            'skipTables' => [],
            'skip' => [],
            'dimensions' => [],
            'pii' => [],
            'dsdr' => [],
            'measures' => [],
            'tools' => [],
            'relatedPlaybooks' => [],
        ];

        $base['id'] = $existing['id'] ?? $data['id'];
        $base['domain'] = $data['domain'];
        $base['order'] = (int) ($data['order'] ?? ($existing['order'] ?? 100));
        $base['label'] = [
            'de' => $data['label_de'],
            'en' => $data['label_en'],
        ];
        $base['shortPurpose'] = [
            'de' => $data['purpose_de'] ?? '',
            'en' => $data['purpose_en'] ?? '',
        ];

        return $base;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readProducts(): array
    {
        $raw = $this->productsWriter->read();
        if ($raw === []) {
            return [];
        }
        if (! array_is_list($raw)) {
            throw new RuntimeException('suppliers/products.json must be a list.');
        }

        return array_values(array_filter($raw, 'is_array'));
    }

    /**
     * @param  list<array<string, mixed>>  $products
     */
    private function writeProducts(array $products): void
    {
        $this->productsWriter->write(array_values($products));
        CatalogJsonLoader::clearCache();
    }

    private function removeOverlayKey(string $file, string $id): void
    {
        $writer = new CatalogJsonWriter(base_path('content/catalogs/suppliers'), $file);
        $data = $writer->read();
        if ($data === [] || ! is_array($data) || ! array_key_exists($id, $data)) {
            return;
        }
        unset($data[$id]);
        $writer->write($data);
        CatalogJsonLoader::clearCache();
    }
}
