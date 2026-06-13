<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Test;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Test\ChameleonMarkupAssertions;

final class ChameleonMarkupAssertionsTest extends TestCase
{
    use ChameleonMarkupAssertions;

    #[Test]
    public function assertChameleonRootAcceptsValidMarkup(): void
    {
        $this->assertChameleonRoot(
            '<button data-ui-role="button" data-ui-fragment="blocks.button">Save</button>',
            'button',
            'blocks.button',
        );
    }

    #[Test]
    public function assertChameleonRootRejectsLegacyHelperClasses(): void
    {
        $this->expectException(\PHPUnit\Framework\ExpectationFailedException::class);

        $this->assertChameleonRoot(
            '<button data-ui-role="button" data-ui-fragment="blocks.button" class="html_cva">Save</button>',
            'button',
            'blocks.button',
        );
    }
}
