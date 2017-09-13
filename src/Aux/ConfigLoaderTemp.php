<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Aux;

use CaT\Ilse\Config;
use CaT\Ilse\Aux\ConfigMerger;
use CaT\Ilse\Aux\YamlConfigParser;
use CaT\Ilse\Aux\Git\GitFactory;
use CaT\Ilse\Aux\TaskLogger;

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

	/**
	 * @var	Filesystem
	 */
	private $filesystem;

	/**
	 * @var	callable
	 */
	private $get_config_loader;

	/**
	 * @var	ConfigRepoLoader
	 */
	private $config_repo_loader = null;

	public function __construct(ConfigMerger $merger, ConfigParser $parser, Filesystem $filesystem, callable $get_config_repo_loader) {
		$this->merger = $merger;
		$this->parser = $parser;
		$this->filesystem = $filesystem;
		$this->get_config_repo_loader = $get_config_repo_loader;
	}

	/**
	 * @inheritdoc
	 */
	public function loadConfigToDic($dic, array $configs) {
		if ($dic->raw("config.ilias") instanceof Config\General) {
			throw new \LogicException("config.ilias already initialized.");
		}

		$paths = [];
		$get_config_repo_loader = $this->get_config_repo_loader;
		$this->config_repo_loader = $get_config_repo_loader();
		if (!($this->config_repo_loader instanceof ConfigRepoLoader)) {
			throw new \RuntimeException("Expected ConfigRepoLoader, got ".get_class($this->config_repo_loader));
		}
		foreach ($configs as $config) {
			if (!$this->filesystem->exists($config)) {
				$paths[] = $this->config_repo_loader->getConfigPath($config);
			}
			else {
				$paths[] = $config;
			}
		}

		$merged = $this->merger->mergeConfigs($paths);
		$dic["config.ilias"] = $this->parser->read_config($merged, Config\General::class);
		return $dic;
	}
}
