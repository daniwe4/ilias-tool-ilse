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

		$this->path 	= $path;
		$this->merger 	= $merger;
		$this->checker 	= $checker;
		$this->git 		= $git;
		$this->parser 	= $parser;
		$this->gw 		= $gw;

		$this->initConfigRepo();
		$this->initCommands();
	}

	/**
	 * Initialize all commands, and add them to the app
	 */
	protected function initCommands()
	{
		$this->add(new Command\UpdateCommand($this->path, $this->merger, $this->checker, $this->git));
		$this->add(new Command\DeleteCommand($this->path, $this->merger, $this->checker, $this->git));
		$this->add(new Command\UpdatePluginsCommand($this->path, $this->merger, $this->checker, $this->git));
		$this->add(new Command\ReinstallCommand($this->path, $this->merger, $this->checker, $this->git));
		$this->add(new Command\InstallCommand($this->path, $this->merger, $this->checker, $this->git));
		$this->add(new Command\ConfigCommand($this->path, $this->merger, $this->checker, $this->git));
	}

	/**
	 * Initialize the config repo in ~/.ilse/config
	 */
	protected function initConfigRepo()
	{
		$ge = new GitExecuter();

		if(!is_dir($this->path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG))
		{
			$ge->cloneGitTo($this->getConfigRepo(),
							self::I_R_BRANCH,
							$this->path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG
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