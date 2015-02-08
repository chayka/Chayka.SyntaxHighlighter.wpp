<?php

namespace Chayka\SyntaxHighlighter;

use Chayka\WP\MVC\Controller;
use Chayka\Helpers\InputHelper;

/**
 * Class ShortcodeController
 * @package Chayka\SyntaxHighlighter
 */
class ShortcodeController extends Controller{

    public function init(){
        // NlsHelper::load('main');
        // InputHelper::captureInput();
    }

    public function codeAction(){
    	$content = InputHelper::getParam('content');
    	$lang = InputHelper::getParam('lang');

	    if($lang){
		    $content = GeSHiHelper::highlight($content, $lang);
	    }

		$this->view->assign('content', $content);
		$this->view->assign('lang', $lang);
    }
}