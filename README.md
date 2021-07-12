# Very short description of the package

PHP driver for Lucent CMS v3.


## Installation

You can install the package via composer:

```bash
composer require radasfunk/lucentcms-php
```

## Usage

```php
$lucent = new Client(
    {{channelID}},
    {{apiKey}}),
);

// Optionally you can set the locale
$lucent->addHeader("Accept-Language",{locale});

$res = $lucent->get('documents', [
    'filter[schema]' => 'products',
]);
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hey@lucentcms.com instead of using the issue tracker.

## Credits

-   [Alexander Lingris](https://github.com/lucentcms)
