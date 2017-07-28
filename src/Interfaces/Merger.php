<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Interfaces;

interface Merger
{
	/**
	 * Merge config files.
	 * Config with higher key is leading
	 *
	 * @param [sting[]] 	$configs
	 *
	 * @return string[]
	 */
	public function mergeConfigs(array $configs);
}