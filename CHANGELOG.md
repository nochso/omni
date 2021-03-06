# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

Major changes are prefixed with `MAJOR`.

<!--
Added      for new features.
Changed    for changes in existing functionality.
Deprecated for once-stable features removed in upcoming releases.
Removed    for deprecated features removed in this release.
Fixed      for any bug fixes.
Security   to invite users to upgrade in case of vulnerabilities.
-->

## [Unreleased]

## [0.3.8]
### Added
- Add placeholder `%s` for quantity in `Format\Quantity`.

### Changed
- `DurationFormat` can now handle milliseconds.

## [0.3.7] - 2016-04-16
### Fixed
- Fix handling of absolute `scheme://` paths in `Path::combine`

## [0.3.6]
### Added
- New method `Multiline::getLineIndexByCharacterPosition` returns the line index containing a certain position.

### Changed
- `Path::isAbsolute` recognizes `scheme://` URIs as absolute.

## [0.3.5]
### Fixed
- `Path::combine` and `Path::localize` now keep a `scheme://` untouched.
- `Multiline::create` now removes left over line feeds.

## [0.3.4]
### Added
- New class `Numeric` for safely converting mixed values to int/float.
- New class `Format\Quantity` for formatting strings depending on a quantity.
- New class `Format\Bytes` for formatting a quantity of bytes using different suffixes and binary or decimal base.
- New class `Format\Duration` formats seconds or DateInterval objects as human readable strings.

### Changed
- Make `Exec` only escape arguments when needed.

### Fixed
- Improved tests to reflect Windows behaviour.
- Make `Folder` delete read-only files on Windows (for example to delete a `.git` folder)
- Make `Exec` escape properly on Windows.

## [0.3.3]
### Added
- New class `VcsVersionInfo` that wraps and enriches `VersionInfo` with the latest release tag from Git and Mercurial
  repositories.
- New class `OS` with methods `isWindows` and `hasBinary`.
- New class `Exec` to create objects that help manage `exec()` calls.
- New `Folder` methods `delete` and `deleteContents`
- New `Strings` methods:
    - `getCommonPrefix`
    - `getCommonSuffix`
    - `groupByCommonPrefix`
    - `groupByCommonSuffix`
    - `reverse`

### Fixed
- `Dot::get` and `Dot::has` no longer require passing the array by reference.

## [0.3.2] - 2016-03-16
### Added
- New method `Strings::padMultibyte` mirroring the standard `str_pad`.
- Dependency on [symfony/polyfill-mbstring](https://packagist.org/packages/symfony/polyfill-mbstring).

### Fixed
- Handle empty strings correctly in `Strings::startsWith`.
- Fix invalid regular expression in `DefaultFinder`.

### Removed
- `patchwork/utf8` was removed as a dependency.

## [0.3.1] - 2016-03-14
### Changed
- Open up `DotArray` for extension by removing `final` modifier.

## [0.3.0] - 2016-03-08
### Added
- `MAJOR` New class `nochso\Omni\DotArray` to wrap an array replacing the newly named `Dot` class. Implements `\ArrayAccess`.
- New methods in `Dot` and `DotArray`:
    - `set`
    - `trySet`
    - `remove`
    - `flatten()` the array into a single dimension array with escaped dot paths as keys.
- Added implementation of `\IteratorAggregate` to `DotArray` to iterate over escaped keys and all values.
- New method `nochso\Omni\Strings::escapeControlChars`.
- New Method `nochso\Omni\Path::isAbsolute`.
- Dependency on [patchwork/utf8](https://packagist.org/packages/patchwork/utf8).

### Changed
- `MAJOR` Renamed class `DotArray` to `Dot`.
    - Keep in mind there's still a `DotArray` which now wraps the static methods of `Dot`.
- Enable caching by default for `PrettyPSR`.

### Fixed
- Fix UTF8 handling of `Multiline::getMaxLength`, `Multiline::pad` and `Strings::getMostFrequentNeedle`
- `EOL` will now throw an exception constructed with an unknown line ending.

## [0.2.0] - 2016-02-28
### Added
- New class `nochso\Omni\ArrayCollection` for creating collection classes.
- New method `nochso\Omni\Multiline::append` to append text to the last or a certain line.

### Changed
- `MAJOR` `nochso\Omni\Multiline` now inherits from `nochso\Omni\ArrayCollection`.
    - `__construct()` only takes one parameter from now on because of the parent constructor.

### Removed
- `MAJOR` Removed methods from `nochso\Omni\Multiline`.
    - `__construct` Use the parent constructor or `create()` instead.
    - `getLines` Use `toArray()` instead.

## [0.1.1] - 2016-02-28
### Added
- New class `nochso\Omni\DotArray` for dot-notation access to nested arrays.

## 0.1.0 - 2016-02-27
### Added
- First public release.

[Unreleased]: https://github.com/nochso/omni/compare/0.3.8...HEAD
[0.3.8]: https://github.com/nochso/omni/compare/0.3.7...0.3.8
[0.3.7]: https://github.com/nochso/omni/compare/0.3.6...0.3.7
[0.3.6]: https://github.com/nochso/omni/compare/0.3.5...0.3.6
[0.3.5]: https://github.com/nochso/omni/compare/0.3.4...0.3.5
[0.3.4]: https://github.com/nochso/omni/compare/0.3.3...0.3.4
[0.3.3]: https://github.com/nochso/omni/compare/0.3.2...0.3.3
[0.3.2]: https://github.com/nochso/omni/compare/0.3.1...0.3.2
[0.3.1]: https://github.com/nochso/omni/compare/0.3.0...0.3.1
[0.3.0]: https://github.com/nochso/omni/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/nochso/omni/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/nochso/omni/compare/0.1.0...0.1.1
