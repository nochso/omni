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

    public function testGetMostFrequentNeedle()
    {
        $haystack = 'abababcc';
        $needles = ['ab', 'c'];
        $this->assertSame('ab', Strings::getMostFrequentNeedle($haystack, $needles));
    }

    public function testGetMostFrequentNeedle_WhenTied_MustReturnFirstTie()
    {
        $haystack = 'bbbaaa';
        $needles = ['a', 'b'];
        $this->assertSame('a', Strings::getMostFrequentNeedle($haystack, $needles));
    }

    public function testGetMostFrequentNeedle_WhenNoNeedle_MustReturnNull()
    {
        $haystack = '';
        $needles = ['a', 'b'];
        $this->assertNull(Strings::getMostFrequentNeedle($haystack, $needles));
    }

    public function testEscapeControlChars()
    {
        $input = "\n\r\t\v\f\\foo\033\x01";
        $expected = '\n\r\t\v\f\\\\foo\e\x01';
        $this->assertNotSame("\033", '\e');
        $this->assertNotSame("\x01", '\x01');
        $this->assertSame($expected, Strings::escapeControlChars($input));
    }
}
