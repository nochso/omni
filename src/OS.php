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

    /**
     * hasBinary returns true if the binary is available in any of the PATHs.
     *
     * @param string $binaryName
     *
     * @return bool
     */
    public static function hasBinary($binaryName)
    {
        if (self::isWindows()) {
            // 'where.exe' uses 0 for success, 1 for failure to find
            exec('where.exe /q ' . escapeshellarg($binaryName), $out, $ret);
            return $ret === 0;
        }

        // 'which' uses 0 for success, 1 for failure to find
        exec('which ' . escapeshellarg($binaryName), $out, $ret);
        if ($ret === 0) {
            return true;
        }

        // 'whereis' does not use status codes. Check for a matching line instead.
        unset($out);
        exec('whereis -b ' . escapeshellarg($binaryName), $out, $ret);
        foreach ($out as $line) {
            if (preg_match('/^' . preg_quote($binaryName) . ': .*$/', $line) === 1) {
                return true;
            }
        }
        return false;
    }
}
