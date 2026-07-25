<?php

namespace App\Accounts\Contracts;

interface ReadStateStoreInterface
{
    /**
     * @return array<string, int>
     */
    public function forUser(string $userId): array;

    public function markRead(string $userId, string $slug): void;

    public function clear(string $userId): void;

    public function isRead(string $userId, string $slug): bool;
}
