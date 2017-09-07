<?php
/* Copyright (c) 2016, 2017 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Setup;

/**
 * Interface for installing ILIAS with a client.
 *
 * TODO: If the methods in this class are parametrized on their
 * inputs (instead of using the config, which is invisible in this
 * interface) we could write test for proper usage of the config.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 * @author Richard Klees <richard.klees@concepts-and-training.de>
 */
interface CoreInstaller {
	/**
	 * Initialize environment for ilias setup.
	 *
	 * @return void
	 */
	public function initEnvironment();

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
	public function writeILIASIni();

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
	public function applyDatabaseUpdates();

	/**
	 * Apply hotfixes to the database.
	 *
	 * @return void
	 */
	public function applyDatabaseHotfixes();

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
