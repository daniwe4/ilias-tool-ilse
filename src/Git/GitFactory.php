<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Git;

/**
 * Builds git-instances.
 */
class GitFactory
{
	/**
	 * @return Git
	 */
	public function getRepo($path, $repo_url, $name)
	{
		return new GitWrapper($path, $repo_url, $name);
	}
}
