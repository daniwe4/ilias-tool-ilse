<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array orgunitTitle()
 * @method array orgunitTypeDefaultLanguage()
 * @method array orgunitTypeTitle()
 */
class OrgunitTypeAssignment extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("orgunit_title" => array("string", false)
			,"orgunit_type_default_language" => array("string", false)
			,"orgunit_type_title" => array("string", false)
			);
	}
}