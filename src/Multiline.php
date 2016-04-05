<?php
namespace nochso\Omni;

/**
 * Multiline string class for working with lines of text.
 */
final class Multiline extends ArrayCollection
{
    /**
     * @var \nochso\Omni\EOL
     */
    private $eol;

    /**
     * Create a new Multiline object from a string.
     *
     * Use the `EOL::EOL_*` class constants.
     *
     * @see \nochso\Omni\EOL
     *
     * @param string $input      A string to split into a Multiline object
     * @param string $defaultEol Default end-of-line type to use if it can not be detected from the input string.
     *                           Optional, defaults to `EOL::EOL_LF` i.e. "\n"
     *
     * @return \nochso\Omni\Multiline
     */
    public static function create($input, $defaultEol = \nochso\Omni\EOL::EOL_LF)
    {
        $eol = EOL::detectDefault($input, $defaultEol);
        $lines = explode($eol, $input);
        $multiline = new self($lines);
        $multiline->setEol($eol);
        return $multiline;
    }

    /**
     * __toString returns a single string using the current EOL style.
     *
     * @return string
     */
    public function __toString()
    {
        return implode((string) $this->eol, $this->list);
    }

    /**
     * Get EOL style ending.
     *
     * @return \nochso\Omni\EOL
     */
    public function getEol()
    {
        return $this->eol;
    }

    /**
     * getMaxLength of all lines.
     *
     * @return int
     */
    public function getMaxLength()
    {
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
    public function setEol($eol)
    {
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
    public function append($text, $index = null)
    {
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
    public function prefix($prefix)
    {
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
    public function pad($length = null, $padding = ' ', $paddingType = STR_PAD_RIGHT)
    {
        if ($length === null) {
            $length = $this->getMaxLength();
        }
        $padder = function ($line) use ($length, $padding, $paddingType) {
            return Strings::padMultibyte($line, $length, $padding, $paddingType);
        };
        return $this->apply($padder);
    }
}
