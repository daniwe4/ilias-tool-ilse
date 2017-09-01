<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * General Configuration for an ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method \\CaT\\Ilse\\Config\\Client client()
 * @method \\CaT\\Ilse\\Config\\DB database()
 * @method \\CaT\\Ilse\\Config\\Language language()
 * @method \\CaT\\Ilse\\Config\\Server server()
 * @method \\CaT\\Ilse\\Config\\Setup setup()
 * @method \\CaT\\Ilse\\Config\\Tools tools()
 * @method \\CaT\\Ilse\\Config\\Log log()
 * @method \\CaT\\Ilse\\Config\\Git git()
 * @method \\CaT\\Ilse\\Config\\Categories category()
 * @method \\CaT\\Ilse\\Config\\OrgUnits orgunit()
 * @method \\CaT\\Ilse\\Config\\Roles role()
 * @method \\CaT\\Ilse\\Config\\LDAP ldap()
 * @method \\CaT\\Ilse\\Config\\Plugins plugin()
 * @method \\CaT\\Ilse\\Config\\HTTPSAutoDetect httpsAutoDetect()
 * @method \\CaT\\Ilse\\Config\\OrgunitTypes OrgunitType()
 * @method \\CaT\\Ilse\\Config\\OrgunitTypeAssignments OrgunitTypeAssignment()
 * @method \\CaT\\Ilse\\Config\\Users user()
 * @method \\CaT\\Ilse\\Config\\PasswordSettings passwordSettings()
 * @method \\CaT\\Ilse\\Config\\Editor editor()
 * @method \\CaT\\Ilse\\Config\\JavaServer javaServer()
 * @method \\CaT\\Ilse\\Config\\Certificate certificate()
 * @method \\CaT\\Ilse\\Config\\Soap soap()
 * @method \\CaT\\Ilse\\Config\\LearningProgress learningProgress()
 */
class General extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "client"					=> array("\\CaT\\Ilse\\Config\\Client", true)
			, "database"				=> array("\\CaT\\Ilse\\Config\\DB", true)
			, "language"				=> array("\\CaT\\Ilse\\Config\\Language", true)
			, "server"					=> array("\\CaT\\Ilse\\Config\\Server", true)
			, "setup"					=> array("\\CaT\\Ilse\\Config\\Setup", true)
			, "tools"					=> array("\\CaT\\Ilse\\Config\\Tools", true)
			, "log"						=> array("\\CaT\\Ilse\\Config\\Log", true)
			, "git"						=> array("\\CaT\\Ilse\\Config\\Git", true)
			, "category"				=> array("\\CaT\\Ilse\\Config\\Categories", true)
			, "orgunit"					=> array("\\CaT\\Ilse\\Config\\OrgUnits", true)
			, "role"					=> array("\\CaT\\Ilse\\Config\\Roles", true)
			, "ldap"					=> array("\\CaT\\Ilse\\Config\\LDAP", true)
			, "plugin"					=> array("\\CaT\\Ilse\\Config\\Plugins", true)
			, "https_auto_detect"		=> array("\\CaT\\Ilse\\Config\\HTTPSAutoDetect", true)
			, "orgunit_type" 			=> array("\\CaT\\Ilse\\Config\\OrgunitTypes", true)
			, "orgunit_type_assignment" => array("\\CaT\\Ilse\\Config\\OrgunitTypeAssignments", true)
			, "user" 					=> array("\\CaT\\Ilse\\Config\\Users", true)
			, "password_settings" 		=> array("\\CaT\\Ilse\\Config\\PasswordSettings", true)
			, "editor"					=> array("\\CaT\\Ilse\\Config\\Editor", true)
			, "java_server" 			=> array("\\CaT\\Ilse\\Config\\JavaServer", true)
			, "certificate" 			=> array("\\CaT\\Ilse\\Config\\Certificate", true)
			, "soap" 					=> array("\\CaT\\Ilse\\Config\\Soap", true)
			, "learning_progress" 		=> array("\\CaT\\Ilse\\Config\\LearningProgress", true)
			);
	}
}