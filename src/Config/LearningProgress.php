<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for one client of ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method int enabled()
 * @method int anonym()
 * @method int timeSpan()
 * @method int extendedData()
 * @method int objectStatistics()
 * @method int sessionStatistics()
 * @method int ownLp()
 * @method int personalDesktop()
 */
class LearningProgress extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "enabled"				=> array("int", false)
			, "anonym"				=> array("int", true)
			, "time_span"			=> array("int", false)
			, "extended_data"		=> array("int", true)
			, "object_statistics"	=> array("int", true)
			, "session_statistics"	=> array("int", true)
			, "own_lp"				=> array("int", false)
			, "personal_desktop"	=> array("int", true)
			);
	}

	protected static $valid_bools = array
			( 0
			, 1
			);

	protected static $valid_extendes_value = array(
			0,
			1,
			2,
			4,
			3,
			5,
			6,
			7
		);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "time_span":
				return parent::checkValueContent($key, $value);
				break;
			case "extended_data":
				return $this->checkContentValueInArray($value, self::$valid_extendes_value);
			default:
				return $this->checkContentValueInArray($value, self::$valid_bools);
		}
	}
}
