<?php
namespace CaT\Ilse;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskLoggerSymfony
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class TaskLoggerSymfony
{
	const MAX_LENGTH = 80;

	/**
	 * Constructor of TaskLoggerSymfony
	 */
	public function __construct(OutputInterface $out)
	{
		$this->out = $out;
	}

	/**
	 * inheritdoc
	 */
	public function always($title, \Closure $task)
	{
		$this->out->write($title);
		$this->writeSpaces($title);
		try
		{
			$result = $task();
		}
		catch(\Exception $e)
		{
			$this->out->write("<fg=red>FAIL</>", true);
			throw $e;
		}
		$this->out->write("<fg=green>DONE</>", true);
		return $result;

	}

	/**
	 * inheritdoc
	 */
	public function eventually($title, \Closure $task)
	{
		$this->out->write($title);
		$this->writeSpaces($title);
		try
		{
			$result = $task();
		}
		catch(\Exception $e)
		{
			$this->out->write("<fg=yellow>FAIL</>", true);
			return;
		}
		$this->out->write("<fg=green>DONE</>", true);
		return $task();
	}

	/**
	 * Write MAX_LENGTH spaces minus title length
	 *
	 * @param string 	$title
	 */
	private function writeSpaces($title)
	{
		$length = strlen($title);
		$spaces = self::MAX_LENGTH - $length;
		$this->out->write(str_repeat(" ", $spaces));
	}
}