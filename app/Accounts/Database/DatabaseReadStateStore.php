<?php

namespace App\Accounts\Database;

use App\Accounts\Contracts\ReadStateStoreInterface;
use App\Models\BnTools\BnReadState;
use Illuminate\Support\Facades\DB;

final class DatabaseReadStateStore implements ReadStateStoreInterface
{
    public function forUser(string $userId): array
    {
        $out = [];
        foreach (BnReadState::query()->where('user_id', $userId)->get() as $row) {
            $out[(string) $row->slug] = (int) $row->read_at;
        }

        return $out;
    }

    public function markRead(string $userId, string $slug): void
    {
        $exists = BnReadState::query()
            ->where('user_id', $userId)
            ->where('slug', $slug)
            ->exists();
        if ($exists) {
            return;
        }

        DB::table('bn_read_state')->insert([
            'user_id' => $userId,
            'slug' => $slug,
            'read_at' => time(),
        ]);
    }

    public function clear(string $userId): void
    {
        BnReadState::query()->where('user_id', $userId)->delete();
    }

    public function isRead(string $userId, string $slug): bool
    {
        return BnReadState::query()
            ->where('user_id', $userId)
            ->where('slug', $slug)
            ->exists();
    }
}
