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
	const MAX_LENGTH = 100;

	const IN_PROGRESS = "in progress";
	const DONE = "<fg=green>DONE</>";
	const FAIL_SOFT = "<fg=yellow>FAIL</>";
	const FAIL_HARD = "<fg=red>FAIL</>";

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
		$title = $this->getIndentedTitle($title);
		$this->pushTitle($title);
		$this->writeLineHead($title);

		try
		{
			$result = $task();
			$this->popTitle(self::DONE);
			return $result;
		}
		catch(\Exception $e)
		{
			$this->popTitle(self::FAIL_HARD);
			throw $e;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function eventually($title, callable $task)
	{
		$title = $this->getIndentedTitle($title);
		$this->pushTitle($title);
		$this->writeLineHead($title);

		try
		{
			$result = $task();
			$this->popTitle(self::DONE);
			return $result;
		}
		catch(\Exception $e)
		{
			$this->popTitle(self::FAIL_SOFT);
			$this->out->write($e->getMessage());
			return null;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function progressing($title, callable $task)
	{

		$title = $this->getIndentedTitle($title);
		$this->pushTitle($title, true);
		$this->writeLineHead($title);
		$this->writeLineEnd($title, self::IN_PROGRESS);

		$this->writeLineEnd("","");

		try
		{
			$result = $task();
			$this->writeLineEnd("","");
			$this->popTitle(self::DONE);
			return $result;
		}
		catch(\Exception $e)
		{
			$this->writeLineEnd("","");
			$this->popTitle(self::FAIL_HARD);
			throw $e;
		}
	}

	private function getIndentedTitle($title) {
		return str_repeat(" ", count($this->titles_stack) * 4).$title;
	}

	private function writeLineHead($title) {
		$this->out->write("<fg=default>$title</>");
	}

	private function writeLineEnd($title, $end) {
		$length = strlen($title);
		$spaces = self::MAX_LENGTH - $length;
		if ($spaces <= 0) {
			$spaces = 5;
		}
		$this->out->write(str_repeat(" ", $spaces).$end, true);
	}

	protected $titles_stack = [];

	protected function pushTitle($title, $has_line_end = false) {
		if (count($this->titles_stack) > 0) {
			list($t, $p) = end($this->titles_stack);
			if (!$p) {
				$this->writeLineEnd($t, self::IN_PROGRESS);
				array_pop($this->titles_stack);
				$this->titles_stack[] = [$t, true];
			}
		}
		$this->titles_stack[] = [$title, $has_line_end];
	}

	protected function popTitle($end_text) {
		list($t, $p) = array_pop($this->titles_stack);
		if ($p) {
			$this->out->write($t);
		}
		$this->writeLineEnd($t, $end_text);
	}
}
