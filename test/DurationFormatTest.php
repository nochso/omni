<?php
namespace nochso\Omni\Test;

use nochso\Omni\DurationFormat;

class DurationFormatTest extends \PHPUnit_Framework_TestCase
{
    public function formatProvider()
    {
        return [
            [DurationFormat::FORMAT_SHORT, '0s', 0],
            [DurationFormat::FORMAT_SHORT, '1s', 1],
            [DurationFormat::FORMAT_SHORT, '1m', 60],
            [DurationFormat::FORMAT_SHORT, '1m 59s', 119],
            [DurationFormat::FORMAT_SHORT, '1y 5d', new \DateInterval('P1Y5D')],
            [DurationFormat::FORMAT_SHORT, '1mo', new \DateInterval('P30D')],
            [DurationFormat::FORMAT_LONG, '0 seconds', 0],
            [DurationFormat::FORMAT_LONG, '1 second', 1],
            [DurationFormat::FORMAT_LONG, '1 minute', 60],
            [DurationFormat::FORMAT_LONG, '1 minute 59 seconds', 119],
            [DurationFormat::FORMAT_LONG, '1 year 5 days', new \DateInterval('P1Y5D')],
            [DurationFormat::FORMAT_LONG, '1 month', new \DateInterval('P30D')],
        ];
    }

    /**
     * @dataProvider formatProvider
     *
     * @param string|int        $formatName
     * @param string            $expected
     * @param int|\DateInterval $duration
     */
    public function testFormat($formatName, $expected, $duration)
    {
        $df = DurationFormat::create($formatName);
        $this->assertSame($expected, $df->format($duration));
    }

    public function testAddFormat()
    {
        $df = DurationFormat::create();
        $format = [
            DurationFormat::YEAR => ' Jahr(e)',
            DurationFormat::MONTH => ' Monat(e)',
            DurationFormat::WEEK => ' Woche(n)',
            DurationFormat::DAY => ' Tag(e)',
            DurationFormat::HOUR => ' Stunde(n)',
            DurationFormat::MINUTE => ' Minute(n)',
            DurationFormat::SECOND => ' Sekunde(n)',
        ];
        $df->addFormat('german', $format);
        $this->assertSame('0 Sekunden', $df->format(0));
        $this->assertSame('1 Jahr 3 Stunden', $df->format(new \DateInterval('P1YT3H')));
    }

    public function testSetFormat()
    {
        $df = DurationFormat::create();
        $this->expectException('InvalidArgumentException');
        $df->setFormat('does not exist');
    }

    public function testLimitPeriods()
    {
        $df = DurationFormat::create()->limitPeriods(2);
        $this->assertSame('1y 3h', $df->format(new \DateInterval('P1YT3H5M3S')));
    }
}
