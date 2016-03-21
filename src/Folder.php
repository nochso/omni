<?php
namespace nochso\Omni;

/**
 * Folder handles file system folders.
 */
final class Folder
{
    /**
     * Ensure a folder exists by creating it if missing and throw an exception on failure.
     *
     * @param string $path
     * @param int    $mode Optional, defaults to 0777.
     *
     * @throws \RuntimeException
     */
    public static function ensure($path, $mode = 0777)
    {
        if (is_dir($path)) {
            return;
        }
        if (@mkdir($path, $mode, true)) {
            return;
        }
        throw new \RuntimeException(sprintf(
            "Unable to create folder '%s': %s",
            $path,
            error_get_last()['message']
        ));
    }
}
