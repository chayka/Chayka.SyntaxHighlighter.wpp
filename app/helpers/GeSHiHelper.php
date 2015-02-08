<?php

namespace Chayka\SyntaxHighlighter;
use GeSHi;


/**
 * Class GeSHiHelper
 * @package Chayka\SyntaxHighlighter
 */
class GeSHiHelper {

	/**
	 * array of registered stylesheets
	 * @var array
	 */
	protected static $stylesheets = [ ];

	/**
	 * Highlight code
	 *
	 * @param string $code
	 * @param string $lang
	 *
	 * @return string
	 */
	public static function highlight($code, $lang){
		$geshi = new GeSHi($code, $lang);
		$geshi->enable_classes();
		self::registerHighlighter($lang, $geshi);

		return $geshi->parse_code();
	}

	/**
	 * Output inline html styles for the rendered code blocks
	 */
	public static function renderStyles(){
		foreach(self::$stylesheets as $lang => $geshi){
			printf('<style type="text/css" id="geshi_lang_%s">%s</style>', $lang, $geshi->get_stylesheet());
		}
	}

	/**
	 * @param string $lang
	 * @param GeSHi $geshi
	 */
	public static function registerHighlighter($lang, $geshi){
		if(!count(self::$stylesheets)){
			add_action('wp_head', function(){self::renderStyles();});
		}
		self::$stylesheets[$lang] = $geshi;
	}

}