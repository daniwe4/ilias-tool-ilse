<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use \CaT\Ilse\Action\InitAppFolder;
use \CaT\Ilse\Aux\Filesystem;
use \CaT\Ilse\Aux\TaskLogger;

class InitAppFolderTest extends PHPUnit_Framework_TestCase {
	public function test_perform_exists() {
		$filesystem = $this->createMock(Filesystem::class);
		$task_logger = $this->createMock(TaskLogger::class);

		$action = new InitAppFolder(".ilse", "config.yaml", $filesystem, $task_logger);

		$filesystem
			->expects($this->atLeastOnce())
			->method("homeDirectory")
			->willReturn("HOME");

		$filesystem
			->expects($this->once())
			->method("exists")
			->with("HOME/.ilse")
			->willReturn(true);

		$filesystem
			->expects($this->exactly(0))
			->method("makeDirectory");	

		$action->perform();
	}

	public function test_perform_not_exists() {
		$filesystem = $this->createMock(Filesystem::class);

		$task_logger = $this->createMock(TaskLogger::class);
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

		$action = new InitAppFolder(".ilse", "config.yaml", $filesystem, $task_logger);

		$filesystem
			->expects($this->atLeastOnce())
			->method("homeDirectory")
			->willReturn("HOME");

		$filesystem
			->expects($this->once())
			->method("exists")
			->with("HOME/.ilse")
			->willReturn(false);

		$filesystem
			->expects($this->once())
			->method("makeDirectory")
			->with("HOME/.ilse");

		$filesystem
			->expects($this->once())
			->method("read")
			->with($this->stringContains("ilse_default_config.yaml"))
			->willReturn("DEFAULT_CONFIG");

		$filesystem
			->expects($this->once())
			->method("write")
			->with("HOME/.ilse/config.yaml", "DEFAULT_CONFIG");

		$action->perform();
	}
}
