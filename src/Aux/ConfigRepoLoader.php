<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

use Pimple\Container;

interface ConfigRepoLoader {
	/**
	 * Refreshes the config repos in the ilse-home folder.
	 *
	 * @return void
	 */
	public function updateConfigRepos();

	/**
	 * Get a path to a config from the config repos.
	 *
	 * @param	string	$name
	 * @return	string
	 */
	public function getConfigPath($name);
}
