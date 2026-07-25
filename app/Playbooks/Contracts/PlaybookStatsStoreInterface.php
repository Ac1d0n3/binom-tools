<?php

namespace App\Playbooks\Contracts;

interface PlaybookStatsStoreInterface
{
    /**
     * @return array{views: int, likes: int}
     */
    public function get(string $slug): array;

    /**
     * @param  list<string>  $slugs
     * @return array<string, array{views: int, likes: int}>
     */
    public function getMany(array $slugs): array;

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public function attachToItems(array $items): array;

    /**
     * @return array{views: int, likes: int}
     */
    public function set(string $slug, int $views, int $likes): array;

    /**
     * @return array{views: int, likes: int}
     */
    public function incrementView(string $slug): array;

    /**
     * @return array{views: int, likes: int, liked: bool}
     */
    public function like(string $slug): array;

    /**
     * @return array{views: int, likes: int, liked: bool}
     */
    public function unlike(string $slug): array;
}
