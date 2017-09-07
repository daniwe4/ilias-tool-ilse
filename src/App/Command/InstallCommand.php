<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Implementation of the install command
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
class InstallCommand extends BaseCommand
{
	/**
	 * Configure the command with description and help text
	 */
	protected function configure()
	{
		$this
			->setName("install")
			->setDescription("Start the installation.")
			->addArgument("config_names", InputArgument::IS_ARRAY, "Name of the Ilias Config Files.")
			;
	}

	/**
	 * Exexutes the command
	 *
	 * @param InputInterface 	$in
	 * @param OutputInterface 	$out
	 */
	protected function execute(InputInterface $in, OutputInterface $out)
	{
		$this->dic["aux.taskLogger"] = $this->buildTaskLogger($out);
		$config_names = $in->getArgument("config_names");
		$this->dic = $this->dic["aux.configLoader"]->loadConfigToDic($this->dic, $config_names);

		$init_app_folder = $this->dic["action.initAppFolder"];
		$init_app_folder->perform();

		$build_installation_environment = $this->dic["action.buildInstallationEnvironment"];
		$build_installation_environment->perform();

		$check_requirements = $this->dic["action.checkRequirements"];
		$check_requirements->perform();

		$install_ilias = $this->dic["action.installILIAS"];
		$install_ilias->perform();

		return;
	}

	/**
	 * Setup the environment
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function setup(array $args)
	{
		$sp = new Executor\SetupEnvironment($args['config'], $this->checker, $this->git, $args['interactive'], $this->path);
		$sp->run();
	}

	/**
	 * Start the installation process
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function start(array $args)
	{
		$ii = new Executor\InstallILIAS($args['config'], $this->checker, $this->git, $this->path);
		$ii->run();
	}

	/**
	 * Configure the ILIAS environment
	 *
	 * @param string 		$cmd
	 */
	protected function config($cmd)
	{
		assert('is_string($cmd)');

		// A hack to avoid an ilLanguage error.
		// It runs config in an seperate process.
		$this->process->setCommandLine($cmd);
		$this->process->setTty(true);
		$this->process->run();
	}
}
