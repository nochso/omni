<?php
namespace nochso\Omni;

final class Multiline
{
    /**
     * @var \nochso\Omni\EOL
     */
    private $eol;

    /**
     * @var array
     */
    private $lines;

    /**
     * @param string $input
     * @param string $defaultEol
     *
     * @return \nochso\Omni\Multiline
     */
    public static function create($input, $defaultEol = EOL::EOL_LF)
    {
        $eol = EOL::detectDefault($input, $defaultEol);
        $lines = explode($eol, $input);
        return new self($lines, $eol);
    }

    /**
     * @param array $lines
     * @param       $eol
     */
    public function __construct(array $lines, $eol)
    {
        $this->lines = $lines;
        $this->setEol($eol);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode((string)$this->eol, $this->lines);
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @return \nochso\Omni\EOL
     */
    public function getEol()
    {
        return $this->eol;
    }

    public function getMaxLength()
    {
        $length = 0;
        foreach ($this->lines as $line) {
            $length = max($length, strlen($line));
        }
        return $length;
    }

    /**
     * @param \nochso\Omni\EOL|string $eol
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
     * Apply a callable to every line.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function apply(callable $callable)
    {
        $this->lines = array_map($callable, $this->lines);
        return $this;
    }

    /**
     * prefixMultiline strings with the same prefix for every line.
     *
     * End-of-line is detected by EOL::detect.
     *
     * @param string $prefix
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
     * Pad multiline strings using `str_pad`.
     *
     * End-of-line is detected by EOL::detect
     *
     * @param int $length
     * @param int $padding     Optional argument pad_type can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH.
     *                         Defaults to STR_PAD_RIGHT.
     * @param int $paddingType
     *
     * @return string
     */
    public function pad($length = null, $padding = ' ', $paddingType = STR_PAD_RIGHT)
    {
        if ($length === null) {
            $length = $this->getMaxLength();
        }
        $padder = function ($line) use ($length, $padding, $paddingType) {
            return str_pad($line, $length, $padding, $paddingType);
        };
        return $this->apply($padder);
    }
}
