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
This is a summary of namespaces, classes, interfaces, traits and public/protected methods.

- `N` `nochso\Omni`
    - `C` `ArrayCollection` wraps an array, providing common collection methods.
        - `__construct`
        - `add`
        - `set` or replace the element at a specific index.
        - `remove` and return the element at the zero based index.
        - `first` sets the internal pointer to the first element and returns it.
        - `last` sets the internal pointer to the last element and returns it.
        - `toArray` returns a plain old array.
        - `apply` a callable to every element.
        - `count` elements of an object.
        - `getIterator` returns an external iterator.
        - `offsetExists` allows using `isset`.
        - `offsetGet` allows array access, e.g. `$list[2]`.
        - `offsetSet` allows writing to arrays `$list[2] = 'foo'`.
        - `offsetUnset` allows using `unset`.
    - `C` `Arrays` class provides methods for array manipulation missing from default PHP.
        - `flatten` arrays and non-arrays recursively into a 2D array.
    - `C` `Dot` allows easy access to multi-dimensional arrays using dot notation.
        - `get` the value of the element at the given dot key path.
        - `has` returns true if an element exists at the given dot key path.
        - `set` a value at a certain path by creating missing elements and overwriting non-array values.
        - `trySet` sets a value at a certain path, expecting arrays or missing elements along the way.
        - `remove` an element if it exists.
        - `flatten` the array into a single dimension array with dot paths as keys.
    - `C` `DotArray` holds a multi-dimensional array and wraps the static API of `\nochso\Omni\Dot`.
        - `__construct`
        - `getArray` returns the complete array.
        - `get` the value of the element at the given dot key path.
        - `has` returns true if an element exists at the given dot key path.
        - `set` a value at a certain path by creating missing elements and overwriting non-array values.
        - `trySet` sets a value at a certain path, expecting arrays or missing elements along the way.
        - `remove` an element if it exists.
        - `flatten` the array into a single dimension array with escaped dot paths as keys.
        - `getIterator` allows you to iterate over a flattened array using `foreach`.
        - `offsetExists` allows using `isset($da['a.b'])`.
        - `offsetGet` allows array access, e.g. `$da['a.b']`.
        - `offsetSet` allows writing to arrays `$da['a.b'] = 'foo'`.
        - `offsetUnset` allows using `unset($da['a.b'])`.
    - `C` `EOL` detects, converts and returns information about line-endings.
        - `__construct`
        - `__toString` casts to/returns the raw line ending string.
        - `getName` of line ending, e.g. `LF`.
        - `getDescription` of line ending, e.g. `Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS`.
        - `apply` this EOL style to a string.
        - `detect` the EOL style of a string and return an EOL representation.
        - `detectDefault` falls back to a default EOL style on failure.
    - `C` `Exec` creates objects that help manage `\exec()` calls.
        - `create` a new callable `Exec` object.
        - `run` a command with auto-escaped arguments.
        - `getCommand` returns the string to be used by `\exec()`.
        - `getLastCommand` returns the string last used by a previous call to `run()`.
        - `getOutput` of last execution.
        - `getStatus` code of last execution.
        - `__invoke` allows using this object as a callable by calling `run()`.
    - `C` `Folder` handles file system folders.
        - `ensure` a folder exists by creating it if missing and throw an exception on failure.
        - `delete` a directory and all of its contents recursively.
        - `deleteContents` of a folder recursively, but not the folder itself.
    - `C` `Multiline` string class for working with lines of text.
        - `create` a new Multiline object from a string.
        - `__toString` returns a single string using the current EOL style.
        - `getEol` Get EOL style ending.
        - `getMaxLength` of all lines.
        - `setEol` Set EOL used by this Multiline string.
        - `append` text to a certain line.
        - `prefix` all lines with a string.
        - `pad` all lines to the same length using `str_pad`.
        - `getLineIndexByCharacterPosition` returns the line index containing a certain position.
    - `C` `Numeric` validates and converts mixed types to numeric types.
        - `ensure` integer or or float value by safely converting.
        - `ensureInteger` values by safely converting.
        - `ensureFloat` values by safely converting.
    - `C` `OS` OS.
        - `isWindows` returns true if the current OS is Windows.
        - `hasBinary` returns true if the binary is available in any of the PATHs.
    - `C` `Path` helps keep the directory separator/implode/trim/replace madness away.
        - `combine` any amount of strings into a path.
        - `localize` directory separators for any file path according to current environment.
        - `contains` returns true if a base path contains a needle.
        - `isAbsolute` checks for an absolute UNIX, Windows or scheme:// path.
    - `C` `Strings` class provides methods for string handling missing from default PHP.
        - `startsWith` returns true if the input begins with a prefix.
        - `endsWith` returns true if the input ends with a suffix.
        - `getMostFrequentNeedle` by counting occurences of each needle in haystack.
        - `escapeControlChars` by replacing line feeds, tabs, etc. to their escaped representation.
        - `padMultibyte` strings to a certain length with another string.
        - `getCommonPrefix` of two strings.
        - `getCommonSuffix` of two strings.
        - `reverse` a string.
        - `groupByCommonPrefix` returns an array with a common key and a list of differing suffixes.
        - `groupByCommonSuffix` returns an array with a common key and a list of differing suffixes.
    - `C` `Type` returns PHP type information.
        - `summarize` the type of any variable.
        - `getClassName` returns the class name without namespaces.
    - `C` `VcsVersionInfo` enriches an internal VersionInfo with the latest tag and current repository state.
        - `__construct`
        - `getInfo`
        - `getVersion`
        - `getName`
    - `C` `VersionInfo` consists of a package name and version.
        - `__construct`
        - `getInfo`
        - `getVersion`
        - `getName`
- `N` `nochso\Omni\Format`
    - `C` `Bytes` formats a quantity of bytes using different suffixes and binary or decimal base.
        - `create` a new Bytes instance.
        - `setBase` to use when converting to different units.
        - `setSuffix` style for units.
        - `setPrecision` of floating point values after the decimal point.
        - `enablePrecisionTrimming` to remove trailing zeroes and decimal points.
        - `disablePrecisionTrimming` to keep trailing zeroes.
        - `format` a quantity of bytes for human consumption.
    - `C` `Duration` formats seconds or DateInterval objects as human readable strings.
        - `__construct`
        - `create` a new Duration.
        - `addFormat` to the existing defaults and set it as the current format.
        - `setFormat` to use by its custom name or one of the default Duration constants.
        - `limitPeriods` limits the amount of significant periods (years, months, etc.) to keep.
        - `format` an amount of seconds or a `DateInterval` object.
    - `C` `Quantity` formats a string depending on quantity (many, one or zero).
        - `format` a string depending on a quantity.
- `N` `nochso\Omni\PhpCsFixer`
    - `C` `DefaultFinder` respects ignore files for Git, Mercurial and Darcs.
        - `createIn`
        - `__construct`
        - `getNames`
        - `getVcsIgnoreFiles`
    - `C` `PrettyPSR` lies inbetween FixerInterface's PSR2 and Symfony level.
        - `createIn`
        - `__construct`
        - `getDefaultFixers`


# Change log
See [CHANGELOG.md](CHANGELOG.md) for the full history of changes between releases.

## [Unreleased]


## [0.3.7] - 2016-04-16

### Fixed
- Fix handling of absolute `scheme://` paths in `Path::combine`




# Contributing
Feedback, bug reports and pull requests are always welcome.

Please read the [contributing guide](CONTRIBUTING.md) for instructions.

# License
This project is released under the MIT license. See [LICENSE.md](LICENSE.md) for the full text.
