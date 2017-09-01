<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for the log of ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string path()
 * @method string fileName()
 * @method string errorLog()
 */
class Log extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "path"			=> array("string", false)
			, "file_name"		=> array("string", false)
			, "error_log"		=> array("string", false)
			);
	}
}