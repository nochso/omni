<?php
namespace nochso\Omni\Test\PhpCsFixer;

use nochso\Omni\PhpCsFixer\DefaultFinder;
use org\bovigo\vfs\vfsStream;

class DefaultFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateIn()
    {
        $finder = DefaultFinder::createIn(__DIR__);
        $this->assertGreaterThan(0, $finder->count());
    }

    public function testReadExcludeLines_WhenNoReadPermission_MustNotThrow()
    {
        $root = vfsStream::setup();
        $file = vfsStream::newFile('.gitignore')->at($root)->setContent('foo');
        $finder = DefaultFinder::createIn($root->url());
        $this->assertSame(0, $finder->count());
    }
}
