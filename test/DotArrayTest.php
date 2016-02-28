<?php
namespace nochso\Omni\Test;

use nochso\Omni\DotArray;

class DotArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $arr = [
            'ak' => 'av',
            'bk' => [
                'ck' => [
                    'c1',
                    'c2',
                ],
            ],
        ];

        $this->assertSame('av', DotArray::get($arr, 'ak'));
        $this->assertSame(['c1', 'c2'], DotArray::get($arr, 'bk.ck'));
        $this->assertSame('c1', DotArray::get($arr, 'bk.ck.0'));
        $this->assertSame('c2', DotArray::get($arr, 'bk.ck.1'));
    }

    public function testGetEscapedKey()
    {
        $arr = [
            'key.complex' => [
                'inner " key' => 'value',
            ],
        ];
        $this->assertSame('value', DotArray::get($arr, 'key\.complex.inner " key'));
    }

    public function testGet_WhenMissing_UseDefault()
    {
        $arr = [];
        $this->assertSame('default', DotArray::get($arr, 'missing.key', 'default'));
    }

    public function testHas()
    {
        $arr = [
            'ak' => 'av',
            'bk' => [
                'ck' => [
                    'c1',
                    'c2',
                ],
            ],
        ];
        $this->assertTrue(DotArray::has($arr, 'ak'));
        $this->assertTrue(DotArray::has($arr, 'bk'));
        $this->assertTrue(DotArray::has($arr, 'bk.ck'));
        $this->assertTrue(DotArray::has($arr, 'bk.ck.0'));
        $this->assertTrue(DotArray::has($arr, 'bk.ck.1'));
        $this->assertFalse(DotArray::has($arr, 'bk.ck.2'));
        $this->assertFalse(DotArray::has($arr, ''));
    }
}
