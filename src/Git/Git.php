<?php
namespace CaT\Ilse\Git;

/**
 * Wrapper for git commands
 * 
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
interface Git
{
		/**
		 * Clone a repository by a given URL
		 *
		 * @throws Exception
		 * @return boolean
		 */
		public function gitClone();

		/**
		 * Checkout a branch by name
		 *
		 * @param string    $path
		 * @param boolean   $new
		 *
		 * @throws Exception
		 * @return boolean
		 */
		public function gitCheckOut($branch, $new);

		/**
		 * Pull a repo
		 *
		 * @param string    $remote
		 * @param string    $branch
		 *
		 * @throws Exception
		 * @return boolean
		 */
		public function gitPull($remote, $branch);

		/**
		 * Fetch a repo
		 *
		 * @param string    $remote
		 *
		 * @throws Exception
		 * @return boolean
		 */
		public function gitFetch($remote);
}