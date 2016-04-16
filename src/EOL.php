<?php
namespace nochso\Omni;

/**
 * EOL detects, converts and returns information about line-endings.
 */
final class EOL {
	/**
	 * Carriage return: Mac OS <=v9, OS-9, Apple II, Commodore 8-bit, BBC Acorn, TRS-80.
	 */
	const EOL_CR = "\r";
	/**
	 * Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS.
	 */
	const EOL_LF = "\n";
	/**
	 * Carriage return/Line feed: Windows, TOPS-10, RT-11, CP/M, MP/M, DOS, Atari TOS, OS/2, Symbian OS, Palm OS.
	 */
	const EOL_CR_LF = "\r\n";

	/**
	 * Note that EOL::detect depends on this order.
	 *
	 * @var array
	 */
	private static $eols = [
		"\r\n" => [
			'CR+LF',
			'Carriage return/line feed: Windows, TOPS-10, RT-11, CP/M, MP/M, DOS, Atari TOS, OS/2, Symbian OS, Palm OS',
		],
		"\n" => ['LF', 'Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS'],
		"\r" => [
			'CR',
			'Carriage return: Mac OS <=v9, OS-9, Apple II, Commodore 8-bit, BBC Acorn, TRS-80',
		],
	];
	private $eol;
	private $name;
	private $description;

	/**
	 * @param string $eol Line ending. See the EOL_* class constants.
	 */
	public function __construct($eol) {
		$this->eol = $eol;
		if (!isset(self::$eols[$eol])) {
			throw new \DomainException(sprintf('Unknown line ending: %s', Strings::escapeControlChars($eol)));
		}
		$initEol = self::$eols[$eol];
		$this->name = $initEol[0];
		$this->description = $initEol[1];
	}

	/**
	 * __toString casts to/returns the raw line ending string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->eol;
	}

	/**
	 * getName of line ending, e.g. `LF`.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * getDescription of line ending, e.g. `Line feed: Unix, Unix-like, Multics, BeOS, Amiga, RISC OS`.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Apply this EOL style to a string.
	 *
	 * @param string $input Input to be converted.
	 *
	 * @return string
	 */
	public function apply($input) {
		return (string) Multiline::create($input)->setEol($this);
	}

	/**
	 * Detect the EOL style of a string and return an EOL representation.
	 *
	 * @param string $input Input string to be analyzed.
	 *
	 * @throws \RuntimeException Thrown on failure to detect any line ending.
	 *
	 * @return \nochso\Omni\EOL
	 */
	public static function detect($input) {
		$maxEol = Strings::getMostFrequentNeedle($input, array_keys(self::$eols));
		$missingEOL = $maxEol === null;
		if ($missingEOL) {
			throw new \RuntimeException('Could not detect end-of-line. There are no EOL chars in the input string.');
		}
		return new self($maxEol);
	}

	/**
	 * DetectDefault falls back to a default EOL style on failure.
	 *
	 * @param string $input   Input string to be analyzed.
	 * @param string $default Optional, defaults to "\n". The default line ending to use when $strict is false. See the `EOL::EOL_*` constants.
	 *
	 * @return \nochso\Omni\EOL
	 */
	public static function detectDefault($input, $default = self::EOL_LF) {
		try {
			$eol = self::detect($input);
		} catch (\RuntimeException $e) {
			$eol = new self($default);
		}
		return $eol;
	}
}
