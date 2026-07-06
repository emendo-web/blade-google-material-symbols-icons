# Blade Google Material Symbols

A package to easily make use of [Google Material Symbols](https://github.com/google/material-design-icons) in your Laravel Blade views.

For a full list of available icons see [the SVG directory](resources/svg) or preview them at [fonts.google.com/icons](https://fonts.google.com/icons?icon.size=24&icon.color=%23e8eaed).

## Requirements

- PHP 8.3 or higher
- Laravel 11, 12 or 13

## Installation

```bash
composer require kienso/blade-google-material-symbols
```

<!-- ## Updating

Please refer to [`the upgrade guide`](UPGRADE.md) when updating the library. -->

## Configuration

Blade Google Material Symbols also offers the ability to use features from Blade Icons like default classes, default attributes, etc. If you'd like to configure these, publish the `blade-material-symbols.php` config file:

```bash
php artisan vendor:publish --tag=blade-material-symbols-config
```

## Usage

Icons can be used as self-closing Blade components which will be compiled to SVG icons:

```blade
<x-gmsi-o-home/>
```

You can also pass classes to your icon components:

```blade
<x-gmsi-o-home class="w-6 h-6 text-gray-500"/>
```

And even use inline styles:

```blade
<x-gmsi-o-home style="color: #555"/>
```

The rounded icons can be referenced like this:

```blade
<x-gmsi-r-home/>
```

The sharp icons can be referenced like this:

```blade
<x-gmsi-s-home/>
```

The filled version of any icon is available by appending `-fill`:

```blade
<x-gmsi-o-home-fill/>
```

### Raw SVG Icons

If you want to use the raw SVG icons as assets, you can publish them using:

```bash
php artisan vendor:publish --tag=blade-material-symbols --force
```

Then use them in your views like:

```blade
<img src="{{ asset('vendor/blade-material-symbols/o-home.svg') }}" width="10" height="10"/>
```

## Changelog

Check out the [CHANGELOG](CHANGELOG.md) in this repository for all the recent changes.

## Maintainers

Blade Google Material Symbols is developed and maintained by Kienso.

## License

Blade Google Material Symbols is open-sourced software licensed under [the MIT license](LICENSE.md).
