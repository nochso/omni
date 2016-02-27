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
}
