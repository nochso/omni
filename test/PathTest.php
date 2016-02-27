<?php

namespace nochso\Omni\Test;

use nochso\Omni\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function combineProvider()
    {
        return [
            ['', ['', '']],
            ['/', ['', '/']],
            ['/', ['/', '']],
            ['/a', ['/', 'a']],
            ['foo/bar', ['foo', 'bar']],
            ['/foo/bar', ['/foo', 'bar']],
            ['/foo/bar', ['/foo', '/bar']],
            ['/foo/bar', ['/foo/', '/bar']],
            ['1/2/3/4/5.zip', ['1', '2', '3', '4', '5.zip']],
            ['1/2', [['1', '2']], 'Array as single parameter'],
            ['1/2/3.zip', [['1', '2'], '3.zip'], 'Array and string as parameter'],
        ];
    }

    /**
     * @dataProvider combineProvider
     */
    public function testCombine($expected, $params, $message = '')
    {
        $this->assertEquals($expected, Path::combine(...$params), $message);
    }

    public function testLocalize()
    {
        $this->assertSame('a/b', Path::localize('a\\b'));
        $this->assertSame('a/b', Path::localize('a\\b', '/'));
        $this->assertSame('a\\b', Path::localize('a/b', '\\'));
        $this->assertSame('a\\b', Path::localize('a\\b', '\\'));
    }
}
