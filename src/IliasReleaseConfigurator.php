<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse;
/**
 * implementation of an ilias configurator
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */

class IliasReleaseConfigurator implements \CaT\Ilse\Interfaces\Configurator {

	/**
	 * @var string
	 */
	protected $absolute_path;
	/**
	 * @var \ilDB
	 */
	protected $gDB;
	/**
	 * @var \ilSetting
	 */
	protected $gSetting;
	/**
	 * @var \ilObjUser
	 */
	protected $gUser;
	/**
	 * @var \ilTree
	 */
	protected $gTree;
	/**
	 * @var \ILIAS
	 */
	protected $gIlias;
	/**
	 * @var \ilRbacAdmin
	 */
	protected $gRbacadmin;

	/**
	 * @var Configurators\Users
	 */
	protected $users_configurator = null;
	/**
	 * @var Configurators\Roles
	 */
	protected $roles_configurator = null;
	/**
	 * @var Configurators\OrgUnits
	 */
	protected $orgunits_configurator = null;
	/**
	 * @var Configurators\Categories
	 */
	protected $categories_configurator = null;
	/**
	 * @var Configurators\LDAP
	 */
	protected $ldap_configurator = null;
	/**
	 * @var Configurators\Plugins
	 */
	protected $plugins_configurator = null;
	/**
	 * @var Configurators\Editor
	 */
	protected $editor_configurator = null;
	/**
	 * @var Configurators\JavaServer
	 */
	protected $javaserver_configurator = null;
	/**
	 * @var Configurators\Certificates
	 */
	protected $certificates_configurator = null;
	/**
	 * @var Configurators\Soap
	 */
	protected $soap_configurator = null;
	/**
	 * @var Configurators\LearningProgress
	 */
	protected $learning_progress_configurator = null;

	public function __construct($absolute_path, $client_id) {
		define ("CLIENT_ID", $client_id);
		define('IL_PHPUNIT_TEST', true);
		$_COOKIE["ilClientId"] = $client_id;

		$this->absolute_path = $absolute_path;
		$this->initIlias();

		global $ilDB, $tree, $ilUser, $rbacadmin, $ilSetting, $ilias;
		$this->gDB = $ilDB;
		$this->gTree = $tree;
		$this->gUser = $ilUser;
		$this->gRbacadmin = $rbacadmin;
		$this->gSetting = $ilSetting;
		$this->gIlias = $ilias;
	}

	/**
	 * @inheritdoc
	 */
	public function initIlias() {
		chdir($this->absolute_path);
		require_once($this->absolute_path."/Services/Context/classes/class.ilContext.php");
		require_once($this->absolute_path."/Services/Init/classes/class.ilInitialisation.php");

		//context unittest is not required an ilias authentication
		//we do not need any authentication to configure ILIAS
		\ilContext::init(\ilContext::CONTEXT_UNITTEST);
		\ilInitialisation::initILIAS();
	}

	/**
	 * @inheritdoc
	 */
	public function getUserConfigurator() {
		if($this->users_configurator === null) {
			$this->users_configurator = new Configurators\Users($this->absolute_path, $this->gIlias, $this->gUser, $this->gRbacadmin, $this->gDB);
		}
		return $this->users_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getRolesConfigurator() {
		if($this->roles_configurator === null) {
			$this->roles_configurator = new Configurators\Roles($this->absolute_path, $this->gRbacadmin);
		}
		return $this->roles_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getOrgUnitsConfigurator() {
		if($this->orgunits_configurator === null) {
			$this->orgunits_configurator = new Configurators\OrgUnits($this->absolute_path, $this->gDB);
		}
		return $this->orgunits_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getCategoriesConfigurator() {
		if($this->categories_configurator === null) {
			$this->categories_configurator = new Configurators\Categories($this->absolute_path, $this->gTree);
		}
		return $this->categories_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getLDAPConfigurator() {
		if($this->ldap_configurator === null) {
			$this->ldap_configurator = new Configurators\LDAP($this->absolute_path, $this->gDB);
		}
		return $this->ldap_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getPluginsConfigurator() {
		if($this->plugins_configurator === null) {
			$this->plugins_configurator = new Configurators\Plugins($this->absolute_path, $this->gDB);
		}
		return $this->plugins_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getEditorConfigurator() {
		if($this->editor_configurator === null) {
			$this->editor_configurator = new Configurators\Editor($this->absolute_path, $this->gDB, $this->gSetting);
		}
		return $this->editor_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getJavaServerConfigurator() {
		if($this->javaserver_configurator === null) {
			$this->javaserver_configurator = new Configurators\JavaServer($this->absolute_path, $this->gSetting);
		}
		return $this->javaserver_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getCertificatesConfigurator() {
		if($this->certificates_configurator === null) {
			$this->certificates_configurator = new Configurators\Certificates($this->absolute_path);
		}
		return $this->certificates_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getSoapConfigurator() {
		if($this->soap_configurator === null) {
			$this->soap_configurator = new Configurators\Soap($this->absolute_path, $this->gSetting);
		}
		return $this->soap_configurator;
	}

	/**
	 * @inheritdoc
	 */
	public function getLearningProgressConfigurator() {
		if($this->learning_progress_configurator === null) {
			$this->learning_progress_configurator = new Configurators\LearningProgress($this->absolute_path, $this->gSetting);
		}
		return $this->learning_progress_configurator;
	}
}