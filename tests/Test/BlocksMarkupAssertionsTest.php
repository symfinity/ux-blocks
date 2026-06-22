<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Test;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Test\BlocksMarkupAssertions;

final class BlocksMarkupAssertionsTest extends TestCase
{
    use BlocksMarkupAssertions;

    #[Test]
    public function assertBlocksRootAcceptsValidMarkup(): void
    {
        $this->assertBlocksRoot(
            '<button data-ui-role="button" data-ui-fragment="blocks.button">Save</button>',
            'button',
            'blocks.button',
        );
    }

    #[Test]
    public function assertBlocksRootRejectsLegacyHelperClasses(): void
    {
        $this->expectException(\PHPUnit\Framework\ExpectationFailedException::class);

        $this->assertBlocksRoot(
            '<button data-ui-role="button" data-ui-fragment="blocks.button" class="html_cva">Save</button>',
            'button',
            'blocks.button',
        );
    }
}
