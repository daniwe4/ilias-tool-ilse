<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

/**
 * Some filesystem functions.
 */
interface Filesystem {
	/**
	 * Remove file or directory.
	 *
	 * @param	string	$path
	 * @return	void
	 */
	public function remove($path);

	/**
	 * Check if file or directory exists.
	 *
	 * @param	string	$path
	 * @return 	bool
	 */
	public function exists($path);

	/**
	 * Create a directory.
	 *
	 * @param	string	$path
	 * @return	void
	 */
	public function makeDirectory($path);

	/**
	 * Get the home directory of the current user.
	 *
	 * @return string
	 */
	public function homeDirectory();

	/**
	 * Read a file.
	 *
	 * @param	string	$path
	 * @return	string
	 */
	public function read($path);

	/**
	 * Write a file.
	 *
	 * @param	string	$path
	 * @param	string 	$content
	 * @return	void
	 */
	public function write($path, $content);
}
