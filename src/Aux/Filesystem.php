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
}
