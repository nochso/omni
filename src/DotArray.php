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
 * ArrayAccess is also possible:
 * ```php
 * $da['a.b'] = 'c';
 * ```
 *
 * You can also escape parts of the path:
 * ```php
 * $da['a\.b'] === $da->getArray()['a.b'] // true
 * ```
 *
 * @see \nochso\Omni\Dot
 */
class DotArray implements \ArrayAccess, \IteratorAggregate{
	private $data;

	/**
	 * @param array $array Any (nested) array.
	 */
	public function __construct(array $array = []) {
		$this->data = $array;
	}

	/**
	 * getArray returns the complete array.
	 *
	 * @return array
	 */
	public function getArray() {
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
	public function get($path, $default = null) {
		return Dot::get($this->data, $path, $default);
	}

	/**
	 * Has returns true if an element exists at the given dot key path.
	 *
	 * @param string $path Dot-notation key path to search.
	 *
	 * @return bool
	 */
	public function has($path) {
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
	public function set($path, $value) {
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
	public function trySet($path, $value) {
		Dot::trySet($this->data, $path, $value);
	}

	/**
	 * Remove an element if it exists.
	 *
	 * @param string $path
	 */
	public function remove($path) {
		Dot::remove($this->data, $path);
	}

	/**
	 * Flatten the array into a single dimension array with escaped dot paths as keys.
	 *
	 * Dots within specific keys are escaped.
	 *
	 * @return array
	 */
	public function flatten() {
		return Dot::flatten($this->data);
	}

	/**
	 * getIterator allows you to iterate over a flattened array using `foreach`.
	 *
	 * Keys are escaped and thus safe to use.
	 *
	 * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
	 *
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>
	 */
	public function getIterator() {
		return new \ArrayIterator($this->flatten());
	}

	/**
	 * offsetExists allows using `isset($da['a.b'])`.
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return bool true on success or false on failure.
	 */
	public function offsetExists($offset) {
		return $this->has($offset);
	}

	/**
	 * offsetGet allows array access, e.g. `$da['a.b']`.
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * offsetSet allows writing to arrays `$da['a.b'] = 'foo'`.
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		if ($offset === null) {
			throw new \InvalidArgumentException(sprintf('Appending to %s is not supported.', self::class));
		}
		$this->set($offset, $value);
	}

	/**
	 * offsetUnset allows using `unset($da['a.b'])`.
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset($offset) {
		$this->remove($offset);
	}
}
