<?php
namespace nochso\Omni\PhpCsFixer;

use Symfony\Component\Finder\Finder;

/**
 * DefaultFinder respects ignore files for Git, Mercurial and Darcs.
 */
class DefaultFinder extends Finder
{
    /**
     * @param array|string $dirs
     *
     * @return \nochso\Omni\PhpCsFixer\DefaultFinder
     */
    public static function createIn($dirs)
    {
        $finder = new self();
        $finder->in($dirs);
        return $finder;
    }

    public function __construct()
    {
        parent::__construct();

        $excludes = [];
        foreach ($this->getVcsIgnoreFiles() as $ignore) {
            $excludes = array_merge($excludes, $this->readExcludeLines($ignore));
        }
        foreach ($this->getNames() as $name) {
            $this->name($name);
        }
        $this->files()
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->exclude($excludes)
        ;
    }

    /**
     * @return array
     */
    protected function getNames()
    {
        return [
            '*.php',
            '*.twig',
            '#*.ya?ml#i',
            '*.xml',
        ];
    }

    /**
     * @return array
     */
    protected function getVcsIgnoreFiles()
    {
        return [
            '.gitignore',
            '.hgignore',
            '_darcs/prefs/boring',
        ];
    }

    /**
     * @param $ignoreFile
     *
     * @return array
     */
    private function readExcludeLines($ignoreFile)
    {
        if (!is_file($ignoreFile)) {
            return [];
        }
        $lines = file($ignoreFile);
        if ($lines === false) {
            // Not sure how to test, so ignore it instead of inlining it above
            return []; // @codeCoverageIgnore
        }
        return array_map(function ($line) {
            return rtrim($line, "\r\n\\/");
        }, $lines);
    }
}
