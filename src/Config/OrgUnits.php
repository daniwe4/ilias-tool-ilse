<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for OrgUnits.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array orgunits()
 */
class OrgUnits extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "orgunits" => array(array("\\CaT\\Ilse\\Config\\OrgUnit"), false)
			);
	}
}