<?php
namespace nochso\Omni\Format;

use nochso\Omni\Numeric;

/**
 * Bytes formats a quantity of bytes using different suffixes and binary or decimal base.
 *
 * By default a binary base and IEC suffixes are used:
 *
 * ```php
 * Bytes::create()->format(1100); // '1.07 KiB'
 * ```
 *
 * You can pick a base and suffix with `create()` or use the specifc setter methods.
 */
class Bytes {
	/**
	 * 1024 binary base.
	 */
	const BASE_BINARY = 1024;
	/**
	 * 1000 decimal base.
	 */
	const BASE_DECIMAL = 1000;
	/**
	 * B, M, G, ...
	 */
	const SUFFIX_SIMPLE = 0;
	/**
	 * KiB, MiB, GiB, ... (SHOULD be used with BASE_BINARY).
	 */
	const SUFFIX_IEC = 1;
	/**
	 * kibibytes, mebibytes, gibibytes, ... (SHOULD be used with BASE_BINARY).
	 */
	const SUFFIX_IEC_LONG = 2;
	/**
	 * kB, MB, GB, ... (SHOULD be used with BASE_DECIMAL).
	 */
	const SUFFIX_SI = 3;
	/**
	 * kilobytes, megabytes, gigabytes, ... (SHOULD be used with BASE_DECIMAL).
	 */
	const SUFFIX_SI_LONG = 4;

	private static $suffixes = [
		self::SUFFIX_SIMPLE => ['B', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'],
		self::SUFFIX_IEC => ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'],
		self::SUFFIX_IEC_LONG => [
			'byte(s)',
			'kibibyte(s)',
			'mebibyte(s)',
			'gibibyte(s)',
			'tebibyte(s)',
			'pebibyte(s)',
			'exbibyte(s)',
			'zebibyte(s)',
			'yobibyte(s)',
		],
		self::SUFFIX_SI => ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
		self::SUFFIX_SI_LONG => [
			'byte(s)',
			'kilobyte(s)',
			'megabyte(s)',
			'gigabyte(s)',
			'terabyte(s)',
			'petabyte(s)',
			'exabyte(s)',
			'zettabyte(s)',
			'yottabyte(s)',
		],
	];
	/**
	 * @var int
	 */
	private $base = self::BASE_BINARY;
	/**
	 * @var int
	 */
	private $suffix = self::SUFFIX_IEC;
	/**
	 * @var int
	 */
	private $precision = 2;
	/**
	 * @var bool
	 */
	private $precisionTrimming = true;

	/**
	 * Create a new Bytes instance.
	 *
	 * @param int $base   The base to use when converting to different units. Must be one of the `Bytes::BASE_*`
	 *                    constants. Optional, defaults to `BASE_BINARY`.
	 * @param int $suffix The suffix style for units. Must be one of the `Bytes::SUFFIX_*` constants. Optional,
	 *                    defaults to SUFFIX_IEC (KiB, MiB, etc.)
	 *
	 * @return \nochso\Omni\Bytes
	 */
	public static function create($base = self::BASE_BINARY, $suffix = self::SUFFIX_IEC) {
		$bytes = new self();
		$bytes->setBase($base)->setSuffix($suffix);
		return $bytes;
	}

	/**
	 * setBase to use when converting to different units.
	 *
	 * @param int $base Must be one of the `Bytes::BASE_*` constants.
	 *
	 * @return $this
	 */
	public function setBase($base) {
		if ($base !== self::BASE_BINARY && $base !== self::BASE_DECIMAL) {
			throw new \InvalidArgumentException('Unknown base. Use either Bytes::BASE_BINARY or Bytes::BASE_DECIMAL');
		}
		$this->base = $base;
		return $this;
	}

	/**
	 * setSuffix style for units.
	 *
	 * @param int $suffix Must be one of the `Bytes::SUFFIX_*` constants.
	 *
	 * @return $this
	 */
	public function setSuffix($suffix) {
		if (!isset(self::$suffixes[$suffix])) {
			throw new \InvalidArgumentException('Unknown suffix. Use one of the Bytes::SUFFIX_* constants.');
		}
		$this->suffix = $suffix;
		return $this;
	}

	/**
	 * setPrecision of floating point values after the decimal point.
	 *
	 * @param int $precision Any non-negative integer.
	 *
	 * @return $this
	 */
	public function setPrecision($precision) {
		$this->precision = Numeric::ensureInteger($precision);
		return $this;
	}

	/**
	 * enablePrecisionTrimming to remove trailing zeroes and decimal points.
	 *
	 * @return $this
	 */
	public function enablePrecisionTrimming() {
		$this->precisionTrimming = true;
		return $this;
	}

	/**
	 * disablePrecisionTrimming to keep trailing zeroes.
	 *
	 * @return $this
	 */
	public function disablePrecisionTrimming() {
		$this->precisionTrimming = false;
		return $this;
	}

	/**
	 * Format a quantity of bytes for human consumption.
	 *
	 * @param int $bytes
	 *
	 * @return string
	 */
	public function format($bytes) {
		$bytes = Numeric::ensure($bytes);
		if (is_float($bytes) && $bytes > 0.0 && $bytes < 1.0) {
			throw new \InvalidArgumentException('Floats smaller than one can not be formatted.');
		}
		// 0 bytes won't work with log(), so set defaults for this case
		$exponent = 0;
		$normBytes = 0;
		if ($bytes !== 0) {
			$exponent = log(abs($bytes), $this->base);
			$normBytes = pow($this->base, $exponent - floor($exponent));
			// Make bytes negative again if needed
			$normBytes *= $bytes >= 0 ? 1 : -1;
		}
		$suffix = self::$suffixes[$this->suffix][$exponent];
		$number = number_format($normBytes, $this->precision, '.', '');
		$number = $this->trimPrecision($number);
		$suffix = Quantity::format($suffix, $number);
		return sprintf('%s %s', $number, $suffix);
	}

	/**
	 * @param string $number
	 *
	 * @return float
	 */
	private function trimPrecision($number) {
		if ($this->precisionTrimming && strpos((string) $number, '.') !== false) {
			$number = rtrim($number, '0');
			$number = rtrim($number, '.');
			$number = (double) $number;
		}
		return $number;
	}
}
