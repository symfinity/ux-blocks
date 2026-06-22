# UX Blocks components

The **UX Blocks** family ships Symfony UX Twig components in tier packages. **symfinity/ux-blocks** (this repo) is the SDK only — no production Twig components here.

## Tier packages

| Tier | Package | Role |
|------|---------|------|
| Core | [symfinity/ux-blocks-core](https://github.com/symfinity/ux-blocks-core) | Atoms — buttons, inputs, typography, … |
| Extended | [symfinity/ux-blocks-extended](https://github.com/symfinity/ux-blocks-extended) | Compounds — cards, alerts, tables, … |
| Interactive | [symfinity/ux-blocks-interactive](https://github.com/symfinity/ux-blocks-interactive) | Progressive enhancement (Stimulus) |
| Live | [symfinity/ux-blocks-live](https://github.com/symfinity/ux-blocks-live) | Live Components |
| Marketing | [symfinity/ux-blocks-marketing](https://github.com/symfinity/ux-blocks-marketing) | Landing sections |
| Ecommerce | [symfinity/ux-blocks-ecommerce](https://github.com/symfinity/ux-blocks-ecommerce) | Shop patterns |
| Lab | [symfinity/ux-blocks-lab](https://github.com/symfinity/ux-blocks-lab) | Experimental roles (dev/test) |

Install tiers as needed. All depend on this SDK for registry vocabulary.

## Styling

- **[symfinity/ui-kernel](https://github.com/symfinity/ui-kernel)** — design tokens and generated theme CSS
- **Tier packages** — `[data-ui-role]` rules scoped per package (not in ui-kernel)

## SDK surface (this package)

- [Registry](registry.md) — schema version, fragment helpers, tier catalogs
- `Symfinity\UxBlocks\Test\BlocksMarkupAssertions` — PHPUnit DOM checks

## See also

- [Quick start](quickstart.md)
- [Installation](installation.md)
