<?php

namespace App\Http\Controllers\LearningPaths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LearningPathController extends Controller
{
    public function index(): View
    {
        /** @var array<string, array{de: string, en: string}> $audiences */
        $audiences = config('learning-paths.audiences', []);

        /** @var list<array<string, mixed>> $paths */
        $paths = config('learning-paths.paths', []);

        usort($paths, static function (array $a, array $b): int {
            $order = ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
            if ($order !== 0) {
                return $order;
            }

            return strcasecmp((string) ($a['title']['en'] ?? $a['id'] ?? ''), (string) ($b['title']['en'] ?? $b['id'] ?? ''));
        });

        $availableAudiences = [];
        foreach ($paths as $path) {
            $audience = is_string($path['audienceId'] ?? null) ? $path['audienceId'] : '';
            if ($audience !== '' && isset($audiences[$audience]) && ! in_array($audience, $availableAudiences, true)) {
                $availableAudiences[] = $audience;
            }
        }

        return view('learning-paths.index', [
            'paths' => $paths,
            'audiences' => $audiences,
            'availableAudiences' => $availableAudiences,
        ]);
    }

    public function show(Request $request): View|Response
    {
        $slug = (string) $request->route('slug');

        /** @var list<array<string, mixed>> $paths */
        $paths = config('learning-paths.paths', []);

        $item = null;
        foreach ($paths as $path) {
            if (($path['id'] ?? '') === $slug) {
                $item = $path;
                break;
            }
        }

        if ($item === null) {
            abort(404);
        }

        /** @var array<string, array{de: string, en: string}> $audiences */
        $audiences = config('learning-paths.audiences', []);

        $steps = [];
        foreach (is_array($item['steps'] ?? null) ? $item['steps'] : [] as $step) {
            if (! is_array($step)) {
                continue;
            }

            $steps[] = [
                'title' => [
                    'en' => (string) ($step['title']['en'] ?? ''),
                    'de' => (string) ($step['title']['de'] ?? $step['title']['en'] ?? ''),
                ],
                'lead' => [
                    'en' => (string) ($step['lead']['en'] ?? ''),
                    'de' => (string) ($step['lead']['de'] ?? $step['lead']['en'] ?? ''),
                ],
                'links' => $this->resolveRelatedLinks(is_array($step['links'] ?? null) ? $step['links'] : []),
            ];
        }

        return view('learning-paths.show', [
            'item' => $item,
            'audiences' => $audiences,
            'steps' => $steps,
            'sprintPlanHref' => $this->sprintPlanHref($item),
        ]);
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function sprintPlanHref(array $item): ?string
    {
        $slug = trim((string) ($item['sprintTemplateSlug'] ?? ''));
        if ($slug === '') {
            return null;
        }

        return locale_route('sprint-planner.templates').'?start='.rawurlencode($slug);
    }

    /**
     * @param  list<array<string, mixed>>  $related
     * @return list<array{label: array{de: string, en: string}, href: string, kind: string}>
     */
    private function resolveRelatedLinks(array $related): array
    {
        $links = [];
        foreach ($related as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $kind = (string) ($entry['type'] ?? '');
            $id = (string) ($entry['id'] ?? '');
            $label = [
                'en' => (string) ($entry['label']['en'] ?? $entry['label'] ?? $id),
                'de' => (string) ($entry['label']['de'] ?? $entry['label']['en'] ?? $entry['label'] ?? $id),
            ];

            $href = match ($kind) {
                'story' => $id !== '' ? locale_route('playbooks.show', ['slug' => $id]) : null,
                'series' => $id !== '' ? locale_route('playbooks.series', ['seriesId' => $id]) : null,
                'tool' => is_string($entry['route'] ?? null) ? locale_route((string) $entry['route']) : null,
                'compliance' => $id !== '' ? locale_route('compliance.show', ['slug' => $id]) : null,
                'glossary' => $id !== '' ? locale_route('glossary.show', ['slug' => $id]) : null,
                'path' => $id !== '' ? locale_route('learning-paths.show', ['slug' => $id]) : null,
                'route' => is_string($entry['route'] ?? null) ? locale_route((string) $entry['route']) : null,
                default => null,
            };

            if (! is_string($href) || $href === '') {
                continue;
            }

            $links[] = [
                'label' => $label,
                'href' => $href,
                'kind' => $kind !== '' ? $kind : 'link',
            ];
        }

        return $links;
    }
}
