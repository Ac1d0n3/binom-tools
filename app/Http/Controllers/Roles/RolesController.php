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

        return view('roles.show', [
            'item' => $this->hydrateRole($item),
            'personas' => $personas,
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
            $storyLinks[] = [
                'slug' => $primaryStory,
                'href' => locale_route('playbooks.show', ['slug' => $primaryStory]),
                'label' => [
                    'en' => (string) ($role['title']['en'] ?? $primaryStory).' story',
                    'de' => (string) ($role['title']['de'] ?? $role['title']['en'] ?? $primaryStory).'-Story',
                ],
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

        $glossaryId = is_string($role['glossaryId'] ?? null) ? $role['glossaryId'] : '';
        $pathId = is_string($role['pathId'] ?? null) ? $role['pathId'] : '';
        $toolRoute = is_string($role['toolRoute'] ?? null) ? $role['toolRoute'] : '';

        $links = [];
        if ($glossaryId !== '') {
            $links[] = [
                'kind' => 'glossary',
                'href' => locale_route('glossary.show', ['slug' => $glossaryId]),
                'label' => ['de' => 'Glossary', 'en' => 'Glossary'],
            ];
        }
        if ($pathId !== '') {
            $links[] = [
                'kind' => 'path',
                'href' => locale_route('learning-paths.show', ['slug' => $pathId]),
                'label' => ['de' => 'Learning Path', 'en' => 'Learning path'],
            ];
        }
        if ($toolRoute !== '') {
            $links[] = [
                'kind' => 'tool',
                'href' => locale_route($toolRoute),
                'label' => ['de' => 'Tool', 'en' => 'Tool'],
            ];
        }

        $role['storyLinks'] = $storyLinks;
        $role['hubLinks'] = $links;
        $role['pendingStories'] = array_values(array_filter([
            is_string($role['storyPreferred'] ?? null)
                && $this->playbooks->find((string) $role['storyPreferred']) === null
                ? (string) $role['storyPreferred']
                : null,
        ]));

        return $role;
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
}
