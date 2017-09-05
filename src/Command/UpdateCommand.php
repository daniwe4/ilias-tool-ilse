<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use CaT\Ilse\Executor;

/**
 * Implementation of the update command
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
class UpdateCommand extends BaseCommand
{
	/**
	 * Configure the command with description and help text
	 */
	protected function configure()
	{
		$this
			->setName("update")
			->setDescription("Update the Ilias-Environment.")
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
		$config_names = $in->getArgument("config_names");
		$args["config"] = $this->merge($config_names);

		$this->update($args);
		$out->writeln("\t\t\t\tDone!");
	}

	/**
	 * Setup the environment
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function setup(array $args)
	{
		$sp = new Executor\SetupEnvironment($args['config'], $this->checker, $this->git, false, $this->path);
		$sp->run();
	}

	/**
	 * Start the update configuration process of ILIAS
	 *
	 * @param ["param_name" => param_value] 	$args
	 */
	protected function update(array $args)
	{
		$u = new Executor\UpdateILIAS($args['config'], $this->checker, $this->git, $this->path);
		$u->run();
	}
}