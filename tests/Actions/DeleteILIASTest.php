<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

use \CaT\Ilse\Action\DeleteILIAS;
use \CaT\Ilse\Config;
use \CaT\Ilse\Filesystem;

// If database had it own interface like filesystem, we could
// drop this and write a proper test instead.
class DeleteILIASForTest extends DeleteILIAS {
	public $drop_database_called = 0;
	protected function dropDatabase() {
		$this->drop_database_called++;
	}
}

class DeleteILIASTest extends PHPUnit_Framework_TestCase {
	public function test_run() {
		$db_config = $this->createMock(Config\DB::class);
		$server_config = new Config\Server("http://path", "absolute_path", "Europe/Berlin");
		$client_config = new Config\Client("data_dir", "name", "bcrypt", 32);
		$log_config = new Config\Log("path", "filename", "error_log");
		$filesystem = $this->createMock(Filesystem::class);

		$action = new DeleteILIASForTest($db_config, $server_config, $client_config, $log_config, $filesystem);

		$filesystem
			->expects($this->exactly(4))
			->method("remove")
			->withConsecutive
				( ["absolute_path"]
				, ["data_dir"]
				, ["path/filename"]
				, ["error_log"]
				);

		$action->run();

		$this->assertEquals(1, $action->drop_database_called);
	}
}
