<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

/**
 * Parses {@see SassListsVocabulary} variables from {@code _shared/_lists.scss}.
 */
final class SassListsScssParser
{
    /**
     * @return array<string, list<string>|array<string, string>|int>
     */
    public function parseFile(string $path): array
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('Sass lists file not readable: %s', $path));
        }

        return $this->parseSource((string) file_get_contents($path));
    }

    /**
     * @return array<string, list<string>|array<string, string>|int>
     */
    public function parseSource(string $scss): array
    {
        $scss = preg_replace('/\/\/.*$/m', '', $scss) ?? $scss;
        $scss = preg_replace('/\/\*.*?\*\//s', '', $scss) ?? $scss;

        $lists = [];

        if (!preg_match_all(
            '/\$(ui-[a-z0-9-]+)\s*:\s*([^;]+?)\s*!default\s*;/',
            $scss,
            $matches,
            \PREG_SET_ORDER,
        )) {
            return [];
        }

        foreach ($matches as $match) {
            $name = $match[1];
            $lists[$name] = $this->parseValue(trim($match[2]));
        }

        return $lists;
    }

    /**
     * @return list<string>|array<string, string>|int
     */
    private function parseValue(string $raw): array|int
    {
        if (preg_match('/^\(\s*(.*)\s*\)$/s', $raw, $mapMatch)) {
            $map = [];
            foreach (explode(',', $mapMatch[1]) as $pair) {
                $pair = trim($pair);
                if ($pair === '' || !str_contains($pair, ':')) {
                    continue;
                }

                [$key, $value] = array_map(trim(...), explode(':', $pair, 2));
                $map[$key] = $value;
            }

            ksort($map);

            return $map;
        }

        if (preg_match('/^-?\d+$/', $raw)) {
            return (int) $raw;
        }

        $items = [];
        foreach (explode(',', $raw) as $item) {
            $item = trim($item);
            if ($item !== '') {
                $items[] = $item;
            }
        }

        return $items;
    }
}
