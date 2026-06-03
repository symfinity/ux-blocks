# symfinity/ux-blocks

Base SDK for the **UX Blocks** family — registry schema, v0 role catalog, and shared Chameleon markup test helpers.

<!-- autodocs:section id=description -->
UX Blocks base SDK — registry schema and Symfinity UI markup helpers for Symfony UX Twig components
<!-- /autodocs:section -->

## Provides

- `Symfinity\UxBlocks\Registry\RegistrySchema` — schema version, default prefix `blocks`, fragment helpers
- `Symfinity\UxBlocks\Registry\CoreRoleCatalog` — fourteen core catalog role ids
- `Symfinity\UxBlocks\Test\ChameleonMarkupAssertions` — trait for component DOM tests

## Consumers

- `symfinity/ux-blocks-core` — official component catalog (requires this package)

## Optional Twig prefix

Consumers may set `name_prefix: st` under `twig_component.defaults` for `Symfinity\UxBlocksCore\Twig\Components\` to render `<twig:st:Alert />` instead of `<twig:Alert />`.

<!-- autodocs:section id=install -->
```bash
composer require symfinity/ux-blocks
```
<!-- /autodocs:section -->

<!-- autodocs:section id=requirements -->
- PHP >=8.2
- UX Blocks base SDK — registry schema and Symfinity UI markup helpers for Symfony UX Twig components
<!-- /autodocs:section -->

## Test

From product monorepo root:

```bash
cd src/symfinity
make test
# or per-package:
docker compose --env-file .env.docker run --rm -T -w /app/packages/ux-blocks php php vendor/bin/phpunit
```
