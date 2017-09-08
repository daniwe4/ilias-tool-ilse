<?php
/* Copyright (c) 2017 Daniel Weise <daniel.weise@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use CaT\Ilse\Executor;

/**
 * Implementation of the delete command
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
class DeleteCommand extends BaseCommand
{
	/**
	 * Configure the command with description and help text
	 */
	protected function configure()
	{
		$this
			->setName("delete")
			->setDescription("Delete the ILIAS installation")
			->addArgument("config_names", InputArgument::IS_ARRAY, "names of the ilse config files")
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
		$this->dic["aux.taskLogger"] = $this->buildTaskLogger($out);
		$config_names = $in->getArgument("config_names");
		$this->dic = $this->dic["aux.configLoader"]->loadConfigToDic($this->dic, $config_names);

		$init_app_folder = $this->dic["action.initAppFolder"];
		$init_app_folder->perform();

		$delete_ilias = $this->dic["action.deleteILIAS"];
		$delete_ilias->perform();
	}
}
