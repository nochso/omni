<?php
namespace nochso\Omni;

/**
 * Strings class provides methods for string handling missing from default PHP.
 *
 * `mb_*` methods are used where sensible, so make sure to pass UTF-8 strings.
 */
final class Strings {
	private static $controlCharMap = [
		"\n" => '\n',
		"\r" => '\r',
		"\t" => '\t',
		"\v" => '\v',
		"\e" => '\e',
		"\f" => '\f',
	];

	const CONTROL_CHAR_PATTERN = '/[\x00-\x1F\x7F]/';

	/**
	 * startsWith returns true if the input begins with a prefix.
	 *
	 * @param string $input
	 * @param string $prefix
	 *
	 * @return bool
	 */
	public static function startsWith($input, $prefix) {
		return substr($input, 0, strlen($prefix)) === $prefix;
	}

	/**
	 * endsWith returns true if the input ends with a suffix.
	 *
	 * @param string $input
	 * @param string $suffix
	 *
	 * @return bool
	 */
	public static function endsWith($input, $suffix) {
		return strlen($suffix) === 0 || substr($input, -strlen($suffix)) === $suffix;
	}

	/**
	 * getMostFrequentNeedle by counting occurences of each needle in haystack.
	 *
	 * @param string $haystack Haystack to be searched in.
	 * @param array  $needles  Needles to be counted.
	 *
	 * @return string|null The most occuring needle. If counts are tied, the first tied needle is returned. If no
	 *                     needles were found, `null` is returned.
	 */
	public static function getMostFrequentNeedle($haystack, array $needles) {
		$maxCount = 0;
		$maxNeedle = null;
		foreach ($needles as $needle) {
			$newCount = mb_substr_count($haystack, $needle);
			if ($newCount > $maxCount) {
				$maxCount = $newCount;
				$maxNeedle = $needle;
			}
		}
		return $maxNeedle;
	}

	/**
	 * escapeControlChars by replacing line feeds, tabs, etc. to their escaped representation.
	 *
	 * e.g. an actual line feed will return '\n'
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	public static function escapeControlChars($input) {
		$escaper = function ($chars) {
			$char = $chars[0];
			if (isset(self::$controlCharMap[$char])) {
				return self::$controlCharMap[$char];
			}
			return sprintf('\x%02X', ord($char));
		};
		$output = str_replace('\\', '\\\\', $input);
		return preg_replace_callback(self::CONTROL_CHAR_PATTERN, $escaper, $output);
	}

	/**
	 * padMultibyte strings to a certain length with another string.
	 *
	 * @param string $input       The input string to be padded.
	 * @param int    $padLength   If the pad is length smaller than the input length, no padding takes place.
	 * @param string $padding     Optional, defaults to a space character. Can be more than one character. The padding
	 *                            may be truncated if the required number of padding characters can't be evenly
	 *                            divided.
	 * @param int    $paddingType Optional, defaults to STR_PAD_RIGHT. Must be one of STR_PAD_LEFT, STR_PAD_RIGHT or
	 *                            STR_PAD_BOTH.
	 *
	 * @return string The padded string.
	 */
	public static function padMultibyte($input, $padLength, $padding = ' ', $paddingType = STR_PAD_RIGHT) {
		if ($paddingType !== STR_PAD_LEFT && $paddingType !== STR_PAD_RIGHT && $paddingType !== STR_PAD_BOTH) {
			throw new \InvalidArgumentException('Padding type must be one of STR_PAD_LEFT, STR_PAD_RIGHT or STR_PAD_BOTH.');
		}
		$paddingLength = mb_strlen($padding);
		if ($paddingLength === 0) {
			throw new \InvalidArgumentException('Padding string must not be empty.');
		}
		$inputLength = mb_strlen($input);
		if ($inputLength > $padLength) {
			return $input;
		}
		$freeLength = $padLength - $inputLength;
		if ($paddingType === STR_PAD_BOTH) {
			// Original str_pad prefers trailing padding
			$leftPadLength = $padLength - ceil($freeLength / 2);
			// Reuse the below left/right implementation
			return self::padMultibyte(
				self::padMultibyte($input, $leftPadLength, $padding, STR_PAD_LEFT),
				$padLength,
				$padding,
				STR_PAD_RIGHT
			);
		}
		$foo = str_repeat($padding, $freeLength / $paddingLength);
		$partialPadLength = $freeLength % $paddingLength;
		if ($partialPadLength > 0) {
			$foo .= mb_substr($padding, 0, $partialPadLength);
		}
		if ($paddingType === STR_PAD_LEFT) {
			return $foo . $input;
		}
		return $input . $foo;
	}

	/**
	 * getCommonPrefix of two strings.
	 *
	 * @param string $first
	 * @param string $second
	 *
	 * @return string All common characters from the beginning of both strings.
	 */
	public static function getCommonPrefix($first, $second) {
		if ($first === $second) {
			return $first;
		}
		$length = min(mb_strlen($first), mb_strlen($second));
		for ($i = 0; $i < $length; $i++) {
			if ($first[$i] !== $second[$i]) {
				return mb_substr($first, 0, $i);
			}
		}
		return mb_substr($first, 0, $length);
	}

	/**
	 * getCommonSuffix of two strings.
	 *
	 * @param string $first
	 * @param string $second
	 *
	 * @return string All common characters from the end of both strings.
	 */
	public static function getCommonSuffix($first, $second) {
		$reversedCommon = self::getCommonPrefix(self::reverse($first), self::reverse($second));
		return self::reverse($reversedCommon);
	}

	/**
	 * Reverse a string.
	 *
	 * @param string $input
	 *
	 * @return string The reversed string.
	 */
	public static function reverse($input) {
		$length = mb_strlen($input);
		$reversed = '';
		for ($i = $length - 1; $i !== -1; --$i) {
			$reversed .= mb_substr($input, $i, 1);
		}
		return $reversed;
	}

	/**
	 * groupByCommonPrefix returns an array with a common key and a list of differing suffixes.
	 *
	 * e.g. passing an array `['sameHERE', 'sameTHERE']` would return
	 * ```
	 * 'same' => [
	 *    'HERE',
	 *    'THERE',
	 * ]
	 * ```
	 *
	 * This can be used to group several file paths by a common base.
	 *
	 * @param string[] $strings
	 *
	 * @return string[][]
	 */
	public static function groupByCommonPrefix($strings) {
		sort($strings);
		$common = null;
		foreach ($strings as $folder) {
			if ($common === null) {
				$common = $folder;
			} else {
				$common = self::getCommonPrefix($common, $folder);
			}
		}
		$trimmedFolders = [];
		$commonLength = mb_strlen($common);
		foreach ($strings as $folder) {
			$trimmedFolders[$common][] = mb_substr($folder, $commonLength);
		}
		return $trimmedFolders;
	}

	/**
	 * groupByCommonSuffix returns an array with a common key and a list of differing suffixes.
	 *
	 * e.g. passing an array `['sameHERE', 'sameTHERE']` would return
	 * ```
	 * 'HERE' => [
	 *    'same',
	 *    'sameT',
	 * ]
	 * ```
	 *
	 * @param string[] $strings
	 *
	 * @return string[][]
	 */
	public static function groupByCommonSuffix($strings) {
		foreach ($strings as $key => $string) {
			$strings[$key] = self::reverse($string);
		}
		$reversedGroups = self::groupByCommonPrefix($strings);
		$groups = [];
		foreach ($reversedGroups as $revKey => $revStrings) {
			$groups[self::reverse($revKey)] = array_map([self::class, 'reverse'], $revStrings);
		}
		return $groups;
	}
}
