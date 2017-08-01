<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;

use Symfony\Component\Console\Application;
use CaT\Ilse\GitWrapperExecuter;


/**
 * Do the main initializing
 */
class App extends Application
{
	const I_P_GLOBAL 			= ".ilse";
	const I_P_GLOBAL_CONFIG 	= ".ilse/config";
	const I_F_CONFIG			= "ilse_config.yaml";
	const I_R_CONFIG			= "https://github.com/conceptsandtraining/ilias-configs.git";
	const I_R_BRANCH			= "master";
	const I_D_WEB_DIR			= "data";
	const I_S_SUCCESS 			= 0;
	const I_S_FAILURE 			= 1;

	/**
	 * @var CaT\Ilse\Interfaces\Path
	 */
	protected $path;

	public function __construct(Interfaces\CommonPathes $path,
								Interfaces\Merger $merger,
								Interfaces\RequirementChecker $checker,
								Interfaces\Git $git)
	{
		parent::__construct();

		$this->path 	= $path;
		$this->merger 	= $merger;
		$this->checker 	= $checker;
		$this->git 		= $git;

		$this->initAppFolder();
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
	 * Checks whether the app folder exists otherwise create one
	 *
	 * @return int
	 */
	protected function initAppFolder()
	{
		if(!is_dir($this->path->getHomeDir() . "/" . self::I_P_GLOBAL))
		{
			mkdir($this->path->getHomeDir() . "/" . self::I_P_GLOBAL, 0755);
		}
		return self::I_S_SUCCESS;
	}

	/**
	 * Initialize the config repo in ~/.ilias-installer/config
	 */
	protected function initConfigRepo()
	{
		$ge = new GitExecuter();

		if(!is_dir($this->path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG))
		{
			$ge->cloneGitTo(self::I_R_CONFIG,
							self::I_R_BRANCH,
							$this->path->getHomeDir() . "/" . self::I_P_GLOBAL_CONFIG
							);
		}
	}
}