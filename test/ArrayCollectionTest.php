<?php
namespace nochso\Omni\Test;

use nochso\Omni\ArrayCollection;

class ArrayCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testCountable()
    {
        $l = new ArrayCollection([]);
        $this->assertSame(0, count($l));
        $l = new ArrayCollection(['a']);
        $this->assertSame(1, count($l));
    }

    public function testAdd()
    {
        $l = new ArrayCollection();
        $this->assertSame(0, count($l));
        $l->add('foo');
        $this->assertSame(1, count($l));
    }

    public function testSet()
    {
        $l = new ArrayCollection(['a' => 'b', 'c' => 'd']);
        $l->set('c', 'foo');
        $this->assertSame('foo', $l['c']);
    }

    public function testRemove()
    {
        $l = new ArrayCollection(['a' => 'b', 'c' => 'd']);
        $this->assertSame('b', $l->remove('a'));
    }

    public function testRemove_WhenMissing_MustReturnNull()
    {
        $l = new ArrayCollection();
        $this->assertNull($l->remove('foo'));
    }

    public function testToArray()
    {
        $l = new ArrayCollection(['a']);
        $this->assertSame(['a'], $l->toArray());
    }

    public function testGetIterator()
    {
        $l = new ArrayCollection(['a', 'b']);
        $c = 0;
        foreach ($l as $element) {
            $c++;
        }
        $this->assertSame(2, $c);
    }

    public function testOffsetExists()
    {
        $l = new ArrayCollection(['a', 'key' => 'foo']);
        $this->assertTrue(isset($l[0]));
        $this->assertTrue(isset($l['key']));
    }

    public function testOffSetSet()
    {
        $l = new ArrayCollection();
        $l[] = 'test';
        $this->assertCount(1, $l);
    }

    public function testOffsetUnset()
    {
        $l = new ArrayCollection(['a', 'b']);
        unset($l[1]);
        $this->assertSame(['a'], $l->toArray());
    }

    public function testFirst()
    {
        $l = new ArrayCollection(['a', 'b']);
        $this->assertSame('a', $l->first());
    }

    public function testLast()
    {
        $l = new ArrayCollection(['a', 'b']);
        $this->assertSame('b', $l->last());
    }

    public function testApplyCallable()
    {
        $l = new ArrayCollection(['a', 'b']);
        $l->apply(function ($element) { return strtoupper($element);});
        $this->assertSame(['A', 'B'], $l->toArray());
    }
    public function testApplyString()
    {
        $l = new ArrayCollection(['a', 'b']);
        $l->apply('strtoupper');
        $this->assertSame(['A', 'B'], $l->toArray());
    }
}
