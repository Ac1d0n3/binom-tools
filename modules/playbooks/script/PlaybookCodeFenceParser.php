<?php

namespace App\Playbooks;

final class PlaybookCodeFenceParser
{
    /**
     * @return array{language: string, title: ?string, highlight: ?string}
     */
    public static function parse(string $info): array
    {
        $language = 'text';
        $title = null;
        $highlight = null;

        if ($info === '') {
            return compact('language', 'title', 'highlight');
        }

        if (preg_match('/\{([^}]+)\}/', $info, $matches)) {
            $highlight = trim($matches[1]);
            $info = trim(str_replace($matches[0], '', $info));
        }

        if (preg_match('/title="([^"]*)"/', $info, $matches)) {
            $title = $matches[1];
            $info = trim(str_replace($matches[0], '', $info));
        } elseif (preg_match("/title='([^']*)'/", $info, $matches)) {
            $title = $matches[1];
            $info = trim(str_replace($matches[0], '', $info));
        }

        $language = self::normalizeLanguage(trim(explode(' ', $info)[0] ?? 'text'));

        return compact('language', 'title', 'highlight');
    }

    public static function normalizeLanguage(string $language): string
    {
        return match (strtolower($language)) {
            'html' => 'markup',
            'xml' => 'markup',
            'ts' => 'typescript',
            'sh', 'shell' => 'bash',
            'yml' => 'yaml',
            'md' => 'markdown',
            'env' => 'properties',
            default => $language !== '' ? strtolower($language) : 'text',
        };
    }
}
