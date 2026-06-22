<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\DevTools;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\DevTools\BlocksCssAuditBundleCompiler;
use Symfinity\UxBlocks\DevTools\SassPackage;

final class BlocksCssAuditBundleCompilerTest extends TestCase
{
    #[Test]
    public function itDetectsStaleAuditBundleOutput(): void
    {
        $root = sys_get_temp_dir() . '/ux-blocks-audit-' . uniqid('', true);
        $srcDir = $root . '/assets/scss/roles';
        $destDir = $root . '/assets/styles/roles';
        mkdir($srcDir, 0775, true);
        mkdir($destDir, 0775, true);
        file_put_contents($srcDir . '/_bundle.scss', '[data-ui-role="separator"] {}');
        file_put_contents($destDir . '/_bundle.css', '[data-ui-role="separator"] {}');
        touch($destDir . '/_bundle.css', time() - 10);
        touch($srcDir . '/_bundle.scss');

        $package = new SassPackage(
            composerName: 'symfinity/ux-blocks-core',
            packageDirectory: $root,
            stylesSrcDirectory: $root . '/assets/scss',
            stylesDirectory: $root . '/assets/styles',
        );

        $compiler = new BlocksCssAuditBundleCompiler();

        self::assertTrue($compiler->isStale($package));
        self::assertSame('assets/styles/roles/_bundle.css', $compiler->stalePath($package));

        $this->removeTree($root);
    }

    private function removeTree(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($path);
    }
}
