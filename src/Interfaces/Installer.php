<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Interfaces;

/**
 * Interface for installing ILIAS with a client.
 *
 * TODO: We might want to add comments for the methods which are allowed
 *       to use the global $ilDB to at least document the steps in the dance
 *       in comments.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
interface Installer {
	/**
	 * Write the client.ini.
	 *
	 * @return	null
	 */
	public function writeClientIni();

	/**
	 * Write the ilias.ini.
	 *
	 * @return	null
	 */
	public function writeIliasIni();

	/**
	 * Install Database.
	 *
	 * @return	null
	 */
	public function installDatabase();

	/**
	 * Open connection to the database.
	 *
	 * ATTENTION: This should also define the global $ilDB.
	 *
	 * @return	null
	 */
	public function connectDatabase();

	/**
	 * Get the handle to the database.
	 *
	 * @return	null
	 */
	public function getDatabaseHandle();

	/**
	 * Apply hotfixes to the database.
	 *
	 * @param	\ilDBUpdate		$db_updater
	 * @return	null
	 */
	public function applyHotfixes(\ilDBUpdate $db_updater);

	/**
	 * Apply updates to the database.
	 *
	 * @param	\ilDBUpdate		$db_updater
	 * @return	null
	 */
	public function applyUpdates(\ilDBUpdate $db_updater);

	/**
	 * Install languages.
	 *
	 * @param	ilLanguage		$lng	handles the installing process
	 * @return	null
	 */
	public function installLanguages(\ilLanguage $lng);

	/**
	 * Set the usage of a proxy.
	 *
	 * TODO: I guess this could have a better name like setProxySettings?
	 *
	 * @return	null
	 */
	public function setProxy();

	/**
	 * Do not register this installation for an ILIAS NIC.
	 *
	 * @return	null
	 */
	public function registerNoNic();

	/**
	 * Set the factory for password encoders.
	 *
	 * @param	ilUserPasswordEncoderFactory	$encoder_factory
	 * @return	null
	 */
	public function setPasswordEncoder(\ilUserPasswordEncoderFactory $encoder_factory);

	/**
	 * Finish the ILIAS setup process.
	 *
	 * @return	null
	 */
	public function finishSetup();
}
