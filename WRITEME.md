---
package: nochso/omni
api:
    # All of these can be omitted and show the defaults settings.
    #from: ['/home/amblin/web/nochso/omni/src']
    from: ['src']
    #folder-exclude: [vendor, test, tests, .*]
    file: ['*.php']
    visibility: [public, protected]
    header-depth: 1
toc:
    max-depth: 1
---
# @package@

[![License](https://poser.pugx.org/@package@/license)](https://packagist.org/packages/@package@)
[![GitHub tag](https://img.shields.io/github/tag/@package@.svg)](https://github.com/@package@/releases)
[![Build Status](https://travis-ci.org/@package@.svg?branch=master)](https://travis-ci.org/@package@)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbc0e55a-bc4d-4936-9d27-72dfb913c323/mini.png)](https://insight.sensiolabs.com/projects/fbc0e55a-bc4d-4936-9d27-72dfb913c323)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nochso/omni/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/@package@/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/@package@/badge.svg?branch=master)](https://coveralls.io/github/@package@?branch=master)

@package@ helps with every day nuisances like path or EOL handling.

- Simple solutions: This is not an alternative for more specific packages.
- Fully tested.
- Strictly follows [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html).
- Clean code with [low complexity](https://www.cs.helsinki.fi/u/luontola/tdd-2009/ext/ObjectCalisthenics.pdf).
- No dependencies other than [symfony/polyfill-mbstring](https://packagist.org/packages/symfony/polyfill-mbstring).
- Not a framework.
- Not another object-based wrapper around the standard PHP library.
- One less reason to stumble upon the PHP.net comment section or have a Stackoverflow [deja-vu](https://i.imgur.com/SZPjHwz.jpg).

Table of contents:
@toc@

# Requirements
PHP 5.6.0, 7.0 or higher.

Installation and autoloading via Composer is recommended. You're free to use any other **PSR-4** compatible autoloader on folder `src/`.

## Optional requirements
[`fabpot/php-cs-fixer`](https://packagist.org/packages/fabpot/php-cs-fixer) is required by namespace `nochso\Omni\PhpCsfixer`.

# Installation
```php
composer require @package@
```

You can now use the namespace `\nochso\Omni`.

# API summary
For full API documentation, see [API.md](API.md).

@api('short')@

# Change log
See [CHANGELOG.md](CHANGELOG.md) for the full history of changes between releases.

@changelog@

# Contributing
Feedback, bug reports and pull requests are always welcome.

Please read the [contributing guide](CONTRIBUTING.md) for instructions.

# License
This project is released under the MIT license. See [LICENSE.md](LICENSE.md) for the full text.
