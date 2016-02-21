<?php

namespace nochso\Omni;

/**
 * Static helper to keep the directory separator/implode/trim madness away.
 */
final class Path
{
    /**
     * Combines any amount of strings into a path.
     *
     * @param string ...$paths One or as many parameters as you need. Both
     *                         strings and arrays of strings can be mixed.
     *
     * @return string The combined paths. Note that the directory separators
     *                will be changed to reflect the local system.
     */
    public static function combine($paths)
    {
        $path = implode(DIRECTORY_SEPARATOR, self::getPaths(func_get_args()));
        $path = self::localize($path);
        // Replace multiple with single separator
        $quotedSeparator = preg_quote(DIRECTORY_SEPARATOR);
        $pattern = '#' . $quotedSeparator . '+#';
        $combined = preg_replace($pattern, $quotedSeparator, $path);
        return $combined;
    }

    /**
     * Localizes directory separators for any file path according to current environment.
     *
     * @param string $path
     * @param string $directorySeparator
     *
     * @return string
     */
    public static function localize($path, $directorySeparator = DIRECTORY_SEPARATOR)
    {
        return str_replace(['\\', '/'], $directorySeparator, $path);
    }

    /**
     * @param array $args
     *
     * @return \string[]
     */
    private static function getPaths($args)
    {
        $paths = [];
        foreach ($args as $arg) {
            self::mergeArg($arg, $paths);
        }
        // Keep only non-empty strings
        return array_filter($paths, 'strlen');
    }

    /**
     * @param string[]|string $arg
     * @param string[]        $paths
     */
    private static function mergeArg($arg, &$paths)
    {
        if (is_array($arg)) {
            $paths = array_merge($paths, $arg);
            return;
        }
        $paths[] = $arg;
    }
}
