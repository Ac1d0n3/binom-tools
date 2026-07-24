<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class VendorResourcesController extends Controller
{
    public function index(): View
    {
        /** @var array<string, array{de: string, en: string}> $families */
        $families = config('vendor-resources.families', []);

        /** @var array<string, array{de: string, en: string}> $vendors */
        $vendors = config('vendor-resources.vendors', []);

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

        return view('resources.index', [
            'products' => $products,
            'families' => $families,
            'vendors' => $vendors,
            'availableFamilies' => $availableFamilies,
            'availableVendors' => $availableVendors,
        ]);
    }
}
