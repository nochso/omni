# nochso/omni

nochso/omni helps with every day nuisances like path or EOL handling.

- Fully tested.
- Clean code with low complexity.
- No 3rd party dependencies by default.
- Not a framework.
- Not another object-based wrapper around the standard PHP library.
- One less reason to stumble upon the PHP.net comment section or have a Stackoverflow deja-vu.

## Requirements
PHP 5.6.0, 7.0 or higher.

Installation and autoloading via Composer is recommended. You're free to use any other **PSR-4** compatible autoloader on folder `src/`.

### Optional requirements
[`fabpot/php-cs-fixer`](https://packagist.org/packages/fabpot/php-cs-fixer) is required by namespace `nochso\Omni\PhpCsfixer`.

## Installation
```php
composer require nochso/omni
```

You can now use the namespace `\nochso\Omni`.

## Documentation
**to do**

## Change log
See [CHANGELOG.md](CHANGELOG.md) for the full history of changes between releases.

## Contributing
Feedback, bug reports and pull requests are always welcome.

When writing code, please follow the **PSR2** coding style and **PSR4** autoloading standards.

## License
This project is released under the MIT license. See [LICENSE.md](LICENSE.md) for the full text.