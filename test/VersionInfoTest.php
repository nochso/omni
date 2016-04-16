<?php
namespace nochso\Omni\Test;

use nochso\Omni\VersionInfo;

class VersionInfoTest extends \PHPUnit_Framework_TestCase {
	public function testGetInfoDefault() {
		$version = new VersionInfo('Name', '0.1.0');
		$this->assertSame('Name v0.1.0', $version->getInfo());
	}

	public function testGetInfoShort() {
		$version = new VersionInfo('Name', '0.1.0', VersionInfo::INFO_FORMAT_SHORT);
		$this->assertSame('Name 0.1.0', $version->getInfo());
	}

	public function testGetInfoLong() {
		$version = new VersionInfo('Name', '0.1.0', VersionInfo::INFO_FORMAT_LONG);
		$this->assertSame('Name (Version 0.1.0)', $version->getInfo());
	}
}
