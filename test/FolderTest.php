<?php
namespace nochso\Omni\Test;

use nochso\Omni\Folder;
use nochso\Omni\Path;

class FolderTest extends \PHPUnit_Framework_TestCase
{
    private static $tmp;

    public static function setUpBeforeClass()
    {
        self::$tmp = Path::combine(sys_get_temp_dir(), 'phpunit-nochso-omni');
    }

    protected function setUp()
    {
        $this->cleanUp();
    }

    protected function tearDown()
    {
        $this->cleanUp();
    }

    public function testEnsure()
    {
        $this->assertFalse(is_dir(self::$tmp));
        Folder::ensure(self::$tmp);
        $this->assertTrue(is_dir(self::$tmp));
        Folder::ensure(self::$tmp);
        $this->assertTrue(is_dir(self::$tmp));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEnsureThrows()
    {
        Folder::ensure('');
    }

    protected function cleanUp()
    {
        if (is_dir(self::$tmp)) {
            rmdir(self::$tmp);
        }
    }
}
