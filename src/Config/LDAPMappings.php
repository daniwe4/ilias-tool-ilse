<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for one client of ILIAS.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string firstname()
 * @method string lastname()
 * @method string department()
 * @method string email()
 * @method string fax()
 * @method string gender()
 * @method string hobby()
 * @method string institution()
 * @method string matriculation()
 * @method string phoneHome()
 * @method string phoneMobile()
 * @method string phoneOffice()
 * @method string street()
 * @method string title()
 * @method string zipcode()
 * @method string country()
 */
class LDAPMappings extends Base {
	const SERVER_REGEX = "/^(ldap:\/\/)/";

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "firstname"		=> array("string", true)
			, "lastname"		=> array("string", true)
			, "department"		=> array("string", true)
			, "email"			=> array("string", true)
			, "fax"				=> array("string", true)
			, "gender"			=> array("string", true)
			, "hobby"			=> array("string", true)
			, "institution"		=> array("string", true)
			, "matriculation"	=> array("string", true)
			, "phone_home"		=> array("string", true)
			, "phone_mobile"	=> array("string", true)
			, "phone_office"	=> array("string", true)
			, "street"			=> array("string", true)
			, "title"			=> array("string", true)
			, "zipcode"			=> array("string", true)
			, "city"			=> array("string", true)
			, "country"			=> array("string", true)
			);
	}

	public function getAvailableMappings() {
		return array_keys(self::fields());
	}
}
