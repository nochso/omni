<?php
namespace nochso\Omni\Test\PhpCsFixer;

use nochso\Omni\PhpCsFixer\PrettyPSR;
use Symfony\CS\FixerInterface;

class PrettyPSRTest extends \PHPUnit_Framework_TestCase {
	public function testCreateIn() {
		$config = PrettyPSR::createIn(__DIR__);
		$this->assertSame(FixerInterface::PSR2_LEVEL, $config->getLevel());
		$this->assertNotFalse(array_search('ordered_use', $config->getDefaultFixers()));
	}
}
