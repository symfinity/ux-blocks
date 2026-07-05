# Verification

**Integration profile:** P0 — registry SDK (components ship in tier packages).

## Local commands

```bash
composer test
composer phpstan
```

## Clean-app smoke

On a fresh Symfony 7.4+ project:

```bash
composer require symfinity/ux-blocks symfinity/ux-blocks-core
php bin/console ux-blocks:registry-export --format=json | head
```

Render `<twig:Button label="Save" />` — expect `data-ui-role="button"` in HTML.

## See also

- [Quick start](quickstart.md)
- [CHANGELOG](../CHANGELOG.md)
