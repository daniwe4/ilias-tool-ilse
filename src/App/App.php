<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\App;

use CaT\Ilse\Action;
use CaT\Ilse\Aux;

use Pimple\Container;
use Symfony\Component\Console\Application;

/**
 * Do the main initializing
 */
class App extends Application
{
	const CONFIG_REPOS_DIR	= "ilias-configs";
	const ILSE_CONFIG		= "config.yaml";
	const ILSE_DIR			= ".ilse";
	const I_F_CONFIG			= "ilse_config.yaml";
	const I_R_BRANCH			= "master";

	public function __construct() {
		parent::__construct();
		$this->initCommands();
	}

	/**
	 * Initialize the dependency injection container.
	 *
	 * @return Container
	 */
	public function getDIC() {
		$container = new Container();

		// Actions

		$container["action.initAppFolder"] = function($c) {
			return new Action\InitAppFolder
						( self::ILSE_DIR
						, self::ILSE_CONFIG
						, $c["aux.filesystem"]
						, $c["aux.taskLogger"]
						);
		};
		$container["action.deleteILIAS"] = function($c) {
			$config = $container["config.ilias"];
			return new Action\DeleteILIAS
						( $config->database()
						, $config->server()
						, $config->client()
						, $config->log()
						, $c["aux.filesystem"]
						, $c["aux.taskLogger"]
						);
		};
		$container["action.installILIAS"] = function($c) {
			return new Action\InstallILIAS
						( $c["config.ilias"]
						, $c["setup.coreInstallerFactory"]
						, $c["aux.taskLogger"]
						);
		};

		// Configs

		$container["config.ilias"] = function($c) {
			throw new \RuntimeException("Expected command to initialized ILIAS config.");
		};
		$container["config.ilse"] = function($c) {
			throw new \RuntimeException("Don't know how to build");
		};

		// Auxiliary Services

		$container["aux.filesystem"] = function($c) {
			return new Aux\FilesystemImpl();
		};
		$container["aux.taskLogger"] = function($c) {
			throw new \RuntimeException("Expected command to initialize task logger.");
		};
		$container["aux.configLoader"] = function($c) {
			return new Aux\ConfigLoaderTemp($c["aux.configMerger"], $c["aux.configParser"]);
		};
		$container["aux.configMerger"] = function($c) {
			return new Aux\ConfigMerger();
		};
		$container["aux.configParser"] = function($c) {
			return new Aux\YamlConfigParser();
		};

		// Setup

		$container["setup.coreInstallerFactory"] = function($c) {
			return new CoreInstallerFactory();
		};

		return $container;
	}

	/**
	 * Initialize commands and add them to the app.
	 *
	 * @return	void
	 */
	protected function initCommands()
	{
		$dic = $this->getDIC();

//		$this->add(new Command\UpdateCommand($path, $merger, $checker, $git, $repos));
//		$this->add(new Command\DeleteCommand($path, $merger, $checker, $git, $repos));
//		$this->add(new Command\UpdatePluginsCommand($path, $merger, $checker, $git, $repos));
//		$this->add(new Command\ReinstallCommand($path, $merger, $checker, $git, $repos));
//		$this->add(new Command\InstallCommand($path, $merger, $checker, $git, $repos));
//		$this->add(new Command\ConfigCommand($path, $merger, $checker, $git, $repos));
		$this->add(new Command\ExampleConfigCommand($dic));
	}

	/**
	 * Initialize the config repo in ~/.ilias-installer/config
	 *
	 * @param string 				$path
	 * @param Git\Git 		$gw
	 * @param Interfaces\Parser 	$parser
	 * @param string 				$repos
	 * @param GitExecutor 			$ge
	 */
	protected function initConfigRepo($path, Git\Git $gw, Interfaces\Parser $parser, $repos, GitExecutor $ge)
	{
		$name = "";
		$path = $path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG;

		foreach ($repos as $repo)
		{
			$dir = $this->getUniqueDirName($path, $repo);
			if(!is_dir($dir))
			{
				mkdir($dir, 0755, true);
			}
			else
			{
				$name = basename($repo, '.git');
			}
			$ge->cloneGitTo($repo,
							self::I_R_BRANCH,
							$dir,
							$name
							);
		}
	}

	/**
	 * Read app config file
	 *
	 * @param string 				$path
	 * @param Interfaces\Parser 	$parser
	 *
	 * @return string
	 */
	protected function readAppConfigFile($path, $parser)
	{
		if(!is_file($path->getHomeDir() . "/" . self::I_F_CONFIG_REPOS))
		{
			throw new \Exception("File not found at " . self::I_F_CONFIG_REPOS);
		}

		return $parser->read($path->getHomeDir() . "/" . self::I_F_CONFIG_REPOS);
	}

	/**
	 * Get the config repos
	 *
	 * @param string 				$path
	 * @param Git\Git 		$gw
	 * @param Interfaces\Parser 	$parser
	 *
	 * @return string
	 */
	protected function getConfigRepos($path, Git\Git $gw, Interfaces\Parser $parser)
	{
		assert('is_string($path)');

		$result = array();
		foreach($this->readAppConfigFile($path, $parser)['repos'] as $repo)
		{
			if($gw->gitIsRemoteGitRepo($repo) === 0)
			{
				$result[] = $repo;
			}
		}
		return $result;
	}

	/**
	 * Get a name from md5 hash of path + url
	 *
	 * @param string 		$path
	 * @param string 		$url
	 *
	 * @return string
	 */
	protected function getUniqueDirName($path, $url)
	{
		assert('is_string($path)');
		assert('is_string($url)');

		$hash 	= md5($url);
		$dir 	= $path . "/" . $hash;

		return $dir;
	}

}
