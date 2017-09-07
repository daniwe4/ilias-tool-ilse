<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\App\Command;

use CaT\Ilse\Aux\ConfigMerger;
use CaT\Ilse\Aux\YamlConfigParser;
use CaT\Ilse\Config;

/**
 * TODO: this is just a temporary solution and deserves some test and
 * refactoring.
 */
class ConfigLoaderTemp implements ConfigLoader { 
	/**
	 * @var	ConfigMerger|null
	 */
	private $config_merger = null;

	public functi

	private function getConfigMerger() {
		if ($this->config_merger === null) {
			$this->config_merger = new ConfigMerger();
		}
		return $this->config_merger;
	}

	/**
	 * @var	YamlConfigParser|null
	 */
	private $config_parser = null;

	private function getConfigParser() { 
		if ($this->config_parser === null) {
			$this->config_parser = new YamlParser();
		}
		return $this->config_parser;
	}

	/**
	 * Load the config based on some directories.
	 *
	 * @param	array		$dic
	 * @param	string[]	$paths
	 * @return	Config\General
	 */
	public function loadConfigToDic($dic, array $paths) {
		if ($dic["config.ilias"] instanceof Config\General) {
			throw new \LogicException("config.ilias already initialized.");
		}
		$merged = $this->config_merger->mergeConfigs($paths);
		$dic["config.ilias"] = $this->config_parser->read_config($merged, Config\General::class);
	}
}
