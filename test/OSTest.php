<?php

namespace nochso\Omni\Test;

use nochso\Omni\OS;

class OSTest extends \PHPUnit_Framework_TestCase
{
    public function isWindowsProvider()
    {
        return [
            [true, 'WIN32'],
            [true, 'WINNT'],
            [true, 'Windows'],
            [false, 'CYGWIN_NT-5.1'],
            [false, 'Darwin'],
            [false, 'Linux'],
        ];
    }

    /**
     * @dataProvider isWindowsProvider
     */
    public function testIsWindows($expected, $phpOs)
    {
        $this->assertSame($expected, OS::isWindows($phpOs));
    }
}