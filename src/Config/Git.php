<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for the git repo and branch name to get ILIAS from.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method string gitUrl()
 * @method string gitBranchName()
 */
class Git extends Base {
	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "url"			=> array("string", false)
			, "branch"		=> array("string", false)
			, "hash"		=> array("string", true)
			);
	}
}