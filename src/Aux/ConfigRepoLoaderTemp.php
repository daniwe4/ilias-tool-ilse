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
class ConfigRepoLoaderTemp implements ConfigRepoLoader { 
	/**
	 * @var	string
	 */
	private $config_repo_base_folder;

	/**
	 * @var	string[]
	 */
	private $config_repos;

	/**
	 * @var	Filesystem
	 */
	private $filesystem;

	/**
	 * @var	TaskLogger
	 */
	private $task_logger;

	/**
	 * @var	GitFactory
	 */
	private $git_factory;

	public function __construct($config_repo_base_folder, array $config_repos, Filesystem $filesystem, TaskLogger $task_logger, GitFactory $git_factory) {
		assert('is_string($config_repo_base_folder)');
		$this->config_repo_base_folder = $config_repo_base_folder;
		$this->config_repos = $config_repos;
		$this->filesystem = $filesystem;
		$this->task_logger = $task_logger;
		$this->git_factory = $git_factory;
	}

	/**
	 * @inheritdoc
	 */
	public function updateConfigRepos() {
		foreach ($this->config_repos as $repo) {
			$this->updateOrRefresh($repo);
		}
	}

	protected function updateOrRefresh($repo) {
		assert('is_string($repo)');
		$folder = $this->getFolderNameFor($repo);
		if (!$this->filesystem->exists($folder)) {
			$this->initializeConfigRepo($folder, $repo);
		}
		else {
			$this->refreshConfigRepo($folder, $repo);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function getConfigPath($name) {
		foreach ($this->config_repos as $repo) {
			$this->updateOrRefresh($repo);
			$folder = $this->getFolderNameFor($repo);
			$path = $folder."/$name/ilse_config.yaml";
			if ($this->filesystem->exists($path)) {
				return $path;
			} 	
		}
		throw new \RuntimeException("Cannot find config '$name' in any config repo.");
	}

	/**
	 * Refreshes one config repo in the ilse-home folder.
	 *
	 * @var		string	$folder
	 * @var		string	$url
	 * @return	void
	 */
	public function refreshConfigRepo($folder, $url) {
		$this->task_logger->progressing("Refreshing repo '$url' at '$folder'", function() use ($folder, $url) {
			$git = $this->git_factory->getRepo($folder, $url, "ilse-configs", true);
			$git->gitPull("master");
		});
	}

	/**
	 * Refreshes one config repo in the ilse-home folder.
	 *
	 * @var		string	$url
	 * @return	void
	 */
	public function initializeConfigRepo($folder, $url) {
		$this->task_logger->progressing("Initializing repo '$url' at '$folder'", function() use ($folder, $url) {
			$git = $this->git_factory->getRepo($folder, $url, "ilse-configs", true);
			$git->gitClone();
		});
	}

	/**
	 * Get folder name for repo.
	 *
	 * @param	string	$url
	 * @return	string
	 */
	protected function getFolderNameFor($url) {
		return $this->config_repo_base_folder."/".$this->getUniqueDirName($url);
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
