<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

class RegistryPackageLocator
{
    /** @var list<string> */
    public const PACKAGE_SLUGS = [
        'ux-blocks-core',
        'ux-blocks-form',
        'ux-blocks-extended',
        'ux-blocks-interactive',
        'ux-blocks-live',
        'ux-blocks-marketing',
        'ux-blocks-ecommerce',
        'ux-blocks-lab',
    ];

    public function resolveMonorepoRoot(?string $startDir = null): ?string
    {
        $dir = $startDir ?? (string) getcwd();

        while ($dir !== \dirname($dir)) {
            if (is_file($dir . '/mono.json')) {
                return $dir;
            }

            $dir = \dirname($dir);
        }

        return null;
    }

    public function packageDir(string $slug, ?string $monorepoRoot = null): string
    {
        if (!\in_array($slug, self::PACKAGE_SLUGS, true)) {
            throw new \InvalidArgumentException(sprintf('Unknown package slug "%s".', $slug));
        }

        $root = $monorepoRoot ?? $this->resolveMonorepoRoot();
        if (null === $root) {
            throw new \RuntimeException('Cannot resolve symfinity monorepo root (mono.json not found).');
        }

        $dir = $root . '/packages/' . $slug;
        if (!is_dir($dir)) {
            throw new \RuntimeException(sprintf('Package directory not found: %s', $dir));
        }

        return $dir;
    }

    /**
     * @return list<string>
     */
    public function resolveTargetSlugs(?string $packageOption): array
    {
        if (null === $packageOption || '' === $packageOption) {
            return self::PACKAGE_SLUGS;
        }

        return [$packageOption];
    }
}
