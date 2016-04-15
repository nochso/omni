<?php
namespace nochso\Omni\Format;

use nochso\Omni\Numeric;

/**
 * Duration formats seconds or DateInterval objects as human readable strings.
 *
 * e.g.
 *
 * ```php
 * $df = Duration::create();
 * $df->format(119);                        // '1m 59s'
 * $df->format(new \DateInterval('P1Y5D')); // '1y 5d'
 * ```
 */
class Duration
{
    const SECOND = 1;
    const MINUTE = 60;
    const HOUR = self::MINUTE * 60;
    const DAY = self::HOUR * 24;
    const WEEK = self::DAY * 7;
    const MONTH = self::DAY * 30;
    const YEAR = self::DAY * 365;

    /**
     * 1y 2m 3d 4h 5m 6s.
     */
    const FORMAT_SHORT = 0;
    /**
     * 1 year 2 months 3 days 4 hours 5 minutes 6 seconds.
     */
    const FORMAT_LONG = 1;

    private static $defaultFormats = [
        self::FORMAT_SHORT => [
            self::YEAR => 'y',
            self::MONTH => 'mo',
            self::WEEK => 'w',
            self::DAY => 'd',
            self::HOUR => 'h',
            self::MINUTE => 'm',
            self::SECOND => 's',
        ],
        self::FORMAT_LONG => [
            self::YEAR => ' year(s)',
            self::MONTH => ' month(s)',
            self::WEEK => ' week(s)',
            self::DAY => ' day(s)',
            self::HOUR => ' hour(s)',
            self::MINUTE => ' minute(s)',
            self::SECOND => ' second(s)',
        ],
    ];

    /**
     * @var int|string
     */
    private $format = self::FORMAT_SHORT;
    /**
     * @var array
     */
    private $formats;
    /**
     * @var int
     */
    private $limit = 0;

    /**
     * @param int $format
     */
    public function __construct($format = self::FORMAT_SHORT)
    {
        $this->formats = self::$defaultFormats;
        $this->setFormat($format);
    }

    /**
     * Create a new Duration.
     *
     * @param int $format
     *
     * @return \nochso\Omni\Format\Duration
     */
    public static function create($format = self::FORMAT_SHORT)
    {
        return new self($format);
    }

    /**
     * addFormat to the existing defaults and set it as the current format.
     *
     * e.g.
     *
     * ```php
     * $format = Duration::FORMAT_LONG => [
     *     Duration::YEAR => ' year(s)',
     *     Duration::MONTH => ' month(s)',
     *     Duration::WEEK => ' week(s)',
     *     Duration::DAY => ' day(s)',
     *     Duration::HOUR => ' hour(s)',
     *     Duration::MINUTE => ' minute(s)',
     *     Duration::SECOND => ' second(s)',
     * ];
     * $df->addFormat('my custom period format', $format);
     * ```
     *
     * @param string   $name
     * @param string[] $periodFormats
     *
     * @return $this
     */
    public function addFormat($name, array $periodFormats)
    {
        $this->formats[$name] = $periodFormats;
        $this->setFormat($name);
        return $this;
    }

    /**
     * setFormat to use by its custom name or one of the default Duration constants.
     *
     * @param string $name One of the `Duration::FORMAT_*` constants or a name of a format added via `addFormat()`
     *
     * @return $this
     */
    public function setFormat($name)
    {
        if (!isset($this->formats[$name])) {
            throw new \InvalidArgumentException(sprintf("Duration format named '%s' does not exist.", $name));
        }
        $this->format = $name;
        return $this;
    }

    /**
     * limitPeriods limits the amount of significant periods (years, months, etc.) to keep.
     *
     * Significant periods are periods with non-zero values.
     *
     * @param int $limit 0 for keeping all significant periods or any positive integer.
     *
     * @return $this
     */
    public function limitPeriods($limit)
    {
        $this->limit = Numeric::ensureInteger($limit);
        return $this;
    }

    /**
     * Format an amount of seconds or a `DateInterval` object.
     *
     * @param int|\DateInterval $duration
     *
     * @return string A formatted duration for human consumption.
     */
    public function format($duration)
    {
        return $this->formatPeriods($duration, $this->formats[$this->format]);
    }

    /**
     * @param int|\DateInterval $duration
     * @param array             $steps
     *
     * @return string
     */
    private function formatPeriods($duration, $steps)
    {
        $seconds = $this->ensureSeconds($duration);
        $parts = [];
        foreach ($steps as $minValue => $suffix) {
            if ($seconds >= $minValue) {
                $stepValue = floor($seconds / $minValue);
                if ($stepValue > 0) {
                    $suffix = Quantity::format($suffix, $stepValue);
                    $parts[] = $stepValue . $suffix;
                    $seconds -= $stepValue * $minValue;
                }
            }
        }
        if (count($parts) === 0) {
            $parts[] = $seconds . Quantity::format($steps[self::SECOND], $seconds);
        }
        if ($this->limit > 0) {
            $parts = array_slice($parts, 0, $this->limit);
        }
        return implode(' ', $parts);
    }

    /**
     * @param int|\DateInterval $duration
     *
     * @return int
     */
    private function ensureSeconds($duration)
    {
        if ($duration instanceof \DateInterval) {
            $d1 = new \DateTime('@0');
            return $d1->add($duration)->getTimestamp();
        }
        return Numeric::ensureInteger($duration);
    }
}
