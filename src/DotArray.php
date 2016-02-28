<?php
namespace nochso\Omni;

/**
 * DotArray for easy access to multi-dimensional arrays.
 */
final class DotArray
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
     * @param string $path
     *
     * @return array
     */
    private static function extractKeys($path)
    {
        $keys = [];
        $escapedKeys = preg_split('/(?<!\\\\)\./', $path);
        foreach ($escapedKeys as $key => $value) {
            $keys[] = str_replace('\.', '.', $value);
        }
        return $keys;
    }
}
