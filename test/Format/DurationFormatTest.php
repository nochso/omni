<?php
namespace nochso\Omni\Test\Format;

use nochso\Omni\Format\Duration;

class DurationFormatTest extends \PHPUnit_Framework_TestCase {
	public function formatProvider() {
		return [
			[Duration::FORMAT_SHORT, '0s', 0],
			[Duration::FORMAT_SHORT, '1s', 1],
			[Duration::FORMAT_SHORT, '1m', 60],
			[Duration::FORMAT_SHORT, '1m 59s', 119],
			[Duration::FORMAT_SHORT, '1y 5d', new \DateInterval('P1Y5D')],
			[Duration::FORMAT_SHORT, '1mo', new \DateInterval('P30D')],
			[Duration::FORMAT_LONG, '0 seconds', 0],
			[Duration::FORMAT_LONG, '1 second', 1],
			[Duration::FORMAT_LONG, '1 minute', 60],
			[Duration::FORMAT_LONG, '1 minute 59 seconds', 119],
			[Duration::FORMAT_LONG, '1 year 5 days', new \DateInterval('P1Y5D')],
			[Duration::FORMAT_LONG, '1 month', new \DateInterval('P30D')],
		];
	}

	/**
	 * @dataProvider formatProvider
	 *
	 * @param string|int        $formatName
	 * @param string            $expected
	 * @param int|\DateInterval $duration
	 */
	public function testFormat($formatName, $expected, $duration) {
		$df = Duration::create($formatName);
		$this->assertSame($expected, $df->format($duration));
	}

	public function testAddFormat() {
		$df = Duration::create();
		$format = [
			Duration::YEAR => ' Jahr(e)',
			Duration::MONTH => ' Monat(e)',
			Duration::WEEK => ' Woche(n)',
			Duration::DAY => ' Tag(e)',
			Duration::HOUR => ' Stunde(n)',
			Duration::MINUTE => ' Minute(n)',
			Duration::SECOND => ' Sekunde(n)',
		];
		$df->addFormat('german', $format);
		$this->assertSame('0 Sekunden', $df->format(0));
		$this->assertSame('1 Jahr 3 Stunden', $df->format(new \DateInterval('P1YT3H')));
	}

	public function testSetFormat() {
		$df = Duration::create();
		$this->expectException('InvalidArgumentException');
		$df->setFormat('does not exist');
	}

	public function testLimitPeriods() {
		$df = Duration::create()->limitPeriods(2);
		$this->assertSame('1y 3h', $df->format(new \DateInterval('P1YT3H5M3S')));
	}
}
