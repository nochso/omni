<?php
namespace nochso\Omni\Test;

use nochso\Omni\Type;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function summarizeProvider()
    {
        return [
            ['string', 'x'],
            ['boolean', false],
            ['double', 2.0],
            ['integer', 2],
            [self::class, $this],
        ];
    }

    /**
     * @dataProvider summarizeProvider
     */
    public function testSummarize($expected, $input)
    {
        $this->assertSame($expected, Type::summarize($input));
    }

    public function testGetClassName()
    {
        $this->assertSame('TypeTest', Type::getClassName($this));
    }
}
