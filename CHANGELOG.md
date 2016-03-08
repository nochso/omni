# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

Major changes are prefixed with `MAJOR`.

## [0.3.0]
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

[Unreleased]: https://github.com/nochso/omni/compare/0.3.0...HEAD
[0.3.0]: https://github.com/nochso/omni/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/nochso/omni/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/nochso/omni/compare/0.1.0...0.1.1
