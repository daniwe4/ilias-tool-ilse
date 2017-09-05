<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

/**
 * Some filesystem functions.
 *
 * TODO: make this expand ~ to home
 */
class FilesystemImpl implements Filesystem {
	/**
	 * @inheritdoc
	 */
	public function remove($path) {
		assert('is_string($path)');

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

	/**
	 * @inheritdoc
	 */
	public function exists($path) {
		assert('is_string($path)');
		return file_exists($path);
	}

	/**
	 * @inheritdoc
	 */
	public function makeDirectory($path) {
		assert('is_string($path)');
		mkdir($path, "755", true);
	}

	/**
	 * @inheritdoc
	 */
	public function homeDirectory() {
		return getenv("HOME");
	}

	/**
	 * @inheritdoc
	 */
	public function read($path) {
		assert('is_string($path)');
		return file_get_contents($path);
	}

	/**
	 * @inheritdoc
	 */
	public function write($path, $content) {
		assert('is_string($path)');
		assert('is_string($content)');
		file_put_contents($path, $content);
	}
}
