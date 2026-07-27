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
     *   roadmapFamily: ?string,
     *   roadmapTitle: ?string,
     *   roadmapTrack: ?string,
     *   roadmapTrackTitle: ?string,
     *   roadmapPhase: ?int,
     *   roadmapOption: ?string,
     *   roadmapFollows: list<string>,
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
            'recommendedPeopleMin' => $this->data['recommendedPeopleMin'],
            'recommendedPeopleMax' => $this->data['recommendedPeopleMax'],
            'capacityHoursPerPersonWeek' => $this->data['capacityHoursPerPersonWeek'],
            'roadmapFamily' => $this->data['roadmapFamily'],
            'roadmapTitle' => $this->data['roadmapTitle'],
            'roadmapTrack' => $this->data['roadmapTrack'],
            'roadmapTrackTitle' => $this->data['roadmapTrackTitle'],
            'roadmapPhase' => $this->data['roadmapPhase'],
            'roadmapOption' => $this->data['roadmapOption'],
            'roadmapFollows' => $this->data['roadmapFollows'],
            'tags' => $this->data['tags'],
            'sprintCount' => count($this->data['sprints']),
            'locales' => [
                'de' => [
                    'title' => $this->data['locales']['de']['title'] ?? '',
                    'description' => $this->data['locales']['de']['description'] ?? '',
                    'roadmapTitle' => $this->data['locales']['de']['roadmapTitle'] ?? '',
                    'roadmapTrackTitle' => $this->data['locales']['de']['roadmapTrackTitle'] ?? '',
                    'roadmapOption' => $this->data['locales']['de']['roadmapOption'] ?? '',
                ],
                'en' => [
                    'title' => $this->data['locales']['en']['title'] ?? '',
                    'description' => $this->data['locales']['en']['description'] ?? '',
                    'roadmapTitle' => $this->data['locales']['en']['roadmapTitle'] ?? '',
                    'roadmapTrackTitle' => $this->data['locales']['en']['roadmapTrackTitle'] ?? '',
                    'roadmapOption' => $this->data['locales']['en']['roadmapOption'] ?? '',
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
            'recommendedPeopleMin' => $this->data['recommendedPeopleMin'],
            'recommendedPeopleMax' => $this->data['recommendedPeopleMax'],
            'capacityHoursPerPersonWeek' => $this->data['capacityHoursPerPersonWeek'],
            'roadmapFamily' => $this->data['roadmapFamily'],
            'roadmapTitle' => $this->data['roadmapTitle'],
            'roadmapTrack' => $this->data['roadmapTrack'],
            'roadmapTrackTitle' => $this->data['roadmapTrackTitle'],
            'roadmapPhase' => $this->data['roadmapPhase'],
            'roadmapOption' => $this->data['roadmapOption'],
            'roadmapFollows' => $this->data['roadmapFollows'],
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
