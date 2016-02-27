<?php
namespace nochso\Omni\Test;

use nochso\Omni\EOL;
use nochso\Omni\Multiline;

class MultilineTest extends \PHPUnit_Framework_TestCase
{
    public function padProvider()
    {
        return [
            ['a', 'a', null, ' ', STR_PAD_LEFT],
            ["  \nbb", "\nbb", null, ' ', STR_PAD_LEFT],
            ["\n", "\n", null, ' ', STR_PAD_LEFT],
            ["xxxa\nxxbc\nxxxx", "a\nbc\n", 4, 'x', STR_PAD_LEFT],
            ["axxx\nbcxx\nxxxx", "a\nbc\n", 4, 'x', STR_PAD_RIGHT],
            ["xaxx\nxbcx\nxxxx", "a\nbc\n", 4, 'x', STR_PAD_BOTH],
        ];
    }

    /**
     * @dataProvider padProvider
     */
    public function testPad($expected, $input, $length, $padding, $paddingType)
    {
        $ml = Multiline::create($input);
        $ml->pad($length, $padding, $paddingType);
        $this->assertSame($expected, (string)$ml);
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
        $this->assertSame($expected, (string)$ml);
    }

    public function testGetLines()
    {
        $ml = Multiline::create("a\nb");
        $this->assertSame(['a', 'b'], $ml->getLines());
    }

    public function testGetEOL()
    {
        $ml = Multiline::create("a\r\nb");
        $this->assertSame(EOL::EOL_CR_LF, (string)$ml->getEol());
    }

    public function testSetEOL()
    {
        $ml = Multiline::create("a\r\nb");
        $ml->setEol("\n");
        $this->assertSame("\n", (string)$ml->getEol());
    }
}