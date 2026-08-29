<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Compiles scss/roles/_bundle.scss → styles/roles/_bundle.css (audit residual roles).
 */
final class BlocksCssAuditBundleCompiler
{
    public const GENERATED_HEADER = '/* generated: blocks-css:compile — do not edit */';

    public function sourcePath(SassPackage $package): string
    {
        return $package->stylesSrcDirectory . '/roles/_bundle.scss';
    }

    public function outputPath(SassPackage $package): string
    {
        return $package->stylesDirectory . '/roles/_bundle.css';
    }

    public function hasSource(SassPackage $package): bool
    {
        return is_file($this->sourcePath($package));
    }

    /**
     * @param list<string> $sassArgv
     *
     * @return list<string>
     */
    public function compile(array $sassArgv, SassPackage $package): array
    {
        if (!$this->hasSource($package)) {
            return [];
        }

        $source = $this->sourcePath($package);
        $output = $this->outputPath($package);
        $outputDir = dirname($output);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        $command = [
            ...$sassArgv,
            '--no-source-map',
            '--style=expanded',
            $source . ':' . $output,
        ];

        $process = new Process($command, $package->packageDirectory, null, null, 120.0);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            return [sprintf('%s audit bundle: %s', $package->composerName, trim($exception->getMessage()))];
        }

        return [];
    }

    public function isStale(SassPackage $package): bool
    {
        if (!$this->hasSource($package)) {
            return false;
        }

        $output = $this->outputPath($package);

        if (!is_file($output)) {
            return true;
        }

        return filemtime($this->sourcePath($package)) > filemtime($output);
    }

    public function stalePath(SassPackage $package): string
    {
        return 'assets/styles/roles/_bundle.css';
    }
}
