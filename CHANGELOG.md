# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

Major changes are prefixed with `MAJOR`.

## [Unreleased]
### Added
- Dependency on [patchwork/utf8](https://packagist.org/packages/patchwork/utf8).

### Fixed
- Fix UTF8 handling of `Multiline::getMaxLength`

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

[Unreleased]: https://github.com/nochso/omni/compare/0.2.0...HEAD
[0.2.0]: https://github.com/nochso/omni/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/nochso/omni/compare/0.1.0...0.1.1
