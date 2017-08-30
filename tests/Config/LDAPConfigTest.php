<?php

use \CaT\Ilse\Config\LDAP;

class LDAPConfigTest extends PHPUnit_Framework_TestCase{
	public function test_not_enough_params() {
		try {
			$config = new LDAP();
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	/**
	 * @dataProvider	LDAPConfigValueProvider
	 */
	public function test_LDAPConfig($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login
									, $sync_per_cron, $atrNameUser, $protocolVersion, $userSearchScope, $registerRoleName, $valid) 
	{
		if ($valid) {
			$this->_test_valid_LDAPConfig($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login
										, $sync_per_cron, $atrNameUser, $protocolVersion, $userSearchScope, $registerRoleName);
		}
		else {
			$this->_test_invalid_LDAPConfig($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login
										, $sync_per_cron, $atrNameUser, $protocolVersion, $userSearchScope, $registerRoleName);
		}
	}

	public function _test_valid_LDAPConfig($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login
											, $sync_per_cron, $atrNameUser, $protocolVersion, $userSearchScope, $registerRoleName) 
	{
		$config = new LDAP($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login, $sync_per_cron, "", $atrNameUser
							, $protocolVersion, $userSearchScope, $registerRoleName);
		$this->assertEquals($name, $config->name());
		$this->assertEquals($basedn, $config->basedn());
		$this->assertEquals($conType, $config->conType());
		$this->assertEquals($conUserDn, $config->conUserDn());
		$this->assertEquals($conUserPw, $config->conUserPw());
		$this->assertEquals($sync_on_login, $config->syncOnLogin());
		$this->assertEquals($sync_per_cron, $config->syncPerCron());
		$this->assertEquals("", $config->userGroup());
		$this->assertEquals($atrNameUser, $config->attrNameUser());
		$this->assertEquals($protocolVersion, $config->protocolVersion());
		$this->assertEquals($userSearchScope, $config->userSearchScope());
		$this->assertEquals($registerRoleName, $config->registerRoleName());
	}

	public function _test_invalid_LDAPConfig($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login
											, $sync_per_cron, $atrNameUser, $protocolVersion, $userSearchScope, $registerRoleName)
	{
		try {
			$config = new LDAP($name, $server, $basedn, $conType, $conUserDn, $conUserPw, $sync_on_login, $sync_per_cron, "", $atrNameUser
								, $protocolVersion, $userSearchScope, $registerRoleName);
			$this->assertFalse("Should have raised.");
		}
		catch (\InvalidArgumentException $e) {}
	}

	public function LDAPConfigValueProvider() {
		$ret = array();
		$take_it = 0;
		$take_every_Xth = 75000;
		foreach ($this->nameProvider() as $name) {
			foreach ($this->serverProvider() as $server) {
				foreach ($this->basednProvider() as $basedn) {
					foreach ($this->conTypeProvider() as $conType) {
						foreach ($this->conUserDnProvider() as $conUserDn) {
							foreach ($this->conUserPwProvider() as $conUserPw) {
								foreach ($this->syncOnLoginProvider() as $sync_on_login) {
									foreach ($this->syncPerCronProvider() as $sync_per_cron) {
										foreach ($this->attrNameUserProvider() as $atrNameUser) {
											foreach ($this->protocolVersionProvider() as $protocolVersion) {
												foreach ($this->userSearchScopeProvider() as $userSearchScope) {
													foreach ($this->registerRoleNameProvider() as $registerRoleName) {
														$take_it++;
														if($take_it == $take_every_Xth) {
															$ret[] = array
																( $name[0], $server[0], $basedn[0], $conType[0], $conUserDn[0], $conUserPw[0]
																	, $sync_on_login[0], $sync_per_cron, $atrNameUser[0], $protocolVersion[0], $userSearchScope[0]
																	, $registerRoleName[0]
																, $name[1] && $server[1] && $basedn[1] && $conType[1] && $conUserDn[1] 
																  && $conUserPw[1] && $sync_on_login[1] && $sync_per_cron && $atrNameUser[1] && $protocolVersion[1]
																  && $userSearchScope[1] && $registerRoleName[1]);

															$take_it = 0;
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $ret;
	}

	public function nameProvider() {
		return array(
				array("ldap", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}

	public function serverProvider() {
		return array(
				array("ldap://127.0.0.1:389", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}

		public function basednProvider() {
		return array(
				array("cn=user,dc=dvdom,dc=local", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}

	public function conTypeProvider() {
		return array(
				array(1, true)
				, array("2", false)
				, array(true, false)
				, array(0, true)
				, array(array(), false)
			);
	}

	public function conUserDnProvider() {
		return array(
				array("cn=ldap,cn=user,dc=dcdom,dc=local", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}

	public function conUserPwProvider() {
		return array(
				array("abcd", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}

	public function syncOnLoginProvider() {
		return array(
				array(0, true)
				, array("2", false)
				, array(true, false)
				, array(1, true)
				, array(array(), false)
			);
	}

	public function syncPerCronProvider() {
		return array(
				array(0, true)
				, array("2", false)
				, array(true, false)
				, array(1, true)
				, array(array(), false)
			);
	}

	public function attrNameUserProvider() {
		return array(
				array("sAMAccountName", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}

	public function protocolVersionProvider() {
		return array(
				array(2, true)
				, array("2", false)
				, array(true, false)
				, array(3, true)
				, array(array(), false)
			);
	}

	public function userSearchScopeProvider() {
		return array(
				array(1, true)
				, array("2", false)
				, array(true, false)
				, array(0, true)
				, array(array(), false)
			);
	}

	public function registerRoleNameProvider() {
		return array(
				array("User", true)
				, array(2, false)
				, array(true, false)
				, array(array(), false)
			);
	}
}
