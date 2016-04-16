<?php
namespace nochso\Omni;

/**
 * Numeric validates and converts mixed types to numeric types.
 */
class Numeric {
	/**
	 * Ensure integer or or float value by safely converting.
	 *
	 * Safe conversions to int or float:
	 *
	 * ```
	 * '-1'    => -1
	 * '+00.0' => 0.0
	 * '1'     => 1
	 * ' 1 '    => 1
	 * '01'    => 1
	 * '0.1'   => 0.1
	 * '.1'    => 0.1
	 * ```
	 *
	 * Invalid conversions:
	 *
	 * ```
	 * 'x'
	 * '0a'
	 * ''
	 * '-'
	 * ' '
	 * '0+1'
	 * '.'
	 * ','
	 * ```
	 *
	 * @param mixed $value
	 *
	 * @throws \InvalidArgumentException If the value could not be safely converted to int or float.
	 *
	 * @return int|float
	 */
	public static function ensure($value) {
		if (is_int($value) || is_float($value)) {
			return $value;
		}
		$value = trim($value);
		if (is_numeric($value)) {
			// Results in either int or float
			return $value + 0;
		}
		throw new \InvalidArgumentException(
			sprintf(
				'Expecting value of type int, float or compatible, got variable of type %s instead.',
				Type::summarize($value)
			)
		);
	}

	/**
	 * ensureInteger values by safely converting.
	 *
	 * These are safe conversions because no information is lost:
	 *
	 * ```
	 * 1      => 1
	 * '1.00' => 1
	 * '1'    => 1
	 * '+1'   => 1
	 * ```
	 *
	 * These are invalid conversions because information would be lost:
	 *
	 * ```
	 * 0.1
	 * '0.1'
	 * '.1'
	 * ```
	 *
	 * If you don't care about this, you should cast to int instead: `(int)$value`
	 *
	 * @param mixed $value
	 *
	 * @throws \InvalidArgumentException If floating point information would be lost, i.e. it does not look like an integer.
	 *
	 * @return int
	 */
	public static function ensureInteger($value) {
		$numeric = self::ensure($value);
		if ((double) (int) $numeric !== (double) $numeric) {
			throw new \InvalidArgumentException(
				sprintf(
					"Could not safely convert value '%s' of type '%s' to integer because of trailing decimal places.",
					$value,
					Type::summarize($value)
				)
			);
		}
		return (int) $numeric;
	}

	/**
	 * ensureFloat values by safely converting.
	 *
	 * For example the following conversions are safe:
	 *
	 * ```
	 * '0'     => 0.0
	 * '0.0'   => 0.0
	 * '0.1'   => 0.1
	 *  '-5.1' => 5.1
	 * ```
	 *
	 * @param mixed $value
	 *
	 * @throws \InvalidArgumentException If value could not be safely converted to float.
	 *
	 * @return float
	 */
	public static function ensureFloat($value) {
		return (double) self::ensure($value);
	}
}
