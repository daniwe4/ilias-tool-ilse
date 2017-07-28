<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Single User Accounts.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array login()
 * @method array firstname()
 * @method array lastname()
 * @method array gender()
 * @method array email()
 * @method array role()
 */
class User extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("login" => array("string", false)
		   , "firstname" => array("string", false)
		   , "lastname" => array("string", false)
		   , "gender" => array("string", false)
		   , "email" => array("string", false)
		   , "role" => array("string", false)
			);
	}

	protected static $valid_gender = array("w","m");

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "gender":
				return $this->checkContentValueInArray($value, self::$valid_gender);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}