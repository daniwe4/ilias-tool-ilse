<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Executer;

/**
 * Base class for all executers
 */
abstract class BaseExecuter
{
	/**
	 * Constructor of the BaseExecuter class
	 *
	 * @param $string 		$config_name
	 */
	public function _construct($config_name)
	{
		assert('is_strig($config_name)');

		if(!is_file($config_name)) {
			throw new Exception("No config file found. (Path: ".$config_name.")");
		}

		$yaml_string = file_get_contents($config_name);
		$parser = new \CaT\Ilse\YamlParser();
		$this->general_config = $parser->read_config($yaml_string, "\\CaT\\Ilse\\Config\\General");
	}

}