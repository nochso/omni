<?php
namespace nochso\Omni;

/**
 * OS.
 */
class OS
{
    /**
     * isWindows returns true if the current OS is Windows.
     *
     * @param string $phpOs Optional, defaults to the PHP_OS constant.
     *
     * @return bool
     */
    public static function isWindows($phpOs = PHP_OS)
    {
        return strtolower(substr($phpOs, 0, 3)) === 'win';
    }
}
