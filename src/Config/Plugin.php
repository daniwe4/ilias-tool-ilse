<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method \CaT\Ilse\Config\Git name()
 * @method \CaT\Ilse\Config\Git git()
 */
class Plugin extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("name" => array("string", false)
			, "git" => array("\\CaT\\Ilse\\Config\\Git", false)
			);
	}
}