<?php

namespace App\SprintPlanner;

final class SprintPlanValidator
{
    private const REQUIRED_META = [
        'type',
        'title',
        'slug',
        'description',
        'duration',
        'unit',
        'version',
        'locale',
    ];

    /**
     * @param  array<string, mixed>  $meta
     * @param  list<array<string, mixed>>  $sprints
     * @return array{errors: list<string>, warnings: list<string>}
     */
    public function validateLocaleVariant(array $meta, array $sprints): array
    {
        $errors = [];
        $warnings = [];

        foreach (self::REQUIRED_META as $field) {
            if (! array_key_exists($field, $meta) || $meta[$field] === null || $meta[$field] === '') {
                $errors[] = "Missing required frontmatter field: {$field}";
            }
        }

        if (($meta['type'] ?? null) !== 'sprint-plan') {
            $errors[] = 'Frontmatter type must be "sprint-plan".';
        }

        $sprintIds = [];
        $sprintNumbers = [];

        foreach ($sprints as $sprint) {
            $id = (string) ($sprint['id'] ?? '');
            $number = $sprint['number'] ?? null;

            if ($id === '') {
                $errors[] = 'Sprint is missing id.';

                continue;
            }

            if (isset($sprintIds[$id])) {
                $errors[] = "Duplicate sprint id: {$id}";
            }
            $sprintIds[$id] = true;

            if ($number === null) {
                $errors[] = "Sprint {$id} is missing number.";
            } elseif (isset($sprintNumbers[$number])) {
                $errors[] = "Duplicate sprint number: {$number}";
            } else {
                $sprintNumbers[$number] = true;
            }

            $taskIds = [];
            foreach ($sprint['tasks'] ?? [] as $task) {
                $taskId = (string) ($task['id'] ?? '');
                if ($taskId === '') {
                    $errors[] = "Sprint {$id} has a task without id.";
                } elseif (isset($taskIds[$taskId])) {
                    $errors[] = "Sprint {$id} has duplicate task id: {$taskId}";
                } else {
                    $taskIds[$taskId] = true;
                }
            }

            $deliverableIds = [];
            foreach ($sprint['deliverables'] ?? [] as $deliverable) {
                $deliverableId = (string) ($deliverable['id'] ?? '');
                if ($deliverableId === '') {
                    $errors[] = "Sprint {$id} has a deliverable without id.";
                } elseif (isset($deliverableIds[$deliverableId])) {
                    $errors[] = "Sprint {$id} has duplicate deliverable id: {$deliverableId}";
                } else {
                    $deliverableIds[$deliverableId] = true;
                }
            }

            $fieldIds = [];
            foreach ($sprint['fields'] ?? [] as $field) {
                $fieldId = (string) ($field['id'] ?? '');
                if ($fieldId === '') {
                    $errors[] = "Sprint {$id} has a field without id.";
                } elseif (isset($fieldIds[$fieldId])) {
                    $errors[] = "Sprint {$id} has duplicate field id: {$fieldId}";
                } else {
                    $fieldIds[$fieldId] = true;
                }
            }
        }

        $duration = (int) ($meta['duration'] ?? 0);
        $sprintCount = count($sprints);
        if ($duration > 0 && $sprintCount > 0 && $duration !== $sprintCount) {
            $warnings[] = "Duration ({$duration}) does not match sprint count ({$sprintCount}).";
        }

        return [
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Compare structural IDs between DE and EN variants.
     *
     * @param  list<array<string, mixed>>  $deSprints
     * @param  list<array<string, mixed>>  $enSprints
     * @param  array<string, mixed>  $deMeta
     * @param  array<string, mixed>  $enMeta
     * @return list<string>
     */
    public function compareLocaleStructures(
        array $deSprints,
        array $enSprints,
        array $deMeta,
        array $enMeta,
    ): array {
        $errors = [];

        if (($deMeta['slug'] ?? null) !== ($enMeta['slug'] ?? null)) {
            $errors[] = 'DE and EN templates must share the same slug.';
        }

        if ((int) ($deMeta['version'] ?? 0) !== (int) ($enMeta['version'] ?? 0)) {
            $errors[] = 'DE and EN templates must share the same version.';
        }

        $deStructure = $this->structureSignature($deSprints);
        $enStructure = $this->structureSignature($enSprints);

        if ($deStructure !== $enStructure) {
            $errors[] = 'DE and EN templates must use identical structural IDs (sprints, tasks, deliverables, fields).';
            $errors[] = 'DE signature: '.$deStructure;
            $errors[] = 'EN signature: '.$enStructure;
        }

        return $errors;
    }

    /**
     * @param  list<array<string, mixed>>  $sprints
     */
    private function structureSignature(array $sprints): string
    {
        $parts = [];

        usort($sprints, static fn (array $a, array $b): int => ((int) ($a['number'] ?? 0)) <=> ((int) ($b['number'] ?? 0)));

        foreach ($sprints as $sprint) {
            $taskIds = array_map(static fn (array $t): string => (string) ($t['id'] ?? ''), $sprint['tasks'] ?? []);
            $deliverableIds = array_map(static fn (array $d): string => (string) ($d['id'] ?? ''), $sprint['deliverables'] ?? []);
            $fieldIds = array_map(static fn (array $f): string => (string) ($f['id'] ?? ''), $sprint['fields'] ?? []);
            sort($taskIds);
            sort($deliverableIds);
            sort($fieldIds);

            $parts[] = sprintf(
                '%s#%s[t:%s|d:%s|f:%s]',
                $sprint['id'] ?? '',
                $sprint['number'] ?? '',
                implode(',', $taskIds),
                implode(',', $deliverableIds),
                implode(',', $fieldIds),
            );
        }

        return implode(';', $parts);
    }
}
