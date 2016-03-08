<?php
namespace nochso\Omni;

/**
 * Dot allows easy access to multi-dimensional arrays using dot notation.
 */
final class Dot
{
    /**
     * Get the value of the element at the given dot key path.
     *
     * @param array      $array   Multi-dimensional array to access.
     * @param string     $path    Dot-notation key path. e.g. `parent.child`
     * @param null|mixed $default Default value to return
     *
     * @return mixed
     */
    public static function get(array &$array, $path, $default = null)
    {
        $getter = function ($arrayCarry, $key) use ($default) {
            if (!is_array($arrayCarry) || !isset($arrayCarry[$key])) {
                return $default;
            }
            return $arrayCarry[$key];
        };
        $keys = self::extractKeys($path);
        return array_reduce($keys, $getter, $array);
    }

    /**
     * Has returns true if an element exists at the given dot key path.
     *
     * @param array  $array Multi-dimensionsal array to search.
     * @param string $path  Dot-notation key path to search.
     *
     * @return bool
     */
    public static function has(array &$array, $path)
    {
        $unique = new \stdClass();
        $value = self::get($array, $path, $unique);
        return $value !== $unique;
    }

    /**
     * Set a value at a certain path by creating missing elements and overwriting non-array values.
     *
     * If any of the visited elements is not an array, it will be replaced with an array.
     *
     * This will overwrite existing non-array values.
     *
     * @param array  $array
     * @param string $path
     * @param mixed  $value
     */
    public static function set(array &$array, $path, $value)
    {
        self::setHelper($array, $path, $value, false);
    }

    /**
     * trySet sets a value at a certain path, expecting arrays or missing elements along the way.
     *
     * If any of the visited elements is not an array, a \RuntimeException is thrown.
     *
     * Use this if you want to avoid overwriting existing non-array values.
     *
     * @param array  $array
     * @param string $path
     * @param mixed  $value
     *
     * @throws \RuntimeException
     */
    public static function trySet(array &$array, $path, $value)
    {
        self::setHelper($array, $path, $value, true);
    }

    /**
     * Remove an element if it exists.
     *
     * @param array  $array
     * @param string $path
     */
    public static function remove(array &$array, $path)
    {
        $keys = self::extractKeys($path);
        $keysWithoutLast = array_slice($keys, 0, -1);
        $keyCount = count($keysWithoutLast);
        $lastKey = $keys[$keyCount];
        $node = &$array;

        // Abort when key is missing earlier than expected
        for ($i = 0; $i < $keyCount && is_array($node) && isset($node[$keys[$i]]); ++$i) {
            $node = &$node[$keys[$i]];
        }
        if ($i < $keyCount) {
            return;
        }
        if (is_array($node) && isset($node[$lastKey])) {
            unset($node[$lastKey]);
        }
    }

    /**
     * Flatten the array into a single dimension array with dot paths as keys.
     *
     * @param array       $array
     * @param null|string $parent
     *
     * @return array
     */
    public static function flatten(array $array, $parent = null)
    {
        $flat = [];
        foreach ($array as $key => $value) {
            $keypath = self::escapeKey($key);
            if ($parent !== null) {
                $keypath = $parent . '.' . $keypath;
            }
            if (is_array($value)) {
                $flat = array_merge(self::flatten($value, $keypath), $flat);
            } else {
                $flat[$keypath] = $value;
            }
        }
        return $flat;
    }

    /**
     * escapeKey escapes an individual part of a key.
     *
     * @param string $key
     *
     * @return string
     */
    private static function escapeKey($key)
    {
        $re = '/([\\.\\\\])/';
        $subst = '\\\$1';
        return preg_replace($re, $subst, $key);
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private static function extractKeys($path)
    {
        $keys = [];
        if (!preg_match_all('/(?:\\\\.|[^\\.\\\\]++)+/', $path, $matches)) {
            return [$path];
        }
        foreach ($matches[0] as $match) {
            $keys[] = str_replace(['\.', '\\\\'], ['.', '\\'], $match);
        }
        return $keys;
    }

    private static function setHelper(array &$array, $path, $value, $strict = true)
    {
        $keys = self::extractKeys($path);
        $lastKey = $keys[count($keys) - 1];
        $keysWithoutLast = array_slice($keys, 0, -1);
        $node = &$array;
        foreach ($keysWithoutLast as $key) {
            self::prepareNode($node, $key, $path, $strict);
            $node = &$node[$key];
        }
        $node[$lastKey] = $value;
    }

    private static function prepareNode(array &$node, $key, $path, $strict)
    {
        if (!isset($node[$key]) || (!$strict && !is_array($node[$key]))) {
            $node[$key] = [];
        }
        if ($strict && !is_array($node[$key])) {
            throw new \RuntimeException(sprintf(
                "Can not set value at path '%s' because the element at key '%s' is not an array. Found value of type '%s' instead.",
                $path,
                $key,
                gettype($node[$key])
            ));
        }
    }
}
