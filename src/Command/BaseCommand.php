<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use CaT\Ilse\App;

/**
 * Base class for all commands
 */
abstract class BaseCommand extends Command
{
	/**
	 * @var Symfony\Component\Process\Process
	 */
	protected $process;

	/**
	 * @var CaT\Ilse\Interfaces\Path
	 */
	protected $path;

	/**
	 * @var CaT\Ilse\Interfaces\Merge
	 */
	protected $merge;

	/**
	 * @var \CaT\Ilse\Interfaces\RequirementChecker
	 */
	protected $checker;

	/**
	 * @var \CaT\Ilse\Interfaces\Git
	 */
	protected $git;

	public function __construct(\CaT\Ilse\Interfaces\CommonPathes $path,
								\CaT\Ilse\Interfaces\Merger $merger,
								\CaT\Ilse\Interfaces\RequirementChecker $checker,
								\CaT\Ilse\Interfaces\Git $git)
	{
		parent::__construct();
		$this->process 	= new Process("");
		$this->path 	= $path;
		$this->merger 	= $merger;
		$this->checker 	= $checker;
		$this->git 		= $git;
	}

	/**
	 * Get the configfile path for a given name
	 * 
	 * @param string 	$name
	 * 
	 */
	protected function getConfigPathByName($name)
	{
		assert('is_string($name)');
		return $this->path->getHomeDir() . "/" . App::I_P_GLOBAL_CONFIG . "/" . $name . "/" . App::I_F_CONFIG;
	}

	/**
	 * Merge all given configs
	 *
	 * @param string
	 */
	protected function merge(array $configs)
	{
		$arr = array_map(function ($s) {
			return $this->getConfigPathByName($s);
		}, $configs);
		return $this->merger->mergeConfigs($arr);
	}

}