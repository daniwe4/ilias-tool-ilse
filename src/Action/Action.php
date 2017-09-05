<?php

/* Copyright (c) 2017 Richard Klees <richard.klees@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Action;

/** 
 * An action is something ilse does to the system, named
 * on a level that standard humans should be able to
 * understand. It only performs sideeffects.
 */
interface Action {
	/**
	 * @return void
	 */
	public function perform();
}
