<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\DevTools;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\DevTools\BlocksCssOutputNormalizer;

final class BlocksCssOutputNormalizerTest extends TestCase
{
    #[Test]
    public function itQuotesDataUiAttributeValues(): void
    {
        $normalizer = new BlocksCssOutputNormalizer();

        $input = '[data-ui-role=button][data-ui-variant=primary] { color: red; }';
        $expected = '[data-ui-role="button"][data-ui-variant="primary"] { color: red; }';

        self::assertSame($expected, $normalizer->normalizeCss($input));
    }

    #[Test]
    public function itIsIdempotentAndQuotesAlignAttribute(): void
    {
        $normalizer = new BlocksCssOutputNormalizer();
        $input = '[data-ui-role=figure][data-ui-align=center] { display: block; }';
        $once = $normalizer->normalizeCss($input);
        $twice = $normalizer->normalizeCss($once);

        self::assertSame('[data-ui-role="figure"][data-ui-align="center"] { display: block; }', $once);
        self::assertSame($once, $twice);
        self::assertSame(hash('sha256', $once), hash('sha256', $twice));
    }
}
