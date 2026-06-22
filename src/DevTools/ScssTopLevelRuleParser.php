<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

/**
 * Extracts top-level CSS-like rule blocks from role SCSS (120 modular audit).
 */
final class ScssTopLevelRuleParser
{
    /**
     * @return list<array{selector: string, body: string, line: int}>
     */
    public function parse(string $scss): array
    {
        $scss = $this->stripComments($scss);
        $lines = preg_split('/\r\n|\r|\n/', $scss) ?: [];
        $rules = [];
        $depth = 0;
        $selectorBuffer = '';
        $bodyBuffer = '';
        $ruleStartLine = 1;
        $capturingBody = false;

        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1;
            $trimmed = trim($line);

            if ($depth === 0) {
                if ($trimmed === '' || $this->isIgnorableTopLevelLine($trimmed)) {
                    continue;
                }

                if (!$capturingBody && str_contains($line, '{')) {
                    $beforeBrace = strstr($line, '{', true);
                    if ($beforeBrace === false) {
                        continue;
                    }

                    $selectorBuffer = trim(rtrim($beforeBrace) . ' ' . trim($selectorBuffer));
                    if ($selectorBuffer === '' || str_starts_with($selectorBuffer, '@')) {
                        $depth += substr_count($line, '{') - substr_count($line, '}');
                        $selectorBuffer = '';
                        $capturingBody = false;

                        continue;
                    }

                    $ruleStartLine = $lineNumber;
                    $capturingBody = true;
                    $afterBrace = substr(strstr($line, '{') ?: '', 1);
                    $bodyBuffer = $afterBrace;
                    $depth = 1 + substr_count($afterBrace, '{') - substr_count($afterBrace, '}');
                } elseif (!$capturingBody) {
                    $selectorBuffer .= ' ' . $trimmed;
                }
            } else {
                $bodyBuffer .= "\n" . $line;
                $depth += substr_count($line, '{') - substr_count($line, '}');
            }

            if ($capturingBody && $depth === 0) {
                $body = preg_replace('/\}\s*$/', '', $bodyBuffer) ?? $bodyBuffer;
                $rules[] = [
                    'selector' => trim($selectorBuffer),
                    'body' => trim($body),
                    'line' => $ruleStartLine,
                ];
                $selectorBuffer = '';
                $bodyBuffer = '';
                $capturingBody = false;
            }
        }

        return $rules;
    }

    private function stripComments(string $scss): string
    {
        $scss = preg_replace('#/\*.*?\*/#s', '', $scss) ?? $scss;

        return preg_replace('#//.*$#m', '', $scss) ?? $scss;
    }

    private function isIgnorableTopLevelLine(string $trimmed): bool
    {
        return str_starts_with($trimmed, '@use ')
            || str_starts_with($trimmed, '@forward ')
            || str_starts_with($trimmed, '@import ');
    }
}
