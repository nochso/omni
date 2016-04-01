<?php
namespace nochso\Omni\Test;

use nochso\Omni\Exec;
use nochso\Omni\OS;

class ExecTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate_AcceptsNoPrefix()
    {
        $runner = Exec::create();
        $this->assertSame(Exec::class, get_class($runner));
    }

    public function getCommandProvider()
    {
        if (OS::isWindows()) {
            return [
                ['""', []],
                ['""', ['']],
                ['foo', ['foo']],
                ['foo bar', ['foo', 'bar']],
                ['foo "bar bar"', ['foo', 'bar bar']],
                ['foo "bar\\"bar"', ['foo', 'bar"bar']],
                ['"foo bar.exe"', ['foo bar.exe']],
                ['foo.exe "test\\\\"', ['foo.exe', 'test\\']],
                // See https://blogs.msdn.microsoft.com/twistylittlepassagesallalike/2011/04/23/everyone-quotes-command-line-arguments-the-wrong-way/
                ['child.exe argument1 "argument 2" "\some\path with\spaces"', ['child.exe', 'argument1', 'argument 2', '\some\path with\spaces']],
                ['child.exe argument1 "she said, \"you had me at hello\"" "\some\path with\spaces"', ['child.exe', 'argument1', 'she said, "you had me at hello"', '\some\path with\spaces']],
                ['child.exe argument1 "argument\"2" argument3 argument4', ['child.exe', 'argument1', 'argument"2', 'argument3', 'argument4']],
                ['child.exe "\some\directory with\spaces\\\\" argument2', ['child.exe', '\some\directory with\spaces\\', 'argument2']],
            ];
        }
        return [
            ["''", []],
            ["''", ['']],
            ['foo', ['foo']],
            ['foo bar', ['foo', 'bar']],
            ["foo 'bar bar'", ['foo', 'bar bar']],
            ["'foo bar'", ['foo bar']],
            ["foo 'test\\'", ['foo', 'test\\']],
            ['foo -x bar', ['foo', '-x', 'bar']],
            ['foo --bar x', ['foo', '--bar', 'x']],
            ["foo '--' x", ['foo', '--', 'x']],
        ];
    }

    /**
     * @dataProvider getCommandProvider
     */
    public function testGetCommand($expected, $command)
    {
        $runner = Exec::create();
        $this->assertSame($expected, $runner->getCommand(...$command));
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
}
