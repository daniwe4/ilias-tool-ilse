<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

use CaT\Ilse\Config;
use CaT\Ilse\Filesystem;

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

	public function __construct(Config\DB $db_config, Config\Server $server_config, Config\Client $client_config, Config\Log $log_config, Filesystem $filesystem)
	{
		$this->db_config = $db_config;
		$this->server_config = $server_config;
		$this->client_config = $client_config;
		$this->log_config = $log_config;
		$this->filesystem = $filesystem;	
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
		echo "Connecting to MySQL...";
		$host = $this->db_config->host();
		$user = $this->db_config->user();
		$passwd = $this->db_config->password();

	 	//TODO: This should be using PDO instead.
		$connection = new \mysqli($host, $user, $passwd);
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
		return $con;
	}

	/**
	 * Drop the database used by ILIAS
	 */
	protected function dropDatabase() {
		$connection = $this->connectDB();

		$database = $this->db_config->database();
		echo "Droping database '$database'...";

		if($this->databaseExist($connection, $database)) {
			$drop_query = "DROP DATABASE ".$database;
			if(!$con->query($drop_query)) {
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
		$this->filesystem->remove($this->server_config->absolutePath());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Delete the data folder
	 */
	protected function deleteDataFolder() {
		echo "Deleting data folder...";
		$this->filesystem->remove($this->client_config->dataDir());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Delete the error_log
	 */
	protected function deleteErrorLog() {
		echo "Deleting error_log folder...";
		$this->filesystem->remove($this->log_config->error_log());
		echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	/**
	 * Delete ilias log file
	 */
	protected function deleteLogFile()
	{
		echo "Deleting log file...";
		$this->filesystem->remove($this->log_config->path()."/".$this->log_config->file_name());
		echo "\t\t\t\t\t\t\t\t\t\t\tDone!\n";
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
