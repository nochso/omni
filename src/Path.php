<?php
namespace nochso\Omni;

/**
 * Path helps keep the directory separator/implode/trim/replace madness away.
 */
final class Path
{
    private $scheme = '';
    private $path = '';

    /**
     * Combine any amount of strings into a path.
     *
     * @param string|array ...$paths One or as many parameters as you need. Both strings and arrays of strings can be mixed.
     *
     * @return string The combined paths. Note that the directory separators will be changed to reflect the local system.
     */
    public static function combine(...$paths)
    {
        // Flatten into simple array
        $paths = Arrays::flatten(...$paths);
        // Keep non-empty elements
        $paths = array_filter($paths, 'strlen');

        // Join parts
        $path = implode(DIRECTORY_SEPARATOR, $paths);

        // Localize everything after an optional scheme://
        $path = self::create($path);
        $path->path = self::localize($path->path);
        // Replace multiple with single separator
        $quotedSeparator = preg_quote(DIRECTORY_SEPARATOR);
        $pattern = '#' . $quotedSeparator . '+#';
        $path->path = preg_replace($pattern, $quotedSeparator, $path->path);
        return (string) $path;
    }

    /**
     * Localize directory separators for any file path according to current environment.
     *
     * @param string $path
     * @param string $directorySeparator
     *
     * @return string
     */
    public static function localize($path, $directorySeparator = DIRECTORY_SEPARATOR)
    {
        if (!$path instanceof self) {
            $path = self::create($path);
        }
        $path->path = str_replace(['\\', '/'], $directorySeparator, $path->path);
        return (string) $path;
    }

    /**
     * Contains returns true if a base path contains a needle.
     *
     * Note that `realpath` is used on both base and needle: they need to exist or false is returned.
     *
     * Use this for avoiding directory traversal outside of a base path.
     *
     * @param string $base   Path to base directory.
     * @param string $needle Needle that must exist within the base directory.
     *
     * @return bool True if both exist and needle does not escape the base folder.
     */
    public static function contains($base, $needle)
    {
        $realBase = realpath($base);
        $needle = realpath($needle);
        if ($realBase === false || $needle === false) {
            return false;
        }
        return Strings::startsWith($needle, $base);
    }

    /**
     * isAbsolute checks for an absolute UNIX or Windows path.
     *
     * @param string $path
     *
     * @return bool True if the path is absolute.
     */
    public static function isAbsolute($path)
    {
        return preg_match('/^([\\/\\\\]|[a-z]:[\\/\\\\])/', $path) === 1;
    }

    public function __toString()
    {
        return $this->scheme . $this->path;
    }

    /**
     * @param string $fullPath
     *
     * @return \nochso\Omni\Path
     */
    private static function create($fullPath)
    {
        $path = new self();
        $path->path = $fullPath;
        if (preg_match('/^([a-z]+:\\/\\/)?(.*)$/', $fullPath, $matches)) {
            $path->scheme = $matches[1];
            $path->path = $matches[2];
        }
        if ($path->scheme !== '') {
            $path->path = ltrim($path->path, '\\/');
        }
        return $path;
    }
}
