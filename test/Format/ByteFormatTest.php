<?php
namespace nochso\Omni\Test\Format;

use nochso\Omni\Format\ByteFormat;

class ByteFormatTest extends \PHPUnit_Framework_TestCase
{
    public function formatDefaultProvider()
    {
        return [
            ['0 B', 0],
            ['1 B', 1],
            ['-1 B', -1],
            ['1023 B', 1023],
            ['1 KiB', 1024],
            ['1.07 KiB', 1100],
            ['1.1 MiB', 1024 * 1024 + 1024 * 100],
            ['1 YiB', pow(1024, 8)],
        ];
    }

    /**
     * @dataProvider formatDefaultProvider
     *
     * @param string $expected
     * @param int    $bytes
     */
    public function testFormatDefault($expected, $bytes)
    {
        $actual = ByteFormat::create()->format($bytes);
        $this->assertSame($expected, $actual);
    }

    public function testFormat_SmallFloat_MustThrow()
    {
        $b = ByteFormat::create();
        $this->expectException('InvalidArgumentException');
        $b->format(0.1);
    }

    public function formatBaseAndSuffixProvider()
    {
        return [
            ['1023 B', 1023, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_SIMPLE],
            ['1 K', 1024, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_SIMPLE],
            ['999 B', 999, ByteFormat::BASE_DECIMAL, ByteFormat::SUFFIX_SIMPLE],
            ['1 K', 1000, ByteFormat::BASE_DECIMAL, ByteFormat::SUFFIX_SIMPLE],
            ['1 mebibyte', 1024 * 1024, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_IEC_LONG],
            ['2 mebibytes', 1024 * 1024 * 2, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_IEC_LONG],
            ['0 bytes', 0, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_IEC_LONG],
            ['1 byte', 1, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_IEC_LONG],
            ['2 bytes', 2, ByteFormat::BASE_BINARY, ByteFormat::SUFFIX_IEC_LONG],
            ['1 gigabyte', 1000 * 1000 * 1000, ByteFormat::BASE_DECIMAL, ByteFormat::SUFFIX_SI_LONG],
            ['1.23 gigabytes', 1000 * 1000 * 1234, ByteFormat::BASE_DECIMAL, ByteFormat::SUFFIX_SI_LONG],
        ];
    }

    /**
     * @dataProvider formatBaseAndSuffixProvider
     *
     * @param string $expected
     * @param int    $bytes
     * @param int    $base
     * @param int    $suffix
     */
    public function testFormatBaseAndSuffix($expected, $bytes, $base, $suffix)
    {
        $this->assertSame($expected, ByteFormat::create($base, $suffix)->format($bytes));
    }

    public function testPrecisionTrimming_EnabledByDefault()
    {
        $b = ByteFormat::create(ByteFormat::BASE_DECIMAL)->setPrecision(5);
        $this->assertSame('1 KiB', $b->format(1000));
        $this->assertSame('1.234 KiB', $b->format(1234));
    }

    public function testDisablePrecisionTrimming()
    {
        $b = ByteFormat::create(ByteFormat::BASE_DECIMAL)->setPrecision(5)->disablePrecisionTrimming();
        $this->assertSame('1.00000 KiB', $b->format(1000));
        $this->assertSame('1.23400 KiB', $b->format(1234));
    }

    public function testSetBase_InvalidBase_MustThrow()
    {
        $this->expectException('InvalidArgumentException');
        $b = ByteFormat::create()->setBase('blorp');
    }

    public function testSetSuffix_InvalidSuffix_MustThrow()
    {
        $this->expectException('InvalidArgumentException');
        $b = ByteFormat::create()->setSuffix('foo');
    }

    public function testEnablePrecisionTrimming()
    {
        $b = ByteFormat::create()->setPrecision(5);
        $b->disablePrecisionTrimming();
        $this->assertSame('1.00000 KiB', $b->format(1024));
        $b->enablePrecisionTrimming();
        $this->assertSame('1 KiB', $b->format(1024));
    }
}
