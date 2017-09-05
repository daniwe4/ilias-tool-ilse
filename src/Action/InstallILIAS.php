<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

use CaT\Ilse\Config;
use CaT\Ilse\Setup\CoreInstallerFactory;
use CaT\Ilse\Setup\CoreInstaller;
use CaT\Ilse\TaskLogger;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Run the ILIAS installation process
 */
class InstallILIAS
{
	/**
	 * @var CoreInstallerFactory
	 */
	protected $core_installer_factory;

	/**
	 * @var CoreInstaller|null
	 */
	protected $core_installer = null;

	/**
	 * @var	Config\General
	 */
	protected $config;

	/**
	 * @var TaskLogger
	 */
	protected $task_logger;

	/**
	 * Constructor of the class InstallILIAS
	 *
	 * @param Config\General		$config
	 * @param CoreInstallerFactor	$core_installer_factory
	 * @param TaskLogger			$task_logger
	 */
	public function __construct(Config\General $config, CoreInstallerFactory $core_installer_factory, TaskLogger $task_logger)
	{
		$this->config = $config;
		$this->core_installer_factory = $core_installer_factory;
		$this->task_logger = $task_logger;
	}

	/**
	 * Start the installation process
	 */
	public function run()
	{
		$version = "5.2"; // TODO: read this from config somehow

		$this->task_logger->always("Start ILIAS installation", function() use ($version) {
			$this->core_installer = $this->core_installer_factory->getCoreInstallerForRelease($version, $this->config, $this->task_logger);
		});

		$this->writeILIASIni();
		$this->writeClientIni();
		$this->checkSessionLifetime();
		$this->createDB();
		$this->installLanguages();
		$this->finishInstallation();

		$this->core_installer = null;
	}


	/**
	 * Start installing with creating IliasIni
	 */
	protected function writeIliasIni()
	{
		$this->task_logger->always("Creating ilias.ini", [$this->core_installer, "writeILIASIni"]);
	}

	/**
	 * Write client ini
	 */
	protected function writeClientIni()
	{
		$this->task_logger->always("Creating client.ini", [$this->core_installer, "writeClientIni"]);
	}

	/**
	 * Check session lifetime
	 */
	protected function checkSessionLifetime()
	{
		$this->task_logger->eventually("Checking session lifetime", function() {
			$ilias_session_lifetime = ($this->config->client()->sessionExpire() * 60);
			$php_session_lifetime = ini_get('session.gc_maxlifetime');
			if($php_session_lifetime < $ilias_session_lifetime) {
				throw new \RuntimeException("Your session max lifetime in php.ini is smaller then ILIAS lifetime. Please change it or ILIAS lifetime will never be used.");
			}
		});
	}

	/**
	 * Create the database
	 */
	protected function createDB()
	{
		$this->task_logger->always("Creating database", [$this->core_installer, "installDatabase"]);
		$this->task_logger->always("Applying database updates", [$this->core_installer, "applyDatabaseUpdates"]);
		$this->task_logger->always("Applying database hotfixes", [$this->core_installer, "applyDatabaseHotfixes"]);
	}

	/**
	 * Installing languages
	 */
	protected function installLanguages()
	{
		$this->task_logger->always("Installing languages", [$this->core_installer, "installLanguages"]);
	}

	/**
	 * Check after install
	 */
	protected function finishInstallation()
	{
		$this->task_logger->always("Setting proxy settings", [$this->core_installer, "setProxySettings"]);
		$this->task_logger->always("Finishing setup", [$this->core_installer, "finishSetup"]);
	}
}
