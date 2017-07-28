<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS certificate.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string enable()
 */
class Certificate extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "enable"	=> array("int", false)
			);
	}
}