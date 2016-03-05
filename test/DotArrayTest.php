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

    public function testSet()
    {
        $arr = [];
        DotArray::set($arr, 'a.b', 'c');
        $expected = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $this->assertSame($expected, $arr);
    }

    public function testSet_WhenExists_MustReplace()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
        ];
        DotArray::set($arr, 'a.b', 'X');
        $expected = [
            'a' => [
                'b' => 'X',
            ],
        ];
        $this->assertSame($expected, $arr);
    }

    public function testSet_WhenNotArray_MustReplace()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
        ];
        DotArray::set($arr, 'a.b.c', 'X');
        $this->assertSame(['a' => ['b' => ['c' => 'X']]], $arr);
    }

    public function testTrySet()
    {
        $arr = [];
        DotArray::trySet($arr, 'a.b', 'c');
        $expected = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $this->assertSame($expected, $arr);
    }

    public function testTrySet_WhenExists_MustThrow()
    {
        $arr = [
            'a' => 'b',
        ];
        $this->expectException('RuntimeException');
        DotArray::trySet($arr, 'a.c', 'X');
    }
}
