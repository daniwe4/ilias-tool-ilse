<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

use CaT\Ilse\Config;
use Pimple\Container;

interface ConfigLoader {
	/**
	 * Load the config.
	 *
	 * @param	array|Container		$dic
	 * @param	string[]			$paths
	 * @return	Config\General
	 */
	public function loadConfigToDic($dic, array $paths);
}
