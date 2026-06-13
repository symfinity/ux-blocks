# Quick start

Use the SDK registry helpers in PHP and assert UX Blocks markup in PHPUnit.

## Prerequisites

[Installation](installation.md) completed. For rendered components, also require a tier package:

```bash
composer require symfinity/ux-blocks-core
```

Pair with [symfinity/ui-kernel](https://github.com/symfinity/ui-kernel) when you need token-driven role CSS on pages.

## 1. Build a fragment id

Role ids and fragment prefixes are shared across tier packages:

```php
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\RegistrySchema;

$role = 'button';
$fragment = RegistrySchema::fragmentId($role);
// "blocks.button"

$pattern = RegistrySchema::fragmentPattern($role);
// "blocks.button.{n}"

$roles = CoreRoleCatalog::roles();
// list of canonical core atom role ids
```

Constants:

| Constant | Value |
|----------|-------|
| `RegistrySchema::VERSION` | `1.1` |
| `RegistrySchema::DEFAULT_PREFIX` | `blocks` |

## 2. Assert component markup in tests

Tier packages use `data-ui-role` and `data-ui-fragment` on the component root. Reuse the shared trait in your PHPUnit suite:

```php
<?php

declare(strict_types=1);

namespace App\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Test\ChameleonMarkupAssertions;

final class ButtonComponentTest extends TestCase
{
    use ChameleonMarkupAssertions;

    public function testButtonRootMarkup(): void
    {
        $html = '<button data-ui-role="button" data-ui-fragment="blocks.button">Save</button>';

        $this->assertChameleonRoot($html, 'button', 'blocks.button');
    }
}
```

The trait also rejects legacy Tailwind/CVA helper class names in HTML output.

## 3. Install a tier package

```bash
composer require symfinity/ux-blocks-core
```

Use Symfony UX Twig components from that package in templates — see the tier handbook on GitHub:

- [ux-blocks-core](https://github.com/symfinity/ux-blocks-core)

## Complete minimal example

```php
<?php

use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\RegistrySchema;

foreach (CoreRoleCatalog::roles() as $role) {
    echo RegistrySchema::fragmentId($role) . PHP_EOL;
}
```

## Next steps

- [Registry](registry.md) — schema version and tier catalogs
- [Components](components.md) — family overview
- [Configuration](configuration.md) — bundle wiring (no app YAML)

See also [CHANGELOG.md](../CHANGELOG.md), [CONTRIBUTING.md](../CONTRIBUTING.md), and [GitHub Issues](https://github.com/symfinity/ux-blocks/issues).
