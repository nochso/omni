<?php
namespace nochso\Omni;

/**
 * Exec creates objects that help manage `\exec()` calls.
 *
 * The returned object itself is callable, which is the same as calling `run()`.
 *
 * Arguments are automatically escaped if needed.
 *
 * Methods `run()`, `create()` and `__invoke()` take any amount of arguments.
 * If you have an array of arguments, unpack it first: `run(...$args)`
 *
 * @see \nochso\Omni\OS::hasBinary Check if the binary/command is available before you run it.
 */
class Exec {
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
	public static function create(...$prefixes) {
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
	public function run(...$arguments) {
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
	public function getCommand(...$arguments) {
		$command = [];
		$allArguments = array_merge($this->prefixes, $arguments);
		if (count($allArguments) === 0) {
			$allArguments[] = '';
		}
		foreach ($allArguments as $argument) {
			$command[] = $this->escapeArgument($argument);
		}
		$commandString = implode(' ', $command);
		return $commandString;
	}

	/**
	 * getLastCommand returns the string last used by a previous call to `run()`.
	 *
	 * @return string|null
	 */
	public function getLastCommand() {
		return $this->lastCommand;
	}

	/**
	 * getOutput of last execution.
	 *
	 * @return string[]
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * getStatus code of last execution.
	 *
	 * @return int
	 */
	public function getStatus() {
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
	public function __invoke(...$arguments) {
		return $this->run(...$arguments);
	}

	/**
	 * @param string $argument
	 *
	 * @return string
	 */
	private function escapeArgument($argument) {
		// Always escape an empty argument so it doesn't get lost.
		if ($argument === '') {
			return escapeshellarg($argument);
		}
		if (!OS::isWindows()) {
			return $this->escapeLinuxArgument($argument);
		}
		return $this->escapeWindowsArgument($argument);
	}

	/**
	 * @param string $argument
	 *
	 * @return string
	 */
	private function escapeLinuxArgument($argument) {
		$escapedArgument = escapeshellarg($argument);
		// Is escaping really needed?
		if ($argument !== '--' && mb_substr($escapedArgument, 1, -1) === $argument && preg_match('/^[a-z0-9-]+$/i', $argument) === 1) {
			return $argument;
		}
		return $escapedArgument;
	}

	/**
	 * @param string $argument
	 *
	 * @return string
	 *
	 * @link https://blogs.msdn.microsoft.com/twistylittlepassagesallalike/2011/04/23/everyone-quotes-command-line-arguments-the-wrong-way/
	 */
	private function escapeWindowsArgument($argument) {
		// Check if there's anything to escape
		if (strpbrk($argument, " \t\n\v\"\\") === false) {
			return $argument;
		}
		$escapedArgument = '"';
		$strlen = mb_strlen($argument);
		for ($i = 0; $i < $strlen; $i++) {
			$backslashes = 0;
			while ($i < $strlen && mb_substr($argument, $i, 1) === '\\') {
				$i++;
				$backslashes++;
			}
			if ($i === $strlen) {
				// Escape all backslashes, but let the terminating double quote be interpreted as a meta character
				$escapedArgument .= str_repeat('\\', $backslashes * 2);
			} elseif (mb_substr($argument, $i, 1) === '"') {
				// Escape all backslashes and the following quotation mark
				$escapedArgument .= str_repeat('\\', $backslashes * 2 + 1);
				$escapedArgument .= '"';
			} else {
				$escapedArgument .= str_repeat('\\', $backslashes);
				$escapedArgument .= mb_substr($argument, $i, 1);
			}
		}
		$escapedArgument .= '"';
		return $escapedArgument;
	}
}
