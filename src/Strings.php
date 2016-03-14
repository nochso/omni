<?php
namespace nochso\Omni;

/**
 * Strings class provides methods for string handling missing from default PHP.
 */
final class Strings
{
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
    public static function startsWith($input, $prefix)
    {
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
    public static function endsWith($input, $suffix)
    {
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
    public static function getMostFrequentNeedle($haystack, array $needles)
    {
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
    public static function escapeControlChars($input)
    {
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
     * @param string $input
     * @param int    $padLength   If the pad length smaller than the input length, no padding takes place.
     * @param string $padding     Optional, defaults to a space character. The padding may be truncated if the required
     *                            number of padding characters can't be evenly divided.
     * @param int    $paddingType Optional, defaults to STR_PAD_RIGHT. Must be one of STR_PAD_LEFT, STR_PAD_RIGHT or
     *                            STR_PAD_BOTH.
     *
     * @return string The padded string.
     */
    public static function padMultibyte($input, $padLength, $padding = ' ', $paddingType = STR_PAD_RIGHT)
    {
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
            return self::padMultibyte(self::padMultibyte($input, $leftPadLength, $padding, STR_PAD_LEFT), $padLength, $padding, STR_PAD_RIGHT);
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
}
