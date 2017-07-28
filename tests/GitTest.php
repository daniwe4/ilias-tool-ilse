<?php
require_once(__DIR__ . "/../vendor/autoload.php");

/**
 * Test class for git commands
 * 
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
abstract class GitTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @return CaT\Ilse\Git\Git
	 */
	abstract public function getImplementation();

	/**
	 * @var CaT\Ilse\Git\Git
	 */
	protected static $gw;

	/**
	 * Setup the testing environment
	 */
	public function setUp()
	{
		self::$gw = $this->getImplementation();
	}

	/**
	 * Test the gitClone method
	 */
	public function test_gitClone()
	{
		self::$gw->gitClone();
		$this->assertFileExists(self::$gw->gitGetPath() . "/DWLibrary/run_tests.sh");
	}

	/**
	 * Test the gitFetch method
	 */
	public function test_gitFetch()
	{
		$result = self::$gw->gitFetch();
		$this->assertEquals($result, 1);
	}

	/**
	 * Test the gitPull method
	 */
	public function test_gitPull()
	{
		$result = self::$gw->gitPull();
		$this->assertEquals($result, 1);
	}

	/**
	 * Test the gitCheckout method
	 */
	public function test_gitCheckout()
	{
		foreach($this->getCheckoutProvider() as $provider)
		{
			$result = self::$gw->gitCheckout($provider[0], $provider[1]);
			$this->assertEquals($result, $provider[2]);
		}
	}

	/**
	 * Test the gitGetBranches method
	 */
	public function test_gitGetBranches()
	{
		$branches = self::$gw->gitGetBranches();
		$this->assertContains("test", $branches);
	}

	/**
	 * Provides data for the gitCheckout test
	 */
	protected function getCheckoutProvider()
	{
		return [["test", true, true],
				["blow", true, true],
				["test", false, true]];
	}

	/**
	 * Do cleanup
	 */
	public static function tearDownAfterClass()
	{
		echo "\nRemoving ". self::$gw->gitGetPath().'/'.self::$gw->gitGetName();
		exec("rm -rf " . self::$gw->gitGetPath().'/'.self::$gw->gitGetName());
	}
}