<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;

use Symfony\Component\Console\Application;
use CaT\Ilse\GitExecuter;


/**
 * Do the main initializing
 */
class App extends Application
{
	const I_P_GLOBAL_CONFIG 	= ".ilias-installer/config";
	const I_F_CONFIG			= "ilse_config.yaml";
	const I_R_CONFIG			= "https://github.com/conceptsandtraining/ilias-configs.git";
	const I_R_BRANCH			= "master";
	const I_D_WEB_DIR			= "data";

	/**
	 * @var CaT\Ilse\Interfaces\Path
	 */
	protected $path;

	public function __construct(Interfaces\CommonPathes $path,
								Interfaces\Merger $merger,
								Interfaces\RequirementChecker $checker)
	{
		parent::__construct();

		$this->path 	= $path;
		$this->merger 	= $merger;
		$this->checker 	= $checker;
		$this->initConfigRepo();
		$this->initCommands();
	}

	/**
	 * Initialize all commands, and add them to the app
	 */
	protected function initCommands()
	{
		// $this->add(new Command\UpdateCommand($this->path, $this->merger, $this->checker));
		$this->add(new Command\ReinstallCommand($this->path, $this->merger, $this->checker));
		$this->add(new Command\InstallCommand($this->path, $this->merger, $this->checker));
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