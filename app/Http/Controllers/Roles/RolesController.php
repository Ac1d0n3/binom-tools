<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RolesController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    public function index(): View
    {
        /** @var list<array<string, mixed>> $roles */
        $roles = config('roles.roles', []);

        usort($roles, static function (array $a, array $b): int {
            return ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
        });

        $cards = [];
        foreach ($roles as $role) {
            $cards[] = $this->hydrateRole($role);
        }

        return view('roles.index', [
            'roles' => $cards,
            'bridges' => $this->hydrateBridges(),
            'roleQuote' => $this->pickRoleQuote(null),
        ]);
    }

    public function show(Request $request): View|Response
    {
        $slug = (string) $request->route('slug');

        /** @var list<array<string, mixed>> $roles */
        $roles = config('roles.roles', []);

        $item = null;
        foreach ($roles as $role) {
            if (($role['id'] ?? '') === $slug) {
                $item = $role;
                break;
            }
        }

        if ($item === null) {
            abort(404);
        }

        /** @var array<string, array{de: string, en: string}> $personas */
        $personas = config('roles.personas', []);

        $hydrated = $this->hydrateRole($item);
        $roleId = (string) ($hydrated['id'] ?? '');

        return view('roles.show', [
            'item' => $hydrated,
            'personas' => $personas,
            'relatedBridges' => $this->relatedBridgesForRole($roleId),
            'roleQuote' => $this->pickRoleQuote($roleId),
        ]);
    }

    /**
     * @param  array<string, mixed>  $role
     * @return array<string, mixed>
     */
    private function hydrateRole(array $role): array
    {
        $primaryStory = $this->resolveStorySlug(
            is_string($role['storyPreferred'] ?? null) ? $role['storyPreferred'] : null,
            is_string($role['storyFallback'] ?? null) ? $role['storyFallback'] : null,
        );

        $storyLinks = [];
        if ($primaryStory !== null) {
            $defaultLabel = [
                'en' => (string) ($role['title']['en'] ?? $primaryStory),
                'de' => (string) ($role['title']['de'] ?? $role['title']['en'] ?? $primaryStory),
            ];
            $label = $this->bilingualLabel($role['storyLabel'] ?? null, $defaultLabel);
            $storyLinks[] = [
                'slug' => $primaryStory,
                'href' => locale_route('playbooks.show', ['slug' => $primaryStory]),
                'label' => $label,
                'pending' => $this->isPendingPreferred(
                    is_string($role['storyPreferred'] ?? null) ? $role['storyPreferred'] : null,
                    $primaryStory,
                ),
            ];
        }

        foreach (is_array($role['extraStories'] ?? null) ? $role['extraStories'] : [] as $extra) {
            if (! is_array($extra)) {
                continue;
            }
            $slug = $this->resolveStorySlug(
                is_string($extra['preferred'] ?? null) ? $extra['preferred'] : null,
                is_string($extra['fallback'] ?? null) ? $extra['fallback'] : null,
            );
            if ($slug === null) {
                continue;
            }
            $label = [
                'en' => (string) ($extra['label']['en'] ?? $slug),
                'de' => (string) ($extra['label']['de'] ?? $extra['label']['en'] ?? $slug),
            ];
            $storyLinks[] = [
                'slug' => $slug,
                'href' => locale_route('playbooks.show', ['slug' => $slug]),
                'label' => $label,
                'pending' => false,
            ];
        }

        $role['storyLinks'] = $storyLinks;
        $role['pathLinks'] = $this->hydratePathLinks($role);
        $role['toolLinks'] = $this->hydrateToolLinks($role);
        $role['worksWithLinks'] = $this->hydrateWorksWithLinks($role);
        $role['glossaryLink'] = $this->hydrateGlossaryLink($role);
        $role['pendingStories'] = array_values(array_filter([
            is_string($role['storyPreferred'] ?? null)
                && $this->playbooks->find((string) $role['storyPreferred']) === null
                ? (string) $role['storyPreferred']
                : null,
        ]));

        return $role;
    }

    /**
     * @param  array<string, mixed>  $role
     * @return list<array{href: string, label: array{de: string, en: string}, why: array{de: string, en: string}}>
     */
    private function hydratePathLinks(array $role): array
    {
        $entries = $this->normalizePathEntries($role);
        if ($entries === []) {
            return [];
        }

        /** @var list<array<string, mixed>> $paths */
        $paths = config('learning-paths.paths', []);
        $byId = [];
        foreach ($paths as $path) {
            if (! is_array($path)) {
                continue;
            }
            $id = (string) ($path['id'] ?? '');
            if ($id !== '') {
                $byId[$id] = $path;
            }
        }

        $links = [];
        foreach ($entries as $entry) {
            $pathId = (string) ($entry['id'] ?? '');
            if ($pathId === '' || ! isset($byId[$pathId])) {
                continue;
            }
            $path = $byId[$pathId];
            $links[] = [
                'href' => locale_route('learning-paths.show', ['slug' => $pathId]),
                'label' => [
                    'en' => (string) ($path['title']['en'] ?? $pathId),
                    'de' => (string) ($path['title']['de'] ?? $path['title']['en'] ?? $pathId),
                ],
                'why' => $this->bilingualLabel(
                    is_array($entry['why'] ?? null) ? $entry['why'] : null,
                    ['de' => '', 'en' => ''],
                ),
            ];
        }

        return $links;
    }

    /**
     * @param  array<string, mixed>  $role
     * @return list<array{id: string, why?: array{de?: string, en?: string}}>
     */
    private function normalizePathEntries(array $role): array
    {
        $pathIds = $role['pathIds'] ?? null;
        if (is_array($pathIds) && $pathIds !== []) {
            $entries = [];
            foreach ($pathIds as $entry) {
                if (is_string($entry) && $entry !== '') {
                    $entries[] = ['id' => $entry];
                    continue;
                }
                if (is_array($entry) && is_string($entry['id'] ?? null) && $entry['id'] !== '') {
                    $entries[] = $entry;
                }
            }

            return $entries;
        }

        $legacy = is_string($role['pathId'] ?? null) ? (string) $role['pathId'] : '';

        return $legacy !== '' ? [['id' => $legacy]] : [];
    }

    /**
     * @param  array<string, mixed>  $role
     * @return list<array{href: string, label: array{de: string, en: string}, why: array{de: string, en: string}}>
     */
    private function hydrateToolLinks(array $role): array
    {
        $entries = $this->normalizeToolEntries($role);
        $links = [];
        foreach ($entries as $entry) {
            $routeName = (string) ($entry['route'] ?? '');
            if ($routeName === '') {
                continue;
            }

            try {
                $href = locale_route($routeName);
            } catch (\Throwable) {
                continue;
            }

            $defaultLabel = [
                'en' => $routeName,
                'de' => $routeName,
            ];
            $links[] = [
                'href' => $href,
                'label' => $this->bilingualLabel(
                    is_array($entry['label'] ?? null) ? $entry['label'] : null,
                    $defaultLabel,
                ),
                'why' => $this->bilingualLabel(
                    is_array($entry['why'] ?? null) ? $entry['why'] : null,
                    ['de' => '', 'en' => ''],
                ),
            ];
        }

        return $links;
    }

    /**
     * @param  array<string, mixed>  $role
     * @return list<array{route: string, label?: array{de?: string, en?: string}, why?: array{de?: string, en?: string}}>
     */
    private function normalizeToolEntries(array $role): array
    {
        $tools = $role['tools'] ?? null;
        if (is_array($tools) && $tools !== []) {
            $entries = [];
            foreach ($tools as $entry) {
                if (is_string($entry) && $entry !== '') {
                    $entries[] = ['route' => $entry];
                    continue;
                }
                if (is_array($entry) && is_string($entry['route'] ?? null) && $entry['route'] !== '') {
                    $entries[] = $entry;
                }
            }

            return $entries;
        }

        $legacy = is_string($role['toolRoute'] ?? null) ? (string) $role['toolRoute'] : '';

        return $legacy !== '' ? [['route' => $legacy]] : [];
    }

    /**
     * @param  array<string, mixed>  $role
     * @return list<array{href: string, label: array{de: string, en: string}}>
     */
    private function hydrateWorksWithLinks(array $role): array
    {
        $ids = is_array($role['worksWith'] ?? null) ? $role['worksWith'] : [];
        if ($ids === []) {
            return [];
        }

        /** @var list<array<string, mixed>> $roles */
        $roles = config('roles.roles', []);
        $byId = [];
        foreach ($roles as $peer) {
            if (! is_array($peer)) {
                continue;
            }
            $id = (string) ($peer['id'] ?? '');
            if ($id !== '') {
                $byId[$id] = $peer;
            }
        }

        $selfId = (string) ($role['id'] ?? '');
        $links = [];
        foreach ($ids as $peerId) {
            if (! is_string($peerId) || $peerId === '' || $peerId === $selfId || ! isset($byId[$peerId])) {
                continue;
            }
            $peer = $byId[$peerId];
            $links[] = [
                'href' => locale_route('roles.show', ['slug' => $peerId]),
                'label' => [
                    'en' => (string) ($peer['title']['en'] ?? $peerId),
                    'de' => (string) ($peer['title']['de'] ?? $peer['title']['en'] ?? $peerId),
                ],
            ];
        }

        return $links;
    }

    /**
     * @param  array<string, mixed>  $role
     * @return array{href: string, label: array{de: string, en: string}}|null
     */
    private function hydrateGlossaryLink(array $role): ?array
    {
        $glossaryId = is_string($role['glossaryId'] ?? null) ? $role['glossaryId'] : '';
        if ($glossaryId === '') {
            return null;
        }

        return [
            'href' => locale_route('glossary.show', ['slug' => $glossaryId]),
            'label' => ['de' => 'Begriff nachschlagen', 'en' => 'Look up the term'],
        ];
    }

    private function resolveStorySlug(?string $preferred, ?string $fallback): ?string
    {
        if (is_string($preferred) && $preferred !== '' && $this->playbooks->find($preferred) !== null) {
            return $preferred;
        }
        if (is_string($fallback) && $fallback !== '' && $this->playbooks->find($fallback) !== null) {
            return $fallback;
        }

        return null;
    }

    private function isPendingPreferred(?string $preferred, string $resolved): bool
    {
        return is_string($preferred)
            && $preferred !== ''
            && $preferred !== $resolved;
    }

    /**
     * Pick a random Roles-hub quote. Role detail prefers role pool, then hub.
     *
     * @return array{quote: array{de: string, en: string}, attribution: array{de: string, en: string}}|null
     */
    private function pickRoleQuote(?string $roleId): ?array
    {
        /** @var array<string, mixed> $quotesConfig */
        $quotesConfig = config('roles.quotes', []);
        if (! is_array($quotesConfig)) {
            return null;
        }

        $pool = [];
        if (is_string($roleId) && $roleId !== '') {
            $byRole = $quotesConfig['roles'][$roleId] ?? null;
            if (is_array($byRole)) {
                $pool = array_values(array_filter($byRole, 'is_array'));
            }
        }

        if ($pool === []) {
            $hub = $quotesConfig['hub'] ?? null;
            $pool = is_array($hub) ? array_values(array_filter($hub, 'is_array')) : [];
        }

        if ($pool === []) {
            return null;
        }

        $picked = $pool[array_rand($pool)];
        if (! is_array($picked)) {
            return null;
        }

        $quoteText = $picked['quote'] ?? null;
        $attribution = $picked['attribution'] ?? null;
        if (! is_array($quoteText) || ! is_array($attribution)) {
            return null;
        }

        $quoteEn = trim((string) ($quoteText['en'] ?? ''));
        $quoteDe = trim((string) ($quoteText['de'] ?? $quoteEn));
        $attrEn = trim((string) ($attribution['en'] ?? ''));
        $attrDe = trim((string) ($attribution['de'] ?? $attrEn));

        if ($quoteEn === '' && $quoteDe === '') {
            return null;
        }

        return [
            'quote' => [
                'en' => $quoteEn !== '' ? $quoteEn : $quoteDe,
                'de' => $quoteDe !== '' ? $quoteDe : $quoteEn,
            ],
            'attribution' => [
                'en' => $attrEn,
                'de' => $attrDe !== '' ? $attrDe : $attrEn,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function hydrateBridges(): array
    {
        /** @var list<array<string, mixed>> $bridges */
        $bridges = config('roles.bridges', []);
        if ($bridges === []) {
            return [];
        }

        $roleIndex = $this->roleTitleIndex();
        $hydrated = [];

        foreach ($bridges as $bridge) {
            if (! is_array($bridge)) {
                continue;
            }

            $id = (string) ($bridge['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $spanLinks = [];
            $spanIds = is_array($bridge['spans'] ?? null) ? $bridge['spans'] : [];
            foreach ($spanIds as $spanId) {
                if (! is_string($spanId) || $spanId === '' || ! isset($roleIndex[$spanId])) {
                    continue;
                }
                $spanLinks[] = [
                    'id' => $spanId,
                    'href' => locale_route('roles.show', ['slug' => $spanId]),
                    'label' => $roleIndex[$spanId],
                ];
            }

            $hydrated[] = [
                'id' => $id,
                'kind' => (string) ($bridge['kind'] ?? 'bridge'),
                'tone' => (string) ($bridge['tone'] ?? 'recommended'),
                'title' => $this->bilingualLabel(
                    is_array($bridge['title'] ?? null) ? $bridge['title'] : null,
                    ['de' => $id, 'en' => $id],
                ),
                'lead' => $this->bilingualLabel(
                    is_array($bridge['lead'] ?? null) ? $bridge['lead'] : null,
                    ['de' => '', 'en' => ''],
                ),
                'spans' => $spanLinks,
                'when' => $this->bilingualLines($bridge['when'] ?? null),
                'keepsSeparate' => $this->bilingualLines($bridge['keepsSeparate'] ?? null),
            ];
        }

        return $hydrated;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function relatedBridgesForRole(string $roleId): array
    {
        if ($roleId === '') {
            return [];
        }

        $related = [];
        foreach ($this->hydrateBridges() as $bridge) {
            $spanIds = array_map(
                static fn (array $span): string => (string) ($span['id'] ?? ''),
                is_array($bridge['spans'] ?? null) ? $bridge['spans'] : [],
            );
            if (in_array($roleId, $spanIds, true)) {
                $related[] = $bridge;
            }
        }

        return $related;
    }

    /**
     * @return array<string, array{de: string, en: string}>
     */
    private function roleTitleIndex(): array
    {
        /** @var list<array<string, mixed>> $roles */
        $roles = config('roles.roles', []);
        $index = [];
        foreach ($roles as $role) {
            if (! is_array($role)) {
                continue;
            }
            $id = (string) ($role['id'] ?? '');
            if ($id === '') {
                continue;
            }
            $index[$id] = [
                'en' => (string) ($role['title']['en'] ?? $id),
                'de' => (string) ($role['title']['de'] ?? $role['title']['en'] ?? $id),
            ];
        }

        return $index;
    }

    /**
     * @param  mixed  $value
     * @return array{de: list<string>, en: list<string>}
     */
    private function bilingualLines(mixed $value): array
    {
        $en = [];
        $de = [];
        if (is_array($value)) {
            $enRaw = is_array($value['en'] ?? null) ? $value['en'] : [];
            $deRaw = is_array($value['de'] ?? null) ? $value['de'] : $enRaw;
            foreach ($enRaw as $line) {
                if (is_string($line) && trim($line) !== '') {
                    $en[] = trim($line);
                }
            }
            foreach ($deRaw as $line) {
                if (is_string($line) && trim($line) !== '') {
                    $de[] = trim($line);
                }
            }
            if ($de === [] && $en !== []) {
                $de = $en;
            }
            if ($en === [] && $de !== []) {
                $en = $de;
            }
        }

        return ['de' => $de, 'en' => $en];
    }

    /**
     * @param  array{de?: string, en?: string}|null  $value
     * @param  array{de: string, en: string}  $fallback
     * @return array{de: string, en: string}
     */
    private function bilingualLabel(?array $value, array $fallback): array
    {
        if ($value === null) {
            return $fallback;
        }

        $en = trim((string) ($value['en'] ?? ''));
        $de = trim((string) ($value['de'] ?? ''));
        if ($en === '') {
            $en = $de !== '' ? $de : $fallback['en'];
        }
        if ($de === '') {
            $de = $en !== '' ? $en : $fallback['de'];
        }

        return ['de' => $de, 'en' => $en];
    }
}
