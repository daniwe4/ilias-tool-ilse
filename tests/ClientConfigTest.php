<?php

use \CaT\Ilse\Config\Client;

class ClientConfigTest extends PHPUnit_Framework_TestCase {
	public function test_not_enough_params() {
		try {
			$config = new Client();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	/**
	 * @dataProvider	ClientConfigValueProvider
	 */
	public function test_ClientConfig($data_dir, $name, $password_encoder, $session_expire, $valid) {
		if ($valid) {
			$this->_test_valid_ClientConfig($data_dir, $name, $password_encoder, $session_expire, $valid);
		}
		else {
			$this->_test_invalid_ClientConfig($data_dir, $name, $password_encoder, $session_expire, $valid);
		}
	}

	public function _test_valid_ClientConfig($data_dir, $name, $password_encoder, $session_expire) {
		$config = new Client($data_dir, $name, $password_encoder, $session_expire);
		$this->assertEquals($data_dir, $config->dataDir());
		$this->assertEquals($name, $config->name());
		$this->assertEquals($password_encoder, $config->passwordEncoder());
		$this->assertEquals($session_expire, $config->sessionExpire());
	}

	public function _test_invalid_ClientConfig($data_dir, $name, $password_encoder, $session_expire) {
		try {
			$config = new Client($data_dir, $name, $password_encoder, $session_expire);
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function ClientConfigValueProvider() {
		$ret = array();
		foreach ($this->dataDirProvider() as $data_dir) {
			foreach ($this->nameProvider() as $name) {
				foreach ($this->passwordEncoderProvider() as $password_encoder) {
					foreach ($this->sessionExpireProvider() as $session_expire) {
						$ret[] = array
							( $data_dir[0], $name[0], $password_encoder[0], $session_expire[0]
							, $data_dir[1] && $name[1] && $password_encoder[1] && $session_expire[1]);
					}
				}
			}
		}
		return $ret;
	}

	public function dataDirProvider() {
		// Second parameter encodes whether the value is a valid config.
		return array
			( array("/data_dir", true)
			, array(1, false)
			);
	}

	public function nameProvider() {
		return array
			( array("ILIAS", true)
			, array(2, false)
			);
	}

	public function passwordEncoderProvider() {
		return array
			( array("md5", true)
			, array("FOO", false)
			, array("bcrypt", true)
			, array(1, false)
			);
	}

	public function sessionExpireProvider() {
		return array(
				array(10, true)
				, array("10", false)
				, array(true, false)
				, array(array(), false)
			);
	}
}
