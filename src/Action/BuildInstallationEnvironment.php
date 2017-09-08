<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

use CaT\Ilse\Config;
use CaT\Ilse\Setup\RequirementsChecker;
use CaT\Ilse\Aux\Git\GitFactory;
use CaT\Ilse\Aux\Filesystem;
use CaT\Ilse\Aux\TaskLogger;

/**
 * Run the ILIAS setup
 */
class BuildInstallationEnvironment implements Action
{
	/**
	 * @var Config\Server
	 */
	protected $server_config;

	/**
	 * @var	Config\Client
	 */
	protected $client_config;

	/**
	 * @var	Config\DB
	 */
	protected $db_config;

	/**
	 * @var	Config\Log
	 */
	protected $log_config;

	/**
	 * @var	Config\Git
	 */
	protected $git_config;

	/**
	 * @var	RequirementsChecker
	 */
	protected  $requirement_checker;

	/**
	 * @var TaskLogger
	 */
	protected $task_logger;

	/**
	 * @var GitFactory
	 */
	protected $git_factory;

	/**
	 * @var	Filesystem
	 */
	protected $filesystem;

	public function __construct(Config\Server $server_config, Config\Client $client_config, Config\DB $db_config, Config\Log $log_config, Config\Git $git_config, RequirementsChecker $requirements_checker, TaskLogger $task_logger, GitFactory $git_factory, Filesystem $filesystem)
	{
		$this->server_config = $server_config;
		$this->client_config = $client_config;
		$this->db_config = $db_config;
		$this->log_config = $log_config;
		$this->git_config = $git_config;
		$this->requirements_checker = $requirements_checker;
		$this->task_logger = $task_logger;
		$this->git_factory = $git_factory;
		$this->filesystem = $filesystem;
	}

	/**
	 * Start the setup for the environment
	 */
	public function perform()
	{
		$this->createWebDir();
		$this->createDataDir();
		$this->createLogFile();
		$this->createErrorLogDir();
		$this->cloneILIAS();
	}

	protected function createWebDir() {
		$absolute_path = $this->server_config->absolute_path();
		$this->task_logger->always("Creating web directory '$absolute_path'", function() use ($absolute_path) {
			if (!$this->requirements_checker->webDirectoryExists($absolute_path)) {
				$this->filesystem->makeDirectory($absolute_path);
			}
			if (!$this->requirements_checker->webDirectoryWriteable($absolute_path)) {
				$this->filesystem->chmod($absolute_path, 0755);
			}
			if (!$this->requirements_checker->webDirectoryContainsILIAS($absolute_path)) {
				$this->filesystem->purgeDirectory($absolute_path);
			}
		});
	}

	protected function createDataDir() {
		$data_dir = $this->client_config->data_dir();
		$this->task_logger->always("Creating data directory '$data_dir'", function() use ($data_dir) {
			if (!$this->requirements_checker->dataDirectoryExists($data_dir)) {
				$this->filesystem->makeDirectory($data_dir);
			}
			if (!$this->requirements_checker->dataDirectoryWriteable($data_dir)) {
				$this->filesystem->chmod($data_dir, 0755);
			}
			if (!$this->requirements_checker->dataDirectoryEmpty($data_dir, $this->client_config->name())) {
				$this->filesystem->purgeDirectory($data_dir);
			}
		});
	}

	protected function createLogFile() {
		$log_dir = $this->log_config->path();
		$file_name = $this->log_config->file_name();
		$path = "$log_dir/$file_name";
		$this->task_logger->always("Creating log file '$path'", function() use ($log_dir, $file_name, $path) {
			if (!$this->requirements_checker->logDirectoryExists($log_dir)) {
				$this->filesystem->makeDirectory($log_dir);
			}
			if (!$this->requirements_checker->logFileExists($path)) {
				$this->filesystem->write($path, "");
			}
			if (!$this->requirements_checker->logFileWriteable($path)) {
				$this->filesystem->chmod($path, 0755);
			}
		});
	}

	protected function createErrorLogDir() {
		$error_log_dir = $this->log_config->error_log();
		$this->task_logger->always("Creating directory for error logs '$error_log_dir'", function() use ($error_log_dir) {
			if (!$this->requirements_checker->logDirectoryExists($error_log_dir)) {
				$this->filesystem->makeDirectory($error_log_dir);
			}
			if (!$this->requirements_checker->logDirectoryWriteable($error_log_dir)) {
				$this->filesystem->chmod($error_log_dir, 0755);
			}
		});
	}

	protected function cloneILIAS() {
		$this->task_logger->progressing("Getting ILIAS-repo", function () {
			if (!$this->requirements_checker->webDirectoryContainsILIAS($this->server_config->absolute_path())) {
				$git = $this->git_factory->getRepo($this->server_config->absolute_path(), $this->git_config->url(), "ILIAS", true);
				$git->gitClone();
				$git->gitCheckout($this->git_config->branch());
			}
		});
	}
}
