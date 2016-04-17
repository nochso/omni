# nochso/omni

[![License](https://poser.pugx.org/nochso/omni/license)](https://packagist.org/packages/nochso/omni)
[![GitHub tag](https://img.shields.io/github/tag/nochso/omni.svg)](https://github.com/nochso/omni/releases)
[![Build Status](https://travis-ci.org/nochso/omni.svg?branch=master)](https://travis-ci.org/nochso/omni)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbc0e55a-bc4d-4936-9d27-72dfb913c323/mini.png)](https://insight.sensiolabs.com/projects/fbc0e55a-bc4d-4936-9d27-72dfb913c323)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nochso/omni/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nochso/omni/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/nochso/omni/badge.svg?branch=master)](https://coveralls.io/github/nochso/omni?branch=master)

nochso/omni helps with every day nuisances like path or EOL handling.

- Simple solutions: This is not an alternative for more specific packages.
- Fully tested.
- Strictly follows [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html).
- Clean code with [low complexity](https://www.cs.helsinki.fi/u/luontola/tdd-2009/ext/ObjectCalisthenics.pdf).
- No dependencies other than [symfony/polyfill-mbstring](https://packagist.org/packages/symfony/polyfill-mbstring).
- Not a framework.
- Not another object-based wrapper around the standard PHP library.
- One less reason to stumble upon the PHP.net comment section or have a Stackoverflow [deja-vu](https://i.imgur.com/SZPjHwz.jpg).

Table of contents:
- [nochso/omni](#nochsoomni)
- [Requirements](#requirements)
- [Installation](#installation)
- [API summary](#api-summary)
- [Change log](#change-log)
- [Contributing](#contributing)
- [License](#license)

# Requirements
PHP 5.6.0, 7.0 or higher.

Installation and autoloading via Composer is recommended. You're free to use any other **PSR-4** compatible autoloader on folder `src/`.

## Optional requirements
[`fabpot/php-cs-fixer`](https://packagist.org/packages/fabpot/php-cs-fixer) is required by namespace `nochso\Omni\PhpCsfixer`.

# Installation
```php
composer require nochso/omni
```

You can now use the namespace `\nochso\Omni`.

# API summary
For full API documentation, see [API.md](API.md).

This is a short summary of namespaces, classes, interfaces and traits.

- `N` `nochso\Omni`
    - `C` `ArrayCollection` wraps an array, providing common collection methods.
    - `C` `Arrays` class provides methods for array manipulation missing from default PHP.
    - `C` `Dot` allows easy access to multi-dimensional arrays using dot notation.
    - `C` `DotArray` holds a multi-dimensional array and wraps the static API of `\nochso\Omni\Dot`.
    - `C` `EOL` detects, converts and returns information about line-endings.
    - `C` `Exec` creates objects that help manage `\exec()` calls.
    - `C` `Folder` handles file system folders.
    - `C` `Multiline` string class for working with lines of text.
    - `C` `Numeric` validates and converts mixed types to numeric types.
    - `C` `OS` OS.
    - `C` `Path` helps keep the directory separator/implode/trim/replace madness away.
    - `C` `Strings` class provides methods for string handling missing from default PHP.
    - `C` `Type` returns PHP type information.
    - `C` `VcsVersionInfo` enriches an internal VersionInfo with the latest tag and current repository state.
    - `C` `VersionInfo` consists of a package name and version.
- `N` `nochso\Omni\Format`
    - `C` `Bytes` formats a quantity of bytes using different suffixes and binary or decimal base.
    - `C` `Duration` formats seconds or DateInterval objects as human readable strings.
    - `C` `Quantity` formats a string depending on quantity (many, one or zero).
- `N` `nochso\Omni\PhpCsFixer`
    - `C` `DefaultFinder` respects ignore files for Git, Mercurial and Darcs.
    - `C` `PrettyPSR` lies inbetween FixerInterface's PSR2 and Symfony level.


# Change log
See [CHANGELOG.md](CHANGELOG.md) for the full history of changes between releases.

## [Unreleased]

### Added
- Add placeholder `%s` for quantity in `Format\Quantity`.

### Changed
- `DurationFormat` can now handle milliseconds.


## [0.3.7] - 2016-04-16

### Fixed
- Fix handling of absolute `scheme://` paths in `Path::combine`




# Contributing
Feedback, bug reports and pull requests are always welcome.

Please read the [contributing guide](CONTRIBUTING.md) for instructions.

# License
This project is released under the MIT license. See [LICENSE.md](LICENSE.md) for the full text.
