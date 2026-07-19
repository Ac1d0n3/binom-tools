<?php

namespace App\SprintPlanner;

final class SprintPlan
{
    /**
     * @param  array{
     *   slug: string,
     *   version: int,
     *   duration: int,
     *   unit: string,
     *   category: ?string,
     *   author: ?string,
     *   tags: list<string>,
     *   locales: array<string, array<string, mixed>>,
     *   sprints: list<array<string, mixed>>,
     *   errors: list<string>,
     *   warnings: list<string>
     * }  $data
     */
    public function __construct(private readonly array $data) {}

    public function slug(): string
    {
        return $this->data['slug'];
    }

    public function version(): int
    {
        return $this->data['version'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toIndexArray(): array
    {
        return [
            'slug' => $this->data['slug'],
            'version' => $this->data['version'],
            'duration' => $this->data['duration'],
            'unit' => $this->data['unit'],
            'category' => $this->data['category'],
            'author' => $this->data['author'],
            'tags' => $this->data['tags'],
            'sprintCount' => count($this->data['sprints']),
            'locales' => [
                'de' => [
                    'title' => $this->data['locales']['de']['title'] ?? '',
                    'description' => $this->data['locales']['de']['description'] ?? '',
                ],
                'en' => [
                    'title' => $this->data['locales']['en']['title'] ?? '',
                    'description' => $this->data['locales']['en']['description'] ?? '',
                ],
            ],
            'errors' => $this->data['errors'],
            'warnings' => $this->data['warnings'],
        ];
    }

    /**
     * Full template payload for the client (both locales for instance start).
     *
     * @return array<string, mixed>
     */
    public function toClientArray(): array
    {
        return [
            'slug' => $this->data['slug'],
            'version' => $this->data['version'],
            'duration' => $this->data['duration'],
            'unit' => $this->data['unit'],
            'category' => $this->data['category'],
            'author' => $this->data['author'],
            'tags' => $this->data['tags'],
            'locales' => $this->data['locales'],
            'sprints' => $this->data['sprints'],
            'errors' => $this->data['errors'],
            'warnings' => $this->data['warnings'],
        ];
    }

    public function hasErrors(): bool
    {
        return $this->data['errors'] !== [];
    }
}
