<?php
require_once(__DIR__ . "/../vendor/autoload.php");
require_once("GitTest.php");

/**
 * Test class for git commands
 * 
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 */
class GitStandardTest extends GitTest
{
	public function getImplementation()
	{
		return new \CaT\Ilse\Git\GitWrapper(sys_get_temp_dir(), "https://github.com/daniel4w/DWLibrary.git");
	}

	public function getExceptionImplementation()
	{
		return new \CaT\Ilse\Git\GitWrapper("www/falscheAdresse/de", "httpsss://testbla");
	}
}