<?php
namespace nochso\Omni;

/**
 * OS.
 */
class OS {
	/**
	 * isWindows returns true if the current OS is Windows.
	 *
	 * @param string $phpOs Optional, defaults to the PHP_OS constant.
	 *
	 * @return bool
	 */
	public static function isWindows($phpOs = PHP_OS) {
		return strtolower(substr($phpOs, 0, 3)) === 'win';
	}

	/**
	 * hasBinary returns true if the binary is available in any of the PATHs.
	 *
	 * @param string $binaryName
	 *
	 * @return bool
	 */
	public static function hasBinary($binaryName) {
		$exec = Exec::create();
		if (self::isWindows()) {
			// 'where.exe' uses 0 for success, 1 for failure to find
			$exec->run('where.exe', '/q', $binaryName);
			return $exec->getStatus() === 0;
		}
		// 'which' uses 0 for success, 1 for failure to find
		$exec->run('which', $binaryName);
		if ($exec->getStatus() === 0) {
			return true;
		}
		// 'whereis' does not use status codes. Check for a matching line instead.
		$exec->run('whereis', '-b', $binaryName);
		foreach ($exec->getOutput() as $line) {
			if (preg_match('/^' . preg_quote($binaryName) . ': .*$/', $line) === 1) {
				return true;
			}
		}
		return false;
	}
}
