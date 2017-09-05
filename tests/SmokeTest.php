<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use CaT\Ilse\Setup\CoreInstaller52;
use CaT\Ilse\Config;
use CaT\Ilse\Aux\TaskLogger;
use CaT\Ilse\Aux;
use CaT\Ilse\Action;
use CaT\Ilse\Setup;

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

	public function test_CheckRequirementsAction() {
		$sc = $this->createMock(Config\Server::class);
		$cc = $this->createMock(Config\Client::class);
		$gc = $this->createMock(Config\Git::class);
		$dc = $this->createMock(Config\DB::class);
		$lc = $this->createMock(Config\Log::class);
		$fs = $this->createMock(Aux\Filesystem::class);
		$tl = $this->createMock(Aux\TaskLogger::class);
		$cr = new Action\CheckRequirements($sc, $cc, $gc, $dc, $lc, $fs, $tl);
		$this->assertInstanceOf(Setup\RequirementsChecker::class, $cr);
	}
}
