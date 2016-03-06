<?php
namespace nochso\Omni;

use Traversable;

/**
 * ArrayCollection wraps an array, providing common collection methods.
 */
class ArrayCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var array
     */
    protected $list;

    /**
     * @param array $list
     */
    public function __construct(array $list = [])
    {
        $this->list = $list;
    }

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function add($element)
    {
        $this->list[] = $element;
        return $this;
    }

    /**
     * Set or replace the element at a specific index.
     *
     * @param mixed $index
     * @param mixed $element
     *
     * @return $this
     */
    public function set($index, $element)
    {
        $this[$index] = $element;
        return $this;
    }

    /**
     * Remove and return the element at the zero based index.
     *
     * @param int $index
     *
     * @return null|mixed Null if the element didn't exist.
     */
    public function remove($index)
    {
        if (!array_key_exists($index, $this->list)) {
            return null;
        }
        $removed = $this->list[$index];
        unset($this->list[$index]);
        return $removed;
    }

    /**
     * First sets the internal pointer to the first element and returns it.
     *
     * @link http://php.net/manual/en/function.reset.php
     *
     * @return mixed the value of the first array element, or false if the array is empty.
     */
    public function first()
    {
        return reset($this->list);
    }

    /**
     * Last sets the internal pointer to the last element and returns it.
     *
     * @link http://php.net/manual/en/function.end.php
     *
     * @return mixed the value of the last element or false for empty array.
     */
    public function last()
    {
        return end($this->list);
    }

    /**
     * toArray returns a plain old array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->list;
    }

    /**
     * Apply a callable to every element.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function apply(callable $callable)
    {
        $this->list = array_map($callable, $this->list);
        return $this;
    }

    /**
     * Count elements of an object.
     *
     * @link  http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->list);
    }

    /**
     * getIterator returns an external iterator.
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    /**
     * offsetExists allows using `isset`.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset An offset to check for.
     *
     * @return bool true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->list);
    }

    /**
     * offsetGet allows array access, e.g. `$list[2]`.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return isset($this->list[$offset]) ? $this->list[$offset] : null;
    }

    /**
     * offsetSet allows writing to arrays `$list[2] = 'foo'`.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->add($value);
            return;
        }
        $this->list[$offset] = $value;
    }

    /**
     * offsetUnset allows using `unset`.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param  mixed $offset The offset to unset.
     *                        
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
