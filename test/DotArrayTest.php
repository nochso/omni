<?php
namespace nochso\Omni\Test;

use nochso\Omni\DotArray;

/**
 * Note that these tests should also cover all of `\nochso\Omni\Dot`.
 */
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
        $da = new DotArray($arr);
        $this->assertSame('av', $da->get('ak'));
        $this->assertSame(['c1', 'c2'], $da->get('bk.ck'));
        $this->assertSame('c1', $da->get('bk.ck.0'));
        $this->assertSame('c2', $da->get('bk.ck.1'));
    }

    public function testGetEscapedKey()
    {
        $arr = [
            'key.complex' => [
                'inner " key' => 'value',
            ],
        ];
        $da = new DotArray($arr);
        $this->assertSame('value', $da->get('key\.complex.inner " key'));
    }

    public function testGet_WhenMissing_UseDefault()
    {
        $da = new DotArray();
        $this->assertSame('default', $da->get('missing.key', 'default'));
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
        $da = new DotArray($arr);
        $this->assertTrue($da->has('ak'));
        $this->assertTrue($da->has('bk'));
        $this->assertTrue($da->has('bk.ck'));
        $this->assertTrue($da->has('bk.ck.0'));
        $this->assertTrue($da->has('bk.ck.1'));
        $this->assertFalse($da->has('bk.ck.2'));
        $this->assertFalse($da->has(''));
    }

    public function testSet()
    {
        $da = new DotArray();
        $da->set('a.b', 'c');
        $expected = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $this->assertSame($expected, $da->getArray());
    }

    public function testSet_WhenExists_MustReplace()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $da = new DotArray($arr);
        $da->set('a.b', 'X');
        $expected = [
            'a' => [
                'b' => 'X',
            ],
        ];
        $this->assertSame($expected, $da->getArray());
    }

    public function testSet_WhenNotArray_MustReplace()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $da = new DotArray($arr);
        $da->set('a.b.c', 'X');
        $this->assertSame(['a' => ['b' => ['c' => 'X']]], $da->getArray());
    }

    public function testTrySet()
    {
        $da = new DotArray();
        $da->trySet('a.b', 'c');
        $expected = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $this->assertSame($expected, $da->getArray());
    }

    public function testTrySet_WhenExists_MustThrow()
    {
        $arr = [
            'a' => 'b',
        ];
        $da = new DotArray($arr);
        $this->expectException('RuntimeException');
        $da->trySet('a.c', 'X');
    }
}
