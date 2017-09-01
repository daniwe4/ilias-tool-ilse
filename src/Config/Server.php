<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for the Server ILIAS runs on.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string httpPath()
 * @method string absolutePath()
 * @method string timezone()
 */
class Server extends Base {
	const URL_REG_EX = "/^^(https:\/\/|http:\/\/)/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "http_path"		=> array("string", false)
			, "absolute_path"	=> array("string", false)
			, "timezone"		=> array("string", false)
			);
	}

	public static $valid_timezones = array(
			"Europe/Berlin"
			,"Europe/Bern"
		);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "timezone":
				return $this->checkContentValueInArray($value, self::$valid_timezones);
			case "http_path":
				return $this->checkContentPregmatch($value, self::URL_REG_EX);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}
