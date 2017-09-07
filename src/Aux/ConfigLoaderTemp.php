<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

use CaT\Ilse\Config;
use CaT\Ilse\Aux\ConfigMerger;
use CaT\Ilse\Aux\YamlConfigParser;

/**
 * TODO: this is just a temporary solution and deserves some test and
 * refactoring.
 */
class ConfigLoaderTemp implements ConfigLoader { 
	/**
	 * @var	ConfigMerger
	 */
	private $merger;

	/**
	 * @var	ConfigParser
	 */
	private $parser;

	public function __construct(ConfigMerger $merger, ConfigParser $parser) {
		$this->merger = $merger;
		$this->parser = $parser;
	}

	/**
	 * @inheritdoc
	 */
	public function loadConfigToDic($dic, array $paths) {
		if ($dic->raw("config.ilias") instanceof Config\General) {
			throw new \LogicException("config.ilias already initialized.");
		}
		$merged = $this->merger->mergeConfigs($paths);
		$dic["config.ilias"] = $this->parser->read_config($merged, Config\General::class);
		return $dic;
	}
}
