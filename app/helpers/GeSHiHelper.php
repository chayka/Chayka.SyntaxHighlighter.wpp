<?php

namespace Chayka\SyntaxHighlighter;
use Chayka\WP\Models\PostModel;
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
		global $post;

		$hash = md5($code);
		$codeCacheId = sprintf('Syntax.code.%s.%s', $lang, $hash);
		$codeCache = '';
		$styleCacheId = sprintf('Syntax.style.%s', $lang);
		$styles = '';
		$richPost = null;
		if($post->ID){
			$richPost = PostModel::unpackDbRecord($post);
			$codeCache = $richPost->getMeta($codeCacheId);
			$styles = $richPost->getMeta($styleCacheId);
		}
		if($codeCache){
			return $styles.$codeCache;
		}

		$geshi = new GeSHi($code, $lang);
		$geshi->enable_classes();
		if(!$styles){
			$styles = self::registerHighlighter($lang, $geshi);
		}
		$geshi->set_overall_class('code geshi');
		$code = $geshi->parse_code();
		$code =preg_replace('%(<pre class="[^"]*">)(&nbsp;)?\s*%m', '$1', $code);

		if($richPost){
			$richPost->updateMeta($codeCacheId, $code);
			$richPost->updateMeta($styleCacheId, $styles);
		}

		return $styles.$code;
	}

	/**
	 * Get all the needed styles in the form of
	 * <style type="text/css" id="geshi_lang_...">...</style>
	 *
	 * @return string
	 */
	public static function getStyles(){
		$styles = '';
		foreach(self::$stylesheets as $lang => $geshi){
			$styles.=sprintf('<style type="text/css" id="geshi_lang_%s">%s</style>', $lang, $geshi->get_stylesheet());
		}
		return $styles;
	}

	/**
	 * Output inline html styles for the rendered code blocks
	 */
	public static function renderStyles(){
		echo self::getStyles();
	}

	/**
	 * @param string $lang
	 * @param GeSHi $geshi
	 *
	 * @return string
	 */
	public static function registerHighlighter($lang, $geshi){
		$styles = '';
		if(!count(self::$stylesheets)){
//			add_filter('the_content', function($content){self::getStyles().$content;});
			$styles = sprintf('<style type="text/css" id="geshi_lang_%s">%s</style>', $lang, $geshi->get_stylesheet());
		}
		self::$stylesheets[$lang] = $geshi;
		return $styles;
	}

}