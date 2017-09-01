<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array language()
 * @method array title()
 * @method array description()
 */
class TypeLanguageSettings extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("language" => array("string", false)
			,"title" => array("string", false)
			,"description" => array("string", false)
			);
	}
}