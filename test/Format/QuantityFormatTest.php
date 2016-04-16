<?php
namespace nochso\Omni\Test\Format;

use nochso\Omni\Format\Quantity;

class QuantityFormatTest extends \PHPUnit_Framework_TestCase {
	public function formatProvider() {
		return [
			['days', 'day(s)', 0],
			['day', 'day(s)', 1],
			['days', 'day(s)', 2],
			['0 days', '%s day(s)', 0],
			['1 day', '%s day(s)', 1],
			['2 days', '%s day(s)', 2],
			['Kakteen', 'Kakt(een|us)', 0],
			['Kaktus', 'Kakt(een|us)', 1],
			['Kakteen', 'Kakt(een|us)', 2],
			['one', '(multiple|one|none)', 1],
			['multiple', '(multiple|one|none)', 5],
			['none', '(multiple|one|none)', 0],
		];
	}

	/**
	 * @dataProvider formatProvider
	 *
	 * @param string    $expected
	 * @param string    $format
	 * @param int|float $quantity
	 */
	public function testFormat($expected, $format, $quantity) {
		$this->assertSame($expected, Quantity::format($format, $quantity));
	}
}
