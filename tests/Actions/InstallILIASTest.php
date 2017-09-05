<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use \CaT\Ilse\Action\InstallILIAS;
use \CaT\Ilse\Config;
use \CaT\Ilse\TaskLogger;
use \CaT\Ilse\Setup\CoreInstaller;
use \CaT\Ilse\Setup\CoreInstallerFactory;

// If database had it own interface like filesystem, we could
// drop this and write a proper test instead.
class InstallILIASForTest extends InstallILIAS {
	protected function checkSessionLifetime() {
	}
}

class InstallILIASTest extends PHPUnit_Framework_TestCase {
	public function test_run() {
		$config = $this->createMock(Config\General::class);
		$core_installer_factory = $this->createMock(CoreInstallerFactory::class);
		$core_installer = $this->createMock(CoreInstaller::class);
		$task_logger = $this->createMock(TaskLogger::class);

		$action = new InstallILIASForTest($config, $core_installer_factory, $task_logger);

		$task_logger
			->expects($this->any())
			->method("eventually")
			->will($this->returnCallback(function($s, $c) {
				$c();
			}));
		$task_logger
			->expects($this->any())
			->method("always")
			->will($this->returnCallback(function($s, $c) {
				$c();
			}));

		$core_installer_factory
			->expects($this->once())
			->method("getCoreInstallerForRelease")
			->with("5.2", $config, $task_logger)
			->willReturn($core_installer);

		$core_installer
			->expects($this->at(0))
			->method("writeILIASIni");
		$core_installer
			->expects($this->at(1))
			->method("writeClientIni");
		$core_installer
			->expects($this->at(2))
			->method("installDatabase");
		$core_installer
			->expects($this->at(3))
			->method("applyDatabaseUpdates");
		$core_installer
			->expects($this->at(4))
			->method("applyDatabaseHotfixes");
		$core_installer
			->expects($this->at(5))
			->method("installLanguages");
		$core_installer
			->expects($this->at(6))
			->method("setProxySettings");
		$core_installer
			->expects($this->at(7))
			->method("finishSetup");

		$action->run();
	}
}
