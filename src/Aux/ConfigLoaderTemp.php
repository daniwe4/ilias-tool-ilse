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
	 * @var	string
	 */
	private $config_repo_base_folder;

	/**
	 * @var	string[]
	 */
	private $config_repos;

	/**
	 * @var	ConfigMerger
	 */
	private $merger;

	/**
	 * @var	ConfigParser
	 */
	private $parser;

	/**
	 * @var	TaskLogger
	 */
	private $task_logger;

	/**
	 * @var	GitFactory
	 */
	private $git_factory;

	public function __construct($config_repo_base_folder, array $config_repos, ConfigMerger $merger, ConfigParser $parser, TaskLogger $task_logger, GitFactory $git_factory) {
		assert('is_string($config_repo_base_folder)');
		$this->config_repo_base_folder = $config_repo_base_folder;
		$this->config_repos = $config_repos;
		$this->merger = $merger;
		$this->parser = $parser;
		$this->task_logger = $task_logger;
		$this->git_factory = $git_factory;
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

	/**
	 * Refreshes the config repos in the ilse-home folder.
	 *
	 * @return void
	 */
	public function updateConfigRepos() {
		$this->task_logger->always("Updating config repos", function() {
			foreach ($this->config_repos as $repo) {
				$folder = $this->config_repo_base_folder."/".$this->getUniqueDirName($repo);
				if (!$this->filesystem->exists($folder)) {
					$this->initializeConfigRepo($folder, $url);
				}
				else {
					$this->refreshConfigRepo($folder, $url);
				}
			}
		});
	}

	/**
	 * Refreshes one config repo in the ilse-home folder.
	 *
	 * @var		string	$folder
	 * @var		string	$url
	 * @return	void
	 */
	public function refreshConfigRepo($folder, $url) {
		$this->task_logger->always("Refreshing repo '$url' at '$folder'", function() use ($folder, $url) {
			$git = $this->git_factory->getRepo($folder, $url, "ilse-configs", true);
			$git->pull("master");
		});
	}

	/**
	 * Refreshes one config repo in the ilse-home folder.
	 *
	 * @var		string	$url
	 * @return	void
	 */
	public function initializeConfigRepo($folder, $url) {
		$this->task_logger->always("Refreshing repo '$url' at '$folder'", function() use ($folder, $url) {
			$git = $this->git_factory->getRepo($folder, $url, "ilse-configs", true);
			$git->Clone();
		});
	}

	/**
	 * Get a unique name from md5 hash of url.
	 *
	 * This is required to safely store different config repos in the ilse home
	 * folder. These could well have the same name, which would crash...
	 *
	 * @param string 		$url
	 *
	 * @return string
	 */
	protected function getUniqueDirName($url)
	{
		assert('is_string($url)');
		return md5($url);
	}
}
