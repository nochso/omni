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

    /**
     * Delete a directory and all of its contents recursively.
     *
     * @param string $path Path of folder to delete
     *
     * @throws \RuntimeException If the folder does not exist or something could not be deleted.
     */
    public static function delete($path)
    {
        self::deleteContents($path);
        if (@rmdir($path) === false) {
            throw new \RuntimeException(sprintf(
                    "Unable to delete folder '%s': %s",
                    $path,
                    error_get_last()['message']
            ));
        }
    }

    /**
     * deleteContents of a folder recursively, but not the folder itself.
     *
     * @param string $path Path of folder whose contents will be deleted
     *
     * @throws \RuntimeException If the folder does not exist or something could not be deleted.
     */
    public static function deleteContents($path)
    {
        if (!is_dir($path)) {
            throw new \RuntimeException(sprintf(
                "Unable to delete folder '%s': folder must exist first",
                $path
            ));
        }
        /** @var \SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileInfo) {
            if ($fileInfo->isDir()) {
                if (@rmdir($fileInfo->getRealPath()) === false) {
                    throw new \RuntimeException(sprintf(
                        "Unable to delete child folder '%s' in '%s': %s",
                        $fileInfo->getRealPath(),
                        $path,
                        error_get_last()['message']
                    ));
                }
            } elseif (@unlink($fileInfo->getRealPath()) === false) {
                throw new \RuntimeException(sprintf(
                    "Unable to delete file '%s' in folder '%s': %s",
                    $fileInfo->getRealPath(),
                    $path,
                    error_get_last()['message']
                ));
            }
        }
    }
}
