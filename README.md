# symfinity/ux-blocks

Base SDK for the **UX Blocks** family — registry schema, v0 role catalog, and shared Chameleon markup test helpers.

**Design:** [PRODUCT-ux-blocks-family](../../../classified/explore/PRODUCT-ux-blocks-family.md)  
**Feature:** [symfinity 003 — ux-component-catalog](../../../specs/symfinity/symfinity/3-ux-component-catalog/spec.md)

## Provides

- `Symfinity\UxBlocks\Registry\RegistrySchema` — schema version, default prefix `blocks`, fragment helpers
- `Symfinity\UxBlocks\Registry\CoreRoleCatalog` — fourteen core catalog role ids
- `Symfinity\UxBlocks\Test\ChameleonMarkupAssertions` — trait for component DOM tests

## Consumers

- `symfinity/ux-blocks-core` — official component catalog (requires this package)

## Optional Twig prefix

Consumers may set `name_prefix: st` under `twig_component.defaults` for `Symfinity\UxBlocksCore\Twig\Components\` to render `<twig:st:Alert />` instead of `<twig:Alert />`.

## Test

From product monorepo root:

```bash
cd src/symfinity
make test
# or per-package:
docker compose --env-file .env.docker run --rm -T -w /app/packages/ux-blocks php php vendor/bin/phpunit
```
