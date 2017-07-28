<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for the HTTPS Auto Detect ILIAS runs on.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string enabled()
 * @method string headerName()
 * @method string headerValue()
 */
class HTTPSAutoDetect extends Base {
	const URL_REG_EX = "/^(https:\/\/|http:\/\/)/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "enabled"		=> array("int", false)
			, "header_name"	=> array("string", true)
			, "header_value"	=> array("string", true)
			);
	}

	public static $valid_enables = array(
			0
			,1
		);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "enabled":
				return $this->checkContentValueInArray($value, self::$valid_enables);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}
