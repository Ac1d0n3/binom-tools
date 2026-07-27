<?php

namespace App\Accounts\Database;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\Contracts\GlossaryQuizResultStoreInterface;
use App\Models\BnTools\BnGlossaryQuizResult;
use InvalidArgumentException;

final class DatabaseGlossaryQuizResultStore implements GlossaryQuizResultStoreInterface
{
    private const MAX_ATTEMPTS = 50;

    public function __construct(
        private readonly AccountsConfig $config,
    ) {}

    public function resultsDirectory(): string
    {
        return $this->config->glossaryQuizDirectory();
    }

    public function loadFor(AccountUser $user): array
    {
        $row = BnGlossaryQuizResult::query()->find($user->id);
        if ($row === null) {
            return $this->emptyPayload();
        }

        $data = is_array($row->payload) ? $row->payload : [];
        if (($data['ownerUserId'] ?? null) !== $user->id && ! ($user->canManageUsers ?? false)) {
            return $this->emptyPayload();
        }

        return $this->normalize($data, $user->id);
    }

    public function recordAttempt(AccountUser $user, int $score, int $total, string $mode = 'mixed'): array
    {
        if ($user->id === '') {
            throw new InvalidArgumentException('Invalid user id.');
        }
        if ($total < 1 || $score < 0 || $score > $total) {
            throw new InvalidArgumentException('Invalid quiz score.');
        }

        $current = $this->loadFor($user);
        $attempt = [
            'at' => gmdate('c'),
            'score' => $score,
            'total' => $total,
            'mode' => $mode !== '' ? $mode : 'mixed',
        ];

        $attempts = $current['attempts'];
        $attempts[] = $attempt;
        if (count($attempts) > self::MAX_ATTEMPTS) {
            $attempts = array_slice($attempts, -self::MAX_ATTEMPTS);
        }

        $bestScore = (int) ($current['bestScore'] ?? 0);
        $bestTotal = (int) ($current['bestTotal'] ?? 0);
        $isBetter = $score > $bestScore
            || ($score === $bestScore && $total >= $bestTotal && $bestTotal > 0)
            || $bestTotal === 0;
        if ($isBetter) {
            $bestScore = $score;
            $bestTotal = $total;
        }

        $payload = [
            'ownerUserId' => $user->id,
            'updatedAt' => gmdate('c'),
            'attempts' => array_values($attempts),
            'bestScore' => $bestScore,
            'bestTotal' => $bestTotal,
            'attemptCount' => (int) ($current['attemptCount'] ?? 0) + 1,
        ];

        BnGlossaryQuizResult::query()->updateOrCreate(
            ['owner_user_id' => $user->id],
            ['payload' => $payload],
        );

        return $payload;
    }

    /**
     * @return array{attempts: list<array{at: string, score: int, total: int, mode?: string}>, bestScore: int, bestTotal: int, attemptCount: int}
     */
    private function emptyPayload(): array
    {
        return [
            'attempts' => [],
            'bestScore' => 0,
            'bestTotal' => 0,
            'attemptCount' => 0,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{attempts: list<array{at: string, score: int, total: int, mode?: string}>, bestScore: int, bestTotal: int, attemptCount: int, ownerUserId?: string, updatedAt?: string}
     */
    private function normalize(array $data, string $userId): array
    {
        $attempts = [];
        foreach ($data['attempts'] ?? [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $attempts[] = [
                'at' => (string) ($row['at'] ?? ''),
                'score' => (int) ($row['score'] ?? 0),
                'total' => (int) ($row['total'] ?? 0),
                'mode' => (string) ($row['mode'] ?? 'mixed'),
            ];
        }

        return [
            'attempts' => $attempts,
            'bestScore' => (int) ($data['bestScore'] ?? 0),
            'bestTotal' => (int) ($data['bestTotal'] ?? 0),
            'attemptCount' => (int) ($data['attemptCount'] ?? count($attempts)),
            'ownerUserId' => $userId,
            'updatedAt' => (string) ($data['updatedAt'] ?? ''),
        ];
    }
}
