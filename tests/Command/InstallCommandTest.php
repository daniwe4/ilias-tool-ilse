<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use \CaT\Ilse\App\Command\InstallCommand;
use \CaT\Ilse\Aux\TaskLogger;
use \CaT\Ilse\Aux\ConfigLoader;
use \CaT\Ilse\Action;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommandForTest extends InstallCommand {
	public $task_logger = null;
	public function buildTaskLogger(OutputInterface $out) {
		return $this->task_logger;
	}
	public function _execute(InputInterface $in, OutputInterface $out) {
		return $this->execute($in, $out);
	}
}

class InstallCommandTest extends PHPUnit_Framework_TestCase {

	public function test_execute() {
		$in = $this->createMock(InputInterface::class);
		$out = $this->createMock(OutputInterface::class);
		$task_logger = $this->createMock(TaskLogger::class);
		$config_loader = $this->createMock(ConfigLoader::class);

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

		$configs = ["CONFIG"];
		$in
			->expects($this->once())
			->method("getArgument")
			->with("config_names")
			->willReturn($configs);

		$config_loader
			->expects($this->once())
			->method("loadConfigToDic")
			->with($this->anything(), $configs);

		$init_app_folder = $this->createMock(Action\InitAppFolder::class);
		$init_app_folder
			->expects($this->once())
			->method("perform");
		$build_installation_environment = $this->createMock(Action\BuildInstallationEnvironment::class);
		$build_installation_environment
			->expects($this->once())
			->method("perform");
		$check_requirements = $this->createMock(Action\CheckRequirements::class);
		$check_requirements
			->expects($this->once())
			->method("perform");

		$dic["action.initAppFolder"] = $init_app_folder;
		$dic["action.buildInstallationEnvironment"] = $build_installation_environment;
		$dic["action.checkRequirements"] = $check_requirements;
		$dic["aux.configLoader"] = $config_loader;
	
		$command = new InstallCommandForTest($dic);
		$command->task_logger = $task_logger;
		$command->_execute($in, $out);
	}
}
