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
	const I_F_CONFIG_REPOS 		= ".ilse/configrepos.yaml";
	const I_P_GLOBAL 			= ".ilse";
	const I_F_CONFIG			= "ilse_config.yaml";
	const I_R_CONFIG			= "https://github.com/conceptsandtraining/ilias-configs.git";
	const I_R_BRANCH			= "master";
	const I_D_WEB_DIR			= "data";

	/**
	 * @var string
	 */
	protected $repos;

	public function __construct(Interfaces\CommonPathes $path,
								Interfaces\Merger $merger,
								Interfaces\RequirementChecker $checker,
								Interfaces\Git $git,
								Interfaces\Parser $parser,
								GitWrapper\Git $gw)
	{
		parent::__construct();

		$this->initAppFolder($path);
		$this->initConfigRepo($path, $gw, $parser);
		$this->initCommands($path, $merger, $checker, $git);
	}

	/**
	 * Initialize all commands, and add them to the app
	 *
	 * @param Interfaces\CommonPathes 			$path
	 * @param Interfaces\Merger 				$merger
	 * @param Interfaces\RequirementChecker 	$checker
	 * @param Interfaces\Git 					$git
	 */
	protected function initCommands(Interfaces\CommonPathes $path,
									Interfaces\Merger $merger,
									Interfaces\RequirementChecker $checker,
									Interfaces\Git $git)
	{
		$this->add(new Command\UpdateCommand($path, $merger, $checker, $git, $this->repos));
		$this->add(new Command\DeleteCommand($path, $merger, $checker, $git, $this->repos));
		$this->add(new Command\UpdatePluginsCommand($path, $merger, $checker, $git, $this->repos));
		$this->add(new Command\ReinstallCommand($path, $merger, $checker, $git, $this->repos));
		$this->add(new Command\InstallCommand($path, $merger, $checker, $git, $this->repos));
		$this->add(new Command\ConfigCommand($path, $merger, $checker, $git, $this->repos));
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
	protected function initConfigRepo($path, $gw, $parser)
	{
		$ge 			= new GitExecuter();
		$this->repos 	= $this->getConfigRepos($path, $gw, $parser);
		$path 			= $path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG;

		if(!is_dir($path))
		{
			foreach ($this->repos as $repo)
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
	 * @return striing
	 */
	protected function createUniqueDir($path, $url)
	{
		assert('is_string($path)');
		assert('is_string($url)');

		$hash = md5($url);
		mkdir($path . "/" . $hash, 0755, true);
		return $path . "/" . $hash;
	}

}