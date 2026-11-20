# UX Blocks components

The **UX Blocks** family ships Symfony UX Twig components in tier packages. **symfinity/ux-blocks** (this repo) is the SDK only — no production Twig components here.

## Tier packages

| Tier | Package | Role |
|------|---------|------|
| Core | [symfinity/ux-blocks-core](https://packagist.org/packages/symfinity/ux-blocks-core) | Atoms — buttons, typography, navigation bar, … |
| Extended | [symfinity/ux-blocks-extended](https://packagist.org/packages/symfinity/ux-blocks-extended) | Compounds — cards, alerts, tables, … |
| Form | [symfinity/ux-blocks-form](https://packagist.org/packages/symfinity/ux-blocks-form) | Form domain controls and compounds |
| Interactive | [symfinity/ux-blocks-interactive](https://packagist.org/packages/symfinity/ux-blocks-interactive) | Stimulus-backed interactive widgets |
| Live | [symfinity/ux-blocks-live](https://packagist.org/packages/symfinity/ux-blocks-live) | LiveComponent widgets |
| Marketing | [symfinity/ux-blocks-marketing](https://packagist.org/packages/symfinity/ux-blocks-marketing) | Landing and campaign sections |
| Ecommerce | [symfinity/ux-blocks-ecommerce](https://packagist.org/packages/symfinity/ux-blocks-ecommerce) | Shop sections |
| Full | [symfinity/ux-blocks-full](https://packagist.org/packages/symfinity/ux-blocks-full) | Metapackage — core, form, extended, interactive, and live |

Install tiers as needed. All depend on this SDK for registry vocabulary. The navigation bar role (`navbar`) ships with core. `page-header` is not a role in this SDK.

## Styling

- **[symfinity/ui-kernel](https://packagist.org/packages/symfinity/ui-kernel)** — design tokens and generated theme CSS
- **Tier packages** — `[data-ui-role]` rules scoped per package (not in ui-kernel)

## SDK surface (this package)

- [Registry](registry.md) — schema version, fragment helpers, tier catalogs
- `Symfinity\UxBlocks\Test\BlocksMarkupAssertions` — PHPUnit DOM checks

## See also

- [Quick start](quickstart.md)
- [Installation](installation.md)
