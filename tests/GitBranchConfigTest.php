<?php

use \CaT\Ilse\Config\GitBranch;

class GitBranchConfigTest extends PHPUnit_Framework_TestCase {
	public function test_not_enough_params() {
		try {
			$config = new GitBranch();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	/**
	 * @dataProvider	GitBranchConfigValueProvider
	 */
	public function test_DBConfig($url, $branch, $valid) {
		if ($valid) {
			$this->_test_valid_GitBranchConfig($url, $branch);
		}
		else {
			$this->_test_invalid_GitBranchConfig($url, $branch);
		}
	}

	public function _test_valid_GitBranchConfig($url, $branch) {
		$config = new GitBranch($url, $branch, '');
		$this->assertEquals($url, $config->url());
		$this->assertEquals($branch, $config->branch());
	}

	public function _test_invalid_GitBranchConfig($url, $branch) {
		try {
			$config = new GitBranch($url, $branch, '');
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function GitBranchConfigValueProvider() {
		$ret = array();
		$take_it = 0;
		$take_every_Xth = 10;
		foreach ($this->urlProvider() as $url) {
			foreach ($this->branchNameProvider() as $branch) {
				$ret[] = array
					( $url[0], $branch[0]
					, $url[1] && $branch[1]);
			}
		}
		return $ret;
	}

	public function urlProvider() {
		return array(
				array("https://github.com/", true)
				, array("http://github.com/", true)
				, array("brot", true)
				, array(4, false)
				, array(true, false)
				, array(array(), false)
			);
	}

	public function branchNameProvider() {
		return array(
				array("testBranch", true)
				, array(4, false)
				, array(true, false)
				, array(array(), false)
			);
	}
}