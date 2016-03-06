<?php
namespace nochso\Omni\Test;

use nochso\Omni\EOL;

class EOLTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $eol = new EOL(EOL::EOL_LF);
        $this->assertSame((string)$eol, EOL::EOL_LF);
    }

    public function testGetName()
    {
        $eol = new EOL(EOL::EOL_LF);
        $this->assertSame('LF', $eol->getName());
    }

    public function testGetDescription()
    {
        $eol = new EOL(EOL::EOL_LF);
        $this->assertSame('Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS', $eol->getDescription());
    }

    public function testConvertTo()
    {
        $eol = new EOL(EOL::EOL_CR_LF);
        $this->assertSame("a\r\nb", $eol->apply("a\nb"));
    }

    public function testDetectLF()
    {
        $eol = EOL::detect("a\nx\nccc");
        $this->assertSame(EOL::EOL_LF, (string)$eol);
    }

    public function testDetectCRLF()
    {
        $this->assertSame(EOL::EOL_CR_LF, (string)EOL::detect("a\r\nx\r\nccc"));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDetect_WhenNoEOL_MustThrow()
    {
        EOL::detect(' xcx ');
    }

    public function testDetectDefaultCRLF()
    {
        $this->assertSame(EOL::EOL_CR_LF, (string)EOL::detectDefault("a\r\nx\r\nccc"));
    }

    public function testDetectDefault_WhenNoEOL_MustNotThrow()
    {
        $this->assertSame(EOL::EOL_LF, (string)EOL::detectDefault('a'));
    }

    public function testDetectDefault_WhenNoEOL_UseDefault()
    {
        $this->assertSame(EOL::EOL_CR_LF, (string)EOL::detectDefault('a', EOL::EOL_CR_LF));
    }
}
