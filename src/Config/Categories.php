<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for OrgUnits.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array categories()
 */
class Categories extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "categories" => array(array("\\CaT\\Ilse\\Config\\Category"), false)
			);
	}
}