<?php
namespace nochso\Omni;

/**
 * DotArray holds a multi-dimensional array and wraps the static API of `\nochso\Omni\Dot`.
 *
 * e.g.
 * ```php
 * $array = [
 *     'a' => [
 *          'b' => 'c'
 *     ]
 * ];
 * $da = new DotArray();
 * echo $da->get('a.b'); // 'c'
 * ```
 *
 * @see \nochso\Omni\Dot
 */
final class DotArray
{
    private $data;

    public function __construct(array $array = [])
    {
        $this->data = $array;
    }

    /**
     * getArray returns the complete array.
     *
     * @return array
     */
    public function getArray()
    {
        return $this->data;
    }

    /**
     * Get the value of the element at the given dot key path.
     *
     * @param string     $path    Dot-notation key path. e.g. `parent.child`
     * @param null|mixed $default Default value to return
     *
     * @return mixed
     */
    public function get($path, $default = null)
    {
        return Dot::get($this->data, $path, $default);
    }

    /**
     * Has returns true if an element exists at the given dot key path.
     *
     * @param string $path Dot-notation key path to search.
     *
     * @return bool
     */
    public function has($path)
    {
        return Dot::has($this->data, $path);
    }

    /**
     * Set a value at a certain path by creating missing elements and overwriting non-array values.
     *
     * If any of the visited elements is not an array, it will be replaced with an array.
     *
     * This will overwrite existing non-array values.
     *
     * @param string $path
     * @param mixed  $value
     */
    public function set($path, $value)
    {
        Dot::set($this->data, $path, $value);
    }

    /**
     * trySet sets a value at a certain path, expecting arrays or missing elements along the way.
     *
     * If any of the visited elements is not an array, a \RuntimeException is thrown.
     *
     * Use this if you want to avoid overwriting existing non-array values.
     *
     * @param string $path
     * @param mixed  $value
     *
     * @throws \RuntimeException
     */
    public function trySet($path, $value)
    {
        Dot::trySet($this->data, $path, $value);
    }
}
