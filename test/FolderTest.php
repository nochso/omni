<?php
namespace nochso\Omni\Test;

use nochso\Omni\Folder;
use nochso\Omni\Path;
use org\bovigo\vfs\vfsStream;

class FolderTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var string Path to be used by all tests in this class
	 */
	private static $base;

	public static function setUpBeforeClass() {
		self::$base = Path::combine(__DIR__, 'temp', 'Folder');
		Folder::delete(self::$base);
	}

	public static function tearDownAfterClass() {
		Folder::delete(self::$base);
	}

	public function testEnsure() {
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
	public function testEnsureThrows() {
		Folder::ensure('');
	}

	public function testDeleteContents() {
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

	public function testDeleteContents_WhenFolderMissing_MustNotThrow() {
		$base = Path::combine(self::$base, 'deleteContents_missing');
		$this->assertFalse(is_dir($base), 'Unable to set up test');
		Folder::deleteContents($base);
	}

	public function testDelete() {
		$base = Path::combine(self::$base, 'delete');
		Folder::ensure($base);
		Folder::delete($base);
		$this->assertFalse(is_dir($base));
	}

	public function testDelete_WhenFolderMissing_MustNotThrow() {
		$base = Path::combine(self::$base, 'delete_missing');
		$this->assertFalse(is_dir($base), 'Unable to set up test');
		Folder::delete($base);
		$this->assertFalse(is_dir($base));
	}

	public function testDelete_FailToDeleteFile_MustThrow() {
		$root = vfsStream::setup('root', 0400);
		vfsStream::newFile('test', 0444)->at($root);
		$this->expectException('RuntimeException');
		Folder::delete($root->url());
	}

	public function testDelete_FailToDeleteFolder_MustThrow() {
		$root = vfsStream::setup('root', 0444);
		$folder = vfsStream::newDirectory('test', 0444)->at($root);
		$this->expectException('RuntimeException');
		Folder::delete($root->url());
	}

	public function testDelete_FailToDeleteRootFolder_MustThrow() {
		$root = vfsStream::setup('root', 0444);
		$folder = vfsStream::newDirectory('test', 0444)->at($root);
		$this->expectException('RuntimeException');
		Folder::delete($folder->url());
	}
}
