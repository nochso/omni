<?php
namespace nochso\Omni\Test;

use nochso\Omni\OS;
use nochso\Omni\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function combineProvider()
    {
        $tests = [
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
            ['1/2/3', [['1/2/', '/3']], 'Multiple slashes should be simplified'],
            ['./1/2/3/', [['.//1//2//', '3//']], 'Multiple slashes should be simplified'],
            ['protocol://folder', [['protocol://', 'folder']]],
            ['protocol://folder', [['protocol://', '/folder']]],
            ['protocol://folder/', [['protocol://', '//folder/']]],
        ];
        if (OS::isWindows()) {
            foreach ($tests as &$test) {
                $test[0] = str_replace('/', '\\', $test[0]);
            }
        }
        return $tests;
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
        if (OS::isWindows()) {
            $this->assertSame('a\\b', Path::localize('a/b'));
            $this->assertSame('a\\b', Path::localize('a\\b'));
        } else {
            $this->assertSame('a/b', Path::localize('a\\b'));
            $this->assertSame('a/b', Path::localize('a/b'));
        }
    }

    public function testLocalizeOverrideSeparator()
    {
        $this->assertSame('a/b', Path::localize('a\\b', '/'));
        $this->assertSame('a\\b', Path::localize('a/b', '\\'));
        $this->assertSame('a\\b', Path::localize('a\\b', '\\'));
    }

    public function testContains()
    {
        $this->assertTrue(Path::contains(__DIR__, __FILE__));
    }

    public function testContainsEscaping()
    {
        $this->assertFalse(Path::contains(__DIR__, __DIR__ . '/../README.md'));
    }

    public function testContainsFileMissing()
    {
        $this->assertFalse(Path::contains(__DIR__, __DIR__ . '/foobar'));
    }

    public function isAbsoluteProvider()
    {
        return [
            [true, '/home/user'],
            [true, '/home/../user'],
            [true, 'c:/win'],
            [true, 'd:\\setup.exe'],
            [true, '\\\\x\\x', 'UNC should be absolute'],
            [true, 'scheme://x/x', 'Scheme should be absolute'],
            [true, 'valid-scheme://x', 'Valid scheme'],
            [true, 'val1d+foo://x\'', 'Valid scheme'],
            [false, 'scheme:\\x/x', 'Invalid scheme'],
            [false, 'invalid,scheme://x', 'Invalid scheme'],
            [false, './../user'],
            [false, './user/foo'],
            [false, 'user\\setup.exe'],
        ];
    }

    /**
     * @dataProvider isAbsoluteProvider
     */
    public function testIsAbsolute($expected, $path, $message = '')
    {
        $this->assertSame($expected, Path::isAbsolute($path), $message);
    }
}
