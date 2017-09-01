<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for one client of ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string name()
 * @method string server()
 * @method string basedn()
 * @method string conType()
 * @method string conUserDn()
 * @method string conUserPw()
 * @method string syncOnLogin()
 * @method string syncPerCron()
 * @method string userGroup()
 * @method string attrNameUser()
 * @method string protocolVersion()
 * @method string userSearchScope()
 * @method string registerRoleName()
 * @method CaT\\Ilse\\Config\\LDAPMappings mappings()
 */
class LDAP extends Base {
	const SERVER_REGEX = "/^(ldap:\/\/)/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "name"	=> array("string", false)
			, "server"	=> array("string", false)
			, "basedn"	=> array("string", false)
			, "con_type"	=> array("int", false)
			, "con_user_dn"	=> array("string", false)
			, "con_user_pw"	=> array("string", false)
			, "sync_on_login"	=> array("int", false)
			, "sync_per_cron"	=> array("int", false)
			, "user_group"	=> array("string", true)
			, "attr_name_user"	=> array("string", false)
			, "protocol_version"	=> array("int", false)
			, "user_search_scope"	=> array("int", false)
			, "register_role_name"	=> array("string", false)
			, "mappings"	=> array("CaT\\Ilse\\Config\\LDAPMappings", true)
			);
	}

	protected static $con_types = array
			( 0
			, 1
			);

	protected static $sync_types = array
			( 0
			, 1
			);

	protected static $protocol_versions = array
			( 2
			, 3
			);

	protected static $user_search_scopes = array
			( 0
			, 1
			);

	/**
	 * @inheritdocs
	 */
	protected function checkValueContent($key, $value) {
		switch($key) {
			case "con_type":
				return $this->checkContentValueInArray($value, self::$con_types);
			case "sync_on_login":
			case "sync_per_cron":
				return $this->checkContentValueInArray($value, self::$sync_types);
			case "protocol_version":
				return $this->checkContentValueInArray($value, self::$protocol_versions);
			case "server":
				return $this->checkContentPregmatch($value, self::SERVER_REGEX);
			case "user_search_scope":
				return $this->checkContentValueInArray($value, self::$user_search_scopes);
			default:
				return parent::checkValueContent($key, $value);
		}
	}
}
