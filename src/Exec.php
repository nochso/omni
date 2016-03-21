<?php
namespace nochso\Omni;

/**
 * Exec creates objects that help manage `\exec()` calls.
 *
 * The returned object itself is callable, which is the same as calling `run()`.
 *
 * All arguments are escaped using `escapeshellarg()`.
 *
 * Methods `run()`, `create()` and `__invoke()` take any amount of arguments.
 * If you have an array of arguments, unpack it first: `run(...$args)`
 *
 * You can use `OS::hasBinary` to check if the command is available before you run it.
 *
 * @see \nochso\Omni\OS::hasBinary
 */
class Exec
{
    /**
     * @var string[]
     */
    private $prefixes;
    /**
     * @var string[]
     */
    private $output;
    /**
     * @var int
     */
    private $status;
    /**
     * @var string
     */
    private $lastCommand;

    /**
     * Create a new callable `Exec` object.
     *
     * @param string[] $prefixes,... Optional arguments will always be added to the beginning of the command.
     *
     * @return \nochso\Omni\Exec
     */
    public static function create(...$prefixes)
    {
        $exec = new self();
        $exec->prefixes = $prefixes;
        return $exec;
    }

    /**
     * Run a command with auto-escaped arguments.
     *
     * @param string[] $arguments,... Optional arguments will be added after the prefixes.
     *
     * @return $this
     */
    public function run(...$arguments)
    {
        $this->lastCommand = $this->getCommand(...$arguments);
        exec($this->lastCommand, $output, $status);
        $this->output = $output;
        $this->status = $status;
        return $this;
    }

    /**
     * getCommand returns the string to be used by `\exec()`.
     *
     * @param string[] $arguments,...
     *
     * @return string
     */
    public function getCommand(...$arguments)
    {
        $command = [];
        foreach (array_merge($this->prefixes, $arguments) as $argument) {
            $command[] = escapeshellarg($argument);
        }
        $commandString = implode(' ', $command);
        return $commandString;
    }

    /**
     * getLastCommand returns the string last used by a previous call to `run()`.
     *
     * @return string|null
     */
    public function getLastCommand()
    {
        return $this->lastCommand;
    }

    /**
     * getOutput of last execution.
     *
     * @return string[]
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * getStatus code of last execution.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * __invoke allows using this object as a callable by calling `run()`.
     *
     * e.g. `$runner('argument');`
     *
     * @param array $arguments,...
     *
     * @return \nochso\Omni\Exec
     */
    public function __invoke(...$arguments)
    {
        return $this->run(...$arguments);
    }
}
