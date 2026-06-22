<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\DevTools;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\DevTools\ScssModularAuditor;
use Symfinity\UxBlocks\DevTools\ScssTopLevelRuleParser;

final class ScssModularAuditorTest extends TestCase
{
    #[Test]
    public function parserExtractsTopLevelRules(): void
    {
        $rules = (new ScssTopLevelRuleParser())->parse(<<<'SCSS'
            [data-ui-role="checkbox"][data-ui-variant="primary"] {
              --ui-toggle-accent: var(--ui-color-primary);
            }

            [data-ui-role="checkbox"][data-ui-variant="secondary"] {
              --ui-toggle-accent: var(--ui-color-secondary);
            }
            SCSS);

        self::assertCount(2, $rules);
        self::assertStringContainsString('primary', $rules[0]['selector']);
    }

    #[Test]
    public function detectsVariantAppearancePasteWithoutInclude(): void
    {
        $findings = (new ScssModularAuditor())->detectVariantAppearancePaste(<<<'SCSS'
            [data-ui-role="checkbox"][data-ui-variant="primary"] {
              --ui-toggle-accent: var(--ui-color-primary);
            }

            [data-ui-role="checkbox"][data-ui-variant="secondary"] {
              --ui-toggle-accent: var(--ui-color-secondary);
            }

            [data-ui-role="checkbox"][data-ui-variant="accent"] {
              --ui-toggle-accent: var(--ui-color-accent);
            }
            SCSS);

        self::assertCount(1, $findings);
        self::assertSame('variant-appearance-paste', $findings[0]['code']);
    }

    #[Test]
    public function modularRoleFileWithIncludeIsNotReported(): void
    {
        $dir = sys_get_temp_dir() . '/scss-audit-modular-' . uniqid('', true);
        mkdir($dir . '/assets/scss/roles', 0775, true);

        file_put_contents($dir . '/assets/scss/roles/checkbox.scss', <<<'SCSS'
            @use '../_shared/tone-surfaces' as tone;

            [data-ui-role="checkbox"] {
              @include tone.each-variant-toggle-accent();
            }
            SCSS);

        $reports = (new ScssModularAuditor())->auditPackage($dir);

        self::assertSame([], $reports);
    }

    #[Test]
    public function bundleWithRawSelectorsIsFlagged(): void
    {
        $dir = sys_get_temp_dir() . '/scss-audit-bundle-' . uniqid('', true);
        mkdir($dir . '/assets/scss/roles', 0775, true);

        file_put_contents($dir . '/assets/scss/roles/_bundle.scss', <<<'SCSS'
            @use '../partials/foo';
            [data-ui-role="hero"] { display: block; }
            SCSS);

        $reports = (new ScssModularAuditor())->auditPackage($dir);

        self::assertCount(1, $reports);
        self::assertSame('paste', $reports[0]['verdict']);
        self::assertSame('bundle-not-modular', $reports[0]['findings'][0]['code']);
    }
}
