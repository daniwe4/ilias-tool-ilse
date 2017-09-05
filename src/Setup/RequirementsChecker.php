<?php
/* Copyright (c) 2016, 2017 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Setup;

/**
 * Interface for requirement checker.
 */
interface RequirementsChecker {
	/**
	 * Checks the defined data directory exist
	 *
	 * @param string $path path to the data directory
	 * @return bool
	 */
	public function dataDirectoryExists($path);

	/**
	 * Checks the system has needed permissions on data directory
	 *
	 * @param string $path path to the data directory
	 * @return bool
	 */
	public function dataDirectoryWriteable($path);

	/**
	 * Checks if the client directory in data directory is empty
	 *
	 * @param $path 	path to the data directory
	 * @param $client 	directory name of the client
	 * @return bool
	 */
	public function dataDirectoryEmpty($path, $client);

	/**
	 * Checks if the installed PHP version is valid to minimum requirements
	 *
	 * @param string $phpversion 	installed php version
	 * @param string $required 		minimum required php version
	 * @return bool
	 */
	public function validPHPVersion($phpversion);

	/**
	 * ILIAS version < trunk|5.2 has some issues with PHP7.
	 * Checks the installed php version is compatible to selected ILIAS version
	 *
	 * @param string $phpversion 	installed php version
	 * @param string $branch_name 	branch_name equals ILIAS version
	 * @return bool
	 */
	public function phpVersionILIASBranchCompatible($phpversion, $branch_name);

	/**
	 * Checks if the pdo extension is installed
	 *
	 * @return bool
	 */
	public function pdoExist();

	/**
	 * Checks if its possible to connect to MySQL Database
	 *
	 * @param string $host 		host name of databse server
	 * @param string $user 		username for login
	 * @param string §passwd 	password for user
	 * @return bool
	 */
	public function databaseConnectable($host, $user, $passwd);

	/**
	 * Checks the directory for the log file exist
	 *
	 * @param string $path 	path to log file
	 * @return bool
	 */
	public function logDirectoryExists($path);

	/**
	 * Checks if the defined log file exist
	 *
	 * @param sring $path 			path to log file
	 * @return bool
	 */
	public function logFileExists($path);

	/**
	 * Checks if the logfile is writeabe.
	 *
	 * @param sring $path 			path to log file
	 * @return bool
	 */
	public function logFileWriteable($path);
}
