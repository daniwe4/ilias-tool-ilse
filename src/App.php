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

	public function __construct(Interfaces\CommonPathes $path,
								Interfaces\Merger $merger,
								Interfaces\RequirementChecker $checker,
								Interfaces\Git $git,
								Interfaces\Parser $parser,
								GitWrapper\Git $gw)
	{
		parent::__construct();

		$this->initAppFolder($path);
		$this->initConfigRepo($path);
		$this->initCommands($path, $merger, $checker, $git);

	}

	/**
	 * Initialize all commands, and add them to the app
	 *
	 * @param Interfaces\CommonPathes 		$path
	 * @param Interfaces\Merger 			$merger
	 * @param Interfaces\RequirementChecker $checker
	 * @param Interfaces\Git 				$git
	 */
	protected function initCommands(Interfaces\CommonPathes $path,
									Interfaces\Merger $merger,
									Interfaces\RequirementChecker $checker,
									Interfaces\Git $git)
	{
		$this->add(new Command\UpdateCommand($path, $merger, $checker, $git));
		$this->add(new Command\DeleteCommand($path, $merger, $checker, $git));
		$this->add(new Command\UpdatePluginsCommand($path, $merger, $checker, $git));
		$this->add(new Command\ReinstallCommand($path, $merger, $checker, $git));
		$this->add(new Command\InstallCommand($path, $merger, $checker, $git));
		$this->add(new Command\ConfigCommand($path, $merger, $checker, $git));
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
	 * @param string 		$path
	 */
	protected function initConfigRepo($path)
	{
		$ge = new GitExecuter();

		if(!is_dir($path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG))
		{
			$ge->cloneGitTo($this->getConfigRepo(),
							self::I_R_BRANCH,
							$path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG
							);
		}
	}

	/**
	 * Get repos from configrepos file
	 */
	protected function getConfigRepos()
	{
		if(!is_file($this->path->getHomeDir() . "/" . self::I_F_CONFIG_REPOS))
		{
			throw new \Exception("File not found at " . self::I_F_CONFIG_REPOS);
		}

		return $this->parser->read($this->path->getHomeDir() . "/" . self::I_F_CONFIG_REPOS);
	}

	/**
	 * Get the config repo
	 */
	protected function getConfigRepo()
	{
		foreach($this->getConfigRepos()['repos'] as $repo)
		{
			if($this->gw->gitIsRemoteGitRepo($repo) === 0)
			{
				return $repo;
			}
		}
	}
}