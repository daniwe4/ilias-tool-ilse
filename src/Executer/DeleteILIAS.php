<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Executer;

use CaT\Ilse\Action;
use CaT\Ilse\FilesystemImpl;

/**
 * Delete an ILIAS instance
 */
class DeleteILIAS extends BaseExecuter
{
	/**
	 * @var Action\DeleteILIAS
	 */
	protected $action;

	/**
	 * Constructor of the class InstallILIAS
	 *
	 * @param string 									$config
	 * @param \CaT\Ilse\Interfaces\RequirementChecker 	$checker
	 * @param \CaT\Ilse\Interfaces\Git 					$git
	 */
	public function __construct($config, \CaT\Ilse\Interfaces\RequirementChecker $checker, \CaT\Ilse\Interfaces\Git $git)
	{
		assert('is_string($config)');
		parent::__construct($config, $checker, $git);

		$this->action = new Action\DeleteILIAS($this->gc->database(), $this->gc->server(), $this->gc->log(), new FilesystemImpl());
	}
}
