<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;

/**
 * Logs which task is currently running.
 */
interface TaskLogger {
	/**
	 * Task needs to succeed in order for the whole process
	 * to go on.
	 *
	 * @param	string		$title
	 * @param	callable 	$task
	 * @return	mixed	what callable returns
	 */
	public function always($title, callable $task);

	/**
	 * Task may fail, process continues.
	 *
	 * @param	string		$title
	 * @param	callable 	$task
	 * @return	mixed	what callable returns
	 */
	public function eventually($title, callable $task);
}
