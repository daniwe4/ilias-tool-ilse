<?php
/* Copyright (c) 2016, 2017 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Setup;

/**
 * Interface for installing ILIAS with a client.
 *
 * TODO: We might want to add comments for the methods which are allowed
 *       to use the global $ilDB to at least document the steps in the dance
 *       in comments.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 * @author Richard Klees <richard.klees@concepts-and-training.de>
 */
interface CoreInstaller {
	/**
	 * Write the client.ini.
	 *
	 * @return void
	 */
	public function writeClientIni();

	/**
	 * Write the ilias.ini.
	 *
	 * @return void
	 */
	public function writeIliasIni();

	/**
	 * Install Database.
	 *
	 * @return void
	 */
	public function installDatabase();

	/**
	 * Apply updates to the database.
	 *
	 * @return void
	 */
	public function applyUpdates();

	/**
	 * Apply hotfixes to the database.
	 *
	 * @return void
	 */
	public function applyHotfixes();

	/**
	 * Install languages.
	 *
	 * @return void
	 */
	public function installLanguages();

	/**
	 * Set the usage of a proxy.
	 *
	 * @return void
	 */
	public function setProxySettings();

	/**
	 * Finish the ILIAS setup process.
	 *
	 * @return void
	 */
	public function finishSetup();
}
