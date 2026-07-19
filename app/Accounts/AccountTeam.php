<?php

namespace App\Accounts;

final class AccountTeam
{
    /**
     * @param  array{de: string, en: string}  $name
     * @param  array{de: string, en: string}  $description
     * @param  list<string>  $memberIds
     */
    public function __construct(
        public readonly string $id,
        public readonly array $name,
        public readonly array $description,
        public readonly array $memberIds,
        public readonly bool $archived = false,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $id = (string) ($data['id'] ?? '');
        if ($id === '') {
            throw new \InvalidArgumentException('Team is missing id.');
        }

        $name = $data['name'] ?? [];
        if (is_string($name)) {
            $name = ['de' => $name, 'en' => $name];
        }
        if (! is_array($name)) {
            $name = ['de' => $id, 'en' => $id];
        }

        $description = $data['description'] ?? ['de' => '', 'en' => ''];
        if (is_string($description)) {
            $description = ['de' => $description, 'en' => $description];
        }
        if (! is_array($description)) {
            $description = ['de' => '', 'en' => ''];
        }

        $memberIds = [];
        if (isset($data['memberIds']) && is_array($data['memberIds'])) {
            $memberIds = array_values(array_map('strval', $data['memberIds']));
        }

        return new self(
            id: $id,
            name: [
                'de' => (string) ($name['de'] ?? $name['en'] ?? $id),
                'en' => (string) ($name['en'] ?? $name['de'] ?? $id),
            ],
            description: [
                'de' => (string) ($description['de'] ?? ''),
                'en' => (string) ($description['en'] ?? ''),
            ],
            memberIds: $memberIds,
            archived: (bool) ($data['archived'] ?? false),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'memberIds' => $this->memberIds,
            'archived' => $this->archived,
        ];
    }
}
