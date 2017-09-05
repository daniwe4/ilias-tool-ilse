<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

use CaT\Ilse\Config;
use CaT\Ilse\Aux\Filesystem;
use CaT\Ilse\Aux\TaskLogger;

/**
 * Delete an ILIAS instance
 */
class DeleteILIAS
{
	/**
	 * @var Config\DB
	 */
	protected $db_config;

	/**
	 * @var Config\Server
	 */
	protected $server_config;

	/**
	 * @var	Config\Client
	 */
	protected $client_config;

	/**
	 * @var	Config\Log
	 */
	protected $log_config;

	/**
	 * @var	Filesystem
	 */
	protected $filesystem;

	/**
	 * @var	TaskLogger
	 */
	protected $task_logger;

	public function __construct(Config\DB $db_config, Config\Server $server_config, Config\Client $client_config, Config\Log $log_config, Filesystem $filesystem, TaskLogger $task_logger)
	{
		$this->db_config = $db_config;
		$this->server_config = $server_config;
		$this->client_config = $client_config;
		$this->log_config = $log_config;
		$this->filesystem = $filesystem;
		$this->task_logger = $task_logger;
	}

	/**
	 * Delete ILIAS.
	 *
	 * @return	void
	 */
	public function run() {
		assert('is_bool($complete)');

		$this->dropDatabase();
		$this->deleteILIASFolder();
		$this->deleteDataFolder();
		$this->deleteLogFile();
		$this->deleteErrorLog();
	}

	/**
	 * Establish a database connection
	 *
	 * @return	\mysqli
	 */
	protected function connectDB() {
		$this->task_logger->eventually("Connecting to Database", function () {
			$host = $this->db_config->host();
			$user = $this->db_config->user();
			$passwd = $this->db_config->password();

			//TODO: This should be using PDO instead.
			$connection = new \mysqli($host, $user, $passwd);
			return $con;
		});
	}

	/**
	 * Drop the database used by ILIAS
	 */
	protected function dropDatabase() {
		$connection = $this->connectDB();
		$database = $this->db_config->database();

		$this->task_logger->eventually("Droping database '$database'", function () {
			if($this->databaseExist($connection, $database)) {
				$drop_query = "DROP DATABASE ".$database;
				if(!$con->query($drop_query)) {
					throw new Exception("Database could not be deleted. (Error: )");
				}
			}
		});
	}

	/**
	 * Delete ILIAS folder
	 */
	protected function deleteILIASFolder() {
		$this->task_logger->eventually("Deleting ILIAS files", function () {
			$this->filesystem->remove($this->server_config->absolutePath());
		});
	}

	/**
	 * Delete the data folder
	 */
	protected function deleteDataFolder() {
		$this->task_logger->eventually("Deleting data folder", function () {
			$this->filesystem->remove($this->client_config->dataDir());
		});
	}

	/**
	 * Delete the error_log
	 */
	protected function deleteErrorLog() {
		$this->task_logger->eventually("Deleting error_log folder", function () {
			$this->filesystem->remove($this->log_config->error_log());
		});
	}

	/**
	 * Delete ilias log file
	 */
	protected function deleteLogFile()
	{
		$this->task_logger->eventually("Deleting log file", function () {
			$this->filesystem->remove($this->log_config->path()."/".$this->log_config->file_name());
		});
	}

	/**
	 * Check whether a database exists
	 *
	 * @param	string	$database
	 * @param	\mysqli	$connection
	 * @return	bool
	 */
	protected function databaseExist($connection, $database) {
		$select = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$database."'";
		$res = $connection->query($select);

		return $res->num_rows > 0;
	}
}
