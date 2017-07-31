<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for the Server ILIAS runs on.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method int changeOnFirstLogin()
 * @method int useSpecialChars()
 * @method int numbersAndChars()
 * @method int minLength()
 * @method int maxLength()
 * @method int numUpperChars()
 * @method int numLowerChars()
 * @method int expireInDays()
 * @method int forgotPasswordAktive()
 * @method int maxNumLoginAttempts()
 */
class PasswordSettings extends Base {

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array(
			  "change_on_first_login"	=> array("int", false)
			, "use_special_chars"		=> array("int", false)
			, "numbers_and_chars"		=> array("int", false)
			, "min_length"				=> array("int", false)
			, "max_length"				=> array("int", false)
			, "num_upper_chars"			=> array("int", false)
			, "num_lower_chars"			=> array("int", false)
			, "expire_in_days"			=> array("int", false)
			, "forgot_password_aktive"	=> array("int", false)
			, "max_num_login_attempts"	=> array("int", false)
			);
	}

	public static $yes_or_no = array(
			0
			,1
		);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "use_special_chars":
			case "numbers_and_chars":
			case "change_on_first_login":
			case "forgot_password_aktive":
				return $this->checkContentValueInArray($value, self::$yes_or_no);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}
