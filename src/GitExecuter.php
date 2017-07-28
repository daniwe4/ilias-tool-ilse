<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;
use Gitonomy\Git\Admin as Git;
use Gitonomy\Git\Repository;

/**
 * Implementation of the git interface.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class GitExecuter implements \CaT\Ilse\Interfaces\Git {

	const URL_REG_EX = "/^(https:\/\/github\.com)/";

	/**
	 * @inhertidoc
	 */
	public function cloneGitTo($git_url, $git_branch, $installation_path) {
		assert('is_string($git_url)');
		assert('is_string($git_branch)');
		assert('is_string($installation_path)');

		$cur_dir = getcwd();
		if(is_dir($installation_path)) {
			chdir($installation_path);
		}

		if(!Git::isValidRepository(strtolower($git_url))) {
			throw new \LogicException("Did not find a repository at ".$git_url);
		}

		if(is_dir($installation_path)) {
			$repository = new Repository($installation_path);
			if($repository->isBare()) {
				$this->cloneRepository($installation_path, $git_url, $git_branch);
				return;
			}

			$this->fetch($repository);
			$this->checkoutBranch($repository, $git_branch);
			$this->pullBranch($repository, $git_branch);
		} else {
			$this->cloneRepository($installation_path, $git_url, $git_branch);
		}
		chdir($cur_dir);
	}

	protected function fetch($repository) {
		$repository->run("fetch");
	}

	protected function checkoutBranch($repository, $git_branch) {
		$args = array($git_branch);
		$repository->run("checkout", $args);
	}

	protected function pullBranch($repository, $git_branch) {
		$args = array("origin", $git_branch);
		$repository->run("pull", $args);
	}

	protected function cloneRepository($installation_path, $git_url, $git_branch) {
		$args = array("--branch", $git_branch);
		Git::cloneRepository($installation_path, $git_url, $args);
	}
}