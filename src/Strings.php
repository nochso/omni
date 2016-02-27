<?php
namespace nochso\Omni;

/**
 * Strings class provides simple string functions missing from default PHP.
 */
final class Strings
{
    /**
     * startsWith tests if the input begins with a prefix.
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
     * endsWith tests if the input ends with a suffix.
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
            $newCount = substr_count($haystack, $needle);
            if ($newCount > $maxCount) {
                $maxCount = $newCount;
                $maxNeedle = $needle;
            }
        }
        return $maxNeedle;
    }
}
