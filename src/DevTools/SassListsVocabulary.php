<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

/**
 * Canonical Sass list vocabulary (115 v2) — PHP export for drift detection.
 *
 * @phpstan-type SassListValue list<string>|array<string, string>|int
 * @phpstan-type SassLists array<string, SassListValue>
 */
final class SassListsVocabulary
{
    /**
     * @return SassLists
     */
    public static function canonical(): array
    {
        return [
            'ui-semantic-colours' => [
                'primary',
                'secondary',
                'accent',
                'success',
                'danger',
                'info',
                'warning',
                'neutral',
            ],
            'ui-appearances' => ['solid', 'soft', 'outline', 'ghost', 'link'],
            'ui-control-sizes' => ['sm', 'md', 'lg'],
            'ui-variant-tone-aliases' => [
                'destructive' => 'danger',
                'error' => 'danger',
            ],
            'ui-placements-y' => ['top', 'bottom'],
            'ui-stack-max-items' => 10,
            'ui-layout-button' => ['icon-only', 'block'],
            'ui-density' => ['default', 'compact'],
        ];
    }

    /** @deprecated 115 — use {@see canonical()} */
    /** @return SassLists */
    public static function canonicalPre115(): array
    {
        return self::canonical();
    }

    /**
     * @param SassLists $lists
     */
    public static function fingerprint(array $lists): string
    {
        return hash('sha256', json_encode(self::normalize($lists), \JSON_THROW_ON_ERROR));
    }

    /**
     * @param SassLists $expected
     * @param SassLists $actual
     *
     * @return list<string> human-readable drift messages
     */
    public static function diff(array $expected, array $actual): array
    {
        $messages = [];
        $allKeys = array_unique([...array_keys($expected), ...array_keys($actual)]);
        sort($allKeys);

        foreach ($allKeys as $key) {
            $exp = $expected[$key] ?? null;
            $got = $actual[$key] ?? null;

            if ($exp === null) {
                $messages[] = sprintf('unexpected list $%s in _lists.scss', $key);

                continue;
            }

            if ($got === null) {
                $messages[] = sprintf('missing list $%s in _lists.scss', $key);

                continue;
            }

            if ($exp !== $got) {
                $messages[] = sprintf(
                    'drift on $%s: expected %s, got %s',
                    $key,
                    json_encode($exp, \JSON_THROW_ON_ERROR),
                    json_encode($got, \JSON_THROW_ON_ERROR),
                );
            }
        }

        return $messages;
    }

    /**
     * @param SassLists $lists
     *
     * @return SassLists
     */
    private static function normalize(array $lists): array
    {
        ksort($lists);

        foreach ($lists as $key => $value) {
            if (\is_array($value) && !array_is_list($value)) {
                ksort($value);
                $lists[$key] = $value;
            }
        }

        return $lists;
    }
}
