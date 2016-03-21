<?php
namespace nochso\Omni\Test;

use nochso\Omni\Strings;

class StringsTest extends \PHPUnit_Framework_TestCase
{
    public function startsWithProvider()
    {
        return [
            [true, 'foo', ''],
            [true, 'foo', 'f'],
            [true, 'foo', 'fo'],
            [true, 'foo', 'foo'],
            [false, '', 'o'],
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
            [true, 'foo', ''],
            [true, 'foo', 'o'],
            [true, 'foo', 'oo'],
            [true, 'foo', 'foo'],
            [false, '', 'foo'],
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

    /**
     * @dataProvider padMultibyteProvider
     */
    public function testPadMultibyte($expected, $input, $padLength, $padding, $paddingType, $message = '')
    {
        $this->assertSame($expected, Strings::padMultibyte($input, $padLength, $padding, $paddingType), $message);
    }

    public function padMultibyteProvider()
    {
        return [
            ['AÄaö', 'AÄ', 4, 'aö', STR_PAD_RIGHT],
            ['AÄaöa', 'AÄ', 5, 'aö', STR_PAD_RIGHT],
            ['aöAÄ', 'AÄ', 4, 'aö', STR_PAD_LEFT],
            ['aöaAÄ', 'AÄ', 5, 'aö', STR_PAD_LEFT],
            ['aAÄa', 'AÄ', 4, 'aö', STR_PAD_BOTH, 'Must pad equally'],
            ['aAÄaö', 'AÄ', 5, 'aö', STR_PAD_BOTH, 'Must prefer trailing padding'],
            ['aöAÄaö', 'AÄ', 6, 'aö', STR_PAD_BOTH, 'Must pad equally'],
            ['ÜÖÄ', 'ÜÖÄ', 3, 'È', STR_PAD_LEFT],
            ['öäöäö', '', 5, 'öä', STR_PAD_BOTH, 'Pad empty input'],
            ['lööng', 'lööng', 1, 'x', STR_PAD_BOTH, 'Return original already long enough'],
        ];
    }

    public function testPad_InvalidPaddingType_MustThrow()
    {
        $this->expectException('InvalidArgumentException');
        Strings::padMultibyte('a', 4, ' ', 'bad');
    }

    public function testPadMultiByte_EmptyPaddingString_MustThrow()
    {
        $this->expectException('InvalidArgumentException');
        Strings::padMultibyte('föö', 23, '');
    }

    public function testGetCommonPrefix()
    {
        $this->assertSame('a', Strings::getCommonPrefix('abc', 'afoo'));
        $this->assertSame('', Strings::getCommonPrefix('xxx', 'yxx'));
        $this->assertSame('foo', Strings::getCommonPrefix('foo', 'foo'));
        $this->assertSame('foo', Strings::getCommonPrefix('foooooo', 'foo'));
    }

    public function testGetCommonSuffix()
    {
        $this->assertSame('o', Strings::getCommonSuffix('foo', 'bao'));
    }

    public function testReverse()
    {
        $this->assertSame('ao', Strings::reverse('oa'));
        $this->assertSame('aö', Strings::reverse('öa'));
        $this->assertSame('', Strings::reverse(''));
    }

    public function groupByCommonPrefixProvider()
    {
        return [
            [
                ['/root/' => ['bar/baz', 'foo']],
                [
                    '/root/foo',
                    '/root/bar/baz',
                ],
            ],
            [
                ['' => ['/root/bar/baz', 'x/root/foo']],
                [
                    'x/root/foo',
                    '/root/bar/baz',
                ],
            ],
            [
                ['/root' => ['', '/foo']],
                ['/root/foo', '/root'],
            ],
        ];
    }

    /**
     * @dataProvider groupByCommonPrefixProvider
     */
    public function testGroupByCommonPrefix($expected, $input)
    {
        $this->assertSame($expected, Strings::groupByCommonPrefix($input));
    }

    public function testGroupByCommonSuffix()
    {
        $this->assertSame(['b' => ['a', 'c']], Strings::groupByCommonSuffix(['ab', 'cb']));
    }
}
