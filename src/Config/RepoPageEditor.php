<?php
/* Copyright (c) 2016 Stefan Hecken <stefan.hecken@concepts-and-training.de>, Extended GPL, see LICENSE */

namespace CaT\Ilse\Config;

/**
 * Configuration for TinyMCE.
 *
 * @author Stefan Hecken <stefan.hecken@concepts-and-training.de>
 *
 * @method int enable()
 * @method int heavyMarked()
 * @method int marked()
 * @method int importand()
 * @method int superscript()
 * @method int subscript()
 * @method int comment()
 * @method int quote()
 * @method int accent()
 * @method int code()
 * @method int latex()
 * @method int footnote()
 * @method int externalLink()
 */
class RepoPageEditor extends Base {
	private $matchings = array("str"=>"heavy_marked"
							 , "emp"=>"marked"
							 , "imp"=>"importand"
							 , "sup"=>"superscript"
							 , "sub"=>"subscript"
							 , "com"=>"comment"
							 , "quot"=>"quote"
							 , "acc"=>"accent"
							 , "code"=>"code"
							 , "tex"=>"latex"
							 , "fn"=>"footnote"
							 , "xln"=>"external_link"
						);

	/**
	 * @inheritdocs
	 */
	public static function fields() {
		return array
			( "enable" => array("int", false)
			, "heavy_marked" => array("int", false)
			, "marked" => array("int", false)
			, "importand" => array("int", false)
			, "superscript" => array("int", false)
			, "subscript" => array("int", false)
			, "comment" => array("int", false)
			, "quote" => array("int", false)
			, "accent" => array("int", false)
			, "code" => array("int", false)
			, "latex" => array("int", false)
			, "footnote" => array("int", false)
			, "external_link" => array("int", false)
			);
	}

	public function valueFor($short) {
		$self = $this;
		foreach ($this->matchings as $key => $value) {
			if($key == $short) {
				return $this->{$value};
			}
		}
	}
}