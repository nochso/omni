<?php
namespace nochso\Omni\Test;

use nochso\Omni\Exec;

class ExecTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate_AcceptsNoPrefix()
    {
        $runner = Exec::create();
        $this->assertSame(Exec::class, get_class($runner));
    }

    public function testGetCommand()
    {
        $runner = Exec::create();
        $this->assertSame('', $runner->getCommand());
        $this->assertSame("'foo'", $runner->getCommand('foo'));
    }

    public function testGetLastCommand()
    {
        $runner = Exec::create('echo');
        $this->assertNull($runner->getLastCommand());
        $runner->run('foo');
        $lastCommand = $runner->getLastCommand();
        $this->assertSame($runner->getCommand('foo'), $lastCommand);
    }

    public function testRun()
    {
        $runner = Exec::create('echo');
        $runner->run('test');
        $this->assertSame(['test'], $runner->getOutput());
        $this->assertSame(0, $runner->getStatus());
    }

    public function testRun_MultiplePrefixes()
    {
        $runner = Exec::create('echo', 'test');
        $runner->run();
        $this->assertSame(['test'], $runner->getOutput());
    }

    public function testCreate_SplatVariadic()
    {
        $runner = Exec::create(...['echo', 'test']);
        $runner->run();
        $this->assertSame(['test'], $runner->getOutput());
    }

    public function testRun_SplatVariadic()
    {
        $runner = Exec::create();
        $runner->run(...['echo', 'test']);
        $this->assertSame(['test'], $runner->getOutput());
    }

    public function testInvoke()
    {
        $runner = Exec::create('echo');
        $runner('test');
        $this->assertSame(['test'], $runner->getOutput());
        $this->assertSame(0, $runner->getStatus());
    }

    public function testInvoke_SplatVariadic()
    {
        $runner = Exec::create();
        $runner(...['echo', 'test']);
        $this->assertSame(['test'], $runner->getOutput());
    }

    public function testFluentInvoke()
    {
        $runner = Exec::create('echo');
        $this->assertSame(['test'], $runner('test')->getOutput());
    }

    public function testAllArgumentsAreEscaped()
    {
        $runner = Exec::create('escape me"!');
        $cmd = $runner->getCommand("needs more escaping' '\"");
        $this->assertSame("'escape me\"!' 'needs more escaping'\\'' '\\''\"'", $cmd);
    }
}
