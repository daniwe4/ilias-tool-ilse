<?php

/* Copyright (c) 2016, 2017 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

use CaT\Ilse\Aux\Filesystem;

/**
 * Initialize the folder of the app if it doesn't yet exist.
 */
class InitAppFolder implements Action
{
	/**
	 * @var	string
	 */
	protected $folder_name;

	/**
	 * @var	string
	 */
	protected $config_name;

	/**
	 * @var	Filesystem
	 */
	protected $filesystem;	

	/**
	 * @param	string		$folder_name
	 * @param	Filesystem $filesystem
	 */
	public function __construct($folder_name, $config_name, Filesystem $filesystem)
	{
		assert('is_string($folder_name)');
		assert('is_string($config_name)');
		$this->folder_name = $folder_name;
		$this->config_name = $config_name;
		$this->filesystem = $filesystem;
	}

	/**
	 * Delete ILIAS.
	 *
	 * @return	void
	 */
	public function perform() {
		$fs = $this->filesystem;
		$dir = $fs->homeDirectory()."/".$this->folder_name;
		if ($fs->exists($dir)) {
			return;
		}

		$fs->makeDirectory($dir);
		$default_config = $fs->read(__DIR__."/../../assets/ilse_default_config.yaml");
		$config_file = $dir."/".$this->config_name;
		$fs->write($config_file, $default_config); 
	}
}
