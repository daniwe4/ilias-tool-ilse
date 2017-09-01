<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array enable()
 * @method array wdslPath()
 * @method array timeout()
 */
class Soap extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("enable" => array("int", false)
			,"wdsl_path" => array("string", false)
			,"timeout" => array("int", false)
			);
	}
}