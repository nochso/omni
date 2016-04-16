<?php
namespace nochso\Omni;

/**
 * Multiline string class for working with lines of text.
 */
final class Multiline extends ArrayCollection {
	/**
	 * @var \nochso\Omni\EOL
	 */
	private $eol;

	/**
	 * Create a new Multiline object from a string.
	 *
	 * First the input string is split into lines by the detected end-of-line
	 * character. Afterwards any extra EOL chars will be trimmed.
	 *
	 * @see \nochso\Omni\EOL
	 *
	 * @param string $input      A string to split into a Multiline object
	 * @param string $defaultEol Default end-of-line type to split the input by. This is a fallback in case it could
	 *                           not be detected from the input string. Optional, defaults to `EOL::EOL_LF` i.e. "\n".
	 *                           See the `EOL::EOL_*` class constants.
	 *
	 * @return \nochso\Omni\Multiline
	 */
	public static function create($input, $defaultEol = \nochso\Omni\EOL::EOL_LF) {
		$eol = EOL::detectDefault($input, $defaultEol);
		$lines = explode($eol, $input);
		$multiline = new self($lines);
		// Remove left-over line feeds
		$multiline->apply(function ($line) {
				return trim($line, "\r\n");
			});
		$multiline->setEol($eol);
		return $multiline;
	}

	/**
	 * __toString returns a single string using the current EOL style.
	 *
	 * @return string
	 */
	public function __toString() {
		return implode((string) $this->eol, $this->list);
	}

	/**
	 * Get EOL style ending.
	 *
	 * @return \nochso\Omni\EOL
	 */
	public function getEol() {
		return $this->eol;
	}

	/**
	 * getMaxLength of all lines.
	 *
	 * @return int
	 */
	public function getMaxLength() {
		$length = 0;
		foreach ($this->list as $line) {
			$length = max($length, mb_strlen($line));
		}
		return $length;
	}

	/**
	 * Set EOL used by this Multiline string.
	 *
	 * @param \nochso\Omni\EOL|string $eol Either an `EOL` object or a string ("\r\n" or "\n")
	 *
	 * @return $this
	 */
	public function setEol($eol) {
		if (!$eol instanceof EOL) {
			$eol = new EOL($eol);
		}
		$this->eol = $eol;
		return $this;
	}

	/**
	 * Append text to a certain line.
	 *
	 * @param string   $text
	 * @param null|int $index Optional, defaults to the last line. Other
	 *
	 * @return $this
	 */
	public function append($text, $index = null) {
		if ($index === null) {
			$index = count($this) - 1;
		}
		$this->list[$index] .= $text;
		return $this;
	}

	/**
	 * Prefix all lines with a string.
	 *
	 * @param string $prefix The prefix to add to the start of the string.
	 *
	 * @return string
	 */
	public function prefix($prefix) {
		$prefixer = function ($line) use ($prefix) {
			return $prefix . $line;
		};
		return $this->apply($prefixer);
	}

	/**
	 * Pad all lines to the same length using `str_pad`.
	 *
	 * @param int    $length      If length is larger than the maximum line length, all lines will be padded up to the
	 *                            given length. If length is null, the maximum of all line lengths is used. Optional,
	 *                            defaults to null.
	 * @param string $padding     Optional, defaults to a space character. Can be more than one character. The padding
	 *                            may be truncated if the required number of padding characters can't be evenly
	 *                            divided.
	 * @param int    $paddingType Optional argument pad_type can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH.
	 *                            Defaults to STR_PAD_RIGHT.
	 *
	 * @return string
	 */
	public function pad($length = null, $padding = ' ', $paddingType = STR_PAD_RIGHT) {
		if ($length === null) {
			$length = $this->getMaxLength();
		}
		$padder = function ($line) use ($length, $padding, $paddingType) {
			return Strings::padMultibyte($line, $length, $padding, $paddingType);
		};
		return $this->apply($padder);
	}

	/**
	 * getLineIndexByCharacterPosition returns the line index containing a certain position.
	 *
	 * @param int $characterPosition Position of a character as if Multiline was a raw string.
	 *
	 * @return int|null The array index of the line containing the character position.
	 */
	public function getLineIndexByCharacterPosition($characterPosition) {
		$position = 0;
		foreach ($this->list as $key => $line) {
			$length = mb_strlen($line . $this->getEol());
			if ($characterPosition >= $position && $characterPosition <= $position + $length) {
				return $key;
			}
			$position += $length;
		}
		return null;
	}
}
