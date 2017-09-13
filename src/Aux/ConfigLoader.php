<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

use Pimple\Container;

interface ConfigLoader {
	/**
	 * Load the config.
	 *
	 * @param	array|Container		$dic
	 * @param	string[]			$configs
	 * @return	array|Container
	 */
	public function loadConfigToDic($dic, array $configs);
}
