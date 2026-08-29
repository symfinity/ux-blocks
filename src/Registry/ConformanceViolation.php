<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * A single composition-language conformance finding (symfinity 108).
 */
final readonly class ConformanceViolation
{
    public const LEVEL_FAIL = 'fail';

    public const LEVEL_WARN = 'warn';

    public function __construct(
        public string $check,
        public string $role,
        public string $level,
        public string $message,
    ) {
    }

    public function isFailure(): bool
    {
        return self::LEVEL_FAIL === $this->level;
    }

    public function describe(): string
    {
        return sprintf('[%s %s] %s: %s', $this->check, strtoupper($this->level), $this->role, $this->message);
    }
}
