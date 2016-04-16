<?php
namespace nochso\Omni;

/**
 * Path helps keep the directory separator/implode/trim/replace madness away.
 */
final class Path
{
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

        // Split into scheme:// and rest
        $scheme = self::extractScheme($paths);
        // Implode, localize and simplify everything after scheme://
        $path = implode(DIRECTORY_SEPARATOR, $paths);
        $path = self::localize($path);
        $quotedSeparator = preg_quote(DIRECTORY_SEPARATOR);
        $pattern = '#' . $quotedSeparator . '+#';
        $path = preg_replace($pattern, $quotedSeparator, $path);
        return $scheme . $path;
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
        $paths = [$path];
        // Do not localize scheme:// paths
        $scheme = self::extractScheme($paths);
        if ($scheme !== '') {
            return $path;
        }
        $path = str_replace(['\\', '/'], $directorySeparator, $path);
        return $path;
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
     * isAbsolute checks for an absolute UNIX, Windows or scheme:// path.
     *
     * Note that paths containing parent dots (`..`) can still be considered absolute.
     *
     * @param string $path
     *
     * @return bool True if the path is absolute i.e. it should be safe to append a relative path to it.
     */
    public static function isAbsolute($path)
    {
        $pattern = '@^
        (                         # Either..
            [/\\\\]               # absolute start
        |   [a-z]:[/\\\\]         # or Windows drive path
        |   [a-z][a-z0-9\.+-]+:// # or URI scheme:// - see http://tools.ietf.org/html/rfc3986#section-3.1
        )@ix';
        return preg_match($pattern, $path) === 1;
    }

    private static function extractScheme(&$parts) {
        if (!count($parts)) {
            return '';
        }
        $first = reset($parts);
        if (preg_match('/^([a-z]+:\\/\\/)(.*)$/', $first, $matches)) {
            array_shift($parts);
            if ($matches[2] !== '') {
                array_unshift($parts, $matches[2]);
            }
            return $matches[1];
        }
        return '';
    }
}
