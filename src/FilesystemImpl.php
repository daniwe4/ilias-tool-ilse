<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;

/**
 * Some filesystem functions.
 */
class FilesystemImpl {
	/**
	 * Remove file or directory.
	 *
	 * @param	string	$path
	 * @return	void
	 */
	public function remove($path) {
		if (is_file($path)) {
			unlink($path);
		}
		else {
			assert('is_dir($path)');
			$files = array_diff(scandir($path), array('.','..'));
			foreach ($files as $file) {
				$this->remove($file);
			}
			rmdir($dir);
		}
	}
}
