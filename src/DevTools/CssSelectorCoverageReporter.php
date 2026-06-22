<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Measures Css PHPUnit selector-key coverage per ux-blocks tier (120 SC-003).
 */
final class CssSelectorCoverageReporter
{
    public const THRESHOLD = 0.80;

    /**
     * @return array{asserted: int, audit: int, ratio: float, missing: list<string>}
     */
    public function reportForPackage(string $packageDirectory): array
    {
        $auditKeys = $this->collectAuditKeys($packageDirectory);
        $assertedKeys = $this->collectAssertedKeys($packageDirectory);

        $missing = array_values(array_diff($auditKeys, $assertedKeys));
        sort($missing);

        $auditCount = count($auditKeys);
        $assertedCount = count(array_intersect($auditKeys, $assertedKeys));
        $ratio = $auditCount === 0 ? 1.0 : $assertedCount / $auditCount;

        return [
            'asserted' => $assertedCount,
            'audit' => $auditCount,
            'ratio' => $ratio,
            'missing' => $missing,
        ];
    }

    /**
     * @return list<string>
     */
    public function collectAuditKeys(string $packageDirectory): array
    {
        $packageDirectory = rtrim($packageDirectory, '/\\');
        $keys = [];

        $bundlePath = $packageDirectory . '/assets/styles/roles/_bundle.css';
        if (is_file($bundlePath)) {
            foreach ($this->extractRoleKeys((string) file_get_contents($bundlePath)) as $key) {
                $keys[$key] = true;
            }
        }

        foreach (glob($packageDirectory . '/assets/styles/blocks-*.css') ?: [] as $entryPath) {
            foreach ($this->extractRoleKeys((string) file_get_contents($entryPath)) as $key) {
                $keys[$key] = true;
            }
        }

        $sorted = array_keys($keys);
        sort($sorted);

        return $sorted;
    }

    /**
     * @return list<string>
     */
    public function collectAssertedKeys(string $packageDirectory): array
    {
        $testsDir = rtrim($packageDirectory, '/\\') . '/tests/Unit/Css';

        if (!is_dir($testsDir)) {
            return [];
        }

        $keys = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($testsDir)) as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $source = (string) file_get_contents($file->getPathname());
            foreach ($this->extractAssertedRoleKeysFromTestSource($source) as $key) {
                $keys[$key] = true;
            }
        }

        $sorted = array_keys($keys);
        sort($sorted);

        return $sorted;
    }

    /**
     * @return list<string>
     */
    public function extractRoleKeys(string $content): array
    {
        preg_match_all('/\[data-ui-role="([^"]+)"\]/', $content, $matches);

        if ($matches[1] === []) {
            return [];
        }

        $keys = array_values(array_unique(array_filter(
            $matches[1],
            static fn (string $key): bool => (bool) preg_match('/^[a-z][a-z0-9-]*$/', $key),
        )));
        sort($keys);

        return $keys;
    }

    /**
     * @return list<string>
     */
    public function extractAssertedRoleKeysFromTestSource(string $content): array
    {
        $keys = $this->extractRoleKeys($content);

        if (preg_match_all('/foreach\s*\(\s*\[(.*?)\]\s*as\s*\$\w+\)\s*\{/s', $content, $loops, PREG_OFFSET_CAPTURE)) {
            $matchCount = count($loops[0]);
            for ($index = 0; $index < $matchCount; ++$index) {
                $fullMatch = $loops[0][$index];
                $arrayContent = $loops[1][$index][0];
                $openBrace = $fullMatch[1] + strlen($fullMatch[0]) - 1;
                $block = $this->extractBalancedBraceBlock($content, $openBrace);
                if (!str_contains($block, 'assertStringContainsString')) {
                    continue;
                }

                if (str_contains($block, 'assertDoesNotMatchRegularExpression')) {
                    continue;
                }

                preg_match_all("/'([a-z][a-z0-9-]*(?:-[a-z0-9-]+)*)'/", $arrayContent, $roles);
                foreach ($roles[1] as $role) {
                    $keys[] = $role;
                }
            }
        }

        $keys = array_values(array_unique($keys));
        sort($keys);

        return $keys;
    }

    private function extractBalancedBraceBlock(string $content, int $openBraceOffset): string
    {
        if (($content[$openBraceOffset] ?? '') !== '{') {
            return '';
        }

        $depth = 0;
        $length = strlen($content);

        for ($index = $openBraceOffset; $index < $length; ++$index) {
            $char = $content[$index];
            if ($char === '{') {
                ++$depth;
            } elseif ($char === '}') {
                --$depth;
                if ($depth === 0) {
                    return substr($content, $openBraceOffset, $index - $openBraceOffset + 1);
                }
            }
        }

        return '';
    }
}
