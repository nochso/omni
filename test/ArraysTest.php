<?php
namespace nochso\Omni\Test;

use nochso\Omni\Arrays;

class ArraysTest extends \PHPUnit_Framework_TestCase
{
    public function combineProvider()
    {
        return [
            [1, '', ''],
            [2, ['', ''], ''],
            [2, ['a', 'b'], ''],
            [4, ['a', 'b', ['c', ['d']]], ''],
        ];
    }

    /**
     * @dataProvider combineProvider
     */
    public function testFlatten($count, $params, $message = '')
    {
        $flat = Arrays::flatten($params);
        $this->assertCount($count, $flat);
        foreach ($flat as $param) {
            $this->assertFalse(is_array($param), $message);
        }
    }
}
