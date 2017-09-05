<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use CaT\Ilse\Setup\CoreInstaller52;
use CaT\Ilse\Config;
use CaT\Ilse\Aux\TaskLogger;
use CaT\Ilse\Aux;

class SmokeTest extends PHPUnit_Framework_TestCase {
	public function _test_valid_ClientConfig() {
		include __DIR__."/../ilse.php";
	}

	public function test_core_installer_52() {
		$config = $this->createMock(Config\General::class);
		$task_logger = $this->createMock(TaskLogger::class);

		$core_installer = new CoreInstaller52($config, $task_logger);
		$this->assertInstanceOf(CoreInstaller52::class, $core_installer);
	}

	public function test_filesystem_impl() {
		$fs = new Aux\FilesystemImpl();
		$this->assertInstanceOf(Aux\Filesystem::class, $fs);
	}
}
