<?php
namespace nochso\Omni;

/**
 * @todo Implement `convert`
 */
final class EOL
{
    const EOL_CR = "\r";
    const EOL_LF = "\n";
    const EOL_CR_LF = "\r\n";

    /**
     * Note that EOL::detect depends on this order.
     *
     * @var array
     */
    private static $eols = [
        "\r\n" => ['CR+LF', 'Windows, TOPS-10, RT-11, CP/M, MP/M, DOS, Atari TOS, OS/2, Symbian OS, Palm OS'],
        "\n" => ['LF', 'Multics, Unix, Unix-like, BeOS, Amiga, RISC OS'],
        "\r" => ['CR', 'Commodore 8-bit, BBC Acorn, TRS-80, Apple II, Mac OS <=v9, OS-9'],
    ];

    private $eol;
    private $name;
    private $description;

    /**
     * @param string $eol See the EOL_* class constants.
     */
    public function __construct($eol)
    {
        $this->eol = $eol;
        $initEol = self::$eols[$eol];
        $this->name = $initEol[0];
        $this->description = $initEol[1];
    }

    public function __toString()
    {
        return $this->eol;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Apply this EOL style to a string.
     *
     * @param string $input Input to be converted.
     *
     * @return string
     */
    public function apply($input)
    {
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
    public static function detect($input)
    {
        $maxEol = self::getFrequentEOL($input);
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
    public static function detectDefault($input, $default = self::EOL_LF)
    {
        try {
            $eol = self::detect($input);
        } catch (\RuntimeException $e) {
            $eol = new self($default);
        }
        return $eol;
    }

    /**
     * @param string $input
     *
     * @return null|string
     */
    private static function getFrequentEOL($input)
    {
        $maxCount = 0;
        $maxEol = null;
        foreach (array_keys(self::$eols) as $eol) {
            $newCount = substr_count($input, $eol);
            if ($newCount > $maxCount) {
                $maxCount = $newCount;
                $maxEol = $eol;
            }
        }
        return $maxEol;
    }
}
