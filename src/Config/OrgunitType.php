<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for an ILIAS Plugin.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method array defaultLanguage()
 * @method array typeLanguageSettings()
 */
class OrgunitType extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			("default_language" => array("string", false)
			,"type_language_settings" => array(array("\\CaT\\Ilse\\Config\\TypeLanguageSettings"), false)
			);
	}
}