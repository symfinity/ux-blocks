<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

use Symfony\Component\Yaml\Yaml;

/**
 * Resolves tier registry YAML and package paths for catalog audit tooling.
 */
final class TierRegistryLocator
{
    /** @var array<string, string> */
    private const LANE_PACKAGE_DIR = [
        'blocks.core' => 'ux-blocks-core',
        'blocks.form' => 'ux-blocks-form',
        'blocks.extended' => 'ux-blocks-extended',
        'blocks.int' => 'ux-blocks-interactive',
        'blocks.live' => 'ux-blocks-live',
        'blocks.marketing' => 'ux-blocks-marketing',
        'blocks.shop' => 'ux-blocks-ecommerce',
        'blocks.lab' => 'ux-blocks-lab',
    ];

    /** @var array<string, string> */
    private array $bundlePathCache = [];

    public function supportsLane(string $lane): bool
    {
        return isset(self::LANE_PACKAGE_DIR[$lane]);
    }

    public function bundlePath(string $lane): ?string
    {
        if (\array_key_exists($lane, $this->bundlePathCache)) {
            return '' === $this->bundlePathCache[$lane] ? null : $this->bundlePathCache[$lane];
        }

        $packageDir = self::LANE_PACKAGE_DIR[$lane] ?? null;
        if (null === $packageDir) {
            $this->bundlePathCache[$lane] = '';

            return null;
        }

        $resolved = $this->resolvePackageRoot($packageDir);
        $this->bundlePathCache[$lane] = $resolved ?? '';

        return $resolved;
    }

    public function registryPath(string $lane): ?string
    {
        $bundlePath = $this->bundlePath($lane);
        if (null === $bundlePath) {
            return null;
        }

        $path = $bundlePath . '/config/ux_roles.yaml';

        return is_readable($path) ? $path : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function roleRows(string $lane): array
    {
        $path = $this->registryPath($lane);
        if (null === $path) {
            return [];
        }

        /** @var array<string, mixed> $registry */
        $registry = Yaml::parseFile($path);

        /** @var list<array<string, mixed>> $rows */
        $rows = $registry['roles'] ?? [];

        return $rows;
    }

    public function tierLabel(string $lane): string
    {
        return match ($lane) {
            'blocks.core' => 'core',
            'blocks.form' => 'form',
            'blocks.extended' => 'extended',
            'blocks.int' => 'interactive',
            'blocks.live' => 'live',
            'blocks.marketing' => 'marketing',
            'blocks.shop' => 'ecommerce',
            'blocks.lab' => 'lab',
            default => $lane,
        };
    }

    public function roleCssPath(string $lane, string $roleKebab): ?string
    {
        $bundlePath = $this->bundlePath($lane);
        if (null === $bundlePath) {
            return null;
        }

        $path = $bundlePath . '/assets/styles/roles/' . $roleKebab . '.css';

        return is_readable($path) ? $path : null;
    }

    public function componentTemplatePath(string $lane, string $twigComponent): ?string
    {
        $bundlePath = $this->bundlePath($lane);
        if (null === $bundlePath) {
            return null;
        }

        $path = $bundlePath . '/templates/components/' . $twigComponent . '.html.twig';

        return is_readable($path) ? $path : null;
    }

    private function resolvePackageRoot(string $packageDir): ?string
    {
        $candidates = [
            \dirname(__DIR__, 3) . '/' . $packageDir,
            \dirname(__DIR__, 4) . '/vendor/symfinity/' . $packageDir,
        ];

        foreach ($candidates as $path) {
            if (!is_dir($path . '/config')) {
                continue;
            }

            $realpath = realpath($path);

            return false !== $realpath ? $realpath : $path;
        }

        return null;
    }
}
