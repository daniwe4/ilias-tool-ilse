<?php
/* Copyright (c) 2016, 2017 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

use \CaT\Ilse\Setup\RequirementsChecker;
use \CaT\Ilse\Aux\Filesystem;
use \CaT\Ilse\Aux\TaskLogger;
use \CaT\Ilse\Config;

/**
 * Checks requirements for ILIAS installation
 *
 * TODO: write a test for this.
 */
class CheckRequirements implements Action, RequirementsChecker
{
	CONST REQUIRED_PHP_VERSION = "5.4";

	/**
	 * @var Config\Server
	 */
	protected $server_config;

	/**
	 * @var Config\Client
	 */
	protected $client_config;

	/**
	 * @var Config\Git
	 */
	protected $git_config;

	/**
	 * @var Config\DB
	 */
	protected $db_config;

	/**
	 * @var Config\Log
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

	public function __construct(Config\Server $server_config, Config\Client $client_config, Config\Git $git_config, Config\DB $db_config, Config\Log $log_config, Filesystem $filesystem, TaskLogger $task_logger) {
		$this->filesystem = $filesystem;
		$this->server_config = $server_config;
		$this->client_config = $client_config;
		$this->git_config = $git_config;
		$this->db_config = $db_config;
		$this->log_config = $log_config;
		$this->task_logger = $task_logger;
	}

	public function perform() {
		$this->task_logger->always("Checking web directory", function() {
			$path = $this->server_config->absolute_path();
			if (!$this->webDirectoryExists($path)) {
				throw new \RuntimeException("Web directory '$path' does not exist.");
			}
			if (!$this->webDirectoryWriteable($path)) {
				throw new \RuntimeException("Web directory '$path' is not writeable.");
			}
			if (!$this->webDirectoryEmpty($path)) {
				throw new \RuntimeException("Data directory '$path' is not empty.");
			}
		});
		$this->task_logger->always("Checking data directory", function() {
			$path = $this->client_config->dataDir();
			if (!$this->dataDirectoryExists($path)) {
				throw new \RuntimeException("Data directory '$path' does not exist.");
			}
			if (!$this->dataDirectoryWriteable($path)) {
				throw new \RuntimeException("Data directory '$path' is not writeable.");
			}
			if (!$this->dataDirectoryEmpty($path)) {
				throw new \RuntimeException("Data directory '$path' is not empty.");
			}
		});
		$this->task_logger->always("Checking PHP version", function() {
			$version = phpversion();
			if (!$this->validPHPVersion($version)) {
				throw new \RuntimeException("PHP with version '$version' won't work.");
			}
			$branch = $this->git_config->branch();
			if (!$this->phpVersionILIASBranchCompatible($version, $branch)) {
				throw new \RuntimeException("PHP with version '$version' won't work with branch '$branch'");
			}
		});
		$this->task_logger->always("Checking database connection", function() {
			if (!$this->pdoExist()) {
				throw new \RuntimeException("PDO database classes must be installed.");
			}
			$host = $this->db_config->host();
			$database = $this->db_config->create_db() ? $this->db_config->database() : null;
			$user = $this->db_config->user();
			$password = $this->db_config->password();
			if (!$this->databaseConnectable($host, $database, $user, $password)) {
				throw new \RuntimeException("Cannot connect to database at '$host' with '$user'");
			}
		});
		$this->task_logger->always("Checking logging files and directories", function () {
			$log_directory = $this->log_config->path();
			if (!$this->logDirectoryExists($log_directory)) {
				throw new \RuntimeException("Directory '$log_directory' for logs does not exist.");
			}
			$log_file = $this->log_config->file_name();
			$log_path = $log_directory."/".$log_file;
			if (!$this->logFileExists($log_path)) {
				throw new \RuntimeException("File '$log_file' in '$log_directory' does not exist.");
			}
			if (!$this->logFileWriteable($log_path)) {
				throw new \RuntimeException("File '$log_file' in '$log_directory' is not writeable.");
			}
			$error_log_directory = $this->log_config->error_log();
			if (!$this->logDirectoryExists($error_log_directory)) {
				throw new \RuntimeException("Directory '$log_directory' for error-logs does not exist.");
			}
			if (!$this->logDirectoryWriteable($error_log_directory)) {
				throw new \RuntimeException("Directory '$log_directory' for error-logs is not writeable.");
			}
		});
	}

	/**
	 * @inheritdocs
	 */
	public function webDirectoryExists($path) {
		assert('is_string($path)');
		return $this->filesystem->isDirectory($path);
	}

	/**
	 * @inheritdocs
	 */
	public function webDirectoryWriteable($path) {
		assert('is_string($path)');
		return $this->filesystem->isWriteable($path);
	}

	/**
	 * @inheritdocs
	 */
	public function webDirectoryEmpty($path) {
		assert('is_string($path)');
		return $this->filesystem->isEmpty($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryExists($path) {
		assert('is_string($path)');
		return $this->filesystem->isDirectory($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryWriteable($path) {
		assert('is_string($path)');
		return $this->filesystem->isWriteable($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryEmpty($path, $client) {
		assert('is_string($path)');
		assert('is_string($client)');

		$client_dir = "$path/$client";
		return !$this->filesystem->isDirectory($client_dir) || $this->filesystem->isEmpty($client_dir);
	}

	/**
	 * @inheritdocs
	 */
	public function validPHPVersion($phpversion) {
		assert('is_string($phpversion)');

		return $phpversion >= self::REQUIRED_PHP_VERSION;
	}

	public function phpVersionILIASBranchCompatible($phpversion, $branch_name) {
		assert('is_string($phpversion)');
		assert('is_string($branch_name)');

		if($phpversion >= "7.1" && $branch_name != "trunk") {
			return false;
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function pdoExist() {
		return class_exists("PDO");
	}

	/**
	 * @inheritdocs
	 */
	public function databaseConnectable($host, $database, $user, $passwd) {
		assert('is_string($host)');
		assert('is_string($database) || is_null($database)');
		assert('is_string($user)');
		assert('is_string($passwd)');

		try{
			if ($database !== null) {
				$dsn = "mysql:host=$host;dbname=$database;charset=utf8;";
			}
			else {
				$dsn = "mysql:host=$host;charset=utf8;";
			}
			$this->pdo = new \PDO($dsn, $user, $passwd, array(3=>2, 10000=>true, 2=>18000));
		} catch(Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function logDirectoryExists($path) {
		return $this->filesystem->isDirectory($path);
	}

	/**
	 * @inheritdocs
	 */
	public function logFileExists($path) {
		assert('is_string($path)');
		return $this->filesystem->exists($path);
	}

	/**
	 * @inheritdocs
	 */
	public function logFileWriteable($path) {
		assert('is_string($path)');
		return $this->filesystem->isWriteable($path);
	}

	/**
	 * @inheritdocs
	 */
	public function logDirectoryWriteable($path) {
		assert('is_string($path)');
		return $this->filesystem->isWriteable($path);
	}
}
