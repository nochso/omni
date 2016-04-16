<?php
namespace nochso\Omni\Test;

use nochso\Omni\Numeric;

class NumericTest extends \PHPUnit_Framework_TestCase {
	public function ensureProvider() {
		return [
			[0, 0],
			[-1, -1],
			[-1, '-1'],
			[0, '-0'],
			[0.0, '+00.0'],
			[1, '1'],
			[1, ' 1'],
			[1, '1 '],
			[1, '+1'],
			[1, '01'],
			[0.0, 0.0],
			[0.1, 0.1],
			[0.1, '0.1'],
			[0.1, '.1'],
		];
	}

	/**
	 * @dataProvider ensureProvider
	 *
	 * @param int|float $expected
	 * @param mixed     $value
	 *
	 * @covers \nochso\Omni\Numeric::ensure
	 */
	public function testEnsure($expected, $value) {
		$actual = Numeric::ensure($value);
		$this->assertSame($expected, $actual);
	}

	public function ensureInvalidProvider() {
		return [['x'], ['0a'], [''], ['-'], [' '], ['0+1'], ['.'], [',']];
	}

	/**
	 * @dataProvider ensureInvalidProvider
	 *
	 * @param mixed $value
	 *
	 * @covers \nochso\Omni\Numeric::ensure
	 */
	public function testEnsure_WhenInvalid_MustThrow($value) {
		$this->expectException('InvalidArgumentException');
		Numeric::ensure($value);
	}

	public function ensureIntegerProvider() {
		return [[1, 1], [1, 1.0], [1, '1.000000000000'], [0, '0'], [1, '1'], [1, '+1']];
	}

	/**
	 * @dataProvider ensureIntegerProvider
	 *
	 * @param int   $expected
	 * @param mixed $value
	 *
	 * @covers \nochso\Omni\Numeric::ensureInteger
	 */
	public function testEnsureInteger($expected, $value) {
		$actual = Numeric::ensureInteger($value);
		$this->assertInternalType('int', $actual);
		$this->assertSame($expected, $actual);
	}

	public function ensureIntegerTrailingDecimalProvider() {
		return [[0.1], ['0.1'], ['.1']];
	}

	/**
	 * @dataProvider ensureIntegerTrailingDecimalProvider
	 *
	 * @param mixed $value
	 *
	 * @covers \nochso\Omni\Numeric::ensureInteger
	 */
	public function testEnsureInteger_WithTrailingDecimals_MustThrow($value) {
		$this->expectException('InvalidArgumentException');
		Numeric::ensureInteger($value);
	}

	public function ensureFloatProvider() {
		return [[0.0, '0'], [0.0, '0.0'], [0.1, '0.1'], [-5.1, '-5.1']];
	}

	/**
	 * @dataProvider ensureFloatProvider
	 *
	 * @param float $expected
	 * @param mixed $value
	 *
	 * @covers \nochso\Omni\Numeric::ensureFloat
	 */
	public function testEnsureFloat($expected, $value) {
		$actual = Numeric::ensureFloat($value);
		$this->assertInternalType('float', $actual);
		$this->assertSame($expected, $actual);
	}
}
