<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Interfaces;

/**
 * 
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de> 
 */
interface Git {
	/**
	 * clones the repository an checkout the needed branch
	 *
	 * @param string $git_url 				url to ILIAS repository
	 * @param string $git_branch			branch should be checked out
	 * @param string $installation_path		path clone repository to
	 * @param string $name 					name of the repo
	 */
	public function cloneGitTo($git_url, $git_branch, $installation_path, $name);
}
