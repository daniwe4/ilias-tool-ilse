<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Executer;

use CaT\Ilse\App;

/**
 * Base class for all executers
 */
abstract class BaseExecuter
{
	/**
	 * @var string
	 */
	protected $gc;

	/**
	 * @var \CaT\Ilse\Interfaces\RequirementChecker
	 */
	protected $checker;

	/**
	 * @var \CaT\Ilse\Interfaces\Git
	 */
	protected $git;

	/**
	 * @var string
	 */
	protected $http_path;

	/**
	 * @var string
	 */
	protected $absolute_path;

	/**
	 * @var string
	 */
	protected $data_path;

	/**
	 * @var string
	 */
	protected $client_id;

	/**
	 * @var string
	 */
	protected $git_url;

	/**
	 * @var string
	 */
	protected $git_branch_name;

	/**
	 * @var string
	 */
	protected $web_dir;

	/**
	 * Constructor of the BaseExecuter class
	 *
	 * @param string 									$config
	 * @param \CaT\Ilse\Interfaces\RequirementChecker 	$checker
	 */
	public function __construct($config, \CaT\Ilse\Interfaces\RequirementChecker $checker, \CaT\Ilse\Interfaces\Git $git)
	{
		assert('is_string($config)');

		$parser = new \CaT\Ilse\YamlParser();
		$this->gc = $parser->read_config($config, "\\CaT\\Ilse\\Config\\General");

		$this->checker 			= $checker;
		$this->git 				= $git;
		$this->http_path 		= $this->gc->server()->httpPath();
		$this->absolute_path 	= $this->gc->server()->absolutePath();
		$this->data_path 		= $this->gc->client()->dataDir();
		$this->client_id 		= $this->gc->client()->name();
		$this->git_url 			= $this->gc->gitBranch()->url();
		$this->git_branch_name 	= $this->gc->gitBranch()->branch();
		$this->error_log 		= $this->gc->log()->errorLog();
		$this->web_dir 			= App::I_D_WEB_DIR;
	}
}