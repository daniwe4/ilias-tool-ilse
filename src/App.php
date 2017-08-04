<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;

use Symfony\Component\Console\Application;

/**
 * Do the main initializing
 */
class App extends Application
{
	const I_P_GLOBAL_CONFIG 	= ".ilse/ilias-configs";
	const I_F_CONFIG_REPOS 		= ".ilse/config_repos.yaml";
	const I_P_GLOBAL 			= ".ilse";
	const I_F_CONFIG			= "ilse_config.yaml";
	const I_R_BRANCH			= "master";
	const I_D_WEB_DIR			= "data";

	public function __construct(Interfaces\CommonPathes $path,
								Interfaces\Merger $merger,
								Interfaces\RequirementChecker $checker,
								Interfaces\Git $git,
								Interfaces\Parser $parser,
								GitWrapper\Git $gw)
	{
		parent::__construct();

		$ge 	= new GitExecuter();
		$repos 	= $this->getConfigRepos($path, $gw, $parser);

		$this->initAppFolder($path);
		$this->initConfigRepo($ge, $repos, $path, $gw, $parser);
		$this->initCommands($path, $merger, $checker, $git, $repos);

	}

	/**
	 * Initialize all commands, and add them to the app
	 *
	 * @param Interfaces\CommonPathes 			$path
	 * @param Interfaces\Merger 				$merger
	 * @param Interfaces\RequirementChecker 	$checker
	 * @param Interfaces\Git 					$git
	 * @param string[] 							$repos
	 */
	protected function initCommands(Interfaces\CommonPathes $path,
									Interfaces\Merger $merger,
									Interfaces\RequirementChecker $checker,
									Interfaces\Git $git,
									array $repos)
	{
		$this->add(new Command\UpdateCommand($path, $merger, $checker, $git, $repos));
		$this->add(new Command\DeleteCommand($path, $merger, $checker, $git, $repos));
		$this->add(new Command\UpdatePluginsCommand($path, $merger, $checker, $git, $repos));
		$this->add(new Command\ReinstallCommand($path, $merger, $checker, $git, $repos));
		$this->add(new Command\InstallCommand($path, $merger, $checker, $git, $repos));
		$this->add(new Command\ConfigCommand($path, $merger, $checker, $git, $repos));
	}

	/**
	 * Checks whether the app folder exists otherwise create one
	 *
	 * @param string 		$path
	 */
	protected function initAppFolder($path)
	{
		if(!is_dir($path->getHomeDir() . "/" . self::I_P_GLOBAL))
		{
			mkdir($path->getHomeDir() . "/" . self::I_P_GLOBAL, 0755);
		}
	}

	/**
	 * Initialize the config repo in ~/.ilias-installer/config
	 *
	 * @param string 				$path
	 * @param GitWrapper\Git 		$gw
	 * @param Interfaces\Parser 	$parser
	 */
	protected function initConfigRepo($ge, $repos, $path, $gw, $parser)
	{
		$path = $path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG;

		if(!is_dir($path))
		{
			foreach ($repos as $repo)
			{
				$clone_path = $this->createUniqueDir($path, $repo);
				$ge->cloneGitTo($repo,
								self::I_R_BRANCH,
								$clone_path
								);
			}
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
	 * @param GitWrapper\Git 		$gw
	 * @param Interfaces\Parser 	$parser
	 *
	 * @return string
	 */
	protected function getConfigRepos($path, $gw, $parser)
	{
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
	 * Create a directory named with md5 hash of url
	 *
	 * @param string 		$path
	 * @param string 		$url
	 *
	 * @return string
	 */
	protected function createUniqueDir($path, $url)
	{
		assert('is_string($path)');
		assert('is_string($url)');

		$hash 	= md5($url);
		$dir 	= $path . "/" . $hash;

		mkdir($dir, 0755, true);
		return $dir;
	}

}