<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Support\ToolsNav;
use Illuminate\View\View;

class VendorResourcesController extends Controller
{
    private const MAX_TOOLS_PER_PRODUCT = 6;

    public function index(): View
    {
        /** @var array<string, array{de: string, en: string}> $families */
        $families = config('vendor-resources.families', []);

        /** @var array<string, array{de: string, en: string}> $vendors */
        $vendors = config('vendor-resources.vendors', []);

        /** @var array<string, array{label: array{de: string, en: string}, description: array{de: string, en: string}, products: list<string>, slots?: list<array{role: array{de: string, en: string}, products: list<string>, chooseOne?: bool}>}> $stacks */
        $stacks = config('vendor-resources.stacks', []);

        /** @var list<array<string, mixed>> $products */
        $products = config('vendor-resources.products', []);

        $availableFamilies = [];
        $availableVendors = [];
        foreach ($products as $product) {
            $family = is_string($product['family'] ?? null) ? $product['family'] : '';
            if ($family !== '' && isset($families[$family]) && ! in_array($family, $availableFamilies, true)) {
                $availableFamilies[] = $family;
            }

            $vendor = is_string($product['vendor'] ?? null) ? $product['vendor'] : '';
            if ($vendor !== '' && isset($vendors[$vendor]) && ! in_array($vendor, $availableVendors, true)) {
                $availableVendors[] = $vendor;
            }
        }

        $familyOrder = array_keys($families);
        usort($availableFamilies, static function (string $a, string $b) use ($familyOrder): int {
            $posA = array_search($a, $familyOrder, true);
            $posB = array_search($b, $familyOrder, true);

            return ($posA === false ? PHP_INT_MAX : $posA) <=> ($posB === false ? PHP_INT_MAX : $posB);
        });

        usort($availableVendors, static function (string $a, string $b) use ($vendors): int {
            $labelA = $vendors[$a]['en'] ?? $a;
            $labelB = $vendors[$b]['en'] ?? $b;

            return strcasecmp($labelA, $labelB);
        });

        /** @var array<string, list<string>> $stacksByProduct */
        $stacksByProduct = [];
        foreach ($stacks as $stackId => $stack) {
            $stackProducts = is_array($stack['products'] ?? null) ? $stack['products'] : [];
            foreach ($stackProducts as $productId) {
                if (! is_string($productId) || $productId === '') {
                    continue;
                }
                $stacksByProduct[$productId][] = $stackId;
            }
        }

        $toolsByProduct = $this->toolsByProduct($products);
        $supplierLibraryByProduct = $this->supplierLibraryByProduct($products);

        return view('resources::index', [
            'products' => $products,
            'families' => $families,
            'vendors' => $vendors,
            'stacks' => $stacks,
            'stacksByProduct' => $stacksByProduct,
            'toolsByProduct' => $toolsByProduct,
            'supplierLibraryByProduct' => $supplierLibraryByProduct,
            'availableFamilies' => $availableFamilies,
            'availableVendors' => $availableVendors,
            'certLastVerified' => (string) config('vendor-resources.lastVerified', ''),
            'certLastVerifiedNote' => config('vendor-resources.lastVerifiedNote', []),
        ]);
    }

    /**
     * Map vendor product id → curated Binom tool links (max 6).
     *
     * @param  list<array<string, mixed>>  $products
     * @return array<string, list<array{route: string, label: array{de: string, en: string}, description: array{de: string, en: string}}>>
     */
    private function toolsByProduct(array $products): array
    {
        /** @var list<array<string, mixed>> $navItems */
        $navItems = ToolsNav::withRegisteredRoutes(config('tools.nav', []));

        /** @var array<string, array<string, mixed>> $navById */
        $navById = [];
        /** @var array<string, list<array<string, mixed>>> $byProductKey */
        $byProductKey = [];

        foreach ($navItems as $item) {
            $id = is_string($item['id'] ?? null) ? $item['id'] : '';
            if ($id !== '') {
                $navById[$id] = $item;
            }

            foreach (ToolsNav::productKeysForTool($item) as $key) {
                // discovery/more only via explicit product tools overrides
                if ($key === 'discovery' || $key === 'more') {
                    continue;
                }
                $byProductKey[$key][] = $item;
            }
        }

        $map = [];

        foreach ($products as $product) {
            $productId = is_string($product['id'] ?? null) ? $product['id'] : '';
            if ($productId === '') {
                continue;
            }

            $family = is_string($product['family'] ?? null) ? $product['family'] : '';
            /** @var array<string, array<string, mixed>> $candidates */
            $candidates = [];

            $overrideIds = is_array($product['tools'] ?? null) ? $product['tools'] : [];
            foreach ($overrideIds as $toolId) {
                if (! is_string($toolId) || $toolId === '' || ! isset($navById[$toolId])) {
                    continue;
                }
                $candidates[$toolId] = $navById[$toolId];
            }

            foreach ($byProductKey[$productId] ?? [] as $item) {
                $toolId = is_string($item['id'] ?? null) ? $item['id'] : '';
                if ($toolId !== '') {
                    $candidates[$toolId] = $item;
                }
            }

            if ($family === 'ai') {
                foreach ($byProductKey['ai'] ?? [] as $item) {
                    $toolId = is_string($item['id'] ?? null) ? $item['id'] : '';
                    if ($toolId !== '') {
                        $candidates[$toolId] = $item;
                    }
                }
            }

            if ($candidates === []) {
                continue;
            }

            $sorted = array_values($candidates);
            usort($sorted, static function (array $a, array $b): int {
                $stepA = is_numeric($a['workflowStep'] ?? null) ? (int) $a['workflowStep'] : PHP_INT_MAX;
                $stepB = is_numeric($b['workflowStep'] ?? null) ? (int) $b['workflowStep'] : PHP_INT_MAX;
                if ($stepA !== $stepB) {
                    return $stepA <=> $stepB;
                }

                $labelA = is_string($a['label']['en'] ?? null) ? $a['label']['en'] : (string) ($a['id'] ?? '');
                $labelB = is_string($b['label']['en'] ?? null) ? $b['label']['en'] : (string) ($b['id'] ?? '');

                return strcasecmp($labelA, $labelB);
            });

            $links = [];
            foreach (array_slice($sorted, 0, self::MAX_TOOLS_PER_PRODUCT) as $item) {
                $route = is_string($item['route'] ?? null) ? $item['route'] : '';
                if ($route === '') {
                    continue;
                }

                $labelEn = is_string($item['label']['en'] ?? null) ? $item['label']['en'] : (string) ($item['id'] ?? $route);
                $labelDe = is_string($item['label']['de'] ?? null) ? $item['label']['de'] : $labelEn;
                $descEn = is_string($item['description']['en'] ?? null) ? $item['description']['en'] : '';
                $descDe = is_string($item['description']['de'] ?? null) ? $item['description']['de'] : $descEn;

                $links[] = [
                    'route' => $route,
                    'label' => ['de' => $labelDe, 'en' => $labelEn],
                    'description' => ['de' => $descDe, 'en' => $descEn],
                ];
            }

            if ($links !== []) {
                $map[$productId] = $links;
            }
        }

        return $map;
    }

    /**
     * Map vendor product id → Supplier Library link when a catalogue entry exists.
     *
     * @param  list<array<string, mixed>>  $products
     * @return array<string, list<array{href: string, label: array{de: string, en: string}, description: array{de: string, en: string}}>>
     */
    private function supplierLibraryByProduct(array $products): array
    {
        /** @var list<array<string, mixed>> $libraryProducts */
        $libraryProducts = config('suppliers.products', []);
        $libraryIds = [];
        foreach ($libraryProducts as $entry) {
            $id = is_string($entry['id'] ?? null) ? $entry['id'] : '';
            if ($id !== '') {
                $libraryIds[$id] = true;
            }
        }

        $map = [];
        foreach ($products as $product) {
            $productId = is_string($product['id'] ?? null) ? $product['id'] : '';
            if ($productId === '' || ! isset($libraryIds[$productId])) {
                continue;
            }

            $label = is_array($product['label'] ?? null) ? $product['label'] : [];
            $labelEn = (string) ($label['en'] ?? $productId);
            $labelDe = (string) ($label['de'] ?? $labelEn);

            $map[$productId] = [
                [
                    'href' => locale_route('suppliers.show', ['slug' => $productId]),
                    'label' => [
                        'de' => 'Supplier Library öffnen',
                        'en' => 'Open Supplier Library',
                    ],
                    'description' => [
                        'de' => "Kernfelder, Measures und PII/DSDR-Vorlagen für {$labelDe}.",
                        'en' => "Core fields, measures and PII/DSDR templates for {$labelEn}.",
                    ],
                ],
            ];
        }

        return $map;
    }
}
