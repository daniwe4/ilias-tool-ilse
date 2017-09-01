<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array orgunitTypes()
 */
class OrgunitTypes extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("orgunit_types" => array(array("\\CaT\\Ilse\\Config\\OrgunitType"), false)
			);
	}
}