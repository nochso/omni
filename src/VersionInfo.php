<?php
namespace nochso\Omni;

/**
 * VersionInfo consists of a package name and version.
 */
class VersionInfo
{
    /**
     * `Omni v0.1.0`.
     */
    const INFO_FORMAT_DEFAULT = '%s v%s';
    /**
     * `Omni 0.1.0`.
     */
    const INFO_FORMAT_SHORT = '%s %s';
    /**
     * `Omni (Version 0.1.0)`.
     */
    const INFO_FORMAT_LONG = '%s (Version %s)';

    /**
     * @var string
     */
    private $version;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $infoFormat;

    /**
     * @param string $name       Package or application name.
     * @param string $version    Version without a prefix.
     * @param string $infoFormat Optional format to use for `getInfo`. Defaults to `self::INFO_FORMAT_DEFAULT`
     */
    public function __construct($name, $version, $infoFormat = self::INFO_FORMAT_DEFAULT)
    {
        $this->name = $name;
        $this->version = $version;
        $this->infoFormat = $infoFormat;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return sprintf($this->infoFormat, $this->getName(), $this->getVersion());
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
