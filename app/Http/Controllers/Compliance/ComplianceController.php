<?php

namespace App\Http\Controllers\Compliance;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplianceController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    public function index(): View
    {
        /** @var array<string, array{de: string, en: string}> $categories */
        $categories = config('compliance.categories', []);

        /** @var array<string, array{de: string, en: string}> $regions */
        $regions = config('compliance.regions', []);

        /** @var array<string, array{de: string, en: string}> $types */
        $types = config('compliance.types', []);

        /** @var list<array<string, mixed>> $items */
        $items = config('compliance.items', []);

        usort($items, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        $availableCategories = [];
        $availableRegions = [];
        foreach ($items as $item) {
            $category = is_string($item['category'] ?? null) ? $item['category'] : '';
            if ($category !== '' && isset($categories[$category]) && ! in_array($category, $availableCategories, true)) {
                $availableCategories[] = $category;
            }

            $region = is_string($item['region'] ?? null) ? $item['region'] : '';
            if ($region !== '' && isset($regions[$region]) && ! in_array($region, $availableRegions, true)) {
                $availableRegions[] = $region;
            }
        }

        $categoryOrder = array_keys($categories);
        usort($availableCategories, static function (string $a, string $b) use ($categoryOrder): int {
            $posA = array_search($a, $categoryOrder, true);
            $posB = array_search($b, $categoryOrder, true);

            return ($posA === false ? PHP_INT_MAX : $posA) <=> ($posB === false ? PHP_INT_MAX : $posB);
        });

        $regionOrder = array_keys($regions);
        usort($availableRegions, static function (string $a, string $b) use ($regionOrder): int {
            $posA = array_search($a, $regionOrder, true);
            $posB = array_search($b, $regionOrder, true);

            return ($posA === false ? PHP_INT_MAX : $posA) <=> ($posB === false ? PHP_INT_MAX : $posB);
        });

        return view('compliance.index', [
            'items' => $items,
            'categories' => $categories,
            'regions' => $regions,
            'types' => $types,
            'availableCategories' => $availableCategories,
            'availableRegions' => $availableRegions,
        ]);
    }

    public function roadmap(): View
    {
        /** @var array<string, array{order?: int, label: array{de: string, en: string}, lead: array{de: string, en: string}}> $phases */
        $phases = config('compliance.roadmapPhases', []);

        /** @var array<string, array{de: string, en: string}> $priorities */
        $priorities = config('compliance.roadmapPriorities', []);

        /** @var array<string, array{de: string, en: string}> $focusRegions */
        $focusRegions = config('compliance.roadmapFocusRegions', []);

        /** @var list<array<string, mixed>> $certifications */
        $certifications = config('compliance.certifications', []);

        /** @var array{de: list<string>, en: list<string>} $tips */
        $tips = config('compliance.roadmapTips', ['de' => [], 'en' => []]);

        usort($certifications, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        uasort($phases, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        $certsByPhase = [];
        foreach (array_keys($phases) as $phaseId) {
            $certsByPhase[$phaseId] = [];
        }
        foreach ($certifications as $cert) {
            $phaseId = is_string($cert['phase'] ?? null) ? $cert['phase'] : '';
            if ($phaseId === '' || ! isset($certsByPhase[$phaseId])) {
                continue;
            }
            $certsByPhase[$phaseId][] = $cert;
        }

        $availableFocusRegions = [];
        foreach ($certifications as $cert) {
            $regions = is_array($cert['focusRegions'] ?? null) ? $cert['focusRegions'] : [];
            foreach ($regions as $regionId) {
                if (! is_string($regionId) || $regionId === '' || ! isset($focusRegions[$regionId])) {
                    continue;
                }
                if (! in_array($regionId, $availableFocusRegions, true)) {
                    $availableFocusRegions[] = $regionId;
                }
            }
        }

        $regionOrder = array_keys($focusRegions);
        usort($availableFocusRegions, static function (string $a, string $b) use ($regionOrder): int {
            $posA = array_search($a, $regionOrder, true);
            $posB = array_search($b, $regionOrder, true);

            return ($posA === false ? PHP_INT_MAX : $posA) <=> ($posB === false ? PHP_INT_MAX : $posB);
        });

        $frameworkLabels = [];
        /** @var list<array<string, mixed>> $frameworks */
        $frameworks = config('compliance.items', []);
        foreach ($frameworks as $framework) {
            $id = is_string($framework['id'] ?? null) ? $framework['id'] : '';
            if ($id === '') {
                continue;
            }
            $frameworkLabels[$id] = [
                'de' => $framework['label']['de'] ?? $id,
                'en' => $framework['label']['en'] ?? $id,
            ];
        }

        $playbookTitles = [];
        foreach ($certifications as $cert) {
            $slugs = is_array($cert['relatedPlaybooks'] ?? null) ? $cert['relatedPlaybooks'] : [];
            foreach ($this->resolveRelatedPlaybooks($slugs) as $related) {
                $playbookTitles[$related['slug']] = $related;
            }
        }

        return view('compliance.roadmap', [
            'phases' => $phases,
            'priorities' => $priorities,
            'focusRegions' => $focusRegions,
            'availableFocusRegions' => $availableFocusRegions,
            'certsByPhase' => $certsByPhase,
            'tips' => $tips,
            'frameworkLabels' => $frameworkLabels,
            'playbookTitles' => $playbookTitles,
        ]);
    }

    public function show(Request $request): View
    {
        $slug = (string) $request->route('slug');

        $item = $this->findItem($slug);
        abort_if($item === null, 404);

        /** @var array<string, array{de: string, en: string}> $categories */
        $categories = config('compliance.categories', []);

        /** @var array<string, array{de: string, en: string}> $regions */
        $regions = config('compliance.regions', []);

        /** @var array<string, array{de: string, en: string}> $types */
        $types = config('compliance.types', []);

        $relatedPlaybooks = $this->resolveRelatedPlaybooks(
            is_array($item['relatedPlaybooks'] ?? null) ? $item['relatedPlaybooks'] : []
        );

        $neighbors = $this->neighbors($slug);

        return view('compliance.show', [
            'item' => $item,
            'categories' => $categories,
            'regions' => $regions,
            'types' => $types,
            'relatedPlaybooks' => $relatedPlaybooks,
            'prev' => $neighbors['prev'],
            'next' => $neighbors['next'],
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findItem(string $slug): ?array
    {
        /** @var list<array<string, mixed>> $items */
        $items = config('compliance.items', []);

        foreach ($items as $item) {
            $id = is_string($item['id'] ?? null) ? $item['id'] : '';
            if ($id === $slug) {
                return $item;
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
     * @return array{prev: ?array<string, mixed>, next: ?array<string, mixed>}
     */
    private function neighbors(string $slug): array
    {
        /** @var list<array<string, mixed>> $items */
        $items = config('compliance.items', []);
        usort($items, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        $index = null;
        foreach ($items as $i => $item) {
            if (($item['id'] ?? null) === $slug) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return ['prev' => null, 'next' => null];
        }

        return [
            'prev' => $index > 0 ? $items[$index - 1] : null,
            'next' => $index < count($items) - 1 ? $items[$index + 1] : null,
        ];
    }
}
