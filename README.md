# nochso/omni

[![License](https://poser.pugx.org/nochso/omni/license)](https://packagist.org/packages/nochso/omni)
[![GitHub tag](https://img.shields.io/github/tag/nochso/omni.svg)](https://github.com/nochso/omni/releases)
[![Build Status](https://travis-ci.org/nochso/omni.svg?branch=master)](https://travis-ci.org/nochso/omni)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbc0e55a-bc4d-4936-9d27-72dfb913c323/mini.png)](https://insight.sensiolabs.com/projects/fbc0e55a-bc4d-4936-9d27-72dfb913c323)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nochso/omni/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nochso/omni/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/nochso/omni/badge.svg?branch=master)](https://coveralls.io/github/nochso/omni?branch=master)

nochso/omni helps with every day nuisances like path or EOL handling.

- Fully tested.
- Strictly follows [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html).
- Clean code with [low complexity](https://www.cs.helsinki.fi/u/luontola/tdd-2009/ext/ObjectCalisthenics.pdf).
- No dependencies other than [patchwork/utf8](https://packagist.org/packages/patchwork/utf8).
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

## API
This is a summary of namespaces, classes, interfaces, traits and their public/protected methods.

- `N` `nochso\Omni`
    - `C` `ArrayCollection` wraps an array, providing common collection methods.
        - `__construct()`
        - `add()`
        - `set()` or replace the element at a specific index.
        - `remove()` and return the element at the zero based index.
        - `first()` sets the internal pointer to the first element and returns it.
        - `last()` sets the internal pointer to the last element and returns it.
        - `toArray()` returns a plain old array.
        - `apply()` a callable to every element.
        - `count()` elements of an object.
        - `getIterator()` returns an external iterator.
        - `offsetExists()` allows using `isset`.
        - `offsetGet()` allows array access, e.g. `$list[2]`.
        - `offsetSet()` allows writing to arrays `$list[2] = 'foo'`.
        - `offsetUnset()` allows using `unset`.
    - `C` `Arrays` class provides methods for array manipulation missing from default PHP.
        - `flatten()` arrays and non-arrays recursively into a 2D array.
    - `C` `Dot` allows easy access to multi-dimensional arrays using dot notation.
        - `get()` the value of the element at the given dot key path.
        - `has()` returns true if an element exists at the given dot key path.
        - `set()` a value at a certain path by creating missing elements and overwriting non-array values.
        - `trySet()` sets a value at a certain path, expecting arrays or missing elements along the way.
        - `remove()` an element if it exists.
        - `flatten()` the array into a single dimension array with dot paths as keys.
    - `C` `DotArray` holds a multi-dimensional array and wraps the static API of `\nochso\Omni\Dot`.
        - `__construct()`
        - `getArray()` returns the complete array.
        - `get()` the value of the element at the given dot key path.
        - `has()` returns true if an element exists at the given dot key path.
        - `set()` a value at a certain path by creating missing elements and overwriting non-array values.
        - `trySet()` sets a value at a certain path, expecting arrays or missing elements along the way.
        - `remove()` an element if it exists.
        - `flatten()` the array into a single dimension array with escaped dot paths as keys.
        - `getIterator()` allows you to iterate over a flattened array using `foreach`.
        - `offsetExists()` allows using `isset($da['a.b'])`.
        - `offsetGet()` allows array access, e.g. `$da['a.b']`.
        - `offsetSet()` allows writing to arrays `$da['a.b'] = 'foo'`.
        - `offsetUnset()` allows using `unset($da['a.b'])`.
    - `C` `EOL` detects, converts and returns information about line-endings.
        - `__construct()`
        - `__toString()` casts to/returns the raw line ending string.
        - `getName()` of line ending, e.g. `LF`.
        - `getDescription()` of line ending, e.g. `Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS`.
        - `apply()` this EOL style to a string.
        - `detect()` the EOL style of a string and return an EOL representation.
        - `detectDefault()` falls back to a default EOL style on failure.
    - `C` `Folder` handles file system folders.
        - `ensure()` a folder exists by creating it if missing and throw an exception on failure.
    - `C` `Multiline` string class for working with lines of text.
        - `create()` a new Multiline object using a preferred EOL style.
        - `__toString()` returns a single string using the current EOL style.
        - `getEol()` Get EOL style ending.
        - `getMaxLength()` of all lines.
        - `setEol()` Set EOL used by this Multiline string.
        - `append()` text to a certain line.
        - `prefix()` all lines with a string.
        - `pad()` all lines to the same length using `str_pad`.
    - `C` `Path` helps keep the directory separator/implode/trim/replace madness away.
        - `combine()` any amount of strings into a path.
        - `localize()` directory separators for any file path according to current environment.
        - `contains()` returns true if a base path contains a needle.
        - `isAbsolute()` checks for an absolute UNIX or Windows path.
    - `C` `Strings` class provides methods for string handling missing from default PHP.
        - `startsWith()` returns true if the input begins with a prefix.
        - `endsWith()` returns true if the input ends with a suffix.
        - `getMostFrequentNeedle()` by counting occurences of each needle in haystack.
        - `escapeControlChars()` by replacing line feeds, tabs, etc. to their escaped representation.
    - `C` `Type` returns PHP type information.
        - `summarize()` the type of any variable.
        - `getClassName()` returns the class name without namespaces.
    - `C` `VersionInfo` consists of a package name and version.
        - `__construct()`
        - `getInfo()`
        - `getVersion()`
        - `getName()`
- `N` `nochso\Omni\PhpCsFixer`
    - `C` `DefaultFinder` respects ignore files for Git, Mercurial and Darcs.
        - `createIn()`
        - `__construct()`
        - `getNames()`
        - `getVcsIgnoreFiles()`
    - `C` `PrettyPSR` lies inbetween FixerInterface's PSR2 and Symfony level.
        - `createIn()`
        - `__construct()`
        - `getDefaultFixers()`

## Change log
See [CHANGELOG.md](CHANGELOG.md) for the full history of changes between releases.

## Contributing
Feedback, bug reports and pull requests are always welcome.

When writing code, please follow the **PSR2** coding style and **PSR4** autoloading standards.

## License
This project is released under the MIT license. See [LICENSE.md](LICENSE.md) for the full text.
