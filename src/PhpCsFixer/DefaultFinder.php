<?php
namespace nochso\Omni\PhpCsFixer;

use nochso\Omni\Path;
use Symfony\Component\Finder\Finder;

/**
 * DefaultFinder respects ignore files for Git, Mercurial and Darcs.
 */
class DefaultFinder extends Finder {
	/**
	 * @param array|string $dirs
	 *
	 * @return \nochso\Omni\PhpCsFixer\DefaultFinder
	 */
	public static function createIn($dirs) {
		$finder = new self();
		if (!is_array($dirs)) {
			$dirs = [$dirs];
		}
		$finder->in($dirs);
		$excludes = [];
		foreach ($finder->getVcsIgnoreFiles($dirs) as $ignore) {
			$excludes = array_merge($excludes, $finder->readExcludeLines($ignore));
		}
		$finder->exclude($excludes);
		return $finder;
	}

	public function __construct() {
		parent::__construct();
		foreach ($this->getNames() as $name) {
			$this->name($name);
		}
		$this->files()->ignoreDotFiles(true)->ignoreVCS(true);
	}

	/**
	 * @return array
	 */
	protected function getNames() {
		return ['*.php', '*.twig', '#.*.ya?ml#i', '*.xml'];
	}

	/**
	 * @param array $dirs
	 *
	 * @return array
	 */
	protected function getVcsIgnoreFiles(array $dirs) {
		$files = ['.gitignore', '.hgignore', '_darcs/prefs/boring'];
		$filepaths = [];
		foreach ($dirs as $dir) {
			foreach ($files as $file) {
				$filepaths[] = Path::combine($dir, $file);
			}
		}
		return $filepaths;
	}

	/**
	 * @param $ignoreFile
	 *
	 * @return array
	 */
	private function readExcludeLines($ignoreFile) {
		if (!file_exists($ignoreFile)) {
			return [];
		}
		$lines = file($ignoreFile);
		if ($lines === false) {
			// Not sure how to test, so ignore it instead of inlining it above
			return [];
			// @codeCoverageIgnore

		}
		return array_map(function ($line) {
				return rtrim($line, "\r\n\\/");
			}, $lines);
	}
}
