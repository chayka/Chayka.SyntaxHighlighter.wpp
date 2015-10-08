<?php

namespace Chayka\SyntaxHighlighter;
use Chayka\Helpers\Util;
use Chayka\WP\Models\PostModel;
use GeSHi;


/**
 * Class GeSHiHelper
 * @package Chayka\SyntaxHighlighter
 */
class GeSHiHelper {

    /**
     * The pattern that is used to match formatted code snippets
     * <pre class="code _lang_">...</pre>
     */
    const CODE_SNIPPET_PATTERN = '/<pre\s+class="[^"]*code(\s+[\w\d_]+)"[^>]*>(.*)<\/pre>/imUs';
    const CACHE_META_KEY = 'Syntax.Highlighted';

	/**
	 * array of registered stylesheets
	 * @var array
	 */
	protected static $stylesheets = [ ];

	/**
	 * Generate post content highlighting and store it to meta.
	 * Should be called when post is saved.
	 * Later, when post is shown, this metadata should be used to replace code content.
	 *
	 * @param PostModel $post
	 */
	public static function generatePostHighlighting($post){
        $cache = [];
        if(preg_match_all(self::CODE_SNIPPET_PATTERN, $post->getContent(false), $m, PREG_SET_ORDER)){
            foreach($m as $mm){
                list($pre, $lang, $code) = $mm;
//                print_r($mm);
                $lang = trim($lang);
                $hash = md5($pre);
                $cache[$hash] = self::highlight($code, $lang);
            }
        }

        if(count($cache)){
            $meta = [
                'code' => $cache,
                'style' => self::getStyles()
            ];
//            print_r($meta);
            $post->updateMeta(self::CACHE_META_KEY, $meta);
        }else{
            $post->deleteMeta(self::CACHE_META_KEY);
        }
	}

    /**
     * Filter applied to the content to replace code snippets with highlighted ones
     *
     * @param $content
     *
     * @return string
     */
    public static function theContent($content){
        global $post;
        if($post){
            $richPost = PostModel::unpackDbRecord($post);
            $cache = $richPost->getMeta(self::CACHE_META_KEY);
            if(isset($cache['code'])){
                $content = preg_replace_callback(self::CODE_SNIPPET_PATTERN, function ($matches) use ($cache){
                    $pre = $matches[0];
                    $hash = md5($pre);
                    return Util::getItem($cache['code'], $hash, $pre);
                }, $content);
                if(isset($cache['style'])){
                    $content = $cache['style']."\n".$content;
                }
            }
        }
        return $content;
    }

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

		$geshi->set_overall_class('code');
		$code = $geshi->parse_code();
		$code =preg_replace('%(<pre class="[^"]*">)(&nbsp;)?\s*%m', '$1', $code);

		return $code;
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
			$styles = sprintf('<style type="text/css" id="geshi_lang_%s">%s</style>', $lang, $geshi->get_stylesheet());
		}
		self::$stylesheets[$lang] = $geshi;
		return $styles;
	}

}