<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for the tools required by ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string convert()
 * @method string zip()
 * @method string unzip()
 * @method string java()
 */
class Tools extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "convert"	=> array("string", false)
			, "zip"		=> array("string", false)
			, "unzip"	=> array("string", false)
			, "java"	=> array("string", false)
			);
	}
}
