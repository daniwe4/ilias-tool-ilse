<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use CaT\Ilse\App\App;

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
								\CaT\Ilse\Interfaces\Git $git,
								array $repos = array())
	{
		parent::__construct();
		$this->process 	= new Process("");
		$this->path 	= $path;
		$this->merger 	= $merger;
		$this->checker 	= $checker;
		$this->git 		= $git;
		$this->repos 	= $repos;
	}

	/**
	 * Match subdirectory
	 *
	 * @param string 	$name
	 */
	protected function searchSubDir($name)
	{
		foreach ($this->repos as $repo)
		{
			$hash = md5($repo);
			$dir = $this->path->getHomeDir() . "/" . App::I_P_GLOBAL_CONFIG . "/" . $hash . "/" . basename($repo, '.git') . "/" . $name;

			if(is_dir($dir))
			{
				return $dir . "/" . App::I_F_CONFIG;
			}
		}
	}

	/**
	 * Merge all given configs
	 *
	 * @param string[]
	 */
	protected function merge(array $configs)
	{
		$arr = array_map(function ($s) {
			if(preg_match("/[a-zA-Z0-9_\/]+\.y[a]?ml/", $s))
			{
				return $s;
			}
			return $this->searchSubDir($s);
		}, $configs);

		return $this->merger->mergeConfigs($arr);
	}
}
