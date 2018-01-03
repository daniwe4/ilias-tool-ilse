<?php
namespace CaT\Ilse\Action;

use CaT\Ilse\Config\Server;
use CaT\Ilse\Config\Plugins;
use CaT\Ilse\Aux\Filesystem;
use CaT\Ilse\Aux\Git\GitFactory;
use CaT\Ilse\Aux\TaskLogger;
use CaT\Ilse\Aux\ILIAS\PluginInfoReaderFactory;
use CaT\Ilse\Aux\ILIAS\PluginInfo;

/**
 * Class UpdatePluginsDirectory
 *
 * @author Daniel Weise <daniel.weise@concepts-and-training.de>
 * @copyright Extended GPL, see LICENSE
 */
class UpdatePluginsDirectory implements Action
{
	use Plugin;

	const BRANCH = "master";

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var Plugins
	 */
	protected $plugins;

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @var GitFactory
	 */
	protected $git_factory;

	/**
	 * @var TaskLogger
	 */
	protected $task_logger;

	/**
	 * @var PluginInfoReaderFactory
	 */
	protected $reader_factory;

	/**
	 * @var ILIAS\PluginInfoReader
	 */
	protected $plugin_info_reader;

	/**
	 * @var UpdatePlugins
	 */
	protected $update_plugins;

	/**
	 * @var Yaml
	 */
	protected $parser;


	/**
	 * Constructor of the class UpdatePluginsDirectory
	 */
	public function __construct(
		Server $server,
		Plugins $plugins,
		Filesystem $filesystem,
		GitFactory $git_factory,
		TaskLogger $task_logger,
		PluginInfoReaderFactory $reader_factory,
		UpdatePlugins $update_plugins
	) {
		$this->server = $server;
		$this->plugins = $plugins;
		$this->filesystem = $filesystem;
		$this->git_factory = $git_factory;
		$this->task_logger = $task_logger;
		$this->reader_factory = $reader_factory;
		$this->update_plugins = $update_plugins;
	}

	/**
	 * @inheritdoc
	 */
	public function perform()
	{
		$this->initPluginDir();
		$this->clonePlugins();
		$this->updatePlugins();
		$this->deleteUnlistedPlugins();
		$this->linkPluginsToIlias();
	}

	/**
	 * Initialize the plugin directory.
	 *
	 * @throws \Exception
	 * @return void
	 */
	protected function initPluginDir()
	{
		if(!$this->filesystem->exists($this->plugins->dir())) {
			$this->task_logger->always("Make plugin directory", function () {
				$this->filesystem->makeDirectory($this->plugins->dir());
			});
		}
		$this->task_logger->always("Check write permissions", function() {
			if(!$this->filesystem->isWriteable($this->plugins->dir())) {
					throw new \Exception("No write permissions");
			}
		});
	}

	/**
	 * Clone plugins
	 *
	 * @return void
	 */
	protected function clonePlugins()
	{
		$installed_plugins = $this->getInstalledPlugins();
		$urls = $this->getRepoUrls();
		$this->task_logger->eventually("Clone new plugins", function () use($urls, $installed_plugins) {
			foreach ($urls as $url) {
				$name = $this->getRepoNameFromUrl($url);
				if(in_array($name, $installed_plugins)) {
					continue;
				}
				$this->filesystem->makeDirectory($this->plugins->dir()."/".$name);
				$git = $this->git_factory->getRepo($this->plugins->dir()."/".$name, $url);
				$this->task_logger->always("clone plugin $name", [$git, "gitClone"]);
			}
		});
	}

	/**
	 * Update plugins
	 *
	 * @return void
	 */
	protected function updatePlugins()
	{
		$urls = $this->getRepoUrls();
		$this->task_logger->eventually("Pull plugins", function () use($urls) {
			foreach ($urls as $url) {
				$name = $this->getRepoNameFromUrl($url);
				$git = $this->git_factory->getRepo($this->plugins->dir().'/'.$name, $url);
				$this->task_logger->always("pull plugin $name", function() use($git) {
					$git->gitPull(self::BRANCH);
				});
			}
		});
	}

	/**
	 * Delete plugins, that not listed in the config file
	 *
	 * @return void
	 */
	protected function deleteUnlistedPlugins()
	{
		$urls = $this->getRepoUrls();
		$installed_plugins = $this->getInstalledPlugins();
		$marked_plugins = $this->getUnlistedPlugins($installed_plugins, $urls);

		$this->task_logger->eventually("Delete plugins", function () use($marked_plugins) {
			foreach($marked_plugins as $marked_plugin) {
				$this->task_logger->always("delete plugin $marked_plugin", function() use($marked_plugin) {
					$pi = $this->getPluginInfo($this->plugins->dir().'/'.$marked_plugin);
					$link = $this->createPluginMetaData($pi);
					$this->update_plugins->uninstall($pi->getPluginName());
					$this->filesystem->remove($link['path'].'/'.$link['name']);
					$this->filesystem->remove($this->plugins->dir()."/".$marked_plugin);
				});
			}
		});
	}

	/**
	 * Link plugins to ilias
	 *
	 * @throws \Exception
	 * @return void
	 */
	protected function linkPluginsToIlias()
	{
		$this->task_logger->eventually("Link plugins", function () {
			$installed_plugins = $this->getInstalledPlugins();
			foreach($installed_plugins as $plugin) {
				$pi = $this->getPluginInfo($this->plugins->dir().'/'.$plugin);
				$link = $this->createPluginMetaData($pi);
				if(!$this->filesystem->exists($link['path'])) {
					$this->filesystem->makeDirectory($link['path']);
				}
				if(!$this->filesystem->isWriteable($link['path'])) {
					throw new \Exception("No write acces to ".$link['path'].".");
				}
				if(!$this->filesystem->isLink($link['path'].'/'.$link['name'])) {
					$this->task_logger->always("link plugin ".$pi->getPluginName(), function() use($plugin, $link) {
						$this->filesystem->symlink($this->plugins->dir()."/".$plugin, $link['path'].'/'.$link['name']);
					});
				}
			}
		});
	}

	/**
	 * Get the repo name
	 *
	 * @param 	string 	$url
	 * @return 	string
	 */
	public function getRepoNameFromUrl($url)
	{
		assert('is_string($url)');

		$lastslash = strrpos($url, '/');
		$result = substr($url, $lastslash+1);
		$git = strpos($result, ".git");
		if($git) {
			$result = substr($result, 0, $git);
		}
		return $result;
	}

	/**
	 * Get unlisted plugins
	 *
	 * @param 	array 	$installed
	 * @param 	array 	$listed
	 * @return 	string[]
	 */
	public function getUnlistedPlugins(array $installed, array $listed)
	{
		$listed = array_map(function($url) { return $this->getRepoNameFromUrl($url); }, $listed);
		return array_diff($installed, $listed);
	}

	/**
	 * Get an array with the ilias path for a plugin and its name
	 *
	 * @param 	PluginInfo 	$info
	 * @return 	string[]
	 */
	public function createPluginMetaData(PluginInfo $info)
	{
		$link = array();
		$absolute_path = $this->server->absolute_path();
		$plugin_default_path = "Customizing/global/plugins";

		$link['path'] =
			$absolute_path."/".
			$plugin_default_path."/".
			$info->getComponentType()."/".
			$info->getComponentName()."/".
			$info->getSlot();
		$link['name'] = $info->getPluginName();

		return $link;
	}
}