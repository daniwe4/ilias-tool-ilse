<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use CaT\Ilse\Executer;

/**
 * Implementation of the install command
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
class ReinstallCommand extends BaseCommand
{
	/**
	 * Configure the command with description and help text
	 */
	protected function configure()
	{
		$this
			->setName("reinstall")
			->setDescription("Reinstall the Ilias-Environment.")
			->addArgument("config_names", InputArgument::IS_ARRAY, "Name of the Ilias Config File.")
			->addOption("interactive", "i", InputOption::VALUE_NONE, "Set i to start the setup in interactiv mode.");
			;
	}

	/**
	 * Executes the command
	 *
	 * @param InputInterface 	$in
	 * @param OutputInterface 	$out
	 */
	protected function execute(InputInterface $in, OutputInterface $out)
	{
		$config_names = $in->getArgument("config_names");
		$args = ["config" => $this->merge($config_names),
				 "interactive" => $in->getOption("interactive")];

		$this->delete($args);
		$this->setup($args);
		$this->start($args);
		$this->config($args);
		$out->writeln("\t\t\t\tDone!");
	}

	/**
	 * Setup the environment
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function setup(array $args)
	{
		$sp = new Executer\SetupEnvironment($args['config'], $this->checker, $this->git, $args['interactive']);
		$sp->run();
	}

	/**
	 * Start the installation process
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function start(array $args)
	{
		$ii = new Executer\InstallILIAS($args['config'], $this->checker, $this->git);
		$ii->run();
	}

	/**
	 * Delete an ILIAS-Environment
	 */
	protected function delete(array $args)
	{
		$ri = new Executer\DeleteILIAS($args['config'], $this->checker, $this->git);
		$ri->run();
	}

	/**
	 * Start the configuration process of ILIAS
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function config(array $args)
	{
		$ci = new Executer\ConfigurateILIAS($args['config'], $this->checker, $this->git);
		$ci->run();
	}
}