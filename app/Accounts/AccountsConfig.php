<?php

namespace App\Accounts;

final class AccountsConfig
{
    public function enabled(): bool
    {
        return (bool) config('accounts.enabled', false);
    }

    public function basePath(): string
    {
        $path = (string) config('accounts.path', storage_path('app/bn-tools'));

        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    public function usersPath(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'users.json';
    }

    public function teamsPath(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'teams.json';
    }

    public function storyAclPath(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'story-acl.json';
    }

    public function plansDirectory(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'plans';
    }

    public function readStateDirectory(): string
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'read-state';
    }
}
