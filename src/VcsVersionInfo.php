<?php
namespace nochso\Omni;

/**
 * VcsVersionInfo enriches an internal VersionInfo with the latest tag and current repository state.
 *
 * If the working directory is clean and at an exact tag, only the tag is returned:
 *
 *     1.0.0
 *
 * If dirty and at an exact tag, `-dirty` is appended:
 *
 *     1.0.0-dirty
 *
 * If there are no tags present, the revision id is returned:
 *
 *     4319e00
 *
 * If there have been commits since a tag:
 *
 *     0.3.1-14-gf602496-dirty
 *
 * Where `14` is the amount of commits since tag `0.3.1`.
 *
 * Internally a VersionInfo object is used. Note that this class does not extend from VersionInfo
 * as it uses different constructor parameters.
 */
final class VcsVersionInfo
{
    /**
     * @var \nochso\Omni\VersionInfo
     */
    private $version;
    /**
     * @var string
     */
    private $repositoryRoot;

    /**
     * @param string $name            Package or application name.
     * @param string $fallBackVersion Optional version to fall back on if no repository info was found.
     * @param string $repositoryRoot  Path the VCS repository root (e.g. folder that contains ".git", ".hg", etc.)
     * @param string $infoFormat      Optional format to use for `getInfo`. Defaults to `VersionInfo::INFO_FORMAT_DEFAULT`
     */
    public function __construct($name, $fallBackVersion = null, $repositoryRoot = '."', $infoFormat = VersionInfo::INFO_FORMAT_DEFAULT)
    {
        $this->repositoryRoot = $repositoryRoot;
        $tag = $this->extractTag();
        if ($tag === null) {
            $tag = $fallBackVersion;
        }
        $this->version = new VersionInfo($name, $tag, $infoFormat);
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->version->getInfo();
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version->getVersion();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->version->getName();
    }

    private function extractTag()
    {
        $tag = $this->readGit();
        if ($tag === null) {
            $tag = $this->readMercurial();
        }
        return $tag;
    }

    /**
     * @return null|string
     */
    private function readGit()
    {
        $gitDir = Path::combine($this->repositoryRoot, '.git');
        if (!is_dir($gitDir)) {
            return null;
        }
        $describe = $this->execEscaped(
            'git --git-dir=%s --work-tree=%s describe --tags --always --dirty',
            $gitDir,
            $this->repositoryRoot
        );
        return Dot::get($describe, 0);
    }

    /**
     * @return null|string
     */
    private function readMercurial()
    {
        $hgDir = Path::combine($this->repositoryRoot, '.hg');
        if (!is_dir($hgDir)) {
            return null;
        }

        // Removes everything but the tag if distance is zero.
        $log = $this->execEscaped(
            'hg --repository %s log -r . -T "{latesttag}{sub(\'^-0-m.*\', \'\', \'-{latesttagdistance}-m{node|short}\')}"',
            $this->repositoryRoot
        );

        $tag = Dot::get($log, 0);
        // Actual null if no lines were returned or `hg log` returned actual "null".
        // Either way, need to fall back to the revision id.
        if ($tag === null || $tag === 'null') {
            $id = $this->execEscaped('hg --repository %s id -i', $this->repositoryRoot);
            $tag = Dot::get($id, 0);
        }

        // Check if working directory is dirty
        $summary = $this->execEscaped(
            'hg --repository %s summary',
            $this->repositoryRoot
        );
        $isDirty = 0 === count(array_filter($summary, function ($line) {
                return preg_match('/^commit: .*\(clean\)$/', $line) === 1;
            }));
        if ($isDirty) {
            $tag .= '-dirty';
        }
        return $tag;
    }

    /**
     * execEscaped executes a command with automatically escaped parameters.
     *
     * @param string $cmdFormat
     * @param array ...$params
     *
     * @see sprintf()
     *
     * @return string[] Only lines with content after trimming are returned.
     */
    private function execEscaped($cmdFormat, ...$params)
    {
        $quotedParams = array_filter($params, 'escapeshellarg');
        $cmd = sprintf($cmdFormat, ...$quotedParams);
        exec($cmd, $out, $ret);
        return array_filter($out, 'strlen');
    }
}
