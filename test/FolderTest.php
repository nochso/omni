<?php
namespace nochso\Omni\Test;

use nochso\Omni\Folder;
use nochso\Omni\Path;

class FolderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string Path to be used by all tests in this class
     */
    private static $base;

    public static function setUpBeforeClass()
    {
        self::$base = Path::combine(__DIR__, 'temp', 'Folder');
    }

    public static function tearDownAfterClass()
    {
        Folder::delete(self::$base);
    }

    public function testEnsure()
    {
        $path = Path::combine(self::$base, 'ensure');
        $this->assertFalse(is_dir($path));
        Folder::ensure($path);
        $this->assertTrue(is_dir($path));
        Folder::ensure($path);
        $this->assertTrue(is_dir($path));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEnsureThrows()
    {
        Folder::ensure('');
    }

    public function testDeleteContents()
    {
        $base = Path::combine(self::$base, 'deleteContents');
        Folder::ensure($base);

        $filepath = Path::combine($base, 'foo.txt');
        touch($filepath);
        $subfolder = Path::combine($base, 'sub-folder');
        Folder::ensure($subfolder);
        Folder::deleteContents($base);

        $this->assertFalse(is_file($filepath));
        $this->assertFalse(is_dir($subfolder));
        $this->assertTrue(is_dir($base));
    }

    public function testDeleteContents_WhenFolderMissing_MustNotThrow()
    {
        $base = Path::combine(self::$base, 'deleteContents_missing');
        $this->assertFalse(is_dir($base), 'Unable to set up test');
        Folder::deleteContents($base);
    }

    public function testDelete()
    {
        $base = Path::combine(self::$base, 'delete');
        Folder::ensure($base);
        Folder::delete($base);
        $this->assertFalse(is_dir($base));
    }
}
