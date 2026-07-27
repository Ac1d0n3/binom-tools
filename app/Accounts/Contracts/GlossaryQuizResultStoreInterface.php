<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountUser;

interface GlossaryQuizResultStoreInterface
{
    public function resultsDirectory(): string;

    /**
     * @return array{
     *     attempts: list<array{at: string, score: int, total: int, mode?: string}>,
     *     bestScore: int,
     *     bestTotal: int,
     *     attemptCount: int,
     *     ownerUserId?: string,
     *     updatedAt?: string
     * }
     */
    public function loadFor(AccountUser $user): array;

    /**
     * @return array{
     *     attempts: list<array{at: string, score: int, total: int, mode?: string}>,
     *     bestScore: int,
     *     bestTotal: int,
     *     attemptCount: int,
     *     ownerUserId: string,
     *     updatedAt: string
     * }
     */
    public function recordAttempt(AccountUser $user, int $score, int $total, string $mode = 'mixed'): array;
}
