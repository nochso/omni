<?php
namespace nochso\Omni\Test;

use nochso\Omni\Dot;

class DotTest extends \PHPUnit_Framework_TestCase
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

        $this->assertSame('av', Dot::get($arr, 'ak'));
        $this->assertSame(['c1', 'c2'], Dot::get($arr, 'bk.ck'));
        $this->assertSame('c1', Dot::get($arr, 'bk.ck.0'));
        $this->assertSame('c2', Dot::get($arr, 'bk.ck.1'));
    }

    public function testGetEscapedKey()
    {
        $arr = [
            'key.complex' => [
                'inner " key' => 'value',
            ],
        ];
        $this->assertSame('value', Dot::get($arr, 'key\.complex.inner " key'));
    }

    public function testGet_WhenMissing_UseDefault()
    {
        $arr = [];
        $this->assertSame('default', Dot::get($arr, 'missing.key', 'default'));
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
        $this->assertTrue(Dot::has($arr, 'ak'));
        $this->assertTrue(Dot::has($arr, 'bk'));
        $this->assertTrue(Dot::has($arr, 'bk.ck'));
        $this->assertTrue(Dot::has($arr, 'bk.ck.0'));
        $this->assertTrue(Dot::has($arr, 'bk.ck.1'));
        $this->assertFalse(Dot::has($arr, 'bk.ck.2'));
        $this->assertFalse(Dot::has($arr, ''));
    }

    public function testSet()
    {
        $arr = [];
        Dot::set($arr, 'a.b', 'c');
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
        Dot::set($arr, 'a.b', 'X');
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
        Dot::set($arr, 'a.b.c', 'X');
        $this->assertSame(['a' => ['b' => ['c' => 'X']]], $arr);
    }

    public function testTrySet()
    {
        $arr = [];
        Dot::trySet($arr, 'a.b', 'c');
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
        Dot::trySet($arr, 'a.c', 'X');
    }
}
