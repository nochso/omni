<?php
namespace nochso\Omni;

/**
 * Arrays class provides methods for array manipulation missing from default PHP.
 */
final class Arrays {
	/**
	 * Flatten arrays and non-arrays recursively into a 2D array.
	 *
	 * @param array ...$elements Any amount of arrays and non-arrays.
	 *
	 * @return array
	 */
	public static function flatten(...$elements) {
		$return = [];
		$anvil = function ($element) use (&$return) {
			$return[] = $element;
		};
		array_walk_recursive($elements, $anvil);
		return $return;
	}
}
