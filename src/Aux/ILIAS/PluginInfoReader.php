<?php
namespace CaT\Ilse\Aux\ILIAS;

/**
 * Interface PluginInfoReader
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
interface PluginInfoReader
{
	/**
	 * Read the information needed for install a plugin.
	 *	- ComponetType
	 *  - ComponentName
	 *  - Slot
	 *  - PluginName
	 *
	 * @param 	string 	$path
	 * @return 	PluginInfo
	 */
	public function readInfo($path);
}