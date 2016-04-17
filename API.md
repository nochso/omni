API documentation
=================

- [Namespace nochso\Omni](#namespace-nochsoomni)
- [Namespace nochso\Omni\Format](#namespace-nochsoomniformat)
- [Namespace nochso\Omni\PhpCsFixer](#namespace-nochsoomniphpcsfixer)

This is an auto-generated documentation of namespaces, classes, interfaces, traits and public/protected methods.

## Namespace nochso\Omni
- [Class ArrayCollection](#class-arraycollection)
- [Class Arrays (final)](#class-arrays-final)
- [Class Dot (final)](#class-dot-final)
- [Class DotArray](#class-dotarray)
- [Class EOL (final)](#class-eol-final)
- [Class Exec](#class-exec)
- [Class Folder (final)](#class-folder-final)
- [Class Multiline (final)](#class-multiline-final)
- [Class Numeric](#class-numeric)
- [Class OS](#class-os)
- [Class Path (final)](#class-path-final)
- [Class Strings (final)](#class-strings-final)
- [Class Type (final)](#class-type-final)
- [Class VcsVersionInfo (final)](#class-vcsversioninfo-final)
- [Class VersionInfo (final)](#class-versioninfo-final)

* * * * *
### Class ArrayCollection
```php
class ArrayCollection implements \Countable, \IteratorAggregate, \ArrayAccess
```

ArrayCollection wraps an array, providing common collection methods.

- [__construct()](#__construct)
- [add()](#add)
- [set()](#set)
- [remove()](#remove)
- [first()](#first)
- [last()](#last)
- [toArray()](#toarray)
- [apply()](#apply)
- [count()](#count)
- [getIterator()](#getiterator)
- [offsetExists()](#offsetexists)
- [offsetGet()](#offsetget)
- [offsetSet()](#offsetset)
- [offsetUnset()](#offsetunset)

#### __construct()

```php
public function __construct(array $list = [])
```



1. `$list` &mdash; `mixed` 

returns *nothing*


#### add()

```php
public function add($element)
```



1. `$element` &mdash; `mixed` 

returns `$this`


#### set()

```php
public function set($index, $element)
```

**set** or replace the element at a specific index.

1. `$index` &mdash; `mixed` 
2. `$element` &mdash; `mixed` 

returns `$this`


#### remove()

```php
public function remove($index)
```

**remove** and return the element at the zero based index.

1. `$index` &mdash; `mixed` 

returns `null|mixed` &mdash; Null if the element didn't exist.


#### first()

```php
public function first()
```

**first** sets the internal pointer to the first element and returns it.


returns `mixed` &mdash; the value of the first array element, or false if the array is empty.


#### last()

```php
public function last()
```

**last** sets the internal pointer to the last element and returns it.


returns `mixed` &mdash; the value of the last element or false for empty array.


#### toArray()

```php
public function toArray()
```

**toArray** returns a plain old array.


returns `array`


#### apply()

```php
public function apply(callable $callable)
```

**apply** a callable to every element.

1. `$callable` &mdash; `mixed` 

returns `$this`


#### count()

```php
public function count()
```

**count** elements of an object.


returns `int` &mdash; The custom count as an integer.


#### getIterator()

```php
public function getIterator()
```

**getIterator** returns an external iterator.


returns `\Traversable` &mdash; An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>


#### offsetExists()

```php
public function offsetExists($offset)
```

**offsetExists** allows using `isset`.

1. `$offset` &mdash; `mixed`  &mdash; An offset to check for.

returns `bool` &mdash; true on success or false on failure.


#### offsetGet()

```php
public function offsetGet($offset)
```

**offsetGet** allows array access, e.g. `$list[2]`.

1. `$offset` &mdash; `mixed`  &mdash; The offset to retrieve.

returns `mixed` &mdash; Can return all value types.


#### offsetSet()

```php
public function offsetSet($offset, $value)
```

**offsetSet** allows writing to arrays `$list[2] = 'foo'`.

1. `$offset` &mdash; `mixed`  &mdash; The offset to assign the value to.
2. `$value` &mdash; `mixed`  &mdash; The value to set.

returns `void`


#### offsetUnset()

```php
public function offsetUnset($offset)
```

**offsetUnset** allows using `unset`.

1. `$offset` &mdash; `mixed`  &mdash; The offset to unset.

returns `void`



* * * * *
### Class Arrays (final)
```php
final class Arrays
```

Arrays class provides methods for array manipulation missing from default PHP.

- [flatten()](#flatten)

#### flatten()

```php
public static function flatten(...$elements)
```

**flatten** arrays and non-arrays recursively into a 2D array.

1. `$elements` &mdash; `array`  &mdash; Any amount of arrays and non-arrays.

returns `array`



* * * * *
### Class Dot (final)
```php
final class Dot
```

Dot allows easy access to multi-dimensional arrays using dot notation.

- [get()](#get)
- [has()](#has)
- [set()](#set-1)
- [trySet()](#tryset)
- [remove()](#remove-1)
- [flatten()](#flatten-1)

#### get()

```php
public static function get(array $array, $path, $default = null)
```

**get** the value of the element at the given dot key path.

1. `$array` &mdash; `array`  &mdash; Multi-dimensional array to access.
2. `$path` &mdash; `string`  &mdash; Dot-notation key path. e.g. `parent.child`
3. `$default` &mdash; `null|mixed`  &mdash; Default value to return

returns `mixed`


#### has()

```php
public static function has(array $array, $path)
```

**has** returns true if an element exists at the given dot key path.

1. `$array` &mdash; `array`  &mdash; Multi-dimensionsal array to search.
2. `$path` &mdash; `string`  &mdash; Dot-notation key path to search.

returns `bool`


#### set()

```php
public static function set(array &$array, $path, $value)
```

**set** a value at a certain path by creating missing elements and overwriting non-array values.

1. `&$array` &mdash; `mixed` 
2. `$path` &mdash; `mixed` 
3. `$value` &mdash; `mixed` 

returns *nothing*

If any of the visited elements is not an array, it will be replaced with an array.

This will overwrite existing non-array values.
#### trySet()

```php
public static function trySet(array &$array, $path, $value)
```

**trySet** sets a value at a certain path, expecting arrays or missing elements along the way.

1. `&$array` &mdash; `mixed` 
2. `$path` &mdash; `mixed` 
3. `$value` &mdash; `mixed` 

returns *nothing*

If any of the visited elements is not an array, a \RuntimeException is thrown.

Use this if you want to avoid overwriting existing non-array values.
#### remove()

```php
public static function remove(array &$array, $path)
```

**remove** an element if it exists.

1. `&$array` &mdash; `mixed` 
2. `$path` &mdash; `mixed` 

returns *nothing*


#### flatten()

```php
public static function flatten(array $array, $parent = null)
```

**flatten** the array into a single dimension array with dot paths as keys.

1. `$array` &mdash; `mixed` 
2. `$parent` &mdash; `mixed` 

returns `array`



* * * * *
### Class DotArray
```php
class DotArray implements \ArrayAccess, \IteratorAggregate
```

DotArray holds a multi-dimensional array and wraps the static API of `\nochso\Omni\Dot`.

e.g.
```php
$array = [
    'a' => [
         'b' => 'c'
    ]
];
$da = new DotArray();
echo $da->get('a.b'); // 'c'
```

ArrayAccess is also possible:
```php
$da['a.b'] = 'c';
```

You can also escape parts of the path:
```php
$da['a\.b'] === $da->getArray()['a.b'] // true
```

- [__construct()](#__construct-1)
- [getArray()](#getarray)
- [get()](#get-1)
- [has()](#has-1)
- [set()](#set-2)
- [trySet()](#tryset-1)
- [remove()](#remove-2)
- [flatten()](#flatten-2)
- [getIterator()](#getiterator-1)
- [offsetExists()](#offsetexists-1)
- [offsetGet()](#offsetget-1)
- [offsetSet()](#offsetset-1)
- [offsetUnset()](#offsetunset-1)

#### __construct()

```php
public function __construct(array $array = [])
```



1. `$array` &mdash; `array`  &mdash; Any (nested) array.

returns *nothing*


#### getArray()

```php
public function getArray()
```

**getArray** returns the complete array.


returns `array`


#### get()

```php
public function get($path, $default = null)
```

**get** the value of the element at the given dot key path.

1. `$path` &mdash; `string`  &mdash; Dot-notation key path. e.g. `parent.child`
2. `$default` &mdash; `null|mixed`  &mdash; Default value to return

returns `mixed`


#### has()

```php
public function has($path)
```

**has** returns true if an element exists at the given dot key path.

1. `$path` &mdash; `string`  &mdash; Dot-notation key path to search.

returns `bool`


#### set()

```php
public function set($path, $value)
```

**set** a value at a certain path by creating missing elements and overwriting non-array values.

1. `$path` &mdash; `mixed` 
2. `$value` &mdash; `mixed` 

returns *nothing*

If any of the visited elements is not an array, it will be replaced with an array.

This will overwrite existing non-array values.
#### trySet()

```php
public function trySet($path, $value)
```

**trySet** sets a value at a certain path, expecting arrays or missing elements along the way.

1. `$path` &mdash; `mixed` 
2. `$value` &mdash; `mixed` 

returns *nothing*

If any of the visited elements is not an array, a \RuntimeException is thrown.

Use this if you want to avoid overwriting existing non-array values.
#### remove()

```php
public function remove($path)
```

**remove** an element if it exists.

1. `$path` &mdash; `mixed` 

returns *nothing*


#### flatten()

```php
public function flatten()
```

**flatten** the array into a single dimension array with escaped dot paths as keys.


returns `array`

Dots within specific keys are escaped.
#### getIterator()

```php
public function getIterator()
```

**getIterator** allows you to iterate over a flattened array using `foreach`.


returns `\Traversable` &mdash; An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>

Keys are escaped and thus safe to use.
#### offsetExists()

```php
public function offsetExists($offset)
```

**offsetExists** allows using `isset($da['a.b'])`.

1. `$offset` &mdash; `mixed`  &mdash; An offset to check for.

returns `bool` &mdash; true on success or false on failure.


#### offsetGet()

```php
public function offsetGet($offset)
```

**offsetGet** allows array access, e.g. `$da['a.b']`.

1. `$offset` &mdash; `mixed`  &mdash; The offset to retrieve.

returns `mixed` &mdash; Can return all value types.


#### offsetSet()

```php
public function offsetSet($offset, $value)
```

**offsetSet** allows writing to arrays `$da['a.b'] = 'foo'`.

1. `$offset` &mdash; `mixed`  &mdash; The offset to assign the value to.
2. `$value` &mdash; `mixed`  &mdash; The value to set.

returns `void`


#### offsetUnset()

```php
public function offsetUnset($offset)
```

**offsetUnset** allows using `unset($da['a.b'])`.

1. `$offset` &mdash; `mixed`  &mdash; The offset to unset.

returns `void`



* * * * *
### Class EOL (final)
```php
final class EOL
```

EOL detects, converts and returns information about line-endings.

- [__construct()](#__construct-2)
- [__toString()](#__tostring)
- [getName()](#getname)
- [getDescription()](#getdescription)
- [apply()](#apply-1)
- [detect()](#detect)
- [detectDefault()](#detectdefault)

#### __construct()

```php
public function __construct($eol)
```



1. `$eol` &mdash; `string`  &mdash; Line ending. See the EOL_* class constants.

returns *nothing*


#### __toString()

```php
public function __toString()
```

**__toString** casts to/returns the raw line ending string.


returns `string`


#### getName()

```php
public function getName()
```

**getName** of line ending, e.g. `LF`.


returns `string`


#### getDescription()

```php
public function getDescription()
```

**getDescription** of line ending, e.g. `Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS`.


returns `string`


#### apply()

```php
public function apply($input)
```

**apply** this EOL style to a string.

1. `$input` &mdash; `string`  &mdash; Input to be converted.

returns `string`


#### detect()

```php
public static function detect($input)
```

**detect** the EOL style of a string and return an EOL representation.

1. `$input` &mdash; `string`  &mdash; Input string to be analyzed.

returns `\nochso\Omni\EOL`


#### detectDefault()

```php
public static function detectDefault($input, $default = self::EOL_LF)
```

**detectDefault** falls back to a default EOL style on failure.

1. `$input` &mdash; `string`  &mdash; Input string to be analyzed.
2. `$default` &mdash; `string`  &mdash; Optional, defaults to "\n". The default line ending to use when $strict is false. See the `EOL::EOL_*` constants.

returns `\nochso\Omni\EOL`



* * * * *
### Class Exec
```php
class Exec
```

Exec creates objects that help manage `\exec()` calls.

The returned object itself is callable, which is the same as calling `run()`.

Arguments are automatically escaped if needed.

Methods `run()`, `create()` and `__invoke()` take any amount of arguments.
If you have an array of arguments, unpack it first: `run(...$args)`

- [create()](#create)
- [run()](#run)
- [getCommand()](#getcommand)
- [getLastCommand()](#getlastcommand)
- [getOutput()](#getoutput)
- [getStatus()](#getstatus)
- [__invoke()](#__invoke)

#### create()

```php
public static function create(...$prefixes)
```

**create** a new callable `Exec` object.

1. `$prefixes` &mdash; `mixed` 

returns `\nochso\Omni\Exec`


#### run()

```php
public function run(...$arguments)
```

**run** a command with auto-escaped arguments.

1. `$arguments` &mdash; `mixed` 

returns `$this`


#### getCommand()

```php
public function getCommand(...$arguments)
```

**getCommand** returns the string to be used by `\exec()`.

1. `$arguments` &mdash; `mixed` 

returns `string`


#### getLastCommand()

```php
public function getLastCommand()
```

**getLastCommand** returns the string last used by a previous call to `run()`.


returns `string|null`


#### getOutput()

```php
public function getOutput()
```

**getOutput** of last execution.


returns `string[]`


#### getStatus()

```php
public function getStatus()
```

**getStatus** code of last execution.


returns `int`


#### __invoke()

```php
public function __invoke(...$arguments)
```

**__invoke** allows using this object as a callable by calling `run()`.

1. `$arguments` &mdash; `mixed` 

returns `\nochso\Omni\Exec`

e.g. `$runner('argument');`

* * * * *
### Class Folder (final)
```php
final class Folder
```

Folder handles file system folders.

- [ensure()](#ensure)
- [delete()](#delete)
- [deleteContents()](#deletecontents)

#### ensure()

```php
public static function ensure($path, $mode = 0777)
```

**ensure** a folder exists by creating it if missing and throw an exception on failure.

1. `$path` &mdash; `mixed` 
2. `$mode` &mdash; `int`  &mdash; Optional, defaults to 0777.

returns *nothing*


#### delete()

```php
public static function delete($path)
```

**delete** a directory and all of its contents recursively.

1. `$path` &mdash; `string`  &mdash; Path of folder to delete

returns *nothing*


#### deleteContents()

```php
public static function deleteContents($path)
```

**deleteContents** of a folder recursively, but not the folder itself.

1. `$path` &mdash; `string`  &mdash; Path of folder whose contents will be deleted

returns *nothing*

On Windows systems this will try to remove the read-only attribute if needed.

* * * * *
### Class Multiline (final)
```php
final class Multiline extends ArrayCollection
```

Multiline string class for working with lines of text.

- [create()](#create-1)
- [__toString()](#__tostring-1)
- [getEol()](#geteol)
- [getMaxLength()](#getmaxlength)
- [setEol()](#seteol)
- [append()](#append)
- [prefix()](#prefix)
- [pad()](#pad)
- [getLineIndexByCharacterPosition()](#getlineindexbycharacterposition)

#### create()

```php
public static function create($input, $defaultEol = \nochso\Omni\EOL::EOL_LF)
```

**create** a new Multiline object from a string.

1. `$input` &mdash; `string`  &mdash; A string to split into a Multiline object
2. `$defaultEol` &mdash; `string`  &mdash; Default end-of-line type to split the input by. This is a fallback in case it could
                          not be detected from the input string. Optional, defaults to `EOL::EOL_LF` i.e. "\n".
                          See the `EOL::EOL_*` class constants.

returns `\nochso\Omni\Multiline`

First the input string is split into lines by the detected end-of-line
character. Afterwards any extra EOL chars will be trimmed.
#### __toString()

```php
public function __toString()
```

**__toString** returns a single string using the current EOL style.


returns `string`


#### getEol()

```php
public function getEol()
```

**getEol** Get EOL style ending.


returns `\nochso\Omni\EOL`


#### getMaxLength()

```php
public function getMaxLength()
```

**getMaxLength** of all lines.


returns `int`


#### setEol()

```php
public function setEol($eol)
```

**setEol** Set EOL used by this Multiline string.

1. `$eol` &mdash; `\nochso\Omni\EOL|string`  &mdash; Either an `EOL` object or a string ("\r\n" or "\n")

returns `$this`


#### append()

```php
public function append($text, $index = null)
```

**append** text to a certain line.

1. `$text` &mdash; `mixed` 
2. `$index` &mdash; `null|int`  &mdash; Optional, defaults to the last line. Other

returns `$this`


#### prefix()

```php
public function prefix($prefix)
```

**prefix** all lines with a string.

1. `$prefix` &mdash; `string`  &mdash; The prefix to add to the start of the string.

returns `string`


#### pad()

```php
public function pad($length = null, $padding = ' ', $paddingType = STR_PAD_RIGHT)
```

**pad** all lines to the same length using `str_pad`.

1. `$length` &mdash; `int`  &mdash; If length is larger than the maximum line length, all lines will be padded up to the
                           given length. If length is null, the maximum of all line lengths is used. Optional,
                           defaults to null.
2. `$padding` &mdash; `string`  &mdash; Optional, defaults to a space character. Can be more than one character. The padding
                           may be truncated if the required number of padding characters can't be evenly
                           divided.
3. `$paddingType` &mdash; `int`  &mdash; Optional argument pad_type can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH.
                           Defaults to STR_PAD_RIGHT.

returns `string`


#### getLineIndexByCharacterPosition()

```php
public function getLineIndexByCharacterPosition($characterPosition)
```

**getLineIndexByCharacterPosition** returns the line index containing a certain position.

1. `$characterPosition` &mdash; `int`  &mdash; Position of a character as if Multiline was a raw string.

returns `int|null` &mdash; The array index of the line containing the character position.



* * * * *
### Class Numeric
```php
class Numeric
```

Numeric validates and converts mixed types to numeric types.

- [ensure()](#ensure-1)
- [ensureInteger()](#ensureinteger)
- [ensureFloat()](#ensurefloat)

#### ensure()

```php
public static function ensure($value)
```

**ensure** integer or or float value by safely converting.

1. `$value` &mdash; `mixed` 

returns `int|float`

Safe conversions to int or float:

```
'-1'    => -1
'+00.0' => 0.0
'1'     => 1
' 1 '    => 1
'01'    => 1
'0.1'   => 0.1
'.1'    => 0.1
```

Invalid conversions:

```
'x'
'0a'
''
'-'
' '
'0+1'
'.'
','
```
#### ensureInteger()

```php
public static function ensureInteger($value)
```

**ensureInteger** values by safely converting.

1. `$value` &mdash; `mixed` 

returns `int`

These are safe conversions because no information is lost:

```
1      => 1
'1.00' => 1
'1'    => 1
'+1'   => 1
```

These are invalid conversions because information would be lost:

```
0.1
'0.1'
'.1'
```

If you don't care about this, you should cast to int instead: `(int)$value`
#### ensureFloat()

```php
public static function ensureFloat($value)
```

**ensureFloat** values by safely converting.

1. `$value` &mdash; `mixed` 

returns `float`

For example the following conversions are safe:

```
'0'     => 0.0
'0.0'   => 0.0
'0.1'   => 0.1
 '-5.1' => 5.1
```

* * * * *
### Class OS
```php
class OS
```

OS.

- [isWindows()](#iswindows)
- [hasBinary()](#hasbinary)

#### isWindows()

```php
public static function isWindows($phpOs = PHP_OS)
```

**isWindows** returns true if the current OS is Windows.

1. `$phpOs` &mdash; `string`  &mdash; Optional, defaults to the PHP_OS constant.

returns `bool`


#### hasBinary()

```php
public static function hasBinary($binaryName)
```

**hasBinary** returns true if the binary is available in any of the PATHs.

1. `$binaryName` &mdash; `mixed` 

returns `bool`



* * * * *
### Class Path (final)
```php
final class Path
```

Path helps keep the directory separator/implode/trim/replace madness away.

- [combine()](#combine)
- [localize()](#localize)
- [contains()](#contains)
- [isAbsolute()](#isabsolute)

#### combine()

```php
public static function combine(...$paths)
```

**combine** any amount of strings into a path.

1. `$paths` &mdash; `string|array`  &mdash; One or as many parameters as you need. Both strings and arrays of strings can be mixed.

returns `string` &mdash; The combined paths. Note that the directory separators will be changed to reflect the local system.


#### localize()

```php
public static function localize($path, $directorySeparator = DIRECTORY_SEPARATOR)
```

**localize** directory separators for any file path according to current environment.

1. `$path` &mdash; `mixed` 
2. `$directorySeparator` &mdash; `mixed` 

returns `string`


#### contains()

```php
public static function contains($base, $needle)
```

**contains** returns true if a base path contains a needle.

1. `$base` &mdash; `string`  &mdash; Path to base directory.
2. `$needle` &mdash; `string`  &mdash; Needle that must exist within the base directory.

returns `bool` &mdash; True if both exist and needle does not escape the base folder.

Note that `realpath` is used on both base and needle: they need to exist or false is returned.

Use this for avoiding directory traversal outside of a base path.
#### isAbsolute()

```php
public static function isAbsolute($path)
```

**isAbsolute** checks for an absolute UNIX, Windows or scheme:// path.

1. `$path` &mdash; `mixed` 

returns `bool` &mdash; True if the path is absolute i.e. it should be safe to append a relative path to it.

Note that paths containing parent dots (`..`) can still be considered absolute.

* * * * *
### Class Strings (final)
```php
final class Strings
```

Strings class provides methods for string handling missing from default PHP.

`mb_*` methods are used where sensible, so make sure to pass UTF-8 strings.

- [startsWith()](#startswith)
- [endsWith()](#endswith)
- [getMostFrequentNeedle()](#getmostfrequentneedle)
- [escapeControlChars()](#escapecontrolchars)
- [padMultibyte()](#padmultibyte)
- [getCommonPrefix()](#getcommonprefix)
- [getCommonSuffix()](#getcommonsuffix)
- [reverse()](#reverse)
- [groupByCommonPrefix()](#groupbycommonprefix)
- [groupByCommonSuffix()](#groupbycommonsuffix)

#### startsWith()

```php
public static function startsWith($input, $prefix)
```

**startsWith** returns true if the input begins with a prefix.

1. `$input` &mdash; `mixed` 
2. `$prefix` &mdash; `mixed` 

returns `bool`


#### endsWith()

```php
public static function endsWith($input, $suffix)
```

**endsWith** returns true if the input ends with a suffix.

1. `$input` &mdash; `mixed` 
2. `$suffix` &mdash; `mixed` 

returns `bool`


#### getMostFrequentNeedle()

```php
public static function getMostFrequentNeedle($haystack, array $needles)
```

**getMostFrequentNeedle** by counting occurences of each needle in haystack.

1. `$haystack` &mdash; `string`  &mdash; Haystack to be searched in.
2. `$needles` &mdash; `array`  &mdash; Needles to be counted.

returns `string|null` &mdash; The most occuring needle. If counts are tied, the first tied needle is returned. If no
                    needles were found, `null` is returned.


#### escapeControlChars()

```php
public static function escapeControlChars($input)
```

**escapeControlChars** by replacing line feeds, tabs, etc. to their escaped representation.

1. `$input` &mdash; `mixed` 

returns `string`

e.g. an actual line feed will return '\n'
#### padMultibyte()

```php
public static function padMultibyte($input, $padLength, $padding = ' ', $paddingType = STR_PAD_RIGHT)
```

**padMultibyte** strings to a certain length with another string.

1. `$input` &mdash; `string`  &mdash; The input string to be padded.
2. `$padLength` &mdash; `int`  &mdash; If the pad is length smaller than the input length, no padding takes place.
3. `$padding` &mdash; `string`  &mdash; Optional, defaults to a space character. Can be more than one character. The padding
                           may be truncated if the required number of padding characters can't be evenly
                           divided.
4. `$paddingType` &mdash; `int`  &mdash; Optional, defaults to STR_PAD_RIGHT. Must be one of STR_PAD_LEFT, STR_PAD_RIGHT or
                           STR_PAD_BOTH.

returns `string` &mdash; The padded string.


#### getCommonPrefix()

```php
public static function getCommonPrefix($first, $second)
```

**getCommonPrefix** of two strings.

1. `$first` &mdash; `mixed` 
2. `$second` &mdash; `mixed` 

returns `string` &mdash; All common characters from the beginning of both strings.


#### getCommonSuffix()

```php
public static function getCommonSuffix($first, $second)
```

**getCommonSuffix** of two strings.

1. `$first` &mdash; `mixed` 
2. `$second` &mdash; `mixed` 

returns `string` &mdash; All common characters from the end of both strings.


#### reverse()

```php
public static function reverse($input)
```

**reverse** a string.

1. `$input` &mdash; `mixed` 

returns `string` &mdash; The reversed string.


#### groupByCommonPrefix()

```php
public static function groupByCommonPrefix($strings)
```

**groupByCommonPrefix** returns an array with a common key and a list of differing suffixes.

1. `$strings` &mdash; `mixed` 

returns `string[][]`

e.g. passing an array `['sameHERE', 'sameTHERE']` would return
```
'same' => [
   'HERE',
   'THERE',
]
```

This can be used to group several file paths by a common base.
#### groupByCommonSuffix()

```php
public static function groupByCommonSuffix($strings)
```

**groupByCommonSuffix** returns an array with a common key and a list of differing suffixes.

1. `$strings` &mdash; `mixed` 

returns `string[][]`

e.g. passing an array `['sameHERE', 'sameTHERE']` would return
```
'HERE' => [
   'same',
   'sameT',
]
```

* * * * *
### Class Type (final)
```php
final class Type
```

Type returns PHP type information.

Uses:

- writing nicer exception messages
- easier debugging

- [summarize()](#summarize)
- [getClassName()](#getclassname)

#### summarize()

```php
public static function summarize($value)
```

**summarize** the type of any variable.

1. `$value` &mdash; `mixed` 

returns `string` &mdash; Result of `get_class` for objects or `gettype` for anything else.


#### getClassName()

```php
public static function getClassName($object)
```

**getClassName** returns the class name without namespaces.

1. `$object` &mdash; `object|string`  &mdash; Object instance of a fully qualified name.

returns `string`



* * * * *
### Class VcsVersionInfo (final)
```php
final class VcsVersionInfo
```

VcsVersionInfo enriches an internal VersionInfo with the latest tag and current repository state.

If the working directory is clean and at an exact tag, only the tag is returned:

    1.0.0

If dirty and at an exact tag, `-dirty` is appended:

    1.0.0-dirty

If there are no tags present, the revision id is returned:

    4319e00

If there have been commits since a tag:

    0.3.1-14-gf602496-dirty

Where `14` is the amount of commits since tag `0.3.1`.

Internally a VersionInfo object is used. Note that this class does not extend from VersionInfo
as it uses different constructor parameters.

- [__construct()](#__construct-3)
- [getInfo()](#getinfo)
- [getVersion()](#getversion)
- [getName()](#getname-1)

#### __construct()

```php
public function __construct(
```



1. `$name` &mdash; `string`  &mdash; Package or application name.
2. `$fallBackVersion` &mdash; `string`  &mdash; Optional version to fall back on if no repository info was found.
3. `$repositoryRoot` &mdash; `string`  &mdash; Path the VCS repository root (e.g. folder that contains ".git", ".hg", etc.)
4. `$infoFormat` &mdash; `string`  &mdash; Optional format to use for `getInfo`. Defaults to `VersionInfo::INFO_FORMAT_DEFAULT`

returns *nothing*


#### getInfo()

```php
public function getInfo()
```




returns `string`


#### getVersion()

```php
public function getVersion()
```




returns `string`


#### getName()

```php
public function getName()
```




returns `string`



* * * * *
### Class VersionInfo (final)
```php
final class VersionInfo
```

VersionInfo consists of a package name and version.

- [__construct()](#__construct-4)
- [getInfo()](#getinfo-1)
- [getVersion()](#getversion-1)
- [getName()](#getname-2)

#### __construct()

```php
public function __construct($name, $version, $infoFormat = self::INFO_FORMAT_DEFAULT)
```



1. `$name` &mdash; `string`  &mdash; Package or application name.
2. `$version` &mdash; `string`  &mdash; Version without a prefix.
3. `$infoFormat` &mdash; `string`  &mdash; Optional format to use for `getInfo`. Defaults to `self::INFO_FORMAT_DEFAULT`

returns *nothing*


#### getInfo()

```php
public function getInfo()
```




returns `string`


#### getVersion()

```php
public function getVersion()
```




returns `string`


#### getName()

```php
public function getName()
```




returns `string`



## Namespace nochso\Omni\Format
- [Class Bytes](#class-bytes)
- [Class Duration](#class-duration)
- [Class Quantity](#class-quantity)

* * * * *
### Class Bytes
```php
class Bytes
```

Bytes formats a quantity of bytes using different suffixes and binary or decimal base.

By default a binary base and IEC suffixes are used:

```php
Bytes::create()->format(1100); // '1.07 KiB'
```

You can pick a base and suffix with `create()` or use the specifc setter methods.

- [create()](#create-2)
- [setBase()](#setbase)
- [setSuffix()](#setsuffix)
- [setPrecision()](#setprecision)
- [enablePrecisionTrimming()](#enableprecisiontrimming)
- [disablePrecisionTrimming()](#disableprecisiontrimming)
- [format()](#format)

#### create()

```php
public static function create($base = self::BASE_BINARY, $suffix = self::SUFFIX_IEC)
```

**create** a new Bytes instance.

1. `$base` &mdash; `int`  &mdash; The base to use when converting to different units. Must be one of the `Bytes::BASE_*`
                   constants. Optional, defaults to `BASE_BINARY`.
2. `$suffix` &mdash; `int`  &mdash; The suffix style for units. Must be one of the `Bytes::SUFFIX_*` constants. Optional,
                   defaults to SUFFIX_IEC (KiB, MiB, etc.)

returns `\nochso\Omni\Bytes`


#### setBase()

```php
public function setBase($base)
```

**setBase** to use when converting to different units.

1. `$base` &mdash; `int`  &mdash; Must be one of the `Bytes::BASE_*` constants.

returns `$this`


#### setSuffix()

```php
public function setSuffix($suffix)
```

**setSuffix** style for units.

1. `$suffix` &mdash; `int`  &mdash; Must be one of the `Bytes::SUFFIX_*` constants.

returns `$this`


#### setPrecision()

```php
public function setPrecision($precision)
```

**setPrecision** of floating point values after the decimal point.

1. `$precision` &mdash; `int`  &mdash; Any non-negative integer.

returns `$this`


#### enablePrecisionTrimming()

```php
public function enablePrecisionTrimming()
```

**enablePrecisionTrimming** to remove trailing zeroes and decimal points.


returns `$this`


#### disablePrecisionTrimming()

```php
public function disablePrecisionTrimming()
```

**disablePrecisionTrimming** to keep trailing zeroes.


returns `$this`


#### format()

```php
public function format($bytes)
```

**format** a quantity of bytes for human consumption.

1. `$bytes` &mdash; `mixed` 

returns `string`



* * * * *
### Class Duration
```php
class Duration
```

Duration formats seconds or DateInterval objects as human readable strings.

e.g.

```php
$df = Duration::create();
$df->format(119);                        // '1m 59s'
$df->format(new \DateInterval('P1Y5D')); // '1y 5d'
```

- [__construct()](#__construct-5)
- [create()](#create-3)
- [addFormat()](#addformat)
- [setFormat()](#setformat)
- [limitPeriods()](#limitperiods)
- [format()](#format-1)

#### __construct()

```php
public function __construct($format = self::FORMAT_SHORT)
```



1. `$format` &mdash; `mixed` 

returns *nothing*


#### create()

```php
public static function create($format = self::FORMAT_SHORT)
```

**create** a new Duration.

1. `$format` &mdash; `mixed` 

returns `\nochso\Omni\Format\Duration`


#### addFormat()

```php
public function addFormat($name, array $periodFormats)
```

**addFormat** to the existing defaults and set it as the current format.

1. `$name` &mdash; `mixed` 
2. `$periodFormats` &mdash; `mixed` 

returns `$this`

e.g.

```php
$format = Duration::FORMAT_LONG => [
    Duration::YEAR => ' year(s)',
    Duration::MONTH => ' month(s)',
    Duration::WEEK => ' week(s)',
    Duration::DAY => ' day(s)',
    Duration::HOUR => ' hour(s)',
    Duration::MINUTE => ' minute(s)',
    Duration::SECOND => ' second(s)',
];
$df->addFormat('my custom period format', $format);
```
#### setFormat()

```php
public function setFormat($name)
```

**setFormat** to use by its custom name or one of the default Duration constants.

1. `$name` &mdash; `string`  &mdash; One of the `Duration::FORMAT_*` constants or a name of a format added via `addFormat()`

returns `$this`


#### limitPeriods()

```php
public function limitPeriods($limit)
```

**limitPeriods** limits the amount of significant periods (years, months, etc.) to keep.

1. `$limit` &mdash; `int`  &mdash; 0 for keeping all significant periods or any positive integer.

returns `$this`

Significant periods are periods with non-zero values.
#### format()

```php
public function format($duration)
```

**format** an amount of seconds or a `DateInterval` object.

1. `$duration` &mdash; `mixed` 

returns `string` &mdash; A formatted duration for human consumption.



* * * * *
### Class Quantity
```php
class Quantity
```

Quantity formats a string depending on quantity (many, one or zero).

The plural, singular and empty formats of the string can be defined like this:

`(plural|singular|zero)`

The singular and zero formats are optional:

```php
Quantity::format('day(s)', 1); // day
Quantity::format('day(s)', 0); // days
```

You can also use `%s` as a placeholder for the quantity:

```php
Quantity::format('%s day(s)', 2); // 2 days
```

If the `zero` format is not defined, the plural form will be used instead.
Alternatively you can use an empty string:

```php
Quantity::format('(many|one|)', 0); // empty string
```

Example with all three formats:

```php
Quantity::format('(bugs|bug|no bugs at all)', 5) // bugs
Quantity::format('(bugs|bug|no bugs at all)', 1) // bug
Quantity::format('(bugs|bug|no bugs at all)', 0) // no bugs at all
```

- [format()](#format-2)

#### format()

```php
public static function format($format, $quantity)
```

**format** a string depending on a quantity.

1. `$format` &mdash; `mixed` 
2. `$quantity` &mdash; `mixed` 

returns `mixed`

See the class documentation for defining `$format`.

## Namespace nochso\Omni\PhpCsFixer
- [Class DefaultFinder](#class-defaultfinder)
- [Class PrettyPSR](#class-prettypsr)

* * * * *
### Class DefaultFinder
```php
class DefaultFinder extends Finder
```

DefaultFinder respects ignore files for Git, Mercurial and Darcs.

- [createIn()](#createin)
- [__construct()](#__construct-6)
- [getNames()](#getnames)
- [getVcsIgnoreFiles()](#getvcsignorefiles)

#### createIn()

```php
public static function createIn($dirs)
```



1. `$dirs` &mdash; `mixed` 

returns `\nochso\Omni\PhpCsFixer\DefaultFinder`


#### __construct()

```php
public function __construct()
```




returns *nothing*


#### getNames()

```php
protected function getNames()
```




returns `array`


#### getVcsIgnoreFiles()

```php
protected function getVcsIgnoreFiles(array $dirs)
```



1. `$dirs` &mdash; `mixed` 

returns `array`



* * * * *
### Class PrettyPSR
```php
class PrettyPSR extends Config
```

PrettyPSR lies inbetween FixerInterface's PSR2 and Symfony level.

Create a new file `.php_cs` in your project root:

```
<?php
require_once 'vendor/autoload.php';
use nochso\Omni\PhpCsFixer\PrettyPSR;
return PrettyPSR::createIn(['src', 'test']);
```

Running `php-cs-fixer` will fix all files in folders `src` and `test`.

Note that `php-cs-fixer` is not included by default. You have to require it first:
```
composer require --dev fabpot/php-cs-fixer
```

- [createIn()](#createin-1)
- [__construct()](#__construct-7)
- [getDefaultFixers()](#getdefaultfixers)

#### createIn()

```php
public static function createIn($dirs)
```



1. `$dirs` &mdash; `mixed` 

returns `static`


#### __construct()

```php
public function __construct($name = self::class, $description = '')
```



1. `$name` &mdash; `mixed` 
2. `$description` &mdash; `mixed` 

returns *nothing*


#### getDefaultFixers()

```php
public function getDefaultFixers()
```




returns `string[]`




