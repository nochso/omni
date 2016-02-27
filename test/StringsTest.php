<?php
namespace nochso\Omni\Test;

use nochso\Omni\Strings;

class StringsTest extends \PHPUnit_Framework_TestCase
{
    public function startsWithProvider()
    {
        return [
            [true, 'foo', 'f'],
            [true, 'foo', 'fo'],
            [true, 'foo', 'foo'],
            [false, 'foo', 'o'],
            [false, 'foo', 'ooo'],
            [false, 'foo', 'fooo'],
        ];
    }

    /**
     * @dataProvider startsWithProvider
     */
    public function testStartsWith($expected, $subject, $prefix)
    {
        $this->assertSame($expected, Strings::startsWith($subject, $prefix));
    }

    public function endsWithProvider()
    {
        return [
            [true, 'foo', 'o'],
            [true, 'foo', 'oo'],
            [true, 'foo', 'foo'],
            [false, 'foo', 'f'],
            [false, 'foo', 'fo'],
            [false, 'foo', 'ooo'],
        ];
    }

    /**
     * @dataProvider endsWithProvider
     */
    public function testEndsWith($expected, $subject, $suffix)
    {
        $this->assertSame($expected, Strings::endsWith($subject, $suffix));
    }

//    public function indentMultilineProvider()
//    {
//        return [
//            ["    a\r\n    b", "a\r\nb"],
//            ["    a\r\n     b", "a\r\n b"],
//            ['    ', ''],
//        ];
//    }
//
//    /**
//     * @dataProvider indentMultilineProvider
//     */
//    public function testIndentMultiline($expected, $input)
//    {
//        $this->assertSame($expected, Strings::prefixMultiline($input));
//    }
}
