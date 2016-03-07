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

    public function testRemove()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $da = new DotArray($arr);
        $da->remove('a.b');
        $this->assertSame(['a' => []], $da->getArray());
    }

    public function testRemove_WhenMissing()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $da = new DotArray($arr);
        $da->remove('a.b.x.y');
        $this->assertSame($arr, $da->getArray());
    }

    public function testRemove_WhenEmpty()
    {
        $da = new DotArray();
        $da->remove('a');
        $this->assertSame([], $da->getArray());
    }

    public function testRemove_TopLevel()
    {
        $arr = [
            'a' => [
                'b' => 'c',
            ],
            'x' => 'X',
        ];
        $da = new DotArray($arr);
        $da->remove('a');
        $this->assertSame(['x' => 'X'], $da->getArray());
    }

    public function testOffsetExists()
    {
        $da = new DotArray([
            'a' => [
                'b' => 'c',
            ],
            'x' => 'X',
        ]);
        $this->assertTrue(isset($da['a']));
        $this->assertTrue(isset($da['a.b']));
        $this->assertTrue(isset($da['x']));
    }

    public function testOffsetGet()
    {
        $da = new DotArray([
            'a' => [
                'b' => 'c',
            ],
            'x' => 'X',
        ]);
        $this->assertSame('c', $da['a.b']);
        $this->assertNull($da['a.xx']);
    }

    public function testOffsetSet()
    {
        $da = new DotArray();
        $da['a.b'] = 'c';
        $expected = [
            'a' => [
                'b' => 'c',
            ],
        ];
        $this->assertSame($expected, $da->getArray());
    }

    public function testOffsetSet_EscapeDots()
    {
        $da = new DotArray([
            'a' => [
                'b' => 'c',
            ],
            'a.b' => 'x',
        ]);

        $da['a.b'] = 'C';
        $this->assertSame('C', $da->getArray()['a']['b']);
        $da['a\.b'] = 'X';
        $this->assertSame('X', $da->getArray()['a.b']);
    }

    public function testOffsetSet_WhenAppending_MustThrow()
    {
        $da = new DotArray();
        $this->expectException('\InvalidArgumentException');
        $da[] = 'x';
    }

    public function testOffsetUnset()
    {
        $da = new DotArray([
            'a' => [
                'b' => 'c',
            ],
        ]);
        unset($da['a.b']);
        $this->assertSame(['a' => []], $da->getArray());
    }

    public function testFlatten()
    {
        $arr = [
            'a' => [
                'b' => 'c',
                'd' => 'e',
            ],
        ];
        $da = new DotArray($arr);

        $expected = [
            'a.b' => 'c',
            'a.d' => 'e',
        ];
        $flat = $da->flatten();
        $this->assertSame($expected, $flat);
        $this->assertFlatArrayMatchesDeepArray($flat, $da);
    }

    public function testFlatten_EscapeDots()
    {
        $arr = [
            'a.b.' => [
                'c.d' => 'e',
            ],
        ];
        $da = new DotArray($arr);
        $flat = $da->flatten();
        $expected = [
            'a\.b\..c\.d' => 'e',
        ];
        $this->assertSame($expected, $flat);
        $this->assertFlatArrayMatchesDeepArray($flat, $da);
    }

    public function testFlatten_EscapeSlashes()
    {
        $arr = [
            'a.b\.' => [
                'c.d' => 'e',
            ],
        ];
        $da = new DotArray($arr);
        $flat = $da->flatten();
        $expected = [
            'a\.b\\\\\\..c\.d' => 'e',
        ];
        $this->assertSame($expected, $flat);
        $this->assertFlatArrayMatchesDeepArray($flat, $da);
    }

    public function testFlatten_EscapeAllTheThings()
    {
        $arr = [
            '..\\' => [
                '\\\"".\..\\.' => [
                    'x',
                ],
            ],
        ];
        $da = new DotArray($arr);
        $flat = $da->flatten();
        $this->assertFlatArrayMatchesDeepArray($flat, $da);
    }

    public function testFlatten_EscapeTrailingSlash()
    {
        $arr = [
            'a.b\\' => [
                'c.d' => 'e',
            ],
        ];
        $da = new DotArray($arr);
        $flat = $da->flatten();
        $expected = [
            'a\.b\\\\.c\.d' => 'e',
        ];
        $this->assertSame($expected, $flat);
        $this->assertFlatArrayMatchesDeepArray($flat, $da);
    }

    public function testFlatten_Empty()
    {
        $da = new DotArray();
        $this->assertCount(0, $da->flatten());
    }

    private function assertFlatArrayMatchesDeepArray($flat, DotArray $dotArray)
    {
        foreach ($flat as $key => $value) {
            $this->assertSame($value, $dotArray->get($key));
        }
    }
}
