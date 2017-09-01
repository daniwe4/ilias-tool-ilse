<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for JavaServer.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string host()
 * @method int port()
 * @method string indexPath()
 * @method string logFile()
 * @method string logLevel()
 * @method int numThreads()
 * @method int maxFileSize()
 * @method string iniPath()
 */
class JavaServer extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "host" => array("string", false)
			, "port" => array("int", false)
			, "index_path" => array("string", false)
			, "log_file" => array("string", false)
			, "log_level" => array("string", false)
			, "num_threads" => array("int", false)
			, "max_file_size" => array("int", false)
			, "ini_path" => array("string", false)
			);
	}
}