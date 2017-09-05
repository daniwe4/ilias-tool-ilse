<?php
/**
 * Main entry point for Ilse
 *
 * @author Daniel Weise 	<daniel.weise@concepts-and-training.de>
 */
require_once(__DIR__."/vendor/autoload.php");


error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE && ~E_WARNING);

$app 		= new \CaT\Ilse\App\App();

// Do not run in unit testing context
if (stripos($_SERVER["SCRIPT_NAME"], "phpunit") === false) {
	$app->run();
}

