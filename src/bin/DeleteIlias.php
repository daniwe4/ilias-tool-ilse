<?php
require_once(__DIR__.'/../../vendor/autoload.php');

class DeleteIlias {

	protected $general_config;
	protected $con;

	public function __construct($config_path) {
		assert('is_string($config_path)');

		if(!is_file($config_path)) {
			throw new Exception("No config file found. (Path: ".$config_path.")");
		}

		$yaml_string = file_get_contents($config_path);
		$parser = new \CaT\Ilse\YamlParser();
		$this->general_config = $parser->read_config($yaml_string, "\\CaT\\Ilse\\Config\\General");
	}

	public function run() {
		$this->connectDB();
		$this->dropDatabase();
		$this->deleteILIASFolder();
		$this->deleteDataFolder();
	}

	protected function connectDB() {
		echo "Connecting to MySQL...";
		$host = $this->general_config->database()->host();
		$user = $this->general_config->database()->user();
		$passwd = $this->general_config->database()->password();

		$this->con = new mysqli($host, $user, $passwd);
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	protected function dropDatabase() {
		echo "Droping database...";
		$database = $this->general_config->database()->database();
		$drop_query = "DROP DATABASE ".$database;

		if($this->dataBaseExist($database)) {
			if(!$this->con->query($drop_query)) {
				throw new Exception("Database could not be deleted. (Error: )");
			}
		}
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	protected function deleteILIASFolder() {
		echo "Deleting ILIAS files...";
		$this->clearDirectory($this->general_config->server()->absolutePath());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	protected function deleteDataFolder() {
		echo "Deleting data folder...";
		$this->clearDirectory($this->general_config->client()->dataDir()."/".$this->general_config->client()->name());
		echo "\t\t\t\t\t\t\t\t\t\t\t\tDone!\n";
	}

	protected function clearDirectory($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->clearDirectory("$dir/$file") : unlink("$dir/$file");
		}

		rmdir($dir);
	}

	protected function dataBaseExist($database) {
		$select = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$database."'";
		$res = $this->con->query($select);

		return $res->num_rows > 0;
	}
}