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
     *
     * @throws \RuntimeException When no fallback was given and tag could not be extracted from a VCS repo.
     */
    public function __construct($name, $fallBackVersion = null, $repositoryRoot = '.', $infoFormat = VersionInfo::INFO_FORMAT_DEFAULT)
    {
        $this->repositoryRoot = $repositoryRoot;
        $tag = $this->extractTag();
        if ($tag === null) {
            $tag = $fallBackVersion;
        }
        if ($tag === null) {
            throw new \RuntimeException('Unable to detect version from VCS repository and no fallback version was specified.');
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
        if (!is_dir($gitDir) || !OS::hasBinary('git')) {
            return null;
        }
        $git = Exec::create(
            'git',
            '--git-dir=' . $gitDir,
            '--work-tree=' . $this->repositoryRoot
        );
        $git->run('describe', '--tags', '--always', '--dirty');
        return Dot::get($git->getOutput(), 0);
    }

    /**
     * @return null|string
     */
    private function readMercurial()
    {
        $hgDir = Path::combine($this->repositoryRoot, '.hg');
        if (!is_dir($hgDir) || !OS::hasBinary('hg')) {
            return null;
        }

        $hg = Exec::create('hg', '--repository', $this->repositoryRoot);
        // Removes everything but the tag if distance is zero.
        $hg->run('log', '-r', '.', '--template', '{latesttag}{sub(\'^-0-m.*\', \'\', \'-{latesttagdistance}-m{node|short}\')}');
        $tag = Dot::get($hg->getOutput(), 0);
        // Actual null if no lines were returned or `hg log` returned actual "null".
        // Either way, need to fall back to the revision id.
        if ($tag === null || $tag === 'null' || Strings::startsWith($tag, 'null-')) {
            $hg->run('id', '-i');
            $tag = Dot::get($hg->getOutput(), 0);
            // Remove 'dirty' plus from revision id
            $tag = rtrim($tag, '+');
        }

        $summary = $hg->run('summary')->getOutput();
        $isDirty = 0 === count(array_filter($summary, function ($line) {
                return preg_match('/^commit: .*\(clean\)$/', $line) === 1;
            }));
        if ($isDirty) {
            $tag .= '-dirty';
        }
        return $tag;
    }
}
