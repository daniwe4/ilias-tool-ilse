<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;
use Gitonomy\Git\Admin as Git;
use Gitonomy\Git\Repository;
use CaT\Ilse\Git\GitWrapper;
/**
 * Implementation of the git interface.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */

class GitExecuter implements \CaT\Ilse\Interfaces\Git
{
	const URL_REG_EX = "/^(https:\/\/github\.com)/";

	/**
	 * @inhertidoc
	 */
	public function cloneGitTo($git_url, $git_branch, $installation_path)
	{
		assert('is_string($git_url)');
		assert('is_string($git_branch)');
		assert('is_string($installation_path)');

		$git = new GitWrapper($installation_path, $git_url);
		$git->gitClone();
		$git->gitCheckout($git_branch);
	}

	protected function fetch($repository)
	{
		$repository->run("fetch");
	}

	protected function checkoutBranch($repository, $git_branch)
	{
		$args = array($git_branch);
		$repository->run("checkout", $args);
	}

	protected function pullBranch($repository, $git_branch)
	{
		$args = array("origin", $git_branch);
		$repository->run("pull", $args);
	}

	protected function cloneRepository($installation_path, $git_url, $git_branch)
	{
		$args = array("--branch", $git_branch);
		Git::cloneRepository($installation_path, $git_url, $args);
	}
}