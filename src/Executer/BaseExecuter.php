<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Executer;

/**
 * Base class for all executers
 */
abstract class BaseExecuter
{
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
	public function _construct($config, \CaT\Ilse\Interfaces\RequirementChecker $checker)
	{
		assert('is_strig($config)');

		$parser = new \CaT\Ilse\YamlParser();
		$gc = $parser->read_config($config, "\\CaT\\Ilse\\Config\\General");

		$this->checker 			= $checker;
		$this->http_path 		= $gc->server()->httpPath();
		$this->absolute_path 	= $gc->server()->absolute_path();
		$this->data_path 		= $gc->client()->dataDir();
		$this->client_id 		= $gc->client()->name();
		$this->git_url 			= $gc->gitBranch()->url();
		$this->git_branch_name 	= $gc->gitBranch()->branch();
		$this->web_dir 			= App::I_D_WEB_DIR;
	}

}