<?php
namespace nochso\Omni;

use Patchwork\Utf8;

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
        return substr($input, -strlen($suffix)) === $suffix;
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
            $newCount = Utf8::substr_count($haystack, $needle);
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
}
