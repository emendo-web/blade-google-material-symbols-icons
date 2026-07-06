# CLAUDE.md

Guidance for working in this repository.

## What this package is

A Laravel Blade icon set that exposes the [Google Material Symbols](https://fonts.google.com/icons)
icons as Blade components, built on top of `blade-ui-kit/blade-icons`. The SVGs
themselves live in `resources/svg/` and are **generated**, not hand-written.

- PHP namespace: `Kienso\BladeMaterialSymbols\` → `src/`
- Composer package: `kienso/blade-google-material-symbols`

## Layout

- `src/BladeMaterialSymbolsServiceProvider.php` — registers the `material-symbols`
  icon set with blade-icons and wires the config.
- `resources/svg/` — ~23k generated SVGs. **Do not edit by hand**; regenerate them
  (see below).
- `config/blade-material-symbols.php` — user-facing config (set `prefix` is `gmsi`,
  default `class`, default `attributes`, `fallback`).
- `config/generation.php` — blade-icons compilation config (dev only, export-ignored).
- `scripts/UpdateIcons.php` — regenerates `resources/svg/` from the npm package.
- `tests/` — PHPUnit + Orchestra Testbench.

## Icon naming

Components are `{set-prefix}-{variant}-{name}[-fill]`:

- Set prefix: `gmsi` (from `config/blade-material-symbols.php`).
- Variant prefix: `o-` outlined, `r-` rounded, `s-` sharp.
- Optional `-fill` suffix for the filled version.

Example: `<x-gmsi-o-home />`, `svg('gmsi-r-settings-fill')`.

## Updating the icons

1. Bump `@material-symbols/svg-400` in `package.json` (check the latest with
   `npm view @material-symbols/svg-400 version`).
2. `npm install`
3. `php scripts/UpdateIcons.php`

`UpdateIcons.php` removes the old SVGs, copies the three variant folders
(`outlined`/`rounded`/`sharp`, prefixing filenames), then rewrites each SVG to add
`fill="currentColor"` and strip `width`/`height`. It aborts before deleting anything
if the npm sources are missing, so always run `npm install` first.

Icon updates are **not backwards compatible**: Google occasionally removes or renames
icons. List removed icons in `CHANGELOG.md` under `### Removed` (they break consumer
views). `git status` after regeneration shows added (`??`) vs deleted (`D`) files.

## Tests

Run with `./vendor/bin/phpunit` (needs `composer install` with dev deps).

- `tests/TestCase.php` — base case registering the blade-icons + package providers.
- `CompilesIconsTest` — asserts exact compiled SVG output (brittle by design: breaks
  if a glyph is redrawn upstream, acting as a canary).
- `TransformsIconsTest` — asserts the build contract (`fill="currentColor"`, viewBox
  kept, no width/height) over representative icons + a deterministic sample of the set.
- `ConfigTest` — default class/attributes and fallback behaviour.

Use the `#[Test]` attribute, not `/** @test */` (deprecated in PHPUnit 11).

## Conventions

- Dev-only files must be kept out of the Composer dist archive via `export-ignore`
  in `.gitattributes` (tests, config/generation.php, CHANGELOG, this file, etc.).
- `CHANGELOG.md` follows [Keep a Changelog](https://keepachangelog.com); keep entries
  user-facing.
