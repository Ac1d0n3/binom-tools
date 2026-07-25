<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use App\Support\ToolsNav;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierLibraryController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    public function index(): View
    {
        /** @var array<string, array{de: string, en: string}> $domains */
        $domains = config('suppliers.domains', []);

        /** @var list<array<string, mixed>> $products */
        $products = config('suppliers.products', []);

        usort($products, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        $availableDomains = [];
        foreach ($products as $product) {
            $domain = is_string($product['domain'] ?? null) ? $product['domain'] : '';
            if ($domain !== '' && isset($domains[$domain]) && ! in_array($domain, $availableDomains, true)) {
                $availableDomains[] = $domain;
            }
        }

        $domainOrder = array_keys($domains);
        usort($availableDomains, static function (string $a, string $b) use ($domainOrder): int {
            $posA = array_search($a, $domainOrder, true);
            $posB = array_search($b, $domainOrder, true);

            return ($posA === false ? PHP_INT_MAX : $posA) <=> ($posB === false ? PHP_INT_MAX : $posB);
        });

        return view('suppliers.index', [
            'products' => $products,
            'domains' => $domains,
            'availableDomains' => $availableDomains,
        ]);
    }

    public function show(Request $request): View
    {
        $slug = (string) $request->route('slug');
        $product = $this->findProduct($slug);
        abort_if($product === null, 404);

        /** @var array<string, array{de: string, en: string}> $domains */
        $domains = config('suppliers.domains', []);

        $relatedPlaybooks = $this->resolveRelatedPlaybooks(
            is_array($product['relatedPlaybooks'] ?? null) ? $product['relatedPlaybooks'] : []
        );

        $toolLinks = $this->resolveToolLinks(
            is_array($product['tools'] ?? null) ? $product['tools'] : []
        );

        $neighbors = $this->neighbors($slug);

        return view('suppliers.show', [
            'product' => $product,
            'domains' => $domains,
            'relatedPlaybooks' => $relatedPlaybooks,
            'toolLinks' => $toolLinks,
            'prev' => $neighbors['prev'],
            'next' => $neighbors['next'],
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findProduct(string $slug): ?array
    {
        /** @var list<array<string, mixed>> $products */
        $products = config('suppliers.products', []);

        foreach ($products as $product) {
            $id = is_string($product['id'] ?? null) ? $product['id'] : '';
            if ($id === $slug) {
                return $product;
            }
        }

        return null;
    }

    /**
     * @param  list<mixed>  $slugs
     * @return list<array{slug: string, titleDe: string, titleEn: string}>
     */
    private function resolveRelatedPlaybooks(array $slugs): array
    {
        $resolved = [];
        foreach ($slugs as $slug) {
            if (! is_string($slug) || $slug === '') {
                continue;
            }

            $playbook = $this->playbooks->find($slug);
            if ($playbook === null) {
                continue;
            }

            $resolved[] = [
                'slug' => $slug,
                'titleDe' => $playbook->title('de'),
                'titleEn' => $playbook->title('en'),
            ];
        }

        return $resolved;
    }

    /**
     * @param  list<mixed>  $toolIds
     * @return list<array{route: string, label: array{de: string, en: string}}>
     */
    private function resolveToolLinks(array $toolIds): array
    {
        /** @var list<array<string, mixed>> $navItems */
        $navItems = ToolsNav::withRegisteredRoutes(config('tools.nav', []));

        /** @var array<string, array<string, mixed>> $byId */
        $byId = [];
        foreach ($navItems as $item) {
            $id = is_string($item['id'] ?? null) ? $item['id'] : '';
            if ($id !== '') {
                $byId[$id] = $item;
            }
        }

        $links = [];
        foreach ($toolIds as $toolId) {
            if (! is_string($toolId) || $toolId === '' || ! isset($byId[$toolId])) {
                continue;
            }
            $item = $byId[$toolId];
            $route = is_string($item['route'] ?? null) ? $item['route'] : '';
            if ($route === '') {
                continue;
            }
            $label = is_array($item['label'] ?? null) ? $item['label'] : [];
            $links[] = [
                'route' => $route,
                'label' => [
                    'de' => (string) ($label['de'] ?? $toolId),
                    'en' => (string) ($label['en'] ?? $toolId),
                ],
            ];
        }

        return $links;
    }

    /**
     * @return array{prev: array{id: string, label: array{de: string, en: string}}|null, next: array{id: string, label: array{de: string, en: string}}|null}
     */
    private function neighbors(string $slug): array
    {
        /** @var list<array<string, mixed>> $products */
        $products = config('suppliers.products', []);
        usort($products, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        $ids = [];
        foreach ($products as $product) {
            $id = is_string($product['id'] ?? null) ? $product['id'] : '';
            if ($id !== '') {
                $ids[] = $product;
            }
        }

        $index = null;
        foreach ($ids as $i => $product) {
            if (($product['id'] ?? '') === $slug) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return ['prev' => null, 'next' => null];
        }

        $mapNeighbor = static function (?array $product): ?array {
            if ($product === null) {
                return null;
            }
            $id = (string) ($product['id'] ?? '');
            $label = is_array($product['label'] ?? null) ? $product['label'] : [];

            return [
                'id' => $id,
                'label' => [
                    'de' => (string) ($label['de'] ?? $id),
                    'en' => (string) ($label['en'] ?? $id),
                ],
            ];
        };

        return [
            'prev' => $mapNeighbor($ids[$index - 1] ?? null),
            'next' => $mapNeighbor($ids[$index + 1] ?? null),
        ];
    }
}
