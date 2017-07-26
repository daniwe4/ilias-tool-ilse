<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;
/**
 * checks requirements for ILIAS installation
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class IliasRequirementChecker implements \CaT\Ilse\Interfaces\RequirementChecker {
	/**
	 * @inheritdocs
	 */
	public function dataDirectoryExists($path) {
		assert('is_string($path)');

		return is_dir($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryPermissions($path) {
		assert('is_string($path)');

		return is_writable($path);
	}

	/**
	 * @inheritdocs
	 */
	public function dataDirectoryEmpty($path, $client) {
		assert('is_string($path)');
		assert('is_string($client)');

		if(!is_dir($path."/".$client)) {
			return true;
		}

		if($d = dir($path."/".$client)) {
			while($n = $d->read() ) {
				if($n == '.' OR $n == '..') {
					continue;
				}

				$d->close();
				return false;
			}
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function validPHPVersion($phpversion, $required) {
		assert('is_string($phpversion)');
		assert('is_string($required)');

		return $phpversion >= $required;
	}

	public function phpVersionILIASBranchCompatible($phpversion, $branch_name) {
		assert('is_string($phpversion)');
		assert('is_string($branch_name)');

		if($phpversion >= "7.1" && $branch_name != "trunk") {
			return false;
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function pdoExist() {
		return class_exists("PDO");
	}

	/**
	 * @inheritdocs
	 */
	public function databaseConnectable($host, $user, $passwd) {
		assert('is_string($host)');
		assert('is_string($user)');
		assert('is_string($passwd)');

		try{
			$dsn = 'mysql:host=' . $host . ';charset=utf8';
			$this->pdo = new \PDO($dsn, $user, $passwd, array(3=>2, 10000=>true, 2=>18000));
		} catch(Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * @inheritdocs
	 */
	public function logDirectoryExists($path) {
		assert('is_string($path)');

		return is_dir($path);
	}

	/**
	 * @inheritdocs
	 */
	public function logFileExists($path, $file_name) {
		assert('is_string($path)');
		assert('is_string($file_name)');

		return file_exists($path."/".$file_name);
	}
}