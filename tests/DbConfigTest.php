<?php

use \CaT\Ilse\Config\DB;

class DbConfigTest extends PHPUnit_Framework_TestCase {
	public function test_not_enough_params() {
		try {
			$config = new DB();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	/**
	 * @dataProvider	DBConfigValueProvider
	 */
	public function test_DBConfig($host, $database, $user, $password, $engine, $encoding, $create_db, $valid) {
		if ($valid) {
			$this->_test_valid_DBConfig($host, $database, $user, $password, $engine, $encoding, $create_db, $valid);
		}
		else {
			$this->_test_invalid_DBConfig($host, $database, $user, $password, $engine, $encoding, $create_db, $valid);
		}
	}

	public function _test_valid_DBConfig($host, $database, $user, $password, $engine, $encoding, $create_db) {
		$config = new DB($host, $database, $user, $password, $engine, $encoding, $create_db);
		$this->assertEquals($host, $config->host());
		$this->assertEquals($database, $config->database());
		$this->assertEquals($user, $config->user());
		$this->assertEquals($password, $config->password());
		$this->assertEquals($engine, $config->engine());
		$this->assertEquals($encoding, $config->encoding());
	}

	public function _test_invalid_DBConfig($host, $database, $user, $password, $engine, $encoding, $create_db) {
		try {
			$config = new DB($host, $database, $user, $password, $engine, $encoding, $create_db);
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function DBConfigValueProvider() {
		$ret = array();
		$take_it = 0;
		$take_every_Xth = 10;
		foreach ($this->hostProvider() as $host) {
			foreach ($this->databaseProvider() as $database) {
				foreach ($this->userProvider() as $user) {
					foreach ($this->passwordProvider() as $password) {
						foreach ($this->engineProvider() as $engine) {
							foreach ($this->encodingProvider() as $encoding) {
								foreach ($this->createDbProvider() as $create_db) {
									$take_it++;
									if (($take_it % $take_every_Xth) != 0) {
										continue;
									}
									$ret[] = array
										( $host[0], $database[0], $user[0], $password[0], $engine[0], $encoding[0], $create_db[0]
										, $host[1] && $database[1] && $user[1] && $password[1] && $engine[1] && $encoding[1] && $create_db[1]);
								}
							}
						}
					}
				}
			}
		}
		return $ret;
	}

	public function hostProvider() {
		return array
			( array("localhost", true)
			, array("127.0.0.1", true)
			, array("127.0.0.1.2", true)
			, array("orange", true)
			, array("server name", false)
			, array(1, false)
			);
	}

	public function databaseProvider() {
		return array
			( array("ilias", true)
			, array("test", true)
			, array("ilias51", true)
			, array("ilias_neu", true)
			, array("il", true)
			, array(2, false)
			);
	}

	public function userProvider() {
		return array
			( array("ilias", true)
			, array("root", true)
			, array("admin_yeah", true)
			, array(3, false)
			);
	}

	public function passwordProvider() {
		return array
			( array("#Ea5489jZ", true)
			, array("2+bLV3926", true)
			, array("YCw/W9Whm", true)
			, array(4, false)
			);
	}

	public function engineProvider() {
		return array
			( array("innodb", true)
			, array("myisam", true)
			, array("foo", false)
			, array(5, false)
			);
	}

	public function encodingProvider() {
		// TODO: check which of these do really exists
		// TODO: do we need all possible encodings?
		// TODO: why would we want to set the encoding?
		return array
			( array("utf8_general_ci", true)
			, array("utf-8_wob", false)
			, array("iso", false)
			, array("utf8-irgendwas", false)
			, array("swedish-latin", false)
			, array("mein_eigenes", false)
			, array(6, false)
			);
	}

	public function createDbProvider() {
		return array
			( array(1, true)
			, array(0, true)
			, array("ja_mach", false)
			, array(array(), false)
			);
	}
}
