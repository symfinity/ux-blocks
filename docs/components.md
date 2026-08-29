# UX Blocks components

The **UX Blocks** family ships Symfony UX Twig components in tier packages. **symfinity/ux-blocks** (this repo) is the SDK only — no production Twig components here.

## Tier packages

| Tier | Package | Role |
|------|---------|------|
| Core | [symfinity/ux-blocks-core](https://packagist.org/packages/symfinity/ux-blocks-core) | Atoms — buttons, inputs, typography, … |
| Extended | [symfinity/ux-blocks-extended](https://packagist.org/packages/symfinity/ux-blocks-extended) | Compounds — cards, alerts, tables, … |
| Form | [symfinity/ux-blocks-form](https://packagist.org/packages/symfinity/ux-blocks-form) | Form domain controls and compounds |

Install tiers as needed. All depend on this SDK for registry vocabulary.

## Styling

- **[symfinity/ui-kernel](https://packagist.org/packages/symfinity/ui-kernel)** — design tokens and generated theme CSS
- **Tier packages** — `[data-ui-role]` rules scoped per package (not in ui-kernel)

## SDK surface (this package)

- [Registry](registry.md) — schema version, fragment helpers, tier catalogs
- `Symfinity\UxBlocks\Test\BlocksMarkupAssertions` — PHPUnit DOM checks

## See also

- [Quick start](quickstart.md)
- [Installation](installation.md)
