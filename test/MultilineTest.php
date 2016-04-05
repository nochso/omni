<?php
namespace nochso\Omni\Test;

use nochso\Omni\EOL;
use nochso\Omni\Multiline;

class MultilineTest extends \PHPUnit_Framework_TestCase
{
    public function getLinesProvider()
    {
        return [
            [['a', 'b'], "a\nb"],
            [['a', 'b'], "a\r\nb"],
            [['a', 'b', ''], "a\nb\n"],
        ];
    }

    /**
     * @dataProvider getLinesProvider
     *
     * @param string[] $expectedLines
     * @param string   $input
     */
    public function testGetLines(array $expectedLines, $input)
    {
        $ml = Multiline::create($input);
        $this->assertSame($expectedLines, $ml->toArray());
    }

    public function createProvider()
    {
        return [
            ["a\nb", "a\nb"],
            ["a\r\nb", "a\r\nb"],
            'Must clean up left over line feeds' => ["a\nb\nc", "a\r\nb\nc"],
        ];
    }

    /**
     * @dataProvider createProvider
     *
     * @param string $expected
     * @param string $input
     */
    public function testCreate($expected, $input)
    {
        $ml = Multiline::create($input);
        $this->assertSame($expected, (string) $ml);
    }

    public function padProvider()
    {
        return [
            ['a', 'a', null, ' ', STR_PAD_LEFT],
            ["  \nbb", "\nbb", null, ' ', STR_PAD_LEFT],
            ["\n", "\n", null, ' ', STR_PAD_LEFT],
            ["xxxa\nxxbc\nxxxx", "a\nbc\n", 4, 'x', STR_PAD_LEFT],
            ["axxx\nbcxx\nxxxx", "a\nbc\n", 4, 'x', STR_PAD_RIGHT],
            ["xaxx\nxbcx\nxxxx", "a\nbc\n", 4, 'x', STR_PAD_BOTH],
            ["aü\n ü", "aü\nü", null, ' ', STR_PAD_LEFT],
            ["aü\nü ", "aü\nü", null, ' ', STR_PAD_RIGHT],
            ["aüä\n ü ", "aüä\nü", null, ' ', STR_PAD_BOTH],
        ];
    }

    /**
     * @dataProvider padProvider
     */
    public function testPad($expected, $input, $length, $padding, $paddingType)
    {
        $ml = Multiline::create($input);
        $ml->pad($length, $padding, $paddingType);
        $this->assertSame($expected, (string) $ml);
    }

    public function prefixProvider()
    {
        return [
            ["    a\r\n    b", "a\r\nb", '    '],
            ["    a\r\n     b", "a\r\n b", '    '],
            ['    ', '', '    '],
        ];
    }

    /**
     * @dataProvider prefixProvider
     */
    public function testPrefix($expected, $input, $prefix)
    {
        $ml = Multiline::create($input)->prefix($prefix);
        $this->assertSame($expected, (string) $ml);
    }

    public function testGetEOL()
    {
        $ml = Multiline::create("a\r\nb");
        $this->assertSame(EOL::EOL_CR_LF, (string) $ml->getEol());
    }

    public function testSetEOL()
    {
        $ml = Multiline::create("a\r\nb");
        $ml->setEol("\n");
        $this->assertSame("\n", (string) $ml->getEol());
    }

    public function testAppend()
    {
        $ml = Multiline::create("a\nb");
        $ml->append('c');
        $this->assertSame('bc', $ml[1]);
    }

    public function testGetMaxLength()
    {
        $ml = Multiline::create("a\nbb\nccc");
        $this->assertSame(3, $ml->getMaxLength());
    }

    public function testGetMaxLengthEmpty()
    {
        $ml = Multiline::create('');
        $this->assertSame(0, $ml->getMaxLength());

        $ml = Multiline::create("\r\n");
        $this->assertSame(0, $ml->getMaxLength());
    }

    public function testGetMaxLengthUTF8()
    {
        $ml = Multiline::create("a\nbb\nüäö");
        $this->assertSame(3, $ml->getMaxLength());
    }
}
