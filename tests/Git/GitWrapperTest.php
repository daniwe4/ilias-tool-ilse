<?php
require_once(__DIR__."/GitTest.php");

/**
 * Test class for git commands
 * 
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
// TODO: disables this, as this makes travis fail. It is a problem, that we use THIS repo
// for testing the functionality. We should instead deliver some small test repo for that
// purpose.
class GitWrapperTest extends GitTest
{
	public function getImplementation($target_dir, $remote_url, $repo_name)
	{
		// TODO: this should not rely on connectivity to github. it could use this repo instead.
		return new \CaT\Ilse\Git\GitWrapper($target_dir, $remote_url, $repo_name);
	}
}
