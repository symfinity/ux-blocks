# Registry

The UX Blocks registry defines stable role ids, fragment naming, and tier boundaries shared by all component packages.

## Schema

| Item | Value |
|------|-------|
| Schema version | `RegistrySchema::VERSION` → `1.1` |
| Default fragment prefix | `blocks` |
| Fragment id | `{prefix}.{role}` — e.g. `blocks.button` |
| Fragment pattern | `{prefix}.{role}.{n}` — for lists and repeats |

```php
use Symfinity\UxBlocks\Registry\RegistrySchema;

RegistrySchema::fragmentId('alert');           // blocks.alert
RegistrySchema::fragmentPattern('alert');      // blocks.alert.{n}
RegistrySchema::fragmentId('alert', 'acme');   // acme.alert (custom prefix)
```

## Tier catalogs

Each tier package ships Twig components for its catalog. Role lists live in this SDK as PHP catalogs (single source for tests and tooling):

| Catalog class | Tier package |
|---------------|--------------|
| `CoreRoleCatalog` | [symfinity/ux-blocks-core](https://github.com/symfinity/ux-blocks-core) |
| `ExtendedRoleCatalog` | [symfinity/ux-blocks-extended](https://github.com/symfinity/ux-blocks-extended) |
| `InteractiveRoleCatalog` | [symfinity/ux-blocks-interactive](https://github.com/symfinity/ux-blocks-interactive) |
| `LiveRoleCatalog` | [symfinity/ux-blocks-live](https://github.com/symfinity/ux-blocks-live) |
| `MarketingRoleCatalog` | [symfinity/ux-blocks-marketing](https://github.com/symfinity/ux-blocks-marketing) |
| `EcommerceRoleCatalog` | [symfinity/ux-blocks-ecommerce](https://github.com/symfinity/ux-blocks-ecommerce) |
| `LabRoleCatalog` | [symfinity/ux-blocks-lab](https://github.com/symfinity/ux-blocks-lab) (optional, dev) |

Each role id belongs to **exactly one** tier catalog. PHPUnit in this package guards overlap and coverage across tiers.

## Markup contract

Rendered components expose:

- `data-ui-role="{role}"` — canonical role id from the catalog
- `data-ui-fragment="{prefix}.{role}"` — stable fragment id for tests and diagnostics

Use [ChameleonMarkupAssertions](quickstart.md#2-assert-component-markup-in-tests) in tier package tests.

## See also

- [Components](components.md) — family overview and handbooks
- [Quick start](quickstart.md)
