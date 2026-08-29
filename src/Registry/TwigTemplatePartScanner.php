<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Extracts `data-ui-part` values from a Twig template file (K2).
 */
final class TwigTemplatePartScanner
{
    /**
     * @return list<string>
     */
    public static function scanFile(string $templatePath): array
    {
        if (!is_readable($templatePath)) {
            return [];
        }

        return self::scanSource((string) file_get_contents($templatePath));
    }

    /**
     * @return list<string>
     */
    public static function scanSource(string $source): array
    {
        if (!preg_match_all('/data-ui-part="([^"]+)"/', $source, $matches)) {
            return [];
        }

        return array_values(array_unique($matches[1]));
    }
}
