<?php
namespace CaT\Ilse\Aux\ILIAS;

use CaT\Ilse\Config\Server;
use CaT\Ilse\Aux\Filesystem;

/**
 * Class PluginInfoReader52
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
class PluginInfoReader52 implements PluginInfoReader
{
	const CLASS_FOLDER = "classes";
	const FILENAME_PREFIX = "class.il";
	const FILENAME_SUFFIX = "Plugin.php";

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * Constructor of the class PluginInfoReader52
	 */
	public function __construct(Server $server, Filesystem $filesystem)
	{
		$this->server = $server;
		$this->filesystem = $filesystem;
	}

	/**
	 * @inheritdoc
	 */
	public function readInfo($path)
	{
		assert('is_string($path)');

		if (!$this->filesystem->isDirectory($path)) {
			throw new \RuntimeException(
				"Cannot read plugin info from inexisting directory '$path'");
		}

		$filelist = $this->filesystem->getFilesInFolder($path."/".self::CLASS_FOLDER);
		$plugin_name = $this->getPluginName($filelist);

		$object = $this->getPluginInstance($path, $plugin_name);

		return new PluginInfo(
			$object->getComponentType(),
			$object->getComponentName(),
			$object->getSlot(),
			$object->getSlotId(),
			$plugin_name
		);
	}

	/**
	 * Get an instance of the plugin file.
	 *
	 * @param 	string 	$path
	 * @param 	string 	$plugin_name
	 * @return 	object
	 */
	protected function getPluginInstance($path, $plugin_name)
	{
		assert('is_string($path)');
		assert('is_string($plugin_name)');

		$cur = getcwd();
		try {
			chdir($this->server->absolute_path());
			require_once(
				$path.
				"/".
				self::CLASS_FOLDER.
				"/".
				self::FILENAME_PREFIX.
				$plugin_name.
				self::FILENAME_SUFFIX
			);
			$plugin = new \ReflectionClass("il".$plugin_name."Plugin");
			return $plugin->newInstanceWithoutConstructor();
		}
		finally {
			chdir($cur);
		}
	}

	/**
	 * Get the plugin name from the plugin file in the given plugin folder.
	 *
	 * @param 	string[] 	$list 	List of files in the plugin classes folder.
	 * @throws 	\Exception
	 * @return 	string
	 */
	protected function getPluginName($list)
	{
		$regexp = '/(\s*class\.il(.*)Plugin\.php\s*)/';
		foreach($list as $item) {
			$groups = [];
			if (preg_match($regexp, $item, $groups)) {
				return $groups[2];
			}
		}
		throw new \Exception("No Pluginname found in list ".$list);
	}
}
