<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Executer;

/**
 * Delete an ILIAS instance
 */
class DeleteILIAS extends BaseExecuter
{
	/**
	 * @var \mysqli
	 */
	protected $con;

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
	}

	/**
	 * Start the deinstallation process
	 */
	public function run() {
		$this->connectDB();
		$this->dropDatabase();
		$this->deleteILIASFolder();
		$this->deleteDataFolder();
	}

	/**
	 * Establish a database connection
	 */
	protected function connectDB() {
		echo "Connecting to MySQL...";
		$host = $this->gc->database()->host();
		$user = $this->gc->database()->user();
		$passwd = $this->gc->database()->password();

		$this->con = new \mysqli($host, $user, $passwd);
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Drop the database used by ILIAS
	 */
	protected function dropDatabase() {
		echo "Droping database...";
		$database = $this->gc->database()->database();
		$drop_query = "DROP DATABASE ".$database;

		if($this->dataBaseExist($database)) {
			if(!$this->con->query($drop_query)) {
				throw new Exception("Database could not be deleted. (Error: )");
			}
		}
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Delete ILIAS folder
	 */
	protected function deleteILIASFolder() {
		echo "Deleting ILIAS files...";
		$this->clearDirectory($this->gc->server()->absolutePath());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Delete the data folder
	 */
	protected function deleteDataFolder() {
		echo "Deleting data folder...";
		$this->clearDirectory($this->gc->client()->dataDir()."/".$this->gc->client()->name());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Clear directory recursive
	 *
	 * @param string 	$dir
	 */
	protected function clearDirectory($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->clearDirectory("$dir/$file") : unlink("$dir/$file");
		}

		rmdir($dir);
	}

	/**
	 * Check whether a database exists
	 *
	 * @param string 	$database
	 */
	protected function dataBaseExist($database) {
		$select = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$database."'";
		$res = $this->con->query($select);

		return $res->num_rows > 0;
	}
}