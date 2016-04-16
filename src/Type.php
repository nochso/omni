<?php
namespace nochso\Omni;

/**
 * Type returns PHP type information.
 *
 * Uses:
 *
 * - writing nicer exception messages
 * - easier debugging
 */
final class Type {
	/**
	 * Summarize the type of any variable.
	 *
	 * @param mixed $value
	 *
	 * @return string Result of `get_class` for objects or `gettype` for anything else.
	 *
	 * @see gettype
	 * @see get_class
	 */
	public static function summarize($value) {
		if (!is_object($value)) {
			return gettype($value);
		}
		return get_class($value);
	}

	/**
	 * getClassName returns the class name without namespaces.
	 *
	 * @param object|string $object Object instance of a fully qualified name.
	 *
	 * @return string
	 */
	public static function getClassName($object) {
		if (is_object($object)) {
			$object = get_class($object);
		}
		return ltrim(substr($object, strrpos($object, '\\')), '\\');
	}
}
