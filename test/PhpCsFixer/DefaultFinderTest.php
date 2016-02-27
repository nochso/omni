<?php

namespace nochso\Omni\Test\PhpCsFixer;


use nochso\Omni\PhpCsFixer\DefaultFinder;

class DefaultFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateIn()
    {
        $finder = DefaultFinder::createIn(__DIR__);
        $this->assertGreaterThan(0, $finder->count());
    }
}
