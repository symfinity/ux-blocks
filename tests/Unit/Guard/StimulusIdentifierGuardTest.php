<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\Guard;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Static guard: every Stimulus identifier a ux-blocks tier writes must be one AssetMapper registers.
 *
 * Package controllers register as `symfinity--{package}--{name}` from `assets/package.json`
 * (`symfony.controllers.{name}`). A wrong name connects nothing and no render test notices.
 */
final class StimulusIdentifierGuardTest extends TestCase
{
    private const IDENTIFIER = '/symfinity--(ux-blocks-[a-z]+)--([a-z0-9-]+)/';

    #[Test]
    public function noTierUsesTheSymfonyVendorPrefix(): void
    {
        $offenders = [];
        foreach ($this->scannedLines() as [$location, $line]) {
            if (str_contains($line, 'symfony--ux-blocks-')) {
                $offenders[] = $location;
            }
        }

        self::assertSame([], $offenders, "Use symfinity--ux-blocks-{tier}--{name}, not symfony--ux-blocks-*:\n" . implode("\n", $offenders));
    }

    #[Test]
    public function everySymfinityIdentifierIsARegisteredController(): void
    {
        $offenders = [];
        foreach ($this->scannedLines() as [$location, $line]) {
            preg_match_all(self::IDENTIFIER, $line, $matches, \PREG_SET_ORDER);
            foreach ($matches as [$token, $package, $rest]) {
                $controllers = $this->controllersOf($package);
                if ($controllers === null) {
                    $offenders[] = \sprintf('%s: %s (package %s has no assets/package.json controllers)', $location, $token, $package);

                    continue;
                }

                if (!$this->matchesController($rest, $controllers)) {
                    $offenders[] = \sprintf('%s: %s (known: %s)', $location, $token, implode(', ', $controllers));
                }
            }
        }

        self::assertSame([], $offenders, "Unknown Stimulus identifiers:\n" . implode("\n", $offenders));
    }

    /**
     * `rest` is the controller name itself (data-controller, actions) or the name plus an
     * attribute suffix (`-target`, `-{value}-value`, `-{outlet}-outlet`, `-{class}-class`).
     *
     * @param list<string> $controllers
     */
    private function matchesController(string $rest, array $controllers): bool
    {
        foreach ($controllers as $name) {
            if ($rest === $name) {
                return true;
            }

            if (str_starts_with($rest, $name . '-') && preg_match('/-(target|value|outlet|class)$/', $rest) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return iterable<array{0: string, 1: string}> [file:line, line]
     */
    private function scannedLines(): iterable
    {
        $packages = \dirname(__DIR__, 4);
        foreach (glob($packages . '/ux-blocks-*', \GLOB_ONLYDIR) ?: [] as $packageDir) {
            foreach (['templates' => '.twig', 'assets/controllers' => '.js'] as $sub => $suffix) {
                $root = $packageDir . '/' . $sub;
                if (!is_dir($root)) {
                    continue;
                }

                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
                foreach ($files as $file) {
                    if (!$file instanceof \SplFileInfo || !str_ends_with($file->getFilename(), $suffix)) {
                        continue;
                    }

                    $lines = file($file->getPathname()) ?: [];
                    foreach ($lines as $index => $line) {
                        yield [substr($file->getPathname(), \strlen($packages) + 1) . ':' . ($index + 1), $line];
                    }
                }
            }
        }
    }

    /** @var array<string, list<string>|null> */
    private array $controllerCache = [];

    /**
     * @return list<string>|null
     */
    private function controllersOf(string $package): ?array
    {
        if (\array_key_exists($package, $this->controllerCache)) {
            return $this->controllerCache[$package];
        }

        $path = \dirname(__DIR__, 4) . '/' . $package . '/assets/package.json';
        $controllers = null;
        if (is_file($path)) {
            $data = json_decode((string) file_get_contents($path), true);
            $map = \is_array($data) && \is_array($data['symfony']['controllers'] ?? null) ? $data['symfony']['controllers'] : null;
            if ($map !== null) {
                $controllers = array_values(array_map('strval', array_keys($map)));
                // Longest first, so `data-table-chrome-interactive` wins over a shorter prefix.
                usort($controllers, static fn (string $a, string $b): int => \strlen($b) <=> \strlen($a));
            }
        }

        return $this->controllerCache[$package] = $controllers;
    }
}
