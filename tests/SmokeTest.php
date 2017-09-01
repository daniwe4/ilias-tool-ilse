<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

// TODO: currently this does not worl because ilse relies on some config file in the
// users home folder, once this dependency is resolved this could be reactivated
abstract class SmokeTest extends PHPUnit_Framework_TestCase {
	public function test_valid_ClientConfig() {
		include __DIR__."/../ilse.php";
	}
}
