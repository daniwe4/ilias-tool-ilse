<?php
namespace CaT\Ilse\Aux;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TaskLoggerSymfony
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class TaskLoggerSymfony implements TaskLogger
{
	const MAX_LENGTH = 80;
	protected $titles = [];

	/**
	 * Constructor of TaskLoggerSymfony
	 */
	public function __construct(OutputInterface $out)
	{
		$this->out = $out;
	}

	/**
	 * @inheritdoc
	 */
	public function always($title, callable $task)
	{
		if (count($this->titles) > 0) {
			$last = end($this->titles);
			$this->writeLineEnd($last, "IN PROGRESS");
		}

		$title = str_repeat(" ", count($this->titles) * 4).$title;

		$this->out->write($title);

		try
		{
			$this->titles[] = $title;
			$result = $task();
		}
		catch(\Exception $e)
		{
			$this->out->write("<fg=red>FAIL</>", true);
			throw $e;
		}
		finally 
		{
			array_pop($this->titles);
		}

		if (count($this->titles) == 0) {
			$this->out->write($title);
		}

		$this->writeLineEnd($title, "<fg=green>DONE</>");

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function eventually($title, callable $task)
	{
		if (count($this->titles) > 0) {
			$last = end($this->titles);
			$this->writeLineEnd($last, "IN PROGRESS");
		}

		$title = str_repeat(" ", count($this->titles) * 4).$title;

		$this->out->write($title);

		try
		{
			$this->titles[] = $title;
			$result = $task();
			$failed = false;
		}
		catch(\Exception $e)
		{
			$failed = true;
		}
		finally
		{
			array_pop($this->titles);
		}

		if (count($this->titles) == 0) {
			$this->out->write($title);
		}

		if ($failed)
		{
			$this->out->write("<fg=yellow>FAIL</>", true);
			return null;
		}

		$this->writeLineEnd($title, "<fg=green>DONE</>");
		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function progressing($title, callable $task)
	{
		$this->out->write($title);
		$this->writeSpaces($title);
		$this->out->write("<fg=orange>in progress</>", true);
		try
		{
			$result = $task();
		}
		catch(\Exception $e)
		{
			$this->out->write("<fg=red>FAIL</>", true);
			throw $e;
		}
		$this->out->write($title);
		$this->writeSpaces($title);
		$this->out->write("<fg=green>DONE</>", true);
		return $result;
	}

	private function writeLineEnd($title, $end) {
		$length = strlen($title);
		$spaces = self::MAX_LENGTH - $length;
		$this->out->write(str_repeat(" ", $spaces).$end, true);
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
