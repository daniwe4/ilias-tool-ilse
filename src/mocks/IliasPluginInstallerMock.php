<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\mocks;
/**
 * implementation of plugin interface to install plugins
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 */
class IliasPluginInstallerMock implements \CaT\Ilse\Interfaces\Plugin {
	/**
	 * @inheritdoc
	 */
	public function install(\CaT\Ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function isInstalled(\CaT\Ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function update(\CaT\Ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function activate(\CaT\Ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function deactivate(\CaT\Ilse\Config\Plugin $plugin) {
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function updateLanguage(\CaT\Ilse\Config\Plugin $plugin) {
		return;
	}

	/**
	 * @inheritdoc
	 */
	public function getPluginObject($plugin_name) {
		return null;
	}

		/**
	 * @inheritdoc
	 */
	public function uninstall($plugin_name) {
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function needsUpdate(\ilPlugin $plugin) {
		return true;
	}
}