<?php
namespace nochso\Omni\Test;

use nochso\Omni\QuantityFormat;

class QuantityFormatTest extends \PHPUnit_Framework_TestCase
{
    public function formatProvider()
    {
        return [
            ['days', 'day(s)', 0],
            ['day', 'day(s)', 1],
            ['days', 'day(s)', 2],
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
    public function testFormat($expected, $format, $quantity)
    {
        $this->assertSame($expected, QuantityFormat::format($format, $quantity));
    }
}
