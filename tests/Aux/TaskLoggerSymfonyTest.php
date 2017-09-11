<?php

use Symfony\Component\Console\Output\ConsoleOutput;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Test class for TaskLoggerSymfony
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class TaskLoggerSymfonyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \CaT\Ilse\TaskLoggerSymfony
	 */
	protected $tls;

	/**
	 * Setup the testing environment
	 */
	public function setUp()
	{
		$this->tls = new \CaT\Ilse\Aux\TaskLoggerSymfony($this->getMockOut());
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function test_always($title, $closure, $result)
	{
		$value = $this->tls->always($title, $closure);
		$this->assertEquals($value, $result);
	}

	public function test_always_exception()
	{
		$raised = true;
		try
		{
			$res = $this->tls->always("title", function() {return 5/0;});
			$raised = false;
		}
		catch(\Exception $e)
		{
		}
		$this->assertTrue($raised);
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function test_eventually($title, $closure, $result)
	{
		$value = $this->tls->eventually($title, $closure);
		$this->assertEquals($value, $result);
	}

	/**
	 * @dataProvider eventually_exeption_provider
	 */
	public function test_eventually_exception($title, $closure, $result)
	{
		try
		{
			$res = $this->tls->eventually($title, $closure);
		}
		catch(\Exception $e)
		{
		}
		$this->assertEquals($res, $result);
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function test_progressing($title, $closure, $result)
	{
		$value = $this->tls->progressing($title, $closure);
		$this->assertEquals($value, $result);
	}

	public function test_progressing_exception()
	{
		$raised = true;
		try
		{
			$res = $this->tls->progressing("title", function() {return 5/0;});
			$raised = false;
		}
		catch(\Exception $e)
		{
		}
		$this->assertTrue($raised);
	}

	public function test_output_tree_always()
	{
		$output_interface = $this->createMock(OutputInterface::class);

		$task_logger = new \CaT\Ilse\Aux\TaskLoggerSymfony($output_interface);

		$output_interface
			->expects($this->exactly(6))
			->method("write")
			->withConsecutive
				( ["<fg=default>Step A</fg>", false, 0]
				, [$this->stringContains("in progress"), true]
				, ["<fg=default>    Step A.1</fg>", false, 0]
				, [$this->stringContains("DONE"), true]
				, ["Step A", false, 0]
				, [$this->stringContains("DONE"), true]
				);

		$task_logger->always("Step A", function() use ($task_logger) {
			$task_logger->always("Step A.1", function() {
				// do stuff
			});
		});
	}

	public function test_output_tree_eventually()
	{
		$output_interface = $this->createMock(OutputInterface::class);

		$task_logger = new \CaT\Ilse\Aux\TaskLoggerSymfony($output_interface);

		$output_interface
			->expects($this->exactly(6))
			->method("write")
			->withConsecutive
				( ["<fg=default>Step A</fg>", false, 0]
				, [$this->stringContains("IN PROGRESS"), true]
					, ["<fg=default>    Step A.1</fg>", false, 0]
				, [$this->stringContains("DONE"), true]
				, ["Step A", false, 0]
				, [$this->stringContains("DONE"), true]
				);

		$task_logger->eventually("Step A", function() use ($task_logger) {
			$task_logger->eventually("Step A.1", function() {
				// do stuff
			});
		});
	}

	private function getFunc($val_1, $val_2)
	{
		$func = function () use($val_1, $val_2) {
			return $val_1 / $val_2;
		};
		return $func;
	}

	public function dataProvider()
	{
		return array(array("Rhein", $this->getFunc(4, 2), 2),
					 array("Alle", $this->getFunc(0.75, 0.25), 3)
					);
	}

	public function eventually_exeption_provider()
	{
		return array(array("Test_1", $this->getFunc(4, 2), 2),
					 array("Test_2", $this->getFunc(4, 0), null)
					);
	}

	private function getMockOut()
	{
		return $this->getMockBuilder('Symfony\\Component\\Console\\Output\\OutputInterface')
					->getMock();
	}
}
